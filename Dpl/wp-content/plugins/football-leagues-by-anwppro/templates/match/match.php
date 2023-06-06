<?php
/**
 * The Template for displaying Match.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.6.1
 *
 * @version       0.11.13
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
		'club_home_link'      => '',
		'club_away_link'      => '',
		'club_home_logo'      => '',
		'club_away_logo'      => '',
		'match_id'            => '',
		'finished'            => '',
		'home_goals'          => '',
		'away_goals'          => '',
		'match_week'          => '',
		'stadium_id'          => '',
		'competition_id'      => '',
		'main_stage_id'       => '',
		'stage_title'         => '',
		'attendance'          => '',
		'aggtext'             => '',
		'home_goals_half'     => '',
		'away_goals_half'     => '',
		'home_goals_p'        => '',
		'away_goals_p'        => '',
		'home_goals_ft'       => '',
		'away_goals_ft'       => '',
		'referee_id'          => '',
		'special_status'      => '',
		'context'             => 'shortcode',
	]
);
?>

<div class="match__header--wrapper match-status__<?php echo esc_attr( $data->finished ); ?> anwp-section">
	<div class="match__header--top anwp-bg-dark text-light small px-3 py-2">
		<span class="d-block anwp-text-center">
			<?php
			if ( ( $data->show_match_datetime && '0000-00-00 00:00:00' !== $data->kickoff ) ) {
				if ( 'TBD' === $data->special_status ) {
					$date_format = anwp_football_leagues()->get_option_value( 'custom_match_date_format' ) ?: 'j M Y';
					echo esc_html( date_i18n( $date_format, get_date_from_gmt( $data->kickoff, 'U' ) ) );
				} else {
					$date_format = anwp_football_leagues()->get_option_value( 'custom_match_date_format' ) ?: 'j M Y - ';
					$time_format = anwp_football_leagues()->get_option_value( 'custom_match_time_format' ) ?: get_option( 'time_format' );
					echo esc_html( date_i18n( $date_format . ' ' . $time_format, get_date_from_gmt( $data->kickoff, 'U' ) ) );
				}
			}

			// Match stadium
			$stadium = intval( $data->stadium_id ) ? get_post( $data->stadium_id ) : null;

			if ( $stadium && 'publish' === $stadium->post_status ) :
				?>
				<span class="anwp-words-separator">|</span>
				<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $stadium ) ); ?>">
						<?php echo esc_html( $stadium->post_title ); ?>
				</a>
				<?php
			endif;

			if ( (int) $data->attendance ) :
				?>
				<span class="anwp-words-separator">|</span>
				<?php echo esc_html( AnWPFL_Text::get_value( 'match__match__attendance', __( 'Attendance', 'anwp-football-leagues' ) ) ); ?>:
				<?php echo esc_html( number_format_i18n( (int) $data->attendance ) ); ?>
			<?php endif; ?>

			<?php if ( (int) $data->referee_id ) : ?>
				<span class="anwp-words-separator">|</span>
				<?php echo esc_html( AnWPFL_Text::get_value( 'match__match__referee', __( 'Referee', 'anwp-football-leagues' ) ) ); ?>:
				<?php echo esc_html( get_the_title( $data->referee_id ) ); ?>
			<?php endif; ?>

		</span>
		<span class="d-block anwp-text-center">
			<a class="anwp-link anwp-link-without-effects"
				href="<?php echo esc_url( get_permalink( (int) $data->main_stage_id ? (int) $data->main_stage_id : (int) $data->competition_id ) ); ?>">
				<?php echo esc_html( $data->stage_title ? ( $data->stage_title . ' - ' ) : '' ); ?>
				<?php echo esc_html( get_the_title( (int) $data->competition_id ) ); ?>
			</a>
			<?php echo esc_html( anwp_football_leagues()->competition->tmpl_get_matchweek_round_text( $data->match_week, $data->competition_id, ' | ' ) ); ?>
		</span>
	</div>

	<div class="match__sup-result anwp-bg-light align-items-center small text-muted anwp-text-center pt-2">
		<?php if ( '1' === $data->finished ) : ?>
			<?php
			$sup_texts = [];

			// Half time
			if ( apply_filters( 'anwpfl/match/show_half_time_score', true ) ) {
				$sup_texts[] = esc_html( AnWPFL_Text::get_value( 'match__match__half_time', __( 'Half Time', 'anwp-football-leagues' ) ) ) . ': ' . $data->home_goals_half . '-' . $data->away_goals_half;
			}

			// Full Time
			if ( apply_filters( 'anwpfl/match/show_full_time_score', true ) && ( '1' === $data->extra || '2' === $data->extra ) ) {
				$sup_texts[] = esc_html( AnWPFL_Text::get_value( 'match__match__full_time', __( 'Full Time', 'anwp-football-leagues' ) ) ) . ': ' . $data->home_goals_ft . '-' . $data->away_goals_ft;
			}

			// Aggregate Text
			if ( $data->aggtext ) {
				$sup_texts[] = $data->aggtext;
			}
			?>

			<?php foreach ( $sup_texts as $text ) : ?>
				<span class="match__sup-result-item-separator"> | </span>
				<span class="match__sup-result-item"><?php echo esc_html( $text ); ?></span>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div class="match__header d-sm-flex flex-sm-row flex-sm-nowrap px-3 no-gutters anwp-bg-light align-items-center <?php echo esc_attr( '1' === $data->finished ? '' : 'pb-2' ); ?>">
		<div class="anwp-col-sm match__club-wrapper--header d-flex align-items-center pb-2">
			<div class="club-logo__cover club-logo__cover--xlarge d-block" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"></div>
			<div class="match__club mx-2 d-inline-block">
				<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_home_link ); ?>">
					<?php echo esc_html( $data->club_home_title ); ?>
				</a>
			</div>

			<?php if ( '1' === $data->finished ) : ?>
				<span class="match__scores-number d-inline-block d-sm-none ml-auto"><?php echo (int) $data->home_goals; ?></span>
			<?php endif; ?>
		</div>

		<?php if ( '1' === $data->finished ) : ?>
			<div class="anwp-col-sm-auto match__scores-number-wrapper d-none d-sm-block">
				<a href="<?php echo esc_url( get_permalink( (int) $data->match_id ) ); ?>" class="anwp-link-without-effects">
					<span class="match__scores-number d-inline-block"><?php echo (int) $data->home_goals; ?></span>
					<span class="match__scores-number-separator d-inline-block">:</span>
					<span class="match__scores-number d-inline-block"><?php echo (int) $data->away_goals; ?></span>
				</a>
			</div>
		<?php endif; ?>

		<div class="anwp-col-sm match__club-wrapper--header d-flex flex-sm-row-reverse align-items-center pb-2">
			<div class="club-logo__cover club-logo__cover--xlarge d-block" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"></div>
			<div class="match__club mx-2 d-inline-block">
				<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_away_link ); ?>">
					<?php echo esc_html( $data->club_away_title ); ?>
				</a>
			</div>
			<?php if ( '1' === $data->finished ) : ?>
				<span class="match__scores-number d-inline-block d-sm-none ml-auto"><?php echo (int) $data->away_goals; ?></span>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( '1' === $data->finished ) : ?>
		<div class="anwp-bg-light anwp-text-center text-uppercase small pb-2">
			<span class="border border-secondary px-1 py-0">
				<?php
				if ( 'yes' === get_post_meta( $data->match_id, '_anwpfl_custom_outcome', true ) && ! empty( get_post_meta( $data->match_id, '_anwpfl_outcome_text', true ) ) ) {
					echo esc_html( get_post_meta( $data->match_id, '_anwpfl_outcome_text', true ) );
				} else {
					$time_result = esc_html( AnWPFL_Text::get_value( 'match__match__full_time', __( 'Full Time', 'anwp-football-leagues' ) ) );

					switch ( intval( $data->extra ) ) {
						case 1:
							$time_result = esc_html( AnWPFL_Text::get_value( 'match__match__aet', _x( 'AET', 'Abbr: after extra time', 'anwp-football-leagues' ) ) );
							break;
						case 2:
							$time_result  = esc_html( AnWPFL_Text::get_value( 'match__match__penalties', _x( 'Penalties', 'on penalties', 'anwp-football-leagues' ) ) );
							$time_result .= ' ' . $data->home_goals_p . '-' . $data->away_goals_p;
							break;
					}
					echo esc_html( $time_result );
				}
				?>
			</span>
		</div>
	<?php endif; ?>

	<?php if ( '0' === $data->finished && 'PST' === $data->special_status ) : ?>
		<div class="anwp-bg-light anwp-text-center text-uppercase small pb-2">
			<span class="border border-secondary px-1 py-0"><?php echo esc_html( AnWPFL_Text::get_value( 'match__match__match_postponed', __( 'Match Postponed', 'anwp-football-leagues' ) ) ); ?></span>
		</div>
	<?php endif; ?>

	<?php if ( '0' === $data->finished ) : ?>
		<?php
		if ( 'hide' !== AnWPFL_Options::get_value( 'fixture_flip_countdown' ) && '0000-00-00 00:00:00' !== $data->kickoff && $data->kickoff && ! in_array( $data->special_status, [ 'PST' ], true ) ) :
			$kickoff_diff = ( date_i18n( 'U', get_date_from_gmt( $data->kickoff, 'U' ) ) - date_i18n( 'U' ) ) > 0 ? date_i18n( 'U', get_date_from_gmt( $data->kickoff, 'U' ) ) - date_i18n( 'U' ) : 0;
			?>
			<div class="anwp-bg-light anwp-text-center pt-3">
				<div class="anwp-match-flip-countdown">
					<div class="countdown-container anwp-match-flip-countdown-container"
						data-kickoff-diff="<?php echo esc_attr( $kickoff_diff * 1000 ); ?>"
						data-kickoff="<?php echo esc_attr( date_i18n( 'Y/m/d H:i:s', strtotime( $data->kickoff ) ) ); ?>"
					></div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( 'shortcode' === $data->context ) : ?>
			<div class="anwp-text-center anwp-match-preview-link anwp-bg-light py-3">
				<a href="<?php echo esc_url( get_permalink( (int) $data->match_id ) ); ?>" class="anwp-link-without-effects">
					<span class="d-inline-block"><?php echo esc_html( AnWPFL_Text::get_value( 'match__match__match_preview', __( '- match preview -', 'anwp-football-leagues' ) ) ); ?></span>
				</a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>


