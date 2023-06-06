<?php
/**
 * The Template for displaying Match >> Substitutes Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-substitutes.php.
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
		'home_club' => '',
		'away_club' => '',
		'events'    => [],
		'header'    => true,
	]
);

if ( empty( $data->events['subs'] ) ) {
	return '';
}

/**
 * Hook: anwpfl/tmpl-match/substitutes_before
 *
 * @param object $data Match data
 *
 * @since 0.7.5
 */
do_action( 'anwpfl/tmpl-match/substitutes_before', $data );
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__substitutes__substitutes', __( 'Substitutes', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php foreach ( $data->events['subs'] as $e ) : ?>
		<div class="list-group-item match__list-item py-2 px-3">
			<div class="anwp-row align-items-center no-gutters">

				<?php if ( $e->club === (int) $data->home_club ) : ?>
					<div class="anwp-col-sm d-flex flex-sm-row-reverse flex-row">

						<div class="match__event-icon--subs-wrapper mx-sm-2">
							<div class="match__event-icon mt-1 mb-2">
								<svg class="icon__subs-out">
									<use xlink:href="#icon-arrow-o-down"></use>
								</svg>
							</div>
							<div class="match__event-icon">
								<svg class="icon__subs-in">
									<use xlink:href="#icon-arrow-o-up"></use>
								</svg>
							</div>
						</div>

						<div class="match__event-minute d-sm-none mx-2 d-flex align-items-center justify-content-center">
							<div class="<?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>">
								<?php echo (int) $e->minute; ?>'
								<?php if ( (int) $e->minuteAdd ) : ?>
									<span class="d-block match__event-minute-add">+<?php echo (int) $e->minuteAdd; ?></span>
								<?php endif; ?>
							</div>
						</div>

						<div class="match__event-content text-left text-sm-right">
							<div class="match__event-type">
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__substitutes__out', _x( 'Out', 'substitute event', 'anwp-football-leagues' ) ) ); ?>
							</div>
							<div class="match__event-player">
								<?php
								if ( $e->playerOut ) :
									$player = anwp_football_leagues()->player->get_player( $e->playerOut );
									?>
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>">
										<?php echo esc_html( $player->name_short ); ?>
									</a>
								<?php endif; ?>
							</div>
							<div class="match__event-type">
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__substitutes__in', _x( 'In', 'substitute event', 'anwp-football-leagues' ) ) ); ?>
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

					</div>
				<?php else : ?>
					<div class="anwp-col-sm"></div>
				<?php endif; ?>

				<div class="anwp-col-sm-auto d-none d-sm-block">
					<div class="match__event-minute d-flex justify-content-center align-items-end">
						<span class="d-inline-block <?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>"><?php echo (int) $e->minute; ?>'</span>
						<?php if ( (int) $e->minuteAdd ) : ?>
							<span class="d-inline-block match__event-minute-add pb-1">+<?php echo (int) $e->minuteAdd; ?></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $e->club === (int) $data->away_club ) : ?>
					<div class="anwp-col-sm d-flex flex-sm-row flex-row-reverse">

						<div class="match__event-icon--subs-wrapper mx-sm-2">
							<div class="match__event-icon mt-1 mb-2">
								<svg class="icon__subs-out">
									<use xlink:href="#icon-arrow-o-down"></use>
								</svg>
							</div>
							<div class="match__event-icon">
								<svg class="icon__subs-in">
									<use xlink:href="#icon-arrow-o-up"></use>
								</svg>
							</div>
						</div>

						<div class="match__event-minute d-sm-none mx-2 d-flex align-items-center justify-content-center">
							<div class="<?php echo esc_attr( intval( $e->minute ) ? '' : 'anwp-hidden' ); ?>">
								<?php echo (int) $e->minute; ?>'
								<?php if ( (int) $e->minuteAdd ) : ?>
									<span class="d-block match__event-minute-add">+<?php echo (int) $e->minuteAdd; ?></span>
								<?php endif; ?>
							</div>
						</div>

						<div class="match__event-content text-sm-left text-right">
							<div class="match__event-type">
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__substitutes__out', _x( 'Out', 'substitute event', 'anwp-football-leagues' ) ) ); ?>
							</div>
							<div class="match__event-player">
								<?php
								if ( $e->playerOut ) :
									$player = anwp_football_leagues()->player->get_player( $e->playerOut );
									?>
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>">
										<?php echo esc_html( $player->name_short ); ?>
									</a>
								<?php endif; ?>
							</div>
							<div class="match__event-type">
								<?php echo esc_html( AnWPFL_Text::get_value( 'match__substitutes__in', _x( 'In', 'substitute event', 'anwp-football-leagues' ) ) ); ?>
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
					</div>
				<?php else : ?>
					<div class="anwp-col-sm"></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
