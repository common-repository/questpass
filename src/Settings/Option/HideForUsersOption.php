<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\LocationConfigGroup;

/**
 * Option defines whether to show the quest for logged in users.
 */
class HideForUsersOption extends OptionAbstract {

	const FIELD_NAME = 'qp_hide_for_users';

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
		return OptionAbstract::OPTION_TYPE_TOGGLE;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_label(): string {
		return __( 'Do not display questpasses for logged-in administrators, editors and authors', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings ): string {
		return '';
	}
}
