<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\LocationConfigGroup;
use Questpass\Settings\PluginSettingsPage;

/**
 * Option defines defines whether to show the quest in the post.
 */
class DisplayQuestOption extends OptionAbstract {

	const FIELD_NAME = 'qp_display_quest';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return LocationConfigGroup::FIELD_GROUP_KEY;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option_key(): string {
		return self::FIELD_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionAbstract::OPTION_TYPE_RADIO;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_label(): string {
		return __( 'How to display Questpass in this post:', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	public function get_values( array $settings, bool $refreshed_values = false ): array {
		return [
			'1' => sprintf(
			/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
				__( 'According to %1$splugin settings%2$s', 'questpass' ),
				'<a href="' . PluginSettingsPage::get_settings_page_url() . '">',
				'</a>'
			),
			'0' => __( 'Do not show questpasses', 'questpass' ),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings ): string {
		return '1';
	}
}
