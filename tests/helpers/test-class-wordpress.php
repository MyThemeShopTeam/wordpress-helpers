<?php
/**
 * The WordPress helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\WordPress;

/**
 * TestWordPress class.
 */
class TestWordPress extends UnitTestCase {

	/**
	 * Retrieves the sitename.
	 */
	public function test_get_site_name() {
		$this->assertEquals( WordPress::get_site_name(), 'Test Blog' );
	}

	/**
	 * Strip all shortcodes active or orphan.
	 */
	public function test_remove_all_shortcodes() {
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( '[dummy]Shakeeb Ahmed[/dummy]' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( '[dummy]Shakeeb Ahmed' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( 'Shakeeb Ahmed[/dummy]' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( 'Shakeeb Ahmed' ) );
	}
}
