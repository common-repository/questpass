<?php

namespace Questpass\Error;

class MissingPhpExtensionsError implements ErrorInterface {

	/**
	 * @var string[]
	 */
	private $missing_php_extensions;

	/**
	 * @param string[] $missing_php_extensions .
	 */
	public function __construct( array $missing_php_extensions ) {
		$this->missing_php_extensions = $missing_php_extensions;
	}

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
		return sprintf(
		/* translators: %1$s: PHP extensions */
			__( 'The plugin requires the following PHP libraries to be enabled: %1$s.', 'questpass' ),
			implode( ', ', $this->missing_php_extensions )
		);
	}
}
