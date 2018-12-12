<?php
/**
 * The WordPress helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Helpers;

use MyThemeShop\Helpers\Str;

/**
 * WordPress class.
 */
class WordPress {

	/**
	 * Get roles.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param string $output How to return roles.
	 * @return array
	 */
	public static function get_roles( $output = 'names' ) {
		$wp_roles = wp_roles();

		if ( 'names' !== $output ) {
			return $wp_roles->roles;
		}

		return $wp_roles->get_names();
	}

	/**
	 * Retrieves the sitename.
	 *
	 * @return string
	 */
	public static function get_site_name() {
		return wp_strip_all_tags( get_bloginfo( 'name' ), true );
	}

	/**
	 * Get action from request.
	 *
	 * @return bool|string
	 */
	public static function get_request_action() {
		if ( empty( $_REQUEST['action'] ) ) {
			return false;
		}

		if ( '-1' === $_REQUEST['action'] && ! empty( $_REQUEST['action2'] ) ) {
			$_REQUEST['action'] = $_REQUEST['action2'];
		}

		return sanitize_key( $_REQUEST['action'] );
	}

	/**
	 * Strip all shortcodes active or orphan.
	 *
	 * @param  string $content Content to remove shortcodes from.
	 * @return string
	 */
	public static function strip_shortcodes( $content ) {
		if ( ! Str::contains( '[', $content ) ) {
			return $content;
		}

		return preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content );
	}
}
