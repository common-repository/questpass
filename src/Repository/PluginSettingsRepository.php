<?php

namespace Questpass\Repository;

use Questpass\Model\PluginSettings;
use Questpass\Settings\Option\ApiClientOption;
use Questpass\Settings\Option\ApiSecretOption;
use Questpass\Settings\Option\ApiTokenOption;
use Questpass\Settings\Option\CategoriesOption;
use Questpass\Settings\Option\DefaultPositionOption;
use Questpass\Settings\Option\EnableNewCategoriesOption;
use Questpass\Settings\Option\HideForUsersOption;
use Questpass\Settings\Option\MasterSwitchOption;
use Questpass\Settings\Option\OptionIntegration;
use Questpass\Settings\Option\PostTypesOption;

/**
 * Manages the plugin settings.
 */
class PluginSettingsRepository {

	const SETTINGS_OPTION_NAME = 'questpass_settings';

	/**
	 * @var OptionIntegration[]
	 */
	private $options = [];

	/**
	 * @var PluginSettings
	 */
	private $plugin_settings = null;

	public function __construct() {
		$this->options[] = new OptionIntegration( new MasterSwitchOption() );
		$this->options[] = new OptionIntegration( new PostTypesOption() );
		$this->options[] = new OptionIntegration( new CategoriesOption() );
		$this->options[] = new OptionIntegration( new EnableNewCategoriesOption() );
		$this->options[] = new OptionIntegration( new DefaultPositionOption() );
		$this->options[] = new OptionIntegration( new HideForUsersOption() );
		$this->options[] = new OptionIntegration( new ApiTokenOption() );
		$this->options[] = new OptionIntegration( new ApiClientOption() );
		$this->options[] = new OptionIntegration( new ApiSecretOption() );
	}

	/**
	 * Returns options of the plugin settings.
	 *
	 * @return mixed[]
	 */
	public function get_options(): array {
		$plugin_settings = $this->get_settings();
		$settings_values = $this->get_settings_values( $plugin_settings );
		$options         = [];
		foreach ( $this->options as $option ) {
			$options[] = $option->get_option_data( $settings_values );
		}
		return $options;
	}

	/**
	 * @return PluginSettings
	 */
	public function get_settings(): PluginSettings {
		return ( $this->plugin_settings === null ) ? $this->refresh_settings() : $this->plugin_settings;
	}

	/**
	 * Generates new settings object based on updated plugin settings.
	 *
	 * @param mixed[]|null $settings_values .
	 *
	 * @return PluginSettings
	 */
	public function refresh_settings( array $settings_values = null ): PluginSettings {
		$settings = ( $settings_values === null )
			? $this->get_default_settings_values()
			: $this->update_settings_values( $settings_values );

		$this->plugin_settings = new PluginSettings(
			$settings[ MasterSwitchOption::FIELD_NAME ],
			$settings[ ApiTokenOption::FIELD_NAME ],
			$settings[ ApiClientOption::FIELD_NAME ],
			$settings[ ApiSecretOption::FIELD_NAME ],
			$settings[ PostTypesOption::FIELD_NAME ],
			$settings[ CategoriesOption::FIELD_NAME ],
			$settings[ EnableNewCategoriesOption::FIELD_NAME ],
			$settings[ HideForUsersOption::FIELD_NAME ],
			$settings[ DefaultPositionOption::FIELD_NAME ]
		);
		return $this->plugin_settings;
	}

	/**
	 * @param PluginSettings $plugin_settings .
	 *
	 * @return void
	 */
	public function save_settings( PluginSettings $plugin_settings ) {
		$settings_values = $this->get_settings_values( $plugin_settings );

		$this->refresh_settings( $settings_values );
		update_option( self::SETTINGS_OPTION_NAME, $settings_values );
	}

	/**
	 * @param PluginSettings $plugin_settings .
	 *
	 * @return mixed[]
	 */
	private function get_settings_values( PluginSettings $plugin_settings ): array {
		return $this->update_settings_values(
			[
				MasterSwitchOption::FIELD_NAME        => $plugin_settings->get_master_switch(),
				ApiTokenOption::FIELD_NAME            => $plugin_settings->get_api_token(),
				ApiClientOption::FIELD_NAME           => $plugin_settings->get_api_client(),
				ApiSecretOption::FIELD_NAME           => $plugin_settings->get_api_secret(),
				PostTypesOption::FIELD_NAME           => $plugin_settings->get_post_types(),
				CategoriesOption::FIELD_NAME          => $plugin_settings->get_categories(),
				EnableNewCategoriesOption::FIELD_NAME => $plugin_settings->get_enable_new_categories(),
				HideForUsersOption::FIELD_NAME        => $plugin_settings->get_hide_for_users(),
				DefaultPositionOption::FIELD_NAME     => $plugin_settings->get_default_position(),
			]
		);
	}

	/**
	 * @param mixed[] $settings_values .
	 *
	 * @return mixed[]
	 */
	private function update_settings_values( array $settings_values ): array {
		$values = [];
		foreach ( $this->options as $option ) {
			$values[ $option->get_option_key() ] = $option->get_refreshed_option_value( $settings_values );
		}
		return $values;
	}

	/**
	 * @return mixed[]
	 */
	private function get_default_settings_values(): array {
		$settings = get_option( self::SETTINGS_OPTION_NAME, [] ) ?: [];
		$values   = [];
		foreach ( $this->options as $option ) {
			$values[ $option->get_option_key() ] = $option->get_option_value( $settings, true );
		}
		return $values;
	}
}
