<?php
/**
 * The Array helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Database
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Database;

use UnitTestCase;
use MyThemeShop\Helpers\DB;
use MyThemeShop\Database\Database;

/**
 * TestDatabase class.
 */
class TestDatabase extends UnitTestCase {

	/**
	 * MySql grammar tests
	 */
	public function test_instance() {
		$table = $this->create_builder();
		$this->assertInstanceOf( '\MyThemeShop\Database\Query_Builder', $table );
	}

	/**
	 * Test getter functions.
	 */
	public function bak_test_getter() {
		$table = DB::query_builder( 'posts' );
		$this->factory()->post->create( array( 'post_type' => 'page' ) );
		$this->factory()->post->create( array( 'post_type' => 'post' ) );

		// Get.
		$ids = $table->select( 'ID' )->get( \ARRAY_A );
		$this->assertArrayHasKey( 'ID', $ids[0] );

		// One.
		$this->assertArrayHasKey( 'ID', $table->select( 'ID' )->one( \ARRAY_A ) );

		// Get Var.
		$this->assertEquals( $table->select( 'ID' )->where( 'ID', 4 )->getVar(), 4 );

		// Get found Rows.
		$table->select( 'ID' )->found_rows()->get();
		$this->assertEquals( $table->get_found_rows(), 2 );
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_simple() {

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit',
			'Select',
			function( $table ) {

			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit',
			'Select',
			function( $table ) {
				$table->select();
			}
		);

		$this->assertQueryTranslation(
			'SELECT DISTINCT * FROM phpunit',
			'Select',
			function( $table ) {
				$table->distinct();
			}
		);

		$this->assertQueryTranslation(
			'SELECT SQL_CALC_FOUND_ROWS * FROM phpunit',
			'Select',
			function( $table ) {
				$table->found_rows();
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_fields() {

		$this->assertQueryTranslation(
			'SELECT id FROM phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id' );
			}
		);

		// Comma seperated fields.
		$this->assertQueryTranslation(
			'SELECT id, foo FROM phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id, foo' );
			}
		);

		// With array.
		$this->assertQueryTranslation(
			'SELECT id, foo FROM phpunit',
			'Select',
			function( $table ) {
				$table->select( [ 'id', 'foo' ] );
			}
		);

		// With alias as string.
		$this->assertQueryTranslation(
			'SELECT id, foo AS f FROM phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id, foo AS f' );
			}
		);

		// With array with alias.
		$this->assertQueryTranslation(
			'SELECT id AS d, foo AS f FROM phpunit',
			'Select',
			function( $table ) {
				$table->select(
					[
						'id'  => 'd',
						'foo' => 'f',
					]
				);
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_count() {
		$this->assertQueryTranslation(
			'SELECT COUNT(*), foo AS f FROM phpunit',
			'Select',
			function( $table ) {
				$table->selectCount()->select( 'foo AS f' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS count FROM phpunit',
			'Select',
			function( $table ) {
				$table->selectCount( 'id', 'count' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_others() {
		$this->assertQueryTranslation(
			'SELECT SUM(id) AS count FROM phpunit',
			'Select',
			function( $table ) {
				$table->selectSum( 'id', 'count' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT AVG(id) AS average FROM phpunit',
			'Select',
			function( $table ) {
				$table->selectAvg( 'id', 'average' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_where() {

		// Simple.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id = 23',
			'Select',
			function( $table ) {
				$table->where( 'id', 23 );
			}
		);

		// Float.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id = 2.500000',
			'Select',
			function( $table ) {
				$table->where( 'id', 2.5 );
			}
		);

		// String.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\'',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' );
			}
		);

		// Diffrent expression.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id != 42',
			'Select',
			function( $table ) {
				$table->where( 'id', '!=', 42 );
			}
		);

		// 2 wheres AND.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id = 2 AND active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 2 )->where( 'active', 1 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id = 2 AND active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 2 )->where( 'active', 1 );
			}
		);

		// 2 wheres OR.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id = 42 OR active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 42 )->orWhere( 'active', 1 );
			}
		);

		// Nesting.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE ( a = \'b\' OR c = \'d\' )',
			'Select',
			function( $table ) {
				$table->orWhere(
					array(
						array( 'a', 'b' ),
						array( 'c', 'd' ),
					)
				);
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE ( a > 10 AND a < 20 )',
			'Select',
			function( $table ) {
				$table->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'AND'
				);
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE a = 1 OR ( a > 10 AND a < 20 )',
			'Select',
			function( $table ) {
				$table->where( 'a', 1 )
				->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'AND'
				);
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE a = 1 OR ( a > 10 AND a < 20 ) AND c = 30',
			'Select',
			function( $table ) {
				$table->where( 'a', 1 )
				->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'AND'
				)
				->where( 'c', 30 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE is_active = 1 AND ( options like \'a\' OR options like \'b\' )',
			'Select',
			function( $table ) {
				$table->where( 'is_active', 1 )
					->where(
						array(
							array( 'options', 'like', 'a' ),
							array( 'options', 'like', 'b' ),
						),
						'OR'
					);
			}
		);

		// Where in / not in.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id IN (23, 25, 30)',
			'Select',
			function( $table ) {
				$table->whereIn( 'id', array( 23, 25, 30 ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR skills IN (\'php\', \'javascript\', \'ruby\')',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereIn( 'skills', array( 'php', 'javascript', 'ruby' ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id NOT IN (23, 25, 30)',
			'Select',
			function( $table ) {
				$table->whereNotIn( 'id', array( 23, 25, 30 ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR id NOT IN (23, 25, 30)',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereNotIn( 'id', array( 23, 25, 30 ) );
			}
		);

		// Where between / not between.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id BETWEEN 10 AND 100',
			'Select',
			function( $table ) {
				$table->whereBetween( 'id', array( 10, 100 ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE dates BETWEEN \'10-04-2018\' AND \'10-09-2018\'',
			'Select',
			function( $table ) {
				$table->whereBetween( 'dates', array( '10-04-2018', '10-09-2018' ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR id BETWEEN 10 AND 100',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereBetween( 'id', array( 10, 100 ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE id NOT BETWEEN 10 AND 100',
			'Select',
			function( $table ) {
				$table->whereNotBetween( 'id', array( 10, 100 ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR id NOT BETWEEN 10 AND 100',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereNotBetween( 'id', array( 10, 100 ) );
			}
		);

		// Where is null / is not null.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE name IS NULL',
			'Select',
			function( $table ) {
				$table->whereNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR name IS NULL',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE name IS NOT NULL',
			'Select',
			function( $table ) {
				$table->whereNotNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR name IS NOT NULL',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->orWhereNotNull( 'name' );
			}
		);
	}

	/**
	 * Test where invalid type.
	 */
	public function test_where_invalid_type() {
		$this->expectException( 'Exception' );

		$this->assertQueryTranslation(
			'SELECT * FROM phpunit WHERE username = \'surajv\' OR name IS NOT NULL',
			'Select',
			function( $table ) {
				$table->where( 'username', 'surajv' )
					->where( 'username', 'mekhan', null, 'something' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_limit() {

		// Simple.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit LIMIT 0, 1',
			'Select',
			function( $table ) {
				$table->limit( 1 );
			}
		);

		// With offset.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit LIMIT 20, 10',
			'Select',
			function( $table ) {
				$table->limit( 10, 20 );
			}
		);

		// Pagination.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit LIMIT 20, 10',
			'Select',
			function( $table ) {
				$table->page( 2, 10 );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_orderby() {

		// Simple.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit ORDER BY id',
			'Select',
			function( $table ) {
				$table->orderBy( 'id' );
			}
		);

		// Other direction.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit ORDER BY id DESC',
			'Select',
			function( $table ) {
				$table->orderBy( 'id', 'desc' );
			}
		);

		// More keys comma separated.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit ORDER BY firstname DESC, lastname DESC',
			'Select',
			function( $table ) {
				$table->orderBy( 'firstname, lastname', 'desc' );
			}
		);

		// Multipe sortings diffrent direction.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit ORDER BY firstname, lastname DESC',
			'Select',
			function( $table ) {
				$table->orderBy(
					array(
						'firstname' => 'asc',
						'lastname'  => 'desc',
					)
				);
			}
		);

		// Raw sorting.
		$this->assertQueryTranslation(
			'SELECT * FROM phpunit ORDER BY firstname <> nick',
			'Select',
			function( $table ) {
				$table->orderBy( 'firstname <> nick', null );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_update() {

		// Simple.
		$this->assertQueryTranslation(
			'UPDATE phpunit SET foo = \'bar\'',
			'Update',
			function( $table ) {
				$table->set( 'foo', 'bar' );
			}
		);

		// Multiple.
		$this->assertQueryTranslation(
			'UPDATE phpunit SET foo = \'bar\', bar = \'foo\'',
			'Update',
			function( $table ) {
				$table
				->set( 'foo', 'bar' )
				->set( 'bar', 'foo' );
			}
		);

		// Array.
		$this->assertQueryTranslation(
			'UPDATE phpunit SET foo = \'bar\', bar = \'foo\'',
			'Update',
			function( $table ) {
				$table->set(
					array(
						'foo' => 'bar',
						'bar' => 'foo',
					)
				);
			}
		);

		// With where and limit.
		$this->assertQueryTranslation(
			'UPDATE phpunit SET foo = \'bar\', bar = \'foo\' WHERE id = 1 LIMIT 0, 1',
			'Update',
			function( $table ) {
				$table
					->set( 'foo', 'bar' )
					->set( 'bar', 'foo' )
					->where( 'id', 1 )
					->limit( 1 );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_delete() {

		// Simple.
		$this->assertQueryTranslation(
			'DELETE FROM phpunit WHERE id = 1 LIMIT 0, 1',
			'Delete',
			function( $table ) {
				$table->where( 'id', 1 )->limit( 1 );
			}
		);
	}

	/**
	 * Join tests
	 */
	public function test_join() {

		// Simple.
		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'JOIN customers ON employeeNumber = salesRepEmployeeNumber ' .
			'JOIN payments ON payments.customerNumber = customers.customerNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				)
				->join( 'customers', 'employeeNumber', 'salesRepEmployeeNumber' )
				->join( 'payments', 'payments.customerNumber', 'customers.customerNumber' );
			}
		);

		// Operator.
		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'JOIN customers ON employeeNumber > salesRepEmployeeNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				);
				$table->join( 'customers', 'employeeNumber', 'salesRepEmployeeNumber', '>' );
			}
		);

		// alias.
		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'JOIN customers AS t1 ON employeeNumber = salesRepEmployeeNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				);
				$table->join( 'customers', 'employeeNumber', 'salesRepEmployeeNumber', '=', 't1' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'JOIN customers AS t2 ON employeeNumber = salesRepEmployeeNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				);
				$table->join( 'customers AS t2', 'employeeNumber', 'salesRepEmployeeNumber' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'LEFT JOIN customers ON employeeNumber = salesRepEmployeeNumber ' .
			'LEFT JOIN payments ON payments.customerNumber = customers.customerNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				);
				$table->leftJoin( 'customers', 'employeeNumber', 'salesRepEmployeeNumber' );
				$table->leftJoin( 'payments', 'payments.customerNumber', 'customers.customerNumber' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT lastName, firstName, customerName, checkNumber, amount ' .
			'FROM phpunit ' .
			'RIGHT JOIN customers ON employeeNumber = salesRepEmployeeNumber ' .
			'RIGHT JOIN payments ON payments.customerNumber = customers.customerNumber ' .
			'ORDER BY customerName, checkNumber',
			'Select',
			function( $table ) {
				$table->select(
					array(
						'lastName',
						'firstName',
						'customerName',
						'checkNumber',
						'amount',
					)
				)->orderBy(
					array(
						'customerName',
						'checkNumber',
					)
				);
				$table->rightJoin( 'customers', 'employeeNumber', 'salesRepEmployeeNumber' );
				$table->rightJoin( 'payments', 'payments.customerNumber', 'customers.customerNumber' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_groupby() {
		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS incoming, target_post_id AS post_id FROM phpunit WHERE target_post_id IN (100, 120, 123) GROUP BY target_post_id',
			'Select',
			function( $table ) {
				$table->selectCount( 'id', 'incoming' )->select( 'target_post_id AS post_id' )
					->whereIn( 'target_post_id', array( 100, 120, 123 ) )
					->groupBy( 'target_post_id' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS incoming, target_post_id AS post_id FROM phpunit WHERE target_post_id IN (100, 120, 123) GROUP BY target_post_id HAVING count(id) > 25',
			'Select',
			function( $table ) {
				$table->selectCount( 'id', 'incoming' )->select( 'target_post_id AS post_id' )
					->whereIn( 'target_post_id', array( 100, 120, 123 ) )
					->groupBy( 'target_post_id' )
					->having( 'count(id)', '>', 25 );
			}
		);
	}

	/**
	 * Assert SQL Query.
	 *
	 * @param  [type] $expected  [description].
	 * @param  [type] $translate [description].
	 * @param  [type] $callback  [description].
	 */
	protected function assertQueryTranslation( $expected, $translate, $callback ) {
		$builder = $this->create_builder();
		call_user_func_array( $callback, array( $builder ) );
		$query = $this->invokeMethod( $builder, 'translate' . $translate );
		$this->assertEquals( $expected, $query );
	}

	/**
	 * [create_builder description]
	 *
	 * @return [type] [description]
	 */
	protected function create_builder() {
		return new \MyThemeShop\Database\Query_Builder( 'phpunit' );
	}

	/**
	 * [log description]
	 *
	 * @param  [type] $text [description].
	 */
	protected function log( $text ) {
		fwrite( STDERR, $text );
	}
}
