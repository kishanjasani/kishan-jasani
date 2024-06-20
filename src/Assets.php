<?php
/**
 * PHP class to handle assets.
 *
 * @since 1.0.0
 *
 * @package kishan-jasani
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;

/**
 * Responsible for handling assets including dependencies management and enqueuing.
 *
 * @since 1.0.0
 */
class Assets {

	use Singleton;

	const BUILD_DIR = KISHAN_JASANI_DIR . '/build/assets/';
	const BUILD_URL = KISHAN_JASANI_URL . '/build/assets/';

	/**
	 * Enqueues script file.
	 *
	 * @param string $handle Script handle.
	 * @param string $file_name Build file name inside build-nonblocks folder.
	 * @param array  $dependencies Script dependencies.
	 *
	 * @return void
	 */
	public function enqueue_script( $handle, $file_name, $dependencies = array() ) {

		$assets = $this->get_assets( $file_name );

		if ( ! is_array( $dependencies ) ) {
			$dependencies = array();
		}
		$deps = array_merge( $assets['dependencies'], $dependencies );

		wp_enqueue_script( $handle, self::BUILD_URL . $file_name, $deps, $assets['version'], true );
	}

	/**
	 * Enqueues style file.
	 *
	 * @param string $handle Style handle.
	 * @param string $file_name Built file name inside build-non-blocks folder.
	 * @param array  $dependencies Style dependencies.
	 */
	public function enqueue_style( $handle, $file_name, $dependencies = array() ) {

		wp_enqueue_style( $handle, self::BUILD_URL . $file_name, $dependencies, $this->get_modified_time( $file_name ) );
	}

	/**
	 * Retrieves assets php file from file name.
	 *
	 * @param string $file_name File name.
	 *
	 * @return array Asset data.
	 */
	private function get_assets( $file_name ) {
		$path = explode( '/', $file_name );
		$path = $path[ count( $path ) - 1 ];

		$name = explode( '.', $path );

		$asset_file = self::BUILD_DIR . $name[0] . '.asset.php';
		$assets     = array(
			'dependencies' => array(),
			'version'      => '',
		);

		if ( file_exists( $asset_file ) ) {
			$assets = (array) require $asset_file;
		} else {
			$assets['version'] = $this->get_modified_time( $file_name );
		}

		return $assets;
	}

	/**
	 * Retrieves last modified time.
	 *
	 * @param string $file_name File name.
	 *
	 * @return string
	 */
	private function get_modified_time( $file_name ) {
		if ( file_exists( self::BUILD_DIR . $file_name ) ) {
			return filemtime( self::BUILD_DIR . $file_name );
		}

		return '';
	}

}
