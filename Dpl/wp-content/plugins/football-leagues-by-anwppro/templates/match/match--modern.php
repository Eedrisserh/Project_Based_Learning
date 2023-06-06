<?php
/**
 * The Template for displaying Match (modern version). Used most in widgets.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match--modern.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author           Andrei Strekozov <anwp.pro>
 * @package          AnWP-Football-Leagues/Templates
 * @since            0.7.4
 *
 * @version          0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'show_match_datetime' => true,
		'kickoff'             => '',
		'match_date'          => '',
		'match_time'          => '',
		'home_club'           => '',
		'away_club'           => '',
		'club_home_abbr'      => '',
		'club_away_abbr'      => '',
		'club_home_logo'      => '',
		'club_away_logo'      => '',
		'club_home_link'      => '',
		'club_away_link'      => '',
		'match_id'            => '',
		'finished'            => '',
		'home_goals'          => '',
		'away_goals'          => '',
		'extra'               => '',
		'aggtext'             => '',
		'permalink'           => '',
		'special_status'      => '',
	]
);
?>

<div class="list-group-item competition__match match-list__item match--modern p-0 position-relative" data-anwp-match="<?php echo intval( $data->match_id ); ?>">

	<?php if ( $data->show_match_datetime && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
		<div class="match-list__kickoff anwp-bg-light anwp-text-center px-2 py-0">
			<span class="match-list__date"><?php echo esc_html( $data->match_date ); ?></span>

			<?php if ( 'TBD' !== $data->special_status ) : ?>
				- <span class="match-list__time"><?php echo esc_html( $data->match_time ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="anwp-row anwp-no-gutters">

		<div class="anwp-col d-flex flex-column justify-content-around m-1 text-truncate">

			<div class="match-list__club d-block d-flex align-items-center">

				<?php if ( $data->club_home_logo ) : ?>
					<div class="club-logo__cover club-logo__cover--mini mr-2" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
				<?php endif; ?>

				<span class="text-truncate d-inline-block"><?php echo esc_html( $data->club_home_abbr ); ?></span>
			</div>

			<div class="match-list__club d-block d-flex align-items-center">

				<?php if ( $data->club_away_logo ) : ?>
					<div class="club-logo__cover club-logo__cover--mini mr-2" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
				<?php endif; ?>

				<span class="text-truncate d-inline-block"><?php echo esc_html( $data->club_away_abbr ); ?></span>
			</div>
			<?php
			$time_result = '';

			switch ( intval( $data->extra ) ) {
				case 1:
					$time_result = esc_html( AnWPFL_Text::get_value( 'match__match__aet', _x( 'AET', 'Abbr: after extra time', 'anwp-football-leagues' ) ) );
					break;
				case 2:
					$time_result  = esc_html( AnWPFL_Text::get_value( 'match__match__penalties', _x( 'Penalties', 'on penalties', 'anwp-football-leagues' ) ) );
					$time_result .= ' ' . $data->home_goals_p . '-' . $data->away_goals_p;
					break;
			}

			if ( $time_result ) {
				echo '<span class="match-list__time-result text-muted mt-1 ml-2 text-nowrap">' . esc_html( $time_result ) . '</span>';
			}

			if ( $data->aggtext ) {
				echo '<span class="match-list__time-result text-muted ml-2">' . esc_html( $data->aggtext ) . '</span>';
			}

			if ( 'PST' === $data->special_status ) {
				echo '<span class="match-list__time-result text-muted ml-2">' . esc_html( AnWPFL_Text::get_value( 'match__match__match_postponed', __( 'Match Postponed', 'anwp-football-leagues' ) ) ) . '</span>';
			}
			?>
		</div>

		<div class="anwp-col-auto match-list__scores d-flex flex-column position-relative m-1">
			<div class="match-list__scores-number match-list__scores-number--small d-inline-block anwp-text-center m-0 mb-1">
				<?php echo (int) $data->finished ? (int) $data->home_goals : '-'; ?>
			</div>

			<div class="match-list__scores-number match-list__scores-number--small d-inline-block anwp-text-center m-0">
				<?php echo (int) $data->finished ? (int) $data->away_goals : '-'; ?>
			</div>
		</div>
	</div>

	<a class="stretched-link anwp-link-without-effects" href="<?php echo esc_url( $data->permalink ); ?>"></a>
</div>
