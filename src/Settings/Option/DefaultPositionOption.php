<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\LocationConfigGroup;

/**
 * Option defines where in the post the quest should be displayed by default.
 */
class DefaultPositionOption extends OptionAbstract {

	const FIELD_NAME = 'qp_default_position';

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
		return __( 'Select questpasses position in text:', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	public function get_values( array $settings, bool $refreshed_values = false ): array {
		return [
			'upper' => sprintf(
			/* translators: %1$s: break line tag, %2$s: open em tag, %3$s: close em tag */
				__( 'Higher %1$s%2$sThis option is recommended for websites where short texts (1500-2500 characters) predominate, eg. news articles or posts where video is a main part.%3$s', 'questpass' ),
				'<br>',
				'<em>',
				'</em>'
			),
			'lower' => sprintf(
			/* translators: %1$s: break line tag, %2$s: open em tag, %3$s: close em tag */
				__( 'Lower %1$s%2$sThis option is recommended for websites where long entries (more than 2500 characters) predominate, such as specialized articles.%3$s', 'questpass' ),
				'<br>',
				'<em>',
				'</em>'
			),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings = null ) {
		return 'upper';
	}
}
