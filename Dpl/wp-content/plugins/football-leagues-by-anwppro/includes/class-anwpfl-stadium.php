<?php
/**
 * AnWP Football Leagues :: Stadium.
 *
 * @since   0.1.0
 * @package AnWP_Football_Leagues
 */

require_once dirname( __FILE__ ) . '/../vendor/cpt-core/CPT_Core.php';

/**
 * AnWP Football Leagues :: Stadium post type class.
 *
 * @since 0.1.0
 *
 * @see   https://github.com/WebDevStudios/CPT_Core
 */
class AnWPFL_Stadium extends CPT_Core {

	/**
	 * Parent plugin class.
	 *
	 * @var AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 * Register Custom Post Types.
	 *
	 * See documentation in CPT_Core, and in wp-includes/post.php.
	 *
	 * @since  0.1.0
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		$permalink_structure = $plugin->options->get_permalink_structure();
		$permalink_slug      = empty( $permalink_structure['stadium'] ) ? 'stadium' : $permalink_structure['stadium'];

		// Register this cpt.
		parent::__construct(
			[ // array with Singular, Plural, and Registered name
				esc_html__( 'Stadium', 'anwp-football-leagues' ),
				esc_html__( 'Stadiums', 'anwp-football-leagues' ),
				'anwp_stadium',
			],
			[
				'supports'            => [
					'title',
					'comments',
				],
				'rewrite'             => [ 'slug' => $permalink_slug ],
				'show_in_menu'        => 'edit.php?post_type=anwp_club',
				'exclude_from_search' => 'hide' === AnWPFL_Options::get_value( 'display_front_end_search_stadium' ),
				'public'              => true,
				'labels'              => [
					'all_items'    => esc_html__( 'Stadiums', 'anwp-football-leagues' ),
					'add_new'      => esc_html__( 'Add New Stadium', 'anwp-football-leagues' ),
					'add_new_item' => esc_html__( 'Add New Stadium', 'anwp-football-leagues' ),
					'edit_item'    => esc_html__( 'Edit Stadium', 'anwp-football-leagues' ),
					'new_item'     => esc_html__( 'New Stadium', 'anwp-football-leagues' ),
					'view_item'    => esc_html__( 'View Stadium', 'anwp-football-leagues' ),
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
			return esc_html__( 'Stadium Title', 'anwp-football-leagues' );
		}

		return $title;
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

		// Create CMB2 metabox
		add_action( 'cmb2_admin_init', [ $this, 'init_cmb2_metaboxes' ] );
		add_action( 'cmb2_before_post_form_anwp_stadium_metabox', [ $this, 'cmb2_before_metabox' ] );
		add_action( 'cmb2_after_post_form_anwp_stadium_metabox', [ $this, 'cmb2_after_metabox' ] );

		// Render Custom Content below
		add_action(
			'anwpfl/tmpl-stadium/after_wrapper',
			function ( $stadium ) {

				$content_below = get_post_meta( $stadium->ID, '_anwpfl_custom_content_below', true );

				if ( trim( $content_below ) ) {
					echo '<div class="anwp-b-wrap mt-4">' . do_shortcode( $content_below ) . '</div>';
				}
			}
		);
	}

	/**
	 * Renders tabs for metabox. Helper HTML before.
	 *
	 * @since 0.9.0
	 */
	public function cmb2_before_metabox() {
		// @formatter:off
		ob_start();
		?>
		<div class="anwp-b-wrap">
			<div class="anwp-metabox-tabs d-sm-flex">
				<div class="anwp-metabox-tabs__controls d-flex flex-sm-column flex-wrap">
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-general-stadium_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-gear"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'General', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-media-stadium_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-device-camera"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Media', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-desc-stadium_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-note"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Info', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-custom_fields-stadium_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-server"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Custom Fields', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-bottom_content-stadium_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-repo-push"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Bottom Content', 'anwp-football-leagues' ); ?></span>
					</div>
					<?php
					/**
					 * Fires in the bottom of stadium tabs.
					 * Add new tabs here.
					 *
					 * @since 0.9.0
					 */
					do_action( 'anwpfl/cmb2_tabs_control/stadium' );
					?>
				</div>
				<div class="anwp-metabox-tabs__content pl-4 pb-4">
		<?php
		echo ob_get_clean(); // WPCS: XSS ok.
		// @formatter:on
	}

	/**
	 * Renders tabs for metabox. Helper HTML after.
	 *
	 * @since 0.9.0
	 */
	public function cmb2_after_metabox() {
		// @formatter:off
		ob_start();
		?>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean(); // WPCS: XSS ok.
		// @formatter:on
	}

	/**
	 * Create CMB2 metaboxes
	 *
	 * @since 0.2.0 (2018-01-06)
	 */
	public function init_cmb2_metaboxes() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_anwpfl_';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box(
			[
				'id'           => 'anwp_stadium_metabox',
				'title'        => esc_html__( 'Stadium Info', 'anwp-football-leagues' ),
				'object_types' => [ 'anwp_stadium' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'classes'      => 'anwp-b-wrap',
				'show_names'   => true,
			]
		);

