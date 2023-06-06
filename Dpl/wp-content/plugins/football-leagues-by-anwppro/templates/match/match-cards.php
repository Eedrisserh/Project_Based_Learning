<?php
/**
 * The Template for displaying Match >> Cards Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-cards.php.
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
		'home_club'  => '',
		'away_club'  => '',
		'home_goals' => '',
		'away_goals' => '',
		'events'     => [],
		'header'     => true,
	]
);

if ( empty( $data->events['cards'] ) ) {
	return '';
}

$card_options = anwp_football_leagues()->data->cards;

/**
 * Hook: anwpfl/tmpl-match/cards_before
 *
 * @param object $data Match data
 *
 * @since 0.7.5
 * @version 0.8.0
 */
do_action( 'anwpfl/tmpl-match/cards_before', $data );
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__cards__cards', __( 'Cards', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php foreach ( $data->events['cards'] as $e ) : ?>
		<div class="list-group-item match__list-item py-2 px-3">
			<div class="anwp-row align-items-center no-gutters">
				<div class="anwp-col-sm d-flex flex-sm-row-reverse flex-row">

					<?php if ( $e->club === (int) $data->home_club ) : ?>
						<div class="match__event-icon mx-sm-2">
							<svg class="icon__card">
								<use xlink:href="#icon-card_<?php echo esc_attr( $e->card ); ?>"></use>
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
								<?php echo esc_html( isset( $card_options[ $e->card ] ) ? $card_options[ $e->card ] : '' ); ?>
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
							<svg class="icon__card">
								<use xlink:href="#icon-card_<?php echo esc_attr( $e->card ); ?>"></use>
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
								<?php echo esc_html( isset( $card_options[ $e->card ] ) ? $card_options[ $e->card ] : '' ); ?>
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
