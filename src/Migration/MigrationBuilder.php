<?php

namespace Questpass\Migration;

use Questpass\Exception\MigrationFailedException;

/**
 * .
 */
class MigrationBuilder {

	/**
	 * @var MigrationInterface[]
	 */
	private $migrations = [];

	public function __construct() {
		$this->migrations[] = new CreateSubscriptionUsersMigration();
	}

	/**
	 * Runs migrations in sequence to prepare database for the plugin.
	 *
	 * @return void
	 * @throws MigrationFailedException
	 */
	public function begin_migration() {
		global $wpdb;

		$wpdb->query( 'START TRANSACTION' );
		try {
			foreach ( $this->migrations as $migration ) {
				if ( $migration->up( $wpdb ) !== true ) {
					throw new MigrationFailedException();
				}
			}
			$wpdb->query( 'COMMIT' );
		} catch ( MigrationFailedException $e ) {
			$wpdb->query( 'ROLLBACK' );
			throw $e;
		}
	}

	/**
	 * Reverses all performed migrations.
	 *
	 * @return void
	 * @throws MigrationFailedException
	 */
	public function rollback_migration() {
		global $wpdb;

		$wpdb->query( 'START TRANSACTION' );
		try {
			foreach ( array_reverse( $this->migrations ) as $migration ) {
				if ( $migration->down( $wpdb ) !== true ) {
					throw new MigrationFailedException();
				}
			}
			$wpdb->query( 'COMMIT' );
		} catch ( MigrationFailedException $e ) {
			$wpdb->query( 'ROLLBACK' );
			throw $e;
		}
	}
}
