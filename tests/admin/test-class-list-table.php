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
use MyThemeShop\Admin\List_Table;

/**
 * TestList_Table class.
 */
class TestList_Table extends UnitTestCase {

	private $table;

	public function setUp() {
		parent::setUp();
		$this->table = new List_Table(
			array(
				'screen' => 'post',
			)
		);
	}

	public function test_list_table() {
		$this->assertInstanceOf( 'WP_List_Table', $this->table );
	}

	public function test_no_item() {
		$this->expectOutputString( 'No items found.' );
		$this->table->no_items();
	}

	public function test_no_item_custom() {
		$table = new List_Table(
			array(
				'screen'   => 'post',
				'no_items' => 'None Found.',
			)
		);

		$this->expectOutputString( 'None Found.' );
		$table->no_items();
	}

	public function test_get_order() {

		// Default.
		$this->assertEquals( $this->invokeMethod( $this->table, 'get_order' ), 'DESC' );
		$this->assertEquals( $this->invokeMethod( $this->table, 'get_orderby' ), 'create_date' );
		$this->assertEmpty( $this->invokeMethod( $this->table, 'get_search' ) );

		// Set somethingelse.
		$_REQUEST['order'] = 'asc';
		$this->assertEquals( $this->invokeMethod( $this->table, 'get_order' ), 'ASC' );

		$_REQUEST['s'] = 'shakeeb';
		$this->assertEquals( $this->invokeMethod( $this->table, 'get_search' ), 'shakeeb' );
	}
}
