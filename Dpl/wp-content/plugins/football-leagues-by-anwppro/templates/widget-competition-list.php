<?php
/**
 * The Template for displaying Competition List Widget.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-competition-list.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.12.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data->context = 'widget';

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo anwp_football_leagues()->template->shortcode_loader( 'competition_list', (array) $data );
