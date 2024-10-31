<?php

namespace Questpass\Service;

use Questpass\Model\User;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\UserRepository;
use QuestpassVendor\League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Updates data of the subscription user in the local database.
 */
class UserDataUpdater {

	/**
	 * @var OauthAuthorizationService
	 */
	private $oauth_authorization_service;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->oauth_authorization_service = new OauthAuthorizationService( $plugin_settings_repository );
		$this->user_repository             = new UserRepository();
	}

	public function update_user( string $user_id ): bool {
		$user = $this->user_repository->get_user_by_id( $user_id );
		if ( ( $user === null ) || ( $user->get_refresh_token() === null ) ) {
			return false;
		}

		$access_token = $this->oauth_authorization_service->refresh_access_token( $user->get_refresh_token() );
		if ( $access_token === null ) {
			return false;
		}

		$this->update_user_data( $access_token, $user );
		return true;
	}

	public function update_user_data( AccessTokenInterface $access_token, User $user = null ): User {
		$user_data = $this->oauth_authorization_service->get_resource_owner( $access_token );
		if ( $user === null ) {
			$user = $this->user_repository->get_user_by_id( $user_data['uid'] ) ?: new User();
		}

		$date_expires      = ( $access_token->getExpires() ) ? $access_token->getExpires() : null;
		$date_subscription = strtotime( $user_data['subscriptionDate'] );

		$user->set_access_token( $access_token->getToken() );
		$user->set_refresh_token( $access_token->getRefreshToken() );
		$user->set_token_expires_at( ( $date_expires ) ? ( new \DateTime() )->setTimestamp( $date_expires ) : null );
		$user->set_user_id( $user_data['uid'] );
		$user->set_user_email( $user_data['email'] );
		$user->set_subscription_date( ( $date_subscription ) ? ( new \DateTime() )->setTimestamp( $date_subscription ) : null );
		$user->set_recurring_payments_status( $user_data['recurringPayments'] );

		$this->user_repository->update_user( $user );
		return $user;
	}
}
