<?php
/**
 * The translate functions.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Database
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Database;

/**
 * Translate class.
 */
trait Translate {

	/**
	 * Translate the current query to an SQL select statement
	 *
	 * @return string
	 */
	private function translateSelect() { // @codingStandardsIgnoreLine
		$query  = array( 'SELECT' );
		$select = $this->get_sql_clause( 'select', true );

		if ( $this->found_rows ) {
			$query[] = 'SQL_CALC_FOUND_ROWS';
		}

		if ( $this->distinct ) {
			$query[] = 'DISTINCT';
		}

		$query[] = ! empty( $select ) ? $select : '*';
		$query[] = 'FROM ' . $this->get_sql_clause( 'from', true );
		$query[] = $this->get_sql_clause( 'join', true );
		$query[] = $this->get_sql_clause( 'where', true );

		$this->translateGroupBy( $query );
		$this->translateOrderBy( $query );
		$this->translateLimit( $query );

		return join( ' ', array_filter( $query ) );
	}

	/**
	 * Translate the current query to an SQL update statement
	 *
	 * @return string
	 */
	private function translateUpdate() { // @codingStandardsIgnoreLine
		$query = array( "UPDATE {$this->table} set" );

		// Add the values.
		$values = array();
		foreach ( $this->sql_clauses['values'] as $key => $value ) {
			$values[] = $key . ' = ' . $this->esc_value( $value );
		}

		if ( ! empty( $values ) ) {
			$query[] = join( ', ', $values );
		}

		// Build the where clauses.
		if ( ! empty( $this->sql_clauses['where'] ) ) {
			$query[] = join( ' ', $this->sql_clauses['where'] );
		}

		$this->translateLimit( $query );

		return join( ' ', $query );
	}

	/**
	 * Translate the current query to an SQL delete statement
	 *
	 * @return string
	 */
	private function translateDelete() { // @codingStandardsIgnoreLine
		$query = array( "DELETE from {$this->table}" );

		// Build the where clauses.
		if ( ! empty( $this->sql_clauses['where'] ) ) {
			$query[] = join( ' ', $this->sql_clauses['where'] );
		}

		$this->translateLimit( $query );

		return join( ' ', $query );
	}

	/**
	 * Build the order by statement
	 *
	 * @param array $query Query holder.
	 */
	protected function translateOrderBy( &$query ) { // @codingStandardsIgnoreLine
		if ( empty( $this->sql_clauses['order_by'] ) ) {
			return;
		}

		$order_by = array();
		foreach ( $this->sql_clauses['order_by'] as $column => $direction ) {

			if ( ! is_null( $direction ) ) {
				$column .= ' ' . $direction;
			}

			$order_by[] = $column;
		}

		$query[] = 'order by ' . join( ', ', $order_by );
	}

	/**
	 * Build the group by clauses.
	 *
	 * @param array $query Query holder.
	 */
	private function translateGroupBy( &$query ) { // @codingStandardsIgnoreLine
		$group_by = $this->get_sql_clause( 'group_by', true );
		$having   = $this->get_sql_clause( 'having', true );
		if ( empty( $group_by ) ) {
			return;
		}

		$query[] = 'GROUP BY ' . $group_by;

		if ( ! empty( $having ) ) {
			$query[] = $having;
		}
	}

	/**
	 * Build offset and limit.
	 *
	 * @param array $query Query holder.
	 */
	private function translateLimit( &$query ) { // @codingStandardsIgnoreLine
		$query[] = $this->get_sql_clause( 'limit', true );
	}
}
