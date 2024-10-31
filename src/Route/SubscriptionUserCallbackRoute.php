<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Service\OauthAuthorizationService;
use Questpass\Service\UserLoginService;

/**
 * Supports a callback after logging into the Questpass service.
 */
class SubscriptionUserCallbackRoute implements RouteInterface {

	/**
	 * @var OauthAuthorizationService
	 */
	private $oauth_authorization_service;

	/**
	 * @var UserLoginService
	 */
	private $user_login_service;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->oauth_authorization_service = new OauthAuthorizationService( $plugin_settings_repository );
		$this->user_login_service          = new UserLoginService( $plugin_settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_USER_LOGIN_CALLBACK;
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
		return 'Supports a callback after logging into the Questpass service.';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_params(): array {
		return [
			'code'  => [
				'description'       => 'OAuth2 code',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					return true;
				},
			],
			'state' => [
				'description'       => 'OAuth2 state',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					return $this->oauth_authorization_service->is_valid_authorization_state( $value );
				},
			],
			'ref'   => [
				'description'       => 'Callback URL',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					return ( filter_var( $value, FILTER_VALIDATE_URL ) !== false );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return void
	 */
	public function get_endpoint_response( \WP_REST_Request $request ) {
		$status = $this->user_login_service->login_user( $request->get_param( 'code' ) );
		if ( $status !== true ) {
			wp_die(
				wp_kses_post(
					sprintf(
					/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
						__( 'Sorry, an error occurred logging. Please %1$stry again%2$s.', 'questpass' ),
						'<a href="' . $request->get_param( 'ref' ) . '">',
						'</a>'
					)
				)
			);
		}

		wp_redirect( $request->get_param( 'ref' ) ); // phpcs:ignore WordPress.Security.SafeRedirect
		exit;
	}
}
