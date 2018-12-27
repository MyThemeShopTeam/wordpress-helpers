<?php
/**
 * The URL helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Helpers;

use MyThemeShop\Helpers\Str;

/**
 * Url class.
 */
class Url {

	/**
	 * Simple check for validating a URL, it must start with http:// or https://.
	 * and pass FILTER_VALIDATE_URL validation.
	 *
	 * @param  string $url to check.
	 * @return bool
	 */
	public static function is_url( $url ) {
		if ( ! is_string( $url ) ) {
			return false;
		}

		// Must start with http:// or https://.
		if ( 0 !== strpos( $url, 'http://' ) && 0 !== strpos( $url, 'https://' ) && 0 !== strpos( $url, '//' ) ) {
			return false;
		}

		// Check for scheme first, if it's missing then add it.
		if ( 0 === strpos( $url, '//' ) ) {
			$url = 'http:' . $url;
		}

		// Must pass validation.
		return false !== filter_var( trailingslashit( $url ), FILTER_VALIDATE_URL ) ? true : false;
	}

	/**
	 * Check whether a url is relative.
	 *
	 * @param  string $url URL string to check.
	 * @return bool
	 */
	public static function is_relative( $url ) {
		return ( 0 !== strpos( $url, 'http' ) && 0 !== strpos( $url, '//' ) );
	}

	/**
	 * Checks whether a url is external.
	 *
	 * @param string $url    URL string to check. This should be a absolute URL.
	 * @param string $domain If wants to use some other domain not home_url().
	 * @return bool
	 */
	public static function is_external( $url, $domain = false ) {
		if ( empty( $url ) || '#' === $url[0] || '/' === $url[0] ) { // Link to current page or relative link.
			return false;
		}

		$domain = self::get_domain( $domain ? $domain : home_url() );
		if ( Str::contains( $domain, $url ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get current url.
	 *
	 * @return string
	 */
	public static function get_current_url() {
		return self::get_scheme() . '://' . self::get_host() . self::get_port() . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get url scheme.
	 *
	 * @return string
	 */
	public static function get_scheme() {
		return is_ssl() ? 'https' : 'http';
	}

	/**
	 * Some setups like HTTP_HOST, some like SERVER_NAME, it's complicated.
	 *
	 * @link http://stackoverflow.com/questions/2297403/http-host-vs-server-name
	 *
	 * @return string the HTTP_HOST or SERVER_NAME
	 */
	public static function get_host() {
		if ( isset( $_SERVER['HTTP_HOST'] ) && $_SERVER['HTTP_HOST'] ) {
			return $_SERVER['HTTP_HOST'];
		}
		if ( isset( $_SERVER['SERVER_NAME'] ) && $_SERVER['SERVER_NAME'] ) {
			return $_SERVER['SERVER_NAME'];
		}
		return '';
	}

	/**
	 * Get current request port.
	 *
	 * @return string
	 */
	public static function get_port() {
		$has_port = isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] && ! in_array( $_SERVER['SERVER_PORT'], [ '80', '443' ] );
		return $has_port ? ':' . $_SERVER['SERVER_PORT'] : '';
	}

	/**
	 * Get parent domain
	 *
	 * @param  string $url Url to parse.
	 * @return string
	 */
	public static function get_domain( $url ) {
		$pieces = wp_parse_url( $url );
		$domain = isset( $pieces['host'] ) ? $pieces['host'] : '';

		if ( Str::contains( 'localhost', $domain ) ) {
			return 'localhost';
		}

		if ( preg_match( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,15})$/i', $domain, $regs ) ) {
			return $regs['domain'];
		}

		return false;
	}
}
