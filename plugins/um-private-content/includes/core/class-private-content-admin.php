<?php
namespace um_ext\um_private_content\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Private_Content_Admin
 * @package um_ext\um_private_content\core
 */
class Private_Content_Admin {

	/**
	 * Private_Content_Admin constructor.
	 */
	public function __construct() {
		add_action( 'um_admin_user_row_actions', array( &$this, 'um_admin_user_row_actions' ), 100, 2 );
		add_filter( 'um_role_row_actions', array( &$this, 'role_row_actions' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ), 0 );

		add_action( 'load-edit.php', array( &$this, 'hide_private_content_list' ) );
		add_action( 'load-post-new.php', array( &$this, 'hide_private_content_add' ) );
		add_action( 'load-post.php', array( &$this, 'hide_private_content_add_button' ) );
		add_action( 'edit_form_top', array( &$this, 'add_username' ), 10, 1 );

		add_action( 'wp_ajax_um_generate_private_pages', array( &$this, 'ajax_generate_private_pages' ) );
		add_action( 'wp_ajax_um_generate_private_roles_pages', array( &$this, 'ajax_generate_private_roles_pages' ) );

		add_action( 'um_admin_create_notices', array( &$this, 'add_admin_notice' ) );
		add_filter( 'um_role_edit_data', array( &$this, 'um_role_edit_data' ), 10, 2 );

		add_action( 'um_after_delete_role_meta', array( &$this, 'after_delete_role_meta' ), 10, 2 );
		add_action( 'um_after_delete_role', array( &$this, 'after_delete_role' ), 10, 2 );
	}

