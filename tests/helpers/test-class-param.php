<?php
/**
 * The utility helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Param;

/**
 * TestParam class.
 */
class TestParam extends UnitTestCase {

	/**
	 * Get field from query string.
	 * Get field from FORM post.
	 * Get action from request.
	 */
	public function test_get_post_request() {

		// Param Get.
		$this->assertFalse( Param::get( 'dummy' ) );
		$this->assertEquals( Param::get( 'dummy', 'default-dummy' ), 'default-dummy' );

		// Param Post.
		$this->assertFalse( Param::post( 'dummy' ) );
		$this->assertEquals( Param::post( 'dummy', 'default-dummy' ), 'default-dummy' );

		// Param Request.
		$this->assertFalse( Param::request( 'dummy' ) );
		$this->assertEquals( Param::request( 'dummy', 'default-dummy' ), 'default-dummy' );
	}
}
