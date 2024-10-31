<?php

namespace Questpass\Content;

use Questpass\HookableInterface;
use Questpass\Model\User;
use Questpass\QuestpassConstants;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\PostSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Repository\UserRepository;
use Questpass\Service\ServiceJavascript;
use Questpass\Service\UserLoginService;
use QuestpassVendor\Adquesto\SDK\Content;
use QuestpassVendor\Adquesto\SDK\CurlHttpClient;
use QuestpassVendor\Adquesto\SDK\ElementsContextProvider;
use QuestpassVendor\Adquesto\SDK\PositioningSettings;
use QuestpassVendor\Adquesto\SDK\SubscriptionsContextProvider;

/**
 * Displays the quest on the post page.
 */
class QuestGenerator implements HookableInterface {

	const CONTENT_AD_CONTAINER = '<div id="%s"></div>';

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	/**
	 * @var PostSettingsRepository
	 */
	private $post_settings_repository;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	/**
	 * @var UserLoginService
	 */
	private $user_login_service;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository,
		PostSettingsRepository $post_settings_repository,
		UserRepository $user_repository = null,
		UserLoginService $user_login_service = null
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
		$this->post_settings_repository   = $post_settings_repository;
		$this->user_repository            = $user_repository ?: new UserRepository();
		$this->user_login_service         = $user_login_service ?: new UserLoginService( $plugin_settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_filter( 'the_content', [ $this, 'clear_quest_container' ], 0 );
		add_filter( 'the_content', [ $this, 'display_quest_in_post' ] );
	}

	/**
	 * Removes the character &nbsp; from the HTML element containing the quest.
	 *
	 * @param string $content .
	 */
	public function clear_quest_container( string $content ): string {
		$new_content = preg_replace(
			'/(questo-should-be-inserted-here(?:[^>]*)>)(.*)(<\/div>)/',
			'$1$3',
			$content
		);
		return ( $new_content !== null ) ? $new_content : $content;
	}

	/**
	 * Adds JavaScript code responsible for displaying the quest to the post content.
	 *
	 * @param string $content .
	 */
	public function display_quest_in_post( string $content ): string {
		global $post;
		if ( ! ( $post instanceof \WP_Post ) || ! $this->is_quest_allowed_to_show( $post ) ) {
			return $content;
		}

		$content_provider       = $this->get_content_provider();
		$elements_provider      = $this->get_elements_provider();
		$subscriptions_provider = $this->get_subscriptions_provider();

		$javascript = $content_provider->javascript(
			[ $elements_provider, $subscriptions_provider ]
		);

		$prepared_content = null;
		if ( $content_provider->hasQuestoClassInHTML( $content ) ) {
			$prepared_content = $content_provider->manualPrepare(
				$content,
				sprintf( self::CONTENT_AD_CONTAINER, $elements_provider->mainQuestId() ),
				sprintf( self::CONTENT_AD_CONTAINER, $elements_provider->reminderQuestId() )
			);
		} elseif ( $this->is_quest_allowed_for_post( $post ) ) {
			$prepared_content = $content_provider->autoPrepare(
				$content,
				sprintf( self::CONTENT_AD_CONTAINER, $elements_provider->mainQuestId() ),
				sprintf( self::CONTENT_AD_CONTAINER, $elements_provider->reminderQuestId() )
			);
		}

		if ( $prepared_content === null ) {
			return $content;
		}

		$prepared_content->setJavascript( $javascript );
		return $prepared_content->__toString();
	}

	private function get_content_provider(): Content {
		return new Content(
			QuestpassConstants::API_SERVICES_URL,
			$this->plugin_settings_repository->get_settings()->get_api_token(),
			new ServiceJavascript( $this->service_status_repository ),
			new CurlHttpClient(),
			PositioningSettings::factory(
				$this->plugin_settings_repository->get_settings()->get_default_position()
			)
		);
	}

	private function get_elements_provider(): ElementsContextProvider {
		$is_draft = ( ( get_post_status() !== 'publish' ) || is_preview() );

		return new ElementsContextProvider(
			null,
			null,
			$is_draft,
			( $is_draft || $this->service_status_repository->get_status()->get_active_campaigns_status() )
		);
	}

	private function get_subscriptions_provider(): SubscriptionsContextProvider {
		$user_token = $this->user_login_service->get_user_token();
		$user       = $this->user_repository->get_user_by_token( $user_token ) ?: new User();

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment
		return new SubscriptionsContextProvider(
			[
				SubscriptionsContextProvider::IS_SUBSCRIPTION_ACTIVE    => (int) $user->is_subscription_active(),
				SubscriptionsContextProvider::IS_SUBSCRIPTION_RECURRING => (int) $user->get_recurring_payments_status(),
				SubscriptionsContextProvider::IS_SUBSCRIPTION_DAYS_LEFT => $user->get_subscription_days_left(),
				SubscriptionsContextProvider::IS_SUBSCRIPTION_AVAILABLE => (int) $this->service_status_repository->get_status()->get_subscriptions_status(),
				SubscriptionsContextProvider::AUTHORIZATION_ERROR       => (string) '',
				SubscriptionsContextProvider::IS_LOGGED_IN              => (int) ( $user->get_user_id() !== null ),
				SubscriptionsContextProvider::AUTHORIZATION_URI         => get_rest_url( null, QuestpassConstants::REST_API_BASE . '/' . QuestpassConstants::REST_API_ROUTE_USER_LOGIN_REDIRECT ),
				SubscriptionsContextProvider::LOGOUT_URI                => get_rest_url( null, QuestpassConstants::REST_API_BASE . '/' . QuestpassConstants::REST_API_ROUTE_USER_LOGOUT ),
				SubscriptionsContextProvider::USER_LOGIN                => $user->get_user_email(),
			]
		);
	}

	public function is_quest_allowed_to_show( \WP_Post $post ): bool {
		if ( ! $this->plugin_settings_repository->get_settings()->get_master_switch() ) {
			return false;
		} elseif ( ! $this->post_settings_repository->get_settings( $post->ID )->get_display_quest() ) {
			return false;
		} elseif ( ( $post->post_status !== 'publish' ) && ! is_preview() ) {
			return false;
		} elseif ( $this->service_status_repository->get_status()->get_service_status() !== true ) {
			return false;
		} elseif ( $this->plugin_settings_repository->get_settings()->get_hide_for_users() && is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	private function is_quest_allowed_for_post( \WP_Post $post ): bool {
		$post_types = $this->plugin_settings_repository->get_settings()->get_post_types();
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return false;
		}

		$allowed_categories = $this->plugin_settings_repository->get_settings()->get_categories();
		$post_categories    = get_the_category( $post->ID );
		if ( ! $post_categories ) {
			return true;
		}

		foreach ( $post_categories as $wp_term_object ) {
			if ( in_array( $wp_term_object->term_id, $allowed_categories ) ) {
				return true;
			}
		}

		return false;
	}
}
