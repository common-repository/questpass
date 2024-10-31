<?php

namespace Questpass\Model;

/**
 * Stores values of the post settings.
 */
class PostSettings {

	/**
	 * @var int
	 */
	private $post_id;

	/**
	 * @var bool
	 */
	private $display_quest;

	/**
	 * @param int  $post_id       .
	 * @param bool $display_quest .
	 */
	public function __construct(
		int $post_id,
		bool $display_quest
	) {
		$this->set_post_id( $post_id );
		$this->set_display_quest( $display_quest );
	}

	public function get_post_id(): int {
		return $this->post_id;
	}

	public function set_post_id( int $post_id ): self {
		$this->post_id = $post_id;
		return $this;
	}

	public function get_display_quest(): bool {
		return $this->display_quest;
	}

	public function set_display_quest( bool $display_quest ): self {
		$this->display_quest = $display_quest;
		return $this;
	}
}
