<?php

namespace Questpass\Settings\Option;

/**
 * Allows to integrate with field in plugin settings by specifying its settings and value.
 */
class OptionIntegration {

	/**
	 * @var OptionInterface
	 */
	private $option;

	public function __construct( OptionInterface $option ) {
		$this->option = $option;
	}

	/**
	 * @return string
	 */
	public function get_option_key(): string {
		return $this->option->get_option_key();
	}

	/**
	 * Returns saved option value or default value.
	 *
	 * @param mixed[] $settings           Plugin settings.
	 * @param bool    $is_default_allowed Replace missing value by default value?
	 *
	 * @return string|string[]|null
	 */
	public function get_option_value( array $settings, bool $is_default_allowed = false ) {
		$option_key = $this->option->get_option_key();
		$values     = $this->option->get_values( $settings ) ?: [];

		if ( $is_default_allowed && ! isset( $settings[ $option_key ] ) ) {
			return $this->option->get_default_value( $settings );
		}
		return $this->sanitize_option_value( $settings[ $option_key ] ?? null, $this->option->get_type(), $values );
	}

	/**
	 * Returns-checked option value again for new settings.
	 *
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return string|string[]
	 */
	public function get_refreshed_option_value( array $settings ) {
		$option_key = $this->option->get_option_key();
		$values     = $this->option->get_values( $settings, true ) ?: [];

		return $this->sanitize_option_value( $settings[ $option_key ] ?? null, $this->option->get_type(), $values );
	}

	/**
	 * @param mixed[] $settings Plugin settings.
	 *
	 * @return mixed[] {
	 * @type string   $key      Option key.
	 * @type string   $type     Field type.
	 * @type string   $label    Option label.
	 * @type string[] $values   Available values for field.
	 * @type string   $value    Option value.
	 * }
	 */
	public function get_option_data( array $settings ): array {
		return [
			'key'       => $this->option->get_option_key(),
			'group_key' => $this->option->get_group_key(),
			'type'      => $this->option->get_type(),
			'label'     => $this->option::get_label(),
			'values'    => $this->option->get_values( $settings, true ) ?: [],
			'value'     => $this->get_option_value( $settings, true ),
		];
	}

	/**
	 * @param mixed    $current_value Value from plugin settings.
	 * @param string   $option_type   Type of option.
	 * @param string[] $values        Values of option.
	 *
	 * @return string[]|string
	 */
	private function sanitize_option_value( $current_value, string $option_type, array $values ) {
		switch ( $option_type ) {
			case OptionAbstract::OPTION_TYPE_TEXT:
				return sanitize_text_field( wp_unslash( $current_value ) );
			case OptionAbstract::OPTION_TYPE_MULTI_CHECKBOX:
				$valid_values = [];
				foreach ( (array) $current_value as $option_value ) {
					if ( array_key_exists( $option_value, $values ) ) {
						$valid_values[] = $option_value;
					}
				}
				return $valid_values;
			case OptionAbstract::OPTION_TYPE_RADIO:
				return ( array_key_exists( (string) $current_value, $values ) ) ? $current_value : '';
			case OptionAbstract::OPTION_TYPE_TOGGLE:
				return ( (string) $current_value === '1' ) ? '1' : '';
		}

		return $current_value;
	}
}
