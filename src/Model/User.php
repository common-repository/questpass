<?php

namespace Questpass\Model;

/**
 * Stores values of a subscription user.
 */
class User {

	/**
	 * @var string
	 */
	private $user_token;

	/**
	 * @var string|null
	 */
	private $user_id;

	/**
	 * @var string|null
	 */
	private $user_email;

	/**
	 * @var bool
	 */
	private $recurring_payments_status;

	/**
	 * @var \DateTime|null
	 */
	private $subscription_date;

	/**
	 * @var string|null
	 */
	private $access_token;

	/**
	 * @var string|null
	 */
	private $refresh_token;

	/**
	 * @var \DateTime|null
	 */
	private $token_expires_at;

	public function __construct(
		string $user_id = null,
		string $user_email = null,
		string $user_token = null,
		bool $recurring_payments = false,
		\DateTime $subscription_date = null,
		string $access_token = null,
		string $refresh_token = null,
		\DateTime $token_expires_at = null
	) {
		$this->set_user_id( $user_id );
		$this->set_user_email( $user_email );
		$this->set_user_token( ( $user_token !== null ) ? $user_token : $this->generate_unique_user_token() );
		$this->set_recurring_payments_status( $recurring_payments );
		$this->set_subscription_date( $subscription_date );
		$this->set_access_token( $access_token );
		$this->set_refresh_token( $refresh_token );
		$this->set_token_expires_at( $token_expires_at );
	}

	public function get_user_token(): string {
		return $this->user_token;
	}

	public function set_user_token( string $user_token ): self {
		$this->user_token = $user_token;
		return $this;
	}

	public function generate_unique_user_token(): string {
		$token = md5( uniqid( '', true ) );

		try {
			$token .= bin2hex( random_bytes( 16 ) );
			return $token;
		} catch ( \Exception $e ) {
			$token .= str_repeat( '0', 32 );
			return $token;
		}
	}

	/**
	 * @return string|null
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	public function set_user_id( string $user_id = null ): self {
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_user_email() {
		return $this->user_email;
	}

	public function set_user_email( string $user_email = null ): self {
		$this->user_email = $user_email;
		return $this;
	}

	public function get_recurring_payments_status(): bool {
		return $this->recurring_payments_status;
	}

	public function set_recurring_payments_status( bool $recurring_payments_status ): self {
		$this->recurring_payments_status = $recurring_payments_status;
		return $this;
	}

	/**
	 * @return \DateTime|null
	 */
	public function get_subscription_date() {
		return $this->subscription_date;
	}

	public function set_subscription_date( \DateTime $subscription_date = null ): self {
		$this->subscription_date = $subscription_date;

		return $this;
	}

	public function is_subscription_active(): bool {
		if ( $this->subscription_date === null ) {
			return false;
		}

		return ( $this->subscription_date >= new \DateTime() );
	}

	public function get_subscription_days_left(): int {
		if ( $this->subscription_date === null ) {
			return 0;
		}

		$days = ( ( $this->subscription_date->getTimestamp() - time() ) / ( 24 * 60 * 60 ) );
		return ( $days < 0 ) ? 0 : (int) floor( $days );
	}

	/**
	 * @return string|null
	 */
	public function get_access_token() {
		return $this->access_token;
	}

	public function set_access_token( string $access_token = null ): self {
		$this->access_token = $access_token;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_refresh_token() {
		return $this->refresh_token;
	}

	public function set_refresh_token( string $refresh_token = null ): self {
		$this->refresh_token = $refresh_token;
		return $this;
	}

	/**
	 * @return \DateTime|null
	 */
	public function get_token_expires_at() {
		return $this->token_expires_at;
	}

	public function set_token_expires_at( \DateTime $token_expires_at = null ): self {
		$this->token_expires_at = $token_expires_at;

		return $this;
	}
}
