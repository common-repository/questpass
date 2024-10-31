<?php

namespace Questpass\Repository;

use Questpass\Migration\CreateSubscriptionUsersMigration;
use Questpass\Model\User;

/**
 * .
 */
class UserRepository {

	/**
	 * @var \wpdb
	 */
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * @param string|null $user_id .
	 *
	 * @return User|null
	 */
	public function get_user_by_id( string $user_id = null ) {
		if ( $user_id === null ) {
			return null;
		}
		return $this->find_user_by( 'user_id', $user_id );
	}

	/**
	 * @param string|null $user_token .
	 *
	 * @return User|null
	 */
	public function get_user_by_token( string $user_token = null ) {
		if ( $user_token === null ) {
			return null;
		}
		return $this->find_user_by( 'user_token', $user_token );
	}

	/**
	 * @param User $user .
	 *
	 * @return void
	 */
	public function update_user( User $user ) {
		$exists_user = $this->find_user_by( 'user_id', $user->get_user_id() );

		if ( $exists_user === null ) {
			$this->wpdb->insert(
				$this->wpdb->base_prefix . CreateSubscriptionUsersMigration::TABLE_NAME,
				$this->get_query_params( $user ),
				'%s'
			);
		} elseif ( $user->get_user_id() !== null ) {
			$this->wpdb->update(
				$this->wpdb->base_prefix . CreateSubscriptionUsersMigration::TABLE_NAME,
				$this->get_query_params( $user ),
				[
					'user_id' => $this->wpdb->_real_escape( $user->get_user_id() ),
				],
				'%s',
				'%s'
			);
		}
	}

	/**
	 * @param string      $where_key   .
	 * @param string|null $where_value .
	 *
	 * @return User|null
	 */
	private function find_user_by( string $where_key, string $where_value = null ) {
		$result = $this->wpdb->get_row(
			sprintf(
				'SELECT user_id, user_email, user_token, recurring_payments, subscription_date, access_token, refresh_token, token_expires_at
					FROM %1$s WHERE %2$s = "%3$s"',
				$this->wpdb->base_prefix . CreateSubscriptionUsersMigration::TABLE_NAME, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$where_key, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$where_value // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			),
			ARRAY_A
		);
		if ( ! $result || ! is_array( $result ) ) {
			return null;
		}

		return new User(
			$result['user_id'] ?? null,
			$result['user_email'] ?? null,
			$result['user_token'] ?? null,
			$result['recurring_payments'] ?? null,
			( $result['subscription_date'] ) ? new \DateTime( $result['subscription_date'] ) : null,
			$result['access_token'] ?? null,
			$result['refresh_token'] ?? null,
			( $result['token_expires_at'] ) ? new \DateTime( $result['token_expires_at'] ) : null
		);
	}

	/**
	 * @param User $user .
	 *
	 * @return mixed[]
	 */
	private function get_query_params( User $user ): array {
		return [
			'user_id'            => ( $user->get_user_id() !== null )
				? $this->wpdb->_real_escape( $user->get_user_id() )
				: null,
			'user_email'         => ( $user->get_user_email() !== null )
				? $this->wpdb->_real_escape( $user->get_user_email() )
				: null,
			'user_token'         => $this->wpdb->_real_escape( $user->get_user_token() ),
			'recurring_payments' => $user->get_recurring_payments_status(),
			'subscription_date'  => ( $user->get_subscription_date() !== null )
				? $this->wpdb->_real_escape( $user->get_subscription_date()->format( 'Y-m-d H:i:s' ) )
				: null,
			'access_token'       => ( $user->get_access_token() !== null )
				? $this->wpdb->_real_escape( $user->get_access_token() )
				: null,
			'refresh_token'      => ( $user->get_refresh_token() !== null )
				? $this->wpdb->_real_escape( $user->get_refresh_token() )
				: null,
			'token_expires_at'   => ( $user->get_token_expires_at() !== null )
				? $this->wpdb->_real_escape( $user->get_token_expires_at()->format( 'Y-m-d H:i:s' ) )
				: null,
		];
	}
}
