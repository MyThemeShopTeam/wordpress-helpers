<?php
/**
 * The database helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\DB;

/**
 * TestDB class.
 */
class TestDB extends UnitTestCase {

	/**
	 * Check if table exists in db or not.
	 */
	public function test_check_table_exists() {
		$this->assertFalse( DB::check_table_exists( 'any_table' ) );
		$this->assertTrue( DB::check_table_exists( 'posts' ) );
		$this->assertTrue( DB::check_table_exists( 'options' ) );
		$this->assertTrue( DB::check_table_exists( 'users' ) );
	}
}
