<?php
/**
 * Unit tests for Breadcrumbs Helper
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

abstract class UnitTestCase extends WP_UnitTestCase {

	/**
	 * Get private variable value.
	 */
	public function getPrivate( $obj, $attribute ) {
		$getter = function() use ( $attribute ) {
			return $this->$attribute;
		};
		$get = \Closure::bind( $getter, $obj, get_class( $obj ) );
		return $get();
	}

	/**
	 * Invoke private and protected methods.
	 */
	public function invokeMethod( &$object, $method, $parameters = array() ) {
		$reflection = new \ReflectionClass( get_class( $object ) );
		$method     = $reflection->getMethod( $method );
		$method->setAccessible( true );
		return $method->invokeArgs( $object, $parameters );
	}

	/**
	 * Assert 2 arrays are equal.
	 */
	public function assertArrayEquals( $array1, $array2 ) {
		$this->assertEquals( json_encode( $array1 ), json_encode( $array2 ) );
	}
}
