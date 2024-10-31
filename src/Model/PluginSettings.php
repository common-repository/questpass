<?php

namespace Questpass\Model;

/**
 * Stores values of the plugin settings.
 */
class PluginSettings {

	/**
	 * @var bool
	 */
	private $master_switch;

	/**
	 * @var string
	 */
	private $api_token;

	/**
	 * @var string
	 */
	private $api_client;

	/**
	 * @var string
	 */
	private $api_secret;

	/**
	 * @var string[]
	 */
	private $post_types;

	/**
	 * @var int[]
	 */
	private $categories;

	/**
	 * @var bool
	 */
	private $enable_new_categories;

	/**
	 * @var bool
	 */
	private $hide_for_users;

	/**
	 * @var string
	 */
	private $default_position;

	/**
	 * @param bool     $master_switch         .
	 * @param string   $api_token             .
	 * @param string   $api_client            .
	 * @param string   $api_secret            .
	 * @param string[] $post_types            .
	 * @param int[]    $categories            .
	 * @param bool     $enable_new_categories .
	 * @param bool     $hide_for_users        .
	 * @param string   $default_position      .
	 */
	public function __construct(
		bool $master_switch,
		string $api_token,
		string $api_client,
		string $api_secret,
		array $post_types,
		array $categories,
		bool $enable_new_categories,
		bool $hide_for_users,
		string $default_position
	) {
		$this->set_master_switch( $master_switch );
		$this->set_api_token( $api_token );
		$this->set_api_client( $api_client );
		$this->set_api_secret( $api_secret );
		$this->set_post_types( $post_types );
		$this->set_categories( $categories );
		$this->set_enable_new_categories( $enable_new_categories );
		$this->set_hide_for_users( $hide_for_users );
		$this->set_default_position( $default_position );
	}

	public function get_master_switch(): bool {
		return $this->master_switch;
	}

	public function set_master_switch( bool $master_switch ): self {
		$this->master_switch = $master_switch;
		return $this;
	}

	public function get_api_token(): string {
		return $this->api_token;
	}

	public function set_api_token( string $api_token ): self {
		$this->api_token = $api_token;
		return $this;
	}

	public function get_api_client(): string {
		return $this->api_client;
	}

	public function set_api_client( string $api_client ): self {
		$this->api_client = $api_client;
		return $this;
	}

	public function get_api_secret(): string {
		return $this->api_secret;
	}

	public function set_api_secret( string $api_secret ): self {
		$this->api_secret = $api_secret;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function get_post_types(): array {
		return $this->post_types;
	}

	/**
	 * @param string[] $post_types .
	 */
	public function set_post_types( array $post_types ): self {
		$this->post_types = $post_types;
		return $this;
	}

	/**
	 * @return int[]
	 */
	public function get_categories(): array {
		return $this->categories;
	}

	/**
	 * @param int[] $categories .
	 */
	public function set_categories( array $categories ): self {
		$this->categories = $categories;
		return $this;
	}

	public function add_category( int $category_id ): self {
		$this->categories[] = $category_id;
		return $this;
	}

	public function remove_category( int $deleted_category_id ): self {
		$this->categories = array_filter(
			$this->categories,
			function ( $category_id ) use ( $deleted_category_id ) {
				return ( (int) $category_id !== $deleted_category_id );
			}
		);
		return $this;
	}

	public function get_enable_new_categories(): bool {
		return $this->enable_new_categories;
	}

	public function set_enable_new_categories( bool $enable_new_categories ): self {
		$this->enable_new_categories = $enable_new_categories;
		return $this;
	}

	public function get_hide_for_users(): bool {
		return $this->hide_for_users;
	}

	public function set_hide_for_users( bool $hide_for_users ): self {
		$this->hide_for_users = $hide_for_users;
		return $this;
	}

	public function get_default_position(): string {
		return $this->default_position;
	}

	public function set_default_position( string $default_position ): self {
		$this->default_position = $default_position;
		return $this;
	}
}
