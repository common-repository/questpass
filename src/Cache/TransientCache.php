<?php

namespace Questpass\Cache;

/**
 * An integration with the WordPress Transients API.
 *
 * @link https://developer.wordpress.org/apis/handbook/transients/
 */
class TransientCache {

	const TRANSIENT_EXPIRATION = 86400;

	/**
	 * Saves values to the cache.
	 *
	 * @param string $transient_name  .
	 * @param mixed  $transient_value .
	 *
	 * @return void
	 */
	public function set( string $transient_name, $transient_value ) {
		set_transient( $transient_name, $transient_value, self::TRANSIENT_EXPIRATION );
	}

	/**
	 * Returns values from the cache.
	 *
	 * @param string $transient_name .
	 *
	 * @return mixed|null
	 */
	public function get( string $transient_name ) {
		$transient_value = get_transient( $transient_name );
		if ( $transient_value === false ) {
			return null;
		}
		return $transient_value;
	}
}
