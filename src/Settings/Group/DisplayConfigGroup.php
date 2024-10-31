<?php

namespace Questpass\Settings\Group;

/**
 * {@inherit}
 */
class DisplayConfigGroup extends GroupAbstract {

	const FIELD_GROUP_KEY = 'display_config';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return self::FIELD_GROUP_KEY;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Questpasses display setup', 'questpass' );
	}
}
