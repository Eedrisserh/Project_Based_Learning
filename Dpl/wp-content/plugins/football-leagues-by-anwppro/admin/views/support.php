<?php
/**
 * Support page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.5.5
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

global $wp_version, $wpdb;

$database_tables = $wpdb->get_results(
	$wpdb->prepare(
		"SELECT table_name AS 'name'
				FROM information_schema.TABLES
				WHERE table_schema = %s
				ORDER BY name ASC;",
		DB_NAME
	)
);

try {
	$matches = get_posts(
		[
			'numberposts' => - 1,
			'post_type'   => 'anwp_match',
			'post_status' => 'publish',
			'fields'      => 'ids',
		]
	);

	$matches_qty = is_array( $matches ) ? count( $matches ) : 0;

	$stats_qty = $wpdb->get_var(
		"
				SELECT COUNT(*)
				FROM {$wpdb->prefix}anwpfl_matches
				"
	);
} catch ( RuntimeException $e ) {
	$matches_qty = 0;
	$stats_qty   = 0;
}
?>

<div class="about-wrap anwp-b-wrap">
	<div class="postbox">
		<div class="inside">
			<h2 class="text-left text-uppercase"><?php echo esc_html__( 'Plugin Support', 'anwp-football-leagues' ); ?></h2>

			<hr>
			<p>
				<?php echo esc_html_x( 'If you find a bug, need help, or would like to request a feature, please visit', 'support page', 'anwp-football-leagues' ); ?>:
				<br>
				- <a href="https://anwppro.userecho.com/communities/1-football-leagues" target="_blank"><?php echo esc_html_x( 'plugin support forum', 'support page', 'anwp-football-leagues' ); ?></a>
			</p>

			<h4><?php echo esc_html_x( 'Your System Info', 'support page', 'anwp-football-leagues' ); ?></h4>

			<ul>
				<li>============================================</li>
				<li>
					<b>Plugin Version:</b> AnWP Football Leagues <?php echo esc_html( anwp_football_leagues()->version ); ?>
				</li>

				<li>
					<b>WordPress version:</b> <?php echo esc_html( $wp_version ); ?>
				</li>

				<li>
					<b>Server Time:</b> <?php echo esc_html( date_default_timezone_get() ); ?>
				</li>

				<li>
					<b>WP Time:</b> <?php echo esc_html( get_option( 'timezone_string' ) ); ?>
				</li>

				<li>
					<b>Current Date:</b> <?php echo esc_html( date_i18n( 'Y-m-d' ) ); ?>
				</li>

				<li>
					<b>Site Locale:</b> <?php echo esc_html( get_locale() ); ?>
				</li>

				<li>
					<b>Plugin DB version:</b> <?php echo esc_html( get_option( 'anwpfl_db_version' ) ); ?>
				</li>

				<li>
					<b>Statistic records:</b> (matches/stats - <?php echo intval( $matches_qty ); ?>/<?php echo intval( $stats_qty ); ?>)
				</li>

				<li>
					<b>PHP version:</b> <?php echo esc_html( phpversion() ); ?>
				</li>

				<li>
					<b><?php echo esc_html_x( 'Active Plugins', 'support page', 'anwp-football-leagues' ); ?>:</b>
					<?php
					foreach ( get_option( 'active_plugins' ) as $value ) {
						$string = explode( '/', $value );
						echo '<br>--- ' . esc_html( $string[0] );
					}
					?>
				</li>
				<li>
					<b><?php echo esc_html_x( 'List of DB tables', 'support page', 'anwp-football-leagues' ); ?>:</b><br>
					<?php
					if ( ! empty( $database_tables ) && is_array( $database_tables ) ) {
						$database_tables = wp_list_pluck( $database_tables, 'name' );

						if ( is_array( $database_tables ) ) {
							echo esc_html( implode( ', ', $database_tables ) );
						}
					}
					?>
				</li>
				<li>============================================</li>
			</ul>
		</div>
	</div>
</div>