		// Clubs
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Clubs', 'anwp-football-leagues' ),
				'id'         => $prefix . 'clubs',
				'type'       => 'anwp_fl_multiselect',
				'options_cb' => [ $this->plugin->club, 'get_clubs_options' ],
				'before_row' => '<div id="anwp-tabs-general-stadium_metabox" class="anwp-metabox-tabs__content-item">',
			]
		);

		// Address
		$cmb->add_field(
			[
				'name' => esc_html__( 'Address', 'anwp-football-leagues' ),
				'id'   => $prefix . 'address',
				'type' => 'text',
			]
		);

		// City
		$cmb->add_field(
			[
				'name' => esc_html__( 'City', 'anwp-football-leagues' ),
				'id'   => $prefix . 'city',
				'type' => 'text',
			]
		);

		// Website
		$cmb->add_field(
			[
				'name' => esc_html__( 'Website', 'anwp-football-leagues' ),
				'id'   => $prefix . 'website',
				'type' => 'text',
			]
		);

		// Capacity
		$cmb->add_field(
			[
				'name' => esc_html__( 'Capacity', 'anwp-football-leagues' ),
				'id'   => $prefix . 'capacity',
				'type' => 'text',
			]
		);

		// Opened
		$cmb->add_field(
			[
				'name' => esc_html__( 'Opened', 'anwp-football-leagues' ),
				'id'   => $prefix . 'opened',
				'type' => 'text',
			]
		);

		// Surface
		$cmb->add_field(
			[
				'name' => esc_html__( 'Surface', 'anwp-football-leagues' ),
				'id'   => $prefix . 'surface',
				'type' => 'text',
			]
		);

		// Map
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Map', 'anwp-football-leagues' ),
				'id'         => $prefix . 'map',
				'type'       => 'anwp_map',
				'show_names' => false,
				'after_row'  => '</div>',
				'attributes' => [
					'readonly' => 'readonly',
				],
			]
		);

		$cmb->add_field(
			[
				'name'            => esc_html__( 'Description', 'anwp-football-leagues' ),
				'id'              => $prefix . 'description',
				'type'            => 'wysiwyg',
				'options'         => [
					'wpautop'       => true,
					'media_buttons' => true,
					'textarea_name' => 'anwp_stadium_description_input',
					'textarea_rows' => 10,
					'teeny'         => false, // output the minimal editor config used in Press This
					'dfw'           => false, // replace the default fullscreen with DFW (needs specific css)
					'tinymce'       => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
				],
				'show_names'      => false,
				'sanitization_cb' => false,
				'before_row'      => '<div id="anwp-tabs-desc-stadium_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'after_row'       => '</div>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Media
		|--------------------------------------------------------------------------
		*/

		// Photo
		$cmb->add_field(
			[
				'name'         => esc_html__( 'Photo', 'anwp-football-leagues' ),
				'id'           => $prefix . 'photo',
				'type'         => 'file',
				'options'      => [
					'url' => false, // Hide the text input for the url
				],
				// query_args are passed to wp.media's library query.
				'query_args'   => [
					'type' => 'image',
				],
				'preview_size' => 'medium', // Image size to use when previewing in the admin.
				'before_row'   => '<div id="anwp-tabs-media-stadium_metabox" class="anwp-metabox-tabs__content-item d-none">',
			]
		);

		// Photo
		$cmb->add_field(
			[
				'name'         => esc_html__( 'Gallery', 'anwp-football-leagues' ),
				'id'           => $prefix . 'gallery',
				'type'         => 'file_list',
				'options'      => [
					'url' => false, // Hide the text input for the url
				],
				// query_args are passed to wp.media's library query.
				'query_args'   => [
					'type' => 'image',
				],
				'preview_size' => 'medium', // Image size to use when previewing in the admin.
			]
		);

		// Notes
		$cmb->add_field(
			[
				'name'      => esc_html__( 'Text below gallery', 'anwp-football-leagues' ),
				'id'        => $prefix . 'gallery_notes',
				'type'      => 'textarea_small',
				'after_row' => '</div>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Custom Fields Metabox
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Title', 'anwp-football-leagues' ) . ' #1',
				'id'         => $prefix . 'custom_title_1',
				'before_row' => '<div id="anwp-tabs-custom_fields-stadium_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'type'       => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Value', 'anwp-football-leagues' ) . ' #1',
				'id'   => $prefix . 'custom_value_1',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Title', 'anwp-football-leagues' ) . ' #2',
				'id'   => $prefix . 'custom_title_2',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Value', 'anwp-football-leagues' ) . ' #2',
				'id'   => $prefix . 'custom_value_2',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Title', 'anwp-football-leagues' ) . ' #3',
				'id'   => $prefix . 'custom_title_3',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Value', 'anwp-football-leagues' ) . ' #3',
				'id'   => $prefix . 'custom_value_3',
				'type' => 'text',
			]
		);

		// Dynamic Custom Fields
		$cmb->add_field(
			[
				'name'        => esc_html__( 'Dynamic Custom Fields', 'anwp-football-leagues' ),
				'id'          => $prefix . 'custom_fields',
				'type'        => 'anwp_fl_custom_fields',
				'option_slug' => 'stadium_custom_fields',
				'after_row'   => '</div>',
				'before_row'  => '<hr>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Custom Content Below
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Content', 'anwp-football-leagues' ),
				'id'         => $prefix . 'custom_content_below',
				'type'       => 'wysiwyg',
				'options'    => [
					'wpautop'       => true,
					'media_buttons' => true, // show insert/upload button(s)
					'textarea_name' => 'anwp_custom_content_below',
					'textarea_rows' => 5,
					'teeny'         => false, // output the minimal editor config used in Press This
					'dfw'           => false, // replace the default fullscreen with DFW (needs specific css)
					'tinymce'       => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
				],
				'show_names' => false,
				'before_row' => '<div id="anwp-tabs-bottom_content-stadium_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'after_row'  => '</div>',
			]
		);

		/**
		 * Adds extra fields to the metabox.
		 *
		 * @since 0.9.0
		 */
		$extra_fields = apply_filters( 'anwpfl/cmb2_tabs_content/stadium', [] );

		if ( ! empty( $extra_fields ) && is_array( $extra_fields ) ) {
			foreach ( $extra_fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Helper function, returns stadiums with id and title
	 *
	 * @since 0.2.0 (2018-01-22)
	 * @return array $output_data - Array of stadiums data
	 */
	public function get_stadiums() {

		$output_data = [];

		$all_stadiums = get_posts(
			[
				'numberposts' => - 1,
				'post_type'   => 'anwp_stadium',
				'orderby'     => 'name',
				'order'       => 'ASC',
			]
		);

		/** @var WP_Post $stadium */
		foreach ( $all_stadiums as $stadium ) {

			$stadium_obj        = (object) [];
			$stadium_obj->id    = $stadium->ID;
			$stadium_obj->title = $stadium->post_title;

			if ( $stadium->_anwpfl_city ) {
				$stadium_obj->title .= ' (' . $stadium->_anwpfl_city . ')';
			}

			$output_data[] = $stadium_obj;
		}

		return $output_data;
	}

	/**
	 * Callback function.
	 * Use it to render stadium list in the CMB2 metabox select field.
	 *
	 * @since 0.2.0 (2017-11-19)
	 * @return array
	 */
	public function get_stadiums_options() {

		static $options = null;

		if ( null === $options ) {
			$args = [
				'post_type'        => 'anwp_stadium',
				'posts_per_page'   => - 1,
				'orderby'          => 'title',
				'order'            => 'ASC',
				'suppress_filters' => false,
			];

			$query   = new WP_Query( $args );
			$options = [];

			if ( $query->have_posts() ) {

				/** @var  $p WP_Post */
				foreach ( $query->get_posts() as $p ) {
					$options[ $p->ID ] = $p->post_title;
				}
			}
		}

		return $options;
	}

	/**
	 * Method returns array of all stadiums for TinyMCE listbox.
	 *
	 * @return array
	 * @since 0.5.4 (2018-03-29)
	 */
	public function mce_get_stadium_options() {

		$options  = [];
		$stadiums = $this->get_stadiums_options();

		if ( empty( $stadiums ) || ! is_array( $stadiums ) ) {
			return $options;
		}

		foreach ( $stadiums as $stadium_id => $stadium_title ) {
			$options[] = (object) [
				'text'  => $stadium_title,
				'value' => $stadium_id,
			];
		}

		return $options;
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array.
	 */
	public function columns( $columns ) {

		// Add new columns
		$new_columns = [
			'anwpfl_city' => esc_html__( 'City', 'anwp-football-leagues' ),
		];

		// Merge old and new columns
		$columns = array_merge( $new_columns, $columns );

		// Change columns order
		$new_columns_order = [ 'cb', 'title', 'anwpfl_city', 'comments', 'date' ];
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
	 * @since  0.1.0
	 *
	 * @param array   $column   Column currently being rendered.
	 * @param integer $post_id  ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
			case 'anwpfl_city':
				echo esc_html( get_post_meta( $post_id, '_anwpfl_city', true ) );
				break;
		}
	}

	/**
	 * Get Stadium title
	 *
	 * @param int $stadium_id
	 *
	 * @return string
	 * @since 0.11.0
	 */
	public function get_stadium_title( $stadium_id ) {

		static $options = null;

		if ( null === $options ) {
			global $wpdb;

			$results = $wpdb->get_results(
				"
				SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_status = 'publish' AND post_type = 'anwp_stadium'
				",
				OBJECT_K
			);

			$options = wp_list_pluck( $results, 'post_title', 'ID' );
		}

		return isset( $options[ $stadium_id ] ) ? $options[ $stadium_id ] : '';
	}

	/**
	 * Get Club home Stadium ID
	 *
	 * @param int $club_id
	 *
	 * @return int/string
	 * @since 0.11.2
	 */
	public function get_stadium_id_by_club( $club_id ) {

		if ( empty( $club_id ) || ! absint( $club_id ) ) {
			return '';
		}

		$stadium_id = get_post_meta( $club_id, '_anwpfl_stadium', true );

		return absint( $stadium_id ) ? absint( $stadium_id ) : '';
	}
}
