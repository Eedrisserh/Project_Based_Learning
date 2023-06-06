<?php
/**
 * The Template for displaying Club >> Latest Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-latest.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.4
 *
 * @version       0.11.6
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
 * Hook: anwpfl/tmpl-club/before_latest
 *
 * @since 0.7.5
 *
 * @param WP_Post $club
 * @param integer $season_id
 */
do_action( 'anwpfl/tmpl-club/before_latest', $club, $data->season_id );

if ( ! apply_filters( 'anwpfl/tmpl-club/render_latest', true, $club, $data->season_id ) ) {
	return;
}
?>
<div class="club__latest club-section anwp-section">

	<div class="anwp-block-header__wrapper d-flex justify-content-between">
		<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'club__latest__latest_matches', __( 'Latest Matches', 'anwp-football-leagues' ) ) ); ?></div>
		<?php
		$dropdown_filter = [
			'context' => 'club',
			'id'      => $data->club_id,
		];

		anwp_football_leagues()->helper->season_dropdown( $data->season_id, true, '', $dropdown_filter );
		?>
	</div>
	<?php
	$shortcode_loader = [
		'filter_by'     => 'club',
		'filter_values' => $club->ID,
		'season_id'     => $data->season_id,
		'type'          => 'result',
		'limit'         => 5,
		'sort_by_date'  => 'desc',
		'class'         => 'mt-2',
	];

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo anwp_football_leagues()->template->shortcode_loader( 'matches', $shortcode_loader );
	?>
</div>
