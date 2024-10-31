<?php

namespace Questpass\Settings;

use Questpass\HookableInterface;
use Questpass\Repository\PluginSettingsRepository;

/**
 * Updates a list of categories in the plugin settings.
 */
class CategorySettings implements HookableInterface {

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	public function __construct( PluginSettingsRepository $plugin_settings_repository ) {
		$this->plugin_settings_repository = $plugin_settings_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'created_category', [ $this, 'on_create_category' ] );
		add_action( 'delete_category', [ $this, 'on_delete_category' ] );
	}

	/**
	 * Adds the ID of new category to the plugin settings.
	 *
	 * @param int $term_id The category ID.
	 *
	 * @return void
	 * @internal
	 */
	public function on_create_category( int $term_id ) {
		if ( ! $this->plugin_settings_repository->get_settings()->get_enable_new_categories() ) {
			return;
		}

		$this->plugin_settings_repository->save_settings(
			$this->plugin_settings_repository->get_settings()->add_category( $term_id )
		);
	}

	/**
	 * Removes the ID of deleted category from the plugin settings.
	 *
	 * @param int $deleted_term_id The category ID.
	 *
	 * @return void
	 * @internal
	 */
	public function on_delete_category( int $deleted_term_id ) {
		$this->plugin_settings_repository->save_settings(
			$this->plugin_settings_repository->get_settings()->remove_category( $deleted_term_id )
		);
	}
}
