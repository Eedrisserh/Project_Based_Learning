<?php
/**
 * The Template for displaying Match (slim version).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match--slim.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.6.1
 *
 * @version       0.13.0
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
		'club_links'          => true,
		'home_club'           => '',
		'away_club'           => '',
		'club_home_title'     => '',
		'club_away_title'     => '',
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
		'competition_logo'    => true,
		'outcome_id'          => '',
		'special_status'      => '',
		'extra_actions_html'  => '',
	]
);

$show_stadium  = AnWPFL_Options::get_value( 'match_slim_stadium_show' );
$stadium_title = ( $show_stadium && (int) $data->stadium_id ) ? anwp_football_leagues()->stadium->get_stadium_title( $data->stadium_id ) : '';

// Get competition
$competition = anwp_football_leagues()->competition->get_competition( intval( $data->main_stage_id ) ?: intval( $data->competition_id ) );

// Wrapper classes
$render_competition_logo = anwp_football_leagues()->helper->string_to_bool( $data->competition_logo );
$render_stadium          = $show_stadium && $data->show_match_datetime;
$render_match_time       = $data->show_match_datetime;

$wrapper_classes = [];

if ( $render_competition_logo ) {
	$wrapper_classes[] = 'match-slim__has-competition';
}

if ( $render_stadium ) {
	$wrapper_classes[] = 'match-slim__has-stadium';
}

if ( $render_match_time ) {
	$wrapper_classes[] = 'match-slim__has-match-time';
}
?>

<div class="list-group-item competition__match match-list__item p-0 position-relative match-slim <?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-anwp-match="<?php echo intval( $data->match_id ); ?>">

	<div class="match-slim__inner-wrapper d-none d-sm-flex no-gutters position-relative w-100">
		<?php if ( $render_competition_logo ) : ?>
			<div class="match-list__competition anwp-col-auto p-1 d-flex align-items-center">
				<div data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( $competition->title ); ?>" class="competition-logo__cover" style="background-image: url('<?php echo esc_url( $competition->logo ); ?>')"></div>
			</div>
		<?php endif; ?>

		<?php if ( $render_stadium ) : ?>
			<div class="match-list__kickoff match-list__kickoff--stadium anwp-col-auto py-0 pl-1 d-flex flex-column justify-content-center mr-sm-0">

				<?php if ( $stadium_title ) : ?>
					<span class="match-list__stadium d-flex align-items-center">
						<svg class="anwp-icon anwp-icon--octi anwp-icon--s10 mr-1"><use xlink:href="#icon-location"></use></svg>
						<span><?php echo esc_html( $stadium_title ); ?></span>
					</span>
				<?php endif; ?>

				<?php if ( $data->kickoff && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
					<span class="match-list__time d-inline pr-1"><?php echo esc_html( $data->match_date ); ?></span>

					<?php if ( 'TBD' !== $data->special_status && $data->match_time ) : ?>
						<span class="match-list__date d-inline pr-1"><?php echo esc_html( $data->match_time ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<?php if ( $data->show_match_datetime && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
				<div class="match-list__kickoff match-list__kickoff--small anwp-col-auto mr-1 py-1 pl-1 d-flex flex-column justify-content-center text-left">
					<span class="match-list__time d-block"><?php echo esc_html( $data->match_date ); ?></span>

					<?php if ( 'TBD' !== $data->special_status && $data->match_time ) : ?>
						<span class="match-list__date d-block"><?php echo esc_html( $data->match_time ); ?></span>
					<?php endif; ?>

					<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->competition_logo ) ) : ?>
						<div class="match-list__competition p-1 d-flex justify-content-center d-sm-none">
							<div data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( $competition->title ); ?>" class="competition-logo__cover mt-2" style="background-image: url('<?php echo esc_url( $competition->logo ); ?>')"></div>
						</div>
					<?php endif; ?>
				</div>
			<?php elseif ( $data->show_match_datetime ) : ?>
				<div class="match-list__kickoff match-list__kickoff--small anwp-col-auto mr-1 p-1"></div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="d-flex align-items-center no-gutters my-1 flex-grow-1 match-slim__scoreboard">
			<div class="anwp-flex-sm-even anwp-min-width-0 d-flex align-items-center flex-sm-row-reverse mb-1 mb-sm-0 align-self-stretch text-truncate">

				<?php if ( $data->club_home_logo ) : ?>
					<div class="club-logo__cover club-logo__cover--small" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
				<?php endif; ?>

				<div class="match-list__club text-sm-right text-truncate">
					<?php echo esc_html( $data->club_home_title ); ?>
				</div>

			</div>
			<div class="anwp-col-auto match-list__scores anwp-text-center">

				<div class="d-flex align-items-center">
					<span class="match-list__scores-number match-list__scores--home d-inline-block mr-1"><?php echo (int) $data->finished ? (int) $data->home_goals : '-'; ?></span>
					<span class="match-list__scores-number match-list__scores--away d-inline-block"><?php echo (int) $data->finished ? (int) $data->away_goals : '-'; ?></span>
				</div>

			</div>
			<div class="anwp-flex-sm-even anwp-min-width-0 d-flex align-items-center align-self-stretch text-truncate">

				<?php if ( $data->club_away_logo ) : ?>
					<div class="club-logo__cover club-logo__cover--small" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
				<?php endif; ?>

				<div class="match-list__club text-truncate">
					<?php echo esc_html( $data->club_away_title ); ?>
				</div>
			</div>

			<?php
			/**
			 * Inject extra actions info match slim.
			 * Hook: anwpfl/tmpl-match-slim/extra_action
			 *
			 * @since 0.10.3
			 *
			 * @param object $data
			 */
			do_action( 'anwpfl/tmpl-match-slim/extra_action', $data );

			/**
			 * Render extra actions block.
			 *
			 * @since 0.11.14
			 */
			if ( $data->extra_actions_html ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $data->extra_actions_html;
			}

			/**
			 * Render outcome.
			 *
			 * @since 0.10.23
			 */
			if ( $data->outcome_id ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo anwp_football_leagues()->match->get_match_outcome_label( $data );
			}

			/**
			 * Additional text
			 */
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
		</div>
	</div>

	<div class="match-slim__inner-wrapper d-sm-none position-relative w-100">

		<div class="match-card__meta d-flex justify-content-center align-items-center pt-2">
			<?php if ( $data->kickoff && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
				<svg class="anwp-icon anwp-icon--octi mr-1">
					<use xlink:href="#icon-calendar"></use>
				</svg>

				<?php echo esc_html( $data->match_date ); ?>

				<?php if ( 'TBD' !== $data->special_status && $data->match_time ) : ?>
					<svg class="anwp-icon anwp-icon--octi mr-1 ml-3">
						<use xlink:href="#icon-clock"></use>
					</svg>
					<span><?php echo esc_html( $data->match_time ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if ( $render_stadium && $stadium_title ) : ?>
			<div class="d-flex justify-content-center align-items-center small">
				<svg class="anwp-icon anwp-icon--octi ml-3 mr-1">
					<use xlink:href="#icon-location"></use>
				</svg>
				<?php echo esc_html( $stadium_title ); ?>
			</div>
		<?php endif; ?>

		<div class="anwp-row anwp-no-gutters mt-2">
			<div class="anwp-col-6 d-flex align-items-center">
				<div class="d-flex align-items-center align-self-stretch my-1 justify-content-center flex-grow-1 flex-column text-truncate">
					<div class="club-logo__cover club-logo__cover--large" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
					<div class="match-list__club text-truncate px-2 w-100 anwp-text-center"><?php echo esc_html( $data->club_home_abbr ); ?></div>
				</div>

				<div class="match-list__scores-number match-list__scores--home d-inline-block mr-0 anwp-text-center ml-auto h3 py-1 px-2">
					<?php echo (int) $data->finished ? (int) $data->home_goals : '-'; ?>
				</div>
			</div>
			<div class="anwp-col-6 d-flex align-items-center">
				<div class="match-list__scores-number match-list__scores--away ml-1 anwp-text-center h3 py-1 px-2">
					<?php echo (int) $data->finished ? (int) $data->away_goals : '-'; ?>
				</div>

				<div class="d-flex align-items-center align-self-stretch my-1 justify-content-center flex-grow-1 flex-column text-truncate">
					<div class="club-logo__cover club-logo__cover--large" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
					<div class="match-list__club text-truncate px-2 w-100 anwp-text-center"><?php echo esc_html( $data->club_away_abbr ); ?></div>
				</div>
			</div>
		</div>

		<div class="match-card__meta d-flex flex-wrap justify-content-center mb-2 anwp-hide-invisible">

			<?php
			/**
			 * Inject extra actions info match slim.
			 * Hook: anwpfl/tmpl-match-slim/extra_action
			 *
			 * @param object $data
			 *
			 * @since 0.10.3
			 *
			 */
			do_action( 'anwpfl/tmpl-match-slim/extra_action', $data );

			/**
			 * Render extra actions block.
			 *
			 * @since 0.11.14
			 */
			if ( $data->extra_actions_html ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $data->extra_actions_html;
			}

			/**
			 * Render outcome.
			 *
			 * @since 0.10.23
			 */
			if ( $data->outcome_id ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo anwp_football_leagues()->match->get_match_outcome_label( $data );
			}

			/**
			 * Additional text
			 */
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
		</div>

	</div>

	<?php if ( $time_result || $data->aggtext || 'PST' === $data->special_status ) : ?>
		<div class="match-list__time-result-wrapper anwp-text-center mb-2">
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

	<?php
	/*
	|--------------------------------------------------------------------
	| Bottom Line
	|--------------------------------------------------------------------
	*/
	$bottom_line_options = AnWPFL_Options::get_value( 'match_slim_bottom_line' );
	$bottom_line_html    = '';

	if ( ! empty( $bottom_line_options ) && is_array( $bottom_line_options ) ) {
		// Stadium Title
		if ( in_array( 'stadium', $bottom_line_options, true ) ) {
			$stadium_title = absint( $data->stadium_id ) ? anwp_football_leagues()->stadium->get_stadium_title( $data->stadium_id ) : '';

			if ( $stadium_title ) {
				$bottom_line_html .= '<span class="match__sup-result-item-separator"> | </span>';
				$bottom_line_html .= '<span class="match-list__time-result text-muted d-inline-block text-nowrap">';
				$bottom_line_html .= '<svg class="anwp-icon anwp-icon--octi anwp-icon--s12 mr-1 mt-n1"><use xlink:href="#icon-location"></use></svg>';
				$bottom_line_html .= esc_html( $stadium_title ) . '</span>';
			}
		}

		// Referee
		if ( in_array( 'referee', $bottom_line_options, true ) ) {
			$referee_id   = get_post_meta( $data->match_id, '_anwpfl_referee', true );
			$referee_name = absint( $referee_id ) ? get_the_title( $referee_id ) : '';

			if ( $referee_name ) {
				$bottom_line_html .= '<span class="match__sup-result-item-separator"> | </span>';
				$bottom_line_html .= '<span class="match-list__time-result text-muted d-inline-block text-nowrap">';
				$bottom_line_html .= esc_html( AnWPFL_Text::get_value( 'match__match__referee', __( 'Referee', 'anwp-football-leagues' ) ) ) . ': ';
				$bottom_line_html .= esc_html( $referee_name ) . '</span>';
			}
		}

		// Referee Assistants
		if ( in_array( 'referee_assistants', $bottom_line_options, true ) ) {

			$assistant_1_id   = get_post_meta( $data->match_id, '_anwpfl_assistant_1', true );
			$assistant_1_name = absint( $assistant_1_id ) ? get_the_title( $assistant_1_id ) : '';

			if ( $assistant_1_name ) {
				$bottom_line_html .= '<span class="match__sup-result-item-separator"> | </span>';
				$bottom_line_html .= '<span class="match-list__time-result text-muted d-inline-block text-nowrap">';
				$bottom_line_html .= esc_html( AnWPFL_Text::get_value( 'match__referees__assistant', __( 'Assistant Referee', 'anwp-football-leagues' ) ) ) . ' 1: ';
				$bottom_line_html .= esc_html( $assistant_1_name ) . '</span>';
			}

			$assistant_2_id   = get_post_meta( $data->match_id, '_anwpfl_assistant_2', true );
			$assistant_2_name = absint( $assistant_2_id ) ? get_the_title( $assistant_2_id ) : '';

			if ( $assistant_2_name ) {
				$bottom_line_html .= '<span class="match__sup-result-item-separator"> | </span>';
				$bottom_line_html .= '<span class="match-list__time-result text-muted d-inline-block text-nowrap">';
				$bottom_line_html .= esc_html( AnWPFL_Text::get_value( 'match__referees__assistant', __( 'Assistant Referee', 'anwp-football-leagues' ) ) ) . ' 2: ';
				$bottom_line_html .= esc_html( $assistant_2_name ) . '</span>';
			}
		}

		// Fourth official
		if ( in_array( 'referee_fourth', $bottom_line_options, true ) ) {
			$referee_fourth_id   = get_post_meta( $data->match_id, '_anwpfl_referee_fourth', true );
			$referee_fourth_name = absint( $referee_fourth_id ) ? get_the_title( $referee_fourth_id ) : '';

			if ( $referee_fourth_name ) {
				$bottom_line_html .= '<span class="match__sup-result-item-separator"> | </span>';
				$bottom_line_html .= '<span class="match-list__time-result text-muted d-inline-block text-nowrap">';
				$bottom_line_html .= esc_html( AnWPFL_Text::get_value( 'match__referees__fourth_official', __( 'Fourth official', 'anwp-football-leagues' ) ) ) . ': ';
				$bottom_line_html .= esc_html( $referee_fourth_name ) . '</span>';
			}
		}
	}
	?>
	<?php if ( $bottom_line_html ) : ?>
		<div class="match-list__time-result-wrapper anwp-text-center mb-2 <?php echo esc_html( $time_result || $data->aggtext ? 'mt-n2' : '' ); ?>">
			<?php echo $bottom_line_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
	<a class="stretched-link" href="<?php echo esc_url( $data->permalink ); ?>"></a>
</div>
