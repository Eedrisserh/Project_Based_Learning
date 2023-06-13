<?php
/**
 * The Template for displaying No Data text.
 * Used in shortcodes.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/no-data.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.12
 *
 * @version       0.11.12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'no_data_text' => '',
	]
);

if ( empty( $data->no_data_text ) ) {
	return;
}
?>
<div class="anwp-b-wrap">
	<div class="anwp-fl-nodata">
		<?php echo esc_html( $data->no_data_text ); ?>
	</div>
</div>
