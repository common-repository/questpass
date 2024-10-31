<?php

namespace Questpass\Settings\Group;

/**
 * Stores an information about field groups for the plugin settings form.
 */
class GroupAggregator {

	/**
	 * @var GroupInterface[]
	 */
	private $groups = [];

	public function __construct() {
		$this->groups[] = new QuestActivationGroup();
		$this->groups[] = new DisplayConfigGroup();
		$this->groups[] = new LocationConfigGroup();
		$this->groups[] = new ApiConfigGroup();
	}

	/**
	 * Returns data about groups of fields.
	 *
	 * @return mixed[]
	 */
	public function get_groups(): array {
		$groups = [];
		foreach ( $this->groups as $group ) {
			$groups[] = $this->get_group_data( $group );
		}
		return $groups;
	}

	/**
	 * Returns data about group of fields.
	 *
	 * @param GroupInterface $group        .
	 *
	 * @return mixed[] {
	 * @type string          $key          Group key.
	 * @type string          $label        Group label.
	 * @type string          $desc         Description of group.
	 * @type string          $info_title   Title of info widget.
	 * @type string[]        $info_content Content of info widget.
	 * }
	 */
	private function get_group_data( GroupInterface $group ): array {
		return [
			'key'          => $group->get_group_key(),
			'label'        => $group->get_label(),
			'desc'         => $group->get_desc(),
			'info_title'   => $group->get_info_title(),
			'info_content' => $group->get_info_content(),
		];
	}
}
