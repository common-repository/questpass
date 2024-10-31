<?php

namespace Questpass\Repository;

use Questpass\Model\PostSettings;
use Questpass\Settings\Option\DisplayQuestOption;
use Questpass\Settings\Option\OptionIntegration;

/**
 * Manages the post settings.
 */
class PostSettingsRepository {

	const SETTINGS_POST_META = 'questpass_settings';

	/**
	 * @var OptionIntegration[]
	 */
	private $options = [];

	/**
	 * @var PostSettings[]
	 */
	private $post_settings;

	public function __construct() {
		$this->options[] = new OptionIntegration( new DisplayQuestOption() );
	}

	/**
	 * Returns options of the post settings.
	 *
	 * @param int $post_id .
	 *
	 * @return mixed[]
	 */
	public function get_options( int $post_id ): array {
		$post_settings   = $this->get_settings( $post_id );
		$settings_values = $this->get_settings_values( $post_settings );
		$options         = [];
		foreach ( $this->options as $option ) {
			$options[] = $option->get_option_data( $settings_values );
		}
		return $options;
	}

	/**
	 * @param int $post_id .
	 *
	 * @return PostSettings
	 */
	public function get_settings( int $post_id ): PostSettings {
		return $this->post_settings[ $post_id ] ?? $this->refresh_settings( $post_id );
	}

	/**
	 * Generates new settings object based on updated post settings.
	 *
	 * @param int          $post_id         .
	 * @param mixed[]|null $settings_values .
	 *
	 * @return PostSettings
	 */
	public function refresh_settings( int $post_id, array $settings_values = null ): PostSettings {
		$settings = ( $settings_values === null )
			? $this->get_default_settings_values( $post_id )
			: $this->update_settings_values( $settings_values );

		$this->post_settings[ $post_id ] = new PostSettings(
			$post_id,
			$settings[ DisplayQuestOption::FIELD_NAME ]
		);
		return $this->post_settings[ $post_id ];
	}

	/**
	 * @param PostSettings $post_settings .
	 *
	 * @return void
	 */
	public function save_settings( PostSettings $post_settings ) {
		$settings_values = $this->get_settings_values( $post_settings );

		$this->refresh_settings( $post_settings->get_post_id(), $settings_values );
		update_post_meta( $post_settings->get_post_id(), self::SETTINGS_POST_META, $settings_values );
	}

	/**
	 * @param PostSettings $post_settings .
	 *
	 * @return mixed[]
	 */
	private function get_settings_values( PostSettings $post_settings ): array {
		return $this->update_settings_values(
			[
				DisplayQuestOption::FIELD_NAME => $post_settings->get_display_quest(),
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
	 * @param int $post_id .
	 *
	 * @return mixed[]
	 */
	private function get_default_settings_values( int $post_id ): array {
		$settings = get_post_meta( $post_id, self::SETTINGS_POST_META, true ) ?: [];
		$values   = [];
		foreach ( $this->options as $option ) {
			$values[ $option->get_option_key() ] = $option->get_option_value( $settings, true );
		}
		return $values;
	}
}
