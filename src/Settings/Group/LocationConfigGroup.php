<?php

namespace Questpass\Settings\Group;

use Questpass\Settings\Option\HideForUsersOption;

/**
 * {@inherit}
 */
class LocationConfigGroup extends GroupAbstract {

	const FIELD_GROUP_KEY = 'widget_config';

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
		return __( 'Questpasses position in text setup', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_info_title() {
		return __( 'Hint', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_info_content() {
		return [
			__( 'You can also select a questpass position manually by using the Questpass block while editing.', 'questpass' ),
			sprintf(
			/* translators: %1$s: option label */
				__( 'Option %1$s does not apply to the post preview. Logged-in editors will still be able to preview questpasses before saving posts.', 'questpass' ),
				sprintf( '<em>"%s"</em>', HideForUsersOption::get_label() )
			),
		];
	}
}
