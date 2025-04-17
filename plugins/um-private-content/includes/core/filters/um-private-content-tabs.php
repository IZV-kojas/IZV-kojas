<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a main tab to display forum activity in profile
 *
 * @param $tabs
 *
 * @return mixed
 */
function um_private_content_add_tab( $tabs ) {
	$tab_title = UM()->options()->get( 'tab_private_content_title' );
	$tab_title = ! empty( $tab_title ) ? $tab_title : __( 'Private Content', 'um-private-content' );

	$tab_icon = UM()->options()->get( 'tab_private_content_icon' );
	$tab_icon = ! empty( $tab_icon ) ? $tab_icon : 'um-faicon-eye-slash';

	$tabs['private_content'] = array(
		'name'            => $tab_title,
		'icon'            => $tab_icon,
		'default_privacy' => 2, // to provide an ability to see the private content from administrator
	);

	return $tabs;
}
add_filter( 'um_profile_tabs', 'um_private_content_add_tab', 1000, 1 );

/**
 * Add tabs based on user
 *
 * @param $tabs
 *
 * @return mixed
 */
function um_private_content_user_add_tab( $tabs ) {
	if ( empty( $tabs['private_content'] ) ) {
		return $tabs;
	}

	// to provide an ability to see the private content from administrator
	if ( ! um_is_myprofile() && ! ( is_user_logged_in() && current_user_can( 'administrator' ) ) ) {
		unset( $tabs['private_content'] );
		return $tabs;
	}

	$hide_tab = false;

	$user_id         = um_user( 'ID' );
	$private_post_id = get_user_meta( $user_id, '_um_private_content_post_id', true );

	if ( empty( $private_post_id ) ) {
		$hide_tab = true;
	} else {
		$post = get_post( $private_post_id );
		if ( empty( $post ) || empty( $post->post_content ) ) {
			$hide_tab = true;
		}
	}

	if ( true === $hide_tab ) {
		$roles = UM()->roles()->get_all_user_roles( $user_id );

		foreach ( $roles as $role_key ) {
			if ( 0 === strpos( $role_key, 'um_' ) ) {
				$role_key = substr( $role_key, 3 );
			}
			$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
			if ( ! empty( $role_meta['_um_private_content_post_id'] ) ) {
				$post = get_post( $role_meta['_um_private_content_post_id'] );
				if ( ! empty( $post ) && ! empty( $post->post_content ) ) {
					$hide_tab = false;
					break;
				}
			}
		}
	}

	if ( true === $hide_tab ) {
		unset( $tabs['private_content'] );
	}

	return $tabs;
}
add_filter( 'um_user_profile_tabs', 'um_private_content_user_add_tab', 1000, 1 );

/**
 * Default private content tab
 *
 * @param $args
 */
function um_profile_content_private_content( $args ) {
	$private_content = array();
	$output          = '';

	$user_id       = um_user( 'ID' );
	$roles         = UM()->roles()->get_all_user_roles( $user_id );
	$role_contents = array();

	foreach ( $roles as $role_key ) {
		if ( 0 === strpos( $role_key, 'um_' ) ) {
			$role_key = substr( $role_key, 3 );
		}
		$role_meta = get_option( 'um_role_' . $role_key . '_meta' );
		if ( ! empty( $role_meta['_um_private_content_post_id'] ) ) {
			$priority = ! empty( $role_meta['_um_priority'] ) ? $role_meta['_um_priority'] : 0;

			$role_contents[] = array(
				'priority' => $priority,
				'post_id'  => $role_meta['_um_private_content_post_id'],
			);
		}
	}

	usort(
		$role_contents,
		function( $a, $b ) {
			if ( $a['priority'] === $b['priority'] ) {
				return 0;
			}
			return ( $a['priority'] > $b['priority'] ) ? -1 : 1;
		}
	);

	$private_post_id = get_user_meta( um_user( 'ID' ), '_um_private_content_post_id', true );
	if ( ! empty( $private_post_id ) ) {
		$order   = UM()->options()->get( 'tab_private_content_order' );
		$private = array(
			array(
				'post_id' => $private_post_id,
			),
		);
		if ( 'role' === $order ) {
			$private_content = array_merge( $role_contents, $private );
		} else {
			$private_content = array_merge( $private, $role_contents );
		}
	}

	if ( ! empty( $private_content ) ) {
		foreach ( $private_content as $content ) {
			$post = get_post( $content['post_id'] );
			if ( ! empty( $post ) ) {
				$output .= apply_filters( 'the_content', $post->post_content );
			}
		}
	}

	echo wp_kses( $output, UM()->get_allowed_html( 'templates' ) );
}
add_action( 'um_profile_content_private_content', 'um_profile_content_private_content' );

/**
 * Fix formatting issue on private content - helpscout#26171
 * @param string $content
 * @return string
 */
if ( ! function_exists( 'um_profile_content_nl2br' ) ) {
	function um_profile_content_nl2br( $pages, $post ) {
		if ( 'um_private_content' === $post->post_type ) {
			foreach ( $pages as &$page ) {
				$page = preg_replace( '/\n\s*\n/im', '<br>', $page );
			}
		}

		return $pages;
	}
}
add_filter( 'content_pagination', 'um_profile_content_nl2br', 20, 2 );
