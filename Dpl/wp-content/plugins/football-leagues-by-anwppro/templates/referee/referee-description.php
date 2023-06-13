<?php
/**
 * The Template for displaying Referee >> Description Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/referee/referee-description.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.14
 * @version       0.11.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'staff_id' => '',
	]
);

$post_content = get_post_meta( $data->staff_id, '_anwpfl_description', true );

if ( ! $post_content ) {
	return;
}
?>
<div class="player__description player-section anwp-section">
	<?php echo do_shortcode( wp_kses_post( wpautop( $post_content ) ) ); ?>
</div>
