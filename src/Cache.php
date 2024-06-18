<?php
/**
 * PHP class to handle caching.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;

/**
 * Responsible for providing access to caching.
 *
 * @since 1.0.0
 */
class Cache {

	use Singleton;

	/**
	 * Cache duration.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const DURATION = HOUR_IN_SECONDS;

	/**
	 * Get transient data.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get( string $key ) {
		return get_transient( $key );
	}

	/**
	 * Sets transient data.
	 *
	 * @param mixed $data Data to set.
	 *
	 * @return bool
	 */
	public function set( $key, $data ) {
		return set_transient( $key, $data, Cache::DURATION );
	}

	/**
	 * Delete transient data.
	 *
	 * @return bool
	 */
	public function remove( $key ) {
		return delete_transient( $key );
	}
}
