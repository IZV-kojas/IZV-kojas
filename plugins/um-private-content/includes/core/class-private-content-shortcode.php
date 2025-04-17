<?php
namespace um_ext\um_private_content\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Private_Content_Shortcode
 * @package um_ext\um_private_content\core
 */
class Private_Content_Shortcode {

	/**
	 * Private_Content_Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'um_private_content', array( &$this, 'private_content_shortcode' ) );
	}

	/**
	 * @param array $args
	 * @return string
	 */
	public function private_content_shortcode( $args = array() ) {
		if ( ! is_user_logged_in() ) {
			return '';
		}

		$defaults = array(
			'for' => '',
		);

		$args = shortcode_atts( $defaults, $args, 'um_private_content' );

		um_fetch_user( get_current_user_id() );

		$private_content = array();

		if ( 'role' === $args['for'] ) {
			$private_content = $this->get_roles_content_ids( get_current_user_id() );
		} elseif ( 'user' === $args['for'] ) {
			$private_post_id = get_user_meta( um_user( 'ID' ), '_um_private_content_post_id', true );
			$private_content = array(
				array(
					'post_id' => $private_post_id,
				),
			);
		} else {
			$private_content = $this->get_roles_content_ids( get_current_user_id() );

			$private_post_id = get_user_meta( um_user( 'ID' ), '_um_private_content_post_id', true );
			if ( ! empty( $private_post_id ) ) {
				$order   = UM()->options()->get( 'tab_private_content_order' );
				$private = array(
					array(
						'post_id' => $private_post_id,
					),
				);
				if ( ! empty( $private_content ) ) {
					if ( 'role' === $order ) {
						$private_content = array_merge( $private_content, $private );
					} else {
						$private_content = array_merge( $private, $private_content );
					}
				} else {
					$private_content = $private;
				}
			}
		}

		if ( ! empty( $private_content ) ) {
			ob_start();

			foreach ( $private_content as $content ) {
				$post = get_post( $content['post_id'] );
				if ( empty( $post ) || empty( $post->post_content ) ) {
					continue;
				}

				setup_postdata( $post );
				the_content();
				wp_reset_postdata();
			}

			return ob_get_clean();
		}

		return '';
	}

	public function get_roles_content_ids( $user_id ) {
		$private_content = array();

		$roles = UM()->roles()->get_all_user_roles( $user_id );

		foreach ( $roles as $role_key ) {
			if ( 0 === strpos( $role_key, 'um_' ) ) {
				$role_key = substr( $role_key, 3 );
			}
			$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
			if ( ! empty( $role_meta['_um_private_content_post_id'] ) ) {
				$priority = ! empty( $role_meta['_um_priority'] ) ? $role_meta['_um_priority'] : 0;

				$private_content[] = array(
					'priority' => $priority,
					'post_id'  => $role_meta['_um_private_content_post_id'],
				);
			}
		}

		usort(
			$private_content,
			function( $a, $b ) {
				if ( $a['priority'] === $b['priority'] ) {
					return 0;
				}
				return ( $a['priority'] > $b['priority'] ) ? -1 : 1;
			}
		);

		return $private_content;
	}
}
