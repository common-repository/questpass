<?php

namespace Questpass\Content;

use Questpass\HookableInterface;
use Questpass\PluginInfo;

/**
 * Adds a new block to the Gutenberg editor.
 */
class GutenbergBlock implements HookableInterface {

	const GUTENBERG_BLOCK_NAME    = 'questpass/widget';
	const GUTENBERG_ASSETS_HANDLE = 'questpass-gutenberg';
	const CSS_FILE_PATH           = 'assets/editor/quest-preview.css';
	const JS_FILE_PATH            = 'assets/editor/gutenberg-block.js';

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
		add_action( 'init', [ $this, 'register_gutenberg_block' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'load_scripts_for_block' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'load_styles_for_block' ] );
	}

	/**
	 * Registers block type if Gutenberg editor is available.
	 *
	 * @return void
	 * @internal
	 */
	public function register_gutenberg_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			self::GUTENBERG_BLOCK_NAME,
			[
				'editor_script' => self::GUTENBERG_ASSETS_HANDLE,
				'editor_style'  => self::GUTENBERG_ASSETS_HANDLE,
			]
		);
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_scripts_for_block() {
		wp_register_script(
			self::GUTENBERG_ASSETS_HANDLE,
			$this->plugin_info->get_plugin_directory_url() . self::JS_FILE_PATH,
			[ 'wp-blocks', 'wp-element' ],
			$this->plugin_info->get_plugin_version(),
			false
		);
		wp_enqueue_script( self::GUTENBERG_ASSETS_HANDLE );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_styles_for_block() {
		wp_register_style(
			self::GUTENBERG_ASSETS_HANDLE,
			$this->plugin_info->get_plugin_directory_url() . self::CSS_FILE_PATH,
			[],
			$this->plugin_info->get_plugin_version()
		);
		wp_enqueue_style( self::GUTENBERG_ASSETS_HANDLE );
	}
}
