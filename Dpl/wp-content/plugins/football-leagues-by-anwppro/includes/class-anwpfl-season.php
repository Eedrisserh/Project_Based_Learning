<?php
/**
 * AnWP Football Leagues :: Season.
 *
 * @since   0.1.0
 * @package AnWP_Football_Leagues
 */

require_once dirname( __FILE__ ) . '/../vendor/taxonomy-core/Taxonomy_Core.php';

/**
 * AnWP Football Leagues :: Season.
 *
 * @since 0.1.0
 *
 * @see   https://github.com/WebDevStudios/Taxonomy_Core
 */
class AnWPFL_Season extends Taxonomy_Core {

	/**
	 * Parent plugin class.
	 *
	 * @var    AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 * Register Taxonomy.
	 *
	 * See documentation in Taxonomy_Core, and in wp-includes/taxonomy.php.
	 *
	 * @since  0.1.0
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->hooks();

		parent::__construct(
			[ // Should be an array with Singular, Plural, and Registered name.
				esc_html__( 'Season', 'anwp-football-leagues' ),
				esc_html__( 'Seasons', 'anwp-football-leagues' ),
				'anwp_season',
			],
			[ // Register taxonomy arguments.
				'hierarchical'      => false,
				'show_in_nav_menus' => false,
				'rewrite'           => [ 'slug' => 'season' ],
				'show_in_menu'      => 'anwp-football-leagues',
				'labels'            => [
					'search_items'  => esc_html__( 'Search Seasons', 'anwp-football-leagues' ),
					'all_items'     => esc_html__( 'All Seasons', 'anwp-football-leagues' ),
					'edit_item'     => esc_html__( 'Edit Season', 'anwp-football-leagues' ),
					'view_item'     => esc_html__( 'View Season', 'anwp-football-leagues' ),
					'update_item'   => esc_html__( 'Update Season', 'anwp-football-leagues' ),
					'add_new_item'  => esc_html__( 'Add New Season', 'anwp-football-leagues' ),
					'new_item_name' => esc_html__( 'New Season Title', 'anwp-football-leagues' ),
				],
			],
			[ // Post types to attach to.
				'anwp_competition',
			]
		);
	}

	/**
	 * Helper function, returns seasons with id and title
	 *
	 * @since 0.2.0 (2018-01-10)
	 * @return array $output_data - Array of seasons objects.
	 */
	public function get_seasons_list() {

		static $output_data = null;

		if ( null === $output_data ) {

			$output_data = [];

			$all_seasons = get_terms(
				[
					'taxonomy'         => 'anwp_season',
					'suppress_filters' => false,
					'hide_empty'       => false,
					'orderby'          => 'name',
					'order'            => 'DESC',
				]
			);

			/** @var WP_Term $season */
			foreach ( $all_seasons as $season ) {

				$season_obj        = (object) [];
				$season_obj->id    = $season->term_id;
				$season_obj->title = $season->name;
				$season_obj->slug  = $season->slug;

				$output_data[] = $season_obj;
			}
		}

		return $output_data;
	}

	/**
	 * Get Season slug by term_id
	 *
	 * @param int $term_id
	 *
	 * @return string
	 * @since 0.11.6
	 */
	public function get_season_slug_by_id( $term_id ) {

		$slug    = '';
		$seasons = $this->get_seasons_list();

		if ( ! empty( $seasons ) ) {
			$season_obj = array_values( wp_list_filter( $seasons, [ 'id' => $term_id ] ) );

			if ( ! empty( $season_obj[0] ) && ! empty( $season_obj[0]->slug ) ) {
				return $season_obj[0]->slug;
			}
		}

		return $slug;
	}

	/**
	 * Get Season term_id by slug
	 *
	 * @param string slug
	 *
	 * @return int|string
	 * @since 0.11.6
	 */
	public function get_season_id_by_slug( $term_slug ) {

		$id      = '';
		$seasons = $this->get_seasons_list();

		if ( ! empty( $seasons ) ) {
			$season_obj = array_values( wp_list_filter( $seasons, [ 'slug' => $term_slug ] ) );

			if ( ! empty( $season_obj[0] ) && ! empty( $season_obj[0]->id ) ) {
				return $season_obj[0]->id;
			}
		}

		return $id;
	}

	/**
	 * Helper function, returns seasons with id and title.
	 * Can be used as CMB2 callback options.
	 *
	 * @since 0.3.0 (2018-02-03)
	 * @return array $output_data - Array <season_id> => <season_title>.
	 */
	public function get_seasons_options() {

		static $output_data = null;

		if ( null === $output_data ) {

			$output_data = [];

			foreach ( $this->get_seasons_list() as $season ) {
				$output_data[ $season->id ] = $season->title;
			}
		}

		return $output_data;
	}

	/**
	 * Helper function, returns seasons with slug and title.
	 *
	 * @since 0.5.0 (2018-03-12)
	 * @return array $output_data
	 */
	public function get_season_slug_options() {

		static $output_data = null;

		if ( null === $output_data ) {

			$output_data = [];

			foreach ( $this->get_seasons_list() as $season ) {
				$output_data[] = [
					'slug' => $season->slug,
					'name' => $season->title,
				];
			}
		}

		return $output_data;
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since 0.1.0
	 */
	public function hooks() {

		add_action( 'anwp_season_add_form_fields', [ $this, 'add_to_form' ] );

		add_filter( 'manage_anwp_season_custom_column', [ $this, 'columns_display' ], 10, 3 );
		add_filter( 'manage_edit-anwp_season_columns', [ $this, 'columns' ], 10, 1 );

		add_action( 'created_anwp_season', [ $this, 'set_default_season' ] );
	}

	/**
	 * Registers admin columns to display.
	 *
	 * @param  array $columns Array of registered column names/labels.
	 *
	 * @return array          Modified array.
	 * @since  0.5.1 (2018-03-23)
	 */
	public function columns( $columns ) {

		// Add new columns
		$new_columns = [
			'anwpfl_season_id' => esc_html__( 'ID', 'anwp-football-leagues' ),
		];

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Set default season, if not set.
	 *
	 * @param $term_id
	 *
	 * @since 0.11.7
	 */
	public function set_default_season( $term_id ) {

		if ( ! AnWPFL_Options::get_value( 'active_season' ) && function_exists( 'cmb2_update_option' ) ) {
			cmb2_update_option( 'anwp_football_leagues_options', 'active_season', $term_id );
		}
	}

	/**
	 * Handles admin column display.
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 *
	 * @since  0.5.1 (2018-03-23)
	 */
	public function columns_display( $string, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'anwpfl_season_id':
				echo (int) $term_id;
				break;
		}
	}

	/**
	 * Add notify about naming recommendations.
	 *
	 * @since 0.2.0 (2018-01-27)
	 */
	public function add_to_form() {

		ob_start();
		?>
		<div class="anwp-global-info">
			<?php echo esc_html__( 'Recommended season name is "YYYY" or "YYYY-YYYY".', 'anwp-football-leagues' ); ?>
		</div>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Method returns array of all seasons for TinyMCE listbox.
	 * @deprecated Will be removed soon
	 *
	 * @return array
	 * @since 0.5.4 (2018-03-29)
	 */
	public function mce_get_season_options() {

		$options = [];
		$seasons = $this->get_seasons_options();

		if ( empty( $seasons ) || ! is_array( $seasons ) ) {
			return $options;
		}

		foreach ( $seasons as $season_id => $season_name ) {

			$options[] = (object) [
				'text'  => $season_name,
				'value' => $season_id,
			];
		}

		return $options;
	}
}
