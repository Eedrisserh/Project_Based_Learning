<?php
/**
 * AnWP Football Leagues Options.
 *
 * @since   0.1.0
 * @package AnWP_Football_Leagues
 */



/**
 * AnWP Football Leagues Options class.
 *
 * @since 0.1.0
 */
class AnWPFL_Options {
	/**
	 * Parent plugin class.
	 *
	 * @var    AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected static $key = 'anwp_football_leagues_options';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected static $metabox_id = 'anwp_football_leagues_options_metabox';

	/**
	 * Options Page title.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $title = '';

	/**
	 * Options Page hook.
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Permalink settings.
	 *
	 * @var array
	 * @since 0.10.8
	 */
	protected $permalinks = [];

	/**
	 * Constructor.
	 *
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 *
	 *@since  0.1.0
	 *
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Set our title.
		$this->title = esc_html__( 'Football Leagues :: Settings', 'anwp-football-leagues' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

		// Hook in our actions to the admin.
		add_action( 'cmb2_admin_init', [ $this, 'add_options_page_metabox' ] );

		// Add tabs functionality
		add_action( 'cmb2_before_options-page_form_anwp_football_leagues_options_metabox', [ $this, 'cmb2_before_metabox' ] );
		add_action( 'cmb2_after_options-page_form_anwp_football_leagues_options_metabox', [ $this, 'cmb2_after_metabox' ] );

		// Override permalink structure
		add_action( 'current_screen', [ $this, 'manage_permalink_structure' ] );
	}

	/**
	 * Manage permalink structure.
	 *
	 * @since 0.10.8
	 */
	public function manage_permalink_structure() {
		$screen = get_current_screen();

		if ( 'options-permalink' !== $screen->id ) {
			return;
		}

		// Get saved permalinks structure
		$this->permalinks = $this->get_permalink_structure();

		$this->permalinks_options();
		$this->save_permalinks_options();
	}

