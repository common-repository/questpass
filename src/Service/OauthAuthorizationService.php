<?php

namespace Questpass\Service;

use Questpass\Logger\LoggerInterface;
use Questpass\Logger\WordpressLogger;
use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use QuestpassVendor\League\OAuth2\Client\Grant\AuthorizationCode;
use QuestpassVendor\League\OAuth2\Client\Grant\RefreshToken;
use QuestpassVendor\League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use QuestpassVendor\League\OAuth2\Client\Provider\GenericProvider;
use QuestpassVendor\League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Supports OAuth2 authorization for getting data of the Questpass service.
 */
class OauthAuthorizationService {

	const STATE_SESSION_KEY = 'questpass_oauth2state';

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var GenericProvider
	 */
	private $auth_provider = null;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		LoggerInterface $logger = null
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->logger                     = $logger ?: new WordpressLogger();
	}

	private function get_provider(): GenericProvider {
		if ( $this->auth_provider === null ) {
			$this->auth_provider = $this->set_debug_http_client(
				new GenericProvider(
					[
						'clientId'                => $this->plugin_settings_repository->get_settings()->get_api_client(),
						'clientSecret'            => $this->plugin_settings_repository->get_settings()->get_api_secret(),
						'redirectUri'             => get_rest_url( null, QuestpassConstants::REST_API_BASE . '/' . QuestpassConstants::REST_API_ROUTE_USER_LOGIN_CALLBACK ),
						'urlAuthorize'            => QuestpassConstants::USER_SUBSCRIBER_URL,
						'urlAccessToken'          => QuestpassConstants::API_OAUTH_TOKEN_URL,
						'urlResourceOwnerDetails' => QuestpassConstants::API_OAUTH_DETAILS_URL,
						'scopes'                  => 'read_profile',
					]
				)
			);
		}
		return $this->auth_provider;
	}

	private function set_debug_http_client( GenericProvider $provider ): GenericProvider {
		$provider->setHttpClient(
			new \QuestpassVendor\GuzzleHttp\Client(
				[
					\QuestpassVendor\GuzzleHttp\RequestOptions::VERIFY => false,
				]
			)
		);
		return $provider;
	}

	public function get_authorization_url( string $param_ref = null ): string {
		$this->start_session();

		$authorization_url                   = $this->get_provider()->getAuthorizationUrl();
		$_SESSION[ self::STATE_SESSION_KEY ] = $this->get_provider()->getState();

		if ( $param_ref !== null ) {
			$authorization_url .= '&ref=' . urlencode( $param_ref );
		}

		return $authorization_url;
	}

	public function is_valid_authorization_state( string $auth_state ): bool {
		$this->start_session();

		return ( isset( $_SESSION[ self::STATE_SESSION_KEY ] )
			&& ( $_SESSION[ self::STATE_SESSION_KEY ] === $auth_state ) );
	}

	/**
	 * @param string $auth_code .
	 *
	 * @return AccessTokenInterface|null
	 */
	public function get_access_token( string $auth_code ) {
		try {
			return $this->get_provider()->getAccessToken(
				new AuthorizationCode(),
				[ 'code' => $auth_code ]
			);
		} catch ( IdentityProviderException $e ) {
			$this->logger->error( $e->getMessage(), __METHOD__ );
		}
		return null;
	}

	/**
	 * @param string $refresh_token .
	 *
	 * @return AccessTokenInterface|null
	 */
	public function refresh_access_token( string $refresh_token ) {
		try {
			return $this->get_provider()->getAccessToken(
				new RefreshToken(),
				[ 'refresh_token' => $refresh_token ]
			);
		} catch ( IdentityProviderException $e ) {
			$this->logger->error( $e->getMessage(), __METHOD__ );
		}
		return null;
	}

	/**
	 * @param AccessTokenInterface $access_token .
	 *
	 * @return mixed[]
	 */
	public function get_resource_owner( AccessTokenInterface $access_token ): array {
		return $this->get_provider()->getResourceOwner( $access_token )->toArray(); // @phpstan-ignore-line
	}

	/**
	 * @return void
	 */
	private function start_session() {
		if ( ! session_id() ) {
			session_start();
		}
	}
}
