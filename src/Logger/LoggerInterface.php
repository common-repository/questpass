<?php

namespace Questpass\Logger;

/**
 * Interface for a class that supports the error logging.
 */
interface LoggerInterface {

	/**
	 * Runtime errors that do not require immediate an action but should typically be logged and monitored.
	 *
	 * @param string $message .
	 * @param string $context .
	 *
	 * @return void
	 */
	public function error( string $message, string $context );
}
