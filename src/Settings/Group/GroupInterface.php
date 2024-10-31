<?php

namespace Questpass\Settings\Group;

/**
 * Interface for a class that handles a field group data in the plugin settings.
 */
interface GroupInterface {

	/**
	 * Returns key of field group.
	 *
	 * @return string
	 */
	public function get_group_key(): string;

	/**
	 * Returns label of group.
	 *
	 * @return string
	 */
	public function get_label(): string;

	/**
	 * Returns description of group.
	 *
	 * @return string|null
	 */
	public function get_desc();

	/**
	 * Returns title of info widget.
	 *
	 * @return string|null
	 */
	public function get_info_title();

	/**
	 * Returns content of info widget (each element is a new paragraph).
	 *
	 * @return string[]|null
	 */
	public function get_info_content();
}
