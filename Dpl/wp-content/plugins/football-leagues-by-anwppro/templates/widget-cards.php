<?php
/**
 * The Template for displaying Widget :: Cards.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-cards.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.7.3
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data->context = 'widget';
$data->layout  = 'mini';

echo anwp_football_leagues()->template->shortcode_loader( 'cards', (array) $data ); // WPCS: XSS ok.

// Prevent errors with new params
$data = (object) wp_parse_args(
	$data,
	[
		'link_text'   => '',
		'link_target' => '',
	]
);

if ( ! empty( $data->link_text ) && ! empty( $data->link_target ) ) : ?>
	<div class="anwp-b-wrap mt-0">
		<p class="anwp-text-center mt-2">
			<a class="btn btn-sm btn-outline-secondary w-100" target="_blank" href="<?php echo esc_url( get_permalink( (int) $data->link_target ) ); ?>"><?php echo esc_html( $data->link_text ); ?></a>
		</p>
	</div>
	<?php
endif;
