<?php

namespace Questpass\Service;

use Questpass\QuestpassConstants;
use Questpass\Repository\ServiceStatusRepository;
use QuestpassVendor\Adquesto\SDK\JavascriptStorage;

/**
 * Manages the cache for JavaScript code for the quest.
 */
class ServiceJavascript implements JavascriptStorage {

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	public function __construct( ServiceStatusRepository $service_status_repository ) {
		$this->service_status_repository = $service_status_repository;
	}

	public function get(): string {
		return $this->service_status_repository->get_status()->get_javascript() ?: '';
	}

	/**
	 * @param string $contents .
	 *
	 * @return void
	 */
	public function set( $contents ) {
		$this->service_status_repository->update_status(
			$this->service_status_repository->get_status()->set_javascript( $contents )
		);
	}

	public function valid(): bool {
		$date = $this->service_status_repository->get_status()->get_javascript_updated_at();
		if ( $date === null ) {
			return false;
		}

		return ( $date >= ( time() - QuestpassConstants::CONFIG_JAVASCRIPT_EXPIRATION_TIME ) );
	}
}
