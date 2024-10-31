<?php

namespace Questpass\Repository;

use Questpass\Model\ServiceStatus;

/**
 * Manages values of the service status.
 */
class ServiceStatusRepository {

	const CONNECTION_STATUS_OPTION_NAME       = 'questpass_connection_status';
	const SERVICE_STATUS_OPTION_NAME          = 'questpass_service_status';
	const SUBSCRIPTIONS_STATUS_OPTION_NAME    = 'questpass_subscriptions_status';
	const ACTIVE_CAMPAIGNS_STATUS_OPTION_NAME = 'questpass_active_campaigns_status';
	const JAVASCRIPT_OPTION_NAME              = 'questpass_javascript';
	const JAVASCRIPT_UPDATE_DATE_OPTION_NAME  = 'questpass_javascript_update_date';

	/**
	 * @var ServiceStatus
	 */
	private $service_status = null;

	/**
	 * @return ServiceStatus
	 */
	public function get_status(): ServiceStatus {
		if ( $this->service_status === null ) {
			$this->service_status = new ServiceStatus(
				( get_option( self::CONNECTION_STATUS_OPTION_NAME, null ) === '1' ),
				( get_option( self::SERVICE_STATUS_OPTION_NAME, null ) === '1' ),
				( get_option( self::SUBSCRIPTIONS_STATUS_OPTION_NAME, null ) === '1' ),
				( get_option( self::ACTIVE_CAMPAIGNS_STATUS_OPTION_NAME, null ) === '1' ),
				get_option( self::JAVASCRIPT_OPTION_NAME, null ) ?: null,
				get_option( self::JAVASCRIPT_UPDATE_DATE_OPTION_NAME, null ) ?: null
			);
		}
		return $this->service_status;
	}

	/**
	 * Saves values of the service status.
	 *
	 * @param ServiceStatus $service_status .
	 *
	 * @return void
	 */
	public function update_status( ServiceStatus $service_status ) {
		update_option( self::CONNECTION_STATUS_OPTION_NAME, ( $service_status->get_connection_status() ) ? '1' : '0' );
		update_option( self::SERVICE_STATUS_OPTION_NAME, ( $service_status->get_service_status() ) ? '1' : '0' );
		update_option( self::SUBSCRIPTIONS_STATUS_OPTION_NAME, ( $service_status->get_subscriptions_status() ) ? '1' : '0' );
		update_option( self::ACTIVE_CAMPAIGNS_STATUS_OPTION_NAME, ( $service_status->get_active_campaigns_status() ) ? '1' : '0' );
		update_option( self::JAVASCRIPT_OPTION_NAME, $service_status->get_javascript() );
		update_option( self::JAVASCRIPT_UPDATE_DATE_OPTION_NAME, $service_status->get_javascript_updated_at() );
	}
}
