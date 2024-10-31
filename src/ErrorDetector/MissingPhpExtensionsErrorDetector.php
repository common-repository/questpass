<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\MissingPhpExtensionsError;

/**
 * Checks the server configuration for missing required PHP extensions.
 */
class MissingPhpExtensionsErrorDetector implements ErrorDetectorInterface {

	/**
	 * Required PHP extensions.
	 *
	 * @var string[]
	 */
	const REQUIRED_EXTENSIONS = [
		'curl',
		'json',
		'intl',
	];

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		$missing_php_extensions = $this->get_missing_php_extensions();

		if ( count( $missing_php_extensions ) > 0 ) {
			return new MissingPhpExtensionsError( $missing_php_extensions );
		}
		return null;
	}

	/**
	 * @return string[]
	 */
	private function get_missing_php_extensions(): array {
		$missing_extensions = [];
		foreach ( self::REQUIRED_EXTENSIONS as $required_ext ) {
			if ( ! extension_loaded( $required_ext ) ) {
				$missing_extensions[] = $required_ext;
			}
		}

		return $missing_extensions;
	}
}
