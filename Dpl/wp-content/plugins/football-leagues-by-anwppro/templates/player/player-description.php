<?php
/**
 * The Template for displaying Player >> Description Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-description.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.3
 * @version       0.10.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'player_id' => '',
	]
);

$post_content = get_post_meta( $data->player_id, '_anwpfl_description', true );

if ( ! $post_content ) {
	return;
}
?>
<div class="player__description player-section anwp-section">
	<?php echo do_shortcode( wp_kses_post( wpautop( $post_content ) ) ); ?>
</div>
