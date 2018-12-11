<?php
/**
 * The Array helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Helpers;

use ArrayAccess;

/**
 * Arr class.
 */
class Arr {

	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param  mixed $value Value to check.
	 * @return bool
	 */
	public static function accessible( $value ) {
		return is_array( $value ) || $value instanceof ArrayAccess;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param  \ArrayAccess|array $array Array to check key in.
	 * @param  string|int         $key   Key to check for.
	 * @return bool
	 */
	public static function exists( $array, $key ) {
		if ( $array instanceof ArrayAccess ) {
			// @codeCoverageIgnoreStart
			return $array->offsetExists( $key );
			// @codeCoverageIgnoreEnd
		}

		return array_key_exists( $key, $array );
	}

	/**
	 * Insert a single array item inside another array at a set position
	 *
	 * @param array $array    Array to modify. Is passed by reference, and no return is needed.
	 * @param array $new      New array to insert.
	 * @param int   $position Position in the main array to insert the new array.
	 */
	public static function insert( &$array, $new, $position ) {
		$before = array_slice( $array, 0, $position - 1 );
		$after  = array_diff_key( $array, $before );
		$array  = array_merge( $before, $new, $after );
	}

	/**
	 * Push an item onto the beginning of an array.
	 *
	 * @param array $array Array to add.
	 * @param mixed $value Value to add.
	 * @param mixed $key   Add with this key.
	 */
	public static function prepend( &$array, $value, $key = null ) {
		if ( is_null( $key ) ) {
			array_unshift( $array, $value );
		} else {
			$array = [ $key => $value ] + $array;
		}
	}

	/**
	 * Update array add or delete value
	 *
	 * @param array $array Array to modify. Is passed by reference, and no return is needed.
	 * @param array $value Value to add or delete.
	 */
	public static function add_delete_value( &$array, $value ) {
		if ( ( $key = array_search( $value, $array ) ) !== false ) { // @codingStandardsIgnoreLine
			unset( $array[ $key ] );
			return;
		}

		$array[] = $value;
	}
}
