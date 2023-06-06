<?php
/**
 * AnWP Football Leagues :: Standing.
 *
 * @since   0.2.0
 * @package AnWP_Football_Leagues
 */

require_once dirname( __FILE__ ) . '/../vendor/cpt-core/CPT_Core.php';

/**
 * AnWP Football Leagues :: Standing post type class.
 *
 * @since 0.2.0
 *
 * @see   https://github.com/WebDevStudios/CPT_Core
 */
class AnWPFL_Standing extends CPT_Core {

	/**
	 * Parent plugin class.
	 *
	 * @var AnWP_Football_Leagues
	 * @since  0.2.0
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 * Register Custom Post Types.
	 *
	 * See documentation in CPT_Core, and in wp-includes/post.php.
	 *
	 * @since  0.2.0
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		$permalink_structure = $plugin->options->get_permalink_structure();
		$permalink_slug      = empty( $permalink_structure['standing'] ) ? 'table' : $permalink_structure['standing'];

		// Register this cpt.
		parent::__construct(
			[ // array with Singular, Plural, and Registered name
				esc_html__( 'Standing Table', 'anwp-football-leagues' ),
				esc_html__( 'Standing Tables', 'anwp-football-leagues' ),
				'anwp_standing',
			],
			[
				'supports'            => [ 'title' ],
				'rewrite'             => [ 'slug' => $permalink_slug ],
				'show_in_menu'        => 'edit.php?post_type=anwp_competition',
				'public'              => true,
				'exclude_from_search' => 'hide' === AnWPFL_Options::get_value( 'display_front_end_search_standing' ),
				'labels'              => [
					'all_items'    => esc_html__( 'Standing Tables', 'anwp-football-leagues' ),
					'add_new'      => esc_html__( 'Add New Standing Table', 'anwp-football-leagues' ),
					'add_new_item' => esc_html__( 'Add New Standing Table', 'anwp-football-leagues' ),
					'edit_item'    => esc_html__( 'Edit Standing Table', 'anwp-football-leagues' ),
					'new_item'     => esc_html__( 'New Standing Table', 'anwp-football-leagues' ),
					'view_item'    => esc_html__( 'View Standing Table', 'anwp-football-leagues' ),
				],
			]
		);
	}

	/**
	 * Filter CPT title entry placeholder text
	 *
	 * @param  string $title Original placeholder text
	 *
	 * @return string        Modified placeholder text
	 */
	public function title( $title ) {

		$screen = get_current_screen();
		if ( isset( $screen->post_type ) && $screen->post_type === $this->post_type ) {
			return esc_html__( 'Standing Table Title', 'anwp-football-leagues' );
		}

		return $title;
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.2.0
	 */
	public function hooks() {

		// Metaboxes
		add_action( 'load-post.php', [ $this, 'init_metaboxes' ] );
		add_action( 'load-post-new.php', [ $this, 'init_metaboxes' ] );
		add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );

		// Scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Notices
		add_action( 'admin_notices', [ $this, 'display_admin_standing_notice' ] );

		// Admin Table filters
		add_filter( 'disable_months_dropdown', [ $this, 'disable_months_dropdown' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'add_more_filters' ] );
		add_filter( 'pre_get_posts', [ $this, 'handle_custom_filter' ] );

		add_filter( 'post_row_actions', [ $this, 'modify_quick_actions' ], 10, 2 );
		add_action( 'post_action_clone-standing', [ $this, 'process_clone_standing' ] );
	}

