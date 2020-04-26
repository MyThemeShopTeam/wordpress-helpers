<?php
/**
 * The orderby functions.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Database
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Database;

/**
 * OrderBy class.
 */
trait OrderBy {

	/**
	 * Add an order by statement to the current query
	 *
	 *     ->orderBy('created_at')
	 *     ->orderBy('modified_at', 'desc')
	 *
	 *     // multiple order clauses
	 *     ->orderBy(['firstname', 'lastname'], 'desc')
	 *
	 *     // muliple order clauses with diffrent directions
	 *     ->orderBy(['firstname' => 'asc', 'lastname' => 'desc'])
	 *
	 * @param array|string $columns   Columns.
	 * @param string       $direction Direction.
	 *
	 * @return self The current query builder.
	 */
	public function orderBy( $columns, $direction = 'asc' ) { // @codingStandardsIgnoreLine
		if ( is_string( $columns ) ) {
			$columns = $this->argument_to_array( $columns );
		}

		foreach ( $columns as $key => $column ) {
			if ( is_numeric( $key ) ) {
				$this->sql_clauses['order_by'][ $column ] = $direction;
				continue;
			}

			$this->sql_clauses['order_by'][ $key ] = $column;
		}

		return $this;
	}

	/**
	 * Returns an string argument as parsed array if possible
	 *
	 * @param string $argument Argument to validate.
	 *
	 * @return array
	 */
	protected function argument_to_array( $argument ) {
		if ( false !== strpos( $argument, ',' ) ) {
			return array_map( 'trim', explode( ',', $argument ) );
		}

		return array( $argument );
	}
}
