<?php
/**
 * Shortcodes page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.10.7
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$available_tabs = [ 'howto', 'builder' ];

// phpcs:ignore WordPress.Security.NonceVerification
$shortcode_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'builder';

if ( ! in_array( $shortcode_tab, $available_tabs, true ) ) {
	$shortcode_tab = 'builder';
}
?>
	<div class="anwp-b-wrap">
		<div class="inside px-3 pt-3">
			<nav class="nav-tab-wrapper">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=anwpfl-shortcodes' ) ); ?>"
					class="nav-tab <?php echo esc_attr( 'builder' === $shortcode_tab ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html__( 'Shortcode Builder', 'anwp-football-leagues' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=anwpfl-shortcodes&tab=howto' ) ); ?>"
					class="nav-tab <?php echo esc_attr( 'howto' === $shortcode_tab ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html__( 'How To\'s', 'anwp-football-leagues' ); ?></a>
				<?php
				do_action( 'anwpfl/config/shortcode_extra_tabs' );
				?>
			</nav>
		</div>
	</div>
<?php
switch ( $shortcode_tab ) {
	case 'howto':
		AnWP_Football_Leagues::include_file( 'admin/views/shortcodes-howto' );
		break;

	default:
		AnWP_Football_Leagues::include_file( 'admin/views/shortcodes-builder' );
}
