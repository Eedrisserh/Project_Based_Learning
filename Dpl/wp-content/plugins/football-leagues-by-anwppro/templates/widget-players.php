<?php
/**
 * The Template for displaying Widget :: Players.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-players.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data->context = 'widget';
$data->layout  = 'mini';

echo anwp_football_leagues()->template->shortcode_loader( 'players', (array) $data ); // WPCS: XSS ok.
