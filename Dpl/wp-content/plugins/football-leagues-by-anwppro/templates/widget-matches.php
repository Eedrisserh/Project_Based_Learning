<?php
/**
 * The Template for displaying Matches.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-matches.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.4.3
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prevent errors with new params
$data = (object) wp_parse_args(
	$data,
	[
		'show_club_logos'       => 1,
		'show_match_datetime'   => true,
		'club_links'            => true,
		'group_by_header_style' => '',
		'layout'                => '',
		'group_by'              => '',
		'link_target'           => '',
		'link_text'             => '',
	]
);

// Get competition matches
$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended( $data );
?>
	<div class="anwp-b-wrap match-list match-list--widget layout--<?php echo esc_attr( $data->layout ); ?>">
		<div class="list-group">

			<?php
			$group_current = '';

			foreach ( $matches as $m_index => $match ) {

				if ( '' !== $data->group_by ) {

					$group_text = '';

					// Check current group by value
					if ( 'stage' === $data->group_by && $group_current !== $match->competition_id ) {
						$group_text    = get_post_meta( $match->competition_id, '_anwpfl_stage_title', true );
						$group_current = $match->competition_id;
					} elseif ( 'matchweek' === $data->group_by && $group_current !== $match->match_week && '0' !== $match->match_week ) {
						$group_text    = anwp_football_leagues()->competition->tmpl_get_matchweek_round_text( $match->match_week, $match->competition_id );
						$group_current = $match->match_week;
					} elseif ( 'day' === $data->group_by ) {
						$day_to_compare = date( 'Y-m-d', strtotime( $match->kickoff ) );

						if ( $day_to_compare !== $group_current ) {
							$group_text    = date_i18n( 'j M Y', strtotime( $match->kickoff ) );
							$group_current = $day_to_compare;
						}
					} elseif ( 'month' === $data->group_by ) {
						$month_to_compare = date( 'Y-m', strtotime( $match->kickoff ) );

						if ( $month_to_compare !== $group_current ) {
							$group_text    = date_i18n( 'M Y', strtotime( $match->kickoff ) );
							$group_current = $month_to_compare;
						}
					}

					if ( $group_text ) {

						switch ( $data->group_by_header_style ) {
							case 'secondary':
								$classes = 'h6 competition__stage-title ' . ( $m_index ? 'mt-2' : 'mt-0' );
								break;

							default:
								$classes = 'anwp-bg-dark text-light px-3 py-1 small border-0 competition__stage-title';
						}

						echo '<div class="' . esc_attr( $classes ) . '">' . esc_html( $group_text ) . '</div>';
					}
				}

				$tmpl_data = array_merge( (array) $data, anwp_football_leagues()->match->prepare_match_data_to_render( $match, $data ) );
				anwp_football_leagues()->load_partial( $tmpl_data, 'match/match', 'simple' );
			}
			?>
		</div>
	</div>
<?php if ( ! empty( $data->link_text ) && ! empty( $data->link_target ) ) : ?>
	<div class="anwp-b-wrap">
		<p class="anwp-text-center mt-1">
			<a class="btn btn-sm btn-outline-secondary w-100" target="_blank" href="<?php echo esc_url( get_permalink( (int) $data->link_target ) ); ?>"><?php echo esc_html( $data->link_text ); ?></a>
		</p>
	</div>
	<?php
endif;
