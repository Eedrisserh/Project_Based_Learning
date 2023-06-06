<?php
/**
 * The Template for displaying Match Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-match.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author          Andrei Strekozov <anwp.pro>
 * @package         AnWP-Football-Leagues/Templates
 * @since           0.6.1
 *
 * @version         0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = (object) wp_parse_args(
	$data,
	[
		'match_id'        => '',
		'club_last'       => '',
		'club_next'       => '',
		'layout'          => '',
		'sections'        => '',
		'show_header'     => 1,
		'class'           => '', // TODO add to params
		'show_club_logos' => '1', // TODO add to params
	]
);

// Get match data
if ( absint( $args->match_id ) ) {
	$match_id = $args->match_id;
} elseif ( absint( $args->club_last ) ) {

	$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended(
		[
			'type'            => 'result',
			'filter_by_clubs' => $args->club_last,
			'limit'           => 1,
			'sort_by_date'    => 'desc',
		]
	);

	if ( empty( $matches ) || empty( $matches[0]->match_id ) ) {
		return;
	}

	$match_id = $matches[0]->match_id;
} elseif ( absint( $args->club_next ) ) {

	$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended(
		[
			'type'            => 'fixture',
			'filter_by_clubs' => $args->club_next,
			'limit'           => 1,
			'sort_by_date'    => 'asc',
		]
	);

	if ( empty( $matches ) || empty( $matches[0]->match_id ) ) {
		return;
	}

	$match_id = $matches[0]->match_id;
}

if ( ! empty( $match_id ) ) {
	$match = anwp_football_leagues()->match->get_match_data( $match_id );
}

if ( empty( $match ) ) {
	return;
}
?>
<div class="anwp-b-wrap match-single match--shortcode match-slim--shortcode <?php echo esc_attr( $args->class ); ?>">
	<?php
	// Get match data to render
	$data = anwp_football_leagues()->match->prepare_match_data_to_render( $match, $args, 'shortcode', $args->layout );

	if ( anwp_football_leagues()->helper->string_to_bool( $args->show_header ) ) {
		anwp_football_leagues()->load_partial( $data, 'match/match', $args->layout );
	}

	if ( ! empty( $args->sections ) && 'slim' !== $args->layout ) {
		$sections = explode( ',', $args->sections );

		// Prepare events data
		$events = json_decode( get_post_meta( $match_id, '_anwpfl_match_events', true ) );

		if ( null !== $events ) {
			$events = anwp_football_leagues()->helper->parse_match_events( $events );
		} else {
			$events = [];
		}

		// Prepare stats data
		$stats = json_decode( get_post_meta( $match_id, '_anwpfl_match_stats', true ) );

		if ( null === $stats ) {
			$stats = [];
		}

		$data['events']          = $events;
		$data['stats']           = $stats;
		$data['line_up_home']    = get_post_meta( $match_id, '_anwpfl_players_home_line_up', true );
		$data['line_up_away']    = get_post_meta( $match_id, '_anwpfl_players_away_line_up', true );
		$data['subs_home']       = get_post_meta( $match_id, '_anwpfl_players_home_subs', true );
		$data['subs_away']       = get_post_meta( $match_id, '_anwpfl_players_away_subs', true );
		$data['summary']         = get_post_meta( $match_id, '_anwpfl_summary', true );
		$data['video_source']    = get_post_meta( $match_id, '_anwpfl_video_source', true );
		$data['video_media_url'] = get_post_meta( $match_id, '_anwpfl_video_media_url', true );
		$data['video_id']        = get_post_meta( $match_id, '_anwpfl_video_id', true );
		$data['coach_home']      = get_post_meta( $match_id, '_anwpfl_coach_home', true );
		$data['coach_away']      = get_post_meta( $match_id, '_anwpfl_coach_away', true );

		foreach ( $sections as $section ) {
			switch ( $section ) {
				case 'line_ups':
				case 'line-ups':
					anwp_football_leagues()->load_partial( $data, 'match/match-lineups' );
					break;

				default:
					anwp_football_leagues()->load_partial( $data, 'match/match-' . sanitize_key( $section ) );
			}
		}
	}
	?>
</div>
