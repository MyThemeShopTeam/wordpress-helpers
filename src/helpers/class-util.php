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
}
