<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\ServiceInactiveError;
use Questpass\Repository\ServiceStatusRepository;

/**
 * Checks whether a given service is active in the Questpass service.
 */
class ServiceInactiveErrorDetector implements ErrorDetectorInterface {

	/**
	 * @var ServiceStatusRepository
	 */
	private $service_status_repository;

	public function __construct( ServiceStatusRepository $service_status_repository ) {
		$this->service_status_repository = $service_status_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		if ( $this->service_status_repository->get_status()->get_service_status() === true ) {
			return null;
		}
		return new ServiceInactiveError();
	}
}
