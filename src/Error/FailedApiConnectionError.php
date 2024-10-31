<?php

namespace Questpass\Error;

class FailedApiConnectionError implements ErrorInterface {

	/**
	 * {@inheritdoc}
	 */
	public function is_fatal(): bool {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_message(): string {
		return __( 'Failed to connect with the Questpass API.', 'questpass' );
	}
}
