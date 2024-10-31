<?php

namespace Questpass\Error;

class ServiceInactiveError implements ErrorInterface {

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
		return __( 'Your website is awaiting activation in the Questpass system.', 'questpass' );
	}
}
