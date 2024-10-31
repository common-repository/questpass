<?php

namespace Questpass\Error;

use Questpass\Settings\Option\MasterSwitchOption;

class QuestsDisabledError implements ErrorInterface {

	/**
	 * {@inheritdoc}
	 */
	public function is_fatal(): bool {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_message(): string {
		return sprintf(
		/* translators: %1$s: option label */
			__( 'To display quests, select the option %1$s.', 'questpass' ),
			sprintf( '"%s"', MasterSwitchOption::get_label() )
		);
	}
}
