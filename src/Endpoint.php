<?php
/**
 * Registers API endpoint to provide API data.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;
use \WP_REST_Request;

/**
 * Registers API endpoint to provide API data.
 *
 * Registers custom REST API endpoint which uses Data class to fetch data from remote API or cache.
 *
 * @since 1.0.0
 */
class Endpoint {

	use Singleton;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Method to setup wp hooks.
	 */
	protected function setup_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_route' ) );
	}

	/**
	 * Registers rest route.
	 */
	public function register_route() {
		register_rest_route(
			'kishan-jasani/v1',
			'/users',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'api_callback' ),
			)
		);
	}

	/**
	 * API callback function.
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	public function api_callback( WP_REST_Request $request ) {
		$refresh = '';
		if ( isset( $request['refresh'] ) ) {
			$refresh = 'refresh';
		}

		$cache = Cache::get_instance();
		$user  = new UserData( $cache );
		$data  = $user->get_data( $refresh );

		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( $data );
	}
}
