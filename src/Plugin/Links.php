<?php

namespace Questpass\Plugin;

use Questpass\HookableInterface;
use Questpass\PluginInfo;
use Questpass\Settings\PluginSettingsPage;

/**
 * Adds links to plugin actions in the list of plugins in the admin panel.
 */
class Links implements HookableInterface {

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
		add_filter( 'plugin_action_links_' . $this->plugin_info->get_plugin_basename(), [ $this, 'add_plugin_links_for_admin' ] );
	}

	/**
	 * Adds new links to the list of plugin actions.
	 *
	 * @param string[] $links Plugin action links.
	 *
	 * @return string[] Plugin action links.
	 * @internal
	 */
	public function add_plugin_links_for_admin( array $links ): array {
		return $this->add_link_to_settings( $links );
	}

	/**
	 * Adds the link of plugin settings page to the list of plugin actions.
	 *
	 * @param string[] $links Plugin action links.
	 *
	 * @return string[] Plugin action links.
	 */
	private function add_link_to_settings( array $links ): array {
		array_unshift(
			$links,
			sprintf(
			/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
				esc_html( __( '%1$sSettings%2$s', 'questpass' ) ),
				'<a href="' . PluginSettingsPage::get_settings_page_url() . '">',
				'</a>'
			)
		);
		return $links;
	}
}
