<?php
/**
 * PHP class to initialize custom blocks.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;

/**
 * Responsible for initializing custom blocks.
 *
 * @since 1.0.0
 */
class Blocks {

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
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register blocks.
	 */
	public function register_blocks() {
		register_block_type( KISHAN_JASANI_DIR . '/build/blocks/users' );
	}

}
