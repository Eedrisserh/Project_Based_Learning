<?php
/**
 * AnWP Football Leagues :: Staff.
 *
 * @since   0.7.0
 * @package AnWP_Football_Leagues
 */

require_once dirname( __FILE__ ) . '/../vendor/cpt-core/CPT_Core.php';

/**
 * AnWP Football Leagues :: Staff post type class.
 *
 * @since 0.1.0
 *
 * @see   https://github.com/WebDevStudios/CPT_Core
 */
class AnWPFL_Staff extends CPT_Core {

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
		$permalink_slug      = empty( $permalink_structure['staff'] ) ? 'staff' : $permalink_structure['staff'];

		// Register this cpt.
		parent::__construct(
			[ // array with Singular, Plural, and Registered name
				esc_html__( 'Staff', 'anwp-football-leagues' ),
				esc_html__( 'Staff', 'anwp-football-leagues' ),
				'anwp_staff',
			],
			[
				'supports'            => [
					'title',
					'comments',
				],
				'rewrite'             => [ 'slug' => $permalink_slug ],
				'show_in_menu'        => true,
				'menu_position'       => 34,
				'menu_icon'           => 'dashicons-groups',
				'exclude_from_search' => 'hide' === AnWPFL_Options::get_value( 'display_front_end_search_staff' ),
				'public'              => true,
				'labels'              => [
					'all_items'    => esc_html__( 'Staff', 'anwp-football-leagues' ),
					'add_new'      => esc_html__( 'Add Staff Member', 'anwp-football-leagues' ),
					'add_new_item' => esc_html__( 'Add Staff Member', 'anwp-football-leagues' ),
					'edit_item'    => esc_html__( 'Edit Staff Member', 'anwp-football-leagues' ),
					'new_item'     => esc_html__( 'New Staff Member', 'anwp-football-leagues' ),
					'view_item'    => esc_html__( 'View Staff Member', 'anwp-football-leagues' ),
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
			return esc_html__( 'Name', 'anwp-football-leagues' );
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
		add_action( 'cmb2_before_post_form_anwp_staff_metabox', [ $this, 'cmb2_before_metabox' ] );
		add_action( 'cmb2_after_post_form_anwp_staff_metabox', [ $this, 'cmb2_after_metabox' ] );

		// Add custom filter
		add_action( 'restrict_manage_posts', [ $this, 'custom_admin_filters' ] );
		add_filter( 'pre_get_posts', [ $this, 'handle_custom_filter' ] );

		// Render Custom Content below
		add_action(
			'anwpfl/tmpl-staff/after_wrapper',
			function ( $staff_id ) {

				$content_below = get_post_meta( $staff_id, '_anwpfl_custom_content_below', true );

				if ( trim( $content_below ) ) {
					echo '<div class="anwp-b-wrap mt-4">' . do_shortcode( $content_below ) . '</div>';
				}
			}
		);
	}

	/**
	 * Fires before the Filter button on the Posts and Pages list tables.
	 *
	 * The Filter button allows sorting by date and/or category on the
	 * Posts list table, and sorting by date on the Pages list table.
	 *
	 * @param string $post_type The post type slug.
	 */
	public function custom_admin_filters( $post_type ) {

		if ( 'anwp_staff' === $post_type ) {

			$clubs = $this->plugin->club->get_clubs_options();

			$current_club_filter = empty( $_GET['_anwpfl_current_club'] ) ? '' : (int) $_GET['_anwpfl_current_club']; // WPCS: CSRF ok.
			ob_start();
			?>

			<select name='_anwpfl_current_club' id='anwp_club_filter' class='postform'>
				<option value=''>All Clubs</option>
				<?php foreach ( $clubs as $club_id => $club_title ) : ?>
					<option value="<?php echo esc_attr( $club_id ); ?>" <?php selected( $club_id, $current_club_filter ); ?>>
						<?php echo esc_html( $club_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php
			echo ob_get_clean(); // WPCS: XSS ok.
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

		if ( 'edit.php' === $pagenow && 'anwp_staff' === $post_type && ! empty( $_GET['_anwpfl_current_club'] ) ) { // WPCS: CSRF ok.
			$query->set(
				'meta_query',
				[
					[
						'key'     => '_anwpfl_current_club',
						'value'   => (int) $_GET['_anwpfl_current_club'], // WPCS: CSRF ok.
						'compare' => '=',
					],
				]
			);
		}
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
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-general-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-gear"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'General', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-history-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-history"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Staff History', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-media-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-device-camera"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Media', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-desc-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-note"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Bio', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-custom_fields-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-server"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Custom Fields', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-bottom_content-staff_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-repo-push"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Bottom Content', 'anwp-football-leagues' ); ?></span>
					</div>
					<?php
					/**
					 * Fires in the bottom of staff tabs.
					 * Add new tabs here.
					 *
					 * @since 0.9.0
					 */
					do_action( 'anwpfl/cmb2_tabs_control/staff' );
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
	 */
	public function init_cmb2_metaboxes() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_anwpfl_';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box(
			[
				'id'           => 'anwp_staff_metabox',
				'title'        => esc_html__( 'Staff Info', 'anwp-football-leagues' ),
				'object_types' => [ 'anwp_staff' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'classes'      => 'anwp-b-wrap',
				'show_names'   => true,
			]
		);

		// Short Name
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Short Name', 'anwp-football-leagues' ),
				'id'         => $prefix . 'short_name',
				'type'       => 'text',
				'before_row' => '<div id="anwp-tabs-general-staff_metabox" class="anwp-metabox-tabs__content-item">',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Current Club', 'anwp-football-leagues' ),
				'id'         => $prefix . 'current_club',
				'options_cb' => [ $this->plugin->club, 'get_clubs_options' ],
				'type'       => 'anwp_fl_select',
				'attributes' => [
					'placeholder' => esc_html__( '- not selected -', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Additional Current Clubs', 'anwp-football-leagues' ),
				'id'         => $prefix . 'current_clubs',
				'options_cb' => [ $this->plugin->club, 'get_clubs_options' ],
				'type'       => 'anwp_fl_multiselect',
				'attributes' => [
					'placeholder' => esc_html__( '- not selected -', 'anwp-football-leagues' ),
				],
			]
		);

		// Job Title
		$cmb->add_field(
			[
				'name' => esc_html__( 'Job Title', 'anwp-football-leagues' ),
				'id'   => $prefix . 'job_title',
				'type' => 'text',
			]
		);

		// Place of Birth
		$cmb->add_field(
			[
				'name' => esc_html__( 'Place of Birth', 'anwp-football-leagues' ),
				'id'   => $prefix . 'place_of_birth',
				'type' => 'text',
			]
		);

		// Date of Birth
		$cmb->add_field(
			[
				'name'        => esc_html__( 'Date of Birth', 'anwp-football-leagues' ),
				'id'          => $prefix . 'date_of_birth',
				'type'        => 'text_date',
				'date_format' => 'Y-m-d',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'id'         => $prefix . 'nationality',
				'after_row'  => '</div>',
				'type'       => 'anwp_fl_multiselect',
				'options_cb' => [ $this->plugin->data, 'cb_get_countries' ],
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
				'after_row'    => '</div>',
				'before_row'   => '<div id="anwp-tabs-media-staff_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'type'         => 'file',
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

		/*
		|--------------------------------------------------------------------------
		| Description
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name'            => esc_html__( 'Description', 'anwp-football-leagues' ),
				'id'              => $prefix . 'description',
				'type'            => 'wysiwyg',
				'options'         => [
					'wpautop'       => true,
					'media_buttons' => true,
					'textarea_name' => 'anwp_staff_description_input',
					'textarea_rows' => 10,
					'teeny'         => false, // output the minimal editor config used in Press This
					'dfw'           => false, // replace the default fullscreen with DFW (needs specific css)
					'tinymce'       => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
				],
				'show_names'      => false,
				'sanitization_cb' => false,
				'before_row'      => '<div id="anwp-tabs-desc-staff_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'after_row'       => '</div><div id="anwp-tabs-history-staff_metabox" class="anwp-metabox-tabs__content-item d-none">',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| History
		|--------------------------------------------------------------------------
		*/

		$group_field_id = $cmb->add_field(
			[
				'id'      => $prefix . 'staff_history_metabox_group',
				'type'    => 'group',
				'options' => [
					'group_title'   => __( 'Entry {#}', 'anwp-football-leagues' ), // since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add Another Entry', 'anwp-football-leagues' ),
					'remove_button' => __( 'Remove Entry', 'anwp-football-leagues' ),
					'sortable'      => true, // beta
				],
			]
		);

		$cmb->add_group_field(
			$group_field_id,
			[
				'name' => esc_html__( 'Job Title', 'anwp-football-leagues' ),
				'id'   => 'job',
				'type' => 'text',
			]
		);

		$cmb->add_group_field(
			$group_field_id,
			[
				'name'        => esc_html__( 'From', 'anwp-football-leagues' ),
				'id'          => 'from',
				'type'        => 'text_date',
				'date_format' => 'Y-m-d',
			]
		);

		$cmb->add_group_field(
			$group_field_id,
			[
				'name'        => esc_html__( 'To', 'anwp-football-leagues' ),
				'id'          => 'to',
				'type'        => 'text_date',
				'date_format' => 'Y-m-d',
			]
		);

		$cmb->add_group_field(
			$group_field_id,
			[
				'name'       => esc_html__( 'Club', 'anwp-football-leagues' ),
				'id'         => 'club',
				'options_cb' => [ $this->plugin->club, 'get_clubs_options' ],
				'type'       => 'anwp_fl_select',
				'attributes' => [
					'placeholder' => esc_html__( '- not selected -', 'anwp-football-leagues' ),
				],
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
				'before_row' => '</div><div id="anwp-tabs-custom_fields-staff_metabox" class="anwp-metabox-tabs__content-item d-none">',
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
				'option_slug' => 'staff_custom_fields',
				'after_row'   => '</div>',
				'before_row'  => '<hr>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Bottom Content
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
				'before_row' => '<div id="anwp-tabs-bottom_content-staff_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'after_row'  => '</div>',
			]
		);

		/**
		 * Adds extra fields to the metabox.
		 *
		 * @since 0.9.0
		 */
		$extra_fields = apply_filters( 'anwpfl/cmb2_tabs_content/staff', [] );

		if ( ! empty( $extra_fields ) && is_array( $extra_fields ) ) {
			foreach ( $extra_fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array.
	 */
	public function columns( $columns ) {

		// Add new columns
		$new_columns = [
			'anwpfl_staff_job'          => esc_html__( 'Job Title', 'anwp-football-leagues' ),
			'anwpfl_staff_current_club' => esc_html__( 'Current Club', 'anwp-football-leagues' ),
			'staff_id'                  => esc_html__( 'ID', 'anwp-football-leagues' ),
		];

		// Merge old and new columns
		$columns = array_merge( $new_columns, $columns );

		// Change columns order
		$new_columns_order = [
			'cb',
			'title',
			'anwpfl_staff_job',
			'anwpfl_staff_current_club',
			'comments',
			'date',
			'staff_id',
		];

		$new_columns = [];

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
	 * @param array   $column   Column currently being rendered.
	 * @param integer $post_id  ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {

			case 'anwpfl_staff_job':
				echo esc_html( get_post_meta( $post_id, '_anwpfl_job_title', true ) );
				break;

			case 'anwpfl_staff_current_club':
				$club_id       = (int) get_post_meta( $post_id, '_anwpfl_current_club', true );
				$clubs_options = $this->plugin->club->get_clubs_options();

				if ( ! empty( $clubs_options[ $club_id ] ) ) {
					echo esc_html( $clubs_options[ $club_id ] );
				}
				break;

			case 'staff_id':
				echo (int) $post_id;
				break;
		}
	}

	/**
	 * Method returns staff with id and title
	 *
	 * @since 0.7.0
	 * @return array $output_data - Array of all staff members
	 */
	public function get_staff_list() {

		global $wpdb;

		$all_staff = $wpdb->get_results(
			"
			SELECT p.ID id, p.post_title name,
				MAX( CASE WHEN pm.meta_key = '_anwpfl_job_title' THEN pm.meta_value ELSE '' END) as job,
				MAX( CASE WHEN pm.meta_key = '_anwpfl_nationality' THEN pm.meta_value ELSE '' END) as nationality,
				MAX( CASE WHEN pm.meta_key = '_anwpfl_date_of_birth' THEN pm.meta_value ELSE '' END) as birthdate,
				MAX( CASE WHEN pm.meta_key = '_anwpfl_current_club' THEN pm.meta_value ELSE '' END) as club_id,
				MAX( CASE WHEN pm.meta_key = '_anwpfl_photo' THEN pm.meta_value ELSE '' END) as photo
			FROM $wpdb->posts p
			LEFT JOIN $wpdb->postmeta pm ON ( pm.post_id = p.ID )
			WHERE p.post_status = 'publish' AND p.post_type = 'anwp_staff'
			GROUP BY p.ID
			ORDER BY p.post_title
			"
		);

		if ( empty( $all_staff ) ) {
			return [];
		}

		foreach ( $all_staff as $staff ) {

			$staff->id       = absint( $staff->id );
			$staff->club_id  = absint( $staff->club_id );
			$staff->country  = '';
			$staff->country2 = '';

			if ( $staff->birthdate ) {
				$staff->birthdate = date_i18n( get_option( 'date_format' ), strtotime( $staff->birthdate ) );
			}

			if ( $staff->nationality ) {
				$countries = maybe_unserialize( $staff->nationality );

				if ( ! empty( $countries ) && is_array( $countries ) && ! empty( $countries[0] ) ) {
					$staff->country = mb_strtolower( $countries[0] );
				}

				if ( ! empty( $countries ) && is_array( $countries ) && ! empty( $countries[1] ) ) {
					$staff->country2 = mb_strtolower( $countries[1] );
				}
			}

			unset( $staff->nationality );
		}

		return $all_staff;
	}
}
