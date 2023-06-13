<?php
/**
 * The Template for displaying Match >> Missing Players Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-missing.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.4
 *
 * @version       0.12.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'home_club'       => '',
		'match_id'        => '',
		'away_club'       => '',
		'club_home_title' => '',
		'club_away_title' => '',
		'club_home_logo'  => '',
		'club_away_logo'  => '',
		'club_home_link'  => '',
		'club_away_link'  => '',
		'season_id'       => '',
		'header'          => true,
	]
);

$missing_players = json_decode( get_post_meta( $data->match_id, '_anwpfl_missing_players', true ) );

if ( empty( $missing_players ) || ! is_array( $missing_players ) ) {
	return;
}

// Prepare squad
$home_squad = anwp_football_leagues()->club->tmpl_prepare_club_squad( $data->home_club, $data->season_id );
$away_squad = anwp_football_leagues()->club->tmpl_prepare_club_squad( $data->away_club, $data->season_id );

$positions = anwp_football_leagues()->data->get_positions_l10n();

$positions_l10n = [
	'g' => anwp_football_leagues()->get_option_value( 'text_abbr_goalkeeper' ) ?: $positions['g'],
	'd' => anwp_football_leagues()->get_option_value( 'text_abbr_defender' ) ?: $positions['d'],
	'm' => anwp_football_leagues()->get_option_value( 'text_abbr_midfielder' ) ?: $positions['m'],
	'f' => anwp_football_leagues()->get_option_value( 'text_abbr_forward' ) ?: $positions['f'],
];

?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__missing_players', __( 'Missing Players', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="list-group-item pl-3 pr-0 pb-3 pt-1 anwp-section__missing-players">
		<div class="anwp-row anwp-no-gutters small">
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
				| Home Club Missing
				|--------------------------------------------------------------------------
				*/
				foreach ( $missing_players as $missing_player ) :

					if ( absint( $missing_player->club ) !== absint( $data->home_club ) ) {
						continue;
					}

					$player_id = absint( $missing_player->player );
					$player    = anwp_football_leagues()->player->get_player( $player_id );

					if ( ! $player ) {
						continue;
					}
					?>
					<div class="match__player-wrapper d-flex align-items-center border-bottom">
						<div class="match__player-number anwp-bg-light">
							<?php
							$player_number = '';

							if ( isset( $home_squad[ $player_id ] ) && $home_squad[ $player_id ]['number'] ) {
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
						<?php endif; ?>

						<div class="match__player-name mr-2 ml-1">
							<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
						</div>

						<div>
							-
							<?php if ( 'suspended' === $missing_player->reason ) : ?>
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__suspended', __( 'Suspended', 'anwp-football-leagues' ) ) ); ?>
								<?php echo $missing_player->comment ? ' - ' : ''; ?>
							<?php elseif ( 'injured' === $missing_player->reason ) : ?>
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__injured', __( 'Injured', 'anwp-football-leagues' ) ) ); ?>
								<?php echo $missing_player->comment ? ' - ' : ''; ?>
							<?php endif; ?>
							<?php echo esc_html( $missing_player->comment ); ?>
						</div>
					</div>
				<?php endforeach; ?>
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
				| Away Club Missing players
				|--------------------------------------------------------------------------
				*/
				foreach ( $missing_players as $missing_player ) :

					if ( absint( $missing_player->club ) !== absint( $data->away_club ) ) {
						continue;
					}

					$player_id = absint( $missing_player->player );
					$player    = anwp_football_leagues()->player->get_player( $player_id );

					if ( ! $player ) {
						continue;
					}
					?>
					<div class="match__player-wrapper d-flex align-items-center border-bottom">
						<div class="match__player-number anwp-bg-light">
							<?php
							$player_number = '';

							if ( isset( $away_squad[ $player_id ] ) && $away_squad[ $player_id ]['number'] ) {
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
						<?php endif; ?>

						<div class="match__player-name mr-2 ml-1">
							<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
						</div>

						<div>
							-
							<?php if ( 'suspended' === $missing_player->reason ) : ?>
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__suspended', __( 'Suspended', 'anwp-football-leagues' ) ) ); ?>
								<?php echo $missing_player->comment ? ' - ' : ''; ?>
							<?php elseif ( 'injured' === $missing_player->reason ) : ?>
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__injured', __( 'Injured', 'anwp-football-leagues' ) ) ); ?>
								<?php echo $missing_player->comment ? ' - ' : ''; ?>
							<?php endif; ?>
							<?php echo esc_html( $missing_player->comment ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
