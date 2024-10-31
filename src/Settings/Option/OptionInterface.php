<?php

namespace Questpass\Settings\Option;

/**
 * Interface for class that supports data of field in plugin settings.
 */
interface OptionInterface {

	/**
	 * Returns key of field group.
	 *
	 * @return string
	 */
	public function get_group_key(): string;

	/**
	 * Returns key of option.
	 *
	 * @return string
	 */
	public function get_option_key(): string;

	/**
	 * Returns type of field.
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Returns the label of the option.
	 *
	 * @return string
	 */
	public static function get_label(): string;

	/**
	 * Returns available values for field.
	 *
	 * @param mixed[] $settings         Plugin settings.
	 * @param bool    $refreshed_values Return refreshed values?
	 *
	 * @return string[]|null
	 */
	public function get_values( array $settings, bool $refreshed_values = false );

	/**
	 * Returns default value of field.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string|string[]
	 */
	public function get_default_value( array $settings );
}