	/**
	 * Rendering permalinks input fields.
	 *
	 * @since 0.10.8
	 */
	public function permalinks_options() {

		add_settings_field(
			'anwpfl_match_base_slug',
			esc_html__( 'FL Match base', 'anwp-football-leagues' ),
			[ $this, 'permalink_match_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_competition_base_slug',
			esc_html__( 'FL Competition base', 'anwp-football-leagues' ),
			[ $this, 'permalink_competition_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_club_base_slug',
			esc_html__( 'FL Club base', 'anwp-football-leagues' ),
			[ $this, 'permalink_club_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_player_base_slug',
			esc_html__( 'FL Player base', 'anwp-football-leagues' ),
			[ $this, 'permalink_player_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_referee_base_slug',
			esc_html__( 'FL Referee base', 'anwp-football-leagues' ),
			[ $this, 'permalink_referee_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_staff_base_slug',
			esc_html__( 'FL Staff base', 'anwp-football-leagues' ),
			[ $this, 'permalink_staff_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_stadium_base_slug',
			esc_html__( 'FL Stadium base', 'anwp-football-leagues' ),
			[ $this, 'permalink_stadium_slug_input' ],
			'permalink',
			'optional'
		);

		add_settings_field(
			'anwpfl_standing_base_slug',
			esc_html__( 'FL Standing base', 'anwp-football-leagues' ),
			[ $this, 'permalink_standing_slug_input' ],
			'permalink',
			'optional'
		);
	}

	/**
	 * Rendering Match input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_match_slug_input() {
		?>
		<input name="anwpfl_match_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['match'] ); ?>" placeholder="<?php echo esc_attr_x( 'match', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Competition input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_competition_slug_input() {
		?>
		<input name="anwpfl_competition_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['competition'] ); ?>" placeholder="<?php echo esc_attr_x( 'competition', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Club input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_club_slug_input() {
		?>
		<input name="anwpfl_club_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['club'] ); ?>" placeholder="<?php echo esc_attr_x( 'club', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Player input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_player_slug_input() {
		?>
		<input name="anwpfl_player_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['player'] ); ?>" placeholder="<?php echo esc_attr_x( 'player', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Referee input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_referee_slug_input() {
		?>
		<input name="anwpfl_referee_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['referee'] ); ?>" placeholder="<?php echo esc_attr_x( 'referee', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Staff input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_staff_slug_input() {
		?>
		<input name="anwpfl_staff_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['staff'] ); ?>" placeholder="<?php echo esc_attr_x( 'staff', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Stadium input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_stadium_slug_input() {
		?>
		<input name="anwpfl_stadium_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['stadium'] ); ?>" placeholder="<?php echo esc_attr_x( 'stadium', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Rendering Standing input field.
	 *
	 * @since 0.10.8
	 */
	public function permalink_standing_slug_input() {
		?>
		<input name="anwpfl_standing_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['standing'] ); ?>" placeholder="<?php echo esc_attr_x( 'table', 'slug', 'anwp-football-leagues' ); ?>"/>
		<?php
	}

	/**
	 * Save the settings.
	 *
	 * @since 0.10.8
	 */
	public function save_permalinks_options() {
		if ( ! is_admin() ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification
		if ( isset( $_POST['permalink_structure'] ) ) {

			$permalink_settings = wp_parse_args(
				$this->get_permalink_structure(),
				[
					'match'       => 'match',
					'competition' => 'competition',
					'club'        => 'club',
					'player'      => 'player',
					'referee'     => 'referee',
					'staff'       => 'staff',
					'stadium'     => 'stadium',
					'standing'    => 'table',
				]
			);

			$permalink_settings['match']       = isset( $_POST['anwpfl_match_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_match_base_slug'], $permalink_settings['match'] ) : $permalink_settings['match'];
			$permalink_settings['competition'] = isset( $_POST['anwpfl_competition_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_competition_base_slug'], $permalink_settings['competition'] ) : $permalink_settings['competition'];
			$permalink_settings['club']        = isset( $_POST['anwpfl_club_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_club_base_slug'], $permalink_settings['club'] ) : $permalink_settings['club'];
			$permalink_settings['player']      = isset( $_POST['anwpfl_player_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_player_base_slug'], $permalink_settings['player'] ) : $permalink_settings['player'];
			$permalink_settings['referee']     = isset( $_POST['anwpfl_referee_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_referee_base_slug'], $permalink_settings['match'] ) : $permalink_settings['match'];
			$permalink_settings['staff']       = isset( $_POST['anwpfl_staff_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_staff_base_slug'], $permalink_settings['staff'] ) : $permalink_settings['staff'];
			$permalink_settings['stadium']     = isset( $_POST['anwpfl_stadium_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_stadium_base_slug'], $permalink_settings['stadium'] ) : $permalink_settings['stadium'];
			$permalink_settings['standing']    = isset( $_POST['anwpfl_standing_base_slug'] ) ? $this->sanitize_permalink( $_POST['anwpfl_standing_base_slug'], $permalink_settings['standing'] ) : $permalink_settings['standing'];

			update_option( 'anwpfl_permalink_structure', wp_json_encode( $permalink_settings ) );
			flush_rewrite_rules();
		}
		// phpcs:enable WordPress.Security.NonceVerification
	}

	/**
	 * Sanitize permalink.
	 *
	 * @param string $value   -
	 * @param string $default -
	 *
	 * @return string
	 * @since  0.10.8
	 */
	private function sanitize_permalink( $value, $default ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			return $default;
		} else {
			$value = esc_url_raw( $value );
			$value = str_replace( 'http://', '', $value );
		}

		return untrailingslashit( $value );
	}

	/**
	 * Method returns permalink structure.
	 *
	 * @since 0.10.8
	 */
	public function get_permalink_structure() {

		$permalink_settings = (array) json_decode( get_option( 'anwpfl_permalink_structure', '' ), true );

		$permalinks = wp_parse_args(
			$permalink_settings,
			[
				'match'       => 'match',
				'competition' => 'competition',
				'club'        => 'club',
				'player'      => 'player',
				'referee'     => 'referee',
				'staff'       => 'staff',
				'stadium'     => 'stadium',
				'standing'    => 'table',
			]
		);

		$permalinks = array_map( 'wp_unslash', $permalinks );

		/**
		 * Filter permalink structure.
		 *
		 * @since 0.10.8
		 */
		$permalinks = apply_filters( 'anwpfl/config/permalinks', $permalinks );

		return $permalinks;
	}

	public function cmb2_before_metabox() {

		// @formatter:off
		ob_start();
		?>
		<div class="anwp-b-wrap">

			<div class="anwp-metabox-tabs d-sm-flex">
				<div class="anwp-metabox-tabs__controls d-flex flex-sm-column flex-wrap">
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-general-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-gear"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'General', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-display-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-eye"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Display', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-api-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-key"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'API Keys', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-custom_fields-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-server"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Custom Fields', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-text-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-text-size"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Text Strings', 'anwp-football-leagues' ); ?></span>
					</div>
					<div class="p-3 anwp-metabox-tabs__control-item" data-target="#anwp-tabs-service-settings_metabox">
						<svg class="anwp-icon anwp-icon--octi d-inline-block"><use xlink:href="#icon-tools"></use></svg>
						<span class="d-block"><?php echo esc_html__( 'Service Links', 'anwp-football-leagues' ); ?></span>
					</div>
					<?php
					/**
					 * Fires in the bottom of options tabs.
					 *
					 * @since 0.9.0
					 */
					do_action( 'anwpfl/cmb2_tabs_control/options' );
					?>
				</div>
				<div class="anwp-metabox-tabs__content p-4 flex-grow-1">
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// @formatter:on
	}

	public function cmb2_after_metabox() {

		// @formatter:off
		ob_start();
		?>
				</div><!-- end of div.anwp-metabox-tabs__controls -->
			</div><!-- end of div.anwp-b-wrap -->
		</div><!-- end of div.anwp-metabox-tabs__content -->
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// @formatter:on
	}

	/**
	 * Get text "Matchweek".
	 *
	 * @param $match_week
	 *
	 * @return string
	 * @since 0.8.0
	 */
	public function get_text_matchweek( $match_week ) {

		if ( empty( $match_week ) ) {
			return '';
		}

		if ( trim( self::get_value( 'matchweek_text' ) ) ) {
			return self::get_value( 'matchweek_text' ) . ' ' . $match_week;
		} else {
			return sprintf(
				/* translators: %d: Matchweek number */
				__( 'Matchweek %d', 'anwp-football-leagues' ),
				(int) $match_week
			);
		}
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  0.1.0
	 */
	public function add_options_page_metabox() {

		// Add our CMB2 metabox.
		$cmb = new_cmb2_box(
			[
				'id'           => self::$metabox_id,
				'title'        => $this->title,
				'object_types' => [ 'options-page' ],
				'classes'      => 'anwp-b-wrap anwp-settings',
				'option_key'   => self::$key,
				'menu_title'   => esc_html__( 'Settings', 'anwp-football-leagues' ),
				'parent_slug'  => 'anwp-settings-tools',
				'capability'   => 'manage_options',
			]
		);

		$cmb->add_field(
			[
				'type'    => 'hidden',
				'id'      => 'anwp_current_page_hash',
				'default' => '',
			]
		);

		$cmb->add_field(
			[
				'name'             => esc_html__( 'Active Season', 'anwp-football-leagues' ),
				'id'               => 'active_season',
				'type'             => 'select',
				'show_option_none' => esc_html__( '- not selected -', 'anwp-football-leagues' ),
				'options_cb'       => [ $this->plugin->season, 'get_seasons_options' ],
				'before_row'       => '<div id="anwp-tabs-general-settings_metabox" class="anwp-metabox-tabs__content-item">',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Hide not used seasons', 'anwp-football-leagues' ),
				'desc'    => esc_html__( 'Hide not used seasons in the Seasons Dropdown', 'anwp-football-leagues' ),
				'id'      => 'hide_not_used_seasons',
				'type'    => 'anwpfl_simple_trigger',
				'default' => '',
				'options' => [
					''    => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'          => esc_html__( 'Match title clubs separator', 'anwp-football-leagues' ),
				'id'            => 'match_title_separator',
				'type'          => 'text_small',
				'label_cb'      => [ $this->plugin, 'cmb2_field_label' ],
				'label_help'    => __( 'Default " - " >> "Team A - Team B". Applied on Match save.', 'anwp-football-leagues' ),
				'label_tooltip' => __( 'E.g.: "vs"', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Match slug generated with', 'anwp-football-leagues' ),
				'id'      => 'match_slug_generated_with',
				'type'    => 'select',
				'default' => 'name',
				'options' => [
					'name' => esc_html__( 'club title', 'anwp-football-leagues' ),
					'slug' => esc_html__( 'club slug', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'  => esc_html__( 'Match title generation rules', 'anwp-football-leagues' ),
				'id'    => 'match_title_generator',
				'type'  => 'text',
				'after' => '<p class="cmb2-metabox-description">' . __( 'Available placeholders: %club_home% - home club name,  %club_away% - away club name, %scores_home% - home scores, %scores_away% - away scores, %competition% - competition title, %kickoff% - kickoff date.', 'anwp-football-leagues' ) . '<br>' . __( 'E.g.: %club_home% - %club_away% %scores_home%:%scores_away% - %kickoff%', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Players dropdown sorting', 'anwp-football-leagues' ),
				'id'         => 'players_dropdown_sorting',
				'type'       => 'select',
				'default'    => 'number',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => __( 'Used on Match edit (for lineups and subs)', 'anwp-football-leagues' ),
				'options'    => [
					'number' => esc_html__( 'number', 'anwp-football-leagues' ),
					'squad'  => esc_html__( 'squad', 'anwp-football-leagues' ),
					'name'   => esc_html__( 'name', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Load Bootstrap 4 CSS files', 'anwp-football-leagues' ),
				'id'      => 'load_bootstrap',
				'type'    => 'select',
				'default' => '',
				'options' => [
					''     => esc_html__( 'yes', 'anwp-football-leagues' ),
					'none' => esc_html__( 'no', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'How to count in statistics "second yellow > red" card', 'anwp-football-leagues' ),
				'id'      => 'yr_card_count',
				'type'    => 'select',
				'default' => 'r',
				'options' => [
					'r'  => esc_html__( 'Red Card', 'anwp-football-leagues' ),
					'yr' => esc_html__( 'Yellow + Red Card', 'anwp-football-leagues' ),
					'y'  => esc_html__( 'Yellow Card', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'How to count in player statistics "yellow + second yellow > red" card', 'anwp-football-leagues' ),
				'id'      => 'player_yr_card_count',
				'type'    => 'select',
				'default' => 'yyr',
				'options' => [
					'yyr' => esc_html__( 'Yellow + Yellow/Red card', 'anwp-football-leagues' ),
					'yr'  => esc_html__( 'Yellow/Red card', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'      => esc_html__( 'Video Player', 'anwp-football-leagues' ),
				'id'        => 'preferred_video_player',
				'type'      => 'select',
				'default'   => 'plyr',
				'options'   => [
					'youtube' => esc_html__( 'Use YouTube player only (Vimeo and custom videos will be ignored)', 'anwp-football-leagues' ),
					'mixed'   => esc_html__( 'Use YouTube player for own video and Plyr player for Vimeo and custom videos', 'anwp-football-leagues' ),
					'plyr'    => esc_html__( 'Use Plyr player for all video types (YouTube, Vimeo, custom)', 'anwp-football-leagues' ),
				],
				'after_row' => '</div>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> General
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name'       => esc_html__( 'General', 'anwp-football-leagues' ),
				'type'       => 'title',
				'before_row' => '<div id="anwp-tabs-display-settings_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'id'         => 'section_display_l1',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Load alternative page layout (experimental)', 'anwp-football-leagues' ),
				'id'      => 'load_alternative_page_layout',
				'type'    => 'anwpfl_simple_trigger',
				'default' => '',
				'options' => [
					''    => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Hide post title for Match and Competition', 'anwp-football-leagues' ),
				'id'      => 'hide_post_titles',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'yes',
				'options' => [
					'no'  => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'          => esc_html__( 'Custom Match Date format', 'anwp-football-leagues' ),
				'id'            => 'custom_match_date_format',
				'type'          => 'text_small',
				'label_cb'      => [ $this->plugin, 'cmb2_field_label' ],
				'label_help'    => __( '<a target="_blank" href="https://codex.wordpress.org/Formatting_Date_and_Time">Date formatting</a>' ),
				'label_tooltip' => __( 'E.g.: "l, F j"', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'          => esc_html__( 'Custom Match Time format', 'anwp-football-leagues' ),
				'label_cb'      => [ $this->plugin, 'cmb2_field_label' ],
				'label_help'    => __( '<a target="_blank" href="https://codex.wordpress.org/Formatting_Date_and_Time">Time formatting</a>' ),
				'label_tooltip' => __( 'E.g.: "H:i"', 'anwp-football-leagues' ),
				'id'            => 'custom_match_time_format',
				'type'          => 'text_small',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Search Results
		|--------------------------------------------------------------------------
		*/

		$cmb->add_field(
			[
				'name' => esc_html__( 'Display in front end search results', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_front_end_search',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Match', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_match',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Club', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_club',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Competition', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_competition',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Player', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_player',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Referee', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_referee',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Staff', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_staff',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Stadium', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_stadium',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Standing', 'anwp-football-leagues' ),
				'id'      => 'display_front_end_search_standing',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Club
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Club', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_club',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Squad Layout', 'anwp-football-leagues' ),
				'id'      => 'club_squad_layout',
				'type'    => 'select',
				'default' => '',
				'options' => [
					''       => esc_html__( 'Table (default)', 'anwp-football-leagues' ),
					'blocks' => esc_html__( 'Blocks', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Squad Blocks Layout - Columns', 'anwp-football-leagues' ),
				'id'      => 'club_squad_layout_block_columns',
				'type'    => 'select',
				'desc'    => esc_html__( 'The number of columns is responsive and will be decreased on small screens.', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''  => '2',
					'3' => '3',
					'4' => '4',
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Show default club logo', 'anwp-football-leagues' ) . ' - <img style="height: 20px; margin-left: 5px; margin-bottom: -5px" src="' . AnWP_Football_Leagues::url( 'public/img/empty_logo.png' ) . '">',
				'id'      => 'show_default_club_logo',
				'type'    => 'anwpfl_simple_trigger',
				'desc'    => esc_html__( 'will be visible if club logo is not set', 'anwp-football-leagues' ),
				'default' => 'yes',
				'options' => [
					'no'  => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'         => esc_html__( 'Custom Default Club Logo', 'anwp-football-leagues' ),
				'id'           => 'default_club_logo',
				'type'         => 'file',
				'options'      => [
					'url' => false,
				],
				'query_args'   => [
					'type' => 'image',
				],
				'preview_size' => 'medium',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Standing
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Standing', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_standing',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Use monospace font-family', 'anwp-football-leagues' ),
				'id'      => 'standing_font_mono',
				'type'    => 'select',
				'default' => 'no',
				'options' => [
					'yes' => esc_html__( 'yes', 'anwp-football-leagues' ),
					'no'  => esc_html__( 'no', 'anwp-football-leagues' ),
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Use Club abbreviation in Mini layout (widget)', 'anwp-football-leagues' ),
				'id'      => 'use_abbr_in_standing_mini',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'yes',
				'options' => [
					'no'  => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Match
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Match', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_match',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Flip Countdown in match fixture', 'anwp-football-leagues' ),
				'id'      => 'fixture_flip_countdown',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Match List
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Match List', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_match_list',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Show Stadium above Match date time (top left)', 'anwp-football-leagues' ),
				'id'      => 'match_slim_stadium_show',
				'type'    => 'anwpfl_simple_trigger',
				'default' => '',
				'options' => [
					''    => [
						'color' => 'neutral',
						'text'  => esc_html__( 'No', 'anwp-football-leagues' ),
					],
					'yes' => [
						'color' => 'success',
						'text'  => esc_html__( 'Yes', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'              => esc_html__( 'Bottom Line Info', 'anwp-football-leagues' ),
				'id'                => 'match_slim_bottom_line',
				'type'              => 'multicheck',
				'options'           => [
					'referee'            => esc_html__( 'Referee', 'anwp-football-leagues' ),
					'referee_assistants' => esc_html__( 'Referee Assistants', 'anwp-football-leagues' ),
					'referee_fourth'     => esc_html__( 'Referee', 'anwp-football-leagues' ) . ' ' . esc_html__( 'Fourth official', 'anwp-football-leagues' ),
					'stadium'            => esc_html__( 'Stadium', 'anwp-football-leagues' ),
				],
				'select_all_button' => false,
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Stadium
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Stadium', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_stadium',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Upcoming matches', 'anwp-football-leagues' ),
				'id'      => 'stadium_rendering_fixture_matches',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Finished matches', 'anwp-football-leagues' ),
				'id'      => 'stadium_rendering_finished_matches',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Player
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Player & Staff', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_player',
			]
		);

		$cmb->add_field(
			[
				'name'         => esc_html__( 'Default Player Photo', 'anwp-football-leagues' ),
				'id'           => 'default_player_photo',
				'type'         => 'file',
				'options'      => [
					'url' => false,
				],
				'query_args'   => [
					'type' => 'image',
				],
				'preview_size' => 'medium',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Main photo caption', 'anwp-football-leagues' ),
				'id'      => 'player_render_main_photo_caption',
				'type'    => 'anwpfl_simple_trigger',
				'default' => 'show',
				'options' => [
					'hide' => [
						'color' => 'neutral',
						'text'  => esc_html__( 'Hide', 'anwp-football-leagues' ),
					],
					'show' => [
						'color' => 'success',
						'text'  => esc_html__( 'Show', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Opposite club name in Latest Player Matches', 'anwp-football-leagues' ),
				'id'      => 'player_opposite_club_name',
				'type'    => 'select',
				'default' => 'abbr',
				'options' => [
					'abbr' => esc_html__( 'Abbreviation', 'anwp-football-leagues' ),
					'full' => esc_html__( 'Full Name', 'anwp-football-leagues' ),
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Display >> Competition
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name' => esc_html__( 'Competition', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_competition',
			]
		);

		$cmb->add_field(
			[
				'name'    => esc_html__( 'Matchweeks order', 'anwp-football-leagues' ),
				'id'      => 'competition_matchweeks_order',
				'type'    => 'anwpfl_simple_trigger',
				'default' => '',
				'options' => [
					''     => [
						'color' => 'success',
						'text'  => esc_html__( 'Ascending (1...30)', 'anwp-football-leagues' ),
					],
					'desc' => [
						'color' => 'success',
						'text'  => esc_html__( 'Descending (30...1)', 'anwp-football-leagues' ),
					],
				],
			]
		);

		$cmb->add_field(
			[
				'name'      => esc_html__( 'Rounds order', 'anwp-football-leagues' ),
				'id'        => 'competition_rounds_order',
				'type'      => 'anwpfl_simple_trigger',
				'after_row' => '</div>',
				'default'   => '',
				'options'   => [
					''     => [
						'color' => 'success',
						'text'  => esc_html__( 'Ascending (1...30)', 'anwp-football-leagues' ),
					],
					'desc' => [
						'color' => 'success',
						'text'  => esc_html__( 'Descending (30...1)', 'anwp-football-leagues' ),
					],
				],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| ## API Keys ##
		|--------------------------------------------------------------------------
		*/
		$cmb->add_field(
			[
				'name'       => esc_html__( 'API Keys', 'anwp-football-leagues' ),
				'type'       => 'title',
				'before_row' => '<div id="anwp-tabs-api-settings_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'id'         => 'section_api',
			]
		);

		// Google Maps API
		$cmb->add_field(
			[
				'name'      => esc_html__( 'Google Maps API Key', 'anwp-football-leagues' ),
				'desc'      => sprintf( ' %s <a href="%s" target="_blank">get Google Maps API Key</a>', esc_html__( 'Google Map is used to locate stadiums. You can get key here - ', 'anwp-football-leagues' ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#--api' ) ),
				'id'        => 'google_maps_api',
				'type'      => 'text',
				'after_row' => '</div>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| ## Custom fields ##
		|--------------------------------------------------------------------------
		*/

		$html_custom_fields_top  = '<div id="anwp-tabs-custom_fields-settings_metabox" class="anwp-metabox-tabs__content-item d-none">';
		$html_custom_fields_top .= $this->render_docs_link( 'custom_fields' );
		$html_custom_fields_top .= '<h3>' . esc_html__( 'Set Dynamic Custom Fields for', 'anwp-football-leagues' ) . '</h3>';

		// Player Custom Fields
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Player', 'anwp-football-leagues' ),
				'id'         => 'player_custom_fields',
				'type'       => 'text',
				'repeatable' => true,
				'before_row' => $html_custom_fields_top,
				'text'       => [
					'add_row_text' => esc_html__( 'Add field', 'anwp-football-leagues' ),
				],
			]
		);

		// Club Custom Fields
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Club', 'anwp-football-leagues' ),
				'id'         => 'club_custom_fields',
				'type'       => 'text',
				'repeatable' => true,
				'text'       => [
					'add_row_text' => esc_html__( 'Add field', 'anwp-football-leagues' ),
				],
			]
		);

		// Stadium Custom Fields
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Stadium', 'anwp-football-leagues' ),
				'id'         => 'stadium_custom_fields',
				'type'       => 'text',
				'repeatable' => true,
				'text'       => [
					'add_row_text' => esc_html__( 'Add field', 'anwp-football-leagues' ),
				],
			]
		);

		// Staff Custom Fields
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Staff', 'anwp-football-leagues' ),
				'id'         => 'staff_custom_fields',
				'type'       => 'text',
				'repeatable' => true,
				'text'       => [
					'add_row_text' => esc_html__( 'Add field', 'anwp-football-leagues' ),
				],
			]
		);

		// Referee Custom Fields
		$cmb->add_field(
			[
				'name'       => esc_html__( 'Referee', 'anwp-football-leagues' ),
				'id'         => 'referee_custom_fields',
				'type'       => 'text',
				'repeatable' => true,
				'text'       => [
					'add_row_text' => esc_html__( 'Add field', 'anwp-football-leagues' ),
				],
				'after_row'  => '</div>',
			]
		);

		/*
		|--------------------------------------------------------------------------
		| ## Text Strings ##
		|--------------------------------------------------------------------------
		*/

		$text_tab_notice = '<div class="alert alert-info p-3">' . esc_html__( 'You can override text strings listed below.', 'anwp-football-leagues' ) . '</div>';

		$cmb->add_field(
			[
				'name'       => esc_html__( 'MatchWeek', 'anwp-football-leagues' ),
				'desc'       => esc_html__( 'e.g. Matchday', 'anwp-football-leagues' ),
				'id'         => 'matchweek_text',
				'type'       => 'text',
				'before_row' => '<div id="anwp-tabs-text-settings_metabox" class="anwp-metabox-tabs__content-item d-none">' . $text_tab_notice,
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goalkeeper', 'position', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_goalkeeper',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Abbreviation', 'anwp-football-leagues' ),
				'id'         => 'text_abbr_goalkeeper',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'g', 'goalkeeper - player position Letter', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Single', 'anwp-football-leagues' ),
				'id'         => 'text_single_goalkeeper',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'Goalkeeper', 'position', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Multiple', 'anwp-football-leagues' ),
				'id'         => 'text_multiple_goalkeeper',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html__( 'Goalkeepers', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Defender', 'position', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_defender',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Abbreviation', 'anwp-football-leagues' ),
				'id'         => 'text_abbr_defender',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'd', 'defender - player position Letter', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Single', 'anwp-football-leagues' ),
				'id'         => 'text_single_defender',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'Defender', 'position', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Multiple', 'anwp-football-leagues' ),
				'id'         => 'text_multiple_defender',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html__( 'Defenders', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Midfielder', 'position', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_midfielder',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Abbreviation', 'anwp-football-leagues' ),
				'id'         => 'text_abbr_midfielder',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'm', 'midfielder - player position Letter', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Single', 'anwp-football-leagues' ),
				'id'         => 'text_single_midfielder',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'Midfielder', 'position', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Multiple', 'anwp-football-leagues' ),
				'id'         => 'text_multiple_midfielder',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html__( 'Midfielders', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Forward', 'position', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_forward',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Abbreviation', 'anwp-football-leagues' ),
				'id'         => 'text_abbr_forward',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'f', 'forward - player position Letter', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Single', 'anwp-football-leagues' ),
				'id'         => 'text_single_forward',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html_x( 'Forward', 'position', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Multiple', 'anwp-football-leagues' ),
				'id'         => 'text_multiple_forward',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Default', 'anwp-football-leagues' ) . ': ' . esc_html__( 'Forwards', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Outcome Letters', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_outcome',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'w', 'Win - in club form', 'anwp-football-leagues' ),
				'id'   => 'text_outcome_letter_w',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'd', 'Draw - in club form', 'anwp-football-leagues' ),
				'id'   => 'text_outcome_letter_d',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'l', 'Lost - in club form', 'anwp-football-leagues' ),
				'id'   => 'text_outcome_letter_l',
				'type' => 'text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Standing', 'anwp-football-leagues' ),
				'type' => 'title',
				'id'   => 'section_display_text_standing',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Matches played', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_played_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Matches played', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Matches played', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_played_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html_x( 'M', 'Matches played - on standing page', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Won', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_won_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Won', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Won', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_won_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html_x( 'W', 'Won - on standing page', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Drawn', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_drawn_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Drawn', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Drawn', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_drawn_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html_x( 'D', 'Drawn', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Lost', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_lost_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Lost', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Lost', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_lost_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html_x( 'L', 'Lost - on standing page', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goals for', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_gf_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Goals for', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goals for', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_gf_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'GF', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goals against', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_ga_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Goals against', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goals against', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_ga_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'GA', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goal difference', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_gd_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Goal difference', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Goal difference', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_gd_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'GD', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Points', 'anwp-football-leagues' ) . ': ' . esc_html__( 'tooltip text', 'anwp-football-leagues' ),
				'id'         => 'text_standing_points_tooltip',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'label_help' => esc_html__( 'Points', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Points', 'anwp-football-leagues' ) . ': ' . esc_html__( 'column name', 'anwp-football-leagues' ),
				'id'         => 'text_standing_points_column',
				'type'       => 'text',
				'label_cb'   => [ $this->plugin, 'cmb2_field_label' ],
				'after_row'  => '</div>',
				'label_help' => esc_html_x( 'Pt', 'Points - on standing page', 'anwp-football-leagues' ),
			]
		);

		/*
		|--------------------------------------------------------------------------
		| ## Service Links ##
		|--------------------------------------------------------------------------
		*/

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Service Links', 'anwp-football-leagues' ),
				'type'       => 'title',
				'before_row' => '<div id="anwp-tabs-service-settings_metabox" class="anwp-metabox-tabs__content-item d-none">',
				'id'         => 'section_title_2',
				'after_row'  => [ $this, 'service_links_html' ],
			]
		);

		/*
		|--------------------------------------------------------------------------
		| ## Extra fields ##
		|--------------------------------------------------------------------------
		*/

		/**
		 * Adds extra fields to the metabox.
		 *
		 * @since 0.9.0
		 */
		$extra_fields = apply_filters( 'anwpfl/cmb2_tabs_content/options', [] );

		if ( ! empty( $extra_fields ) && is_array( $extra_fields ) ) {
			foreach ( $extra_fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Render service links.
	 *
	 * @since 0.3.0 (2018-02-06)
	 * @return string
	 */
	public function service_links_html() {

		global $wpdb;

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

		ob_start();
		?>
		<div class="cmb-th">
			<label for=""><?php echo esc_html__( 'Recalculate Stats', 'anwp-football-leagues' ); ?></label>
			<span class="d-block text-muted small">(matches/stats - <?php echo intval( $matches_qty ); ?>/<?php echo intval( $stats_qty ); ?>)</span>
		</div>

		<div class="cmb-td d-flex align-items-center">
			<select name="" id="">
				<option value="">all (with truncate)</option>
				<option value="-20">+ 20</option>
				<option value="-50">+ 50</option>
			</select>
			<button class="button button mt-1 mx-2" data-anwpfl-recalculate-match-stats>start</button>
			<span class="spinner mx-0"></span>
		</div>
		<?php
		/**
		 * Hook: anwpfl/cmb2_tabs_control/service_links_after
		 *
		 * @since 0.10.8
		 */
		do_action( 'anwpfl/cmb2_tabs_control/service_links_after' );

		return ob_get_clean() . '</div>';
	}

	/**
	 * Wrapper function around cmb2_get_option.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $key     Options array key
	 * @param  mixed  $default Optional default value
	 * @return mixed           Option value
	 */
	public static function get_value( $key = '', $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {

			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( self::$key, $key, $default );
		}

		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( self::$key, $default );

		$val = $default;

		if ( 'all' === $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}

	/**
	 * Renders documentation link.
	 *
	 * @param $section
	 *
	 * @return string
	 * @since 0.10.17
	 */
	private function render_docs_link( $section ) {

		$section_link  = '';
		$section_title = '';

		switch ( $section ) {
			case 'custom_fields':
				$section_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/506-dynamic-custom-fields';
				$section_title = esc_html__( 'Settings', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Dynamic Custom Fields', 'anwp-football-leagues' );
				break;
		}

		$output = '<div class="anwp-admin-docs-link d-flex align-items-center table-info border p-2 border-info">';

		$output .= '<svg class="anwp-icon anwp-icon--octi"><use xlink:href="#icon-book"></use></svg>';
		$output .= '<b class="mx-2">' . esc_html__( 'Documentation', 'anwp-football-leagues' ) . ':</b> ';
		$output .= '<a target="_blank" href="' . esc_url( $section_link ) . '">' . esc_html( $section_title ) . '</a>';
		$output .= '</div>';

		return $output;
	}
}
