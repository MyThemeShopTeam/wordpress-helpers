<?php
/**
 * The JSON manager handles json output to admin and frontend.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests;

use UnitTestCase;

/**
 * TestJsonManager class.
 */
class TestJsonManager extends UnitTestCase {

	private $manager;

	public function setUp() {
		parent::setUp();
		$this->manager = new \MyThemeShop\Json_Manager;
	}

	/**
	 * Add something to JSON object.
	 */
	public function test_add() {
		$this->manager->add( 'test', 'value', 'mythemeshop' );
		$data = $this->getPrivate( $this->manager, 'data' );
		$this->assertArrayHasKey( 'mythemeshop', $data );
		$this->assertArrayEquals( $data, [ 'mythemeshop' => [ 'test' => 'value' ] ] );
	}

	/**
	 * Remove something from JSON object.
	 */
	public function test_remove() {
		$this->manager->add( 'name', 'shakeeb', 'mythemeshop' );
		$this->manager->remove( 'test', 'mythemeshop' );
		$data = $this->getPrivate( $this->manager, 'data' );
		$this->assertArrayEquals( $data, [ 'mythemeshop' => [ 'name' => 'shakeeb' ] ] );
	}

	/**
	 * Print data.
	 */
	public function print() {
		$script = $this->encode();
		if ( ! $script ) {
			return;
		}

		echo "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5.
		echo "/* <![CDATA[ */\n";
		echo "$script\n";
		echo "/* ]]> */\n";
		echo "</script>\n";
	}

	/**
	 * Get encoded string.
	 *
	 * @return string
	 */
	private function encode() {
		$script = '';
		foreach ( $this->data as $object_name => $object_data ) {
			$script .= $this->single_object( $object_name, $object_data );
		}

		return $script;
	}

	/**
	 * Encode single object.
	 *
	 * @param  string $object_name Object name to use as JS variable.
	 * @param  array  $object_data Object data to json encode.
	 * @return array
	 */
	private function single_object( $object_name, $object_data ) {
		if ( empty( $object_data ) ) {
			return '';
		}

		foreach ( (array) $object_data as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$object_data[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}

		return "var $object_name = " . wp_json_encode( $object_data ) . ';' . PHP_EOL;
	}
}
