<?php
/**
 * The Template for displaying Match >> Latest Clubs Matches Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-latest.php.
 *
 * @var object $data - Object with args.
 *
 * @author          Andrei Strekozov <anwp.pro>
 * @package         AnWP-Football-Leagues/Templates
 *
 * @version         0.11.12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'club_home_logo'  => '',
		'home_club'       => '',
		'club_home_title' => '',
		'club_away_logo'  => '',
		'away_club'       => '',
		'club_away_title' => '',
		'club_home_link'  => '',
		'club_away_link'  => '',
		'kickoff'         => '',
		'header'          => true,
	]
);

// Home Matches
$matches_home = anwp_football_leagues()->template->shortcode_loader(
	'matches',
	[
		'filter_by'     => 'club',
		'filter_values' => $data->home_club,
		'type'          => 'result',
		'limit'         => 5,
		'sort_by_date'  => 'desc',
		'class'         => '',
		'date_to'       => $data->kickoff,
	]
);

// Away Matches
$matches_away = anwp_football_leagues()->template->shortcode_loader(
	'matches',
	[
		'filter_by'     => 'club',
		'filter_values' => $data->away_club,
		'type'          => 'result',
		'limit'         => 5,
		'sort_by_date'  => 'desc',
		'class'         => '',
		'date_to'       => $data->kickoff,
	]
);

if ( empty( $matches_home ) && empty( $matches_away ) ) {
	return;
}
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__latest__latest_matches', __( 'Latest Matches', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="match__club--mini p-2 my-2 d-flex align-items-center anwp-bg-light">
		<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_home_logo ); ?>')"></div>
		<div class="match__club mx-2 mr-auto">
			<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_home_link ); ?>">
				<?php echo esc_html( $data->club_home_title ); ?>
			</a>
		</div>
		<?php anwp_football_leagues()->helper->club_form( $data->home_club ); ?>
	</div>

	<?php echo $matches_home; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<div class="match__club--mini p-2 mb-2 mt-2 d-flex align-items-center anwp-bg-light">
		<div class="club-logo__cover club-logo__cover--large d-block" style="background-image: url('<?php echo esc_attr( $data->club_away_logo ); ?>')"></div>
		<div class="match__club mx-2 mr-auto">
			<a class="match__club-link club__link anwp-link" href="<?php echo esc_url( $data->club_away_link ); ?>">
				<?php echo esc_html( $data->club_away_title ); ?>
			</a>
		</div>
		<?php anwp_football_leagues()->helper->club_form( $data->away_club ); ?>
	</div>

	<?php echo $matches_away; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
