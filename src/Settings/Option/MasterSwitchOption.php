<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\QuestActivationGroup;

/**
 * Option defines whether to allow the quest to be shown.
 */
class MasterSwitchOption extends OptionAbstract {

	const FIELD_NAME = 'qp_master_switch';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return QuestActivationGroup::FIELD_GROUP_KEY;
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
		return __( 'Display questpasses on the website', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value( array $settings ): string {
		return '';
	}
}
