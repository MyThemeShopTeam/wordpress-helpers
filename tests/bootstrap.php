<?php
/**
 * WordPress Helpers Unit Tests Bootstrap
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

/**
 * Bootloader
 */
class WP_Helpers_Tests_Bootstrap {

	/** @var WP_Helpers_Tests_Bootstrap instance */
	protected static $instance = null;

	/** @var string directory where wordpress-tests-lib is installed */
	public $wp_tests_dir;

	/** @var string testing directory */
	public $tests_dir;

	/** @var string plugin directory */
	public $plugin_dir;

	/**
	 * Setup the unit testing environment.
	 */
	public function __construct() {

		// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions, WordPress.PHP.DevelopmentFunctions
		ini_set( 'display_errors', 'on' );
		error_reporting( E_ALL );
		// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions, WordPress.PHP.DevelopmentFunctions

		// Ensure server variable is set for WP email functions.
		// phpcs:disable WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
			$_SERVER['SERVER_NAME'] = 'localhost';
		}
		// phpcs:enable WordPress.VIP.SuperGlobalInputUsage.AccessDetected

		define( 'WC_DOING_PHPUNIT', true );
		echo 'Welcome to the WordPress Helpers SEO Test Suite' . PHP_EOL;
		echo 'Version: 1.0' . PHP_EOL . PHP_EOL;

		$this->tests_dir    = dirname( __FILE__ );
		$this->plugin_dir   = dirname( $this->tests_dir );
		$this->wp_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : '/tmp/wordpress-tests-lib';

		// load test function so tests_add_filter() is available
		require_once $this->wp_tests_dir . '/includes/functions.php';

		// load WC
		tests_add_filter( 'muplugins_loaded', array( $this, 'load_plugin' ) );

		// load the WP testing environment
		require_once $this->wp_tests_dir . '/includes/bootstrap.php';

		// load testing framework
		$this->includes();
	}

	/**
	 * Load WordPress Helpers.
	 */
	public function load_plugin() {
		require_once $this->plugin_dir . '/wordpress-helpers.php';
	}

	/**
	 * Load test caes and factories.
	 */
	public function includes() {
		require dirname( __FILE__ ) . '/framework/class-unit-test-case.php';
	}

	/**
	 * Get the single class instance.
	 *
	 * @return WP_Helpers_Tests_Bootstrap
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

WP_Helpers_Tests_Bootstrap::instance();
