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

	public function getPrivate( $obj, $attribute ) {
		$getter = function() use ( $attribute ) {
			return $this->$attribute;
		};
		$get = \Closure::bind( $getter, $obj, get_class( $obj ) );
		return $get();
	}
}
