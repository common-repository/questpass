<?php

namespace Questpass\Migration;

use wpdb;

/**
 * Supports the migration to create a table for subscription users.
 */
class CreateSubscriptionUsersMigration implements MigrationInterface {

	const TABLE_NAME = 'questpass_subscription_users';

	/**
	 * {@inheritdoc}
	 */
	public function up( wpdb $wpdb ): bool {
		$table_name = $wpdb->base_prefix . self::TABLE_NAME;
		if ( $wpdb->get_var( sprintf( 'SHOW TABLES LIKE "%s";', $table_name ) ) === $table_name ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			return true;
		}

		return (bool) $wpdb->query(
			sprintf(
				'CREATE TABLE %1$s (
					id int NOT NULL AUTO_INCREMENT,
					user_id VARCHAR(64) NOT NULL,
					user_token VARCHAR(64) NOT NULL,
					user_email VARCHAR(255) NOT NULL,
					recurring_payments TINYINT(1) NOT NULL,
					subscription_date DATETIME NOT NULL,
					access_token VARCHAR(255) NOT NULL,
					refresh_token VARCHAR(255) NOT NULL,
					token_expires_at DATETIME NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY ( id ),
					UNIQUE ( user_id ),
					UNIQUE ( user_token )
				) %2$s;',
				$table_name, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$wpdb->get_charset_collate() // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function down( wpdb $wpdb ): bool {
		return (bool) $wpdb->query(
			sprintf(
				'DROP TABLE IF EXISTS %1$s;',
				$wpdb->base_prefix . self::TABLE_NAME // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			)
		);
	}
}
