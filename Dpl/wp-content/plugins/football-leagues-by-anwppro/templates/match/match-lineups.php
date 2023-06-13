<?php
/**
 * The Template for displaying Match >> Line Ups Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-lineups.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.6.1
 *
 * @version       0.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'home_club'       => '',
		'away_club'       => '',
		'club_home_title' => '',
		'club_away_title' => '',
		'club_home_logo'  => '',
		'club_away_logo'  => '',
		'club_home_link'  => '',
		'club_away_link'  => '',
		'season_id'       => '',
		'events'          => [],
		'line_up_home'    => '',
		'line_up_away'    => '',
		'subs_home'       => '',
		'subs_away'       => '',
		'coach_home'      => '',
		'coach_away'      => '',
		'custom_numbers'  => (object) [],
		'header'          => true,
	]
);

$home_line_up = $data->line_up_home;
$away_line_up = $data->line_up_away;

$home_subs = $data->subs_home;
$away_subs = $data->subs_away;

$events['players'] = empty( $data->events['players'] ) ? [] : $data->events['players'];

// Prepare squad
$home_squad = anwp_football_leagues()->club->tmpl_prepare_club_squad( $data->home_club, $data->season_id );
$away_squad = anwp_football_leagues()->club->tmpl_prepare_club_squad( $data->away_club, $data->season_id );

// Event icons
$event_icons = anwp_football_leagues()->data->get_event_icons();

$positions = anwp_football_leagues()->data->get_positions_l10n();

$positions_l10n = [
	'g' => anwp_football_leagues()->get_option_value( 'text_abbr_goalkeeper' ) ?: $positions['g'],
	'd' => anwp_football_leagues()->get_option_value( 'text_abbr_defender' ) ?: $positions['d'],
	'm' => anwp_football_leagues()->get_option_value( 'text_abbr_midfielder' ) ?: $positions['m'],
	'f' => anwp_football_leagues()->get_option_value( 'text_abbr_forward' ) ?: $positions['f'],
];

if ( $home_line_up || $away_line_up ) :
	/**
	 * Trigger on before rendering match lineups.
	 *
	 * @param object $data Match data
	 *
	 * @since 0.7.5
	 */
	do_action( 'anwpfl/tmpl-match/lineups_before', $data );
	?>
	<div class="anwp-section">

		<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
			<div class="anwp-block-header">
				<?php echo esc_html( AnWPFL_Text::get_value( 'match__lineups__line_ups', __( 'Line Ups', 'anwp-football-leagues' ) ) ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		* Trigger on before rendering match lineups.
		*
		* @param object $data Match data
		*
		* @since 0.7.5
		*/
		do_action( 'anwpfl/tmpl-match/lineups_after_header', $data );
		?>

		<div class="list-group-item pl-3 pr-0 pb-3 pt-1 anwp-section__lineups-inner">
			<div class="anwp-row no-gutters small">
				<div class="anwp-col-sm d-flex flex-column pr-3">
					<div class="match__club--mini p-2 my-2 d-flex align-items-center anwp-bg-light">
						<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"></div>
						<div class="match__club mx-3 d-inline-block">
							<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_home_link ); ?>">
								<?php echo esc_html( $data->club_home_title ); ?>
							</a>
						</div>
					</div>

					<?php
					/*
					|--------------------------------------------------------------------------
					| Home Club Line Ups
					|--------------------------------------------------------------------------
					*/
					$home_line_up = $home_line_up ? explode( ',', $home_line_up ) : [];

					if ( ! empty( $home_line_up ) && is_array( $home_line_up ) ) :
						foreach ( $home_line_up as $player_id ) :
							$player = anwp_football_leagues()->player->get_player( $player_id );

							if ( ! $player ) {
								continue;
							}
							?>
							<div class="match__player-wrapper d-flex align-items-center border-bottom">
								<div class="match__player-number anwp-bg-light">
									<?php
									$player_number = '';

									if ( ! empty( $data->custom_numbers->{$player_id} ) ) {
										$player_number = (int) $data->custom_numbers->{$player_id};
									} elseif ( isset( $home_squad[ $player_id ] ) && $home_squad[ $player_id ]['number'] ) {
										$player_number = (int) $home_squad[ $player_id ]['number'];
									}

									echo esc_html( $player_number );
									?>
								</div>
								<div class="match__player-flag mx-1">
									<?php
									if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) :
										foreach ( $player->nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</div>

								<?php if ( ! empty( $home_squad[ $player_id ] ) && isset( $positions_l10n[ $home_squad[ $player_id ]['position'] ] ) ) : ?>
									<div class="match__player-position mr-1 text-nowrap">
										<?php echo esc_html( $positions_l10n[ $home_squad[ $player_id ]['position'] ] ); ?>
									</div>
								<?php elseif ( ! empty( $player->position ) && isset( $positions_l10n[ $player->position ] ) ) : ?>
									<div class="match__player-position mr-1">
										<?php echo esc_html( $positions_l10n[ $player->position ] ); ?>
									</div>
								<?php endif; ?>

								<div class="match__player-name mr-auto ml-1">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
								</div>
								<?php
								if ( ! empty( $events['players'][ $player_id ] ) ) :
									foreach ( $events['players'][ $player_id ] as $evt ) :
										echo isset( $event_icons[ $evt ] ) ? $event_icons[ $evt ] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									endforeach;
								endif;
								?>
							</div>
							<?php
						endforeach;
					endif;

					/*
					|--------------------------------------------------------------------------
					| Home Club Substitutions
					|--------------------------------------------------------------------------
					*/
					$home_subs = $home_subs ? explode( ',', $home_subs ) : [];

					if ( ! empty( $home_subs ) && is_array( $home_subs ) ) :
						?>
						<div class="h6 mt-4"><?php echo esc_html( AnWPFL_Text::get_value( 'match__lineups__substitutes', __( 'Substitutes', 'anwp-football-leagues' ) ) ); ?></div>
						<?php
						foreach ( $home_subs as $player_id ) :
							$player = anwp_football_leagues()->player->get_player( $player_id );

							if ( ! $player ) {
								continue;
							}
							?>
							<div class="match__player-wrapper d-flex align-items-center border-bottom">
								<div class="match__player-number anwp-bg-light">
									<?php
									$player_number = '';

									if ( ! empty( $data->custom_numbers->{$player_id} ) ) {
										$player_number = (int) $data->custom_numbers->{$player_id};
									} elseif ( isset( $home_squad[ $player_id ] ) && $home_squad[ $player_id ]['number'] ) {
										$player_number = (int) $home_squad[ $player_id ]['number'];
									}

									echo esc_html( $player_number );
									?>
								</div>
								<div class="match__player-flag mx-1">
									<?php
									if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) :
										foreach ( $player->nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</div>

								<?php if ( ! empty( $home_squad[ $player_id ] ) && isset( $positions_l10n[ $home_squad[ $player_id ]['position'] ] ) ) : ?>
									<div class="match__player-position mr-1 text-nowrap">
										<?php echo esc_html( $positions_l10n[ $home_squad[ $player_id ]['position'] ] ); ?>
									</div>
								<?php elseif ( ! empty( $player->position ) && isset( $positions_l10n[ $player->position ] ) ) : ?>
									<div class="match__player-position mr-1">
										<?php echo esc_html( $positions_l10n[ $player->position ] ); ?>
									</div>
								<?php endif; ?>

								<div class="match__player-name mr-auto ml-1">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
								</div>
								<?php
								if ( ! empty( $events['players'][ $player_id ] ) ) :
									foreach ( $events['players'][ $player_id ] as $evt ) :
										echo isset( $event_icons[ $evt ] ) ? $event_icons[ $evt ] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									endforeach;
								endif;
								?>
							</div>
							<?php
						endforeach;
					endif;

					/*
					|--------------------------------------------------------------------------
					| Home Club Coach
					|--------------------------------------------------------------------------
					*/

					if ( ! empty( $data->coach_home ) ) :
						$coach_nationality = get_post_meta( $data->coach_home, '_anwpfl_nationality', true );
						?>
						<div class="h6 mt-auto pt-4"><?php echo esc_html( AnWPFL_Text::get_value( 'match__lineups__coach', __( 'Coach', 'anwp-football-leagues' ) ) ); ?></div>

						<div class="match__player-wrapper anwp-row anwp-no-gutters border-bottom">
							<div class="anwp-col match__player-inner">
								<span class="match__player-flag mr-1">
									<?php
									if ( ! empty( $coach_nationality ) && is_array( $coach_nationality ) ) :
										foreach ( $coach_nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</span>
								<span class="match__player-name">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->coach_home ) ); ?>"><?php echo esc_html( get_the_title( $data->coach_home ) ); ?></a>
								</span>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="anwp-col-sm mt-4 mt-sm-0 d-flex flex-column pr-3">
					<div class="match__club--mini p-2 d-flex flex-row-reverse align-items-center anwp-bg-light my-2">
						<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"></div>
						<div class="match__club mx-3 d-inline-block">
							<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_away_link ); ?>">
								<?php echo esc_html( $data->club_away_title ); ?>
							</a>
						</div>
					</div>

					<?php
					/*
					|--------------------------------------------------------------------------
					| Away Club Line Ups
					|--------------------------------------------------------------------------
					*/
					$away_line_up = $away_line_up ? explode( ',', $away_line_up ) : [];

					if ( ! empty( $away_line_up ) && is_array( $away_line_up ) ) :
						foreach ( $away_line_up as $player_id ) :
							$player = anwp_football_leagues()->player->get_player( $player_id );

							if ( ! $player ) {
								continue;
							}
							?>
							<div class="match__player-wrapper d-flex align-items-center border-bottom">
								<div class="match__player-number anwp-bg-light">
									<?php
									$player_number = '';

									if ( ! empty( $data->custom_numbers->{$player_id} ) ) {
										$player_number = (int) $data->custom_numbers->{$player_id};
									} elseif ( isset( $away_squad[ $player_id ] ) && $away_squad[ $player_id ]['number'] ) {
										$player_number = (int) $away_squad[ $player_id ]['number'];
									}

									echo esc_html( $player_number );
									?>
								</div>
								<div class="match__player-flag mx-1">
									<?php
									if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) :
										foreach ( $player->nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</div>

								<?php if ( ! empty( $away_squad[ $player_id ] ) && isset( $positions_l10n[ $away_squad[ $player_id ]['position'] ] ) ) : ?>
									<div class="match__player-position mr-1 text-nowrap">
										<?php echo esc_html( $positions_l10n[ $away_squad[ $player_id ]['position'] ] ); ?>
									</div>
								<?php elseif ( ! empty( $player->position ) && isset( $positions_l10n[ $player->position ] ) ) : ?>
									<div class="match__player-position mr-1">
										<?php echo esc_html( $positions_l10n[ $player->position ] ); ?>
									</div>
								<?php endif; ?>

								<div class="match__player-name mr-auto ml-1">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
								</div>
								<?php
								if ( ! empty( $events['players'][ $player_id ] ) ) :
									foreach ( $events['players'][ $player_id ] as $evt ) :
										echo isset( $event_icons[ $evt ] ) ? $event_icons[ $evt ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									endforeach;
								endif;
								?>
							</div>
							<?php
						endforeach;
					endif;

					/*
					|--------------------------------------------------------------------------
					| Away Club Substitutions
					|--------------------------------------------------------------------------
					*/
					$away_subs = $away_subs ? explode( ',', $away_subs ) : [];

					if ( ! empty( $away_subs ) && is_array( $away_subs ) ) :
						?>
						<div class="h6 mt-4"><?php echo esc_html( AnWPFL_Text::get_value( 'match__lineups__substitutes', __( 'Substitutes', 'anwp-football-leagues' ) ) ); ?></div>
						<?php
						foreach ( $away_subs as $player_id ) :
							$player = anwp_football_leagues()->player->get_player( $player_id );

							if ( ! $player ) {
								continue;
							}
							?>
							<div class="match__player-wrapper d-flex align-items-center border-bottom">
								<div class="match__player-number anwp-bg-light">
									<?php
									$player_number = '';

									if ( ! empty( $data->custom_numbers->{$player_id} ) ) {
										$player_number = (int) $data->custom_numbers->{$player_id};
									} elseif ( isset( $away_squad[ $player_id ] ) && $away_squad[ $player_id ]['number'] ) {
										$player_number = (int) $away_squad[ $player_id ]['number'];
									}

									echo esc_html( $player_number );
									?>
								</div>
								<div class="match__player-flag mx-1">
									<?php
									if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) :
										foreach ( $player->nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</div>

								<?php if ( ! empty( $away_squad[ $player_id ] ) && isset( $positions_l10n[ $away_squad[ $player_id ]['position'] ] ) ) : ?>
									<div class="match__player-position mr-1 text-nowrap">
										<?php echo esc_html( $positions_l10n[ $away_squad[ $player_id ]['position'] ] ); ?>
									</div>
								<?php elseif ( ! empty( $player->position ) && isset( $positions_l10n[ $player->position ] ) ) : ?>
									<div class="match__player-position mr-1">
										<?php echo esc_html( $positions_l10n[ $player->position ] ); ?>
									</div>
								<?php endif; ?>

								<div class="match__player-name mr-auto ml-1">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
								</div>
								<?php
								if ( ! empty( $events['players'][ $player_id ] ) ) :
									foreach ( $events['players'][ $player_id ] as $evt ) :
										echo isset( $event_icons[ $evt ] ) ? $event_icons[ $evt ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									endforeach;
								endif;
								?>
							</div>
							<?php
						endforeach;
					endif;

					/*
					|--------------------------------------------------------------------------
					| Away Club Coach
					|--------------------------------------------------------------------------
					*/
					if ( ! empty( $data->coach_away ) ) :
						$coach_nationality = get_post_meta( $data->coach_away, '_anwpfl_nationality', true );
						?>
						<div class="h6 pt-4 mt-auto"><?php echo esc_html( AnWPFL_Text::get_value( 'match__lineups__coach', __( 'Coach', 'anwp-football-leagues' ) ) ); ?></div>

						<div class="match__player-wrapper anwp-row anwp-no-gutters border-bottom">
							<div class="anwp-col match__player-inner">
								<span class="match__player-flag mr-1">
									<?php
									if ( ! empty( $coach_nationality ) && is_array( $coach_nationality ) ) :
										foreach ( $coach_nationality as $country_code ) :
											?>
											<span class="options__flag f16" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
											<?php
										endforeach;
									endif;
									?>
								</span>
								<span class="match__player-name">
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->coach_away ) ); ?>"><?php echo esc_html( get_the_title( $data->coach_away ) ); ?></a>
								</span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
