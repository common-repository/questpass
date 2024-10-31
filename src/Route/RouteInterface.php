<?php

namespace Questpass\Route;

interface RouteInterface {

	/**
	 * Returns route of REST API endpoint.
	 *
	 * @return string Route name.
	 */
	public function get_endpoint_route(): string;

	/**
	 * Returns the list of methods supported for REST API route.
	 *
	 * @return string[] .
	 */
	public function get_http_methods(): array;

	/**
	 * Returns the list of args for params used to register the endpoint.
	 *
	 * @return mixed[] Args for endpoint params.
	 */
	public function get_route_params(): array;

	/**
	 * Returns description of REST API endpoint.
	 *
	 * @return string|null .
	 */
	public function get_route_desc();

	/**
	 * Returns response for REST API route.
	 *
	 * @param \WP_REST_Request $request .
	 *
	 * @return \WP_REST_Response|void
	 * @internal
	 */
	public function get_endpoint_response( \WP_REST_Request $request );
}
