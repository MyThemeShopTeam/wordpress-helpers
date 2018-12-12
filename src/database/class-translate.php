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
		$query = array( 'select' );

		if ( $this->found_rows ) {
			$query[] = 'SQL_CALC_FOUND_ROWS';
		}
		if ( $this->distinct ) {
			$query[] = 'distinct';
		}

		// Build the selected fields.
		$query[] = ! empty( $this->statements['select'] ) && is_array( $this->statements['select'] ) ? join( ', ', $this->statements['select'] ) : '*';

		// Append the table.
		$query[] = 'from ' . $this->table;

		// Build the where statements.
		if ( ! empty( $this->statements['wheres'] ) ) {
			$query[] = join( ' ', $this->statements['wheres'] );
		}

		$this->translateGroupBy( $query );
		$this->translateOrderBy( $query );
		$this->translateLimit( $query );

		return join( ' ', $query );
	}

	/**
	 * Translate the current query to an SQL update statement
	 *
	 * @return string
	 */
	private function translateUpdate() { // @codingStandardsIgnoreLine
		$query = array( "update {$this->table} set" );

		// Add the values.
		$values = array();
		foreach ( $this->statements['values'] as $key => $value ) {
			$values[] = $key . ' = ' . $this->esc_value( $value );
		}

		if ( ! empty( $values ) ) {
			$query[] = join( ', ', $values );
		}

		// Build the where statements.
		if ( ! empty( $this->statements['wheres'] ) ) {
			$query[] = join( ' ', $this->statements['wheres'] );
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
		$query = array( "delete from {$this->table}" );

		// Build the where statements.
		if ( ! empty( $this->statements['wheres'] ) ) {
			$query[] = join( ' ', $this->statements['wheres'] );
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
		if ( empty( $this->statements['orders'] ) ) {
			return;
		}

		$orders = array();
		foreach ( $this->statements['orders'] as $column => $direction ) {

			// in case a raw value is given we had to
			// put the column / raw value an direction inside another
			// array because we cannot make objects to array keys.
			if ( is_array( $direction ) ) {
				list( $column, $direction ) = $direction;
			}

			if ( ! is_null( $direction ) ) {
				$column .= ' ' . $direction;
			}

			$orders[] = $column;
		}

		$query[] = 'order by ' . join( ', ', $orders );
	}

	/**
	 * Build the group by statements.
	 *
	 * @param array $query Query holder.
	 */
	private function translateGroupBy( &$query ) { // @codingStandardsIgnoreLine
		if ( empty( $this->statements['groups'] ) ) {
			return;
		}

		$query[] = 'group by ' . join( ', ', $this->statements['groups'] );

		if ( ! empty( $this->statements['having'] ) ) {
			$query[] = $this->statements['having'];
		}
	}

	/**
	 * Build offset and limit.
	 *
	 * @param array $query Query holder.
	 */
	private function translateLimit( &$query ) { // @codingStandardsIgnoreLine
		if ( ! empty( $this->limit ) ) {
			$query[] = $this->limit;
		}
	}
}
