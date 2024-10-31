<?php

namespace Questpass\Content;

use Questpass\HookableInterface;
use Questpass\PluginInfo;

/**
 * Adds a new button to the TinyMCE editor.
 */
class ClassicEditorButton implements HookableInterface {

	const EDITOR_BUTTON_NAME = 'questpass_button';
	const CSS_FILE_PATH      = 'assets/editor/quest-preview.css';
	const JS_FILE_PATH       = 'assets/editor/tinymce-button.js';

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
		add_filter( 'mce_buttons', [ $this, 'register_editor_button' ] );
		add_filter( 'mce_css', [ $this, 'add_styles_for_button' ] );
		add_action( 'mce_external_plugins', [ $this, 'add_script_for_button' ] );
	}

	/**
	 * @param string[] $buttons .
	 *
	 * @return string[]
	 * @internal
	 */
	public function register_editor_button( array $buttons ): array {
		$buttons[] = self::EDITOR_BUTTON_NAME;
		return $buttons;
	}

	/**
	 * @param string $mce_css The list of URLs to CSS assets separated by commas.
	 *
	 * @return string
	 * @internal
	 */
	public function add_styles_for_button( string $mce_css ): string {
		if ( ! empty( $mce_css ) ) {
			$mce_css .= ',';
		}
		$mce_css .= $this->plugin_info->get_plugin_directory_url() . self::CSS_FILE_PATH;
		return $mce_css;
	}

	/**
	 * @param string[] $plugins The list of buttons with URLs to JavaScript assets.
	 *
	 * @return string[]
	 * @internal
	 */
	public function add_script_for_button( array $plugins ): array {
		$plugins[ self::EDITOR_BUTTON_NAME ] = $this->plugin_info->get_plugin_directory_url() . self::JS_FILE_PATH;
		return $plugins;
	}
}
