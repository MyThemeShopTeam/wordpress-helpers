<?php
/**
 * The Array helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Admin
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Admin;

use UnitTestCase;
use MyThemeShop\Admin\Page;

/**
 * TestPage class.
 */
class TestPage extends UnitTestCase {

	private $page;

	public function setUp() {
		parent::setUp();
		$this->page = new Page(
			'test-page',
			'Test Page',
			array(
				'position'   => 80,
				'capability' => 'manage_options',
				'icon'       => 'dashicons-chart-area',
				'render'     => 'dashboard.php',
				'assets'     => array(
					'styles'  => array( 'test-page-dashboard' => '' ),
					'scripts' => array( 'test-page-dashboard' => '' ),
				),
			)
		);
	}

	public function test_page() {
		$this->assertInstanceOf( 'MyThemeShop\Admin\Page', $this->page );
	}

	public function test_no_id() {
		$this->expectException( 'WPDieException' );
		new Page( '', 'Test Page' );
	}

	public function test_no_title() {
		$this->expectException( 'WPDieException' );
		new Page( 'test-page', '' );
	}

	public function test_is_current_page() {
		$this->assertFalse( $this->page->is_current_page() );
	}
}