	/**
	 * Filters the array of row action links on the Pages list table.
	 *
	 * @param array $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 * @since 0.10.12
	 */
	public function modify_quick_actions( $actions, $post ) {

		if ( 'anwp_standing' === $post->post_type && current_user_can( 'edit_post', $post->ID ) ) {

			$clone_link                = admin_url( 'post.php?post=' . intval( $post->ID ) . '&action=clone-standing' );
			$actions['clone-standing'] = '<a href="' . esc_url( $clone_link ) . '">' . esc_html__( 'Clone', 'anwp-football-leagues' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Handle clone standing action.
	 *
	 * @param int $post_id
	 *
	 * @since 0.10.12
	 */
	public function process_clone_standing( $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$standing_id = wp_insert_post(
			[
				'post_type' => 'anwp_standing',
			]
		);

		if ( $standing_id ) {

			$meta_fields_to_clone = [
				'_anwpfl_points_initial',
				'_anwpfl_table_colors',
				'_anwpfl_table_notes',
				'_anwpfl_points_win',
				'_anwpfl_points_draw',
				'_anwpfl_points_loss',
				'_anwpfl_ranking_rules_current',
				'_anwpfl_manual_ordering',
			];

			/**
			 * Filter Standing Data to clone
			 *
			 * @param array $meta_fields_to_clone Clone data
			 * @param int   $post_id              Standing ID
			 * @param int   $standing_id          New Cloned Standing ID
			 *
			 * @since 0.11.2
			 */
			$meta_fields_to_clone = apply_filters( 'anwpfl/standing/fields_to_clone', $meta_fields_to_clone, $post_id, $standing_id );

			foreach ( $meta_fields_to_clone as $meta_key ) {

				$meta_value = get_post_meta( $post_id, $meta_key, true );

				if ( '' !== $meta_value ) {
					$meta_value = maybe_unserialize( $meta_value );
					update_post_meta( $standing_id, $meta_key, wp_slash( $meta_value ) );
				}
			}

			update_post_meta( $standing_id, '_anwpfl_cloned', $post_id );

			// phpcs:ignore WordPress.Security.SafeRedirect
			if ( wp_redirect( admin_url( 'post.php?post=' . intval( $standing_id ) . '&action=edit' ) ) ) {
				exit;
			}
		}
	}

	/**
	 * Fires before the Filter button on the Posts and Pages list tables.
	 *
	 * The Filter button allows sorting by date and/or category on the
	 * Posts list table, and sorting by date on the Pages list table.
	 *
	 * @param string $post_type The post type slug.
	 */
	public function add_more_filters( $post_type ) {

		if ( 'anwp_standing' === $post_type ) {

			ob_start();

			/*
			|--------------------------------------------------------------------
			| Filter By League
			|--------------------------------------------------------------------
			*/
			$leagues = get_terms(
				[
					'taxonomy'   => 'anwp_league',
					'hide_empty' => false,
				]
			);

			if ( ! is_wp_error( $leagues ) && ! empty( $leagues ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification
				$current_league_filter = empty( $_GET['_anwpfl_current_league'] ) ? '' : (int) $_GET['_anwpfl_current_league'];
				?>

				<select name='_anwpfl_current_league' id='anwp_league_filter' class='postform'>
					<option value=''><?php echo esc_html__( 'All Leagues', 'anwp-football-leagues' ); ?></option>
					<?php foreach ( $leagues as $league ) : ?>
						<option value="<?php echo esc_attr( $league->term_id ); ?>" <?php selected( $league->term_id, $current_league_filter ); ?>>
							- <?php echo esc_html( $league->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php
			}

			/*
			|--------------------------------------------------------------------
			| Filter By Season
			|--------------------------------------------------------------------
			*/
			$seasons = get_terms(
				[
					'taxonomy'   => 'anwp_season',
					'hide_empty' => false,
				]
			);

			if ( ! is_wp_error( $seasons ) && ! empty( $seasons ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification
				$current_season_filter = empty( $_GET['_anwpfl_current_season'] ) ? '' : (int) $_GET['_anwpfl_current_season'];
				?>

				<select name='_anwpfl_current_season' id='anwp_season_filter' class='postform'>
					<option value=''><?php echo esc_html__( 'All Seasons', 'anwp-football-leagues' ); ?></option>
					<?php foreach ( $seasons as $season ) : ?>
						<option value="<?php echo esc_attr( $season->term_id ); ?>" <?php selected( $season->term_id, $current_season_filter ); ?>>
							- <?php echo esc_html( $season->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ob_get_clean();
		}
	}

	/**
	 * Handle custom filter.
	 *
	 * @param WP_Query $query
	 */
	public function handle_custom_filter( $query ) {
		global $post_type, $pagenow;

		// Check main query in admin
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( 'edit.php' !== $pagenow || 'anwp_standing' !== $post_type ) {
			return;
		}

		$sub_query = [];

		/*
		|--------------------------------------------------------------------
		| Filter By Season
		|--------------------------------------------------------------------
		*/
		// phpcs:ignore WordPress.Security.NonceVerification
		$filter_by_season = empty( $_GET['_anwpfl_current_season'] ) ? '' : intval( $_GET['_anwpfl_current_season'] );

		if ( $filter_by_season ) {

			$season_args = [
				'numberposts' => - 1,
				'fields'      => 'ids',
				'post_type'   => 'anwp_competition',
				'post_status' => [ 'publish', 'stage_secondary' ],
				'tax_query'   => [
					[
						'taxonomy' => 'anwp_season',
						'field'    => 'id',
						'terms'    => [ $filter_by_season ],
					],
				],
			];

			$season_competition_ids = get_posts( $season_args );

			if ( $season_competition_ids ) {
				$sub_query[] =
					[
						'key'     => '_anwpfl_competition',
						'value'   => $season_competition_ids,
						'compare' => 'IN',
					];
			}
		}

		/*
		|--------------------------------------------------------------------
		| Filter By League
		|--------------------------------------------------------------------
		*/
		// phpcs:ignore WordPress.Security.NonceVerification
		$filter_by_league = empty( $_GET['_anwpfl_current_league'] ) ? '' : intval( $_GET['_anwpfl_current_league'] );

		if ( $filter_by_league ) {

			$league_args = [
				'numberposts' => - 1,
				'fields'      => 'ids',
				'post_type'   => 'anwp_competition',
				'post_status' => [ 'publish', 'stage_secondary' ],
				'tax_query'   => [
					[
						'taxonomy' => 'anwp_league',
						'field'    => 'id',
						'terms'    => [ $filter_by_league ],
					],
				],
			];

			$league_competition_ids = get_posts( $league_args );

			if ( $league_competition_ids ) {
				$sub_query[] =
					[
						'key'   => '_anwpfl_competition',
						'value' => $league_competition_ids,
					];
			}
		}

		/*
		|--------------------------------------------------------------------
		| Join All values to main query
		|--------------------------------------------------------------------
		*/
		if ( ! empty( $sub_query ) ) {
			$query->set(
				'meta_query',
				[
					array_merge( [ 'relation' => 'AND' ], $sub_query ),
				]
			);
		}
	}

	/**
	 * Filters whether to remove the 'Months' drop-down from the post list table.
	 *
	 * @param bool   $disable   Whether to disable the drop-down. Default false.
	 * @param string $post_type The post type.
	 *
	 * @return bool
	 */
	public function disable_months_dropdown( $disable, $post_type ) {

		return 'anwp_standing' === $post_type ? true : $disable;
	}

	/**
	 * Prepare to Recalculate Standing Table.
	 *
	 * @param int $match_id
	 * @param int $competition_id
	 * @param int $group_id
	 *
	 * @since  0.3.0 (2018-31-01)
	 * @return void
	 */
	public function calculate_standing_prepare( $match_id, $competition_id = 0, $group_id = 0 ) {

		// Sanitize data
		$match_id       = (int) $match_id;
		$competition_id = (int) $competition_id;
		$group_id       = (int) $group_id;

		if ( ! $match_id ) {
			return;
		}

		// Get competition & competitionGroup
		if ( ! $competition_id || ! $group_id ) {

			$competition_id = (int) get_post_meta( $match_id, '_anwpfl_competition', true );
			$group_id       = (int) get_post_meta( $match_id, '_anwpfl_competition_group', true );

			// Recheck
			if ( ! $competition_id || ! $group_id ) {
				return;
			}
		}

		// Get standing
		$standing_obj = get_posts(
			[
				'post_type'      => 'anwp_standing',
				'posts_per_page' => 1,
				'meta_query'     => [
					'relation' => 'AND',
					[
						'key'     => '_anwpfl_competition',
						'value'   => $competition_id,
						'compare' => '=',
					],
					[
						'key'     => '_anwpfl_competition_group',
						'value'   => $group_id,
						'compare' => '=',
					],
				],
			]
		);

		if ( empty( $standing_obj[0]->ID ) || 'true' !== get_post_meta( $standing_obj[0]->ID, '_anwpfl_fixed', true ) ) {
			return;
		}

		$data = [
			'id'              => $standing_obj[0]->ID,
			'competition'     => $competition_id,
			'group'           => $group_id,
			'points_initial'  => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_initial', true ),
			'win'             => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_win', true ),
			'draw'            => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_draw', true ),
			'loss'            => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_loss', true ),
			'ranking_rules'   => get_post_meta( $standing_obj[0]->ID, '_anwpfl_ranking_rules_current', true ),
			'manual_ordering' => get_post_meta( $standing_obj[0]->ID, '_anwpfl_manual_ordering', true ),
		];

		$this->calculate_standing( $data );
	}

	/**
	 * Calculate all competition standings (one/first per group).
	 *
	 * @param int $competition_id
	 *
	 * @return void
	 * @since  0.10.21
	 */
	public function calculate_competition_standings( $competition_id ) {

		if ( ! absint( $competition_id ) ) {
			return;
		}

		// Get all competition groups
		$groups    = json_decode( get_post_meta( $competition_id, '_anwpfl_groups', true ) );
		$group_ids = [];

		if ( ! empty( $groups ) && is_array( $groups ) ) {
			$group_ids = wp_list_pluck( $groups, 'id' );
		}

		foreach ( $group_ids as $group_id ) {

			// Get standing
			$standing_obj = get_posts(
				[
					'post_type'      => 'anwp_standing',
					'posts_per_page' => 1,
					'meta_query'     => [
						'relation' => 'AND',
						[
							'key'     => '_anwpfl_competition',
							'value'   => $competition_id,
							'compare' => '=',
						],
						[
							'key'     => '_anwpfl_competition_group',
							'value'   => $group_id,
							'compare' => '=',
						],
					],
				]
			);

			if ( empty( $standing_obj[0]->ID ) || 'true' !== get_post_meta( $standing_obj[0]->ID, '_anwpfl_fixed', true ) ) {
				return;
			}

			$data = [
				'id'              => $standing_obj[0]->ID,
				'competition'     => $competition_id,
				'group'           => $group_id,
				'points_initial'  => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_initial', true ),
				'win'             => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_win', true ),
				'draw'            => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_draw', true ),
				'loss'            => get_post_meta( $standing_obj[0]->ID, '_anwpfl_points_loss', true ),
				'ranking_rules'   => get_post_meta( $standing_obj[0]->ID, '_anwpfl_ranking_rules_current', true ),
				'manual_ordering' => get_post_meta( $standing_obj[0]->ID, '_anwpfl_manual_ordering', true ),
			];

			$this->calculate_standing( $data );
		}
	}

	/**
	 * Recalculate Standing Table
	 *
	 * @param array $data
	 *
	 * @since 0.3.0 (2018-01-31)
	 */
	private function calculate_standing( $data ) {

		global $wpdb;

		/**
		 * Filter: anwpfl/standing/calculate_standing
		 *
		 * @since 0.7.5
		 *
		 * @param bool
		 * @param int $standing_id
		 * @param int $competition_id
		 * @param int $group_id
		 */
		if ( ! apply_filters( 'anwpfl/standing/calculate_standing', true, $data['id'], $data['competition'], $data['group'] ) ) {
			return;
		}

		// Prepare empty table
		$table = [];

		foreach ( $this->plugin->competition->get_competition_clubs( $data['competition'], $data['group'] ) as $club ) {

			if ( (int) $club ) {
				$table[ $club ] = [
					'club_id' => $club,
					'place'   => 0,
					'played'  => 0,
					'won'     => 0,
					'drawn'   => 0,
					'lost'    => 0,
					'gf'      => 0,
					'ga'      => 0,
					'gd'      => 0,
					'points'  => 0,
					'series'  => '',
				];
			}
		}

		// Get finished matches
		$matches = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT home_club, away_club, home_goals, away_goals, match_id
				FROM {$wpdb->prefix}anwpfl_matches
				WHERE competition_id = %d
					AND group_id = %d
					AND finished = 1
				ORDER BY kickoff
				",
				$data['competition'],
				$data['group']
			)
		);

		// Get games with custom outcome
		$custom_outcomes = $this->get_games_with_custom_outcome( $data['competition'], $data['group'] );

		// Populate stats
		foreach ( $matches as $match ) {

			if ( ! empty( $custom_outcomes ) && in_array( $match->match_id, $custom_outcomes, true ) ) {

				$home_outcome = get_post_meta( $match->match_id, '_anwpfl_outcome_home', true );

				switch ( $home_outcome ) {
					case 'won':
						$table[ $match->home_club ]['won'] ++;
						$table[ $match->home_club ]['series'] .= 'w';
						break;

					case 'drawn':
						$table[ $match->home_club ]['drawn'] ++;
						$table[ $match->home_club ]['series'] .= 'd';
						break;

					case 'lost':
						$table[ $match->home_club ]['lost'] ++;
						$table[ $match->home_club ]['series'] .= 'l';
						break;
				}

				$away_outcome = get_post_meta( $match->match_id, '_anwpfl_outcome_away', true );

				switch ( $away_outcome ) {
					case 'won':
						$table[ $match->away_club ]['won'] ++;
						$table[ $match->away_club ]['series'] .= 'w';
						break;

					case 'drawn':
						$table[ $match->away_club ]['drawn'] ++;
						$table[ $match->away_club ]['series'] .= 'd';
						break;

					case 'lost':
						$table[ $match->away_club ]['lost'] ++;
						$table[ $match->away_club ]['series'] .= 'l';
						break;
				}
			} else {
				if ( $match->home_goals > $match->away_goals ) {

					// Home Club
					$table[ $match->home_club ]['won'] ++;
					$table[ $match->home_club ]['series'] .= 'w';

					// Away Club
					$table[ $match->away_club ]['lost'] ++;
					$table[ $match->away_club ]['series'] .= 'l';

				} elseif ( $match->home_goals === $match->away_goals ) {

					// Home Club
					$table[ $match->home_club ]['drawn'] ++;
					$table[ $match->home_club ]['series'] .= 'd';

					// Away Club
					$table[ $match->away_club ]['drawn'] ++;
					$table[ $match->away_club ]['series'] .= 'd';

				} else {

					// Home Club
					$table[ $match->home_club ]['lost'] ++;
					$table[ $match->home_club ]['series'] .= 'l';

					// Away Club
					$table[ $match->away_club ]['won'] ++;
					$table[ $match->away_club ]['series'] .= 'w';
				}
			}

			$table[ $match->home_club ]['gf'] += $match->home_goals;
			$table[ $match->away_club ]['gf'] += $match->away_goals;

			$table[ $match->home_club ]['ga'] += $match->away_goals;
			$table[ $match->away_club ]['ga'] += $match->home_goals;

			$table[ $match->home_club ]['played'] ++;
			$table[ $match->away_club ]['played'] ++;
		}

		// Calculate others fields
		foreach ( $table as $club_id => $club ) {
			$table[ $club_id ]['points'] = $club['won'] * (int) $data['win'] + $club['drawn'] * (int) $data['draw'] + $club['lost'] * (int) $data['loss'];
			$table[ $club_id ]['gd']     = $club['gf'] - $club['ga'];
		}

		/*
		|--------------------------------------------------------------------
		| Custom Outcome Points
		|--------------------------------------------------------------------
		*/
		if ( ! empty( $custom_outcomes ) && is_array( $custom_outcomes ) ) {
			foreach ( $custom_outcomes as $custom_outcome ) {
				$home_outcome = get_post_meta( $custom_outcome, '_anwpfl_outcome_home', true );
				$home_club    = get_post_meta( $custom_outcome, '_anwpfl_club_home', true );
				$home_points  = get_post_meta( $custom_outcome, '_anwpfl_outcome_points_home', true );

				$away_outcome = get_post_meta( $custom_outcome, '_anwpfl_outcome_away', true );
				$away_club    = get_post_meta( $custom_outcome, '_anwpfl_club_away', true );
				$away_points  = get_post_meta( $custom_outcome, '_anwpfl_outcome_points_away', true );

				if ( isset( $table[ $home_club ]['points'] ) ) {

					$points_added = (int) $data['win'];

					switch ( $home_outcome ) {
						case 'drawn':
							$points_added = (int) $data['draw'];
							break;

						case 'lost':
							$points_added = (int) $data['loss'];
							break;
					}

					$table[ $home_club ]['points'] = $table[ $home_club ]['points'] - $points_added + absint( $home_points );
				}

				if ( isset( $table[ $away_club ]['points'] ) ) {

					$points_added = (int) $data['win'];

					switch ( $away_outcome ) {
						case 'drawn':
							$points_added = (int) $data['draw'];
							break;

						case 'lost':
							$points_added = (int) $data['loss'];
							break;
					}

					$table[ $away_club ]['points'] = $table[ $away_club ]['points'] - $points_added + absint( $away_points );
				}
			}
		}

		/*
		|--------------------------------------------------------------------
		| Add initial points
		|--------------------------------------------------------------------
		*/
		if ( $data['points_initial'] ) {
			$initial = json_decode( wp_unslash( $data['points_initial'] ) );

			if ( ! empty( $initial ) && is_object( $initial ) ) {
				foreach ( $initial as $club_id => $points_to_add ) {
					$table[ $club_id ]['points'] = $table[ $club_id ]['points'] + (int) $points_to_add;
				}
			}
		}

		/*
		|--------------------------------------------------------------------
		| Check Initial Table Data
		|--------------------------------------------------------------------
		*/
		if ( anwp_football_leagues()->helper->string_to_bool( get_post_meta( $data['id'], '_anwpfl_is_initial_data_active', true ) ) ) {
			$initial_data = json_decode( get_post_meta( $data['id'], '_anwpfl_table_initial', true ) );

			$table_fields = [
				'played',
				'won',
				'drawn',
				'lost',
				'gf',
				'ga',
				'gd',
				'points',
			];

			foreach ( $initial_data as $row_club_id => $data_row ) {
				foreach ( $table_fields as $table_field ) {
					if ( ! empty( $data_row->{$table_field} ) && isset( $table[ $row_club_id ] ) && isset( $table[ $row_club_id ][ $table_field ] ) ) {
						$table[ $row_club_id ][ $table_field ] += (int) $data_row->{$table_field};
					}
				}
			}
		}

		/*
		|--------------------------------------------------------------------
		| Ordering
		|--------------------------------------------------------------------
		*/
		if ( count( $table ) && 'true' !== $data['manual_ordering'] ) {

			/**
			 * Filter: anwpfl/standing/custom_position_calculation
			 *
			 * @since 0.7.5
			 *
			 * @param bool
			 * @param array $table
			 * @param array $data
			 */
			if ( apply_filters( 'anwpfl/standing/custom_position_calculation', false, $table, $data ) ) {

				/**
				 * Filter: anwpfl/standing/custom_position_calculation_table
				 *
				 * @since 0.7.5
				 *
				 * @param array $table
				 * @param array $data
				 * @param array $matches
				 */
				$table = apply_filters( 'anwpfl/standing/custom_position_calculation_table', $table, $data, $matches );
			} else {
				foreach ( $table as $key => $row ) {
					$points[ $key ] = $row['points'];
					$won[ $key ]    = $row['won'];
					$gd[ $key ]     = $row['gd'];
					$gf[ $key ]     = $row['gf'];
				}

				$sort_order = [ $points, SORT_DESC ];

				if ( $data['ranking_rules'] ) {
					foreach ( explode( ',', $data['ranking_rules'] ) as $rule ) {
						switch ( $rule ) {

							case 'wins':
								$sort_order[] = $won;
								$sort_order[] = SORT_DESC;
								break;

							case 'goals_scored':
								$sort_order[] = $gf;
								$sort_order[] = SORT_DESC;
								break;

							case 'goals_difference':
								$sort_order[] = $gd;
								$sort_order[] = SORT_DESC;
								break;
						}
					}
				}

				$sort_order[] = &$table;
				call_user_func_array( 'array_multisort', $sort_order );
			}
		} else {

			$table_old = json_decode( get_post_meta( $data['id'], '_anwpfl_table_main', true ) );

			if ( is_array( $table_old ) && count( $table_old ) ) {

				$places = [];

				foreach ( $table_old as $row ) {
					$table[ $row->club_id ]['place'] = $row->place;
				}

				foreach ( $table as $key => $row ) {
					$places[ $key ] = $row['place'];
				}

				array_multisort( $places, SORT_ASC, $table );

			} else {
				$table = array_values( $table );
			}
		}

		// Set Place field
		$place_counter = 1;
		foreach ( $table as $index => $row ) {
			$table[ $index ]['place'] = $place_counter ++;
		}

		// Save to DB
		update_post_meta( $data['id'], '_anwpfl_table_main', wp_json_encode( $table ) );
		update_post_meta( $data['id'], '_anwpfl_last_recalc', current_time( 'mysql', true ) );

		// Table recalculated notice
		$notice_text = sprintf( 'Standing Table "%s" has been successfully recalculated.', get_the_title( $data['id'] ) );
		set_transient( 'anwp-admin-standing-recalculated', $notice_text, 10 );
	}

	/**
	 * Display successful recalculated text.
	 *
	 * @since 0.5.1 (2018-03-19)
	 */
	public function display_admin_standing_notice() {

		if ( get_transient( 'anwp-admin-standing-recalculated' ) ) :
			?>
			<div class="notice notice-success is-dismissible anwp-visible-after-header">
				<p><?php echo esc_html( get_transient( 'anwp-admin-standing-recalculated' ) ); ?></p>
			</div>
			<?php
			delete_transient( 'anwp-admin-standing-recalculated' );
		endif;
	}

	/**
	 * Load admin scripts and styles
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @since 0.2.0 (2018-01-25)
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		$current_screen = get_current_screen();

		if ( 'post.php' === $hook_suffix && 'anwp_standing' === $current_screen->id ) {

			$post_id = get_the_ID();
			$fixed   = filter_var( get_post_meta( $post_id, '_anwpfl_fixed', true ), FILTER_VALIDATE_BOOLEAN );

			$cloned = get_post_meta( $post_id, '_anwpfl_cloned', true );

			if ( intval( $cloned ) ) {
				$cloned = get_the_title( $cloned ) . ' (ID: ' . intval( $cloned ) . ')';
			}

			if ( $fixed || $cloned ) {

				$manual_ordering = filter_var( get_post_meta( $post_id, '_anwpfl_manual_ordering', true ), FILTER_VALIDATE_BOOLEAN );

				$data = [
					'fixed'               => $fixed ? 'yes' : '',
					'cloned'              => $cloned ? $cloned : '',
					'manualOrdering'      => $manual_ordering ? 'yes' : '',
					'competition'         => get_post_meta( $post_id, '_anwpfl_competition', true ),
					'tableNotes'          => get_post_meta( $post_id, '_anwpfl_table_notes', true ),
					'competitionGroup'    => get_post_meta( $post_id, '_anwpfl_competition_group', true ),
					'pointsWin'           => get_post_meta( $post_id, '_anwpfl_points_win', true ),
					'pointsDraw'          => get_post_meta( $post_id, '_anwpfl_points_draw', true ),
					'pointsLoss'          => get_post_meta( $post_id, '_anwpfl_points_loss', true ),
					'rankingRulesCurrent' => get_post_meta( $post_id, '_anwpfl_ranking_rules_current', true ),
					'pointsInitial'       => get_post_meta( $post_id, '_anwpfl_points_initial', true ),
					'tableColors'         => get_post_meta( $post_id, '_anwpfl_table_colors', true ),
					'tableMain'           => get_post_meta( $post_id, '_anwpfl_table_main', true ),
				];

				/**
				 * Filter Standing Data.
				 *
				 * @param array $data    Standing data
				 * @param int   $post_id Standing ID
				 *
				 * @since 0.7.5
				 */
				$data = apply_filters( 'anwpfl/standing/data_to_admin_vue', $data, $post_id );

				wp_localize_script( 'anwpfl_admin_vue', 'anwpStanding', $data );
			}
		}
	}

	/**
	 * Meta box initialization.
	 *
	 * @since  0.2.0 (2017-12-07)
	 */
	public function init_metaboxes() {
		add_action(
			'add_meta_boxes',
			function ( $post_type ) {

				if ( 'anwp_standing' === $post_type ) {
					add_meta_box(
						'anwpfl_standing',
						__( 'Standing Table', 'anwp-football-leagues' ),
						[ $this, 'render_metabox' ],
						$post_type,
						'normal',
						'high'
					);
				}
			}
		);
	}

	/**
	 * Render Meta Box content for Competition Stages.
	 *
	 * @param WP_Post $post The post object.
	 * @since  0.2.0 (2017-10-28)
	 */
	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'anwp_save_metabox_' . $post->ID, 'anwp_metabox_nonce' );

		$app_id         = apply_filters( 'anwpfl/standing/vue_app_id', 'anwpfl-app-standing' );
		$fixed          = 'true' === get_post_meta( $post->ID, '_anwpfl_fixed', true );
		$competition_id = get_post_meta( $post->ID, '_anwpfl_competition', true );
		$group_id       = get_post_meta( $post->ID, '_anwpfl_competition_group', true );

		$available_competitions = $fixed
			? anwp_football_leagues()->competition->get_competition_group_standing( $competition_id, $group_id )
			: anwp_football_leagues()->competition->get_competition_groups_without_standing();

		/*
		|--------------------------------------------------------------------
		| Standing Data
		|--------------------------------------------------------------------
		*/
		$anwp_fl_standing = [
			'is_initial_data_active' => anwp_football_leagues()->helper->string_to_bool( get_post_meta( $post->ID, '_anwpfl_is_initial_data_active', true ) ),
			'table_initial'          => json_decode( get_post_meta( $post->ID, '_anwpfl_table_initial', true ) ) ?: (object) [],
		];
		?>
		<div class="anwp-b-wrap anwpfl-standing-metabox-wrapper">

			<?php if ( empty( $available_competitions ) ) : ?>

				<div class="alert alert-warning border-warning d-flex align-items-center" role="alert">
					<svg class="anwp-icon mr-2 anwp-icon--octi anwp-icon--s16"><use xlink:href="#icon-alert"></use></svg>
					<?php echo esc_html__( 'There are no available "Round-Robin" competitions.', 'anwp-football-leagues' ); ?>
				</div>

			<?php else : ?>

				<script type="text/javascript">
					var _anwpFL_CompetitionsForStanding = <?php echo wp_json_encode( $available_competitions ); ?>;
					var _anwp_FL_Standing = <?php echo wp_json_encode( $anwp_fl_standing ); ?>;
				</script>

				<?php if ( $fixed ) : ?>
					<div class="mb-4 border border-success bg-light px-3 py-2">
						<div>
							<?php
							$groups      = json_decode( get_post_meta( $competition_id, '_anwpfl_groups', true ) );
							$group_title = '';

							if ( absint( $group_id ) && ! empty( $groups ) && is_array( $groups ) ) :
								foreach ( $groups as $index => $group ) :
									if ( absint( $group->id ) === absint( $group_id ) ) :
										$group_title = $group->title ? $group->title : 'Group #' . ( $index + 1 );
									endif;
								endforeach;
							endif;
							?>
							<b class="mr-1"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?>:</b>
							<span><?php echo esc_html( get_the_title( $competition_id ) ); ?></span>

							<?php if ( $group_title ) : ?>
								<span class="text-muted small mx-2">|</span>
								<span><?php echo esc_html( $group_title ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div id="<?php echo esc_attr( $app_id ); ?>"></div>

			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @since  0.2.0 (2017-12-10)
	 * @return bool|int
	 */
	public function save_metabox( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['anwp_metabox_nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['anwp_metabox_nonce'], 'anwp_save_metabox_' . $post_id ) ) {
			return $post_id;
		}

		// Check post type
		if ( 'anwp_standing' !== $_POST['post_type'] ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// check if there was a multisite switch before
		if ( is_multisite() && ms_is_switched() ) {
			return $post_id;
		}

		/* OK, it's safe for us to save the data now. */

		/** ---------------------------------------
		 * Save Standing Data
		 *
		 * @since 0.2.0
		 * ---------------------------------------*/

		$fixed = sanitize_key( $_POST['_anwpfl_fixed'] );

		if ( 'true' === $fixed ) {

			update_post_meta( $post_id, '_anwpfl_fixed', $fixed );

			// Prepare data & Encode with some WP sanitization
			$point_initial = wp_json_encode( json_decode( stripslashes( $_POST['_anwpfl_points_initial'] ) ) );
			$table_main    = wp_json_encode( json_decode( stripslashes( $_POST['_anwpfl_table_main'] ) ) );
			$table_colors  = wp_json_encode( json_decode( stripslashes( $_POST['_anwpfl_table_colors'] ) ) );

			// phpcs:disable WordPress.NamingConventions
			if ( $table_main ) {
				update_post_meta( $post_id, '_anwpfl_table_main', wp_slash( $table_main ) );
			}

			if ( $point_initial ) {
				update_post_meta( $post_id, '_anwpfl_points_initial', wp_slash( $point_initial ) );
			}

			if ( $table_colors ) {
				update_post_meta( $post_id, '_anwpfl_table_colors', wp_slash( $table_colors ) );
			}

			/*
			|--------------------------------------------------------------------
			| Handle Initial Data
			| @since 0.11.12
			|--------------------------------------------------------------------
			*/
			if ( isset( $_POST['_anwpfl_is_initial_data_active'] ) && anwp_football_leagues()->helper->string_to_bool( $_POST['_anwpfl_is_initial_data_active'] ) ) {
				update_post_meta( $post_id, '_anwpfl_is_initial_data_active', '1' );

				if ( isset( $_POST['_anwpfl_table_initial'] ) ) {
					$table_initial = wp_json_encode( json_decode( wp_unslash( $_POST['_anwpfl_table_initial'] ) ) );
				}
			} else {
				update_post_meta( $post_id, '_anwpfl_is_initial_data_active', '0' );
			}

			if ( ! empty( $table_initial ) ) {
				update_post_meta( $post_id, '_anwpfl_table_initial', wp_slash( $table_initial ) );
			} else {
				delete_post_meta( $post_id, '_anwpfl_table_initial' );
			}

			/*
			|--------------------------------------------------------------------
			| Handle General Data
			|--------------------------------------------------------------------
			*/
			$data = [];

			$data['_anwpfl_table_notes']       = wp_kses_post( $_POST['_anwpfl_table_notes'] );
			$data['_anwpfl_competition']       = (int) $_POST['_anwpfl_competition'];
			$data['_anwpfl_competition_group'] = (int) $_POST['_anwpfl_competition_group'];
			$data['_anwpfl_points_win']        = (int) $_POST['_anwpfl_points_win'];
			$data['_anwpfl_points_draw']       = (int) $_POST['_anwpfl_points_draw'];
			$data['_anwpfl_points_loss']       = (int) $_POST['_anwpfl_points_loss'];

			$data['_anwpfl_ranking_rules_current'] = sanitize_text_field( $_POST['_anwpfl_ranking_rules_current'] );
			$data['_anwpfl_manual_ordering']       = sanitize_key( $_POST['_anwpfl_manual_ordering'] );

			/**
			 * Filter Standing Data before save
			 *
			 * @param array $data    Match data
			 * @param int   $post_id Standing ID
			 *
			 * @since 0.7.5
			 */
			$data = apply_filters( 'anwpfl/standing/data_to_save', $data, $post_id );

			foreach ( $data as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

			/**
			 * Trigger on save standing data.
			 *
			 * @param array $data    Standing data
			 * @param int   $post_id Standing ID
			 *
			 * @since 0.7.5
			 */
			do_action( 'anwpfl/standing/on_save', $data, $post_id );

			// Recalculate standing
			$standing_data = [
				'id'              => $post_id,
				'competition'     => $data['_anwpfl_competition'],
				'group'           => $data['_anwpfl_competition_group'],
				'points_initial'  => $point_initial,
				'win'             => $data['_anwpfl_points_win'],
				'draw'            => $data['_anwpfl_points_draw'],
				'loss'            => $data['_anwpfl_points_loss'],
				'ranking_rules'   => $data['_anwpfl_ranking_rules_current'],
				'manual_ordering' => $data['_anwpfl_manual_ordering'],
			];

			$this->calculate_standing( $standing_data );

			// Generate title if empty
			if ( isset( $_POST['post_title'] ) && '' === $_POST['post_title'] ) {

				$standing_title = $this->generate_title( $post_id );

				if ( $standing_title ) {
					remove_action( 'save_post', [ $this, 'save_metabox' ] );

					// update the post, which calls save_post again
					wp_update_post(
						[
							'ID'         => $post_id,
							'post_title' => $standing_title,
						]
					);

					// re-hook this function
					add_action( 'save_post', [ $this, 'save_metabox' ] );
				}
			}
		}

		return $post_id;
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  0.2.0
	 *
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array.
	 */
	public function columns( $columns ) {
		// Add new columns
		$new_columns = [
			'anwpfl_competition' => esc_html__( 'Competition', 'anwp-football-leagues' ),
			'anwpfl_group'       => esc_html__( 'Group', 'anwp-football-leagues' ),
			'standing_id'        => esc_html__( 'ID', 'anwp-football-leagues' ),
		];

		// Merge old and new columns
		$columns = array_merge( $new_columns, $columns );

		// Change columns order
		$new_columns_order = [
			'cb',
			'title',
			'anwpfl_competition',
			'anwpfl_group',
			'date',
			'standing_id',
		];
		$new_columns       = [];

		foreach ( $new_columns_order as $c ) {

			if ( isset( $columns[ $c ] ) ) {
				$new_columns[ $c ] = $columns[ $c ];
			}
		}

		return $new_columns;
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  0.2.0
	 *
	 * @param array   $column   Column currently being rendered.
	 * @param integer $post_id  ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {

		switch ( $column ) {
			case 'anwpfl_competition':
				// Get competition id
				$competition_id = (int) get_post_meta( $post_id, '_anwpfl_competition', true );

				// Get competition title
				$competition = get_post( $competition_id );
				echo esc_html( $competition->post_title );

				if ( '' !== $competition->_anwpfl_multistage && $competition->_anwpfl_stage_title ) {
					echo '<br>' . esc_html( $competition->_anwpfl_stage_title );
				}
				break;

			case 'anwpfl_group':
				// Get competition id
				$competition_id = (int) get_post_meta( $post_id, '_anwpfl_competition', true );

				$group_id = (int) get_post_meta( $post_id, '_anwpfl_competition_group', true );

				// Get competition title
				$groups = json_decode( get_post_meta( $competition_id, '_anwpfl_groups', true ) );

				if ( ! empty( $groups ) && is_array( $groups ) ) {
					foreach ( $groups as $index => $group ) {

						if ( $group->id === $group_id ) {
							$title = $group->title ? $group->title : 'Group #' . ( $index + 1 );
							echo esc_html( $title );
						}
					}
				}

				break;

			case 'standing_id':
				echo (int) $post_id;
				break;

		}
	}

	/**
	 * Helper function, returns standings with id and title.
	 *
	 * @since 0.10.8
	 * @return array $output_data
	 */
	public function get_standing_options() {

		static $options = null;

		if ( null === $options ) {

			$options = [];

			$posts = get_posts(
				[
					'numberposts' => - 1,
					'post_type'   => 'anwp_standing',
				]
			);

			/** @var  $p WP_Post */
			foreach ( $posts as $p ) {
				$options[ $p->ID ] = $p->post_title;
			}

			asort( $options );
		}

		return $options;
	}

	/**
	 * Parse and prepare table notes to output.
	 *
	 * @param string $notes - Raw notes string
	 *
	 * @return string
	 * @since 0.10.11
	 */
	public function prepare_table_notes( $notes ) {

		$replace_data = [ 'info', 'warning', 'danger', 'primary', 'secondary', 'success' ];

		foreach ( $replace_data as $replace ) {
			$notes = str_ireplace( '%' . $replace . '%', '<span class="border mr-1 px-3 table-' . $replace . '"></span>', $notes );
		}

		$notes = nl2br( $notes );

		return $notes;
	}

	/**
	 * Filter standing table partition data.
	 *
	 * @param array  $table
	 * @param string $partial
	 *
	 * @return array
	 * @since 0.10.23
	 */
	public function get_standing_partial_data( $table, $partial ) {

		$first = 0;
		$last  = 0;

		if ( ! mb_strpos( $partial, '-', 1 ) && absint( $partial ) ) {
			$club_id = absint( $partial );

			foreach ( $table as $table_row ) {
				if ( $club_id === $table_row->club_id ) {

					$first = ( $table_row->place - 2 ) < 1 ? 1 : $table_row->place - 2;
					$last  = $table_row->place + 2;

					break;
				}
			}
		} elseif ( mb_strpos( $partial, '-', 1 ) ) {
			$partial_arr = explode( '-', $partial );

			$first = absint( trim( $partial_arr[0] ) );

			if ( ! empty( $partial_arr[1] ) ) {
				$last = absint( trim( $partial_arr[1] ) );
			}
		}

		if ( empty( $first ) || empty( $last ) ) {
			return $table;
		}

		foreach ( $table as $row_index => $row_item ) {
			if ( $row_item->place < $first || $row_item->place > $last ) {
				unset( $table[ $row_index ] );
			}
		}

		return $table;
	}

	/**
	 * Generate Standing Table title
	 *
	 * @param int $standing_id
	 *
	 * @return string        Standing Table title
	 * @since 0.11.2
	 */
	public function generate_title( $standing_id ) {

		// Get competition id
		$competition_id = absint( get_post_meta( $standing_id, '_anwpfl_competition', true ) );
		$group_id       = absint( get_post_meta( $standing_id, '_anwpfl_competition_group', true ) );

		// Get competition title
		$groups = json_decode( get_post_meta( $competition_id, '_anwpfl_groups', true ) );

		$generated_title = get_the_title( $competition_id );

		if ( ! empty( $groups ) && is_array( $groups ) ) {
			foreach ( $groups as $index => $group ) {
				if ( absint( $group->id ) === absint( $group_id ) ) {
					$generated_title .= ' - ' . esc_html( $group->title ? $group->title : ( esc_html__( 'Group', 'anwp-football-leagues' ) . ' #' . ( $index + 1 ) ) );
				}
			}
		}

		return $generated_title;
	}

	/**
	 * Get Game Ids with Custom Outcome
	 *
	 * @param int $competition_id
	 * @param int $group_id
	 *
	 * @return array Array of game ids
	 * @since 0.11.2
	 */
	public function get_games_with_custom_outcome( $competition_id, $group_id ) {
		global $wpdb;

		if ( ! absint( $competition_id ) || ! absint( $group_id ) ) {
			return [];
		}

		// Get games with custom outcome
		$custom_query = "
		SELECT p.ID
		FROM $wpdb->posts p
		LEFT JOIN $wpdb->postmeta pm1 ON ( pm1.post_id = p.ID AND pm1.meta_key = '_anwpfl_competition' )
		LEFT JOIN $wpdb->postmeta pm2 ON ( pm2.post_id = p.ID AND pm2.meta_key = '_anwpfl_competition_group' )
		LEFT JOIN $wpdb->postmeta pm3 ON ( pm3.post_id = p.ID AND pm3.meta_key = '_anwpfl_status' )
		LEFT JOIN $wpdb->postmeta pm4 ON ( pm4.post_id = p.ID AND pm4.meta_key = '_anwpfl_custom_outcome' )
		WHERE p.post_type = 'anwp_match' AND p.post_status = 'publish' AND pm3.meta_value = 'result' AND pm4.meta_value = 'yes'
		";

		$custom_query .= $wpdb->prepare( ' AND pm1.meta_value = %d ', $competition_id );
		$custom_query .= $wpdb->prepare( ' AND pm2.meta_value = %d ', $group_id );
		$custom_query .= ' GROUP BY p.ID';

		return $wpdb->get_col( $custom_query ); // phpcs:ignore WordPress.DB.PreparedSQL
	}
}
