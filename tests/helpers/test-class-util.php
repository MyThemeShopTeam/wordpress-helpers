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
use MyThemeShop\Helpers\Util;

/**
 * TestUtil class.
 */
class TestUtil extends UnitTestCase {

	/**
	 * Get field from query string.
	 * Get field from FORM post.
	 * Get action from request.
	 */
	public function test_get_post_request() {

		// Param Get.
		$this->assertFalse( Util::param_get( 'dummy' ) );
		$this->assertEquals( Util::param_get( 'dummy', 'default-dummy' ), 'default-dummy' );
		$_GET['dummy'] = 'test-string';
		$this->assertEquals( Util::param_get( 'dummy' ), 'test-string' );

		// Param Post.
		$this->assertFalse( Util::param_post( 'dummy' ) );
		$this->assertEquals( Util::param_post( 'dummy', 'default-dummy' ), 'default-dummy' );
		$_POST['dummy'] = 'test-string';
		$this->assertEquals( Util::param_post( 'dummy' ), 'test-string' );
	}
}
