<?php

namespace Questpass\ErrorDetector;

use Questpass\Error\FailedApiConnectionError;
use Questpass\Repository\ServiceStatusRepository;

/**
 * Checks if the connection to the Questpass API is correct.
 */
class FailedApiConnectionErrorDetector implements ErrorDetectorInterface {

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
		if ( $this->service_status_repository->get_status()->get_connection_status() === true ) {
			return null;
		}
		return new FailedApiConnectionError();
	}
}
