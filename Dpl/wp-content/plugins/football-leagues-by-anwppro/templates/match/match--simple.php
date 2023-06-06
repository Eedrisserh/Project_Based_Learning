<?php
/**
 * The Template for displaying Match (simple version). Used most in widgets.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match--simple.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author         Andrei Strekozov <anwp.pro>
 * @package        AnWP-Football-Leagues/Templates
 * @since          0.7.4
 *
 * @version        0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'show_match_datetime' => true,
		'show_club_name'      => 1,
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

<div class="list-group-item competition__match match-list__item match--simple p-0 position-relative" data-anwp-match="<?php echo intval( $data->match_id ); ?>">

	<?php if ( $data->show_match_datetime && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
		<div class="match-list__kickoff anwp-bg-light anwp-text-center px-2 py-0">
			<span class="match-list__date"><?php echo esc_html( $data->match_date ); ?></span>

			<?php if ( 'TBD' !== $data->special_status ) : ?>
				- <span class="match-list__time"><?php echo esc_html( $data->match_time ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="anwp-row anwp-no-gutters m-1">
		<div class="anwp-col-6 d-flex align-items-center">

			<?php if ( $data->club_home_logo ) : ?>
				<?php if ( $data->show_club_name ) : ?>
					<div class="club-logo__cover club-logo__cover--small" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
				<?php else : ?>
					<div class="d-flex align-items-center align-self-stretch my-1 justify-content-center flex-grow-1">
						<div class="club-logo__cover club-logo__cover--large" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $data->show_club_name ) : ?>
				<div class="match-list__club flex-grow-1 anwp-text-center mx-1 text-truncate">
					<?php echo esc_html( $data->club_home_abbr ); ?>
				</div>
			<?php endif; ?>

			<div class="match-list__scores-number d-inline-block mr-0 anwp-text-center ml-auto">
				<?php echo (int) $data->finished ? (int) $data->home_goals : '-'; ?>
			</div>
		</div>
		<div class="anwp-col-6 d-flex align-items-center">
			<div class="match-list__scores-number ml-1 anwp-text-center">
				<?php echo (int) $data->finished ? (int) $data->away_goals : '-'; ?>
			</div>

			<?php if ( $data->show_club_name ) : ?>
				<div class="flex-grow-1 match-list__club anwp-text-center mx-1 text-truncate"><?php echo esc_html( $data->club_away_abbr ); ?></div>
			<?php endif; ?>

			<?php if ( $data->club_away_logo ) : ?>
				<?php if ( $data->show_club_name ) : ?>
					<div class="club-logo__cover club-logo__cover--small ml-auto" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
				<?php else : ?>
					<div class="d-flex align-items-center align-self-stretch my-1 justify-content-center flex-grow-1">
						<div class="club-logo__cover club-logo__cover--large" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
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
		?>
		<?php if ( $time_result || $data->aggtext || 'PST' === $data->special_status ) : ?>
			<div class="anwp-col-12 match-list__time-result-wrapper text-sm-center pl-2 pl-sm-0">
				<?php
				if ( 'PST' === $data->special_status ) {
					echo '<span class="match-list__time-result text-muted d-inline-block text-nowrap">' . esc_html( AnWPFL_Text::get_value( 'match__match__match_postponed', __( 'Match Postponed', 'anwp-football-leagues' ) ) ) . '</span>';
				}

				if ( $time_result ) {
					echo '<span class="match-list__time-result text-muted d-inline-block text-nowrap">' . esc_html( $time_result ) . '</span>';
				}

				if ( $data->aggtext ) {
					echo '<span class="match__sup-result-item-separator"> | </span><span class="match-list__time-result text-muted d-inline-block">' . esc_html( $data->aggtext ) . '</span>';
				}
				?>
			</div>
		<?php endif; ?>
	</div>
	<a class="stretched-link anwp-link-without-effects" href="<?php echo esc_url( $data->permalink ); ?>"></a>
</div>
