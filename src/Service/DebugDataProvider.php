<?php

namespace Questpass\Service;

use Questpass\PluginInfo;

/**
 * Returns information useful for debugging..
 */
class DebugDataProvider {

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct( PluginInfo $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	public function get_wordpress_version(): string {
		global $wp_version;
		return $wp_version;
	}

	public function get_plugin_version(): string {
		return $this->plugin_info->get_plugin_version();
	}

	public function get_php_version(): string {
		return phpversion() ?: '';
	}

	/**
	 * @return mixed[] Plugin slugs with versions.
	 */
	public function get_plugins(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		$values  = [];
		foreach ( $plugins as $plugin_slug => $plugin_data ) {
			$values[ $plugin_slug ] = [
				'version'   => $plugin_data['Version'],
				'is_active' => is_plugin_active( $plugin_slug ),
			];
		}
		return $values;
	}

	/**
	 * @return mixed[] Theme slugs with versions.
	 */
	public function get_themes(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$theme_objects = wp_get_themes();
		$active_theme  = wp_get_theme();
		$values        = [];
		foreach ( $theme_objects as $theme_slug => $theme_object ) {
			$values[ $theme_slug ] = [
				'version'   => $theme_object->get( 'Version' ),
				'is_active' => ( $active_theme->get_stylesheet() === $theme_object->get_stylesheet() ),
			];
		}
		return $values;
	}
}
