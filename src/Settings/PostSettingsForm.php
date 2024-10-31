<?php

namespace Questpass\Settings;

use Questpass\HookableInterface;
use Questpass\PluginInfo;
use Questpass\Repository\PluginSettingsRepository;
use Questpass\Repository\PostSettingsRepository;
use Questpass\Template\TemplateLoader;

/**
 * Adds a metabox on the post edit page and supports saving settings.
 */
class PostSettingsForm implements HookableInterface {

	const NONCE_PARAM_KEY    = '_questpass_nonce';
	const SUBMIT_VALUE       = '_questpass_save_post';
	const NONCE_PARAM_VALUE  = 'questpass_save_post';
	const TEMPLATE_VIEW_PATH = 'components/widgets/post_options.php';

	/**
	 * @var PluginSettingsRepository
	 */
	private $plugin_settings_repository;

	/**
	 * @var PostSettingsRepository
	 */
	private $post_settings_repository;

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct(
		PluginSettingsRepository $plugin_settings_repository,
		PostSettingsRepository $post_settings_repository,
		PluginInfo $plugin_info
	) {
		$this->plugin_settings_repository = $plugin_settings_repository;
		$this->post_settings_repository   = $post_settings_repository;
		$this->plugin_info                = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'add_meta_boxes', [ $this, 'add_settings_box' ] );
		add_action( 'save_post', [ $this, 'save_post_settings' ] );
	}

	/**
	 * Initializes displaying a metabox on the post edit page.
	 *
	 * @link https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
	 *
	 * @return void
	 * @internal
	 */
	public function add_settings_box() {
		add_meta_box(
			'questpass-settings',
			__( 'Questpass', 'questpass' ),
			[ $this, 'load_settings_view' ],
			$this->plugin_settings_repository->get_settings()->get_post_types(),
			'side'
		);
	}

	/**
	 * Displays a content of a metabox with the post settings.
	 *
	 * @return void
	 * @internal
	 */
	public function load_settings_view() {
		global $post;

		( new TemplateLoader( $this->plugin_info ) )->load_template(
			self::TEMPLATE_VIEW_PATH,
			[
				'options'      => $this->post_settings_repository->get_options( $post->ID ),
				'submit_value' => self::SUBMIT_VALUE,
				'nonce_key'    => self::NONCE_PARAM_KEY,
				'nonce_value'  => wp_create_nonce( self::NONCE_PARAM_VALUE ),
			]
		);
	}

	/**
	 * Initializes saving post settings from a metabox for the post.
	 *
	 * @return void
	 * @internal
	 */
	public function save_post_settings() {
		global $post;
		if ( ( $post === null ) || ! isset( $_POST[ self::SUBMIT_VALUE ] ) ) {
			return;
		} elseif ( ! isset( $_POST[ self::NONCE_PARAM_KEY ] )
			|| ! wp_verify_nonce( $_POST[ self::NONCE_PARAM_KEY ], self::NONCE_PARAM_VALUE ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			wp_die( esc_html( __( 'Sorry, you are not allowed to save post settings.', 'questpass' ) ) );
		}

		$this->post_settings_repository->save_settings(
			$this->post_settings_repository->refresh_settings( $post->ID, $_POST )
		);
	}
}
