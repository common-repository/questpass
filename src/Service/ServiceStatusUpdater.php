<?php

namespace Questpass\Service;

use Questpass\Logger\LoggerInterface;
use Questpass\Logger\WordpressLogger;
use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use QuestpassVendor\GuzzleHttp\Client;
use QuestpassVendor\GuzzleHttp\Exception\GuzzleException;

/**
 * Updates the service status using the Questpass API.
 */
class ServiceStatusUpdater {

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository,
		LoggerInterface $logger = null
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
		$this->logger                     = $logger ?: new WordpressLogger();
	}

	public function update_status(): bool {
		$api_url = sprintf(
			QuestpassConstants::API_SERVICES_STATUS_URL,
			$this->plugin_settings_repository->get_settings()->get_api_token()
		);

		try {
			$request  = ( new Client( [ 'verify' => false ] ) )->request( 'GET', $api_url );
			$response = json_decode( $request->getBody()->getContents(), true );

			$this->update_status_data( $response );
			return true;
		} catch ( GuzzleException $e ) {
			$this->update_status_data();
			$this->logger->error( $e->getMessage(), __METHOD__ );
		}

		return false;
	}

	/**
	 * @param mixed[]|null $response_data .
	 *
	 * @return void
	 */
	private function update_status_data( array $response_data = null ) {
		$service_status      = (string) ( $response_data['status'] ?? '' );
		$subscription_status = (string) ( $response_data['subscription'] ?? '' );
		$campaigns_status    = (string) ( $response_data['hasActiveCampaigns'] ?? '' );

		$this->service_status_repository->update_status(
			$this->service_status_repository->get_status()
				->set_connection_status( ( $response_data !== null ) )
				->set_service_status( ( $service_status === '1' ) )
				->set_subscriptions_status( ( $subscription_status === '1' ) )
				->set_active_campaigns_status( ( $campaigns_status === '1' ) )
		);
	}
}
