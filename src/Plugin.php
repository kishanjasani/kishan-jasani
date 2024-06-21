<?php
/**
 * Main plugin class to initialize all other classes.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use WP_CLI;
use WP_CLI_Command;
use KishanJasani\Traits\Singleton;

/**
 * Main plugin class to initialize all other classes.
 *
 * @since 1.0.0
 */
final class Plugin {

	use Singleton;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Setup all instance required on plugin initialization.
	 *
	 * @return void
	 */
	public function setup() {
		if ( is_admin() ) {
			Settings::get_instance();
		}

		Endpoint::get_instance();
		Blocks::get_instance();

		if ( $this->is_wp_cli() ) {
			WP_CLI::add_command( 'kj', Cli\WpCliCommand::class );
		}
	}

	private function is_wp_cli() {
		return defined( 'WP_CLI' )
			&& class_exists( WP_CLI::class )
			&& class_exists( WP_CLI_Command::class );
	}
}
