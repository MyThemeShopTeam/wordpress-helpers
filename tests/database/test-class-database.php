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
	 * MySql grammar tests
	 */
	public function test_select_simple() {

		$this->assertQueryTranslation(
			'select * from phpunit',
			'Select',
			function( $table ) {

			}
		);

		$this->assertQueryTranslation(
			'select distinct * from phpunit',
			'Select',
			function( $table ) {
				$table->distinct();
			}
		);

		$this->assertQueryTranslation(
			'select SQL_CALC_FOUND_ROWS * from phpunit',
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
			'select id from phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id' );
			}
		);

		// Comma seperated fields.
		$this->assertQueryTranslation(
			'select id, foo from phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id, foo' );
			}
		);

		// With array.
		$this->assertQueryTranslation(
			'select id, foo from phpunit',
			'Select',
			function( $table ) {
				$table->select( [ 'id', 'foo' ] );
			}
		);

		// With alias as string.
		$this->assertQueryTranslation(
			'select id, foo as f from phpunit',
			'Select',
			function( $table ) {
				$table->select( 'id, foo as f' );
			}
		);

		// With array with alias.
		$this->assertQueryTranslation(
			'select id as d, foo as f from phpunit',
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
			'select count(*), foo as f from phpunit',
			'Select',
			function( $table ) {
				$table->selectCount()->select( 'foo as f' );
			}
		);

		$this->assertQueryTranslation(
			'select count(id) as count from phpunit',
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
			'select sum(id) as count from phpunit',
			'Select',
			function( $table ) {
				$table->selectSum( 'id', 'count' );
			}
		);

		$this->assertQueryTranslation(
			'select avg(id) as average from phpunit',
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
			'select * from phpunit where id = 2',
			'Select',
			function( $table ) {
				$table->where( 'id', 2 );
			}
		);

		// Diffrent expression.
		$this->assertQueryTranslation(
			'select * from phpunit where id != 42',
			'Select',
			function( $table ) {
				$table->where( 'id', '!=', 42 );
			}
		);

		// 2 wheres AND.
		$this->assertQueryTranslation(
			'select * from phpunit where id = 2 and active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 2 )->where( 'active', 1 );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where id = 2 and active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 2 )->where( 'active', 1 );
			}
		);

		// 2 wheres OR.
		$this->assertQueryTranslation(
			'select * from phpunit where id = 42 or active = 1',
			'Select',
			function( $table ) {
				$table->where( 'id', 42 )->orWhere( 'active', 1 );
			}
		);

		// Nesting.
		$this->assertQueryTranslation(
			'select * from phpunit where ( a = \'b\' or c = \'d\' )',
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
			'select * from phpunit where ( a > 10 and a < 20 )',
			'Select',
			function( $table ) {
				$table->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'and'
				);
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where a = 1 or ( a > 10 and a < 20 )',
			'Select',
			function( $table ) {
				$table->where( 'a', 1 )
				->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'and'
				);
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where a = 1 or ( a > 10 and a < 20 ) and c = 30',
			'Select',
			function( $table ) {
				$table->where( 'a', 1 )
				->orWhere(
					array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					),
					'and'
				)
				->where( 'c', 30 );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where is_active = 1 and ( options like \'a\' or options like \'b\' )',
			'Select',
			function( $table ) {
				$table->where( 'is_active', 1 )
					->where(
						array(
							array( 'options', 'like', 'a' ),
							array( 'options', 'like', 'b' ),
						),
						'or'
					);
			}
		);

		// Where in / not in.
		$this->assertQueryTranslation(
			'select * from phpunit where id in (23, 25, 30)',
			'Select',
			function( $table ) {
				$table->whereIn( 'id', array( 23, 25, 30 ) );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where skills in (\'php\', \'javascript\', \'ruby\')',
			'Select',
			function( $table ) {
				$table->whereIn( 'skills', array( 'php', 'javascript', 'ruby' ) );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where id not in (23, 25, 30)',
			'Select',
			function( $table ) {
				$table->whereNotIn( 'id', array( 23, 25, 30 ) );
			}
		);

		// Where between / not between.
		$this->assertQueryTranslation(
			'select * from phpunit where id between 10 and 100',
			'Select',
			function( $table ) {
				$table->whereBetween( 'id', array( 10, 100 ) );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where dates between \'10-04-2018\' and \'10-09-2018\'',
			'Select',
			function( $table ) {
				$table->whereBetween( 'dates', array( '10-04-2018', '10-09-2018' ) );
			}
		);

		$this->assertQueryTranslation(
			'select * from phpunit where id not between 10 and 100',
			'Select',
			function( $table ) {
				$table->whereNotBetween( 'id', array( 10, 100 ) );
			}
		);

		// Where is null / is not null.
		$this->assertQueryTranslation(
			'select * from phpunit where name is null',
			'Select',
			function( $table ) {
				$table->whereNull( 'name' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_limit() {

		// Simple.
		$this->assertQueryTranslation(
			'select * from phpunit limit 0, 1',
			'Select',
			function( $table ) {
				$table->limit( 1 );
			}
		);

		// With offset.
		$this->assertQueryTranslation(
			'select * from phpunit limit 20, 10',
			'Select',
			function( $table ) {
				$table->limit( 10, 20 );
			}
		);

		// Pagination.
		$this->assertQueryTranslation(
			'select * from phpunit limit 20, 10',
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
			'select * from phpunit order by id asc',
			'Select',
			function( $table ) {
				$table->orderBy( 'id' );
			}
		);

		// Other direction.
		$this->assertQueryTranslation(
			'select * from phpunit order by id desc',
			'Select',
			function( $table ) {
				$table->orderBy( 'id', 'desc' );
			}
		);

		// More keys comma separated.
		$this->assertQueryTranslation(
			'select * from phpunit order by firstname desc, lastname desc',
			'Select',
			function( $table ) {
				$table->orderBy( 'firstname, lastname', 'desc' );
			}
		);

		// Multipe sortings diffrent direction.
		$this->assertQueryTranslation(
			'select * from phpunit order by firstname asc, lastname desc',
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
			'select * from phpunit order by firstname <> nick',
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
			'update phpunit set foo = \'bar\'',
			'Update',
			function( $table ) {
				$table->set( 'foo', 'bar' );
			}
		);

		// Multiple.
		$this->assertQueryTranslation(
			'update phpunit set foo = \'bar\', bar = \'foo\'',
			'Update',
			function( $table ) {
				$table
				->set( 'foo', 'bar' )
				->set( 'bar', 'foo' );
			}
		);

		// Array.
		$this->assertQueryTranslation(
			'update phpunit set foo = \'bar\', bar = \'foo\'',
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
			'update phpunit set foo = \'bar\', bar = \'foo\' where id = 1 limit 0, 1',
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
			'delete from phpunit where id = 1 limit 0, 1',
			'Delete',
			function( $table ) {
				$table->where( 'id', 1 )->limit( 1 );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_groupby() {
		$this->assertQueryTranslation(
			'select count(id) as incoming, target_post_id as post_id from phpunit where target_post_id in (100, 120, 123) group by target_post_id',
			'Select',
			function( $table ) {
				$table->selectCount( 'id', 'incoming' )->select( 'target_post_id as post_id' )
					->whereIn( 'target_post_id', array( 100, 120, 123 ) )
					->groupBy( 'target_post_id' );
			}
		);

		$this->assertQueryTranslation(
			'select count(id) as incoming, target_post_id as post_id from phpunit where target_post_id in (100, 120, 123) group by target_post_id having count(id) > 25',
			'Select',
			function( $table ) {
				$table->selectCount( 'id', 'incoming' )->select( 'target_post_id as post_id' )
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
