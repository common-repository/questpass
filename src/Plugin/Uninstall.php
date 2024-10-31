<?php

namespace Questpass\Plugin;

use Questpass\Exception\MigrationFailedException;
use Questpass\HookableInterface;
use Questpass\Migration\MigrationBuilder;
use Questpass\PluginInfo;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\PostSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Settings\Option\CategoriesOption;

/**
 * Runs actions before the plugin uninstallation.
 */
class Uninstall implements HookableInterface {

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct( PluginInfo $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		register_uninstall_hook( $this->plugin_info->get_plugin_file(), [ self::class, 'init_uninstall_actions' ] );
	}

	/**
	 * @return void
	 * @throws MigrationFailedException
	 * @internal
	 */
	public static function init_uninstall_actions() {
		self::clear_options_table();
		self::clear_postmeta_table();
		self::drop_tables();
	}

	/**
	 * Removes options from the wp_options table.
	 *
	 * @return void
	 */
	private static function clear_options_table() {
		delete_option( PluginSettingsRepository::SETTINGS_OPTION_NAME );
		delete_option( ServiceStatusRepository::CONNECTION_STATUS_OPTION_NAME );
		delete_option( ServiceStatusRepository::SERVICE_STATUS_OPTION_NAME );
		delete_option( ServiceStatusRepository::SUBSCRIPTIONS_STATUS_OPTION_NAME );
		delete_option( ServiceStatusRepository::ACTIVE_CAMPAIGNS_STATUS_OPTION_NAME );
		delete_option( ServiceStatusRepository::JAVASCRIPT_OPTION_NAME );
		delete_option( ServiceStatusRepository::JAVASCRIPT_UPDATE_DATE_OPTION_NAME );

		delete_transient( CategoriesOption::CATEGORIES_TRANSIENT_OPTION );
	}

	/**
	 * Removes options from the wp_postmeta table.
	 *
	 * @return void
	 */
	private static function clear_postmeta_table() {
		global $wpdb;

		$wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->postmeta,
			[
				'meta_key' => PostSettingsRepository::SETTINGS_POST_META, // phpcs:ignore WordPress.DB.SlowDBQuery
			]
		);
	}

	/**
	 * Deletes all tables created by the plugin.
	 *
	 * @return void
	 * @throws MigrationFailedException
	 */
	private static function drop_tables() {
		( new MigrationBuilder() )->rollback_migration();
	}
}
