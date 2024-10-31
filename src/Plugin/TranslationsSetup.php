<?php

namespace Questpass\Plugin;

use Questpass\HookableInterface;
use Questpass\PluginInfo;

/**
 * Manages the Internationalization for the plugin.
 */
class TranslationsSetup implements HookableInterface {

	const PLUGIN_TEXTDOMAIN = 'questpass';

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct( PluginInfo $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_filter( 'init', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			self::PLUGIN_TEXTDOMAIN,
			false,
			basename( $this->plugin_info->get_plugin_directory_path() ) . '/languages'
		);
	}
}
