<?php

namespace Questpass\Migration;

use wpdb;

interface MigrationInterface {

	/**
	 * Creates new changes in the database.
	 *
	 * @param wpdb $wpdb .
	 *
	 * @return bool Status of operation.
	 */
	public function up( wpdb $wpdb ): bool;

	/**
	 * Reverses changes in the database.
	 *
	 * @param wpdb $wpdb .
	 *
	 * @retrun bool Status of operation.
	 */
	public function down( wpdb $wpdb ): bool;
}
