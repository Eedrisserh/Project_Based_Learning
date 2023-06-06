<?php
/**
 * The Template for displaying Match content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-match.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.7.3
 *
 * @version       0.11.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// phpcs:disable WordPress.NamingConventions

$match_post = get_post();

// Check fixed state
if ( 'true' !== $match_post->_anwpfl_fixed ) {
	return '';
}

// Prepare Match data
$match = anwp_football_leagues()->match->get_match_data( get_the_ID() );

/**
 * Hook: anwpfl/tmpl-match/before_wrapper
 *
 * @since 0.7.5
 *
 * @param WP_Post $match_post
 */
do_action( 'anwpfl/tmpl-match/before_wrapper', $match_post );
?>
<div class="anwp-b-wrap match match__inner match-<?php echo (int) $match_post->ID; ?> match-status__<?php echo esc_attr( $match_post->_anwpfl_status ); ?>">
	<?php

	// Get match data to render
	$data = anwp_football_leagues()->match->prepare_match_data_to_render( $match, [], 'match', 'full' );

	// Add referee
	$data['referee_id'] = get_post_meta( $match_post->ID, '_anwpfl_referee', true );

	/**
	 * Hook: anwpfl/tmpl-match/before_header
	 *
	 * @since 0.7.5
	 * @since 0.7.6 - Added $data
	 *
	 * @param WP_Post $match_post
	 */
	do_action( 'anwpfl/tmpl-match/before_header', $match_post, $data );

	/**
	 * Filter: anwpfl/tmpl-match/render_header
	 *
	 * @since 0.7.5
	 *
	 * @param WP_Post $match_post
	 */
	if ( apply_filters( 'anwpfl/tmpl-match/render_header', true, $match_post ) ) {
		anwp_football_leagues()->load_partial( $data, 'match/match' );
	}

	/**
	 * Hook: anwpfl/tmpl-match/after_header
	 *
	 * @since 0.7.5
	 * @since 0.7.6 - Added $data
	 *
	 * @param WP_Post $match_post
	 */
	do_action( 'anwpfl/tmpl-match/after_header', $match_post, $data );

	$match_sections = [
		'goals',
		'penalty_shootout',
		'missed_penalties',
		'line_ups',
		'substitutes',
		'missing',
		'referees',
		'video',
		'cards',
		'stats',
		'summary',
		'gallery',
		'latest',
	];

	/**
	 * Filter: anwpfl/tmpl-match/sections
	 *
	 * @since 0.7.5
	 *
	 * @param array   $match_sections
	 * @param array   $data
	 * @param WP_Post $match_post
	 */
	$match_sections = apply_filters( 'anwpfl/tmpl-match/sections', $match_sections, $data, $match_post );

	// Prepare events data
	$events = json_decode( get_post_meta( $match_post->ID, '_anwpfl_match_events', true ) );

	if ( null !== $events ) {
		$events = anwp_football_leagues()->helper->parse_match_events( $events );
	} else {
		$events = [];
	}

	// Prepare stats data
	$stats = json_decode( get_post_meta( $match_post->ID, '_anwpfl_match_stats', true ) );

	if ( null === $stats ) {
		$stats = [];
	}

	// Prepare custom numbers
	$custom_numbers = json_decode( get_post_meta( $match_post->ID, '_anwpfl_match_custom_numbers', true ) );

	if ( null === $custom_numbers ) {
		$custom_numbers = [];
	}

	$data['events']          = $events;
	$data['stats']           = $stats;
	$data['custom_numbers']  = $custom_numbers;
	$data['line_up_home']    = get_post_meta( $match_post->ID, '_anwpfl_players_home_line_up', true );
	$data['line_up_away']    = get_post_meta( $match_post->ID, '_anwpfl_players_away_line_up', true );
	$data['subs_home']       = get_post_meta( $match_post->ID, '_anwpfl_players_home_subs', true );
	$data['subs_away']       = get_post_meta( $match_post->ID, '_anwpfl_players_away_subs', true );
	$data['summary']         = get_post_meta( $match_post->ID, '_anwpfl_summary', true );
	$data['video_source']    = get_post_meta( $match_post->ID, '_anwpfl_video_source', true );
	$data['video_media_url'] = get_post_meta( $match_post->ID, '_anwpfl_video_media_url', true );
	$data['video_id']        = get_post_meta( $match_post->ID, '_anwpfl_video_id', true );

	// Get extra Referees
	$data['assistant_1']       = get_post_meta( $match_post->ID, '_anwpfl_assistant_1', true );
	$data['assistant_2']       = get_post_meta( $match_post->ID, '_anwpfl_assistant_2', true );
	$data['referee_fourth_id'] = get_post_meta( $match_post->ID, '_anwpfl_referee_fourth', true );

	// Get coaches
	$data['coach_home'] = get_post_meta( $match_post->ID, '_anwpfl_coach_home', true );
	$data['coach_away'] = get_post_meta( $match_post->ID, '_anwpfl_coach_away', true );

	// Prepare Match players cache
	anwp_football_leagues()->player->prepare_match_players_cache( $data );

	foreach ( $match_sections as $section ) {
		switch ( $section ) {

			case 'line_ups':
				anwp_football_leagues()->load_partial( $data, 'match/match-lineups' );
				break;

			case 'latest':
				if ( 'fixture' === $match_post->_anwpfl_status ) {
					anwp_football_leagues()->load_partial( $data, 'match/match-latest' );
				}
				break;

			default:
				anwp_football_leagues()->load_partial( $data, 'match/match-' . sanitize_key( $section ) );
		}
	}
	?>
</div>
<?php
/**
 * Hook: anwpfl/tmpl-match/after_wrapper
 *
 * @since 0.7.5
 *
 * @param WP_Post $match_post
 */
do_action( 'anwpfl/tmpl-match/after_wrapper', $match_post );
