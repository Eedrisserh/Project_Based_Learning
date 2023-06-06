<?php
/**
 * The Template for displaying Player >> Stats Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-stats.php.
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

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'player_id'           => '',
		'current_season_id'   => '',
		'competition_matches' => '',
		'card_icons'          => '',
		'position_code'       => '',
		'header'              => true,
	]
);
?>
<div class="player__stats player-section anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header__wrapper d-flex justify-content-between">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__stats_totals', __( 'Stats Totals', 'anwp-football-leagues' ) ) ); ?></div>
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
				<th><?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__competition', __( 'Competition', 'anwp-football-leagues' ) ) ); ?></th>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__played_matches', __( 'Played Matches', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="anwp-icon--s20 anwp-icon--trans">
						<use xlink:href="#icon-field"></use>
					</svg>
				</th>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__started', __( 'Started', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="anwp-icon--s20 anwp-icon--trans">
						<use xlink:href="#icon-field-shirt"></use>
					</svg>
				</th>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__substituted_in', __( 'Substituted In', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="anwp-icon--s20 anwp-icon--trans">
						<use xlink:href="#icon-field-shirt-in"></use>
					</svg>
				<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__minutes', __( 'Minutes', 'anwp-football-leagues' ) ) ); ?>">
					<svg class="anwp-icon--s20 anwp-icon--gray-900">
						<use xlink:href="#icon-watch"></use>
					</svg>
				</th>
				<th class="anwp-text-center pb-1"><?php echo $data->card_icons['y']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
				<th class="anwp-text-center pb-1"><?php echo $data->card_icons['yr']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
				<th class="anwp-text-center pb-1"><?php echo $data->card_icons['r']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>

				<?php if ( 'g' === $data->position_code ) : ?>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__goals_conceded', __( 'Goals Conceded', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball icon__ball--conceded">
							<use xlink:href="#icon-ball"></use>
						</svg>
					</th>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__clean_sheets', __( 'Clean Sheets', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball">
							<use xlink:href="#icon-ball_canceled"></use>
						</svg>
					</th>
				<?php else : ?>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__goals_from_penalty', __( 'Goals (from penalty)', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball anwp-icon--stats-goal">
							<use xlink:href="#icon-ball"></use>
						</svg>
					</th>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__assists', __( 'Assists', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball anwp-semi-opacity">
							<use xlink:href="#icon-ball"></use>
						</svg>
					</th>
					<th class="anwp-text-center" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__own_goals', __( 'Own Goals', 'anwp-football-leagues' ) ) ); ?>">
						<svg class="icon__ball icon__ball--own">
							<use xlink:href="#icon-ball"></use>
						</svg>
					</th>
				<?php endif; ?>
			</tr>
			</thead>

			<tbody>
			<?php foreach ( $data->competition_matches as $competition ) : ?>
				<tr>
					<td class="text-nowrap"><?php echo esc_html( $competition['title'] ); ?></td>
					<td class="anwp-text-center"><?php echo (int) ( $competition['totals']['started'] + $competition['totals']['sub_in'] ); ?></td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['started']; ?></td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['sub_in']; ?></td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['minutes']; ?>′</td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['card_y']; ?></td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['card_yr']; ?></td>
					<td class="anwp-text-center"><?php echo (int) $competition['totals']['card_r']; ?></td>
					<?php if ( 'g' === $data->position_code ) : ?>
						<td class="anwp-text-center"><?php echo (int) $competition['totals']['goals_conceded']; ?></td>
						<td class="anwp-text-center"><?php echo (int) $competition['totals']['clean_sheets']; ?></td>
					<?php else : ?>
						<td class="anwp-text-center"><?php echo (int) $competition['totals']['goals']; ?> (<?php echo (int) $competition['totals']['goals_penalty']; ?>)</td>
						<td class="anwp-text-center"><?php echo (int) $competition['totals']['assist']; ?></td>
						<td class="anwp-text-center"><?php echo (int) $competition['totals']['goals_own']; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>

			<?php
			if ( count( $data->competition_matches ) > 1 ) :
				/*
				|--------------------------------------------------------------------
				| Prepare and calculate totals
				|--------------------------------------------------------------------
				*/
				$stat_totals = [
					'started'        => 0,
					'sub_in'         => 0,
					'minutes'        => 0,
					'card_y'         => 0,
					'card_yr'        => 0,
					'card_r'         => 0,
					'goals_conceded' => 0,
					'clean_sheets'   => 0,
					'goals'          => 0,
					'goals_penalty'  => 0,
					'assist'         => 0,
					'goals_own'      => 0,
				];

				foreach ( $data->competition_matches as $t_competition ) {
					$stat_totals['started']        += $t_competition['totals']['started'];
					$stat_totals['sub_in']         += $t_competition['totals']['sub_in'];
					$stat_totals['minutes']        += $t_competition['totals']['minutes'];
					$stat_totals['card_y']         += $t_competition['totals']['card_y'];
					$stat_totals['card_yr']        += $t_competition['totals']['card_yr'];
					$stat_totals['card_r']         += $t_competition['totals']['card_r'];
					$stat_totals['goals_conceded'] += $t_competition['totals']['goals_conceded'];
					$stat_totals['clean_sheets']   += $t_competition['totals']['clean_sheets'];
					$stat_totals['goals']          += $t_competition['totals']['goals'];
					$stat_totals['goals_penalty']  += $t_competition['totals']['goals_penalty'];
					$stat_totals['assist']         += $t_competition['totals']['assist'];
					$stat_totals['goals_own']      += $t_competition['totals']['goals_own'];
				}

				?>
				<tfoot>
					<tr>
						<th class="text-nowrap"><b><?php echo esc_html( AnWPFL_Text::get_value( 'player__stats__totals', __( 'Totals', 'anwp-football-leagues' ) ) ); ?>:</b></th>
						<th class="anwp-text-center"><?php echo (int) ( $stat_totals['started'] + $stat_totals['sub_in'] ); ?></th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['started']; ?></th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['sub_in']; ?></th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['minutes']; ?>′</th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['card_y']; ?></th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['card_yr']; ?></th>
						<th class="anwp-text-center"><?php echo (int) $stat_totals['card_r']; ?></th>
						<?php if ( 'g' === $data->position_code ) : ?>
							<th class="anwp-text-center"><?php echo (int) $stat_totals['goals_conceded']; ?></th>
							<th class="anwp-text-center"><?php echo (int) $stat_totals['clean_sheets']; ?></th>
						<?php else : ?>
							<th class="anwp-text-center"><?php echo (int) $stat_totals['goals']; ?> (<?php echo (int) $stat_totals['goals_penalty']; ?>)</th>
							<th class="anwp-text-center"><?php echo (int) $stat_totals['assist']; ?></th>
							<th class="anwp-text-center"><?php echo (int) $stat_totals['goals_own']; ?></th>
						<?php endif; ?>
					</tr>
				</tfoot>
			<?php endif; ?>
		</table>
	</div>
</div>
