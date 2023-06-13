<?php
/**
 * Health Class
 * AnWP Football Leagues :: Health.
 *
 * @since   0.13.2
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Health {

	/**
	 * Parent plugin class.
	 *
	 * @var    AnWP_Football_Leagues
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @param AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 */
	public function hooks() {
		add_action( 'rest_api_init', [ $this, 'add_rest_routes' ] );
	}

	/**
	 * Register REST routes.
	 *
	 * @since 0.13.2
	 */
	public function add_rest_routes() {

		register_rest_route(
			'anwpfl/health-test',
			'/(?P<action_slug>[a-z_]+)/',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'process_plugin_health_test' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);

		register_rest_route(
			'anwpfl/health-fix',
			'/(?P<action_slug>[a-z_]+)/',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'process_plugin_health_fix' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	/**
	 * Handle plugin health tests
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_REST_Response
	 */
	public function process_plugin_health_test( WP_REST_Request $request ) {

		// Get Request params
		$params = $request->get_params();

		// Check API Method exists
		if ( empty( $params['action_slug'] ) ) {
			return new WP_Error( 'rest_invalid', 'Incorrect Test', [ 'status' => 400 ] );
		}

		$available_actions = [
			'items_in_trash',
			'plugin_tables',
			'games_without_competition',
		];

		$maybe_method_name = 'test_health_' . $params['action_slug'];

		if ( in_array( $params['action_slug'], $available_actions, true ) && method_exists( $this, $maybe_method_name ) ) {
			return $this->{$maybe_method_name}();
		}

		/**
		 * Handle additional slugs
		 *
		 * @param string $action_slug
		 *
		 * @since 0.13.2
		 */
		$action_handler_result = apply_filters( 'anwpfl/health/process_plugin_health_test', false, $params['action_slug'] );

		if ( $action_handler_result && ! empty( $action_handler_result['result'] ) ) {
			return rest_ensure_response( $action_handler_result );
		}

		return new WP_Error( 'rest_invalid', 'Test Not Found', [ 'status' => 400 ] );
	}

	/**
	 * Handle plugin health fixes
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_REST_Response
	 */
	public function process_plugin_health_fix( WP_REST_Request $request ) {

		// Get Request params
		$params = $request->get_params();

		// Check API Method exists
		if ( empty( $params['action_slug'] ) ) {
			return new WP_Error( 'rest_invalid', 'Incorrect Test', [ 'status' => 400 ] );
		}

		$available_actions = [];

		$maybe_method_name = 'test_fix_' . $params['action_slug'];

		if ( in_array( $params['action_slug'], $available_actions, true ) && method_exists( $this, $maybe_method_name ) ) {
			return $this->{$maybe_method_name}( $params );
		}

		/**
		 * Handle additional fix slugs
		 *
		 * @param string $action_slug
		 *
		 * @since 0.13.2
		 */
		$action_handler_result = apply_filters( 'anwpfl/health/process_plugin_health_fix', false, $params['action_slug'], $params );

		if ( $action_handler_result && ! empty( $action_handler_result['result'] ) ) {
			return rest_ensure_response( $action_handler_result );
		}

		return new WP_Error( 'rest_invalid', 'Fix Handler Not Found', [ 'status' => 400 ] );
	}

	/**
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	private function test_health_items_in_trash() {

		$output_text = [];
		$fix_links   = [];

		/*
		|--------------------------------------------------------------------
		| Check Games in Trash
		|--------------------------------------------------------------------
		*/
		$trash_game_ids = get_posts(
			[
				'numberposts' => - 1,
				'post_type'   => 'anwp_match',
				'post_status' => 'trash',
				'fields'      => 'ids',
			]
		);

		if ( ! empty( $trash_game_ids ) && count( $trash_game_ids ) ) {
			$output_text[] = '<span class="anwp-text-red-500">You have some Games in Trash (' . count( $trash_game_ids ) . ')</span>';
			$fix_links[]   = [
				'text' => 'Fix Games',
				'link' => admin_url( 'edit.php?post_status=trash&post_type=anwp_match' ),
			];
		} else {
			$output_text[] = '<span class="anwp-text-green-500">No Games in Trash - OK</span>';
		}

		/*
		|--------------------------------------------------------------------
		| Check Competitions in Trash
		|--------------------------------------------------------------------
		*/
		$trash_competition_ids = get_posts(
			[
				'numberposts' => - 1,
				'post_type'   => 'anwp_competition',
				'post_status' => 'trash',
				'fields'      => 'ids',
			]
		);

		if ( ! empty( $trash_competition_ids ) && count( $trash_competition_ids ) ) {
			$output_text[] = '<span class="anwp-text-red-500">You have some Competitions in Trash (' . count( $trash_competition_ids ) . ')</span>';
			$fix_links[]   = [
				'text' => 'Fix Competitions',
				'link' => admin_url( 'edit.php?post_status=trash&post_type=anwp_competition' ),
			];
		} else {
			$output_text[] = '<span class="anwp-text-green-500">No Competitions in Trash - OK</span>';
		}

		/*
		|--------------------------------------------------------------------
		| Check Clubs in Trash
		|--------------------------------------------------------------------
		*/
		$trash_club_ids = get_posts(
			[
				'numberposts' => - 1,
				'post_type'   => 'anwp_club',
				'post_status' => 'trash',
				'fields'      => 'ids',
			]
		);

		if ( ! empty( $trash_club_ids ) && count( $trash_club_ids ) ) {
			$output_text[] = '<span class="anwp-text-red-500">You have some Clubs in Trash (' . count( $trash_club_ids ) . ')</span>';
			$fix_links[]   = [
				'text' => 'Fix Clubs',
				'link' => admin_url( 'edit.php?post_status=trash&post_type=anwp_club' ),
			];
		} else {
			$output_text[] = '<span class="anwp-text-green-500">No Clubs in Trash - OK</span>';
		}

		/*
		|--------------------------------------------------------------------
		| Prepare Output
		|--------------------------------------------------------------------
		*/
		return rest_ensure_response(
			[
				'status'   => count( $fix_links ) ? 'problems' : 'ok',
				'fix_data' => [
					'text'      => implode( '<br>', $output_text ),
					'link_type' => 'link',
					'links'     => $fix_links,
				],
			]
		);
	}

	private function test_health_plugin_tables() {

		global $wpdb;

		$database_tables = $wpdb->get_col(
			$wpdb->prepare(
				'
				SELECT table_name
				FROM information_schema.TABLES
				WHERE table_schema = %s
				',
				DB_NAME
			)
		);

		$required_tables = [
			$wpdb->prefix . 'anwpfl_missing_players',
			$wpdb->prefix . 'anwpfl_players',
			$wpdb->prefix . 'anwpfl_matches',
		];

		$missing_tables = array_diff( $required_tables, $database_tables );

		/*
		|--------------------------------------------------------------------
		| Prepare Output
		|--------------------------------------------------------------------
		*/
		if ( count( $missing_tables ) ) {
			$output_text = '<span class="anwp-text-red-500">Some plugin tables don\'t exist. Ask plugin support for help.</span>';
		} else {
			$output_text = '<span class="anwp-text-green-500">Plugin tables - OK</span>';
		}

		return rest_ensure_response(
			[
				'status'   => count( $missing_tables ) ? 'problems' : 'ok',
				'fix_data' => [
					'text'      => $output_text,
					'link_type' => 'link',
					'links'     => [],
				],
			]
		);
	}

	private function test_health_games_without_competition() {

		global $wpdb;

		/*
		|--------------------------------------------------------------------
		| Get Competition IDs by games
		|--------------------------------------------------------------------
		*/
		$competition_games_ids = $wpdb->get_col(
			"
			SELECT DISTINCT competition_id
			FROM {$wpdb->prefix}anwpfl_matches
			"
		);

		$competition_games_ids = array_unique( array_map( 'absint', $competition_games_ids ) );

		/*
		|--------------------------------------------------------------------
		| Get all site Competition IDs
		|--------------------------------------------------------------------
		*/
		$all_competition_ids = get_posts(
			[
				'numberposts'   => - 1,
				'post_type'     => 'anwp_competition',
				'post_status'   => [ 'publish', 'stage_secondary' ],
				'cache_results' => false,
				'fields'        => 'ids',
			]
		);

		$all_competition_ids = array_unique( array_map( 'absint', $all_competition_ids ) );

		/*
		|--------------------------------------------------------------------
		| Output
		|--------------------------------------------------------------------
		*/
		$missing_ids = array_diff( $competition_games_ids, $all_competition_ids );
		$fix_links   = [];

		if ( ! empty( $missing_ids ) && count( $missing_ids ) ) {
			$output_text = '<span class="anwp-text-red-500">You have some Games with invalid Competition ID (' . count( $missing_ids ) . ')</span>';

			foreach ( $missing_ids as $missing_id ) {
				$fix_links[] = [
					'text' => 'Fix ID ' . absint( $missing_id ),
					'link' => admin_url( 'edit.php?s&post_status=all&post_type=anwp_match&action=-1&filter_action=Filter&_anwpfl_current_competition=' . absint( $missing_id ) ),
				];
			}
		} else {
			$output_text = '<span class="anwp-text-green-500">No Games with invalid Competition ID - OK</span>';
		}

		return rest_ensure_response(
			[
				'status'   => count( $missing_ids ) ? 'problems' : 'ok',
				'fix_data' => [
					'text'      => $output_text,
					'link_type' => 'link',
					'links'     => $fix_links,
				],
			]
		);
	}
}
