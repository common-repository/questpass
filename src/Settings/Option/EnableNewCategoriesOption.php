<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\DisplayConfigGroup;

/**
 * Option defines defines whether new categories should be automatically added to the plugin settings.
 *
 * @see CategoriesOption
 */
class EnableNewCategoriesOption extends OptionAbstract {

	const FIELD_NAME = 'qp_enable_new_categories';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return DisplayConfigGroup::FIELD_GROUP_KEY;
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
		return OptionAbstract::OPTION_TYPE_TOGGLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_label(): string {
		return __( 'Display questpasses in the newly added categories', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings ): string {
		return '';
	}
}
