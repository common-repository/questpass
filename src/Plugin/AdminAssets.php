<?php

namespace Questpass\Plugin;

use Questpass\HookableInterface;
use Questpass\PluginInfo;

/**
 * Initializes loading of assets in the admin panel.
 */
class AdminAssets implements HookableInterface {

	const CSS_FILE_PATH = 'assets/build/css/styles.css';

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
		add_filter( 'admin_enqueue_scripts', [ $this, 'load_styles' ] );
	}

	/**
	 * Loads CSS assets.
	 *
	 * @return void
	 * @internal
	 */
	public function load_styles() {
		wp_register_style(
			'questpass',
			$this->plugin_info->get_plugin_directory_url() . self::CSS_FILE_PATH,
			[],
			$this->plugin_info->get_plugin_version()
		);
		wp_enqueue_style( 'questpass' );
	}
}
