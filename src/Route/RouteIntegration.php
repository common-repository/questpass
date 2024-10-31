<?php

namespace Questpass\Route;

use Questpass\HookableInterface;
use Questpass\QuestpassConstants;

/**
 * Registers a new route in the WordPress REST API.
 */
class RouteIntegration implements HookableInterface {

	const ROUTE_DESCRIPTION_PARAM = '_desc';

	/**
	 * @var RouteInterface
	 */
	private $api_route;

	public function __construct( RouteInterface $api_route ) {
		$this->api_route = $api_route;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'rest_api_init', [ $this, 'register_endpoint' ] );
		add_filter( 'rest_route_data', [ $this, 'set_endpoint_description' ] );
	}

	/**
	 * Registers REST API route.
	 *
	 * @return void
	 * @internal
	 */
	public function register_endpoint() {
		register_rest_route(
			QuestpassConstants::REST_API_BASE,
			$this->api_route->get_endpoint_route(),
			[
				'methods'             => $this->api_route->get_http_methods(),
				'args'                => $this->api_route->get_route_params(),
				'callback'            => [ $this->api_route, 'get_endpoint_response' ],
				'permission_callback' => function () {
					return true;
				},
			],
			true
		);
	}

	/**
	 * @param mixed[] $available_routes .
	 *
	 * @return mixed[]
	 */
	public function set_endpoint_description( array $available_routes ): array {
		$valid_route = sprintf( '/%1$s/%2$s', QuestpassConstants::REST_API_BASE, $this->api_route->get_endpoint_route() );
		foreach ( $available_routes as $route_name => $route_data ) {
			if ( $valid_route === $route_name ) {
				$available_routes[ $route_name ][ self::ROUTE_DESCRIPTION_PARAM ] = $this->api_route->get_route_desc();
			}
		}

		return $available_routes;
	}
}
