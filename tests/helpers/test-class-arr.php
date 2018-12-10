<?php
/**
 * The Array helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Arr;

/**
 * TestArr class.
 */
class TestArr extends UnitTestCase {

	/**
	 * Determine whether the given value is array accessible.
	 */
	public function test_accessible() {
		$array = array();
		$this->assertTrue( Arr::accessible( $array ) );
		$this->assertFalse( Arr::accessible( true ) );
		$this->assertFalse( Arr::accessible( 123 ) );
		$this->assertFalse( Arr::accessible( 'string' ) );
	}

	/**
	 * Determine if the given key exists in the provided array.
	 */
	public function test_exists() {
		$array = array( 'shakeeb', 'ahmed' );
		$this->assertTrue( Arr::exists( $array, 0 ) );
		$this->assertTrue( Arr::exists( $array, 1 ) );
		$this->assertFalse( Arr::exists( $array, 2 ) );

		$array = array( 'shakeeb' => 'ahmed' );
		$this->assertTrue( Arr::exists( $array, 'shakeeb' ) );
		$this->assertFalse( Arr::exists( $array, 'ahmed' ) );
	}

	/**
	 * Insert a single array item inside another array at a set position
	 */
	public function test_insert() {
		$array = array(
			'b' => 'B',
			'd' => 'D',
		);

		Arr::insert( $array, array( 'a' => 'A' ), 0 );
		$this->assertEquals(
			$array,
			array(
				'a' => 'A',
				'b' => 'B',
				'd' => 'D',
			)
		);

		Arr::insert( $array, array( 'c' => 'C' ), 2 );
		$this->assertEquals(
			$array,
			array(
				'a' => 'A',
				'b' => 'B',
				'c' => 'C',
				'd' => 'D',
			)
		);

		Arr::insert( $array, array( 'f' => 'F' ), -1 );
		$this->assertEquals(
			$array,
			array(
				'a' => 'A',
				'b' => 'B',
				'c' => 'C',
				'd' => 'D',
				'f' => 'F',
			)
		);

		Arr::insert( $array, array( 'e' => 'E' ), -2 );
		$this->assertEquals(
			$array,
			array(
				'a' => 'A',
				'b' => 'B',
				'c' => 'C',
				'd' => 'D',
				'e' => 'E',
				'f' => 'F',
			)
		);

		Arr::insert( $array, array( 'g' => 'G' ), 99 );
		$this->assertEquals(
			$array,
			array(
				'a' => 'A',
				'b' => 'B',
				'c' => 'C',
				'd' => 'D',
				'e' => 'E',
				'f' => 'F',
				'g' => 'G',
			)
		);
	}

	/**
	 * Push an item onto the beginning of an array.
	 */
	public function test_prepend() {
		$array = array();
		Arr::prepend( $array, 'orange' );
		$this->assertEquals(
			$array,
			array(
				'orange',
			)
		);

		Arr::prepend( $array, 'stawberry' );
		$this->assertEquals(
			$array,
			array(
				'stawberry',
				'orange',
			)
		);

		$array = array();
		Arr::prepend( $array, 'stawberry', 'first' );
		$this->assertEquals(
			$array,
			array(
				'first' => 'stawberry',
			)
		);

		Arr::prepend( $array, 'banana', 'second' );
		$this->assertEquals(
			$array,
			array(
				'second' => 'banana',
				'first'  => 'stawberry',
			)
		);
	}

	/**
	 * Update array add or delete value
	 */
	public function test_add_delete_value() {
		$array = array();

		// Add.
		Arr::add_delete_value( $array, 'shakeeb' );
		$this->assertContains( 'shakeeb', $array );

		// Delete.
		Arr::add_delete_value( $array, 'shakeeb' );
		$this->assertNotContains( 'shakeeb', $array );
	}
}
