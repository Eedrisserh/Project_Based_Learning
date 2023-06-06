<?php
/**
 * The Template for displaying Match (Card A).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match--card-a.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.0
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
		'club_titles'         => true,
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
		'permalink'           => '',
		'special_status'      => '',
	]
);

$text_stage = $data->stage_title ?: '';
$text_round = anwp_football_leagues()->competition->tmpl_get_matchweek_round_text( $data->match_week, $data->competition_id );
?>

<div class="match-card match-card--a py-1 px-2 d-flex flex-column position-relative" data-anwp-match="<?php echo intval( $data->match_id ); ?>">

	<div class="match-card__header anwp-text-center">
		<div class="match-card__header-item text-truncate anwp-text-center"><?php echo esc_html( get_post( (int) $data->competition_id )->post_title ); ?></div>
		<div class="match-card__header-item text-truncate anwp-text-center"><?php echo esc_html( $text_stage ); ?></div>

		<?php if ( $text_stage !== $text_round ) : ?>
			<div class="match-card__header-item text-truncate anwp-text-center"><?php echo esc_html( $text_round ); ?></div>
		<?php endif; ?>
	</div>

	<div class="d-flex anwp-no-gutters my-1">

		<div class="anwp-col anwp-text-center anwp-min-width-0">
			<?php if ( $data->club_home_logo ) : ?>
				<div class="club-logo__cover club-logo__cover--xlarge" style="background-image: url('<?php echo esc_url( $data->club_home_logo ); ?>')"></div>
			<?php endif; ?>

			<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->club_titles ) ) : ?>
				<div class="match-card__club-title text-truncate">
					<?php echo esc_html( $data->club_home_abbr ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="anwp-col-auto anwp-text-center">
			<div class="d-flex align-items-center match-card__scores">
				<span class="d-inline-block ml-1"><?php echo (int) $data->finished ? (int) $data->home_goals : '-'; ?></span>
				<span>:</span>
				<span class="d-inline-block mr-1"><?php echo (int) $data->finished ? (int) $data->away_goals : '-'; ?></span>
			</div>
		</div>

		<div class="anwp-col anwp-text-center anwp-min-width-0">
			<?php if ( $data->club_away_logo ) : ?>
				<div class="club-logo__cover club-logo__cover--xlarge" style="background-image: url('<?php echo esc_url( $data->club_away_logo ); ?>')"></div>
			<?php endif; ?>

			<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->club_titles ) ) : ?>
				<div class="match-card__club-title text-truncate">
					<?php echo esc_html( $data->club_away_abbr ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $data->show_match_datetime && '0000-00-00 00:00:00' !== $data->kickoff ) : ?>
		<div class="match-card__footer anwp-bg-light anwp-text-center mt-auto d-flex justify-content-center">
			<?php if ( 'PST' === $data->special_status ) : ?>
				<span class="match-card__time">
				<?php echo esc_html( AnWPFL_Text::get_value( 'match__match__match_postponed', __( 'Match Postponed', 'anwp-football-leagues' ) ) ); ?>
				</span>
			<?php else : ?>
				<span class="match-card__date"><?php echo esc_html( date_i18n( 'j M', strtotime( $data->kickoff ) ) ); ?></span>
				<?php if ( 'TBD' !== $data->special_status ) : ?>
					<span class="mx-1">-</span>
					<span class="match-card__time"><?php echo esc_html( $data->match_time ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<a class="stretched-link anwp-link-without-effects" href="<?php echo esc_url( $data->permalink ); ?>"></a>
</div>
