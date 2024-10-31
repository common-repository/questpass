<?php

namespace Questpass\Settings\Option;

use Questpass\Cache\TransientCache;
use Questpass\Integration\WpmlIntegration;
use Questpass\Settings\Group\DisplayConfigGroup;

/**
 * Option stores a list of post categories in which to show the quest.
 */
class CategoriesOption extends OptionAbstract {

	const FIELD_NAME                  = 'qp_categories';
	const CATEGORIES_TRANSIENT_OPTION = 'questpass_settings_categories';

	/**
	 * @var TransientCache
	 */
	private $transient_cache;

	public function __construct( TransientCache $transient_cache = null ) {
		$this->transient_cache = $transient_cache ?: new TransientCache();
	}

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
		return __( 'Select the posts categories for which questpasses should be displayed:', 'questpass' );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	public function get_values( array $settings, bool $refreshed_values = false ): array {
		$cached_values = $this->transient_cache->get( self::CATEGORIES_TRANSIENT_OPTION );
		if ( ! $refreshed_values && ( $cached_values !== null ) ) {
			return $cached_values;
		}

		$values = $this->get_categories();
		$this->transient_cache->set( self::CATEGORIES_TRANSIENT_OPTION, $values );
		return $values;
	}

	/**
	 * Returns list of categories for all languages.
	 *
	 * @return string[] Id of categories with names.
	 */
	private function get_categories(): array {
		$wpml_integration = ( new WpmlIntegration() )->before_get_terms();
		$categories       = get_terms(
			[
				'taxonomy'   => 'category',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			]
		);
		$wpml_integration->after_get_terms();

		$values = [];
		if ( ! is_array( $categories ) ) {
			return $values;
		}

		foreach ( $categories as $term_object ) {
			if ( ! is_object( $term_object ) ) {
				continue;
			}
			$values[ $term_object->term_id ] = $term_object->name;
		}
		return $values;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[] Default value of field.
	 */
	public function get_default_value( array $settings ): array {
		return array_keys( $this->get_values( $settings ) );
	}
}
