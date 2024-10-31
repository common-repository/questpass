<?php

namespace Questpass\Settings\Group;

use Questpass\QuestpassConstants;

/**
 * {@inherit}
 */
class ApiConfigGroup extends GroupAbstract {

	const FIELD_GROUP_KEY = 'api_config';

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
		return __( 'Connection setup', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_desc(): string {
		return sprintf(
		/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
			__( 'Configuration data can be found in the website settings in %1$sthe Questpass panel%2$s.', 'questpass' ),
			'<a href="' . QuestpassConstants::USER_PUBLISHER_URL . '" target="_blank">',
			'</a>'
		);
	}
}
