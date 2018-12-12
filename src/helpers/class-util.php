<?php
/**
 * The Helper class that provides easy access to useful common php functions.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Helpers;

/**
 * Util class.
 */
class Util {

	/**
	 * Dumps the content of the given variable and exits the script.
	 *
	 * @codeCoverageIgnore
	 */
	public static function dd() {
		array_map(
			function ( $item ) {
				self::dump( $item );
				echo "\n";
			},
			func_get_args()
		);
		die();
	}

	/**
	 * Dumps the content of the given variable. Script does NOT stop after call.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param mixed $var The variable to dump.
	 */
	public static function dump( $var ) {
		if ( is_bool( $var ) ) {
			$var = 'bool(' . ( $var ? 'true' : 'false' ) . ')';
		}

		highlight_string( "<?php\n" . var_export( $var, true ) );
	}

	/**
	 * Get field from query string.
	 *
	 * @param  string $id      Field id to get.
	 * @param  mixed  $default Default value to return if field is not found.
	 * @return mixed
	 */
	public static function param_get( $id, $default = false ) {
		return isset( $_GET[ $id ] ) ? $_GET[ $id ] : $default;
	}

	/**
	 * Get field from FORM post.
	 *
	 * @param  string $id      Field id to get.
	 * @param  mixed  $default Default value to return if field is not found.
	 * @return mixed
	 */
	public static function param_post( $id, $default = false ) {
		return isset( $_POST[ $id ] ) ? $_POST[ $id ] : $default;
	}

	/**
	 * Get field from request.
	 *
	 * @param  string $id      Field id to get.
	 * @param  mixed  $default Default value to return if field is not found.
	 * @return mixed
	 */
	public static function param_request( $id, $default = false ) {
		return isset( $_REQUEST[ $id ] ) ? $_REQUEST[ $id ] : $default;
	}
}
