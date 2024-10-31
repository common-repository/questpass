<?php

namespace Questpass\Route;

use Questpass\PluginInfo;
use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Service\DebugDataProvider;

/**
 * Returns debug information about WordPress environmental.
 */
class DebugRoute extends ProtectedRouteAbstract {

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository,
		PluginInfo $plugin_info
	) {
		parent::__construct( $plugin_settings_repository, $service_status_repository );
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_DEBUG;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_http_methods(): array {
		return [ 'GET' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_desc(): string {
		return 'Returns debug information about WordPress environmental.';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_response( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->get_response_data(),
			200
		);
	}

	/**
	 * @return mixed[] Response data.
	 */
	private function get_response_data(): array {
		$debug_data = new DebugDataProvider( $this->plugin_info );

		return [
			'wordpress_version' => $debug_data->get_wordpress_version(),
			'php_version'       => $debug_data->get_php_version(),
			'plugin_version'    => $debug_data->get_plugin_version(),
			'plugins'           => $debug_data->get_plugins(),
			'themes'            => $debug_data->get_themes(),
		];
	}
}
