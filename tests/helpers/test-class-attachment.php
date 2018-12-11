<?php
/**
 * The Attachment helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests\Helpers;

use UnitTestCase;
use MyThemeShop\Helpers\Attachment;

/**
 * TestAttachment class.
 */
class TestAttachment extends UnitTestCase {

	/**
	 * Grabs an image alt text.
	 */
	public function test_get_alt_tag() {
		$attachment = $this->factory()->post->create( array( 'post_type' => 'attachment' ) );

		// Empty.
		$this->assertEmpty( Attachment::get_alt_tag( 1 ) );

		// Not Empty.
		update_post_meta( $attachment, '_wp_attachment_image_alt', 'shakeeb-ahmed' );
		$this->assertEquals( Attachment::get_alt_tag( $attachment ), 'shakeeb-ahmed' );
	}
}
