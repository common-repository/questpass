<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\ApiConfigGroup;

/**
 * Option stores a value of the OAuth Secret.
 */
class ApiSecretOption extends OptionAbstract {

	const FIELD_NAME = 'qp_api_secret';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return ApiConfigGroup::FIELD_GROUP_KEY;
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
		return OptionAbstract::OPTION_TYPE_TEXT;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_label(): string {
		return __( 'OAuth Secret', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings ): string {
		return '';
	}
}