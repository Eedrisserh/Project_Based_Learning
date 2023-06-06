<?php
/**
 * The Template for displaying Widget :: Player.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-player.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data->context = 'widget';

echo anwp_football_leagues()->template->shortcode_loader( 'player', (array) $data ); // WPCS: XSS ok.
