<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\MissingApiConfigError;
use Questpass\Repository\PluginSettingsRepository;

/**
 * Checks if API configuration data is entered.
 */
class MissingApiConfigErrorDetector implements ErrorDetectorInterface {

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->plugin_settings_repository = $plugin_settings_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		$plugin_settings = $this->plugin_settings_repository->get_settings();

		if ( ( $plugin_settings->get_api_token() === '' )
			|| ( $plugin_settings->get_api_client() === '' )
			|| ( $plugin_settings->get_api_secret() === '' ) ) {
			return new MissingApiConfigError();
		}
		return null;
	}
}
