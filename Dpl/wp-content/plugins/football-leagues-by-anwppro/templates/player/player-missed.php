<?php
/**
 * The Template for displaying Player >> Missed Games Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-missed.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.4
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'player_id'         => '',
		'current_season_id' => '',
		'position_code'     => '',
		'series_map'        => [],
		'card_icons'        => [],
		'header'            => true,
	]
);

$missed_games = anwp_football_leagues()->match->get_player_missed_games_by_season( $data->player_id, $data->current_season_id );

if ( empty( $missed_games ) ) {
	return;
}
?>
<div class="player__missed player-section anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header__wrapper d-flex justify-content-between">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'player__missed__missed_matches', __( 'Missed Matches', 'anwp-football-leagues' ) ) ); ?></div>
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
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__missed__reason', __( 'Reason', 'anwp-football-leagues' ) ) ); ?></th>
			</tr>
			</thead>

			<tbody>

			<?php foreach ( $missed_games as $ii => $competition ) : ?>
				<tr class="bg-light">
					<td colspan="4"><?php echo esc_html( $competition['title'] ); ?></td>
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
							<td>
								<?php if ( 'suspended' === $match->reason ) : ?>
									<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__suspended', __( 'Suspended', 'anwp-football-leagues' ) ) ); ?>
									<?php echo $match->comment ? ' - ' : ''; ?>
								<?php elseif ( 'injured' === $match->reason ) : ?>
									<?php echo esc_html( AnWPFL_Text::get_value( 'match__missing__injured', __( 'Injured', 'anwp-football-leagues' ) ) ); ?>
									<?php echo $match->comment ? ' - ' : ''; ?>
								<?php endif; ?>
								<?php echo esc_html( $match->comment ); ?>
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
