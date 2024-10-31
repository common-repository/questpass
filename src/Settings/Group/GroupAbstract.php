<?php

namespace Questpass\Settings\Group;

/**
 * {@inherit}
 */
abstract class GroupAbstract implements GroupInterface {

	/**
	 * {@inheritdoc}
	 */
	public function get_desc() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_info_title() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_info_content() {
		return null;
	}
}
