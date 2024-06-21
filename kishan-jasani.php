<?php
/**
 * Main plugin file.
 *
 * Plugin Name: Kishan Jasani
 * Description: A plugin that retrieves data from a remote API endpoint, and makes that data accessible from an API endpoint on the WordPress site. The data will be displayed via a custom block and on an admin WordPress page.
 * Version:     1.0.0
 * Author:      Kishan Jasani
 * Author URI:  https://kishanjasani.wordpress.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: kishan-jasani
 *
 * @package kishan-jasani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'KISHAN_JASANI_DIR' ) ) {
	define( 'KISHAN_JASANI_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'KISHAN_JASANI_URL' ) ) {
	define( 'KISHAN_JASANI_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( is_readable( KISHAN_JASANI_DIR . '/vendor/autoload.php' ) ) {
	require_once KISHAN_JASANI_DIR . '/vendor/autoload.php';
}

if ( class_exists( KishanJasani\Plugin::class ) ) {
	new KishanJasani\Plugin();
}
