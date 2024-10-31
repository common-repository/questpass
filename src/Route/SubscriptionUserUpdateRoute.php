<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Service\UserDataUpdater;

/**
 * Updates data of the subscription user in the local database.
 */
class SubscriptionUserUpdateRoute implements RouteInterface {

	const USER_ID_REGEX = '/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

	/**
	 * @var UserDataUpdater
	 */
	private $user_data_updater;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->user_data_updater = new UserDataUpdater( $plugin_settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_USER_UPDATE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_http_methods(): array {
		return [ 'PUT' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_desc() {
		return 'Updates data of the subscription user in the local database.';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_params(): array {
		return [
			'user_id' => [
				'description'       => 'Id of Questpass user',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					return (bool) preg_match( self::USER_ID_REGEX, $value );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_response( \WP_REST_Request $request ): \WP_REST_Response {
		return new \WP_REST_Response(
			$this->get_response_data( $request->get_params() ),
			201
		);
	}

	/**
	 * @param mixed[] $request_params .
	 *
	 * @return bool[] Response data.
	 */
	private function get_response_data( array $request_params ): array {
		return [
			'status' => $this->user_data_updater->update_user( $request_params['user_id'] ),
		];
	}
}
