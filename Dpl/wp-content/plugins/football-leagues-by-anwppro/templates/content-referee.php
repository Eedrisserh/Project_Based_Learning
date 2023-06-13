<?php
/**
 * The Template for displaying referee content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-referee.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.7.3
 *
 * @version       0.11.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prepare data
$staff_id = get_the_ID();

$staff_data = [
	'staff_id' => $staff_id,
];

/**
 * Hook: anwpfl/tmpl-referee/before_wrapper
 *
 * @since 0.7.5
 *
 * @param int $staff_id
 */
do_action( 'anwpfl/tmpl-referee/before_wrapper', $staff_id );
?>
	<div class="anwp-b-wrap referee referee__inner">
		<?php
		$staff_sections = [
			'header',
			'fixtures',
			'finished',
			'description',
		];

		/**
		 * Filter: anwpfl/tmpl-referee/sections
		 *
		 * @param array $staff_sections
		 * @param int   $staff_id
		 *
		 * @since 0.11.14
		 *
		 */
		$staff_sections = apply_filters( 'anwpfl/tmpl-referee/sections', $staff_sections, $staff_id );

		foreach ( $staff_sections as $section ) {
			anwp_football_leagues()->load_partial( $staff_data, 'referee/referee-' . sanitize_key( $section ) );
		}
		?>
	</div>
<?php
/**
 * Hook: anwpfl/tmpl-referee/after_wrapper
 *
 * @param int $staff_id
 *
 * @since 0.7.5
 *
 */
do_action( 'anwpfl/tmpl-referee/after_wrapper', $staff_id );
