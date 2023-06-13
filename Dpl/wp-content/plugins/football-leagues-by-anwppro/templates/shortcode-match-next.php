<?php
/**
 * The Template for displaying Match Next Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-match-next.php.
 *
 * @var object $data - Object with shortcode args.
 *
 * @author          Andrei Strekozov <anwp.pro>
 * @package         AnWP-Football-Leagues/Templates
 * @since           0.12.7
 *
 * @version         0.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo anwp_football_leagues()->template->widget_loader( 'next-match', (array) $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
