<?php
/**
 * Instantiates CLI command functions.
 *
 * @package    kishan-jasani
 *
 * @since 1.0.0
 */

namespace KishanJasani\Cli;

use WP_CLI;
use WP_CLI_Command;
use KishanJasani\UserData;
use KishanJasani\Cache;


/**
 * Provides CLI functions to clear the API cache.
 *
 * ## EXAMPLES
 *
 *     # Refresh cached data.
 *     $ wp kj flush
 *     Success: cache flushed successfully.
 *
 * @package wp-cli
 */
class WpCliCommand extends WP_CLI_Command
{

	// phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamTag
	/**
	 * Flush the cache.
	 *
	 * ## EXAMPLES
	 *
	 *     # Refresh cached data.
	 *     $ wp kj flush
	 *     Success: cache flushed successfully.
	 *
	 */
	public function flush($args, $assocArgs)
	{
		Cache::get_instance()->remove( UserData::KEY );

		WP_CLI::success( __( 'Cache flushed successfully!', 'kishan-jasani' ) );
	}
}
