<?php

namespace Questpass\Template;

use Questpass\PluginInfo;

/**
 * Loads template views from the /templates directory.
 */
class TemplateLoader {

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct( PluginInfo $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * Loads the template view with given variables.
	 *
	 * @param string  $path   The server path relative to the plugin root directory.
	 * @param mixed[] $params Variables for the view.
	 *
	 * @return void
	 */
	public function load_template( string $path, array $params = [] ) {
		extract( $params ); // phpcs:ignore
		$view_path = sprintf( '%1$s/templates/%2$s', $this->plugin_info->get_plugin_directory_path(), $path );

		/** @noinspection PhpIncludeInspection */ // phpcs:ignore
		require_once $view_path;
	}
}
