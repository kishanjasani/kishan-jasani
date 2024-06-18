<?php
/**
 * Singleton trait.
 *
 * @package kishan-jasani
 */

namespace KishanJasani\Traits;

trait Singleton {

	/**
	 * Private constructor.
	 */
	private function __construct() {}

	/**
	 * Final protected __clone method.
	 */
	final protected function __clone() {}

	/**
	 * Method to get singleton instance.
	 */
	final public static function get_instance() {

		/**
		 * Collection of instance.
		 *
		 * @var array
		 */
		static $instance = array();

		$called_class = get_called_class();

		if ( ! isset( $instance[ $called_class ] ) ) {

			$instance[ $called_class ] = new $called_class();

		}

		return $instance[ $called_class ];
	}
}
