<?php
/**
 * The HTML helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\HTML;

/**
 * TestHTML class.
 */
class TestHTML extends UnitTestCase {

	/**
	 * Extract attributes from a html tag.
	 */
	public function test_extract_attributes() {

		// Element without attrs.
		$this->assertEmpty( HTML::extract_attributes( '<div>' ) );

		// Element with empty attrs.
		$this->assertEmpty( HTML::extract_attributes( '<div data-url>' ) );
		$this->assertEquals( array( 'class' => 'test' ), HTML::extract_attributes( '<div class="test" data-url>' ) );

		// Basic element.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url',
			),
			HTML::extract_attributes( '<a href="https://sampleurl.com" class="sample-url">' )
		);

		// Self close element.
		$this->assertEquals(
			array(
				'src'   => 'https://sampleurl.com/img.png',
				'class' => 'sample-image',
			),
			HTML::extract_attributes( '<img src="https://sampleurl.com/img.png" class="sample-image" />' )
		);

		// Element with attrs contain spaces.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url extra-class',
			),
			HTML::extract_attributes( '<a href="https://sampleurl.com" class="sample-url extra-class">Link text</a>' )
		);

		// Multiple spaces between attrs.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url',
			),
			HTML::extract_attributes( '<a href="https://sampleurl.com"      class="sample-url">Link text</a>' )
		);

		// No space between attrs.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url',
			),
			HTML::extract_attributes( '<a href="https://sampleurl.com"class="sample-url">Link text</a>' )
		);

		// Single quote attrs.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url',
			),
			HTML::extract_attributes( "<a href='https://sampleurl.com'      class='sample-url'>Link text</a>" )
		);

		// Combine single quote and double quote.
		$this->assertEquals(
			array(
				'href'  => 'https://sampleurl.com',
				'class' => 'sample-url',
			),
			HTML::extract_attributes( "<a href='https://sampleurl.com'      class=\"sample-url\">Link text</a>" )
		);

		// Single quote in double quote.
		$this->assertEquals(
			array(
				'href' => 'https://sampleurl.com\'   class=\'sample-url',
			),
			HTML::extract_attributes( '<a href="https://sampleurl.com\'   class=\'sample-url">Link text</a>' )
		);

		// Double quote in single quote.
		$this->assertEquals(
			array(
				'href' => 'https://sampleurl.com"   class="sample-url',
			),
			HTML::extract_attributes( '<a href=\'https://sampleurl.com"   class="sample-url\'>Link text</a>' )
		);
	}

	/**
	 * Generate html attribute string for array.
	 */
	public function test_attributes_to_string() {
		$this->assertFalse( HTML::attributes_to_string( array() ) );

		$attrs = array(
			'id'    => 'test-id',
			'class' => 'test-class',
		);
		$this->assertEquals( HTML::attributes_to_string( $attrs ), ' id="test-id" class="test-class"' );

		$attrs = array(
			'id'    => 'test-id',
			'class' => 'test-class',
		);
		$this->assertEquals( HTML::attributes_to_string( $attrs, 'data-' ), ' data-id="test-id" data-class="test-class"' );

		$attrs = array(
			'id'       => 'test-id',
			'disabled' => false,
			'readonly' => true,
		);
		$this->assertEquals( HTML::attributes_to_string( $attrs ), ' id="test-id" disabled="false" readonly="true"' );

		$attrs = array(
			'disabled' => '',
			'readonly' => '',
		);
		$this->assertEquals( HTML::attributes_to_string( $attrs ), ' disabled readonly' );

		$attrs = array(
			'data-content' => 'This contains "quotes" characters',
		);
		$this->assertEquals( HTML::attributes_to_string( $attrs ), ' data-content="This contains &quot;quotes&quot; characters"' );
	}
}
