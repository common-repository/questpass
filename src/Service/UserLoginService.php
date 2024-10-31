<?php

namespace Questpass\Service;

use Questpass\Model\User;
use Questpass\Repository\PluginSettingsRepository;

/**
 * Supports subscription user login and logout.
 */
class UserLoginService {

	const COOKIE_AUTH_NAME = 'questpass_user_token';

	/**
	 * @var OauthAuthorizationService
	 */
	private $oauth_authorization_service;

	/**
	 * @var UserDataUpdater
	 */
	private $user_update;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->oauth_authorization_service = new OauthAuthorizationService( $plugin_settings_repository );
		$this->user_update                 = new UserDataUpdater( $plugin_settings_repository );
	}

	/**
	 * @return string|null
	 */
	public function get_user_token() {
		return ( isset( $_COOKIE[ $this->get_cookie_name() ] ) ) ? $_COOKIE[ $this->get_cookie_name() ] : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	}

	private function get_cookie_name(): string {
		return sprintf( '%1$s_%2$s', self::COOKIE_AUTH_NAME, get_current_blog_id() );
	}

	public function login_user( string $auth_code ): bool {
		$access_token = $this->oauth_authorization_service->get_access_token( $auth_code );
		if ( $access_token === null ) {
			return false;
		}

		$user = $this->user_update->update_user_data( $access_token );
		return $this->create_auth_cookie( $user );
	}

	public function logout_user(): bool {
		return $this->delete_auth_cookie();
	}

	private function create_auth_cookie( User $user ): bool {
		if ( $user->get_token_expires_at() === null ) {
			return false;
		}

		return setcookie(
			$this->get_cookie_name(),
			$user->get_user_token(),
			strtotime( $user->get_token_expires_at()->format( 'Y-m-d H:i:s' ) ) ?: 0,
			defined( 'COOKIEPATH' ) ? COOKIEPATH : '',
			defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : ''
		);
	}

	private function delete_auth_cookie(): bool {
		return setcookie(
			$this->get_cookie_name(),
			'',
			( time() - 3600 ),
			defined( 'COOKIEPATH' ) ? COOKIEPATH : '',
			defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : ''
		);
	}
}
