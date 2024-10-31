<?php

namespace Questpass;

/**
 * Interface for a class integrating with WordPress hooks.
 *
 * @link https://developer.wordpress.org/plugins/hooks/
 */
interface HookableInterface {

	/**
	 * Adds filters and actions to integrate with WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks();
}
