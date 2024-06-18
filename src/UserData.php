<?php
/**
 * PHP class to retrieve data from API endpoint or cache.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

/**
 * Responsible for providing API data from remote or cache.
 *
 * @since 1.0.0
 */
class UserData {

	/**
	 * Remote API endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ENDPOINT = 'https://miusage.com/v1/challenge/1/';

	/**
	 * Cache key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const KEY = 'kishan-jasani-data';

	/**
	 * Cache object.
	 *
	 * @since 1.0.0
	 *
	 * @var Cache
	 */
	private Cache $cache;

	/**
	 * Constructor.
	 */
	public function __construct( Cache $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Get data from API or cache.
	 *
	 * @param string $refresh Whether to refresh data.
	 *
	 * @return array
	 */
	public function get_data( $refresh = '' ) {

		$data = $this->cache->get( UserData::KEY );

		if ( 'refresh' === strtolower( trim( $refresh ) ) || empty( $data ) ) {
			$data = $this->get_remote();
		}

		if ( empty( $data ) ) {
			return $data;
		}

		$this->cache->set( UserData::KEY, $data );

		$data = $this->process_data( $data );

		return $data;

	}

	/**
	 * Process data in a better accessible way.
	 *
	 * @param string $data Data retrieved from API.
	 *
	 * @return array Processed data.
	 */
	private function process_data( $data ) {
		$temp_data = json_decode( $data, true );

		if ( empty( $temp_data['data']['headers'] ) || empty( $temp_data['data']['rows'] ) ) {
			return array();
		}

		$temp_data = array(
			'headers' => $temp_data['data']['headers'],
			'rows'    => $temp_data['data']['rows'],
		);
		$data      = $temp_data;

		foreach ( $temp_data['rows'] as $row_key => $row ) {
			foreach ( $row as $key => $value ) {
				if ( 'date' === $key ) {
					$value = wp_date( 'D M j Y', intval( $value ) );
				}
				$data['rows'][ $row_key ][ $key ] = $value;
			}
		}

		return $data;
	}

	/**
	 * Get data from remote server and return processed data.
	 *
	 * @return array
	 */
	private function get_remote() {
		$response  = wp_remote_get( UserData::ENDPOINT );
		$http_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $http_code ) {
			return array();
		}

		return wp_remote_retrieve_body( $response );
	}
}
