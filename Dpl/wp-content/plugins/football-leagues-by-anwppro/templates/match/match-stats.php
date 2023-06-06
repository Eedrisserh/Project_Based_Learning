<?php
/**
 * The Template for displaying Match >> Stats Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-stats.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.9.0
 *
 * @version       0.11.13
 */
// phpcs:disable WordPress.NamingConventions.ValidVariableName

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'kickoff'         => '',
		'match_date'      => '',
		'match_time'      => '',
		'home_club'       => '',
		'away_club'       => '',
		'club_home_title' => '',
		'club_away_title' => '',
		'club_home_link'  => '',
		'club_away_link'  => '',
		'club_home_logo'  => '',
		'club_away_logo'  => '',
		'match_id'        => '',
		'season_id'       => '',
		'finished'        => '',
		'home_goals'      => '',
		'away_goals'      => '',
		'match_week'      => '',
		'stadium_id'      => '',
		'competition_id'  => '',
		'main_stage_id'   => '',
		'stage_title'     => '',
		'events'          => [],
		'stats'           => [],
		'line_up_home'    => '',
		'line_up_away'    => '',
		'subs_home'       => '',
		'subs_away'       => '',
	]
);

$stats = $data->stats;

if ( empty( $stats->shotsH ) && empty( $stats->shotsA ) ) {
	return '';
}

$color_home = get_post_meta( $data->home_club, '_anwpfl_main_color', true );
$color_away = get_post_meta( $data->away_club, '_anwpfl_main_color', true );

if ( empty( $color_home ) ) {
	$color_home = '#0085ba';
}

if ( empty( $color_away ) ) {
	$color_away = '#dc3545';
}

/**
 * Hook: anwpfl/tmpl-match/stats_before
 *
 * @param object $data Match data
 *
 * @since 0.7.5
 */
do_action( 'anwpfl/tmpl-match/stats_before', $data );
?>
<div class="anwp-section">
	<div class="anwp-block-header">
		<?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__match_statistics', __( 'Match Statistics', 'anwp-football-leagues' ) ) ); ?>
	</div>
	<div class="list-group-item">
		<div class="anwp-row anwp-no-gutters small">
			<div class="anwp-col-sm">
				<div class="match__club--mini p-2 d-flex align-items-center anwp-bg-light my-1">
					<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"></div>
					<div class="match__club mx-3 d-inline-block">
						<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_home_link ); ?>">
							<?php echo esc_html( $data->club_home_title ); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="anwp-col-sm">
				<div class="match__club--mini p-2 d-flex flex-row-reverse align-items-center anwp-bg-light my-1">
					<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"></div>
					<div class="match__club mx-3 d-inline-block">
						<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_away_link ); ?>">
							<?php echo esc_html( $data->club_away_title ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ( isset( $stats->shotsH ) && isset( $stats->shotsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__shots">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__shots', __( 'Shots', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->shotsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->shotsH * 2; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->shotsA * 2; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->shotsA; ?></div>
			</div>
		</div>
	<?php endif; ?>


	<?php if ( isset( $stats->shotsOnGoalsH ) && isset( $stats->shotsOnGoalsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__shotsOnGoals">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__shots_on_target', __( 'Shots on Target', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->shotsOnGoalsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->shotsOnGoalsH * 2; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->shotsOnGoalsA * 2; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->shotsOnGoalsA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->foulsH ) && isset( $stats->foulsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__fouls">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__fouls', __( 'Fouls', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->foulsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->foulsH * 2; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->foulsA * 2; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->foulsA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->cornersH ) && isset( $stats->cornersA ) ) : ?>
		<div class="list-group-item p-2 club-stats__corners">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__corners', __( 'Corners', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->cornersH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->cornersH * 4; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->cornersA * 4; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->cornersA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->offsidesH ) && isset( $stats->offsidesA ) ) : ?>
		<div class="list-group-item p-2 club-stats__offsides">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__offsides', __( 'Offsides', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->offsidesH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->offsidesH * 4; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->offsidesA * 4; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->offsidesA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->possessionH ) && isset( $stats->possessionA ) ) : ?>
		<div class="list-group-item p-2 club-stats__possession">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__ball_possession', __( 'Ball Possession', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->possessionH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo (int) $stats->possessionH; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo (int) $stats->possessionA; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->possessionA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->yellowCardsH ) && isset( $stats->yellowCardsA ) && ( '' !== $stats->yellowCardsH || '' !== $stats->yellowCardsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__yellowCards">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__yellow_cards', __( 'Yellow Cards', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->yellowCardsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo intval( $stats->yellowCardsH ) * 10; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo intval( $stats->yellowCardsA ) * 10; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->yellowCardsA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->yellow2RCardsH ) && isset( $stats->yellow2RCardsA ) && ( '' !== $stats->yellow2RCardsH || '' !== $stats->yellow2RCardsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__yellow2RCards">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__2_d_yellow_red_cards', __( '2d Yellow > Red Cards', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->yellow2RCardsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo intval( $stats->yellow2RCardsH ) * 10; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo intval( $stats->yellow2RCardsA ) * 10; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->yellow2RCardsA; ?></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( isset( $stats->redCardsH ) && isset( $stats->redCardsA ) && ( '' !== $stats->redCardsH || '' !== $stats->redCardsA ) ) : ?>
		<div class="list-group-item p-2 club-stats__redCards">
			<div class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'match__stats__red_cards', __( 'Red Cards', 'anwp-football-leagues' ) ) ); ?></div>
			<div class="anwp-row anwp-no-gutters small">
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->redCardsH; ?></div>
				<div class="anwp-col mx-1">
					<div class="progress flex-row-reverse">
						<div class="progress-bar" style="width: <?php echo intval( $stats->redCardsH ) * 10; ?>%; background-color: <?php echo esc_attr( $color_home ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col mx-1">
					<div class="progress">
						<div class="progress-bar" style="width: <?php echo intval( $stats->redCardsA ) * 10; ?>%; background-color: <?php echo esc_attr( $color_away ); ?>" role="progressbar"></div>
					</div>
				</div>
				<div class="anwp-col-auto match__stats-number h6 mx-1"><?php echo (int) $stats->redCardsA; ?></div>
			</div>
		</div>
	<?php endif; ?>

</div>
