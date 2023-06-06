<?php
/**
 * The Template for displaying Club >> Squad Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-squad.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.4
 *
 * @version       0.10.3
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
 * Hook: anwpfl/tmpl-club/before_squad
 *
 * @since 0.7.5
 *
 * @param WP_Post $club
 * @param integer $season_id
 */
do_action( 'anwpfl/tmpl-club/before_squad', $club, $data->season_id );

/**
 * Filter: anwpfl/tmpl-club/render_squad
 *
 * @since 0.7.5
 *
 * @param bool
 * @param WP_Post $club
 * @param integer $season_id
 */
if ( ! apply_filters( 'anwpfl/tmpl-club/render_squad', true, $club, $data->season_id ) ) {
	return;
}
?>
	<div class="club__squad club-section anwp-section">

		<?php
		/**
		 * Filter: anwpfl/tmpl-club/squad_layout
		 *
		 * @since 0.7.5
		 *
		 * @param bool
		 * @param WP_Post $club
		 * @param integer $season_id
		 */
		$squad_layout = apply_filters( 'anwpfl/tmpl-club/squad_layout', AnWPFL_Options::get_value( 'club_squad_layout' ), $club, $data->season_id );

		echo anwp_football_leagues()->template->shortcode_loader(
			'squad',
			[
				'club_id'         => $data->club_id,
				'season_id'       => $data->season_id,
				'season_dropdown' => 'show',
				'layout'          => $squad_layout,
			]
		); // WPCS: XSS ok.
		?>
	</div>
<?php
/**
 * Hook: anwpfl/tmpl-club/after_squad
 *
 * @since 0.7.5
 *
 * @param WP_Post $club
 * @param integer $season_id
 */
do_action( 'anwpfl/tmpl-club/after_squad', $club, $data->season_id );
