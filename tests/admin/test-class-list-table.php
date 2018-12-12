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

	public function test_list_table() {
		$GLOBALS['hook_suffix'] = 'post';

		$table = new List_Table;
		$this->assertInstanceOf( 'WP_List_Table', $table );
	}
}
