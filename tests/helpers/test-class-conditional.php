<?php
/**
 * The Conditional helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Conditional;

/**
 * TestConditional class.
 */
class TestConditional extends UnitTestCase {

	/**
	 * The Conditional helpers.
	 */
	public function test_conditional_functions() {
		$this->assertFalse( Conditional::is_edd_active() );
		$this->assertFalse( Conditional::is_woocommerce_active() );
	}

	/**
	 * Is AJAX request
	 */
	public function test_is_ajax() {
		$this->assertFalse( Conditional::is_ajax() );

		define( 'DOING_AJAX', true );
		$this->assertTrue( Conditional::is_ajax() );
	}

	/**
	 * Is CRON request
	 */
	public function test_is_cron() {
		$this->assertFalse( Conditional::is_cron() );

		define( 'DOING_CRON', true );
		$this->assertTrue( Conditional::is_cron() );
	}

	/**
	 * Is auto-saving
	 */
	public function test_is_autosave() {
		$this->assertFalse( Conditional::is_autosave() );

		define( 'DOING_AUTOSAVE', true );
		$this->assertTrue( Conditional::is_autosave() );
	}

	/**
	 * Is REST request
	 */
	public function test_is_rest() {
		$this->assertFalse( Conditional::is_rest() );

		define( 'REST_REQUEST', true );
		$this->assertTrue( Conditional::is_rest() );
	}

	/**
	 * Check if the request is heartbeat.
	 */
	public function is_heartbeat() {
		$this->assertFalse( Conditional::is_heartbeat() );

		$_POST['action'] = 'heartbeat';
		$this->assertTrue( Conditional::is_heartbeat() );
		unset( $_POST['action'] );
	}
}
