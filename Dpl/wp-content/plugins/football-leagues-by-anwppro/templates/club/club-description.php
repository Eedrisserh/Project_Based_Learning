<?php
/**
 * The Template for displaying Club >> Description Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-description.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.4
 *
 * @version       0.10.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'description' => '',
	]
);

if ( ! trim( $data->description ) ) {
	return;
}
?>
<div class="club__description club-section anwp-section">
	<?php echo do_shortcode( wp_kses_post( wpautop( $data->description ) ) ); ?>
</div>
