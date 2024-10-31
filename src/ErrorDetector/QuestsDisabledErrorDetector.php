<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\QuestsDisabledError;
use Questpass\Repository\PluginSettingsRepository;

/**
 * Checks if the display of quests is blocked in the plugin settings.
 */
class QuestsDisabledErrorDetector implements ErrorDetectorInterface {

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

		if ( $plugin_settings->get_master_switch() === true ) {
			return null;
		}
		return new QuestsDisabledError();
	}
}
