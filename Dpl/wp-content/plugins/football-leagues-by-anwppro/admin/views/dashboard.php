<?php

/**
 * Dashboard page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.1.0
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Dashboard Here</h1>
