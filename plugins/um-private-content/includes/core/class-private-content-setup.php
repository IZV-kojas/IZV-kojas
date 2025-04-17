<?php namespace um_ext\um_private_content\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Private_Content_Setup
 * @package um_ext\um_private_content\core
 */
class Private_Content_Setup {

	/**
	 * @var array
	 */
	public $settings_defaults;

	/**
	 * Private_Content_Setup constructor.
	 */
	public function __construct() {
		//settings defaults
		$this->settings_defaults = array(
			'profile_tab_private_content' => 1,
			'tab_private_content_title'   => __( 'Private Content', 'um-private-content' ),
			'tab_private_content_icon'    => 'um-faicon-eye-slash',
			'private_content_template'    => '',
			'tab_private_content_order'   => 'user',
		);
	}

	public function set_default_settings() {
		$options = get_option( 'um_options', array() );

		foreach ( $this->settings_defaults as $key => $value ) {
			//set new options to default
			if ( ! isset( $options[ $key ] ) ) {
				$options[ $key ] = $value;
			}
		}

		update_option( 'um_options', $options );
	}

	/**
	 *
	 */
	public function create_private_posts() {
		$version = get_option( 'um_private_content_version' );
		if ( $version ) {
			return;
		}

		register_post_type(
			'um_private_content',
			array(
				'labels'          => array(
					'name'               => __( 'Private Contents', 'um-private-content' ),
					'singular_name'      => __( 'Private Content', 'um-private-content' ),
					'add_new'            => __( 'Add New Private Content', 'um-private-content' ),
					'add_new_item'       => __( 'Add New Private Content', 'um-private-content' ),
					'edit_item'          => __( 'Edit Private Content', 'um-private-content' ),
					'not_found'          => __( 'You did not create any private contents yet', 'um-private-content' ),
					'not_found_in_trash' => __( 'Nothing found in Trash', 'um-private-content' ),
					'search_items'       => __( 'Search Private Contents', 'um-private-content' ),
				),
				'show_ui'         => true,
				'show_in_menu'    => false,
				'public'          => false,
				'supports'        => array( 'editor' ),
				'capability_type' => 'page',
			)
		);

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
				}
			}
		}
	}

	/**
	 *
	 */
	public function run_setup() {
		$this->set_default_settings();
		$this->create_private_posts();
	}
}
