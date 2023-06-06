<?php
/**
 * The Template for displaying Standing Table content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-standing.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$standing_id = get_the_ID();

// Prepare data
$data = [
	'id'      => $standing_id,
	'title'   => '',
	'context' => 'standing',
];

/**
 * Hook: anwpfl/tmpl-standing/before_wrapper
 *
 * @since 0.7.5
 *
 * @param int $standing_id
 */
do_action( 'anwpfl/tmpl-standing/before_wrapper', $standing_id );

echo anwp_football_leagues()->template->shortcode_loader( 'standing', $data ); // WPCS: XSS ok.

/**
 * Hook: anwpfl/tmpl-standing/after_wrapper
 *
 * @since 0.7.5
 *
 * @param integer $standing_id
 */
do_action( 'anwpfl/tmpl-standing/after_wrapper', $standing_id );
