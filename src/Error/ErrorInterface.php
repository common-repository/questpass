<?php

namespace Questpass\Error;

interface ErrorInterface {

	/**
	 * @return bool
	 */
	public function is_fatal(): bool;

	/**
	 * @return string
	 */
	public function get_message(): string;
}
