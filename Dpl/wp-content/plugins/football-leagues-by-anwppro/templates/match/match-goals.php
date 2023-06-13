<?php
/**
 * The Template for displaying Match >> Goals Section.
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-goals.php.
 *
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.6.1
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
		'club_home_logo'  => '',
		'club_away_logo'  => '',
		'match_id'        => '',
		'finished'        => '',
		'home_goals'      => '',
		'away_goals'      => '',
		'match_week'      => '',
		'stadium_id'      => '',
		'competition_id'  => '',
		'main_stage_id'   => '',
		'stage_title'     => '',
		'events'          => [],
		'header'          => true,
	]
);

if ( empty( $data->events['goals'] ) ) {
	return '';
}

/**
 * Hook: anwpfl/tmpl-match/goals_before
 *
 * @param object $data Match data
 *
 * @since 0.7.5
 */
do_action( 'anwpfl/tmpl-match/goals_before', $data );
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__goals__goals', __( 'Goals', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php foreach ( $data->events['goals'] as $e ) : ?>
		<div class="list-group-item match__list-item py-2 px-3">
			<div class="anwp-row align-items-center no-gutters">
				<div class="anwp-col-sm d-flex flex-sm-row-reverse flex-row">

					<?php if ( $e->club === (int) $data->home_club ) : ?>
						<div class="match__event-icon mx-sm-2">
							<svg class="icon__ball <?php echo esc_attr( 'yes' === $e->ownGoal ? 'icon__ball--own' : '' ); ?>">
								<use xlink:href="#<?php echo esc_attr( 'yes' === $e->fromPenalty ? 'icon-ball_penalty' : 'icon-ball' ); ?>"></use>
							</svg>
						</div>

						<div class="match__event-minute d-flex justify-content-center align-items-end d-sm-none mx-2">
							<span class="d-inline-block <?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>"><?php echo (int) $e->minute; ?>'</span>
							<?php if ( (int) $e->minuteAdd ) : ?>
								<span class="d-inline-block match__event-minute-add pb-1">+<?php echo (int) $e->minuteAdd; ?></span>
							<?php endif; ?>
						</div>

						<div class="match__event-content text-left text-sm-right">
							<div class="match__event-type">
								<?php
								if ( 'yes' === $e->ownGoal ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal_own', _x( 'Goal (own)', 'match event', 'anwp-football-leagues' ) ) );
								} elseif ( 'yes' === $e->fromPenalty ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal_penalty', _x( 'Goal (from penalty)', 'match event', 'anwp-football-leagues' ) ) );
								} else {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal', _x( 'Goal', 'match event', 'anwp-football-leagues' ) ) );
								}
								?>
							</div>
							<div class="match__event-player">
								<?php
								if ( ! empty( $e->player ) ) :
									$player = anwp_football_leagues()->player->get_player( $e->player );
									?>
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>">
										<?php echo esc_html( $player->name_short ); ?>
									</a>
									<?php
								endif;

								if ( ! empty( $e->assistant ) ) :
									$assistant = anwp_football_leagues()->player->get_player( $e->assistant );
									?>
									<span class="mx-1 text-nowrap">
										(<span class="text-lowercase text-muted small"><?php echo esc_html( AnWPFL_Text::get_value( 'match__goals__assistant', __( 'Assistant', 'anwp-football-leagues' ) ) ); ?></span>:
										<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $assistant->link ); ?>"><?php echo esc_html( $assistant->name_short ); ?></a>)
									</span>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="anwp-col-sm-auto d-none d-sm-block">
					<div class="match__event-minute d-flex justify-content-center align-items-end">
						<span class="d-inline-block <?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>"><?php echo (int) $e->minute; ?>'</span>
						<?php if ( (int) $e->minuteAdd ) : ?>
							<span class="d-inline-block match__event-minute-add pb-1">+<?php echo (int) $e->minuteAdd; ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="anwp-col-sm d-flex flex-sm-row flex-row-reverse">
					<?php if ( $e->club === (int) $data->away_club ) : ?>
						<div class="match__event-icon mx-sm-2">
							<svg class="icon__ball <?php echo esc_attr( 'yes' === $e->ownGoal ? 'icon__ball--own' : '' ); ?>">
								<use xlink:href="#<?php echo esc_attr( 'yes' === $e->fromPenalty ? 'icon-ball_penalty' : 'icon-ball' ); ?>"></use>
							</svg>
						</div>

						<div class="match__event-minute d-flex justify-content-center align-items-end d-sm-none mx-2">
							<span class="d-inline-block <?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>"><?php echo (int) $e->minute; ?>'</span>
							<?php if ( (int) $e->minuteAdd ) : ?>
								<span class="d-inline-block match__event-minute-add pb-1">+<?php echo (int) $e->minuteAdd; ?></span>
							<?php endif; ?>
						</div>

						<div class="match__event-content text-sm-left text-right">
							<div class="match__event-type">
								<?php
								if ( 'yes' === $e->ownGoal ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal_own', _x( 'Goal (own)', 'match event', 'anwp-football-leagues' ) ) );
								} elseif ( 'yes' === $e->fromPenalty ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal_penalty', _x( 'Goal (from penalty)', 'match event', 'anwp-football-leagues' ) ) );
								} else {
									echo esc_html( AnWPFL_Text::get_value( 'match__goals__goal', _x( 'Goal', 'match event', 'anwp-football-leagues' ) ) );
								}
								?>
							</div>
							<div class="match__event-player">
								<?php
								if ( $e->player ) :
									$player = anwp_football_leagues()->player->get_player( $e->player );
									?>
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>">
										<?php echo esc_html( $player->name_short ); ?>
									</a>
									<?php
								endif;

								if ( ! empty( $e->assistant ) ) :
									$assistant = anwp_football_leagues()->player->get_player( $e->assistant );
									?>
									<span class="mx-1 text-nowrap">
										(<span class="text-lowercase text-muted small"><?php echo esc_html( AnWPFL_Text::get_value( 'match__goals__assistant', __( 'Assistant', 'anwp-football-leagues' ) ) ); ?>:</span>
										<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $assistant->link ); ?>"><?php echo esc_html( $assistant->name_short ); ?></a>)
									</span>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
