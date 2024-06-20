<?php
/**
 * Custom admin page to show API data.
 *
 * @package kishan-jasani
 *
 * @since 1.0.0
 */

namespace KishanJasani;

use KishanJasani\Traits\Singleton;

/**
 * Responsible for creating custom admin page to show API data.
 *
 * @since 1.0.0
 */
class Settings {

	use Singleton;

	const SLUG = 'kishan-jasani';

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
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'in_admin_header', array( $this, 'admin_page_header' ) );
	}

	/**
	 * Adds assets on admin side.
	 */
	public function admin_assets() {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		$assets = Assets::get_instance();
		$assets->enqueue_style( 'kj-admin', 'admin.css' );
		$assets->enqueue_script( 'kj-admin', 'admin.js' );
	}

	/**
	 * Adds header html on admin page.
	 */
	public function admin_page_header() {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		?>
		<div id="kj-admin-header">
			<h1><?php esc_attr_e( 'Kishan Jasani', 'kishan-jasani' ); ?></h1>
		</div>
		<?php
	}

	/**
	 * Adds custom admin page.
	 */
	public function admin_page() {
		add_menu_page(
			__( 'Kishan Jasani', 'kishan-jasani' ),
			__( 'Kishan Jasani', 'kishan-jasani' ),
			'manage_options',
			self::SLUG,
			array( $this, 'admin_page_callback' ),
			'dashicons-chart-bar'
		);
	}

	/**
	 * Page renderer callback.
	 */
	public function admin_page_callback() {
		$cache = Cache::get_instance();
		$user = new UserData( $cache );
		$data = $user->get_data();

		$nodata = ( empty( $data['headers'] ) || empty( $data['rows'] ) );
		?>
		<div class="wrap" id="wrap">
			<div class="kj-page">
				<div class="kj-page__title">
					<span><?php esc_html_e( 'Users', 'kishan-jasani' ); ?></span>
				</div>
				<div class="kj-page__content">

					<div class="kj-page__heading-container">
						<div class="kj-page__heading">
							<h3><?php esc_html_e( 'Users', 'kishan-jasani' ); ?></h3>
							<p class="desc"><?php esc_html_e( 'List of users fetched from a remote API. Refresh the table by clicking the refresh button at the top right side of the table.', 'kishan-jasani' ); ?></p>
						</div>
					</div>

					<div id="banner" class="kj-page__banner">
						<?php if ( ! $nodata ) : ?>
							<span class="kj-page__refresh">
								<button id="kj-refresh" title="<?php esc_attr_e( 'Refresh data', 'kishan-jasani' ); ?>">
									<span class="dashicons dashicons-image-rotate"></span>
								</button>
							</span>
						<?php endif; ?>

						<table>
							<?php if ( $nodata ) : ?>
								<caption>
									<div class="notice notice-error">
										<p><?php esc_html_e( 'Something went wrong while fetching data.', 'kishan-jasani' ); ?></p>
									</div>
								</caption>
							<?php else : ?>
								<caption></caption>
								<thead>
									<tr>
									<?php foreach ( $data['headers'] as $header ) : ?>
										<th><?php echo esc_html( $header ); ?></th>
									<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $data['rows'] as $row ) : ?>
										<tr>
										<?php foreach ( $row as $value ) : ?>
												<td><?php echo esc_html( $value ); ?></td>
										<?php endforeach; ?>
											</tr>
									<?php endforeach; ?>
								</tbody>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Checks whether current page is admin page.
	 *
	 * @return bool
	 */
	private function is_admin_page() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$cur_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

		return self::SLUG === $cur_page && is_admin();
	}

}
