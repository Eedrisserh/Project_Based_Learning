<?php
/**
 * The Template for displaying Player >> Matches Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-matches.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.3
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'player_id'           => '',
		'current_season_id'   => '',
		'competition_matches' => [],
		'position_code'       => '',
		'series_map'          => [],
		'card_icons'          => [],
		'header'              => true,
	]
);

if ( ! $data->header && empty( $data->competition_matches ) ) {
	return;
}

$col_span = 'g' === $data->position_code ? 8 : 9;
?>
<div class="player__matches player-section anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header__wrapper d-flex justify-content-between">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__latest_matches', __( 'Latest Matches', 'anwp-football-leagues' ) ) ); ?></div>
			<?php
			$dropdown_filter = [
				'context' => 'player',
				'id'      => $data->player_id,
			];

			anwp_football_leagues()->helper->season_dropdown( $data->current_season_id, true, '', $dropdown_filter );
			?>
		</div>
	<?php endif; ?>

	<div class="anwp-scroll-responsive anwp-scroll-responsive--scrollbar-top">
		<table class="table table-sm table-bordered">
			<thead>
			<tr>
				<th style="width: 5%;"><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__date', __( 'Date', 'anwp-football-leagues' ) ) ); ?></th>
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__for', __( 'For', 'anwp-football-leagues' ) ) ); ?></th>
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__against', __( 'Against', 'anwp-football-leagues' ) ) ); ?></th>
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__home_away', __( 'Home /Away', 'anwp-football-leagues' ) ) ); ?></th>
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__result', __( 'Result', 'anwp-football-leagues' ) ) ); ?></th>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__minutes', __( 'Minutes', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="anwp-icon--s20 anwp-icon--gray-900"><use xlink:href="#icon-watch"></use></svg>
				</th>
				<?php if ( 'g' === $data->position_code ) : ?>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__goals_conceded', __( 'Goals Conceded', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball icon__ball--conceded"><use xlink:href="#icon-ball"></use></svg>
					</th>
				<?php else : ?>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__goals', __( 'Goals', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball anwp-icon--stats-goal"><use xlink:href="#icon-ball"></use></svg>
					</th>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__assists', __( 'Assists', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball anwp-semi-opacity"><use xlink:href="#icon-ball"></use></svg>
					</th>
				<?php endif; ?>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__matches__cards', __( 'Cards', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="icon__card"><use xlink:href="#icon-card_yr"></use></svg>
				</th>
			</tr>
			</thead>

			<tbody>

			<?php foreach ( $data->competition_matches as $ii => $competition ) : ?>
				<tr class="bg-light">
					<td colspan="<?php echo absint( $col_span ); ?>"><?php echo esc_html( $competition['title'] ); ?></td>
				</tr>
				<?php
				if ( ! empty( $competition['matches'] ) && is_array( $competition['matches'] ) ) :
					foreach ( $competition['matches'] as $match ) :

						$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $match->club_id );
						$match_link = get_permalink( $match->match_id );

						// Opponent
						$opponent_id    = $match->club_id === $match->home_club ? $match->away_club : $match->home_club;
						$opponent_title = 'full' === AnWPFL_Options::get_value( 'player_opposite_club_name' ) ? anwp_football_leagues()->club->get_club_title_by_id( $opponent_id ) : anwp_football_leagues()->club->get_club_abbr_by_id( $opponent_id );
						$opponent_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $opponent_id );

						$home_away = $match->club_id === $match->home_club ? esc_html( AnWPFL_Text::get_value( 'player__matches__home', __( 'Home', 'anwp-football-leagues' ) ) ) : esc_html( AnWPFL_Text::get_value( 'player__matches__away', __( 'Away', 'anwp-football-leagues' ) ) );

						$result_class = 'anwp-bg-success';
						$result_code  = 'w';

						if ( $match->home_goals === $match->away_goals ) {
							$result_class = 'anwp-bg-warning';
							$result_code  = 'd';
						} elseif ( ( $match->club_id === $match->home_club && $match->home_goals < $match->away_goals ) || ( $match->club_id === $match->away_club && $match->home_goals > $match->away_goals ) ) {
							$result_class = 'anwp-bg-danger';
							$result_code  = 'l';
						}

						// Card Type
						$card_type = intval( $match->card_r ) ? 'r' : ( intval( $match->card_yr ) ? 'yr' : ( intval( $match->card_y ) ? 'y' : '' ) );
						?>
						<tr>
							<td>
								<?php if ( '0000-00-00 00:00:00' !== $match->kickoff ) : ?>
									<a class="anwp-link anwp-link-without-effects text-nowrap" href="<?php echo esc_url( $match_link ); ?>">
										<?php echo esc_html( date_i18n( anwp_football_leagues()->get_option_value( 'custom_match_date_format' ) ?: 'j M Y', strtotime( $match->kickoff ) ) ); ?>
									</a>
								<?php endif; ?>
							</td>
							<td class="anwp-text-center">
								<?php if ( $club_logo ) : ?>
									<div class="club-logo__cover club-logo__cover--mini align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></div>
								<?php else : ?>
									<?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $match->club_id ) ); ?>
								<?php endif; ?>
							</td>
							<td class="text-nowrap">
								<?php if ( $opponent_logo ) : ?>
									<div class="club-logo__cover club-logo__cover--mini mr-1 align-middle" style="background-image: url('<?php echo esc_url( $opponent_logo ); ?>')"></div>
								<?php endif; ?>
								<?php echo esc_html( $opponent_title ); ?>
							</td>
							<td><?php echo esc_html( $home_away ); ?></td>
							<td class="text-nowrap">
								<span class="text-white <?php echo esc_attr( $result_class ); ?> d-inline-block w-50 anwp-text-center mr-1"><?php echo esc_html( mb_strtoupper( $data->series_map[ $result_code ] ) ); ?></span>
								<span class="mr-1"><?php echo (int) $match->home_goals; ?>:<?php echo (int) $match->away_goals; ?></span>
								<span>&nbsp;</span>
							</td>
							<td class="anwp-text-center">
								<?php
								$minutes = $match->time_out - $match->time_in;

								// Fix minutes after half time substitution (1 min correction)
								// @since v0.6.5 (2018-08-17)
								if ( 46 === intval( $match->time_out ) ) {
									$minutes = $match->time_out - $match->time_in - 1;
								} elseif ( 46 === intval( $match->time_in ) ) {
									$minutes = $match->time_out - $match->time_in + 1;
								}

								echo intval( $minutes ) . '′';
								?>
							</td>
							<?php if ( 'g' === $data->position_code ) : ?>
								<td class="anwp-text-center"><?php echo (int) $match->goals_conceded; ?></td>
							<?php else : ?>
								<td class="anwp-text-center"><?php echo (int) $match->goals; ?></td>
								<td class="anwp-text-center"><?php echo (int) $match->assist; ?></td>
							<?php endif; ?>
							<td class="anwp-text-center">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo 'r' === $card_type && intval( $match->card_y ) ? $data->card_icons['y'] : '';

								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo $card_type ? $data->card_icons[ $card_type ] : '';
								?>
							</td>
						</tr>
						<?php
					endforeach;
				endif;
			endforeach;
			?>
			</tbody>
		</table>
	</div>
</div>
