<?php
/**
 * Main plugin class to initialize all other classes.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;

/**
 * Main plugin class to initialize all other classes.
 *
 * @since 1.0.0
 */
class Plugin {

	use Singleton;

	/**
	 * Setup all instance required on plugin initialization.
	 *
	 * @return void
	 */
	public static function setup() {
		Endpoint::get_instance();
	}
}
