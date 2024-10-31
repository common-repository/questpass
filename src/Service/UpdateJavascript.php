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
 * Forces updating Javascript code stored in the local cache.
 */
class UpdateJavascript {

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

	public function update_javascript(): bool {
		$api_url = sprintf(
			QuestpassConstants::API_SERVICES_JAVASCRIPT_URL,
			$this->plugin_settings_repository->get_settings()->get_api_token()
		);

		try {
			$request  = ( new Client( [ 'verify' => false ] ) )->request( 'GET', $api_url );
			$response = $request->getBody()->getContents();

			$this->update_javascript_data( $response );
			return true;
		} catch ( GuzzleException $e ) {
			$this->logger->error( $e->getMessage(), __METHOD__ );
		}

		return false;
	}

	/**
	 * @param string|null $response_data .
	 *
	 * @return void
	 */
	private function update_javascript_data( string $response_data = null ) {
		$this->service_status_repository->update_status(
			$this->service_status_repository->get_status()->set_javascript( $response_data )
		);
	}
}
