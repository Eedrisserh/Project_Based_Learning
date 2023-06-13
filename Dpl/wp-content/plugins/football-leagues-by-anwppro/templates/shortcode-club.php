<?php
/**
 * The Template for displaying Club Data.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-club.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.11.8
 *
 * @version       0.12.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'club_id'   => '',
		'season_id' => '',
		'sections'  => '',
		'context'   => 'shortcode',
	]
);

if ( empty( $data->club_id ) || empty( $data->sections ) ) {
	return;
}

if ( ! empty( $_GET['season'] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification

	// phpcs:ignore WordPress.Security.NonceVerification
	$maybe_season_id = anwp_football_leagues()->season->get_season_id_by_slug( sanitize_key( $_GET['season'] ) );

	if ( absint( $maybe_season_id ) ) {
		$data->season_id = absint( $maybe_season_id );
	}
}

if ( ! absint( $data->season_id ) ) {
	return;
}

// Prepare tmpl data
$club = get_post( $data->club_id );

$fields = [
	'logo_big',
	'description',
	'city',
	'nationality',
	'address',
	'website',
	'founded',
	'stadium',
	'club_kit',
	'twitter',
	'youtube',
	'facebook',
	'instagram',
	'vk',
	'tiktok',
	'linkedin',
];

foreach ( $fields as $field ) {
	$data->{$field} = $club->{'_anwpfl_' . $field};
}

/**
 * Filter: anwpfl/tmpl-club/data_fields
 *
 * @since 0.7.5
 *
 * @param array   $data
 * @param WP_Post $club
 */
$data = (object) apply_filters( 'anwpfl/tmpl-club/data_fields', $data, $club );
?>
<div class="anwp-b-wrap club club__inner club-<?php echo (int) $club->ID; ?>">
	<?php
	$club_sections = wp_parse_slug_list( $data->sections );

	foreach ( $club_sections as $section ) {
		anwp_football_leagues()->load_partial( $data, 'club/club-' . sanitize_key( $section ) );
	}
	?>
</div>
