<?php

namespace Questpass\Integration;

use Questpass\Logger\LoggerInterface;
use Questpass\Logger\WordpressLogger;

/**
 * An integration with the WPML plugin.
 */
class WpmlIntegration {

	/**
	 * The main class of WPML plugin.
	 *
	 * @var object|null
	 */
	private $sitepress = null;

	/**
	 * @var bool
	 */
	private $has_get_terms_args;

	/**
	 * @var bool
	 */
	private $has_get_term;

	/**
	 * @var bool
	 */
	private $has_terms_clauses;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct( LoggerInterface $logger = null ) {
		$this->sitepress = $this->get_integration_wpml();
		$this->logger    = $logger ?: new WordpressLogger();
	}

	/**
	 * Returns an integration object with the WPML plugin.
	 *
	 * @return object|null The main class of WPML plugin.
	 */
	private function get_integration_wpml() {
		global $sitepress;

		if ( ! $sitepress || ! is_object( $sitepress ) ) {
			return null;
		}
		return $sitepress;
	}

	/**
	 * Removes filters so that the get_terms() function returns categories for all languages.
	 *
	 * @return self
	 */
	public function before_get_terms(): self {
		if ( $this->sitepress === null ) {
			return $this;
		}

		if ( ! method_exists( $this->sitepress, 'get_terms_args_filter' )
			|| ! method_exists( $this->sitepress, 'get_term_adjust_id' )
			|| ! method_exists( $this->sitepress, 'terms_clauses' ) ) {
			$this->logger->error( 'Not compatible with WPML plugin.', __METHOD__ );
			return $this;
		}

		$this->has_get_terms_args = remove_filter( 'get_terms_args', [ $this->sitepress, 'get_terms_args_filter' ] );
		$this->has_get_term       = remove_filter( 'get_term', [ $this->sitepress, 'get_term_adjust_id' ], 1 );
		$this->has_terms_clauses  = remove_filter( 'terms_clauses', [ $this->sitepress, 'terms_clauses' ] );
		return $this;
	}

	/**
	 * Adds filters so that the get_terms() function returns categories for the current language.
	 *
	 * @return void
	 */
	public function after_get_terms() {
		if ( $this->has_get_terms_args ) {
			add_filter( 'terms_clauses', [ $this->sitepress, 'terms_clauses' ] );
		}
		if ( $this->has_get_term ) {
			add_filter( 'get_term', [ $this->sitepress, 'get_term_adjust_id' ] );
		}
		if ( $this->has_terms_clauses ) {
			add_filter( 'get_terms_args', [ $this->sitepress, 'get_terms_args_filter' ] );
		}
	}
}
