<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Service\UserLoginService;

/**
 * Initializes logout the subscription user.
 */
class SubscriptionUserLogoutRoute implements RouteInterface {

	/**
	 * @var UserLoginService
	 */
	private $user_login_service;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->user_login_service = new UserLoginService( $plugin_settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_USER_LOGOUT;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_http_methods(): array {
		return [ 'GET', 'DELETE' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_desc() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_params(): array {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_response( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->get_response_data(),
			202
		);
	}

	/**
	 * @return bool[] Response data.
	 */
	private function get_response_data(): array {
		return [
			'status' => $this->user_login_service->logout_user(),
		];
	}
}
