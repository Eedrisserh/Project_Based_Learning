<?php
/**
 * The Template for displaying Player Data.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-player-data.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.7
 *
 * @version       0.11.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'player_id' => '',
		'season_id' => '',
		'sections'  => '',
		'context'   => 'shortcode',
	]
);

if ( empty( $data->player_id ) || empty( $data->sections ) ) {
	return;
}

// Get Season ID
if ( empty( $data->season_id ) ) {
	$data->season_id = anwp_football_leagues()->get_active_player_season( $data->player_id );
}

if ( ! absint( $data->season_id ) ) {
	return;
}

/*
|--------------------------------------------------------------------------
| Prepare player data for sections
|--------------------------------------------------------------------------
*/
$position_code = get_post_meta( $data->player_id, '_anwpfl_position', true );

// Card icons
$card_icons = [
	'y'  => '<svg class="icon__card m-0"><use xlink:href="#icon-card_y"></use></svg>',
	'r'  => '<svg class="icon__card m-0"><use xlink:href="#icon-card_r"></use></svg>',
	'yr' => '<svg class="icon__card m-0"><use xlink:href="#icon-card_yr"></use></svg>',
];

$series_map = anwp_football_leagues()->data->get_series();

// Get season matches
$season_matches      = anwp_football_leagues()->player->tmpl_get_latest_matches( $data->player_id, $data->season_id );
$competition_matches = anwp_football_leagues()->player->tmpl_prepare_competition_matches( $season_matches );

$player_data = [
	'player_id'           => $data->player_id,
	'current_season_id'   => $data->season_id,
	'competition_matches' => $competition_matches,
	'card_icons'          => $card_icons,
	'series_map'          => $series_map,
	'position_code'       => $position_code,
	'header'              => false,
	'club_id'             => (int) get_post_meta( $data->player_id, '_anwpfl_current_club', true ),
];

$player_data['club_title'] = anwp_football_leagues()->club->get_club_title_by_id( $player_data['club_id'] );
$player_data['club_link']  = anwp_football_leagues()->club->get_club_link_by_id( $player_data['club_id'] );
?>
<div class="anwp-b-wrap player player__inner player-id-<?php echo (int) $data->player_id; ?>">
	<?php
	$player_sections = wp_parse_slug_list( $data->sections );

	foreach ( $player_sections as $section ) {
		anwp_football_leagues()->load_partial( $player_data, 'player/player-' . sanitize_key( $section ) );
	}
	?>
</div>
