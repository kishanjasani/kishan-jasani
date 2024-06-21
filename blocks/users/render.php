<?php
/**
 * Render file for Users block.
 *
 * @package kishan-jasani
 */

use KishanJasani\UserData;
use KishanJasani\Cache;

$headers = array();
$rows    = array();
$mapping = array();
$hide    = $attributes['hide'];

$user = new UserData( Cache::get_instance() );
$data = $user->get_data();

if ( ! empty( $data ) ) {
	$headers = $data['headers'];
	$rows    = $data['rows'];

	foreach ( $headers as $i => $header ) {
		$first_row          = $rows[ array_keys( $rows )[0] ];
		$mapping[ $header ] = array_keys( $first_row )[ $i ];
	}
}
?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php if ( empty( $headers ) || empty( $rows ) ) : ?>
		<h5><?php esc_html_e( 'Unable to fetch data.', 'kishan-jasani' ); ?></h5>
	<?php elseif ( count( $hide ) === count( $headers ) ) : ?>
		<h5><?php esc_html_e( 'No data selected.', 'kishan-jasani' ); ?></h5>
	<?php else : ?>
		<table>
			<thead>
				<tr>
					<?php foreach ( $headers as $header ) : ?>
						<?php if ( ! in_array( $mapping[ $header ], $hide, true ) ) : ?>
							<th><?php echo esc_html( $header ); ?></th>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<?php foreach ( $row as $key => $data ) : ?>
							<?php if ( ! in_array( $key, $hide, true ) ) : ?>
								<td><center><?php echo esc_html( $data ); ?></center></td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
