<?php

namespace Questpass\Plugin;

use Questpass\Exception\MigrationFailedException;
use Questpass\HookableInterface;
use Questpass\Migration\MigrationBuilder;
use Questpass\PluginInfo;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Service\ServiceStatusUpdater;
use Questpass\Service\UpdateJavascript;

/**
 * Runs actions during the plugin activation.
 */
class Activation implements HookableInterface {

	const CONSTANT_DEFAULT_API_TOKEN  = 'DEFAULT_API_TOKEN';
	const CONSTANT_DEFAULT_API_CLIENT = 'DEFAULT_API_CLIENT';
	const CONSTANT_DEFAULT_API_SECRET = 'DEFAULT_API_SECRET';

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository,
		PluginInfo $plugin_info
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
		$this->plugin_info                = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'activate_' . $this->plugin_info->get_plugin_basename(), [ $this, 'prevent_network_activation' ] );
		add_action( 'activate_' . $this->plugin_info->get_plugin_basename(), [ $this, 'set_default_config' ] );
		add_action( 'activate_' . $this->plugin_info->get_plugin_basename(), [ $this, 'create_tables' ] );
	}

	/**
	 * Blocks the ability to activate the plugin for the Multisite Network.
	 *
	 * @param bool $network_wide Whether to enable the plugin for all sites in the network or just the current site.
	 *
	 * @return void
	 * @internal
	 */
	public function prevent_network_activation( bool $network_wide ) {
		if ( $network_wide ) {
			wp_die( esc_html__( 'This plugin cannot be activated for the Multisite Network.', 'questpass' ) );
		}
	}

	/**
	 * Updates the plugin settings based on the values added in constants.
	 *
	 * @return void
	 * @internal
	 */
	public function set_default_config() {
		$plugin_settings = $this->plugin_settings_repository->get_settings();
		$api_token       = $this->get_default_config_value( self::CONSTANT_DEFAULT_API_TOKEN );
		$api_client      = $this->get_default_config_value( self::CONSTANT_DEFAULT_API_CLIENT );
		$api_secret      = $this->get_default_config_value( self::CONSTANT_DEFAULT_API_SECRET );

		if ( ( $api_token !== null ) && ( $plugin_settings->get_api_token() === '' ) ) {
			$plugin_settings->set_api_token( $api_token );
		}
		if ( ( $api_client !== null ) && ( $plugin_settings->get_api_client() === '' ) ) {
			$plugin_settings->set_api_client( $api_client );
		}
		if ( ( $api_secret !== null ) && ( $plugin_settings->get_api_secret() === '' ) ) {
			$plugin_settings->set_api_secret( $api_secret );
		}

		$this->plugin_settings_repository->save_settings( $plugin_settings );

		( new ServiceStatusUpdater( $this->plugin_settings_repository, $this->service_status_repository ) )->update_status();
		( new UpdateJavascript( $this->plugin_settings_repository, $this->service_status_repository ) )->update_javascript();
	}

	/**
	 * @param string $constant_key .
	 *
	 * @return string|null
	 */
	private function get_default_config_value( string $constant_key ) {
		$constant_name  = 'QUESTPASS_' . $constant_key;
		$constant_value = constant( $constant_name );

		if ( ( $constant_value === null ) || ( $constant_value === '__' . $constant_key . '__' ) ) {
			return null;
		}
		return $constant_value;
	}

	/**
	 * Creates tables in the database.
	 *
	 * @return void
	 * @throws MigrationFailedException
	 * @internal
	 */
	public function create_tables() {
		( new MigrationBuilder() )->begin_migration();
	}
}
