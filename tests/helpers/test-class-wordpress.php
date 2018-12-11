<?php
/**
 * The WordPress helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\WordPress;

/**
 * TestWordPress class.
 */
class TestWordPress extends UnitTestCase {

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
	 * Strip all shortcodes active or orphan.
	 *
	 * @param  string $content Content to remove shortcodes from.
	 * @return string
	 */
	public static function remove_all_shortcodes( $content ) {
		if ( ! Str::contains( '[', $content ) ) {
			return $content;
		}

		return preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content );
	}

	/**
	 * Check if table exists in db or not.
	 *
	 * @param  string $table_name Table name to check for existance.
	 * @return bool
	 */
	public static function check_table_exists( $table_name ) {
		global $wpdb;

		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->prefix . $table_name ) ) ) === $wpdb->prefix . $table_name ) {
			return true;
		}

		return false;
	}
}
