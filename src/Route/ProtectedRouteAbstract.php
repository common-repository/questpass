<?php

namespace Questpass\Route;

use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;

/**
 * Supports an endpoint that requires passing the secret key.
 */
abstract class ProtectedRouteAbstract implements RouteInterface {

	/**
	 * @var PluginSettingsRepository
	 */
	protected $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	protected $service_status_repository;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return mixed[]
	 */
	public function get_route_params(): array {
		return [
			'secret' => [
				'description'       => 'OAuth Secret',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					$api_secret = $this->plugin_settings_repository->get_settings()->get_api_secret();
					return ( $api_secret && ( $api_secret === $value ) );
				},
			],
		];
	}
}
