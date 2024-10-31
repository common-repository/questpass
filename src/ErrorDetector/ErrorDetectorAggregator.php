<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\ErrorInterface;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;

/**
 * Supports generating found errors that prevent the plugin from correct operation.
 */
class ErrorDetectorAggregator {

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
	}

	/**
	 * Returns the detected error.
	 *
	 * @return ErrorInterface|null
	 */
	public function get_error() {
		foreach ( $this->get_error_detectors() as $error_detector ) {
			$error_detected = $error_detector->get_error();
			if ( $error_detected === null ) {
				continue;
			}

			return $error_detected;
		}
		return null;
	}

	/**
	 * @return ErrorDetectorInterface[]
	 */
	private function get_error_detectors(): array {
		return [
			new MissingPhpExtensionsErrorDetector(),
			new MissingApiConfigErrorDetector( $this->plugin_settings_repository ),
			new FailedApiConnectionErrorDetector( $this->service_status_repository ),
			new ServiceInactiveErrorDetector( $this->service_status_repository ),
			new QuestsDisabledErrorDetector( $this->plugin_settings_repository ),
		];
	}
}
