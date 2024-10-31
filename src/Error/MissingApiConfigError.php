<?php

namespace Questpass\Error;

class MissingApiConfigError implements ErrorInterface {

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
		return __( 'Set up a connection to the Questpass system to be able to view questpasses.', 'questpass' );
	}
}
