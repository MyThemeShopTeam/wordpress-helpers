<?php
/**
 * The JSON manager handles json output to admin and frontend.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop;

/**
 * Json_Manager class.
 */
class Json_Manager {

	/**
	 * Data.
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * Construct
	 */
	public function __construct() {
		$hook = is_admin() ? 'admin_footer' : 'wp_footer';
		add_action( $hook, [ $this, 'print' ], 0 );
	}

	/**
	 * Print data.
	 */
	public function print() {
		$script = '';
		foreach ( $this->data as $object_name => $l10n ) {
			foreach ( (array) $l10n as $key => $value ) {
				if ( ! is_scalar( $value ) ) {
					continue;
				}

				$l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
			}

			$script .= "var $object_name = " . wp_json_encode( $l10n ) . ';' . PHP_EOL;
		}

		if ( ! $script ) {
			return;
		}

		echo "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5.
		echo "/* <![CDATA[ */\n";
		echo "$script\n";
		echo "/* ]]> */\n";
		echo "</script>\n";
	}

	/**
	 * Add something to JSON object.
	 *
	 * @param string $key         Unique identifier.
	 * @param mixed  $value       The data itself can be either a single or an array.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 */
	public function add( $key, $value, $object_name ) {

		if ( empty( $key ) ) {
			return;
		}

		// If key doesn't exists.
		if ( ! isset( $this->data[ $object_name ][ $key ] ) ) {
			$this->data[ $object_name ][ $key ] = $value;
			return;
		}

		// If key already exists.
		$old_value = $this->data[ $object_name ][ $key ];

		// If both array merge them.
		if ( is_array( $old_value ) && is_array( $value ) ) {
			$this->data[ $object_name ][ $key ] = array_merge( $old_value, $value );
			return;
		}

		$this->data[ $object_name ][ $key ] = $value;
	}

	/**
	 * Remove something from JSON object.
	 *
	 * @param string $key         Unique identifier.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 */
	public function remove( $key, $object_name ) {
		if ( isset( $this->data[ $object_name ][ $key ] ) ) {
			unset( $this->data[ $object_name ][ $key ] );
		}
	}
}