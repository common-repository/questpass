<?php


namespace Questpass\Settings;

use Questpass\ErrorDetector\ErrorDetectorAggregator;
use Questpass\HookableInterface;
use Questpass\Plugin\AdminAssets;
use Questpass\PluginInfo;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\ServiceStatusRepository;
use Questpass\Service\ServiceStatusUpdater;
use Questpass\Service\UpdateJavascript;
use Questpass\Settings\Group\GroupAggregator;
use Questpass\Template\TemplateLoader;

/**
 * Adds the plugin settings page and supports saving settings.
 */
class PluginSettingsPage implements HookableInterface {

	const TEMPLATE_VIEW_PATH = 'views/settings.php';
	const ADMIN_MENU_PAGE    = 'questpass_admin_page';
	const SUBMIT_VALUE       = '_questpass_save_settings';
	const NONCE_PARAM_KEY    = '_questpass_nonce';
	const NONCE_PARAM_VALUE  = 'questpass_save_settings';

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		ServiceStatusRepository $service_status_repository,
		PluginInfo $plugin_info
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->service_status_repository  = $service_status_repository;
		$this->plugin_info                = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'admin_menu', [ $this, 'add_settings_page_for_admin' ] );
	}

	/**
	 * Adds the settings page to the menu.
	 *
	 * @return void
	 * @internal
	 */
	public function add_settings_page_for_admin() {
		$this->add_settings_page( 'options-general.php' );
	}

	/**
	 * Creates settings page of the plugin in the WordPress Admin Dashboard.
	 *
	 * @param string $menu_page Parent menu page.
	 *
	 * @return void
	 */
	private function add_settings_page( string $menu_page ) {
		$page = add_submenu_page(
			$menu_page,
			'Questpass',
			'Questpass',
			'manage_options',
			self::ADMIN_MENU_PAGE,
			[ $this, 'load_settings_view' ]
		);
		add_action( 'load-' . $page, [ $this, 'load_scripts_for_page' ] );
	}

	/**
	 * Displays view for the plugin settings page.
	 *
	 * @return void
	 */
	public function load_settings_view() {
		$this->save_plugin_settings();

		( new TemplateLoader( $this->plugin_info ) )->load_template(
			self::TEMPLATE_VIEW_PATH,
			[
				'error'        => ( new ErrorDetectorAggregator( $this->plugin_settings_repository, $this->service_status_repository ) )->get_error(),
				'groups'       => ( new GroupAggregator() )->get_groups(),
				'options'      => $this->plugin_settings_repository->get_options(),
				'submit_value' => self::SUBMIT_VALUE,
				'nonce_key'    => self::NONCE_PARAM_KEY,
				'nonce_value'  => wp_create_nonce( self::NONCE_PARAM_VALUE ),
				'settings_url' => self::get_settings_page_url(),
			]
		);
	}

	/**
	 * Loads assets on the plugin settings page.
	 *
	 * @return void
	 * @internal
	 */
	public function load_scripts_for_page() {
		( new AdminAssets( $this->plugin_info ) )->init_hooks();
	}

	/**
	 * Returns the URL of the plugin settings page.
	 *
	 * @return string
	 */
	public static function get_settings_page_url(): string {
		return admin_url( 'options-general.php?page=' . self::ADMIN_MENU_PAGE );
	}

	/**
	 * Initializes saving plugin settings from the plugin admin page.
	 *
	 * @return void
	 * @internal
	 */
	private function save_plugin_settings() {
		if ( ! isset( $_POST[ self::SUBMIT_VALUE ] ) ) {
			return;
		} elseif ( ! isset( $_POST[ self::NONCE_PARAM_KEY ] )
			|| ! wp_verify_nonce( $_POST[ self::NONCE_PARAM_KEY ], self::NONCE_PARAM_VALUE ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			wp_die( esc_html( __( 'Sorry, you are not allowed to save plugin settings.', 'questpass' ) ) );
		}

		$this->plugin_settings_repository->save_settings(
			$this->plugin_settings_repository->refresh_settings( $_POST )
		);

		( new ServiceStatusUpdater( $this->plugin_settings_repository, $this->service_status_repository ) )->update_status();
		( new UpdateJavascript( $this->plugin_settings_repository, $this->service_status_repository ) )->update_javascript();
	}
}
