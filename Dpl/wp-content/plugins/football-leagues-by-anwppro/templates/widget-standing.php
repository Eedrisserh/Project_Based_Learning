<?php
/**
 * The Template for displaying Standing Table Widget.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-standing.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data->context = 'widget';
$data->layout  = 'mini';
$data->title   = '';

echo anwp_football_leagues()->template->shortcode_loader( 'standing', (array) $data ); // WPCS: XSS ok.
