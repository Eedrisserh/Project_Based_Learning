<?php
/**
 * The Template for displaying Clubs.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-clubs.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author          Andrei Strekozov <anwp.pro>
 * @package         AnWP-Football-Leagues/Templates
 * @since           0.4.3
 *
 * @version         0.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check for required data
if ( empty( $data->competition_id ) && empty( $data->include_ids ) ) {
	return;
}

$data->context = 'widget';

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo anwp_football_leagues()->template->shortcode_loader( 'clubs', (array) $data );
