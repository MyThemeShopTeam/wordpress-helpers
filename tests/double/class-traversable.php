<?php
/**
 * Unit tests for Traversable Helper
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

interface CountableTraversable extends Traversable {
}

interface CountableTraversableAggregate  extends CountableTraversable, IteratorAggregate {
}

class TestWidgets implements CountableTraversableAggregate {

	private $widgets = [ 'Blue', 'Red' ];

	public function getIterator()  {
		return new ArrayIterator( $this->widgets );
	}
}
