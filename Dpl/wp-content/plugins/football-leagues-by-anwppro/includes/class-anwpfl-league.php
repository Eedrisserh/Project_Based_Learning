<?php
/**
 * AnWP Football Leagues :: League.
 *
 * @since   0.1.0
 * @package AnWP_Football_Leagues
 */

require_once dirname( __FILE__ ) . '/../vendor/taxonomy-core/Taxonomy_Core.php';

/**
 * AnWP Football Leagues :: League taxonomy.
 *
 * @since 0.1.0
 * @see   https://github.com/WebDevStudios/Taxonomy_Core
 */
class AnWPFL_League extends Taxonomy_Core {

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
				esc_html__( 'League', 'anwp-football-leagues' ),
				esc_html__( 'Leagues', 'anwp-football-leagues' ),
				'anwp_league',
			],
			[ // Register taxonomy arguments.
				'hierarchical'      => false,
				'show_in_nav_menus' => false,
				'rewrite'           => [ 'slug' => 'league' ],
				'show_in_menu'      => 'anwp-football-leagues',
				'labels'            => [
					'search_items'  => esc_html__( 'Search Leagues', 'anwp-football-leagues' ),
					'all_items'     => esc_html__( 'All Leagues', 'anwp-football-leagues' ),
					'edit_item'     => esc_html__( 'Edit League', 'anwp-football-leagues' ),
					'view_item'     => esc_html__( 'View League', 'anwp-football-leagues' ),
					'update_item'   => esc_html__( 'Update League', 'anwp-football-leagues' ),
					'add_new_item'  => esc_html__( 'Add New League', 'anwp-football-leagues' ),
					'new_item_name' => esc_html__( 'New League Title', 'anwp-football-leagues' ),
				],
			],
			[ // Post types to attach to.
				'anwp_competition',
			]
		);

		add_action( 'cmb2_admin_init', [ $this, 'init_cmb2_metaboxes' ] );
	}

	/**
	 * Create CMB2 metabox
	 *
	 * @since 0.10.0 (2019-02-22)
	 */
	public function init_cmb2_metaboxes() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_anwpfl_';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box(
			[
				'id'           => 'anwp_league_metabox',
				'title'        => esc_html__( 'League Info', 'anwp-football-leagues' ),
				'object_types' => [ 'term' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'classes'      => 'anwp-b-wrap',
				'show_names'   => true,
				'taxonomies'   => [ 'anwp_league' ],
			]
		);

		$cmb->add_field(
			[
				'name'             => esc_html__( 'Country', 'anwp-football-leagues' ),
				'id'               => $prefix . 'country',
				'type'             => 'select',
				'show_option_none' => '- ' . esc_html__( 'select country', 'anwp-football-leagues' ) . ' -',
				'default'          => '',
				'options_cb'       => [ $this->plugin->data, 'cb_get_countries' ],
				'column'           => [
					'position' => 4,
				],
				'display_cb'       => [ $this, 'render_league_country_column' ],
			]
		);
	}

	/**
	 * Rendering Country Column content.
	 *
	 * @param            $field_args
	 * @param CMB2_Field $field
	 *
	 * @since 0.10.0
	 */
	public function render_league_country_column( $field_args, $field ) {

		$options = $field->options();

		if ( isset( $options[ $field->value ] ) ) {
			echo esc_html( $options[ $field->value ] );
		} else {
			echo esc_attr( $field->value );
		}
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since 0.1.0
	 */
	public function hooks() {

		add_filter( 'manage_anwp_league_custom_column', [ $this, 'columns_display' ], 10, 3 );
		add_filter( 'manage_edit-anwp_league_columns', [ $this, 'columns' ], 10, 1 );

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
			'anwpfl_league_id' => esc_html__( 'ID', 'anwp-football-leagues' ),
		];

		return array_merge( $columns, $new_columns );
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
			case 'anwpfl_league_id':
				echo (int) $term_id;
				break;
		}
	}

	/**
	 * Helper function, returns leagues with id and title.
	 * Can be used as cmb2 callback options.
	 *
	 * @since 0.5.1 (2018-03-22)
	 * @return array $output_data - Array <league_id> => <league_title>.
	 */
	public function get_league_options() {

		static $output_data = null;

		if ( null === $output_data ) {

			$output_data = [];

			$all_leagues = get_terms(
				[
					'taxonomy'         => 'anwp_league',
					'suppress_filters' => false,
					'hide_empty'       => false,
					'orderby'          => 'name',
					'order'            => 'ASC',
				]
			);

			/** @var WP_Term $league */
			foreach ( $all_leagues as $league ) {
				$output_data[ $league->term_id ] = $league->name;
			}
		}

		return $output_data;
	}

	/**
	 * Helper function, returns leagues objects.
	 * Used in Vue multiselect
	 *
	 * @since 0.10.0
	 * @return array $output_data
	 */
	public function get_leagues_list() {

		static $output_data = null;

		if ( null === $output_data ) {

			$output_data = [];

			$all_leagues = get_terms(
				[
					'taxonomy'   => 'anwp_league',
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
				]
			);

			/** @var WP_Term $league */
			foreach ( $all_leagues as $league ) {

				$country_code = get_term_meta( $league->term_id, '_anwpfl_country', true );

				$output_data[] = (object) [
					'id'           => $league->term_id,
					'name'         => $league->name,
					'country_code' => $country_code ?: '',
					'country'      => anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ),
				];
			}
		}

		return $output_data;
	}

	/**
	 * Get league Country code by league ID
	 *
	 * @return string
	 * @since 0.13.0
	 */
	public function get_league_country_code( $league_id ) {

		static $output_data = null;

		if ( null === $output_data ) {
			global $wpdb;

			$codes = $wpdb->get_results(
				"
				SELECT term_id, meta_value
				FROM $wpdb->termmeta
				WHERE meta_key = '_anwpfl_country' AND meta_value != ''
				"
			);

			foreach ( $codes as $code ) {
				$output_data[ $code->term_id ] = $code->meta_value;
			}
		}

		return isset( $output_data[ $league_id ] ) ? $output_data[ $league_id ] : '';
	}
}
