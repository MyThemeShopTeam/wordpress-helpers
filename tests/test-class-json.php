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

		// Empty.
		$this->manager->add( '', 'awesome', 'mythemeshop' );

		// Key don't exists.
		$this->manager->add( 'test', 'value', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'test' => 'value' ] ]
		);

		// Key exists and not array overwrite.
		$this->manager->add( 'test', 'changed', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'test' => 'changed' ] ]
		);

		// Key exists and array merge.
		$this->manager->add( 'name', [ 'first' => 'awesome' ], 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[
				'mythemeshop' => [
					'test' => 'changed',
					'name' => [
						'first' => 'awesome',
					],
				],
			]
		);

		$this->manager->add( 'name', [ 'last' => 'ahmed' ], 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[
				'mythemeshop' => [
					'test' => 'changed',
					'name' => [
						'first' => 'awesome',
						'last'  => 'ahmed',
					],
				],
			]
		);
	}

	/**
	 * Remove something from JSON object.
	 */
	public function test_remove() {
		$this->manager->add( 'name', 'awesome', 'mythemeshop' );
		$this->manager->remove( 'test', 'mythemeshop' );
		$this->assertArrayEquals(
			$this->getPrivate( $this->manager, 'data' ),
			[ 'mythemeshop' => [ 'name' => 'awesome' ] ]
		);
	}

	/**
	 * Print data.
	 */
	public function test_output() {
		$this->manager->add( 'name', 'awesome', 'mythemeshop' );
		$this->manager->add( 'count', 10, 'mythemeshop' );
		$this->manager->add( 'isRegistered', true, 'mythemeshop' );
		$script  = '';
		$script .= "<script type='text/javascript'>\n";
		$script .= "/* <![CDATA[ */\n";
		$script .= "var mythemeshop = {\"name\":\"awesome\",\"count\":10,\"isRegistered\":true};" . PHP_EOL . "\n";
		$script .= "/* ]]> */\n";
		$script .= "</script>\n";

		$this->expectOutputString( $script );
		$this->manager->output();
	}

	/**
	 * Empty output.
	 */
	public function test_empty_output() {
		$this->expectOutputString( '' );
		$this->manager->output();
	}

	/**
	 * Empty object.
	 */
	public function test_empty_object() {
		$this->manager->add( 'name', 'awesome', 'mythemeshop' );
		$this->manager->remove( 'name', 'mythemeshop' );
		$this->expectOutputString( '' );
		$this->manager->output();
	}
}
