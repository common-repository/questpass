<?php

namespace Questpass\Settings\Option;

use Questpass\Settings\Group\DisplayConfigGroup;

/**
 * Option stores a list of post types in which to show the quest.
 */
class PostTypesOption extends OptionAbstract {

	const FIELD_NAME = 'qp_post_types';

	/**
	 * {@inheritdoc}
	 */
	public function get_group_key(): string {
		return DisplayConfigGroup::FIELD_GROUP_KEY;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option_key(): string {
		return self::FIELD_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionAbstract::OPTION_TYPE_MULTI_CHECKBOX;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_label(): string {
		return __( 'Select the types of entries for which questpasses should be displayed:', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	public function get_values( array $settings, bool $refreshed_values = false ): array {
		$post_types = get_post_types(
			[
				'public' => true,
			]
		);

		$values = [];
		foreach ( $post_types as $post_type => $type_label ) {
			$values[ $post_type ] = ( is_string( $type_label ) ? $type_label : $post_type );
		}
		return $values;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[] Default value of field.
	 */
	public function get_default_value( array $settings ): array {
		return [ 'post', 'page' ];
	}
}
