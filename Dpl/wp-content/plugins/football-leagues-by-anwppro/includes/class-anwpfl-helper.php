<?php
/**
 * AnWP Football Leagues :: Helper.
 *
 * @since   0.2.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Helper class.
 *
 * @since 0.1.0
 */
class AnWPFL_Helper {

	/**
	 * Parent plugin class.
	 *
	 * @var AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
	 *
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since 0.3.0
	 */
	public function hooks() {

		add_action( 'rest_api_init', [ $this, 'add_rest_routes' ] );

		add_action( 'wp_ajax_anwp_fl_selector_data', [ $this, 'get_selector_data' ] );
		add_action( 'wp_ajax_anwp_fl_selector_initial', [ $this, 'get_selector_initial' ] );
	}

	/**
	 * Register REST routes.
	 *
	 * @since 0.9.2
	 */
	public function add_rest_routes() {
		register_rest_route(
			'anwpfl/v1',
			'/helper/recalculate-matches-stats',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'recalculate_matches_statistic' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);

		register_rest_route(
			'anwpfl/v1',
			'/import/(?P<type>[a-z]+)/',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'save_import_data' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	/**
	 * Callback for the rest route "/helper/recalculate-matches-stats/"
	 *
	 * @param WP_REST_Request $request
	 *
	 * @since 0.3.0 (2018-02-06)
	 * @since 0.5.0 (2018-03-10) Added reset for players table
	 * @return mixed
	 */
	public function recalculate_matches_statistic( WP_REST_Request $request ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Access Denied !!!' );
		}

		global $wpdb;

		$params = $request->get_query_params();
		$option = empty( $params['option'] ) ? 0 : intval( $params['option'] );

		$rows_affected = 0;

		if ( empty( $option ) ) {

			// Reset matches statistics
			$wpdb->query( 'TRUNCATE ' . $wpdb->prefix . 'anwpfl_matches' );
			$wpdb->query( 'TRUNCATE ' . $wpdb->prefix . 'anwpfl_players' );

			// Get all fixed matches
			$matches = get_posts(
				[
					'numberposts' => - 1,
					'post_type'   => 'anwp_match',
					'post_status' => 'publish',
				]
			);

			$wpdb->query( 'SET autocommit = 0;' );

			foreach ( $matches as $match ) {

				if ( 'true' === get_post_meta( $match->ID, '_anwpfl_fixed', true ) ) {

					// Prepare data
					$data = [
						'match_id'             => $match->ID,
						'match_datetime'       => get_post_meta( $match->ID, '_anwpfl_match_datetime', true ),
						'competition'          => get_post_meta( $match->ID, '_anwpfl_competition', true ),
						'competition_group'    => get_post_meta( $match->ID, '_anwpfl_competition_group', true ),
						'aggtext'              => get_post_meta( $match->ID, '_anwpfl_aggtext', true ),
						'league'               => get_post_meta( $match->ID, '_anwpfl_league', true ),
						'season'               => get_post_meta( $match->ID, '_anwpfl_season', true ),
						'club_home'            => get_post_meta( $match->ID, '_anwpfl_club_home', true ),
						'club_away'            => get_post_meta( $match->ID, '_anwpfl_club_away', true ),
						'status'               => get_post_meta( $match->ID, '_anwpfl_status', true ),
						'penalty'              => get_post_meta( $match->ID, '_anwpfl_penalty', true ),
						'extra_time'           => get_post_meta( $match->ID, '_anwpfl_extra_time', true ),
						'attendance'           => get_post_meta( $match->ID, '_anwpfl_attendance', true ),
						'stadium'              => get_post_meta( $match->ID, '_anwpfl_stadium', true ),
						'matchweek'            => get_post_meta( $match->ID, '_anwpfl_matchweek', true ),
						'priority'             => get_post_meta( $match->ID, '_anwpfl_match_priority', true ),
						'players_home_line_up' => get_post_meta( $match->ID, '_anwpfl_players_home_line_up', true ),
						'players_away_line_up' => get_post_meta( $match->ID, '_anwpfl_players_away_line_up', true ),
						'players_home_subs'    => get_post_meta( $match->ID, '_anwpfl_players_home_subs', true ),
						'players_away_subs'    => get_post_meta( $match->ID, '_anwpfl_players_away_subs', true ),
					];

					$stats_json    = get_post_meta( $match->ID, '_anwpfl_match_stats', true );
					$data['stats'] = null === json_decode( $stats_json ) ? [] : json_decode( $stats_json );

					$events_json    = get_post_meta( $match->ID, '_anwpfl_match_events', true );
					$data['events'] = null === json_decode( $events_json ) ? [] : json_decode( $events_json );

					$rows_affected += (int) $this->plugin->match->save_match_statistics( $data );
				}
			}

			$wpdb->query( 'COMMIT;' );
			$wpdb->query( 'SET autocommit = 1;' );
		} elseif ( $option < 0 ) {

			$option = absint( $option );

			$matches_ids = get_posts(
				[
					'numberposts' => - 1,
					'post_type'   => 'anwp_match',
					'post_status' => 'publish',
					'fields'      => 'ids',
				]
			);

			$stats_ids = $wpdb->get_col(
				"
				SELECT match_id
				FROM {$wpdb->prefix}anwpfl_matches
				"
			);

			$stats_ids = array_map( 'intval', $stats_ids );

			$ids = array_diff( $matches_ids, $stats_ids );
			$ids = array_slice( $ids, 0, $option );

			// Get all fixed matches
			$matches = get_posts(
				[
					'numberposts' => - 1,
					'post_type'   => 'anwp_match',
					'post_status' => 'publish',
					'include'     => $ids,
				]
			);

			foreach ( $matches as $match ) {

				if ( 'true' === get_post_meta( $match->ID, '_anwpfl_fixed', true ) ) {
					try {
						// Prepare data
						$data = [
							'match_id'             => $match->ID,
							'match_datetime'       => get_post_meta( $match->ID, '_anwpfl_match_datetime', true ),
							'competition'          => get_post_meta( $match->ID, '_anwpfl_competition', true ),
							'competition_group'    => get_post_meta( $match->ID, '_anwpfl_competition_group', true ),
							'aggtext'              => get_post_meta( $match->ID, '_anwpfl_aggtext', true ),
							'league'               => get_post_meta( $match->ID, '_anwpfl_league', true ),
							'season'               => get_post_meta( $match->ID, '_anwpfl_season', true ),
							'club_home'            => get_post_meta( $match->ID, '_anwpfl_club_home', true ),
							'club_away'            => get_post_meta( $match->ID, '_anwpfl_club_away', true ),
							'status'               => get_post_meta( $match->ID, '_anwpfl_status', true ),
							'penalty'              => get_post_meta( $match->ID, '_anwpfl_penalty', true ),
							'extra_time'           => get_post_meta( $match->ID, '_anwpfl_extra_time', true ),
							'attendance'           => get_post_meta( $match->ID, '_anwpfl_attendance', true ),
							'stadium'              => get_post_meta( $match->ID, '_anwpfl_stadium', true ),
							'matchweek'            => get_post_meta( $match->ID, '_anwpfl_matchweek', true ),
							'priority'             => get_post_meta( $match->ID, '_anwpfl_match_priority', true ),
							'players_home_line_up' => get_post_meta( $match->ID, '_anwpfl_players_home_line_up', true ),
							'players_away_line_up' => get_post_meta( $match->ID, '_anwpfl_players_away_line_up', true ),
							'players_home_subs'    => get_post_meta( $match->ID, '_anwpfl_players_home_subs', true ),
							'players_away_subs'    => get_post_meta( $match->ID, '_anwpfl_players_away_subs', true ),
						];

						$stats_json    = get_post_meta( $match->ID, '_anwpfl_match_stats', true );
						$data['stats'] = null === json_decode( $stats_json ) ? [] : json_decode( $stats_json );

						$events_json    = get_post_meta( $match->ID, '_anwpfl_match_events', true );
						$data['events'] = null === json_decode( $events_json ) ? [] : json_decode( $events_json );

						$rows_affected += (int) $this->plugin->match->save_match_statistics( $data );
					} catch ( RuntimeException $e ) {
						continue;
					}
				}
			}
		}

		return $rows_affected;
	}

	/**
	 * Handle import Rest request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_REST_Response
	 * @since 0.8.2
	 */
	public function save_import_data( WP_REST_Request $request ) {

		$params = $request->get_params();
		$insert = false;

		switch ( $params['type'] ) {

			case 'clubs':
				$insert = $this->import_clubs( $params );
				break;

			case 'players':
				$insert = $this->import_players( $params );
				break;

			case 'referees':
				$insert = $this->import_referees( $params );
				break;
		}

		return $insert
			? rest_ensure_response( esc_html__( 'Successfully saved items', 'anwp-football-leagues' ) . ': ' . $insert )
			: rest_ensure_response( new WP_Error( 'rest_invalid', esc_html__( 'Saving Data Error', 'anwp-football-leagues' ), [ 'status' => 400 ] ) );
	}

	/**
	 * Import Clubs.
	 *
	 * @param $params
	 *
	 * @return bool|false|int
	 * @since 0.8.2
	 */
	protected function import_clubs( $params ) {

		$insert_qty      = 0;
		$current_user_id = get_current_user_id();
		$current_time    = current_time( 'mysql' );

		// Prepare mapping data
		$mapping_countries = array_flip( $this->plugin->data->cb_get_countries() );

		if ( ! empty( $params['table'] ) && is_array( $params['table'] ) && ! empty( $params['headers'] ) && is_array( $params['headers'] ) ) {
			foreach ( $params['table'] as $row ) {

				$club_data = [
					'post_title'   => '',
					'post_content' => '',
					'post_type'    => 'anwp_club',
					'post_status'  => 'publish',
					'post_author'  => $current_user_id,
					'meta_input'   => [
						'_anwpfl_import_time' => $current_time,
					],
				];

				$mapping = [
					'country' => '',
				];

				foreach ( $params['headers'] as $header_index => $header ) {
					switch ( $header ) {
						case 'club_title':
							$club_data['post_title'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'abbreviation':
							$club_data['meta_input']['_anwpfl_abbr'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'city':
							$club_data['meta_input']['_anwpfl_city'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'address':
							$club_data['meta_input']['_anwpfl_address'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'website':
							$club_data['meta_input']['_anwpfl_website'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'founded':
							$club_data['meta_input']['_anwpfl_founded'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_1':
							$club_data['meta_input']['_anwpfl_custom_title_1'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_2':
							$club_data['meta_input']['_anwpfl_custom_title_2'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_3':
							$club_data['meta_input']['_anwpfl_custom_title_3'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_1':
							$club_data['meta_input']['_anwpfl_custom_value_1'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_2':
							$club_data['meta_input']['_anwpfl_custom_value_2'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_3':
							$club_data['meta_input']['_anwpfl_custom_value_3'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'country':
							$mapping['country'] = sanitize_text_field( $row[ $header_index ] );
							break;
					}
				}

				// Parse mapping data
				if ( ! empty( $mapping['country'] ) && isset( $mapping_countries[ $mapping['country'] ] ) ) {
					$club_data['meta_input']['_anwpfl_nationality'] = $mapping_countries[ $mapping['country'] ];
				}

				if ( trim( $club_data['post_title'] ) && wp_insert_post( $club_data ) ) {
					$insert_qty ++;
				}
			}
		}

		return $insert_qty;
	}

	/**
	 * Import Clubs.
	 *
	 * @param $params
	 *
	 * @return bool|false|int
	 * @since 0.8.2
	 */
	protected function import_players( $params ) {

		$insert_qty      = 0;
		$current_user_id = get_current_user_id();
		$current_time    = current_time( 'mysql' );

		// Prepare mapping data
		$mapping_countries = array_flip( $this->plugin->data->cb_get_countries() );
		$mapping_positions = array_change_key_case( array_flip( $this->plugin->data->get_positions() ) );
		$mapping_clubs     = array_flip( $this->plugin->club->get_clubs_options() );

		if ( ! empty( $params['table'] ) && is_array( $params['table'] ) && ! empty( $params['headers'] ) && is_array( $params['headers'] ) ) {
			foreach ( $params['table'] as $row ) {

				$player_data = [
					'post_title'   => '',
					'post_content' => '',
					'post_type'    => 'anwp_player',
					'post_status'  => 'publish',
					'post_author'  => $current_user_id,
					'meta_input'   => [
						'_anwpfl_import_time' => $current_time,
					],
				];

				$mapping = [
					'country'      => [],
					'position'     => '',
					'current_club' => '',
				];

				foreach ( $params['headers'] as $header_index => $header ) {
					switch ( $header ) {
						case 'player_name':
							$player_data['post_title'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'short_name':
							$player_data['meta_input']['_anwpfl_short_name'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'weight':
							$player_data['meta_input']['_anwpfl_weight'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'height':
							$player_data['meta_input']['_anwpfl_height'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'position':
							$mapping['position'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'current_club':
							$mapping['current_club'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'place_of_birth':
							$player_data['meta_input']['_anwpfl_place_of_birth'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'date_of_birth':
							$player_data['meta_input']['_anwpfl_date_of_birth'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'nationality_1':
						case 'nationality_2':
							$mapping['country'][] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_1':
							$player_data['meta_input']['_anwpfl_custom_title_1'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_2':
							$player_data['meta_input']['_anwpfl_custom_title_2'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_title_3':
							$player_data['meta_input']['_anwpfl_custom_title_3'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_1':
							$player_data['meta_input']['_anwpfl_custom_value_1'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_2':
							$player_data['meta_input']['_anwpfl_custom_value_2'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'custom_value_3':
							$player_data['meta_input']['_anwpfl_custom_value_3'] = sanitize_text_field( $row[ $header_index ] );
							break;
					}
				}

				// Parse mapping data
				if ( ! empty( $mapping['country'] ) ) {
					foreach ( $mapping['country'] as $country ) {
						if ( isset( $mapping_countries[ $country ] ) ) {
							$player_data['meta_input']['_anwpfl_nationality'][] = $mapping_countries[ $country ];
						}
					}
				}

				if ( ! empty( $mapping['position'] ) && isset( $mapping_positions[ $mapping['position'] ] ) ) {
					$player_data['meta_input']['_anwpfl_position'] = $mapping_positions[ $mapping['position'] ];
				}

				if ( ! empty( $mapping['current_club'] ) && isset( $mapping_clubs[ $mapping['current_club'] ] ) ) {
					$player_data['meta_input']['_anwpfl_current_club'] = $mapping_clubs[ $mapping['current_club'] ];
				}

				if ( trim( $player_data['post_title'] ) && wp_insert_post( $player_data ) ) {
					$insert_qty ++;
				}
			}
		}

		return $insert_qty;
	}

	/**
	 * Import Referees.
	 *
	 * @param $params
	 *
	 * @return bool|false|int
	 * @since 0.11.13
	 */
	protected function import_referees( $params ) {

		$insert_qty      = 0;
		$current_user_id = get_current_user_id();
		$current_time    = current_time( 'mysql' );

		// Prepare mapping data
		$mapping_countries = array_flip( $this->plugin->data->cb_get_countries() );

		if ( ! empty( $params['table'] ) && is_array( $params['table'] ) && ! empty( $params['headers'] ) && is_array( $params['headers'] ) ) {
			foreach ( $params['table'] as $row ) {

				$player_data = [
					'post_title'   => '',
					'post_content' => '',
					'post_type'    => 'anwp_referee',
					'post_status'  => 'publish',
					'post_author'  => $current_user_id,
					'meta_input'   => [
						'_anwpfl_import_time' => $current_time,
					],
				];

				$mapping = [
					'country' => [],
				];

				foreach ( $params['headers'] as $header_index => $header ) {
					switch ( $header ) {
						case 'referee_name':
							$player_data['post_title'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'short_name':
							$player_data['meta_input']['_anwpfl_short_name'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'job_title':
							$player_data['meta_input']['_anwpfl_job_title'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'place_of_birth':
							$player_data['meta_input']['_anwpfl_place_of_birth'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'date_of_birth':
							$player_data['meta_input']['_anwpfl_date_of_birth'] = sanitize_text_field( $row[ $header_index ] );
							break;

						case 'nationality_1':
						case 'nationality_2':
							$mapping['country'][] = sanitize_text_field( $row[ $header_index ] );
							break;
					}
				}

				// Parse mapping data
				if ( ! empty( $mapping['country'] ) ) {
					foreach ( $mapping['country'] as $country ) {
						if ( isset( $mapping_countries[ $country ] ) ) {
							$player_data['meta_input']['_anwpfl_nationality'][] = $mapping_countries[ $country ];
						}
					}
				}

				if ( trim( $player_data['post_title'] ) && wp_insert_post( $player_data ) ) {
					$insert_qty ++;
				}
			}
		}

		return $insert_qty;
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.2.0 (2018-01-05)
	 *
	 * @param  string $field Field to get.
	 *
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {

		if ( property_exists( $this, $field ) ) {
			return $this->$field;
		}

		throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
	}

	/**
	 * Validate datetime.
	 * From - https://secure.php.net/manual/en/function.checkdate.php#113205
	 *
	 * @param        $date
	 * @param string $format
	 *
	 * @since 2018-01-22
	 * @return bool
	 */
	public function validate_date( $date, $format = 'Y-m-d H:i:s' ) {
		$d = DateTime::createFromFormat( $format, $date );

		return $d && $d->format( $format ) === $date;
	}

	/**
	 * Prepare events for rendering in match.
	 * Method sorts and groups events by type.
	 *
	 * @param $events
	 *
	 * @since 0.3.0 (2018-02-08)
	 * @since 0.8.2 (2018-11-13) Fixed error in sorting with penalty shootout.
	 * @return array
	 */
	public function parse_match_events( $events ) {

		$output = [
			'goals'            => [],
			'cards'            => [],
			'subs'             => [],
			'players'          => [],
			'missed_penalty'   => [],
			'penalty_shootout' => [],
		];

		// Prepare Penalty Shootout first
		foreach ( $events as $e ) {
			if ( ! empty( $e->player ) && 'penalty_shootout' === $e->type ) {
				$output['penalty_shootout'][] = $e;
			}
		}

		// Sort events
		usort(
			$events,
			function ( $a, $b ) {
				return intval( $a->minute ) - intval( $b->minute );
			}
		);

		// phpcs:disable WordPress.NamingConventions
		foreach ( $events as $e ) {

			if ( isset( $e->club ) ) {
				$e->club = intval( $e->club );
			}

			if ( ! empty( $e->player ) ) {
				switch ( $e->type ) {
					case 'goal':
						$output['goals'][] = $e;

						// add data to players array
						$goal_type = 'goal';

						if ( 'yes' === $e->ownGoal ) {
							$goal_type = 'goal_own';
						} elseif ( 'yes' === $e->fromPenalty ) {
							$goal_type = 'goal_penalty';
						}

						$output['players'][ $e->player ][] = $goal_type;
						break;

					case 'card':
						$output['cards'][] = $e;

						// add data to players array
						$output['players'][ $e->player ][] = 'card_' . $e->card;
						break;

					case 'substitute':
						$output['subs'][] = $e;

						// add data to players array
						$output['players'][ $e->player ][]    = 'subs_in';
						$output['players'][ $e->playerOut ][] = 'subs_out';
						break;

					case 'missed_penalty':
						$output['missed_penalty'][] = $e;
						break;
				}
			}
		}

		// phpcs:enable WordPress.NamingConventions
		return $output;
	}

	/**
	 * Rendering season dropdown.
	 *
	 * @param int    $season_id
	 * @param bool   $echo
	 * @param string $class
	 * @param array  $filter (player|club)
	 *
	 * @return string
	 * @since 0.5.0 (2018-03-13)
	 */
	public function season_dropdown( $season_id, $echo = true, $class = '', $filter = [] ) {

		// Get all season options
		$season_options = anwp_football_leagues()->season->get_season_slug_options();

		if ( count( $season_options ) < 2 ) {
			return '';
		}

		// Filter season options
		$season_options = $this->filter_season_list( $season_options, $filter );

		if ( 'yes' === AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			if ( ! empty( $filter ) && ! empty( $filter['context'] ) && absint( $filter['id'] ) ) {
				if ( 'player' === $filter['context'] ) {
					$active_season_id = anwp_football_leagues()->get_active_player_season( $filter['id'] );
				} elseif ( 'club' === $filter['context'] ) {
					$active_season_id = anwp_football_leagues()->get_active_club_season( $filter['id'] );
				} elseif ( 'stadium' === $filter['context'] ) {
					$active_season_id = anwp_football_leagues()->get_active_stadium_season( $filter['id'] );
				}
			}
		}

		if ( empty( $active_season_id ) ) {
			$active_season_id = anwp_football_leagues()->get_active_season();
		}

		$active_season  = anwp_football_leagues()->season->get_season_slug_by_id( $active_season_id );
		$current_season = intval( $active_season_id ) === intval( $season_id )
			? $active_season
			: anwp_football_leagues()->season->get_season_slug_by_id( $season_id );

		ob_start();
		?>
		<select class="custom-select custom-select-sm anwp-season-dropdown <?php echo esc_attr( $class ); ?>">
			<?php
			foreach ( $season_options as $s ) :
				$data_url = $s['slug'] === $active_season ? remove_query_arg( 'season' ) : add_query_arg( 'season', $s['slug'] );
				?>
				<option <?php selected( $s['slug'], $current_season ); ?> data-href="<?php echo esc_url( $data_url ); ?>" value="<?php echo esc_attr( $s['slug'] ); ?>"><?php echo esc_attr( $s['name'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		$output = ob_get_clean();

		/**
		 * Filter season dropdown output.
		 *
		 * @param string $output
		 * @param int    $season_id
		 *
		 * @since 0.10.8
		 */
		$output = apply_filters( 'anwpfl/layout/season_dropdown', $output, $season_id );

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $output;
		}

		return $output;
	}

	/**
	 * Get filtered list of seasons
	 *
	 * @param array $options Available season options
	 * @param array $filter  Filter Options (context and ID)
	 *
	 * @return array
	 * @since 0.11.6
	 */
	public function filter_season_list( $options, $filter ) {

		// Check season filter is set in options
		if ( 'yes' !== AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			return $options;
		}

		$filter = wp_parse_args(
			$filter,
			[
				'context' => '',
				'id'      => 0,
			]
		);

		// Validate filter data
		if ( empty( $filter['context'] ) || ! in_array( $filter['context'], [ 'player', 'club', 'stadium' ], true ) || ! absint( $filter['id'] ) ) {
			return $options;
		}

		$season_slugs     = $this->get_filtered_seasons( $filter['context'], absint( $filter['id'] ) );
		$filtered_options = [];

		foreach ( $options as $option ) {
			if ( in_array( $option['slug'], $season_slugs, true ) ) {
				$filtered_options[] = $option;
			}
		}

		return $filtered_options;
	}

	/**
	 * Get number of matches for selected competition.
	 *
	 * @param string $type
	 * @param int    $id
	 *
	 * @return array
	 * @since 0.10.0
	 */
	public function get_filtered_seasons( $type, $id ) {

		static $options = [
			'players' => [],
			'clubs'   => [],
		];

		// Validate data
		if ( ! in_array( $type, [ 'club', 'player', 'stadium' ], true ) ) {
			return [];
		}

		// Return cached
		if ( ! empty( $options[ $type ][ absint( $id ) ] ) ) {
			return $options[ $type ][ absint( $id ) ];
		}

		global $wpdb;

		if ( 'player' === $type ) {
			$options[ $type ][ absint( $id ) ] = $wpdb->get_col(
				$wpdb->prepare(
					"
					SELECT DISTINCT t.slug
					FROM {$wpdb->prefix}anwpfl_players a
					LEFT JOIN {$wpdb->terms} t ON t.term_id = a.season_id
					WHERE a.player_id = %d
					",
					$id
				)
			);
		} elseif ( 'club' === $type ) {
			$options[ $type ][ absint( $id ) ] = $wpdb->get_col(
				$wpdb->prepare(
					"
					SELECT DISTINCT t.slug
					FROM {$wpdb->prefix}anwpfl_matches a
					LEFT JOIN {$wpdb->terms} t ON t.term_id = a.season_id
					WHERE a.home_club = %d OR a.away_club = %d
					",
					$id,
					$id
				)
			);

			/*
			|--------------------------------------------------------------------
			| Get club squad slugs
			|--------------------------------------------------------------------
			*/
			$squad_season_ids = anwp_football_leagues()->club->get_club_squad_season_ids( $id );

			if ( ! empty( $squad_season_ids ) ) {
				foreach ( $squad_season_ids as $squad_season_id ) {
					$squad_season_slug = anwp_football_leagues()->season->get_season_slug_by_id( $squad_season_id );

					if ( $squad_season_slug && ! in_array( $squad_season_slug, $options[ $type ][ absint( $id ) ], true ) ) {
						$options[ $type ][ absint( $id ) ][] = $squad_season_slug;
					}
				}
			}
		} elseif ( 'stadium' === $type ) {
			$options[ $type ][ absint( $id ) ] = $wpdb->get_col(
				$wpdb->prepare(
					"
					SELECT DISTINCT t.slug
					FROM {$wpdb->prefix}anwpfl_matches a
					LEFT JOIN {$wpdb->terms} t ON t.term_id = a.season_id
					WHERE a.stadium_id = %d
					",
					$id
				)
			);
		}

		return empty( $options[ $type ][ absint( $id ) ] ) ? [] : $options[ $type ][ absint( $id ) ];
	}

	/**
	 * Rendering club form.
	 *
	 * @param int $club_id
	 * @param bool $echo
	 *
	 * @return string
	 * @since 0.5.0 (2018-03-14)
	 */
	public function club_form( $club_id, $echo = true ) {

		global $wpdb;
		$series_map = anwp_football_leagues()->data->get_series();

		// Get latest matches
		$matches = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT *
				FROM {$wpdb->prefix}anwpfl_matches
				WHERE finished = 1 AND ( home_club = %d OR away_club = %d )
				ORDER BY kickoff DESC
				LIMIT 5
				",
				$club_id,
				$club_id
			)
		);

		$matches = array_reverse( $matches );

		ob_start();
		?>
		<div class="club-form">
			<?php
			foreach ( $matches as $match ) :

				$match_result = 'w';
				$result_class = 'bg-success';

				if ( $match->home_goals === $match->away_goals ) {
					$match_result = 'd';
					$result_class = 'bg-warning';
				} elseif ( ( (int) $club_id === (int) $match->home_club && $match->home_goals < $match->away_goals ) || ( (int) $club_id === (int) $match->away_club && $match->home_goals > $match->away_goals ) ) {
					$match_result = 'l';
					$result_class = 'bg-danger';
				}
				?>
				<span class="my-1 d-inline-block club-form__item px-1 text-white <?php echo esc_attr( $result_class ); ?>">
					<?php echo esc_html( mb_strtoupper( $series_map[ $match_result ] ) ); ?>
				</span>
			<?php endforeach; ?>
		</div>
		<?php
		$output = ob_get_clean();

		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $output;
	}

	/**
	 * Converts a string to a bool.
	 * From WOO
	 *
	 * @since 0.7.4
	 * @param string $string String to convert.
	 * @return bool
	 */
	public function string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 1 === $string || 'yes' === $string || 'true' === $string || '1' === $string );
	}

	/**
	 * Get options for target link in the bottom of the widgets.
	 *
	 * @return array
	 * @since 2018-10-04
	 */
	public function get_target_options() {

		static $options = null;

		if ( null === $options ) {

			$options = [];

			// Prepare competitions data
			$competitions = anwp_football_leagues()->competition->get_competitions();
			if ( is_array( $competitions ) ) {
				foreach ( $competitions as $c ) {
					if ( 'secondary' !== $c->multistage ) {
						$options[ $c->id ] = $c->title;
					}
				}
			}

			// Prepare regular pages
			$pages = get_posts(
				[
					'numberposts' => - 1,
					'post_type'   => 'page',
				]
			);

			foreach ( $pages as $page ) {
				$options[ $page->ID ] = $page->post_title;
			}
		}

		return $options;
	}

	/**
	 * Get default player photo.
	 *
	 * @since 0.8.3
	 * @return string
	 */
	public function get_default_player_photo() {

		// Get photo from plugin options
		$photo = AnWPFL_Options::get_value( 'default_player_photo' );

		if ( ! $photo ) {
			$photo = AnWP_Football_Leagues::url( 'public/img/empty_player.png' );
		}

		return $photo;
	}

	/**
	 * Get default club logo.
	 *
	 * @return string
	 * @since 0.10.23
	 */
	public function get_default_club_logo() {

		if ( 'no' === AnWPFL_Options::get_value( 'show_default_club_logo' ) ) {
			return '';
		}

		// Get photo from plugin options
		$logo = AnWPFL_Options::get_value( 'default_club_logo' );

		if ( ! $logo ) {
			$logo = AnWP_Football_Leagues::url( 'public/img/empty_logo.png' );
		}

		return $logo;
	}

	/**
	 * Get Instance Selector Data
	 *
	 * @since 0.11.7
	 */
	public function get_selector_data() {

		// Check if our nonce is set.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax_anwpfl_nonce' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Get POST search data
		$search_data = wp_parse_args(
			isset( $_POST['search_data'] ) ? $this->recursive_sanitize( $_POST['search_data'] ) : [],
			[
				'context'   => '',
				's'         => '',
				'club'      => '',
				'country'   => '',
				'club_away' => '',
				'club_home' => '',
				'season'    => '',
			]
		);

		if ( ! in_array( $search_data['context'], [ 'player', 'club', 'match' ], true ) ) {
			wp_send_json_error();
		}

		$html_output = '';

		switch ( $search_data['context'] ) {
			case 'player':
				$html_output = $this->get_selector_player_data( $search_data );
				break;

			case 'club':
				$html_output = $this->get_selector_club_data( $search_data );
				break;

			case 'match':
				$html_output = $this->get_selector_game_data( $search_data );
				break;
		}

		wp_send_json_success( [ 'html' => $html_output ] );
	}

	/**
	 * Get Instance Selector Data
	 *
	 * @since 0.11.7
	 */
	public function get_selector_initial() {

		// Check if our nonce is set.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax_anwpfl_nonce' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Get context
		$data_context = isset( $_POST['data_context'] ) ? sanitize_text_field( $_POST['data_context'] ) : '';

		if ( ! in_array( $data_context, [ 'player', 'club', 'match' ], true ) ) {
			wp_send_json_error();
		}

		// Initial
		$data_initial = isset( $_POST['initial'] ) ? wp_parse_id_list( $_POST['initial'] ) : [];

		if ( empty( $data_initial ) ) {
			wp_send_json_error();
		}

		$output = '';

		switch ( $data_context ) {
			case 'player':
				$output = $this->get_selector_player_initial( $data_initial );
				break;

			case 'club':
				$output = $this->get_selector_club_initial( $data_initial );
				break;

			case 'match':
				$output = $this->get_selector_match_initial( $data_initial );
				break;
		}

		wp_send_json_success( [ 'items' => $output ] );
	}

	/**
	 * Get selector player initial data.
	 *
	 * @param array $data_initial
	 *
	 * @return array
	 * @since 0.11.7
	 */
	private function get_selector_player_initial( $data_initial ) {

		$query_args = [
			'post_type'               => [ 'anwp_player' ],
			'posts_per_page'          => 30,
			'include'                 => $data_initial,
			'cache_results'           => false,
			'update_post_meta_cache'  => false,
			'update_post_term_cache ' => false,
		];

		$results = get_posts( $query_args );

		if ( empty( $results ) || ! is_array( $results ) ) {
			return [];
		}

		$output = [];

		foreach ( $results as $result_item ) {
			$output[] = [
				'id'   => $result_item->ID,
				'name' => $result_item->post_title,
			];
		}

		return $output;
	}

	/**
	 * Get selector club initial data.
	 *
	 * @param array $data_initial
	 *
	 * @return array
	 * @since 0.11.8
	 */
	private function get_selector_club_initial( $data_initial ) {

		$query_args = [
			'post_type'               => [ 'anwp_club' ],
			'posts_per_page'          => 50,
			'include'                 => $data_initial,
			'cache_results'           => false,
			'update_post_meta_cache'  => false,
			'update_post_term_cache ' => false,
		];

		$results = get_posts( $query_args );

		if ( empty( $results ) || ! is_array( $results ) ) {
			return [];
		}

		$output = [];

		foreach ( $results as $result_item ) {
			$output[] = [
				'id'   => $result_item->ID,
				'name' => $result_item->post_title,
			];
		}

		return $output;
	}

	/**
	 * Get selector player data.
	 *
	 * @param array $search_data
	 *
	 * @return false|string
	 * @since 0.11.7
	 */
	private function get_selector_player_data( $search_data ) {

		$query_args = [
			'post_type'      => [ 'anwp_player' ],
			'posts_per_page' => 30,
			's'              => $search_data['s'],
		];

		$meta_query = [];

		if ( ! empty( $search_data['club'] ) && absint( $search_data['club'] ) ) {
			$meta_query[] = [
				'key'   => '_anwpfl_current_club',
				'value' => absint( $search_data['club'] ),
			];
		}

		if ( ! empty( $search_data['country'] ) ) {
			$meta_query[] = [
				'key'     => '_anwpfl_nationality',
				'value'   => '"' . sanitize_text_field( $search_data['country'] ) . '"',
				'compare' => 'LIKE',
			];
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query;
		}

		$results = get_posts( $query_args );

		ob_start();

		if ( ! empty( $results ) ) :
			?>
			<table class="wp-list-table widefat striped table-view-list">
				<thead>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Player Name', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Date of Birth', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></td>
				</tr>
				</thead>

				<tbody>
				<?php foreach ( $results as $player ) : ?>
					<tr data-id="<?php echo absint( $player->ID ); ?>" data-name="<?php echo esc_html( $player->post_title ); ?>">
						<td>
							<button type="button" class="button button-small anwp-fl-selector-action">
								<span class="dashicons dashicons-plus"></span>
							</button>
						</td>
						<td><?php echo esc_html( $player->post_title ); ?></td>
						<td><?php echo esc_html( get_post_meta( $player->ID, '_anwpfl_date_of_birth', true ) ); ?></td>
						<td>
							<?php
							$club_id       = (int) get_post_meta( $player->ID, '_anwpfl_current_club', true );
							$clubs_options = $this->plugin->club->get_clubs_options();

							if ( ! empty( $clubs_options[ $club_id ] ) ) {
								echo esc_html( $clubs_options[ $club_id ] );
							}
							?>
						</td>
						<td style="text-transform: uppercase;">
							<?php
							$nationality = maybe_unserialize( get_post_meta( $player->ID, '_anwpfl_nationality', true ) );

							if ( ! empty( $nationality ) && is_array( $nationality ) ) {
								echo esc_html( implode( ', ', $nationality ) );
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>

				<tfoot>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Player Name', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Date of Birth', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></td>
				</tr>
				</tfoot>
			</table>
		<?php else : ?>
			<div class="anwp-alert-warning">- <?php echo esc_html__( 'nothing found', 'anwp-football-leagues' ); ?> -</div>
			<?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Get selector club data.
	 *
	 * @param array $search_data
	 *
	 * @return false|string
	 * @since 0.11.8
	 */
	private function get_selector_club_data( $search_data ) {

		$query_args = [
			'post_type'      => [ 'anwp_club' ],
			'posts_per_page' => 30,
			's'              => $search_data['s'],
		];

		$meta_query = [];

		if ( ! empty( $search_data['country'] ) ) {
			$meta_query[] = [
				'key'   => '_anwpfl_nationality',
				'value' => sanitize_text_field( $search_data['country'] ),
			];
		}

		if ( ! empty( $meta_query ) ) {
			$query_args['meta_query'] = $meta_query;
		}

		$results = get_posts( $query_args );

		ob_start();

		if ( ! empty( $results ) ) :
			?>
			<table class="wp-list-table widefat striped table-view-list">
				<thead>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Club Title', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'City', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></td>
				</tr>
				</thead>

				<tbody>
				<?php foreach ( $results as $club ) : ?>
					<tr data-id="<?php echo absint( $club->ID ); ?>" data-name="<?php echo esc_html( $club->post_title ); ?>">
						<td>
							<button type="button" class="button button-small anwp-fl-selector-action">
								<span class="dashicons dashicons-plus"></span>
							</button>
						</td>
						<td><?php echo esc_html( $club->post_title ); ?></td>
						<td>
							<?php echo esc_html( get_post_meta( $club->ID, '_anwpfl_city', true ) ); ?>
						</td>
						<td style="text-transform: uppercase;">
							<?php echo esc_html( get_post_meta( $club->ID, '_anwpfl_nationality', true ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>

				<tfoot>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Club Title', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'City', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></td>
				</tr>
				</tfoot>
			</table>
		<?php else : ?>
			<div class="anwp-alert-warning">- <?php echo esc_html__( 'nothing found', 'anwp-football-leagues' ); ?> -</div>
			<?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Get selector games data.
	 *
	 * @param array $search_data
	 *
	 * @return false|string
	 * @since 0.11.13
	 */
	private function get_selector_game_data( $search_data ) {

		$args = [
			'season_id'    => absint( $search_data['season'] ) ?: '',
			'home_club'    => absint( $search_data['club_home'] ),
			'away_club'    => absint( $search_data['club_away'] ),
			'sort_by_date' => 'asc',
			'limit'        => 40,
		];

		$games = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended( $args, 'stats' );

		ob_start();

		if ( ! empty( $games ) ) :
			?>
			<table class="wp-list-table widefat striped table-view-list">
				<thead>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Home Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column"><?php echo esc_html__( 'Away Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Date', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Scores', 'anwp-football-leagues' ); ?></td>
				</tr>
				</thead>

				<tbody>
				<?php
				foreach ( $games as $game ) :

					$club_home_title = anwp_football_leagues()->club->get_club_title_by_id( $game->home_club );
					$club_away_title = anwp_football_leagues()->club->get_club_title_by_id( $game->away_club );
					$game_date       = explode( ' ', $game->kickoff )[0];
					$game_scores     = absint( $game->finished ) ? ( $game->home_goals . ':' . $game->away_goals ) : '?:?';

					$game_title = $club_home_title . ' - ' . $club_away_title . ' - ' . $game_date . ' - ' . $game_scores;

					?>
					<tr data-id="<?php echo absint( $game->match_id ); ?>" data-name="<?php echo esc_html( $game_title ); ?>">
						<td>
							<button type="button" class="button button-small anwp-fl-selector-action">
								<span class="dashicons dashicons-plus"></span>
							</button>
						</td>
						<td><?php echo esc_html( $club_home_title ); ?></td>
						<td><?php echo esc_html( $club_away_title ); ?></td>
						<td><?php echo esc_html( $game_date ); ?></td>
						<td><?php echo esc_html( $game_scores ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>

				<tfoot>
				<tr>
					<td class="manage-column check-column"></td>
					<td class="manage-column"><?php echo esc_html__( 'Home Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column"><?php echo esc_html__( 'Away Club', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Date', 'anwp-football-leagues' ); ?></td>
					<td class="manage-column column-format"><?php echo esc_html__( 'Scores', 'anwp-football-leagues' ); ?></td>
				</tr>
				</tfoot>
			</table>
		<?php else : ?>
			<div class="anwp-alert-warning">- <?php echo esc_html__( 'nothing found', 'anwp-football-leagues' ); ?> -</div>
			<?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Get selector match initial data.
	 *
	 * @param array $data_initial
	 *
	 * @return array
	 * @since 0.11.13
	 */
	private function get_selector_match_initial( $data_initial ) {

		if ( empty( $data_initial ) || ! is_array( $data_initial ) ) {
			return [];
		}

		$args = [
			'include_ids'  => implode( ',', $data_initial ),
			'sort_by_date' => 'asc',
		];

		$games = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended( $args, 'stats' );

		if ( empty( $games ) || ! is_array( $games ) ) {
			return [];
		}

		$output = [];

		foreach ( $games as $game ) {

			$club_home_title = anwp_football_leagues()->club->get_club_title_by_id( $game->home_club );
			$club_away_title = anwp_football_leagues()->club->get_club_title_by_id( $game->away_club );
			$game_date       = explode( ' ', $game->kickoff )[0];
			$game_scores     = absint( $game->finished ) ? ( $game->home_goals . ':' . $game->away_goals ) : '?:?';

			$output[] = [
				'id'   => $game->match_id,
				'name' => $club_home_title . ' - ' . $club_away_title . ' - ' . $game_date . ' - ' . $game_scores,
			];
		}

		return $output;
	}

	/**
	 * Recursive sanitization.
	 *
	 * @param string|array
	 *
	 * @return string|array
	 */
	public function recursive_sanitize( $value ) {
		if ( is_array( $value ) ) {
			return array_map( [ $this, 'recursive_sanitize' ], $value );
		} else {
			return is_scalar( $value ) ? sanitize_text_field( $value ) : $value;
		}
	}

	/**
	 * Get options in Select2 format
	 *
	 * @param array
	 *
	 * @return array
	 */
	public function get_select2_formatted_options( $options ) {
		$output = [];

		foreach ( $options as $option_key => $option_text ) {
			$output[] = [
				'id'   => $option_key,
				'text' => $option_text,
			];
		}

		return $output;
	}

	/**
	 * Get Youtube ID from url
	 *
	 * @param $url
	 *
	 * @return string Youtube ID or empty string
	 */
	public function get_youtube_id( $url ) {

		if ( mb_strlen( $url ) <= 11 ) {
			return $url;
		}

		preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches );

		return isset( $matches[1] ) ? $matches[1] : '';
	}
}
