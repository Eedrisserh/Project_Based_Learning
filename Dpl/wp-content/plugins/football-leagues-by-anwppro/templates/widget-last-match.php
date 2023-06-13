<?php
/**
 * The Template for displaying Last Match.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-last-match.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.13
 *
 * @version       0.11.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check for required data
if ( empty( $data->club_id ) ) {
	return;
}

// Prevent errors with new params
$args = (object) wp_parse_args(
	$data,
	[
		'club_id'         => '',
		'competition_id'  => '',
		'season_id'       => '',
		'match_link_text' => '',
		'show_club_name'  => 1,
		'exclude_ids'     => '',
		'include_ids'     => '',
	]
);

// Get competition matches
$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended(
	[
		'competition_id'  => $args->competition_id,
		'season_id'       => $args->season_id,
		'show_secondary'  => 1,
		'type'            => 'result',
		'filter_by_clubs' => $args->club_id,
		'limit'           => 1,
		'sort_by_date'    => 'desc',
		'exclude_ids'     => $args->exclude_ids,
		'include_ids'     => $args->include_ids,
	]
);

if ( empty( $matches ) || empty( $matches[0]->match_id ) ) {
	return;
}

$data = (object) anwp_football_leagues()->match->prepare_match_data_to_render( $matches[0], [], 'widget', 'full' );

$show_name = anwp_football_leagues()->helper->string_to_bool( $args->show_club_name );
?>
<div class="anwp-b-wrap">
	<div class="match-card anwp-bg-light py-3">

		<?php if ( $data->stadium_id ) : ?>
			<div class="match-card__stadium anwp-text-center text-muted">
				<svg class="anwp-icon anwp-icon--octi">
					<use xlink:href="#icon-location"></use>
				</svg>
				<?php
				echo esc_html( get_the_title( $data->stadium_id ) );

				$stadium_city = get_post_meta( $data->stadium_id, '_anwpfl_city', true );
				echo $stadium_city ? esc_html( ', ' . $stadium_city ) : '';
				?>
			</div>
		<?php endif; ?>

		<div class="match-card__competition anwp-text-center font-weight-bold">
			<?php echo esc_html( $data->stage_title ? ( $data->stage_title . ' - ' ) : '' ); ?>
			<?php echo esc_html( get_post( (int) $data->competition_id )->post_title ); ?>
		</div>

		<div class="match-card__competition anwp-text-center font-weight-bold">
			<?php
			if ( $data->kickoff && '0000-00-00 00:00:00' !== $data->kickoff ) {

				$date_format = anwp_football_leagues()->get_option_value( 'custom_match_date_format' ) ?: 'j M ';
				$time_format = anwp_football_leagues()->get_option_value( 'custom_match_time_format' ) ?: get_option( 'time_format' );

				echo esc_html( date_i18n( $date_format . ' ' . $time_format, get_date_from_gmt( $data->kickoff, 'U' ) ) );
			}
			?>
		</div>

		<div class="match-card__clubs d-flex anwp-no-gutters my-3">
			<div class="anwp-col d-flex flex-column anwp-text-center anwp-min-width-0 px-1">
				<?php if ( $show_name ) : ?>
					<div class="club-logo__cover club-logo__cover--xlarge d-block mx-auto" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"></div>
					<div class="match__club mt-1 d-inline-block text-truncate">
						<?php echo esc_html( $data->club_home_title ); ?>
					</div>
				<?php else : ?>
					<div class="club-logo__cover club-logo__cover--xlarge d-block mx-auto text-truncate" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"
						data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( $data->club_home_title ); ?>"></div>
				<?php endif; ?>
			</div>
			<div class="anwp-col-auto align-self-center match-list__scores d-flex">
				<span class="match-list__scores-number match-list__scores--home d-inline-block mr-1 anwp-text-center h3 p-1"><?php echo (int) $data->home_goals; ?></span>
				<span class="match-list__scores-number match-list__scores--away d-inline-block anwp-text-center h3 p-1"><?php echo (int) $data->away_goals; ?></span>
			</div>
			<div class="anwp-col d-flex flex-column anwp-text-center anwp-min-width-0 px-1">
				<?php if ( $show_name ) : ?>
					<div class="club-logo__cover club-logo__cover--xlarge d-block mx-auto" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"></div>
					<div class="match__club mt-1 d-inline-block text-truncate">
						<?php echo esc_html( $data->club_away_title ); ?>
					</div>
				<?php else : ?>
					<div class="club-logo__cover club-logo__cover--xlarge d-block mx-auto text-truncate" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"
						data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( $data->club_away_title ); ?>"></div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $args->match_link_text ) : ?>
			<div class="anwp-text-center anwp-match-preview-link anwp-bg-light mt-1">
				<a href="<?php echo esc_url( get_permalink( (int) $data->match_id ) ); ?>" class="anwp-link-without-effects">
					<span class="d-inline-block"><?php echo esc_html( $args->match_link_text ); ?></span>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
