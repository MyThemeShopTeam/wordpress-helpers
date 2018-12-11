<?php
/**
 * The String helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Str;

/**
 * TestStr class.
 */
class TestStr extends UnitTestCase {

	/**
	 * Validates whether the passed variable is a non-empty string.
	 *
	 * @param mixed $variable The variable to validate.
	 * @return bool Whether or not the passed value is a non-empty string.
	 */
	public static function is_non_empty( $variable ) {
		return is_string( $variable ) && '' !== $variable;
	}

	/**
	 * Check if the string contains the given value.
	 */
	public function test_contains() {
		// True.
		$this->assertTrue( Str::contains( 'H', 'Hello world' ) );
		$this->assertTrue( Str::contains( ' ', 'Hello world' ) );
		$this->assertTrue( Str::contains( '0', 'Hello 0 world' ) );
		$this->assertTrue( Str::contains( 'llo 1', 'Hello 1 world' ) );

		// False.
		$this->assertFalse( Str::contains( 'a', 'Hello world' ) );
	}

	/**
	 * Check if the string begins with the given value.
	 */
	public function test_starts_with() {
		// True.
		$this->assertTrue( Str::starts_with( '', '' ) );
		$this->assertTrue( Str::starts_with( '', 'Hello world' ) );
		$this->assertTrue( Str::starts_with( 'Hell', 'Hello world' ) );
		$this->assertTrue( Str::starts_with( 0, '0Hello world' ) );
		$this->assertTrue( Str::starts_with( 10, '10 Hello world' ) );

		// False.
		$this->assertFalse( Str::starts_with( 'Hello', ' Hello world' ) );
		$this->assertFalse( Str::starts_with( 'Hello', 'He' ) );
		$this->assertFalse( Str::starts_with( 'H', '' ) );
		$this->assertFalse( Str::starts_with( ' ', 'Hello world' ) );
		$this->assertFalse( Str::starts_with( 'h', 'Hello world' ) );
	}

	/**
	 * Check if the string end with the given value.
	 */
	public function test_ends_with() {
		// True.
		$this->assertTrue( Str::ends_with( '', 'Hello world' ) );
		$this->assertTrue( Str::ends_with( 'rld', 'Hello world' ) );
		$this->assertTrue( Str::ends_with( 0, 'Hello world0' ) );
		$this->assertTrue( Str::ends_with( 1, 'Hello world1' ) );

		// False.
		$this->assertFalse( Str::ends_with( 'world', 'Hello world ' ) );
		$this->assertFalse( Str::ends_with( 'H', '' ) );
		$this->assertFalse( Str::ends_with( ' ', 'Hello world' ) );
		$this->assertFalse( Str::ends_with( 'D', 'Hello world' ) );
		$this->assertFalse( Str::ends_with( 'Hell', 'Hello world' ) );
		$this->assertFalse( Str::ends_with( 0, 'Hello world' ) );
		$this->assertFalse( Str::ends_with( 1, 'Hello world' ) );
	}

	/**
	 * Check the string for desired comparison.
	 */
	public function test_comparison() {
		$this->assertTrue( Str::comparison( 'str', 'str', 'exact' ) );
		$this->assertFalse( Str::comparison( 'str', 'str1', 'exact' ) );

		$this->assertTrue( Str::comparison( 'Hello', 'Hello world', 'start' ) );
		$this->assertFalse( Str::comparison( 'world', 'Hello world', 'start' ) );

		$this->assertTrue( Str::comparison( 'world', 'Hello world', 'end' ) );
		$this->assertFalse( Str::comparison( 'Hello', 'Hello world', 'end' ) );

		$this->assertTrue( Str::comparison( 'llo', 'Hello world', 'contains' ) );
		$this->assertFalse( Str::comparison( 'a', 'Hello world', 'contains' ) );

		$this->assertEquals( 1, Str::comparison( '/\s/', 'Hello world', 'regex' ) );
		$this->assertEquals( 0, Str::comparison( '/[0-9]/', 'Hello World', 'regex' ) );
	}

	/**
	 * Convert string to array with defined seprator.
	 */
	public function test_to_arr() {
		$this->assertEquals( Str::to_arr( 'a,b,c' ), array( 'a', 'b', 'c' ) );
		$this->assertEquals( Str::to_arr( 'a-b-c', '-' ), array( 'a', 'b', 'c' ) );
	}

	/**
	 * Convert string to array, weed out empty elements and whitespaces.
	 */
	public function test_to_arr_no_empty() {
		$this->assertEquals( Str::to_arr_no_empty( "a \r\n b \r\n c" ), array( 'a', 'b', 'c' ) );
		$this->assertEquals( Str::to_arr_no_empty( "a \r\n\r\n c" ), array( 'a', 'c' ) );
	}

	/**
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 */
	public function test_let_to_num() {
		$this->assertEquals( Str::let_to_num( '' ), 0 );
		$this->assertEquals( Str::let_to_num( '0M' ), 0 );
		$this->assertEquals( Str::let_to_num( '2M' ), 2 * 1024 * 1024 );
		$this->assertEquals( Str::let_to_num( '10P' ), 10 * 1024 * 1024 * 1024 * 1024 * 1024 );
	}

	/**
	 * Convert a number to K, M, B, etc.
	 */
	public function test_human_number() {
		// Not numeric.
		$this->assertEquals( Str::human_number( '0' ), 0 );

		// Negative.
		$this->assertEquals( Str::human_number( -100 ), -100 );
		$this->assertEquals( Str::human_number( -10550 ), '-10.6K' );

		// Less Than 1000.
		$this->assertEquals( Str::human_number( 560 ), 560 );

		$this->assertEquals( Str::human_number( 0 ), '0' );
		$this->assertEquals( Str::human_number( 10 ), '10' );
		$this->assertEquals( Str::human_number( 1000 ), '1K' );
		$this->assertEquals( Str::human_number( 1500 ), '1.5K' );
		$this->assertEquals( Str::human_number( 1585 ), '1.6K' );
		$this->assertEquals( Str::human_number( 999900 ), '999.9K' );
		$this->assertEquals( Str::human_number( 999900000000000 ), '999.9T' );
	}
}
