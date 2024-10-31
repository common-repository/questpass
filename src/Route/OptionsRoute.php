<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;

/**
 * Returns the list of service settings and status.
 */
class OptionsRoute extends ProtectedRouteAbstract {

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_OPTIONS;
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
		return 'Returns the list of service settings and status.';
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
		return [
			'plugin_settings'         => [
				'api_token'             => $this->plugin_settings_repository->get_settings()->get_api_token(),
				'api_client'            => $this->plugin_settings_repository->get_settings()->get_api_client(),
				'api_secret'            => $this->plugin_settings_repository->get_settings()->get_api_secret(),
				'post_types'            => $this->plugin_settings_repository->get_settings()->get_post_types(),
				'categories'            => $this->plugin_settings_repository->get_settings()->get_categories(),
				'enable_new_categories' => $this->plugin_settings_repository->get_settings()->get_enable_new_categories(),
				'display_for_users'     => $this->plugin_settings_repository->get_settings()->get_hide_for_users(),
				'default_position'      => $this->plugin_settings_repository->get_settings()->get_default_position(),
			],
			'connection_status'       => $this->service_status_repository->get_status()->get_connection_status(),
			'service_status'          => $this->service_status_repository->get_status()->get_service_status(),
			'subscriptions_available' => $this->service_status_repository->get_status()->get_subscriptions_status(),
			'has_active_campaigns'    => $this->service_status_repository->get_status()->get_active_campaigns_status(),
			'javascript'              => $this->service_status_repository->get_status()->get_javascript(),
			'javascript_update_date'  => $this->service_status_repository->get_status()->get_javascript_updated_at(),
		];
	}
}
