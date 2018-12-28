<?php
/**
 * The Notification center handles notifications storage and display.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Tests
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Tests;

use UnitTestCase;

/**
 * TestNotificationCenter class.
 */
class TestNotificationCenter extends UnitTestCase {

	private $manager;

	public function setUp() {
		parent::setUp();
		$this->manager = new \MyThemeShop\Notification_Center;
	}

	/**
	 * Add notification
	 */
	public function test_add() {
		$this->assertNull( $this->manager->get_notification_by_id( 'test' ) );
		$this->manager->add( 'Test message.', [ 'id' => 'test' ] );
		$this->assertNotNull( $this->manager->get_notification_by_id( 'test' ) );

		$this->assertArrayEquals(
			$this->manager->get_notifications(),
			[ [ 'message' => 'Test message.' ] ]
		);
	}

	/**
	 * Retrieve the notifications from storage
	 *
	 * @return array Notification[] Notifications
	 */
	public function get_from_storage() {
		if ( $this->retrieved ) {
			return;
		}

		$this->retrieved = true;
		$notifications   = get_option( $this->storage_key );

		// Check if notifications are stored.
		if ( empty( $notifications ) ) {
			return;
		}

		if ( is_array( $notifications ) ) {
			foreach ( $notifications as $notification ) {
				$this->notifications[] = new Notification(
					$notification['message'],
					$notification['options']
				);
			}
		}
	}

	/**
	 * Display the notifications.
	 */
	public function display() {

		// Never display notifications for network admin.
		if ( $this->is_network_admin() ) {
			return;
		}

		$notifications = $this->get_sorted_notifications();
		if ( empty( $notifications ) ) {
			return;
		}

		foreach ( $notifications as $notification ) {
			if ( $notification->can_display() ) {
				echo $notification;
			}
		}
	}

	/**
	 * Save persistent or transactional notifications to storage.
	 *
	 * We need to be able to retrieve these so they can be dismissed at any time during the execution.
	 */
	public function update_storage() {
		$notifications = $this->get_notifications();
		$notifications = array_filter( $notifications, [ $this, 'remove_notification' ] );

		/**
		 * Filter: 'wp_helpers_notifications_before_storage' - Allows developer to filter notifications before saving them.
		 *
		 * @param Notification[] $notifications
		 */
		$notifications = apply_filters( 'wp_helpers_notifications_before_storage', $notifications );

		// No notifications to store, clear storage.
		if ( empty( $notifications ) ) {
			delete_option( $this->storage_key );

			return;
		}

		$notifications = array_map( [ $this, 'notification_to_array' ], $notifications );

		// Save the notifications to the storage.
		update_option( $this->storage_key, $notifications );
	}

	/**
	 * Dismiss persistent notice.
	 */
	public function notice_dismissible() {
		$notification_id  = filter_input( INPUT_POST, 'notificationId' );
		$notification_key = filter_input( INPUT_POST, 'notificationKey' );

		$this->verify_nonce( $notification_id );

		$notification = $this->get_notification_by_id( $notification_id );
		if ( ! is_null( $notification ) ) {
			$notification->dismiss();
		}
	}

	/**
	 * Remove notification after it has been displayed.
	 *
	 * @param Notification $notification Notification to remove.
	 */
	public function remove_notification( Notification $notification ) {
		if ( ! $notification->is_displayed() ) {
			return true;
		}

		if ( $notification->is_persistent() ) {
			return true;
		}

		return false;
	}

	/**
	 * Provide a way to verify present notifications
	 *
	 * @return array|Notification[] Registered notifications.
	 */
	private function get_notifications() {
		return $this->notifications;
	}

	/**
	 * Return the notifications sorted on type and priority
	 *
	 * @return array|Notification[] Sorted Notifications
	 */
	private function get_sorted_notifications() {
		$notifications = $this->get_notifications();
		if ( empty( $notifications ) ) {
			return [];
		}

		// Sort by severity, error first.
		usort( $notifications, [ $this, 'sort_notifications' ] );

		return $notifications;
	}

	/**
	 * Get the notification by ID
	 *
	 * @param  string $notification_id The ID of the notification to search for.
	 * @return null|Notification
	 */
	private function get_notification_by_id( $notification_id ) {
		foreach ( $this->notifications as &$notification ) {
			if ( $notification_id === $notification->args( 'id' ) ) {
				return $notification;
			}
		}
		return null;
	}

	/**
	 * Sort on type then priority
	 *
	 * @param  Notification $first  Compare with B.
	 * @param  Notification $second Compare with A.
	 * @return int 1, 0 or -1 for sorting offset.
	 */
	private function sort_notifications( Notification $first, Notification $second ) {

		if ( 'error' === $first->args( 'type' ) ) {
			return -1;
		}

		if ( 'error' === $second->args( 'type' ) ) {
			return 1;
		}

		return 0;
	}

	/**
	 * Convert Notification to array representation
	 *
	 * @param  Notification $notification Notification to convert.
	 * @return array
	 */
	private function notification_to_array( Notification $notification ) {
		return $notification->to_array();
	}

	/**
	 * Check if is network admin.
	 *
	 * @return bool
	 */
	private function is_network_admin() {
		return function_exists( 'is_network_admin' ) && is_network_admin();
	}
}
