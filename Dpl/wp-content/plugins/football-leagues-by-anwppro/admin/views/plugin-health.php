<?php
/**
 * Plugin Health page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.13.2
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

$app_id = apply_filters( 'anwpfl/health/vue_app_id', 'anwp-fl-app-plugin-health' );

/*
|--------------------------------------------------------------------
| Prepare plugin actions
|--------------------------------------------------------------------
*/
$plugin_health_actions = [
	[
		'slug'     => 'items_in_trash',
		'title'    => 'Items in Trash',
		'fix_data' => (object) [],
		'status'   => '',
	],
	[
		'slug'     => 'plugin_tables',
		'title'    => 'Plugin Tables',
		'fix_data' => (object) [],
		'status'   => '',
	],
	[
		'slug'     => 'games_without_competition',
		'title'    => 'Games without Competition',
		'fix_data' => (object) [],
		'status'   => '',
	],
];

$plugin_health_actions = apply_filters( 'anwpfl/health/available_actions', $plugin_health_actions );

/*
|--------------------------------------------------------------------
| App Options
|--------------------------------------------------------------------
*/
$plugin_health_app_data = [
	'spinner_url' => admin_url( 'images/spinner.gif' ),
	'actions'     => $plugin_health_actions,
];
?>
<script type="text/javascript">
	var _anwpPluginHealthData = <?php echo wp_json_encode( $plugin_health_app_data ); ?>;
</script>
<div class="wrap anwp-b-wrap">
	<div class="anwp-import-header-block mb-1 pb-1">
		<h1 class="h4 font-weight-normal text-uppercase mb-0"><?php echo esc_html__( 'Plugin Health', 'anwp-football-leagues' ); ?></h1>
	</div>

	<div class="row">
		<div class="col-md-9 anwp-import-api-wrapper">
			<div id="<?php echo esc_attr( $app_id ); ?>"></div>
		</div>
	</div>
</div>
