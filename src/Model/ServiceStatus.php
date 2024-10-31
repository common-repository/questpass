<?php

namespace Questpass\Model;

/**
 * Stores values of the Questpass service.
 */
class ServiceStatus {

	/**
	 * @var bool|null
	 */
	private $connection_status;

	/**
	 * @var bool|null
	 */
	private $service_status;

	/**
	 * @var bool|null
	 */
	private $subscriptions_status;

	/**
	 * @var bool|null
	 */
	private $active_campaigns_status;

	/**
	 * @var string|null
	 */
	private $javascript;

	/**
	 * @var int|null
	 */
	private $javascript_updated_at;

	/**
	 * @param bool   $connection_status       .
	 * @param bool   $service_status          .
	 * @param bool   $subscriptions_status    .
	 * @param bool   $active_campaigns_status .
	 * @param string $javascript              .
	 * @param int    $javascript_updated_at   .
	 */
	public function __construct(
		bool $connection_status = null,
		bool $service_status = null,
		bool $subscriptions_status = null,
		bool $active_campaigns_status = null,
		string $javascript = null,
		int $javascript_updated_at = null
	) {
		$this->set_connection_status( $connection_status );
		$this->set_service_status( $service_status );
		$this->set_subscriptions_status( $subscriptions_status );
		$this->set_active_campaigns_status( $active_campaigns_status );
		$this->set_javascript( $javascript );
		$this->set_javascript_updated_at( $javascript_updated_at );
	}

	/**
	 * @return bool|null
	 */
	public function get_connection_status() {
		return $this->connection_status;
	}

	public function set_connection_status( bool $connection_status = null ): self {
		$this->connection_status = $connection_status;
		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function get_service_status() {
		return $this->service_status;
	}

	public function set_service_status( bool $service_status = null ): self {
		$this->service_status = $service_status;
		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function get_subscriptions_status() {
		return $this->subscriptions_status;
	}

	public function set_subscriptions_status( bool $subscriptions_status_status = null ): self {
		$this->subscriptions_status = $subscriptions_status_status;
		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function get_active_campaigns_status() {
		return $this->active_campaigns_status;
	}

	public function set_active_campaigns_status( bool $active_campaigns_status = null ): self {
		$this->active_campaigns_status = $active_campaigns_status;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_javascript() {
		return $this->javascript;
	}

	public function set_javascript( string $javascript = null ): self {
		$this->javascript = $javascript;
		return $this->set_javascript_updated_at( time() );
	}

	/**
	 * @return int|null
	 */
	public function get_javascript_updated_at() {
		return $this->javascript_updated_at;
	}

	private function set_javascript_updated_at( int $javascript_update_date = null ): self {
		$this->javascript_updated_at = $javascript_update_date;
		return $this;
	}
}
