<?php
/**
 * The Template for displaying Matches.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-matches.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.4.3
 *
 * @version       0.12.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = (object) wp_parse_args(
	$data,
	[
		'competition_id'        => '',
		'show_secondary'        => 0,
		'season_id'             => '',
		'league_id'             => '',
		'group_id'              => '',
		'type'                  => '',
		'limit'                 => 0,
		'date_from'             => '',
		'date_to'               => '',
		'stadium_id'            => '',
		'filter_by'             => '',
		'filter_values'         => '',
		'filter_by_clubs'       => '',
		'filter_by_matchweeks'  => '',
		'sort_by_date'          => '',
		'sort_by_matchweek'     => '',
		'club_links'            => true,
		'priority'              => '',
		'class'                 => 'mt-4',
		'group_by'              => '',
		'group_by_header_style' => '',
		'show_club_logos'       => 1,
		'show_match_datetime'   => true,
		'competition_logo'      => '1',
		'outcome_id'            => '',
		'no_data_text'          => '',
		'home_club'             => '',
		'away_club'             => '',
	]
);

// Get competition matches
$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended( $args );

if ( empty( $matches ) ) {

	if ( trim( $args->no_data_text ) ) {
		anwp_football_leagues()->load_partial( $args, 'nodata' );
	}

	return;
}
?>
<div class="anwp-b-wrap match-list match-list--shortcode <?php echo esc_attr( $args->class ); ?>">
	<div class="list-group">

		<?php
		$group_current = '';

		foreach ( $matches as $ii => $list_match ) :

			if ( '' !== $args->group_by ) {

				$group_text = '';

				// Check current group by value
				if ( 'stage' === $args->group_by && $group_current !== $list_match->competition_id ) {
					$group_text    = get_post_meta( $list_match->competition_id, '_anwpfl_stage_title', true );
					$group_current = $list_match->competition_id;
				} elseif ( 'competition' === $args->group_by && $group_current !== $list_match->competition_id ) {
					$group_text    = anwp_football_leagues()->competition->get_competition_title( $list_match->competition_id );
					$group_current = $list_match->competition_id;
				} elseif ( 'matchweek' === $args->group_by && $group_current !== $list_match->match_week && '0' !== $list_match->match_week ) {
					$group_text    = anwp_football_leagues()->competition->tmpl_get_matchweek_round_text( $list_match->match_week, $list_match->competition_id );
					$group_current = $list_match->match_week;
				} elseif ( 'day' === $args->group_by ) {
					$day_to_compare = date( 'Y-m-d', strtotime( $list_match->kickoff ) );

					if ( $day_to_compare !== $group_current ) {
						$group_text    = date_i18n( 'j M Y', strtotime( $list_match->kickoff ) );
						$group_current = $day_to_compare;
					}
				} elseif ( 'month' === $args->group_by ) {
					$month_to_compare = date( 'Y-m', strtotime( $list_match->kickoff ) );

					if ( $month_to_compare !== $group_current ) {
						$group_text    = date_i18n( 'M Y', strtotime( $list_match->kickoff ) );
						$group_current = $month_to_compare;
					}
				}

				if ( $group_text ) {

					switch ( $args->group_by_header_style ) {
						case 'secondary':
							$classes = 'h6 competition__stage-title';
							break;

						default:
							$classes = 'anwp-block-header';
					}

					$classes .= $ii ? ' mt-4' : '';

					echo '<div class="' . esc_attr( $classes ) . '">' . esc_html( $group_text ) . '</div>';
				}
			}

			// Get match data to render
			$data = anwp_football_leagues()->match->prepare_match_data_to_render( $list_match, $args );

			$data['competition_logo'] = $args->competition_logo;
			$data['outcome_id']       = $args->outcome_id;

			anwp_football_leagues()->load_partial( $data, 'match/match', 'slim' );

		endforeach;
		?>
	</div>
</div>
