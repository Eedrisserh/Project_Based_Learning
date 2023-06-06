<?php
/**
 * The Template for displaying Club >> Fixtures Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-fixtures.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.4
 *
 * @version       0.10.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'club_id'   => '',
		'season_id' => '',
	]
);

$club = get_post( $data->club_id );

/**
 * Hook: anwpfl/tmpl-club/before_fixtures
 *
 * @since 0.7.5
 *
 * @param WP_Post $club
 * @param integer $data->season_idv
 */
do_action( 'anwpfl/tmpl-club/before_fixtures', $club, $data->season_id );

/**
 * Filter: anwpfl/tmpl-club/render_fixtures
 *
 * @since 0.7.5
 *
 * @param bool
 * @param WP_Post $club
 * @param integer $season_id
 */
if ( ! apply_filters( 'anwpfl/tmpl-club/render_fixtures', true, $club, $data->season_id ) ) {
	return;
}
?>
<div class="club__fixtures club-section anwp-section">
	<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'club__fixtures__fixtures', __( 'Fixtures', 'anwp-football-leagues' ) ) ); ?></div>
	<?php

	/**
	 * Filter: anwpfl/tmpl-club/fixtures_limit
	 *
	 * @since 0.7.5
	 *
	 * @param bool
	 * @param WP_Post $club
	 * @param integer $season_id
	 */
	$fixtures_limit = apply_filters( 'anwpfl/tmpl-club/fixtures_limit', 5, $club, $data->season_id );

	$shortcode_loader = [
		'filter_by'     => 'club',
		'filter_values' => $data->club_id,
		'type'          => 'fixture',
		'limit'         => $fixtures_limit,
		'sort_by_date'  => 'asc',
		'class'         => 'mt-2',
	];

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo anwp_football_leagues()->template->shortcode_loader( 'matches', $shortcode_loader );
	?>
</div>
