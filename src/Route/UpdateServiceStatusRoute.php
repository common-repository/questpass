<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;
use Questpass\Service\ServiceStatusUpdater;

/**
 * Updates the service status using the Questpass API.
 */
class UpdateServiceStatusRoute extends ProtectedRouteAbstract {

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_UPDATE_SERVICE_STATUS;
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
	public function get_route_desc() {
		return 'Updates the service status using the Questpass API (service_status, subscriptions_available, has_active_campaigns).';
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
	 * @return bool[] Response data.
	 */
	private function get_response_data(): array {
		return [
			'status' => ( new ServiceStatusUpdater( $this->plugin_settings_repository, $this->service_status_repository ) )
				->update_status(),
		];
	}
}
