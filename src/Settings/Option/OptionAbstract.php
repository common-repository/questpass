<?php

namespace Questpass\Settings\Option;

/**
 * Abstract class that supports data of field in plugin settings.
 */
abstract class OptionAbstract implements OptionInterface {

	const OPTION_TYPE_TEXT           = 'text';
	const OPTION_TYPE_MULTI_CHECKBOX = 'multi-checkbox';
	const OPTION_TYPE_RADIO          = 'radio';
	const OPTION_TYPE_TOGGLE         = 'toggle';

	/**
	 * {@inheritdoc}
	 */
	public function get_values( array $settings, bool $refreshed_values = false ) {
		return null;
	}
}
