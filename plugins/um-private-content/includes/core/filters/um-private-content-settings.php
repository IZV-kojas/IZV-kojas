<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Extend UM settings
 *
 * @param $settings
 * @return mixed
 */
function um_private_content_settings( $settings ) {
	$settings['licenses']['fields'][] = array(
		'id'        => 'um_private_content_license_key',
		'label'     => __( 'Private Content License Key', 'um-private-content' ),
		'item_name' => 'Private Content',
		'author'    => 'Ultimate Member',
		'version'   => um_private_content_version,
	);

	$settings['extensions']['sections']['private-content'] = array(
		'title'  => __( 'Private Content', 'um-private-content' ),
		'fields' => array(
			array(
				'id'      => 'private_content_generate',
				'type'    => 'ajax_button',
				'label'   => __( 'Generate pages', 'um-private-content' ),
				'value'   => __( 'Generate pages for existing users', 'um-private-content' ),
				'tooltip' => __( 'Generate pages for already existing users', 'um-private-content' ),
				'size'    => 'small',
			),
			array(
				'id'      => 'private_content_roles_generate',
				'type'    => 'ajax_button',
				'label'   => __( 'Generate pages for user roles', 'um-private-content' ),
				'value'   => __( 'Generate pages for existing user roles', 'um-private-content' ),
				'tooltip' => __( 'Generate pages for already existing user roles', 'um-private-content' ),
				'size'    => 'small',
			),
			array(
				'id'      => 'tab_private_content_title',
				'type'    => 'text',
				'label'   => __( 'Private Content Tab Title', 'um-private-content' ),
				'tooltip' => __( 'This is the title of the tab for show user\'s private content', 'um-private-content' ),
			),
			array(
				'id'      => 'tab_private_content_icon',
				'type'    => 'icon',
				'label'   => __( 'Private Content Tab Icon', 'um-private-content' ),
				'tooltip' => __( 'This is the icon of the tab for show user\'s private content', 'um-private-content' ),
				'class'   => 'private_content_icon',
			),
			array(
				'id'      => 'tab_private_content_order',
				'type'    => 'select',
				'label'   => __( 'Private Content Tab Order', 'um-private-content' ),
				'tooltip' => __( 'if the user has multiple roles - the order will be determined by role priority', 'um-private-content' ),
				'class'   => 'private_content_order',
				'options' => array(
					'user' => __( 'User private content first', 'um-private-content' ),
					'role' => __( 'Role private content first', 'um-private-content' ),
				),
			),
			array(
				'id'      => 'private_content_template',
				'type'    => 'wp_editor',
				'label'   => __( 'Predefined content template', 'um-private-content' ),
				'tooltip' => __( 'If the Private Content post content is empty, this template is used as a predefined content for Private Content post draft. Convenient to use if you need to create similar private content for users.', 'um-private-content' ),
			),
		),
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_private_content_settings', 10, 1 );
