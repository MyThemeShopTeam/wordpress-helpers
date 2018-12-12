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
	 * Get action from request.
	 */
	public function test_get_request_action() {

		$this->assertFalse( WordPress::get_request_action() );
		$_REQUEST['action'] = 'add';
		$this->assertEquals( WordPress::get_request_action(), 'add' );
		$_REQUEST['action'] = '-1';

		$_REQUEST['action2'] = 'delete';
		$this->assertEquals( WordPress::get_request_action(), 'delete' );
	}

	/**
	 * Strip all shortcodes active or orphan.
	 */
	public function test_strip_shortcodes() {
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( '[dummy]Shakeeb Ahmed[/dummy]' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( '[dummy]Shakeeb Ahmed' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( 'Shakeeb Ahmed[/dummy]' ) );
		$this->assertEquals( 'Shakeeb Ahmed', WordPress::strip_shortcodes( 'Shakeeb Ahmed' ) );
	}

	/**
	 * Instantiates the WordPress filesystem for use.
	 */
	public function test_get_filesystem() {
		$this->assertInstanceOf( 'WP_Filesystem_Base', WordPress::get_filesystem() );
	}

	/**
	 * Get current post type.
	 */
	public function test_get_post_type() {
		global $post, $typenow, $current_screen, $pagenow;

		$old_pagenow        = $pagenow;
		$old_typenow        = $typenow;
		$old_post           = $post;
		$old_current_screen = $current_screen;

		// Global Post .
		$post = (object) array( 'post_type' => 'mts_portfolio' );
		$this->assertEquals( WordPress::get_post_type(), 'mts_portfolio' );
		$post = $old_post;

		// Global typenow.
		$typenow = 'page';
		$this->assertEquals( WordPress::get_post_type(), 'page' );
		$typenow = $old_typenow;

		// Global current_screen.
		$current_screen = (object) array( 'post_type' => 'mts_gallery' );
		$this->assertEquals( WordPress::get_post_type(), 'mts_gallery' );
		$current_screen = $old_current_screen;

		// Request post_type.
		$_REQUEST['post_type'] = 'rank-math';
		$this->assertEquals( WordPress::get_post_type(), 'rank-math' );
		unset( $_REQUEST['post_type'] );

		$post_id = $this->factory()->post->create_object( array( 'post_type' => 'new_post_type' ) );

		// Request post_ID.
		$_REQUEST['post_ID'] = $post_id;
		$this->assertEquals( WordPress::get_post_type(), 'new_post_type' );
		unset( $_REQUEST['post_ID'] );

		// Request post_ID.
		$_GET['post'] = $post_id;
		$this->assertEquals( WordPress::get_post_type(), 'new_post_type' );
		unset( $_GET['post'] );

		// Global pagenow.
		$pagenow = 'post-new.php';
		$this->assertEquals( WordPress::get_post_type(), 'post' );
		$pagenow = $old_pagenow;

		$this->assertFalse( WordPress::get_post_type() );
	}
}
