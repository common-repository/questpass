<?php

namespace Questpass\Logger;

/**
 * Writes errors to the debug.log file.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
class WordpressLogger implements LoggerInterface {

	/**
	 * {@inheritdoc}
	 */
	public function error( string $message, string $context = null ) {
		error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			sprintf( 'Questpass error: %1$s (%2$s)', $message, $context )
		);
	}
}
