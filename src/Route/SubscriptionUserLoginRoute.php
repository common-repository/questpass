<?php

namespace Questpass\Route;

use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Service\OauthAuthorizationService;

/**
 * Redirects the subscription user to log into the Questpass service.
 */
class SubscriptionUserLoginRoute implements RouteInterface {

	const PARAM_ARTICLE_URL = 'articleUrl';

	/**
	 * @var OauthAuthorizationService
	 */
	private $oauth_authorization_service;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->oauth_authorization_service = new OauthAuthorizationService( $plugin_settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_endpoint_route(): string {
		return QuestpassConstants::REST_API_ROUTE_USER_LOGIN_REDIRECT;
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
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_params(): array {
		return [
			self::PARAM_ARTICLE_URL => [
				'description'       => 'Callback URL',
				'required'          => true,
				'validate_callback' => function ( $value ) {
					return filter_var( $value, FILTER_VALIDATE_URL );
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
		$authorization_url = $this->oauth_authorization_service->get_authorization_url( $request->get_param( self::PARAM_ARTICLE_URL ) );

		wp_redirect( $authorization_url ); // phpcs:ignore WordPress.Security.SafeRedirect
		exit;
	}
}
