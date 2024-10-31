<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\ErrorInterface;

/**
 * Interface for a class that detects errors that prevent the plugin from correct operation.
 */
interface ErrorDetectorInterface {

	/**
	 * Checks whether a given error occurs.
	 *
	 * @return ErrorInterface|null
	 */
	public function get_error();
}
