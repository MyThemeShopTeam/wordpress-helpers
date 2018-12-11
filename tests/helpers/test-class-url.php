<?php
/**
 * The URL helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Url;

/**
 * TestUrl class.
 */
class TestUrl extends UnitTestCase {

	/**
	 * Simple check for validating a URL, it must start with http:// or https://.
	 * and pass FILTER_VALIDATE_URL validation.
	 */
	public function test_is_url() {
		// True.
		$this->assertTrue( Url::is_url( 'http://shakeebahmed.com' ) );
		$this->assertTrue( Url::is_url( 'https://shakeebahmed.com' ) );
		$this->assertTrue( Url::is_url( '//shakeebahmed.com' ) );

		// False.
		$this->assertFalse( Url::is_url( false ) );
		$this->assertFalse( Url::is_url( 123456 ) );
		$this->assertFalse( Url::is_url( '/wp-content/plugins/test/style.css' ) );
	}

	/**
	 * Check whether a url is relative.
	 */
	public function test_is_relative() {
		// True.
		$this->assertTrue( Url::is_relative( 'domain/hello-world' ) );
		$this->assertTrue( Url::is_relative( '/domain/hello-world' ) );

		// False.
		$this->assertFalse( Url::is_relative( 'http://domain.com/hello-world' ) );
		$this->assertFalse( Url::is_relative( 'https://domain.com/hello-world' ) );
	}

	/**
	 * Checks whether a url is external.
	 */
	public function test_is_external() {

		// Relative.
		$this->assertFalse( Url::is_external( '' ) );
		$this->assertFalse( Url::is_external( '#section-links' ) );
		$this->assertFalse( Url::is_external( '/domain/hello-world' ) );
		$this->assertFalse( Url::is_external( '/domain/hello-world.html' ) );

		// With FQDN.
		$this->assertFalse( Url::is_external( 'http://example.org/#section-links' ) );
		$this->assertFalse( Url::is_external( 'https://example.org/#section-links' ) );
		$this->assertFalse( Url::is_external( 'http://example.org/domain/hello-world' ) );
		$this->assertFalse( Url::is_external( 'https://example.org/domain/hello-world.html' ) );

		// Other domain.
		$this->assertTrue( Url::is_external( 'http://domain.com/hello-world' ) );
		$this->assertTrue( Url::is_external( 'https://domain.com/hello-world' ) );
		$this->assertTrue( Url::is_external( 'http://yahoo.com' ) );
	}

	/**
	 * Get current page full url.
	 */
	public function test_get_current_url() {
		$this->assertEquals( 'http://example.org', Url::get_current_url() );

		$_SERVER['SERVER_PORT'] = 8080;
		$this->assertEquals( 'http://example.org:8080', Url::get_current_url() );
		unset( $_SERVER['SERVER_PORT'] );
	}

	/**
	 * Get url scheme.
	 */
	public function test_get_scheme() {
		$this->assertEquals( 'http', Url::get_scheme() );
	}

	/**
	 * Some setups like HTTP_HOST, some like SERVER_NAME, it's complicated.
	 */
	public function test_get_host() {
		$this->assertEquals( 'example.org', Url::get_host() );

		unset( $_SERVER['HTTP_HOST'] );
		$_SERVER['SERVER_NAME'] = 'shakeebahmed.com';
		$this->assertEquals( 'shakeebahmed.com', Url::get_host() );
		unset( $_SERVER['SERVER_NAME'] );

		$this->assertEmpty( Url::get_host() );
	}

	/**
	 * Get current request port.
	 */
	public function test_get_port() {
		// Empty.
		$this->assertEquals( '', Url::get_port() );

		$_SERVER['SERVER_PORT'] = 80;
		$this->assertEquals( '', Url::get_port() );

		$_SERVER['SERVER_PORT'] = 443;
		$this->assertEquals( '', Url::get_port() );

		// Not Empty.
		$_SERVER['SERVER_PORT'] = 8080;
		$this->assertEquals( ':8080', Url::get_port() );
	}

	/**
	 * Get parent domain
	 */
	public function test_get_domain() {
		$this->assertFalse( Url::get_domain( '' ) );
		$this->assertEquals( 'localhost', Url::get_domain( 'http://localhost/dummy' ) );
		$this->assertEquals( 'domain.com', Url::get_domain( 'http://domain.com' ) );
		$this->assertEquals( 'domain.com', Url::get_domain( 'https://domain.com' ) );
		$this->assertEquals( 'shakeebahmed.com', Url::get_domain( 'http://shakeebahmed.com/hello-world' ) );
		$this->assertEquals( 'shakeebahmed.com', Url::get_domain( 'https://shakeebahmed.com/hello-world' ) );
		$this->assertEquals( 'meus.reviews', Url::get_domain( 'https://meus.reviews' ) );
	}
}
