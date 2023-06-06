<?php
/**
 * The Template for displaying Match >> Penalty Shootout.
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-penalty_shootout.php.
 *
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.6.5
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'home_club' => '',
		'away_club' => '',
		'events'    => [],
		'header'    => true,
	]
);

if ( empty( $data->events['penalty_shootout'] ) ) {
	return '';
}

static $goal_t1 = 0;
static $goal_t2 = 0;
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__penalty_shootout__penalty_shootout', __( 'Penalty Shootout', 'anwp-football-leagues' ) ) ); ?>
		</div>
		<?php
	endif;

	foreach ( $data->events['penalty_shootout'] as $e ) :

		// Round scores
		if ( $e->club === (int) $data->home_club && 'yes' === $e->scored ) {
			$goal_t1 ++;
		} elseif ( $e->club === (int) $data->away_club && 'yes' === $e->scored ) {
			$goal_t2 ++;
		}
		?>
		<div class="list-group-item match__list-item py-2 px-3">
			<div class="anwp-row align-items-center no-gutters">
				<div class="anwp-col-sm d-flex flex-sm-row-reverse flex-row">

					<?php if ( $e->club === (int) $data->home_club ) : ?>
						<div class="match__event-icon mx-sm-2">
							<svg class="icon__ball">
								<use xlink:href="#<?php echo esc_attr( 'yes' === $e->scored ? 'icon-ball_penalty' : 'icon-ball_canceled' ); ?>"></use>
							</svg>
						</div>

						<div class="match__event-minute d-sm-none mx-2">
							<?php echo esc_html( $goal_t1 . '-' . $goal_t2 ); ?>
						</div>

						<div class="match__event-content text-left text-sm-right">
							<div class="match__event-type">
								<?php
								if ( 'yes' === $e->scored ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__penalty_shootout__scored', _x( 'Scored', 'penalty shootout', 'anwp-football-leagues' ) ) );
								} else {
									echo esc_html( AnWPFL_Text::get_value( 'match__penalty_shootout__missed', _x( 'Missed', 'penalty shootout', 'anwp-football-leagues' ) ) );
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
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="anwp-col-sm-auto d-none d-sm-block">
					<div class="match__event-minute text-nowrap">
						<?php echo esc_html( $goal_t1 . '-' . $goal_t2 ); ?>
					</div>
				</div>
				<div class="anwp-col-sm d-flex flex-sm-row flex-row-reverse">
					<?php if ( $e->club === (int) $data->away_club ) : ?>
						<div class="match__event-icon mx-sm-2">
							<svg class="icon__ball">
								<use xlink:href="#<?php echo esc_attr( 'yes' === $e->scored ? 'icon-ball_penalty' : 'icon-ball_canceled' ); ?>"></use>
							</svg>
						</div>

						<div class="match__event-minute d-sm-none mx-2">
							<?php echo esc_html( $goal_t1 . '-' . $goal_t2 ); ?>
						</div>

						<div class="match__event-content text-sm-left text-right">
							<div class="match__event-type">
								<?php
								if ( 'yes' === $e->scored ) {
									echo esc_html( AnWPFL_Text::get_value( 'match__penalty_shootout__scored', _x( 'Scored', 'penalty shootout', 'anwp-football-leagues' ) ) );
								} else {
									echo esc_html( AnWPFL_Text::get_value( 'match__penalty_shootout__missed', _x( 'Missed', 'penalty shootout', 'anwp-football-leagues' ) ) );
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
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
