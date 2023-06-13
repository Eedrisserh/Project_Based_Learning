<?php
/**
 * Text Class
 * AnWP Football Leagues :: Text.
 *
 * @since   0.10.23
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Text {

	/**
	 * Parent plugin class.
	 *
	 * @var    AnWP_Football_Leagues
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 */
	protected static $key = 'anwp_fl_text';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 */
	protected static $metabox_id = 'anwp_fl_text_metabox';

	/**
	 * Options Page title.
	 *
	 * @var    string
	 */
	protected $title = '';

	/**
	 * Options Page hook.
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor.
	 *
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Set our title.
		$this->title = esc_html__( 'Football Leagues', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Text Options', 'anwp-football-leagues' );
	}

	/**
	 * Initiate our hooks.
	 */
	public function hooks() {

		// Hook in our actions to the admin.
		add_action( 'cmb2_admin_init', [ $this, 'add_config_page_metabox' ] );

		// Inject some HTML before CMB2 form
		add_action( 'cmb2_before_options-page_form_anwp_fl_text_metabox', [ $this, 'cmb2_before_metabox' ] );
	}

	/**
	 * Special HTML before CMB2 metabox.
	 */
	public function cmb2_before_metabox() {

		$admin_system_texts = admin_url( 'admin.php?page=anwp_football_leagues_options#anwp-tabs-text-settings_metabox' );

		/*
		|--------------------------------------------------------------------
		| Start Output
		|--------------------------------------------------------------------
		*/
		ob_start();
		?>
		<div class="cmb2-wrap form-table anwp-b-wrap anwp-settings">

			<div class="alert alert-info border-info mt-n2 mb-3">
				<svg class="anwp-icon anwp-icon--s14 anwp-icon--octi mr-1"><use xlink:href="#icon-light-bulb"></use></svg>
				Some system text strings (such as "Matchweek", Standing columns and Player positions) are available to translate at the settings page - <a href="<?php echo esc_url( $admin_system_texts ); ?>" target="_blank">link</a>
			</div>

			<div class="d-flex align-items-center mb-2">
				<span class="mr-2"><?php echo esc_html__( 'Search Text String', 'anwp-football-leagues' ); ?>:</span>
				<input type="text" id="anwp-fl-live-text-search">
			</div>

			<div class="cmb2-metabox cmb-field-list">
				<div class="cmb-row bg-light">
					<div class="row align-items-center">
						<div class="col-sm-4"><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></div>
						<div class="col-sm-4"><?php echo esc_html__( 'New', 'anwp-football-leagues' ); ?></div>
						<div class="col-sm-4"><?php echo esc_html__( 'Context', 'anwp-football-leagues' ); ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add custom fields to the options page.
	 */
	public function add_config_page_metabox() {

		// Add our CMB2 metabox.
		$cmb = new_cmb2_box(
			[
				'id'           => self::$metabox_id,
				'title'        => $this->title,
				'object_types' => [ 'options-page' ],
				'classes'      => 'anwp-b-wrap anwp-settings',
				'option_key'   => self::$key,
				'show_names'   => false,
				'capability'   => 'manage_options',
				'parent_slug'  => 'anwp-settings-tools',
				'menu_title'   => esc_html__( 'Text Options', 'anwp-football-leagues' ),
			]
		);

		$cmb->add_field(
			[
				'id'      => 'hidden_dummy',
				'type'    => 'hidden',
				'default' => 'System field to fix empty saving error',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Fixtures', 'anwp-football-leagues' ),
				'desc' => 'club :: fixtures',
				'id'   => 'club__fixtures__fixtures',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Gallery', 'anwp-football-leagues' ),
				'desc' => 'club :: gallery',
				'id'   => 'club__gallery__gallery',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'City', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__city',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Country', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__country',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Address', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__address',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Website', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__website',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Founded', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__founded',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Stadium', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__stadium',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Club Kit', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__club_kit',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Social', 'anwp-football-leagues' ),
				'desc' => 'club :: header',
				'id'   => 'club__header__social',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Latest Matches', 'anwp-football-leagues' ),
				'desc' => 'club :: latest',
				'id'   => 'club__latest__latest_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Finished matches', 'anwp-football-leagues' ),
				'desc' => 'referee :: finished',
				'id'   => 'referee__finished__finished_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Upcoming matches', 'anwp-football-leagues' ),
				'desc' => 'referee :: fixtures',
				'id'   => 'referee__fixtures__upcoming_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'no data', 'anwp-football-leagues' ),
				'desc' => 'referee :: no data',
				'id'   => 'referee__finished__no_data',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Attendance', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__attendance',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Referee', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__referee',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Half Time', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__half_time',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Match Postponed', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__match_postponed',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Full Time', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__full_time',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'AET', 'Abbr: after extra time', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__aet',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Penalties', 'on penalties', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__penalties',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( '- match preview -', 'anwp-football-leagues' ),
				'desc' => 'match :: header',
				'id'   => 'match__match__match_preview',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Cards', 'anwp-football-leagues' ),
				'desc' => 'match :: cards',
				'id'   => 'match__cards__cards',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Gallery', 'anwp-football-leagues' ),
				'desc' => 'match :: gallery',
				'id'   => 'match__gallery__gallery',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals', 'anwp-football-leagues' ),
				'desc' => 'match :: goals',
				'id'   => 'match__goals__goals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal (own)', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: goals',
				'id'   => 'match__goals__goal_own',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal (from penalty)', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: goals',
				'id'   => 'match__goals__goal_penalty',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: goals',
				'id'   => 'match__goals__goal',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Latest Matches', 'anwp-football-leagues' ),
				'desc' => 'match :: latest',
				'id'   => 'match__latest__latest_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Line Ups', 'anwp-football-leagues' ),
				'desc' => 'match :: lineups',
				'id'   => 'match__lineups__line_ups',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Substitutes', 'anwp-football-leagues' ),
				'desc' => 'match :: lineups',
				'id'   => 'match__lineups__substitutes',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Coach', 'anwp-football-leagues' ),
				'desc' => 'match :: lineups',
				'id'   => 'match__lineups__coach',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Suspended', 'anwp-football-leagues' ),
				'desc' => 'match :: missing players',
				'id'   => 'match__missing__suspended',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Injured', 'anwp-football-leagues' ),
				'desc' => 'match :: missing players',
				'id'   => 'match__missing__injured',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Missing Players', 'anwp-football-leagues' ),
				'desc' => 'match :: missing players',
				'id'   => 'match__missing__missing_players',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Missed penalties', 'anwp-football-leagues' ),
				'desc' => 'match :: missed penalties',
				'id'   => 'match__missed_penalties__missed_penalties',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ),
				'desc' => 'match :: penalty shootout',
				'id'   => 'match__penalty_shootout__penalty_shootout',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Scored', 'penalty shootout', 'anwp-football-leagues' ),
				'desc' => 'match :: penalty shootout',
				'id'   => 'match__penalty_shootout__scored',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Missed', 'penalty shootout', 'anwp-football-leagues' ),
				'desc' => 'match :: penalty shootout',
				'id'   => 'match__penalty_shootout__missed',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Referees', 'anwp-football-leagues' ),
				'desc' => 'match :: referees',
				'id'   => 'match__referees__referees',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Referee', 'anwp-football-leagues' ),
				'desc' => 'match :: referees',
				'id'   => 'match__referees__referee',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assistant Referee', 'anwp-football-leagues' ),
				'desc' => 'match :: referees',
				'id'   => 'match__referees__assistant',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Fourth official', 'anwp-football-leagues' ),
				'desc' => 'match :: referees',
				'id'   => 'match__referees__fourth_official',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Match Statistics', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__match_statistics',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Shots', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__shots',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Shots on Target', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__shots_on_target',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Fouls', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__fouls',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Corners', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__corners',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Offsides', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__offsides',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Ball Possession', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__ball_possession',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Yellow Cards', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__yellow_cards',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( '2d Yellow > Red Cards', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__2_d_yellow_red_cards',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Red Cards', 'anwp-football-leagues' ),
				'desc' => 'match :: stats',
				'id'   => 'match__stats__red_cards',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Substitutes', 'anwp-football-leagues' ),
				'desc' => 'match :: substitutes',
				'id'   => 'match__substitutes__substitutes',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Out', 'substitute event', 'anwp-football-leagues' ),
				'desc' => 'match :: substitutes',
				'id'   => 'match__substitutes__out',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'In', 'substitute event', 'anwp-football-leagues' ),
				'desc' => 'match :: substitutes',
				'id'   => 'match__substitutes__in',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Match Summary', 'anwp-football-leagues' ),
				'desc' => 'match :: summary',
				'id'   => 'match__summary__match_summary',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Match Preview', 'anwp-football-leagues' ),
				'desc' => 'match :: summary',
				'id'   => 'match__summary__match_preview',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Match Video', 'anwp-football-leagues' ),
				'desc' => 'match :: video',
				'id'   => 'match__video__match_video',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'years', 'anwp-football-leagues' ),
				'desc' => 'player :: birthday',
				'id'   => 'player__birthday__years',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Gallery', 'anwp-football-leagues' ),
				'desc' => 'player :: gallery',
				'id'   => 'player__gallery__gallery',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Position', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__position',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Full Name', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__full_name',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Current Club', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__current_club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'National Team', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__national_team',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__nationality',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Place Of Birth', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__place_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Date Of Birth', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__date_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Date Of Death', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__date_of_death',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Age', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__age',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Age', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__age',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Weight (kg)', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__weight_kg',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Height (cm)', 'anwp-football-leagues' ),
				'desc' => 'player :: header',
				'id'   => 'player__header__height_cm',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Latest Matches', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__latest_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Date', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__date',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'For', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__for',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Against', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__against',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Home /Away', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__home_away',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Result', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__result',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Minutes', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__minutes',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals Conceded', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__goals_conceded',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__goals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assists', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__assists',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Cards', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__cards',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Home', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__home',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Away', 'anwp-football-leagues' ),
				'desc' => 'player :: matches',
				'id'   => 'player__matches__away',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Missed Matches', 'anwp-football-leagues' ),
				'desc' => 'player :: missed matches',
				'id'   => 'player__missed__missed_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Reason', 'anwp-football-leagues' ),
				'desc' => 'player :: missed matches',
				'id'   => 'player__missed__reason',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Stats Totals', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__stats_totals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Competition', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__competition',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Played Matches', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__played_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Started', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__started',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Substituted In', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__substituted_in',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Minutes', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__minutes',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Totals', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__totals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals Conceded', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__goals_conceded',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Clean Sheets', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__clean_sheets',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals (from penalty)', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__goals_from_penalty',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assists', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__assists',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Own Goals', 'anwp-football-leagues' ),
				'desc' => 'player :: stats',
				'id'   => 'player__stats__own_goals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Job Title', 'anwp-football-leagues' ),
				'desc' => 'referee :: content',
				'id'   => 'referee__content__job_title',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'desc' => 'referee :: content',
				'id'   => 'referee__content__nationality',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Place Of Birth', 'anwp-football-leagues' ),
				'desc' => 'referee :: content',
				'id'   => 'referee__content__place_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Date Of Birth', 'anwp-football-leagues' ),
				'desc' => 'referee :: content',
				'id'   => 'referee__content__date_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Age', 'anwp-football-leagues' ),
				'desc' => 'referee :: content',
				'id'   => 'referee__content__age',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'City', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__city',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Clubs', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__clubs',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Address', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__address',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Capacity', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__capacity',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Opened', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__opened',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Surface', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__surface',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Website', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__website',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Fixtures', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__fixtures',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Latest Matches', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__latest_matches',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Stadium Gallery', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__stadium_gallery',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Location', 'anwp-football-leagues' ),
				'desc' => 'stadium :: content',
				'id'   => 'stadium__content__location',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Current Club', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__current_club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Job Title', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__job_title',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__nationality',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Place Of Birth', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__place_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Date Of Birth', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__date_of_birth',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Age', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__age',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Career', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__career',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Job Title', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__job_title',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Club', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'From', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__from',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'To', 'anwp-football-leagues' ),
				'desc' => 'staff :: content',
				'id'   => 'staff__content__to',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( '#', 'Rank', 'anwp-football-leagues' ),
				'desc' => 'cards :: shortcode',
				'id'   => 'cards__shortcode__n',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Clubs', 'anwp-football-leagues' ),
				'desc' => 'cards :: shortcode',
				'id'   => 'cards__shortcode__clubs',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Club', 'anwp-football-leagues' ),
				'desc' => 'player :: shortcode',
				'id'   => 'player__shortcode__club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Player', 'anwp-football-leagues' ),
				'desc' => 'cards :: shortcode',
				'id'   => 'cards__shortcode__player',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Pts', 'points', 'anwp-football-leagues' ),
				'desc' => 'cards :: shortcode',
				'id'   => 'cards__shortcode__pts',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Goals', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__goals',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assists', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__assists',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assists', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__assists',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Rank', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__rank',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Player', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__player',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Club', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__nationality',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( '#', 'Rank', 'anwp-football-leagues' ),
				'desc' => 'players :: shortcode',
				'id'   => 'players__shortcode__rank_n',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Squad', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__squad',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'No players in the squad', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__no_players_in_the_squad',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'On Loan', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__on_loan',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Age', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__age',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Nationality', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__nationality',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Position', 'anwp-football-leagues' ),
				'desc' => 'squad :: shortcode',
				'id'   => 'squad__shortcode__position',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Club', 'anwp-football-leagues' ),
				'desc' => 'standing :: shortcode',
				'id'   => 'standing__shortcode__club',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'No Upcoming Birthdays', 'anwp-football-leagues' ),
				'desc' => 'birthdays :: widget',
				'id'   => 'birthdays__widget__no_upcoming_birthdays',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Assistant', 'anwp-football-leagues' ),
				'desc' => 'match :: goals',
				'id'   => 'match__goals__assistant',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal (own)', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__goal_own',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__goal',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Goal (from penalty)', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__goal_from_penalty',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Substitute', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__substitute',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'Missed Penalty', 'match event', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__missed_penalty',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ),
				'desc' => 'match :: events',
				'id'   => 'match__event__penalty_shootout',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Yellow Card', 'anwp-football-leagues' ),
				'desc' => 'data :: cards',
				'id'   => 'data__cards__yellow_card',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( 'Red Card', 'anwp-football-leagues' ),
				'desc' => 'data :: cards',
				'id'   => 'data__cards__red_card',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html__( '2nd Yellow > Red Card', 'anwp-football-leagues' ),
				'desc' => 'data :: cards',
				'id'   => 'data__cards__red_yellow_card',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'weeks', 'flip countdown', 'anwp-football-leagues' ),
				'desc' => 'data :: flip countdown',
				'id'   => 'data__flip_countdown__weeks',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'days', 'flip countdown', 'anwp-football-leagues' ),
				'desc' => 'data :: flip countdown',
				'id'   => 'data__flip_countdown__days',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'hours', 'flip countdown', 'anwp-football-leagues' ),
				'desc' => 'data :: flip countdown',
				'id'   => 'data__flip_countdown__hours',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'minutes', 'flip countdown', 'anwp-football-leagues' ),
				'desc' => 'data :: flip countdown',
				'id'   => 'data__flip_countdown__minutes',
				'type' => 'anwp_fl_text',
			]
		);

		$cmb->add_field(
			[
				'name' => esc_html_x( 'seconds', 'flip countdown', 'anwp-football-leagues' ),
				'desc' => 'data :: flip countdown',
				'id'   => 'data__flip_countdown__seconds',
				'type' => 'anwp_fl_text',
			]
		);

		/**
		 * Adds extra fields to the metabox.
		 *
		 * @since 0.10.23
		 */
		$extra_fields = apply_filters( 'anwpfl/text/text_extra_options', [] );

		if ( ! empty( $extra_fields ) && is_array( $extra_fields ) ) {
			foreach ( $extra_fields as $field ) {
				$cmb->add_field( $field );
			}
		}
	}

	/**
	 * Wrapper function around cmb2_get_option.
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
	 * Returns config options for selected value.
	 *
	 * @param string $value
	 *
	 * @return array
	 */
	public function get_options( $value ) {

		$options = self::get_value( $value );

		if ( ! empty( $options ) && is_array( $options ) ) {
			return $options;
		}

		return [];
	}
}
