<?php
/**
 * Tools page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.8.2
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
}

// phpcs:ignore WordPress.Security.NonceVerification
$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
?>
<div class="anwp-b-wrap wrap" id="anwpfl-import-wrapper">

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab text-dark <?php echo esc_attr( '' === $active_tab ? 'nav-tab-active' : '' ); ?>"
			href="<?php echo esc_url( admin_url( 'admin.php?page=anwp-settings-tools' ) ); ?>">
			<?php echo esc_html__( 'Batch Import', 'anwp-football-leagues' ); ?>
		</a>
		<a class="nav-tab text-dark <?php echo esc_attr( 'csv-export' === $active_tab ? 'nav-tab-active' : '' ); ?>"
			href="<?php echo esc_url( admin_url( 'admin.php?page=anwp-settings-tools&tab=csv-export' ) ); ?>">
			<?php echo esc_html__( 'CSV Export', 'anwp-football-leagues' ); ?>
		</a>
	</h2>

	<?php if ( '' === $active_tab ) : ?>
		<?php AnWP_Football_Leagues::include_file( 'admin/views/tools-import' ); ?>
	<?php elseif ( 'csv-export' === $active_tab ) : ?>
		<?php AnWP_Football_Leagues::include_file( 'admin/views/tools-export' ); ?>
	<?php endif; ?>

</div>