	/**
	 * Add notice that a predefined template can be used.
	 *
	 * @since   2.0.6
	 * @version 2.0.7
	 */
	public function add_admin_notice() {
		global $pagenow, $um_predefined_post_content;
		// phpcs:disable WordPress.Security.NonceVerification -- getting value from GET line
		if ( 'post.php' === $pagenow && ! empty( $_GET['post'] ) ) {
			$post = get_post( absint( $_GET['post'] ) );
			if ( ! empty( $post ) && ! is_wp_error( $post ) && 'um_private_content' === $post->post_type && empty( $post->post_content ) ) {

				$um_predefined_post_content = stripslashes( trim( (string) UM()->options()->get( 'private_content_template' ) ) );
				if ( empty( $um_predefined_post_content ) ) {
					return;
				}

				ob_start();
				?>
				<p><?php echo wp_kses( __( 'You see the predefined content template below. <b>Update</b> private content to apply this template for the user. You can edit this template before saving or leave it as it is.', 'um-private-content' ), UM()->get_allowed_html( 'admin_notice' ) ); ?></p>
				<?php
				$message = ob_get_clean();

				UM()->admin()->notices()->add_notice(
					'um_private_content_notice',
					array(
						'class'       => 'updated',
						'message'     => $message,
						'dismissible' => false,
					),
					10
				);
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification -- getting value from GET line

		$empty_users = get_users(
			array(
				'meta_query' => array(
					array(
						'key'     => '_um_private_content_post_id',
						'compare' => 'NOT EXISTS',
					),
				),
				'number'     => -1,
				'fields'     => 'ids',
			)
		);

		$empty_roles = false;
		$role_keys   = array_keys( UM()->roles()->get_roles() );
		if ( ! empty( $role_keys ) ) {
			foreach ( $role_keys as $role_key ) {
				if ( 0 === strpos( $role_key, 'um_' ) ) {
					$role_key = substr( $role_key, 3 );
				}
				$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
				if ( empty( $role_meta['_um_private_content_post_id'] ) ) {
					$empty_roles = true;
					break;
				}
			}
		}

		$link = add_query_arg(
			array(
				'page'    => 'um_options',
				'tab'     => 'extensions',
				'section' => 'private-content',
			),
			admin_url( 'admin.php' )
		);

		if ( ! empty( $empty_users ) ) {
			ob_start();
			?>
			<p>
				<?php
				// translators: %s - link to the Private Content settings.
				echo wp_kses( sprintf( __( 'You have users without Private Content posts. It\'s highly recommended to <a href="%s">create</a> Private Content posts if you need to show it for these users.', 'um-private-content' ), $link ), UM()->get_allowed_html( 'admin_notice' ) );
				?>
			</p>
			<?php
			$message = ob_get_clean();

			UM()->admin()->notices()->add_notice(
				'um_private_content_empty_users_notice',
				array(
					'class'       => 'notice-warning',
					'message'     => $message,
					'dismissible' => true,
				)
			);
		}

		if ( $empty_roles ) {
			ob_start();
			?>
			<p>
				<?php
				// translators: %s - link to the Private Content settings.
				echo wp_kses( sprintf( __( 'You have user roles without Private Content posts. It\'s highly recommended to <a href="%s">create</a> Private Content posts if you need to show it for these user roles.', 'um-private-content' ), $link ), UM()->get_allowed_html( 'admin_notice' ) );
				?>
			</p>
			<?php
			$message = ob_get_clean();

			UM()->admin()->notices()->add_notice(
				'um_private_content_empty_user_roles_notice',
				array(
					'class'       => 'notice-warning',
					'message'     => $message,
					'dismissible' => true,
				)
			);
		}
	}

	/**
	 * Custom row actions for users page
	 *
	 * @version 2.0.6
	 *
	 * @param \WP_Post $post
	 */
	public function add_username( $post ) {
		if ( 'um_private_content' === $post->post_type ) {
			global $wpdb, $um_predefined_post_content;

			// phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $_GET['role'] ) && '' !== $_GET['role'] ) {
				$role_key  = $_GET['role']; // phpcs:ignore WordPress.Security.NonceVerification
				$role_name = UM()->roles()->get_role_name( $role_key );
				if ( $role_name ) {
					// translators: %s is the role name.
					echo '<h2 style="margin: 0;">' . esc_html( sprintf( __( 'Private Content for %s', 'um-private-content' ), $role_name ) ) . '</h2>';
				}
			} else {
				$user_id = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT um.user_id
						FROM {$wpdb->usermeta} um
						WHERE meta_key = '_um_private_content_post_id' AND
							  meta_value = %d",
						$post->ID
					)
				);

				$user = get_userdata( $user_id );
				if ( ! empty( $user->user_login ) ) {
					// translators: %s is the username.
					echo '<h2 style="margin: 0;">' . esc_html( sprintf( __( 'Private Content for %s', 'um-private-content' ), $user->user_login ) ) . '</h2>';
				}
			}

			// Show predefined content if the content is empty.
			if ( ! empty( $um_predefined_post_content ) ) {
				$post->post_content = $um_predefined_post_content;
			}
		}
	}

	/**
	 * Custom row actions for users page.
	 *
	 * @param array $actions
	 * @param int   $user_object user ID
	 * @return array
	 */
	public function um_admin_user_row_actions( $actions, $user_object ) {
		$private_content_link = UM()->Private_Content()->get_private_content_post_link( $user_object );
		if ( $private_content_link ) {
			$actions['private-content'] = '<a class="" href="' . esc_url( $private_content_link ) . '">' . __( 'Private Content', 'um-private-content' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * @param array  $actions
	 * @param string $id
	 *
	 * @return array
	 */
	public function role_row_actions( $actions, $id ) {
		$role_meta = get_option( 'um_role_' . $id . '_meta' );
		if ( ! empty( $role_meta['_um_private_content_post_id'] ) ) {
			$post_id = $role_meta['_um_private_content_post_id'];
			$post    = get_post( $post_id );
			if ( ! empty( $post ) ) {
				$url = get_edit_post_link( $post->ID );

				$key = '';
				if ( array_key_exists( 'delete', $actions ) ) {
					$key = 'delete';
				} elseif ( array_key_exists( 'reset', $actions ) ) {
					$key = 'reset';
				}

				if ( ! empty( $key ) ) {
					$actions = UM()->array_insert_before(
						$actions,
						$key,
						array(
							'private-content' => '<a href="' . esc_url( $url ) . '&role=' . esc_attr( $id ) . '">' . esc_html__( 'Private Content', 'um-private-content' ) . '</a>',
						)
					);
				}
			}
		}

		return $actions;
	}

	/**
	 *
	 */
	public function admin_scripts() {
		$suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || defined( 'UM_SCRIPT_DEBUG' ) ) ? '' : '.min';

		wp_register_script( 'um_private_content', um_private_content_url . 'assets/js/um-private-content' . $suffix . '.js', array( 'jquery', 'wp-util', 'um_admin_global' ), um_private_content_version, true );
		wp_register_style( 'um_private_content', um_private_content_url . 'assets/css/settings' . $suffix . '.css', array(), um_private_content_version );

		if ( UM()->admin()->is_um_screen() ) {
			wp_enqueue_script( 'um_private_content' );
			wp_enqueue_style( 'um_private_content' );
		}
	}

	public function hide_private_content_list() {
		global $typenow;

		if ( 'um_private_content' === $typenow ) {
			wp_safe_redirect( admin_url( 'users.php' ) );
			exit;
		}
	}

	public function hide_private_content_add() {
		global $typenow;

		if ( 'um_private_content' === $typenow ) {
			wp_safe_redirect( admin_url( 'users.php' ) );
			exit;
		}
	}

	public function hide_private_content_add_button() {
		global $typenow;

		if ( 'um_private_content' === $typenow ) {
			?>
			<style type="text/css">
				#minor-publishing {
					display:none;
				}

				#delete-action {
					display:none;
				}

				.page-title-action {
					display:none;
				}
			</style>
			<?php
		}
	}

	/**
	 * AJAX handler for the private content generate. By user.
	 */
	public function ajax_generate_private_pages() {
		UM()->admin()->check_ajax_nonce();

		global $wpdb;

		$private_posts = $wpdb->get_results(
			"SELECT um.meta_value as post_id, um.user_id as user_id
			FROM {$wpdb->usermeta} um
			WHERE meta_key = '_um_private_content_post_id'",
			ARRAY_A
		);

		if ( ! empty( $private_posts ) ) {
			foreach ( $private_posts as $post ) {
				$postdata = get_post( $post['post_id'] );
				if ( empty( $postdata ) || is_wp_error( $postdata ) ) {
					$post_id = wp_insert_post(
						array(
							'post_title'   => 'private_content_' . $post['user_id'],
							'post_type'    => 'um_private_content',
							'post_status'  => 'publish',
							'post_content' => '',
						)
					);

					update_user_meta( $post['user_id'], '_um_private_content_post_id', $post_id );
				}
			}
		}

		$empty_users = get_users(
			array(
				'meta_query' => array(
					array(
						'key'     => '_um_private_content_post_id',
						'compare' => 'NOT EXISTS',
					),
				),
				'number'     => -1,
				'fields'     => 'ids',
			)
		);

		if ( ! empty( $empty_users ) ) {
			foreach ( $empty_users as $user_id ) {
				$post_id = wp_insert_post(
					array(
						'post_title'   => 'private_content_' . $user_id,
						'post_type'    => 'um_private_content',
						'post_status'  => 'publish',
						'post_content' => '',
					)
				);

				update_user_meta( $user_id, '_um_private_content_post_id', $post_id );
			}
		}

		wp_send_json_success( array( 'message' => __( 'Private Content pages was generated successfully', 'um-private-content' ) ) );
	}

	/**
	 * AJAX handler for the private content generate. By user role.
	 */
	public function ajax_generate_private_roles_pages() {
		UM()->admin()->check_ajax_nonce();
		$role_keys = array_keys( UM()->roles()->get_roles() );
		if ( ! empty( $role_keys ) ) {
			foreach ( $role_keys as $role_key ) {
				if ( 0 === strpos( $role_key, 'um_' ) ) {
					$role_key = substr( $role_key, 3 );
				}
				$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
				if ( ! isset( $role_meta['_um_private_content_post_id'] ) ) {
					$post_id = wp_insert_post(
						array(
							'post_title'   => 'private_content_' . $role_key,
							'post_type'    => 'um_private_content',
							'post_status'  => 'publish',
							'post_content' => '',
						)
					);

					$role_meta['_um_private_content_post_id'] = $post_id;
					update_option( 'um_role_' . $role_key . '_meta', $role_meta );
				} else {
					$post_id  = $role_meta['_um_private_content_post_id'];
					$postdata = get_post( $post_id );
					if ( empty( $postdata ) || is_wp_error( $postdata ) ) {
						$post_id = wp_insert_post(
							array(
								'post_title'   => 'private_content_' . $role_key,
								'post_type'    => 'um_private_content',
								'post_status'  => 'publish',
								'post_content' => '',
							)
						);

						$role_meta['_um_private_content_post_id'] = $post_id;
						update_option( 'um_role_' . $role_key . '_meta', $role_meta );
					}
				}
			}
		}

		wp_send_json_success( array( 'message' => __( 'Private Content pages for user roles was generated successfully', 'um-private-content' ) ) );
	}

	/**
	 * @param array  $data
	 * @param string $role_key
	 *
	 * @return array
	 */
	public function um_role_edit_data( $data, $role_key ) {
		if ( 0 === strpos( $role_key, 'um_' ) ) {
			$role_key = substr( $role_key, 3 );
		}
		$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
		if ( ! empty( $role_meta['_um_private_content_post_id'] ) ) {
			$data['_um_private_content_post_id'] = $role_meta['_um_private_content_post_id'];
		}

		return $data;
	}

	/**
	 * @param $role_key
	 * @param array $role_meta
	 */
	public function after_delete_role_meta( $role_key, $role_meta ) {
		if ( ! array_key_exists( '_um_private_content_post_id', $role_meta ) ) {
			return;
		}

		$post_id = $role_meta['_um_private_content_post_id'];
		if ( ! empty( $post_id ) ) {
			$data = array();
			$post = get_post( $post_id );
			if ( $post ) {
				$post_data = array(
					'ID'           => $post_id,
					'post_content' => '',
				);
				wp_update_post( $post_data );
			}

			$data['_um_private_content_post_id'] = $post_id;
			update_option( "um_role_{$role_key}_meta", $data );
		}
	}

	/**
	 * @param string $role_key
	 * @param array  $role_meta
	 */
	public function after_delete_role( $role_key, $role_meta ) {
		if ( ! array_key_exists( '_um_private_content_post_id', $role_meta ) ) {
			return;
		}

		$post_id = $role_meta['_um_private_content_post_id'];
		if ( ! empty( $post_id ) ) {
			wp_delete_post( $post_id, true );
		}
	}
}
