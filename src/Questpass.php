<?php

namespace Questpass;

use Questpass\Content;
use Questpass\Plugin;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\PostSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Route;
use Questpass\Settings;

/**
 * Initializes all the plugin actions.
 */
class Questpass {

	public function __construct( PluginInfo $plugin_info ) {
		$plugin_settings = new PluginSettingsRepository();
		$service_status  = new ServiceStatusRepository();
		$post_settings   = new PostSettingsRepository();

		( new Content\QuestGenerator( $plugin_settings, $service_status, $post_settings ) )->init_hooks();
		( new Content\ClassicEditorButton( $plugin_info ) )->init_hooks();
		( new Content\GutenbergBlock( $plugin_info ) )->init_hooks();
		( new Plugin\Links( $plugin_info ) )->init_hooks();
		( new Plugin\Activation( $plugin_settings, $service_status, $plugin_info ) )->init_hooks();
		( new Plugin\TranslationsSetup( $plugin_info ) )->init_hooks();
		( new Plugin\Uninstall( $plugin_info ) )->init_hooks();
		( new Route\RouteIntegration( new Route\OptionsRoute( $plugin_settings, $service_status ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\DebugRoute( $plugin_settings, $service_status, $plugin_info ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\UpdateJavascriptRoute( $plugin_settings, $service_status ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\UpdateServiceStatusRoute( $plugin_settings, $service_status ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\SubscriptionUserLoginRoute( $plugin_settings ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\SubscriptionUserCallbackRoute( $plugin_settings ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\SubscriptionUserLogoutRoute( $plugin_settings ) ) )->init_hooks();
		( new Route\RouteIntegration( new Route\SubscriptionUserUpdateRoute( $plugin_settings ) ) )->init_hooks();
		( new Settings\PluginSettingsPage( $plugin_settings, $service_status, $plugin_info ) )->init_hooks();
		( new Settings\PostSettingsForm( $plugin_settings, $post_settings, $plugin_info ) )->init_hooks();
		( new Settings\CategorySettings( $plugin_settings ) )->init_hooks();
	}
}
