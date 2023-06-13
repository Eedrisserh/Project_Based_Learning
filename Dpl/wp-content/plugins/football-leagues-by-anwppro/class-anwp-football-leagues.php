<?php
/**
 * AnWP Football Leagues :: Main Class
 *
 * @since   0.1.0
 * @package AnWP_Football_Leagues
 */


/**
 * Autoloads files with classes when needed.
 *
 * @since  0.1.0
 *
 * @param string $class_name Name of the class being requested.
 */
function anwp_football_leagues_autoload_classes( $class_name ) {

	// If our class doesn't have our prefix, don't load it.
	if ( 0 !== strpos( $class_name, 'AnWPFL_' ) ) {
		return;
	}

	// Set up our filename.
	$filename = strtolower( str_replace( '_', '-', substr( $class_name, strlen( 'AnWPFL_' ) ) ) );

	// Include our file.
	AnWP_Football_Leagues::include_file( 'includes/class-anwpfl-' . $filename );
}

spl_autoload_register( 'anwp_football_leagues_autoload_classes' );

/**
 * Main initiation class.
 *
 * @property-read AnWPFL_Club           $club
 * @property-read AnWPFL_Competition    $competition
 * @property-read AnWPFL_Data           $data
 * @property-read AnWPFL_Health         $health
 * @property-read AnWPFL_Helper         $helper
 * @property-read AnWPFL_League         $league
 * @property-read AnWPFL_Match          $match
 * @property-read AnWPFL_Options        $options
 * @property-read AnWPFL_Player         $player
 * @property-read AnWPFL_Season         $season
 * @property-read AnWPFL_Staff          $staff
 * @property-read AnWPFL_Referee        $referee
 * @property-read AnWPFL_Standing       $standing
 * @property-read AnWPFL_Stadium        $stadium
 * @property-read AnWPFL_Template       $template
 * @property-read AnWPFL_Text           $text
 * @property-read AnWPFL_Text_Countries $text_countries
 * @property-read string                $path     Path of plugin directory
 *
 * @since  0.1.0
 */
final class AnWP_Football_Leagues {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	const VERSION = '0.13.2';

	/**
	 * Current DB structure version.
	 *
	 * @var    int
	 * @since  0.3.0
	 */
	const DB_VERSION = 15;

	/**
	 * Menu Icon.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	const SVG_BALL = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSJmaWxsOm5vbmUiIHZpZXdCb3g9IjAgLTAuMDE2IDIwIDIwIj48cGF0aCBkPSJNMCA5Ljk4NGMwIDUuNTE5IDQuNDgxIDEwIDEwIDEwczEwLTQuNDgxIDEwLTEwYzAtNS41Mi00LjQ4MS0xMC0xMC0xMHMtMTAgNC40OC0xMCAxMHptNy41My0uMjE5bC0yLjMxOCAyLjEwNiAxLjA0NSAzLjk1MSAzLjE2NSAxLjA5NyAzLjEwMi0yLjg4NC0xLjE4OS0yLjkzNUw3LjUzIDkuNzY1ek0zLjE5OSA0LjA5MWE4Ljk4NyA4Ljk4NyAwIDAgMSA2LjE4NC0zLjA4N2wuMTQ1Ljc3MS0zLjQyMSAzLjYwMS0yLjE2Ny4xMjItLjc0MS0xLjQwN3ptNC40NjYgNC42NjFMNi45NSA1LjkzOWwzLjE4OS0zLjM1NWMuNjkuMDU5IDEuNTE1LjI3NSAyLjM1My42NDYuMzgxLjE2OS43MzcuMzU3IDEuMDYuNTU3bC4zMzYgNC40NzItMi4xODEgMS45MTEtNC4wNDItMS40MTh6TTIuNTI5IDQuOTY2QTguOTQ5IDguOTQ5IDAgMCAwIDEuMDAxIDkuODZsMS4xMjgtLjMzNS44ODMtMy42NDMtLjQ4My0uOTE2ek0xLjA0NSAxMC44OWE4Ljk5IDguOTkgMCAwIDAgMy40NzMgNi4yMjlsLjc2OC0xLjA2LTEuMDgxLTQuMDgzLTEuNjk0LTEuNTIxLTEuNDY2LjQzNXptOS4xNjEgOC4wOTFhOC45NzUgOC45NzUgMCAwIDAgNi40MDQtMi44OTNsLS44NDUtMS40ODUtMi4zODcuMDA0LTMuMzQgMy4xMDQuMTY4IDEuMjd6bTguNzAxLTEwLjI5NmE4Ljk4NSA4Ljk4NSAwIDAgMC0yLjkzNS01LjQzMmwtMS40MDcuNjY4LjMxNSA0LjE5NSAyLjEzNSAxLjY2MiAxLjg5Mi0xLjA5M3ptLjA5MSAxLjEwM2wuMDAyLjE5NmE4Ljk2IDguOTYgMCAwIDEtMS43MSA1LjI3NmwtLjcxMi0xLjI1Ljk5Ny0zLjQwMSAxLjQyMy0uODIxeiIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+';
	const SVG_CUP  = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAuNDc5IDEuMDY0IDE5LjA5OSAxOC45MzYiPjxwYXRoIGZpbGw9Im5vbmUiIGQ9Ik02IDE5SDV2MWgxMHYtMWgtMXYtMWgtMS4xMzVhNy4xNjQgNy4xNjQgMCAwIDEtLjc2NS0xLjUgMTAuMTkgMTAuMTkgMCAwIDEtLjU1My0yLjQ3MWMxLjc4LS42MzkgMy4xMjMtMi4zNDcgMy4zOTEtNC40MzcgMS40NjctLjgyOCAzLjIyMi0yLjA2NyAzLjkwNi0zLjMwNS45OC0xLjY1Ny45OC0zLjMxNCAwLTQuMTQzLS45NTUtLjgwNy0yLjg0MS0uODI4LTMuODQ0LjcwM1YxLjA2NEg1LjAxNnYxLjcxOWMtMS4wMTQtMS40NjYtMi44Ni0xLjQzNC0zLjgwMS0uNjM5LS45ODEuODI5LS45ODEgMi40ODYgMCA0LjE0My42NzQgMS4yMjEgMi4zODkgMi40NDEgMy44NDIgMy4yNjguMjU3IDIuMTA3IDEuNjA2IDMuODMxIDMuMzk2IDQuNDc0QTEwLjE5IDEwLjE5IDAgMCAxIDcuOSAxNi41YTcuMTY0IDcuMTY0IDAgMCAxLS43NjUgMS41SDZ2MXoiLz48L3N2Zz4=';
	const SVG_VS   = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSJmaWxsOm5vbmUiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PGRlZnM+PGNsaXBQYXRoIGlkPSJhIj48cGF0aCBkPSJNMCAwaDIwdjIwSDB6Ii8+PC9jbGlwUGF0aD48L2RlZnM+PGcgY2xpcC1wYXRoPSJ1cmwoI2EpIj48cGF0aCBkPSJNNi42NjcgMXMyLjg1NyAyLjEyNSA2LjY2NiAyLjEyNUMxMy4zMzMgMTQuODEyIDYuNjY3IDE4IDYuNjY3IDE4UzAgMTQuODEyIDAgMy4xMjVDMy44MSAzLjEyNSA2LjY2NyAxIDYuNjY3IDF6Ii8+PHBhdGggZD0iTTEzLjMzMyAxUzE2LjE5IDMuMTI1IDIwIDMuMTI1QzIwIDE0LjgxMiAxMy4zMzMgMTggMTMuMzMzIDE4UzYuNjY3IDE0LjgxMiA2LjY2NyAzLjEyNUMxMC40NzYgMy4xMjUgMTMuMzMzIDEgMTMuMzMzIDF6IiBmaWxsLW9wYWNpdHk9Ii40Ii8+PC9nPjwvc3ZnPg==';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  0.1.0
	 */
	protected $activation_errors = [];

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    AnWP_Football_Leagues
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of AnWPFL_Options
	 *
	 * @since 0.1.0
	 * @var AnWPFL_Options
	 */
	protected $options;

	/**
	 * Instance of AnWPFL_League
	 *
	 * @since 0.1.0
	 * @var AnWPFL_League
	 */
	protected $league;

	/**
	 * Instance of AnWPFL_Season
	 *
	 * @since 0.1.0
	 * @var AnWPFL_Season
	 */
	protected $season;

	/**
	 * Instance of AnWPFL_Match
	 *
	 * @since 0.1.0
	 * @var AnWPFL_Match
	 */
	protected $match;

	/**
	 * Instance of AnWPFL_Competition
	 *
	 * @since 0.1.0
	 * @var AnWPFL_Competition
	 */
	protected $competition;

	/**
	 * Instance of AnWPFL_Club
	 *
	 * @since 0.1.0
	 * @var AnWPFL_Club
	 */
	protected $club;

	/**
	 * Instance of AnWPFL_Stadium
	 *
	 * @since 0.2.0
	 * @var AnWPFL_Stadium
	 */
	protected $stadium;

	/**
	 * Instance of AnWPFL_Health
	 *
	 * @since 0.13.2
	 * @var AnWPFL_Health
	 */
	protected $health;

	/**
	 * Instance of AnWPFL_Helper
	 *
	 * @since 0.2.0
	 * @var AnWPFL_Helper
	 */
	protected $helper;

	/**
	 * Instance of AnWPFL_Player
	 *
	 * @since 0.2.0
	 * @var AnWPFL_Player
	 */
	protected $player;

	/**
	 * Instance of AnWPFL_Staff
	 *
	 * @since 0.7.0
	 * @var AnWPFL_Staff
	 */
	protected $staff;

	/**
	 * Instance of AnWPFL_Referee
	 *
	 * @since 0.7.3
	 * @var AnWPFL_Referee
	 */
	protected $referee;

	/**
	 * Instance of AnWPFL_Standing
	 *
	 * @since 0.2.0
	 * @var AnWPFL_Standing
	 */
	protected $standing;

	/**
	 * Instance of AnWPFL_Text
	 *
	 * @since 0.10.23
	 * @var AnWPFL_Text
	 */
	protected $text;

	/**
	 * Instance of AnWPFL_Text_Countries
	 *
	 * @since 0.12.3
	 * @var AnWPFL_Text_Countries
	 */
	protected $text_countries;

	/**
	 * Instance of AnWPFL_Data
	 *
	 * @since 0.2.0
	 * @var AnWPFL_Data
	 */
	protected $data;

	/**
	 * Instance of AnWPFL_Template
	 *
	 * @since 0.3.0
	 * @var AnWPFL_Template
	 */
	protected $template;

	/**
	 * Plugin Post Types
	 *
	 * @since 0.5.5
	 * @var array
	 */
	protected $plugin_post_types = [];

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   0.1.0
	 * @return  AnWP_Football_Leagues A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  0.1.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( self::dir( 'anwp-football-leagues.php' ) );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		/**
		 * Set available plugin post types.
		 *
		 * @since 0.5.5
		 *
		 * @param array  List of plugin pages to load styles.
		 */
		$this->plugin_post_types = apply_filters(
			'anwpfl/config/plugin_post_types',
			[
				'anwp_match',
				'anwp_competition',
				'anwp_club',
				'anwp_stadium',
				'anwp_standing',
				'anwp_player',
				'anwp_staff',
				'anwp_referee',
			]
		);
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.1.0
	 */
	public function plugin_classes() {

		// Upgrade Code
		new AnWPFL_Upgrade( $this );

		// Options
		$this->options = new AnWPFL_Options( $this );

		// Taxonomies
		$this->league = new AnWPFL_League( $this );
		$this->season = new AnWPFL_Season( $this );

		// CPT
		$this->match       = new AnWPFL_Match( $this );
		$this->competition = new AnWPFL_Competition( $this );
		$this->club        = new AnWPFL_Club( $this );
		$this->stadium     = new AnWPFL_Stadium( $this );
		$this->player      = new AnWPFL_Player( $this );
		$this->staff       = new AnWPFL_Staff( $this );
		$this->referee     = new AnWPFL_Referee( $this );
		$this->standing    = new AnWPFL_Standing( $this );

		// Others
		$this->data           = new AnWPFL_Data( $this );
		$this->helper         = new AnWPFL_Helper( $this );
		$this->health         = new AnWPFL_Health( $this );
		$this->template       = new AnWPFL_Template( $this );
		$this->text           = new AnWPFL_Text( $this );
		$this->text_countries = new AnWPFL_Text_Countries( $this );

		// Shortcodes
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-standing.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-club.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-clubs.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-matches.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-match.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-squad.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-competition-header.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-competition-list.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-players.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-cards.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-player.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-staff.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-referee.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-player-data.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-match-next.php' );
		require self::dir( 'includes/shortcodes/class-anwpfl-shortcode-match-last.php' );

	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

		add_action( 'init', [ $this, 'init' ], 0 );

		/**
		 * Initialize widgets
		 *
		 * @since 0.4.3 (2018-02-18)
		 */
		add_action( 'widgets_init', [ $this, 'register_widgets' ], 0 );

		/**
		 * Update plugin DB check
		 *
		 * @since 0.3.0 (2018-01-30)
		 */
		add_action( 'init', [ $this, 'update_db_check' ], 1 );

		/**
		 * Register menu pages.
		 *
		 * @since  0.1.0 (2017-10-17)
		 */
		add_action( 'admin_menu', [ $this, 'register_menus' ], 5 );
		add_action( 'admin_menu', [ $this, 'register_alt_menus' ], 5 );

		/**
		 * Enqueue admin scripts
		 *
		 * @since 0.2.0 (2017-10-28)
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		/**
		 * Enqueue admin scripts in Elementor
		 *
		 * @since 0.12.7
		 */
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'admin_enqueue_scripts_elementor' ] );

		/**
		 * Add svg icons to the footer
		 *
		 * @since 0.2.0 (2017-10-28)
		 */
		add_action( 'admin_footer', [ $this, 'include_admin_svg_icons' ], 99 );

		/**
		 * Add svg icons to the public side
		 *
		 * @since 0.3.0 (2018-02-08)
		 */
		add_action( 'wp_footer', [ $this, 'include_public_svg_icons' ], 99 );

		/**
		 * Enqueue public scripts & styles
		 *
		 * @since 0.3.0 (2018-01-29)
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'public_enqueue_scripts' ] );

		/**
		 * Maybe flush rewrite rules.
		 *
		 * @since 0.3.0 (2018-02-05)
		 */
		add_action( 'init', [ $this, 'flush_rewrite_rules_maybe' ], 20 );

		/**
		 * Register custom status for secondary stage (in multistage competition)
		 *
		 * @since 0.4.2 (2018-02-16)
		 */
		add_action( 'init', [ $this, 'register_secondary_post_status' ], 0 );

		/**
		 * Add theme name to body classes
		 *
		 * @since 0.5.1 (2018-03-22)
		 */
		add_filter( 'body_class', [ $this, 'add_body_classes' ] );

		/**
		 * Filters the retrieved excerpt.
		 *
		 * @since 0.5.5
		 */
		add_filter( 'get_the_excerpt', [ $this, 'get_the_excerpt' ], 5, 2 );

		/**
		 * Add plugin meta links.
		 *
		 * @since 0.8.1
		 */
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_meta_links' ], 10, 2 );

		/**
		 * Renders notice if CMB2 not installed.
		 *
		 * @since 0.9.0
		 */
		add_action( 'admin_notices', [ $this, 'notice_cmb_not_installed' ] );

		/**
		 * Added CMB2 show_on filter
		 *
		 * @since 0.10.0
		 */
		add_filter( 'cmb2_show_on', [ $this, 'show_on_fixed_metabox' ], 10, 2 );

		/**
		 * Filters list of allowed mime types and file extensions.
		 *
		 * @since 0.8.3
		 */
		add_filter( 'upload_mimes', [ $this, 'add_svg_mime_support' ], 99 );

		/**
		 * Filters the post title.
		 *
		 * @since 0.10.6
		 */
		add_filter( 'the_title', [ $this, 'filter_post_title' ], 10, 2 );

		/**
		 * Filters the post title.
		 *
		 * @since 0.10.17
		 */
		add_filter( 'nav_menu_item_title', [ $this, 'fix_filter_post_menu_title' ], 9, 2 );

		/**
		 * Add redirect to premium page.
		 *
		 * @since 0.10.7
		 */
		add_action( 'admin_init', [ $this, 'page_redirect' ] );

		/**
		 * Inject media image instead of thumbnail id to use on the archive page.
		 */
		add_filter( 'get_post_metadata', [ $this, 'insert_thumbnail_id' ], 20, 3 );

		/**
		 * Fix Divi content duplication
		 *
		 * @since 0.10.12
		 */
		add_filter( 'et_first_image_use_custom_content', [ $this, 'fix_divi_duplicate_content' ], 20, 3 );
	}

	/**
	 * Fix duplicate content on Divi sometimes.
	 *
	 * @param         $bool
	 * @param         $content
	 * @param WP_Post $post
	 *
	 * @return bool|string
	 * @since 0.10.12
	 */
	public function fix_divi_duplicate_content( $bool, $content, $post ) {

		if ( in_array( $post->post_type, $this->plugin_post_types, true ) ) {
			return '';
		}

		return $bool;
	}

	/**
	 * Insert media images as thumbnails to use at archive page.
	 *
	 * @param null   $check
	 * @param int    $object_id Object ID.
	 * @param string $meta_key  Meta key.
	 *
	 * @return mixed
	 * @since 0.10.9
	 */
	public function insert_thumbnail_id( $check, $object_id, $meta_key ) {

		if ( ! is_post_type_archive( [ 'anwp_player', 'anwp_referee', 'anwp_staff', 'anwp_stadium', 'anwp_club' ] ) || empty( $object_id ) ) {
			return $check;
		}

		if ( '_thumbnail_id' !== $meta_key ) {
			return $check;
		}

		$thumbnail_id = 0;

		switch ( get_post_type( $object_id ) ) {
			case 'anwp_stadium':
			case 'anwp_player':
			case 'anwp_referee':
			case 'anwp_staff':
				$thumbnail_id = get_post_meta( $object_id, '_anwpfl_photo_id', true );
				break;

			case 'anwp_club':
				$thumbnail_id = get_post_meta( $object_id, '_anwpfl_logo_id', true );
				break;
		}

		if ( empty( $thumbnail_id ) ) {
			return $check;
		}

		return $thumbnail_id;
	}

	/**
	 * Filter Post title.
	 *
	 * @param string $title   The post title.
	 * @param int    $post_id The post ID.
	 *
	 * @return string
	 * @since 0.10.6
	 */
	public function filter_post_title( $title, $post_id = null ) {

		if ( 'no' === AnWPFL_Options::get_value( 'hide_post_titles' ) ) {
			return $title;
		}

		if ( is_singular( [ 'anwp_match' ] ) && 'anwp_match' === get_post_type( $post_id ) && is_main_query() && get_the_ID() === $post_id ) {
			return '';
		}

		if ( is_singular( [ 'anwp_competition' ] ) && 'anwp_competition' === get_post_type( $post_id ) && is_main_query() && get_the_ID() === $post_id ) {
			return '';
		}

		return $title;
	}

	/**
	 * Fix Post title filtering in Menu.
	 *
	 * @param string  $title   The post title.
	 * @param WP_Post $post The post ID.
	 *
	 * @return string
	 * @since 0.10.17
	 */
	public function fix_filter_post_menu_title( $title, $post ) {

		if ( '' === $post->post_title && ! empty( $post->object ) && in_array( $post->object, [ 'anwp_match', 'anwp_competition' ], true ) && 'no' !== AnWPFL_Options::get_value( 'hide_post_titles' ) ) {
			if ( ! empty( $post->object_id ) && absint( $post->object_id ) ) {
				$menu_object_post = get_post( $post->object_id );

				return $menu_object_post->post_title;
			}
		}

		return $title;
	}

	/**
	 * Adds SVG support for featured images.
	 *
	 * @param array $mimes Registered MIME types.
	 *
	 * @return mixed
	 * @since 0.10.6
	 */
	public function add_svg_mime_support( $mimes ) {

		// New allowed mime types.
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Add body classes.
	 *
	 * @param array $classes
	 *
	 * @return array
	 * @since 0.10.2
	 */
	public function add_body_classes( $classes ) {
		global $is_IE;

		// If it's IE, add a class.
		if ( $is_IE ) {
			$classes[] = 'ie';
		}

		if ( 'no' !== AnWPFL_Options::get_value( 'hide_post_titles' ) ) {
			$classes[] = 'anwp-hide-titles';
		}

		$classes[] = 'theme--' . wp_get_theme()->get_template();

		return $classes;
	}

	/**
	 * Filters the retrieved excerpt.
	 *
	 * @param string  $post_excerpt The post excerpt.
	 * @param WP_Post $post         Post object.
	 *
	 * @since 0.5.5
	 * @return string Modified post excerpt
	 */
	public function get_the_excerpt( $post_excerpt, $post ) {

		if ( in_array( $post->post_type, $this->plugin_post_types, true ) && empty( $post_excerpt ) ) {
			$post_excerpt = $post->post_title;
		}

		return $post_excerpt;
	}

	/**
	 * Add plugin meta links.
	 *
	 * @param array  $links       An array of the plugin's metadata,
	 *                            including the version, author,
	 *                            author URI, and plugin URI.
	 * @param string $file        Path to the plugin file, relative to the plugins directory.
	 *
	 * @since 0.8.1
	 * @return array
	 */
	public function add_plugin_meta_links( $links, $file ) {

		if ( false !== strpos( $file, $this->basename ) ) {
			$new_links = [
				'doc'       => '<a href="https://anwppro.userecho.com/communities/1-football-leagues" target="_blank">' . esc_html__( 'Documentation', 'anwp-football-leagues' ) . '</a>',
				'changelog' => '<a href="https://anwppro.userecho.com/knowledge-bases/11-fl-changelog/categories/28-basic-version/articles" target="_blank">' . esc_html__( 'Changelog', 'anwp-football-leagues' ) . '</a>',
				'premium'   => '<a href="https://anwp.pro/football-leagues-premium/" target="_blank">' . esc_html__( 'Go Premium', 'anwp-football-leagues' ) . '</a>',
			];

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	/**
	 * Register custom status for secondary stage (in multistage competition)
	 *
	 * @since 0.4.2 (2018-02-16)
	 */
	public function register_secondary_post_status() {
		register_post_status(
			'stage_secondary',
			[
				'label'                     => esc_html_x( 'Stage Secondary', 'post status', 'anwp-football-leagues' ),
				'public'                    => false,
				'internal'                  => true,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => true,
			]
		);
	}

	/**
	 * Check Plugin's DB version.
	 *
	 * @since 0.3.0 (2018-01-30)
	 */
	public function update_db_check() {

		if ( (int) get_option( 'anwpfl_db_version' ) < self::DB_VERSION ) {
			$this->update_db();
		}
	}

	/**
	 * Update plugin DB
	 *
	 * @since 0.3.0 (2018-01-30)
	 */
	public function update_db() {

		global $wpdb;

		$charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}

		$sql = "
CREATE TABLE {$wpdb->prefix}anwpfl_matches (
  match_id bigint(20) UNSIGNED NOT NULL,
  competition_id bigint(20) UNSIGNED NOT NULL,
  main_stage_id bigint(20) UNSIGNED NOT NULL,
  competition_status varchar(20) DEFAULT '' NOT NULL,
  group_id bigint(20) UNSIGNED NOT NULL,
  season_id bigint(20) UNSIGNED NOT NULL,
  league_id bigint(20) UNSIGNED NOT NULL,
  home_club bigint(20) UNSIGNED NOT NULL,
  away_club bigint(20) UNSIGNED NOT NULL,
  kickoff datetime NOT NULL default '0000-00-00 00:00:00',
  finished tinyint(1) NOT NULL DEFAULT '0',
  extra tinyint(1) NOT NULL DEFAULT '0',
  attendance int(10) NOT NULL DEFAULT '0',
  aggtext varchar(250) NOT NULL DEFAULT '',
  stadium_id bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  match_week tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  priority tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  home_goals tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_goals tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_goals_half tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_goals_half tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_goals_ft tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_goals_ft tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_goals_e tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_goals_e tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_goals_p tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_goals_p tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_cards_y tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_cards_y tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_cards_yr tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_cards_yr tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_cards_r tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_cards_r tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_corners tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_corners tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_fouls tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_fouls tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_offsides tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_offsides tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_possession tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_possession tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_shots tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_shots tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  home_shots_on_goal tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  away_shots_on_goal tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  special_status varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY  (match_id),
  KEY competition_id (competition_id),
  KEY home_club (home_club),
  KEY away_club (away_club),
  KEY kickoff (kickoff),
  KEY stadium_id (stadium_id),
  KEY group_id (group_id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}anwpfl_players (
  match_id bigint(20) UNSIGNED NOT NULL,
  player_id bigint(20) UNSIGNED NOT NULL,
  club_id bigint(20) UNSIGNED NOT NULL,
  season_id bigint(20) UNSIGNED NOT NULL,
  competition_id bigint(20) UNSIGNED NOT NULL,
  main_stage_id bigint(20) UNSIGNED NOT NULL,
  competition_status varchar(20) NOT NULL DEFAULT '',
  league_id bigint(20) UNSIGNED NOT NULL,
  time_in tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  time_out tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  appearance tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  goals tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  goals_penalty tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  goals_own tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  goals_conceded tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  assist tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  card_y tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  card_yr tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  card_r tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (match_id,player_id),
  KEY season_id (season_id),
  KEY league_id (league_id),
  KEY competition_id (competition_id),
  KEY main_stage_id (main_stage_id),
  KEY club_id (club_id)
) $charset_collate;
CREATE TABLE {$wpdb->prefix}anwpfl_missing_players (
  id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  match_id bigint(20) UNSIGNED NOT NULL,
  player_id bigint(20) UNSIGNED NOT NULL,
  club_id bigint(20) UNSIGNED NOT NULL,
  reason varchar(20) NOT NULL DEFAULT '',
  comment text NOT NULL,
  PRIMARY KEY  (id),
  KEY match_id (match_id),
  KEY player_id (player_id),
  KEY club_id (club_id)
) $charset_collate;
";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		$success = empty( $wpdb->last_error );

		if ( (int) get_option( 'anwpfl_db_version' ) < self::DB_VERSION && $success ) {
			update_option( 'anwpfl_db_version', self::DB_VERSION, true );
		}

		return $success;
	}

	/**
	 * Flush rewrite rules if the previously added flag exists,
	 * and then remove the flag.
	 *
	 * from - https://andrezrv.com/2014/08/12/efficiently-flush-rewrite-rules-plugin-activation/
	 *
	 * @since 0.3.0 (2018-02-05)
	 */
	public function flush_rewrite_rules_maybe() {

		// Check flag exists
		if ( get_option( 'anwpfl_flush_rewrite_rules_flag' ) ) {

			// Flush and delete flag
			flush_rewrite_rules();
			delete_option( 'anwpfl_flush_rewrite_rules_flag' );
		}
	}

	/**
	 * Manually render a CMB2 field.
	 *
	 * @deprecated Will be removed soon.
	 *
	 * @param  array      $field_args Array of field arguments.
	 * @param  CMB2_Field $field      The field object
	 */
	public function cmb_render_row_cb( /** @noinspection PhpUnusedParameterInspection */ $field_args, $field ) {

		$id    = $field->args( 'id' );
		$label = $field->args( 'name' );
		$name  = $field->args( '_name' );
		$value = $field->escaped_value();

		$field->peform_param_callback( 'before_row' );
		?>
		<div class="form-group <?php echo esc_attr( $field->row_classes() ); ?>" data-fieldtype="<?php echo esc_attr( $field->type() ); ?>">
			<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>

			<?php $field->peform_param_callback( 'before' ); ?>

			<?php if ( 'text' === $field->type() ) : ?>

				<input
					id="<?php echo esc_attr( $id ); ?>"
					class="form-control"
					type="text"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $value ); ?>"/>

			<?php else : ?>

				<?php
				$types = new CMB2_Types( $field );
				$types->render();
				?>

			<?php endif; ?>

			<?php $field->peform_param_callback( 'after' ); ?>
		</div>
		<?php

		$field->peform_param_callback( 'after_row' );
	}

	/**
	 * Register menu pages.
	 *
	 * @since 0.1.0 (2017-10-17)
	 */
	public function register_menus() {

		add_menu_page(
			esc_html_x( 'Football Leagues', 'admin page title', 'anwp-football-leagues' ),
			esc_html_x( 'Football Leagues', 'admin menu title', 'anwp-football-leagues' ),
			'manage_options',
			'anwp-football-leagues',
			[ $this, 'render_tutorials_page' ],
			self::SVG_BALL,
			32
		);

		/*
		|--------------------------------------------------------------------------
		| Prepare submenu pages
		|--------------------------------------------------------------------------
		*/
		$submenu_pages = [
			'tutorials'     => [
				'parent_slug' => 'anwp-football-leagues',
				'page_title'  => esc_html__( 'Documentation', 'anwp-football-leagues' ),
				'menu_title'  => esc_html__( 'Documentation', 'anwp-football-leagues' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwp-football-leagues',
				'output_func' => '',
			],
			'shortcodes'    => [
				'parent_slug' => 'anwp-football-leagues',
				'page_title'  => esc_html__( 'Shortcodes', 'anwp-football-leagues' ),
				'menu_title'  => esc_html__( 'Shortcodes', 'anwp-football-leagues' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwpfl-shortcodes',
				'output_func' => [ $this, 'render_shortcode_page' ],
			],
			'support'       => [
				'parent_slug' => 'anwp-football-leagues',
				'page_title'  => esc_html__( 'Support', 'anwp-football-leagues' ),
				'menu_title'  => esc_html__( 'Support', 'anwp-football-leagues' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwpfl-support',
				'output_func' => [ $this, 'render_support_page' ],
			],
			'plugin-health' => [
				'parent_slug' => 'anwp-football-leagues',
				'page_title'  => esc_html__( 'Plugin Health', 'anwp-football-leagues' ),
				'menu_title'  => esc_html__( 'Plugin Health', 'anwp-football-leagues' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwpfl-plugin-health',
				'output_func' => [ $this, 'render_health_page' ],
			],
			'premium'       => [
				'parent_slug' => 'anwp-football-leagues',
				'page_title'  => '',
				'menu_title'  => '<span style="color: #fd7e14">' . esc_html__( 'Go Premium', 'anwp-football-leagues' ) . '</span>',
				'capability'  => 'manage_options',
				'menu_slug'   => 'redirect_anwpfl_premium',
				'output_func' => [ $this, 'page_redirect' ],
			],
		];

		/**
		 * Filters loaded submenu pages.
		 *
		 * @since 0.5.5
		 *
		 * @param array Array of submenus
		 */
		$submenu_pages = apply_filters( 'anwpfl/admin/submenu_pages', $submenu_pages );

		foreach ( $submenu_pages as $m ) {
			add_submenu_page( $m['parent_slug'], $m['page_title'], $m['menu_title'], $m['capability'], $m['menu_slug'], $m['output_func'] );
		}
	}

	/**
	 * Register settings menu pages.
	 *
	 * @since 0.10.14
	 */
	public function register_alt_menus() {

		add_menu_page(
			esc_html_x( 'Settings & Tools', 'admin page title', 'anwp-football-leagues' ),
			esc_html_x( 'Settings & Tools', 'admin menu title', 'anwp-football-leagues' ),
			'manage_options',
			'anwp-settings-tools',
			'',
			self::SVG_BALL,
			32
		);

		/*
		|--------------------------------------------------------------------------
		| Settings Menu
		|--------------------------------------------------------------------------
		*/
		$submenu_settings_pages = [
			'tools' => [
				'parent_slug' => 'anwp-settings-tools',
				'page_title'  => esc_html__( 'Tools', 'anwp-football-leagues' ),
				'menu_title'  => esc_html__( 'Data Import', 'anwp-football-leagues' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwp-settings-tools',
				'output_func' => [ $this, 'render_tools_page' ],
			],
		];

		/**
		 * Filters loaded submenu pages.
		 *
		 * @since 0.10.14
		 *
		 * @param array Array of submenus
		 */
		$submenu_settings_pages = apply_filters( 'anwpfl/admin/submenu_settings_pages', $submenu_settings_pages );

		foreach ( $submenu_settings_pages as $m ) {
			add_submenu_page( $m['parent_slug'], $m['page_title'], $m['menu_title'], $m['capability'], $m['menu_slug'], $m['output_func'] );
		}
	}

	/**
	 * Rendering Tutorials page
	 *
	 * @since 0.1.0
	 */
	public function render_tutorials_page() {

		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/tutorials' );
	}

	/**
	 * Rendering Tutorials page
	 *
	 * @since 0.10.14
	 */
	public function render_settings_tools_page() {

		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/settings-tools' );
	}

	/**
	 * Rendering Tutorials page
	 *
	 * @since 0.5.5
	 */
	public function render_support_page() {

		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/support' );
	}

	/**
	 * Rendering Plugin Health Page
	 *
	 * @since 0.13.2
	 */
	public function render_health_page() {

		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/plugin-health' );
	}

	/**
	 * Rendering Shortcodes page
	 *
	 * @since 0.10.7
	 */
	public function render_shortcode_page() {

		// Check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/shortcodes' );
	}

	/**
	 * Rendering Tools page
	 *
	 * @since 0.8.2
	 */
	public function render_tools_page() {

		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
		}

		self::include_file( 'admin/views/tools' );
	}

	/**
	 * Rendering Premium page
	 *
	 * @since 0.8.0
	 */
	public function page_redirect() {

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['page'] ) && 'redirect_anwpfl_premium' === $_GET['page'] ) {
			// phpcs:ignore WordPress.Security.SafeRedirect
			wp_redirect( 'https://anwp.pro/football-leagues-premium/' );
			die;
		}
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  0.1.0
	 */
	public function activate() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Add rewrite flag
		// from - https://andrezrv.com/2014/08/12/efficiently-flush-rewrite-rules-plugin-activation/
		if ( ! get_option( 'anwpfl_flush_rewrite_rules_flag' ) ) {
			add_option( 'anwpfl_flush_rewrite_rules_flag', true );
		}
	}

	/**
	 * Deactivate the plugin.
	 * Uninstall routines should be in uninstall.php.
	 *
	 * @since  0.1.0
	 */
	public function deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'anwp-football-leagues', false, dirname( $this->basename ) . '/languages/' );

		// Include Gamajo_Template_Loader.
		// From - http://github.com/GaryJones/Gamajo-Template-Loader
		require_once self::dir( 'vendor/class-gamajo-template-loader.php' );

		// Initialize plugin classes.
		$this->plugin_classes();

		// Include CMB2 fields.
		if ( is_admin() ) {

			require_once self::dir( 'includes/cmb2-fields/cmb-field-map.php' );
			require_once self::dir( 'includes/cmb2-fields/cmb-field-simple-trigger.php' );
			require_once self::dir( 'includes/cmb2-fields/cmb-field-ordering-list.php' );
			require_once self::dir( 'includes/cmb2-fields/cmb-anwp-fl-custom-fields.php' );
			require_once self::dir( 'includes/cmb2-fields/class-anwp-cmb2-field-ajax-search.php' );
			require_once self::dir( 'includes/cmb2-fields/class-anwp-fl-cmb2-field-select2.php' );
			require_once self::dir( 'includes/cmb2-fields/cmb-field-translated-text.php' );
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.1.0
	 *
	 * @return boolean True if requirements met, false if not.
	 */
	public function check_requirements() {

		// Bail early if plugin meets requirements.
		if ( $this->meets_requirements() ) {
			return true;
		}

		// Add a dashboard notice.
		add_action( 'all_admin_notices', [ $this, 'requirements_not_met_notice' ] );

		// Deactivate our plugin.
		add_action( 'admin_init', [ $this, 'deactivate_me' ] );

		// Didn't meet the requirements.
		return false;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  0.1.0
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met.
	 *
	 * @since  0.1.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  0.1.0
	 */
	public function requirements_not_met_notice() {

		// Compile default message.
		/* translators: %s: link to plugins page. */
		$default_message = sprintf( __( 'AnWP Football Leagues is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'anwp-football-leagues' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->activation_errors && is_array( $this->activation_errors ) ) {
			$details = '<small>' . implode( '</small><br /><small>', $this->activation_errors ) . '</small>';
		}

		// Output errors.
		?>
		<div id="message" class="error">
			<p><?php echo wp_kses_post( $default_message ); ?></p>
			<?php echo wp_kses_post( $details ); ?>
		</div>
		<?php
	}

	/**
	 * Add SVG definitions to the admin footer.
	 *
	 * @since 0.2.0 (2018-01-03)
	 */
	public function include_admin_svg_icons() {

		// Define SVG sprite file.
		$svg_icons = self::dir( 'admin/img/svg-icons.svg' );

		// If it exists, include it.
		if ( file_exists( $svg_icons ) ) {
			require_once $svg_icons;
		}
	}

	/**
	 * Load selector modal
	 *
	 * @return string
	 * @since 0.11.7
	 */
	public function include_selector_modaal() {
		ob_start();
		?>
		<div id="anwp-fl-selector-modaal">
			<div class="anwpfl-shortcode-modal__header">
				<h3 style="margin: 0">AnWP Selector: <span id="anwp-fl-selector-modaal__header-context"></span></h3>
			</div>
			<div class="anwpfl-shortcode-modal__content" id="anwp-fl-selector-modaal__search-bar">
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--player anwp-fl-selector-modaal__bar-group--staff anwp-fl-selector-modaal__bar-group--referee anwp-fl-selector-modaal__bar-group--club anwp-fl-selector-modaal__bar-group--competition anwp-fl-selector-modaal__bar-group--league anwp-fl-selector-modaal__bar-group--season anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search"><?php echo esc_html__( 'start typing name or title ...', 'anwp-football-leagues' ); ?></label>
					<input name="s" type="text" id="anwp-fl-selector-modaal__search" value="" class="fl-shortcode-attr code">
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--player anwp-fl-selector-modaal__bar-group--staff anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-club"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></label>
					<select name="clubs" id="anwp-fl-selector-modaal__search-club" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--match anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-club-home"><?php echo esc_html__( 'Home Club', 'anwp-football-leagues' ); ?></label>
					<select name="clubs" id="anwp-fl-selector-modaal__search-club-home" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--match anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-club-away"><?php echo esc_html__( 'Away Club', 'anwp-football-leagues' ); ?></label>
					<select name="clubs" id="anwp-fl-selector-modaal__search-club-away" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--match anwp-fl-selector-modaal__bar-group--competition anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-season"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label>
					<select name="seasons" id="anwp-fl-selector-modaal__search-season" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--competition anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-league"><?php echo esc_html__( 'League', 'anwp-football-leagues' ); ?></label>
					<select name="leagues" id="anwp-fl-selector-modaal__search-league" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--competition anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__stages">
						<input type="checkbox" id="anwp-fl-selector-modaal__stages" value="yes">
						<?php echo esc_html__( 'Include secondary stages', 'anwp-football-leagues' ); ?>
					</label>
				</div>
				<div class="anwp-fl-selector-modaal__bar-group d-none anwp-fl-selector-modaal__bar-group--player anwp-fl-selector-modaal__bar-group--referee anwp-fl-selector-modaal__bar-group--club anwp-mr-2 anwp-mt-2">
					<label for="anwp-fl-selector-modaal__search-country"><?php echo esc_html__( 'Country/Nationality', 'anwp-football-leagues' ); ?></label>
					<select name="countries" id="anwp-fl-selector-modaal__search-country" class="anwp-selector-select2">
						<option value="">- select -</option>
					</select>
				</div>
			</div>
			<div class="anwpfl-shortcode-modal__footer">
				<h4 style="margin: 0"><?php echo esc_html__( 'Selected Values', 'anwp-football-leagues' ); ?>:
					<span class="spinner" id="anwp-fl-selector-modaal__initial-spinner" style="float: none; margin-top: 0;"></span>
				</h4>
				<div id="anwp-fl-selector-modaal__selected"></div>
				<div id="anwp-fl-selector-modaal__selected-none">- <?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?> -</div>
			</div>
			<div class="anwpfl-shortcode-modal__content" id="anwp-fl-selector-modaal__content"></div>
			<span class="spinner" id="anwp-fl-selector-modaal__search-spinner"></span>
			<div class="anwpfl-shortcode-modal__footer" id="anwp-fl-selector-modaal__footer">
				<button id="anwp-fl-selector-modaal__cancel" class="button"><?php echo esc_html__( 'Cancel', 'anwp-football-leagues' ); ?></button>
				<button id="anwp-fl-selector-modaal__insert" class="button button-primary"><?php echo esc_html__( 'Insert', 'anwp-football-leagues' ); ?></button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Add SVG definitions to the public footer.
	 *
	 * @since 0.3.0 (2018-02-08)
	 */
	public function include_public_svg_icons() {

		// Define SVG sprite file.
		$svg_icons = self::dir( 'public/img/svg-icons.svg' );

		// If it exists, include it.
		if ( file_exists( $svg_icons ) ) {
			require_once $svg_icons;
		}
	}

	/**
	 * Load admin scripts and styles
	 *
	 * @since 0.3.0 (2018-01-29)
	 */
	public function public_enqueue_scripts() {

		/*
		|--------------------------------------------------------------------------
		| Bootstrap CSS
		|--------------------------------------------------------------------------
		*/
		$load_bootstrap = AnWPFL_Options::get_value( 'load_bootstrap' );

		/**
		 * Filters a load bootstrap state.
		 *
		 * @param string $load_bootstrap Load Bootstrap-4 state.
		 *
		 * @since 0.5.3
		 */
		$load_bootstrap = apply_filters( 'anwpfl/public/load_bootstrap', $load_bootstrap );

		if ( is_rtl() ) {
			wp_enqueue_style( 'anwpfl_styles_rtl', self::url( 'public/css/styles-rtl.css' ), [], self::VERSION );
			wp_enqueue_style( 'anwpfl_styles_rtl_extra', self::url( 'public/css/styles-rtl-extra.css' ), [], self::VERSION );
		} else {
			if ( in_array( $load_bootstrap, [ 'none', 'js' ], true ) ) {
				wp_enqueue_style( 'anwpfl_styles', self::url( 'public/css/styles-bt4-free.min.css' ), [], self::VERSION );
			} else {
				wp_enqueue_style( 'anwpfl_styles', self::url( 'public/css/styles.min.css' ), [], self::VERSION );
			}
		}

		/*
		|--------------------------------------------------------------------------
		| World Flags Sprite
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_style( 'anwpfl_flags', self::url( 'vendor/world-flags-sprite/stylesheets/flags32.css' ), [], self::VERSION );
		wp_enqueue_style( 'anwpfl_flags_16', self::url( 'vendor/world-flags-sprite/stylesheets/flags16.css' ), [], self::VERSION );

		/*
		|--------------------------------------------------------------------------
		| Google Maps (for stadiums)
		|--------------------------------------------------------------------------
		*/
		if ( $this->options->get_value( 'google_maps_api' ) && is_singular( 'anwp_stadium' ) ) {
			$google_maps_api_key = '?key=' . $this->options->get_value( 'google_maps_api' );
			wp_enqueue_script( 'google-maps-api-3', '//maps.googleapis.com/maps/api/js' . $google_maps_api_key, [], 3, false );
		}

		/*
		|--------------------------------------------------------------------------
		| Main JS
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script(
			'anwpfl_public',
			self::url( 'public/js/anwpfl-public.min.js' ),
			[ 'jquery', 'underscore', 'anwpfl_countdown', 'anwpfl_overlayscrollbars' ],
			self::VERSION,
			true
		);

		wp_localize_script(
			'anwpfl_public',
			'anwpfl_l10n',
			[
				'weeks'   => AnWPFL_Text::get_value( 'data__flip_countdown__weeks', esc_html_x( 'weeks', 'flip countdown', 'anwp-football-leagues' ) ),
				'days'    => AnWPFL_Text::get_value( 'data__flip_countdown__days', esc_html_x( 'days', 'flip countdown', 'anwp-football-leagues' ) ),
				'hours'   => AnWPFL_Text::get_value( 'data__flip_countdown__hours', esc_html_x( 'hours', 'flip countdown', 'anwp-football-leagues' ) ),
				'minutes' => AnWPFL_Text::get_value( 'data__flip_countdown__minutes', esc_html_x( 'minutes', 'flip countdown', 'anwp-football-leagues' ) ),
				'seconds' => AnWPFL_Text::get_value( 'data__flip_countdown__seconds', esc_html_x( 'seconds', 'flip countdown', 'anwp-football-leagues' ) ),
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Countdown
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwpfl_countdown', self::url( 'vendor/jquery.countdown/jquery.countdown.min.js' ), [ 'jquery' ], '2.2.0', true );

		/*
		|--------------------------------------------------------------------------
		| OverlayScrollbars - v1.12.0 - MIT
		| https://github.com/KingSora/OverlayScrollbars
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwpfl_overlayscrollbars', self::url( 'vendor/overlayscrollbars/jquery.overlayScrollbars.min.js' ), [ 'jquery' ], '1.12.0', true );

		/*
		|--------------------------------------------------------------------------
		| Justified Gallery
		|--------------------------------------------------------------------------
		*/
		if ( apply_filters( 'anwpfl/config/load_justified_gallery', true ) && is_singular( [ 'anwp_stadium', 'anwp_club', 'anwp_player', 'anwp_match' ] ) ) {
			wp_enqueue_script( 'anwpfl_justified_gallery', self::url( 'vendor/justified-gallery/jquery.justifiedGallery.min.js' ), [ 'jquery' ], '3.8.1', true );
		}

		/*
		|--------------------------------------------------------------------------
		| Featherlight
		|--------------------------------------------------------------------------
		*/
		if ( apply_filters( 'anwpfl/config/load_featherlight', true ) && is_singular( [ 'anwp_stadium', 'anwp_club', 'anwp_player', 'anwp_match' ] ) ) {
			wp_enqueue_style( 'anwpfl_featherlight_css', self::url( 'vendor/featherlight/featherlight.min.css' ), [], '1.7.13' );
			wp_enqueue_style( 'anwpfl_featherlight_gallery', self::url( 'vendor/featherlight/featherlight.gallery.min.css' ), [], '1.7.13' );

			wp_enqueue_script( 'anwpfl_featherlight', self::url( 'vendor/featherlight/featherlight.min.js' ), [ 'jquery' ], '1.7.13', true );
			wp_enqueue_script( 'anwpfl_featherlight_gallery', self::url( 'vendor/featherlight/featherlight.gallery.min.js' ), [ 'anwpfl_featherlight' ], '1.7.13', true );
		}

		/*
		|--------------------------------------------------------------------------
		| Modaal
		|
		| @license  MIT
		| @link     https://github.com/humaan/Modaal
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'modaal', self::url( 'vendor/modaal/modaal.min.js' ), [ 'jquery' ], self::VERSION, false );
		wp_enqueue_style( 'modaal', self::url( 'vendor/modaal/modaal.min.css' ), [], self::VERSION );

		/*
		|--------------------------------------------------------------------------
		| Plyr
		| @licence - MIT
		| @url - https://plyr.io/
		|--------------------------------------------------------------------------
		*/
		if ( apply_filters( 'anwpfl/config/load_plyr', true ) && 'youtube' !== AnWPFL_Options::get_value( 'preferred_video_player' ) ) {
			wp_enqueue_script( 'plyr', self::url( 'vendor/plyr/plyr.polyfilled.min.js' ), [], '3.6.3', false );
			wp_enqueue_style( 'plyr', self::url( 'vendor/plyr/plyr.css' ), [], '3.6.3' );
		}

		if ( in_array( AnWPFL_Options::get_value( 'preferred_video_player' ), [ 'youtube', 'mixed' ], true ) ) {
			wp_enqueue_script( 'yt-video', self::url( 'public/js/anwp-yt-video.js' ), [ 'jquery', 'underscore' ], self::VERSION, true );
		}

		/*
		|--------------------------------------------------------------------------
		| Tippy.js
		| * (c) 2017-2019 atomiks
		| * MIT
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'tippy', self::url( 'vendor/tippy/tippy-bundle.umd.min.js' ), [ 'popperjs' ], '6.2.3', true );
		wp_enqueue_style( 'tippy-light-border', self::url( 'vendor/tippy/light-border.css' ), [], '6.1.1' );

		/*
		|--------------------------------------------------------------------------
		| Popper.js (UMD)
		| * (c) Federico Zivolo
		| * MIT
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'popperjs', self::url( 'vendor/popperjs/popper.min.js' ), [], '2.2.3', true );
	}

	/**
	 * Load admin scripts and styles
	 *
	 * @since 0.2.0 (2017-10-28)
	 */
	public function admin_enqueue_scripts() {

		// Load global styles
		if ( is_rtl() ) {
			wp_enqueue_style( 'anwpfl_styles_global_rtl', self::url( 'admin/css/global-rtl.css' ), [], self::VERSION );
			wp_enqueue_style( 'anwpfl_styles_global_rtl_extra', self::url( 'admin/css/global-rtl-extra.css' ), [], self::VERSION );
		} else {
			wp_enqueue_style( 'anwpfl_styles_global', self::url( 'admin/css/global.css' ), [], self::VERSION );
		}

		/*
		|--------------------------------------------------------------------------
		| Modaal
		|
		| @license  MIT
		| @link     https://github.com/humaan/Modaal
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'modaal', self::url( 'vendor/modaal/modaal.min.js' ), [ 'jquery', 'underscore' ], self::VERSION, false );

		/*
		|--------------------------------------------------------------------------
		| Global JS
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwp-fl-js-global', self::url( 'admin/js/anwp-fl-global.js' ), [ 'jquery', 'underscore', 'modaal' ], self::VERSION, false );

		wp_localize_script(
			'anwp-fl-js-global',
			'anwpflGlobals',
			[
				'ajaxNonce'    => wp_create_nonce( 'ajax_anwpfl_nonce' ),
				'selectorHtml' => $this->include_selector_modaal(),
				'countries'    => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->data->cb_get_countries() ),
				'clubs'        => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->club->get_clubs_options() ),
				'seasons'      => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->season->get_seasons_options() ),
				'leagues'      => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->league->get_league_options() ),
			]
		);

		/*
		|--------------------------------------------------------------------------
		| Select2 - 4.0.13
		|
		| @license  MIT
		| @link     https://select2.github.io
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwp-select2', self::url( 'vendor/select2/select2.full.min.js' ), [ 'jquery', 'jquery-ui-sortable' ], '4.0.13', false );
		wp_enqueue_style( 'anwp-select2', self::url( 'vendor/select2/select2.min.css' ), [], '4.0.13' );

		// Load styles and scripts (limit to plugin pages)
		$current_screen = get_current_screen();

		$page_prefix          = sanitize_title( _x( 'Football Leagues', 'admin menu title', 'anwp-football-leagues' ) );
		$page_settings_prefix = sanitize_title( _x( 'Settings & Tools', 'admin menu title', 'anwp-football-leagues' ) );

		$plugin_pages = [

			// Top level
			'toplevel_page_anwp-football-leagues',
			'toplevel_page_anwp-settings-tools',

			// CPTs
			'anwp_competition',
			'anwp_standing',
			'anwp_club',
			'anwp_staff',
			'anwp_referee',
			'anwp_player',
			'anwp_stadium',
			'anwp_match',

			// Options page
			'settings-tools_page_anwp_football_leagues_options',
			$page_settings_prefix . '_page_anwp_football_leagues_options',

			// Text page
			'settings-tools_page_anwp_fl_text',
			$page_settings_prefix . '_page_anwp_fl_text',

			// Countries page
			'settings-tools_page_anwp_fl_text_countries',
			$page_settings_prefix . '_page_anwp_fl_text_countries',

			// Tools page
			'settings-tools_page_anwp-settings-tools',
			$page_settings_prefix . '_page_anwp-settings-tools',

			// Premium page
			'football-leagues_page_anwpfl_premium',
			$page_prefix . '_page_anwpfl_premium',

			// Support page
			'football-leagues_page_support',
			$page_prefix . '_page_support',

			// Shortcodes page
			'football-leagues_page_anwpfl-shortcodes',
			$page_prefix . '_page_anwpfl-shortcodes',

			// Plugin Health
			'football-leagues_page_anwpfl-plugin-health',
			$page_prefix . '_page_anwpfl-plugin-health',

			// Match Admin List
			'edit-anwp_match',

			// Competition Admin List
			'edit-anwp_competition',
		];

		/**
		 * Filters plugin pages.
		 *
		 * @param array $plugin_pages List of plugin pages to load styles.
		 *
		 * @since 0.5.5
		 */
		$plugin_pages = array_unique( apply_filters( 'anwpfl/admin/plugin_pages', $plugin_pages ) );

		// Load Common files
		if ( in_array( $current_screen->id, $plugin_pages, true ) ) {

			wp_enqueue_media();

			/*
			|--------------------------------------------------------------------------
			| CSS Styles
			|--------------------------------------------------------------------------
			*/
			if ( is_rtl() ) {
				wp_enqueue_style( 'anwpfl_styles_rtl', self::url( 'admin/css/styles-rtl.css' ), [], self::VERSION );
			} else {
				wp_enqueue_style( 'anwpfl_styles', self::url( 'admin/css/styles.css' ), [], self::VERSION );
			}

			/*
			|--------------------------------------------------------------------------
			| World Flags Sprite
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_style( 'anwpfl_flags', self::url( 'vendor/world-flags-sprite/stylesheets/flags32.css' ), [], self::VERSION );
			wp_enqueue_style( 'anwpfl_flags_16', self::url( 'vendor/world-flags-sprite/stylesheets/flags16.css' ), [], self::VERSION );

			/*
			|--------------------------------------------------------------------------
			| FlatPickrStyles
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_style( 'anwpfl_flatpickr', self::url( 'admin/css/flatpickr.min.css' ), [], self::VERSION );
			wp_enqueue_style( 'anwpfl_flatpickr_theme', self::url( 'admin/css/flatpickr_airbnb.css' ), [], self::VERSION );

			/*
			|--------------------------------------------------------------------------
			| VueJS
			|--------------------------------------------------------------------------
			*/
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_enqueue_script( 'vuejs', self::url( 'vendor/vuejs/vue' . $suffix . '.js' ), [], '2.6.14', false );

			/*
			|--------------------------------------------------------------------------
			| Toastr
			|
			| @license  MIT
			| @link     https://github.com/CodeSeven/toastr
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'toastr', self::url( 'vendor/toastr/toastr.min.js' ), [], '2.1.4', false );

			/*
			|--------------------------------------------------------------------------
			| Tippy.js
			| * (c) 2017-2019 atomiks
			| * MIT
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'tippy', self::url( 'vendor/tippy/tippy-bundle.umd.min.js' ), [ 'popperjs' ], '6.1.1', true );

			/*
			|--------------------------------------------------------------------------
			| Popper.js (UMD)
			| * (c) Federico Zivolo
			| * MIT
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'popperjs', self::url( 'vendor/popperjs/popper.min.js' ), [], '2.2.3', true );

			/*
			|--------------------------------------------------------------------------
			| jQuery Autocomplete
			|
			| @license  MIT
			| @link     https://github.com/devbridge/jQuery-Autocomplete
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'anwp-jquery-autocomplete', self::url( 'vendor/jquery-autocomplete/jquery.autocomplete.min.js' ), [], '1.4.11', false );

			/*
			|--------------------------------------------------------------------------
			| Main admin JS
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'anwpfl_admin', self::url( 'admin/js/anwpfl-admin.min.js' ), [ 'jquery', 'underscore', 'jquery-ui-sortable' ], self::VERSION, true );

			/*
			|--------------------------------------------------------------------------
			| Admin VueJS
			|--------------------------------------------------------------------------
			*/
			wp_enqueue_script( 'anwpfl_admin_vue', self::url( 'admin/js/anwpfl-admin-vue.min.js' ), [ 'vuejs', 'anwpfl_admin' ], self::VERSION, true );

			wp_localize_script(
				'anwpfl_admin_vue',
				'anwp',
				[
					'rest_root'    => esc_url_raw( rest_url() ),
					'rest_nonce'   => wp_create_nonce( 'wp_rest' ),
					'admin_url'    => admin_url(),
					'clubs_map'    => $this->club->get_clubs_options(),
					'seasons_list' => $this->season->get_seasons_list(),
					'positions'    => $this->data->get_positions(),
					'activeSeason' => $this->get_active_season(),
				]
			);

			wp_localize_script( 'anwpfl_admin_vue', 'anwpfl_admin_l10n', anwp_football_leagues()->data->get_l10n_admin() );
		}

		// Load Google Maps only for stadium page
		if ( 'anwp_stadium' === $current_screen->id ) {

			if ( $this->options->get_value( 'google_maps_api' ) ) {
				$google_maps_api_key = '?key=' . $this->options->get_value( 'google_maps_api' );

				wp_enqueue_script( 'google-maps-api-3', '//maps.googleapis.com/maps/api/js' . $google_maps_api_key, [], 3, false );

				/*
				|--------------------------------------------------------------------------
				| Algolia Places - Turn any <input> into an address autocomplete
				|
				| @license  MIT
				| @link     https://github.com/algolia/places
				|--------------------------------------------------------------------------
				*/
				wp_enqueue_script( 'algolia-places', self::url( 'vendor/algolia-places/places.min.js' ), [], '1.16.4', false );
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Jspreadsheet CE (jExcel)
		| * Author: Paul Hodel <paul.hodel@gmail.com>
		| * Website: https://github.com/jspreadsheet/ce
		| * MIT License
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_style( 'jexcel-v4', self::url( 'vendor/jexcel/jexcel.css' ), [], '4.7.5' );
		wp_enqueue_script( 'jexcel-v4', self::url( 'vendor/jexcel/jexcel.js' ), [ 'jexcel-suites-v4' ], '4.7.5', true );
		wp_enqueue_style( 'jexcel-suites-v4', self::url( 'vendor/jexcel/jsuites.css' ), [], '4.7.3' );
		wp_enqueue_script( 'jexcel-suites-v4', self::url( 'vendor/jexcel/jsuites.js' ), [], '4.7.3', true );

		if ( false !== mb_strpos( $current_screen->id, '_page_anwp-settings-tools' ) ) {

			wp_localize_script(
				'anwpfl_admin',
				'anwpImportOptions',
				$this->data->get_import_options()
			);
		}

		if ( 'edit-anwp_match' === $current_screen->id ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
		}
	}

	/**
	 * Load admin scripts and styles in Elementor
	 *
	 * @since 0.12.7
	 */
	public function admin_enqueue_scripts_elementor() {

		// Load global styles
		if ( is_rtl() ) {
			wp_enqueue_style( 'anwpfl_styles_global_rtl', self::url( 'admin/css/global-rtl.css' ), [], self::VERSION );
			wp_enqueue_style( 'anwpfl_styles_global_rtl_extra', self::url( 'admin/css/global-rtl-extra.css' ), [], self::VERSION );
		} else {
			wp_enqueue_style( 'anwpfl_styles_global', self::url( 'admin/css/global.css' ), [], self::VERSION );
		}

		/*
		|--------------------------------------------------------------------------
		| Modaal
		|
		| @license  MIT
		| @link     https://github.com/humaan/Modaal
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'modaal', self::url( 'vendor/modaal/modaal.min.js' ), [ 'jquery', 'underscore' ], self::VERSION, false );

		/*
		|--------------------------------------------------------------------------
		| Global JS
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwp-fl-js-global', self::url( 'admin/js/anwp-fl-global.js' ), [ 'jquery', 'underscore', 'modaal' ], self::VERSION, false );

		wp_localize_script(
			'anwp-fl-js-global',
			'anwpflGlobals',
			[
				'ajaxNonce'    => wp_create_nonce( 'ajax_anwpfl_nonce' ),
				'selectorHtml' => $this->include_selector_modaal(),
				'countries'    => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->data->cb_get_countries() ),
				'clubs'        => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->club->get_clubs_options() ),
				'seasons'      => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->season->get_seasons_options() ),
				'leagues'      => anwp_football_leagues()->helper->get_select2_formatted_options( anwp_football_leagues()->league->get_league_options() ),
			]
		);
	}

	/**
	 * Register widgets.
	 *
	 * @since 0.4.3 (2018-02-18)
	 */
	public function register_widgets() {

		// include classes
		self::include_file( 'includes/widgets/class-anwpfl-widget' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-standing' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-clubs' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-matches' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-players' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-cards' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-player' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-birthday' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-next-match' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-last-match' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-video' );
		self::include_file( 'includes/widgets/class-anwpfl-widget-competition-list' );

		// register widgets
		register_widget( 'AnWPFL_Widget_Standing' );
		register_widget( 'AnWPFL_Widget_Clubs' );
		register_widget( 'AnWPFL_Widget_Matches' );
		register_widget( 'AnWPFL_Widget_Players' );
		register_widget( 'AnWPFL_Widget_Cards' );
		register_widget( 'AnWPFL_Widget_Player' );
		register_widget( 'AnWPFL_Widget_Birthday' );
		register_widget( 'AnWPFL_Widget_Next_Match' );
		register_widget( 'AnWPFL_Widget_Last_Match' );
		register_widget( 'AnWPFL_Widget_Video' );
		register_widget( 'AnWPFL_Widget_Competition_List' );
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $field Field to get.
	 *
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'options':
			case 'league':
			case 'match':
			case 'season':
			case 'competition':
			case 'club':
			case 'stadium':
			case 'health':
			case 'helper':
			case 'player':
			case 'staff':
			case 'referee':
			case 'standing':
			case 'template':
			case 'text':
			case 'text_countries':
			case 'data':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $filename Name of the file to be included.
	 * @return boolean          Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once $file;
		}

		return false;
	}

	/**
	 * This plugin's directory.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       Directory and path.
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );

		return $dir . $path;
	}

	/**
	 * This plugin's url.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       URL and path.
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );

		return $url . $path;
	}

	/**
	 * Load template partial.
	 * Proxy for template rendering class method.
	 *
	 * @param array|object $atts
	 * @param string       $slug
	 * @param string       $layout
	 *
	 * @since 0.6.1
	 * @return string
	 */
	public function load_partial( $atts, $slug, $layout = '' ) {

		$layout = empty( $layout ) ? '' : ( '-' . sanitize_key( $layout ) );
		return $this->template->set_template_data( $atts )->get_template_part( $slug, $layout );
	}

	/**
	 * Get list of plugin post types.
	 *
	 * @since 0.7.2 (2018-09-17)
	 * @return array
	 */
	public function get_post_types() {
		return $this->plugin_post_types;
	}

	/**
	 * Get Options value helper.
	 *
	 * @param  string $value
	 *
	 * @return string
	 * @since  0.7.5
	 */
	public function get_option_value( $value ) {
		return AnWPFL_Options::get_value( $value );
	}

	/**
	 * Get active season.
	 *
	 * @return int
	 * @since 0.8.4
	 */
	public function get_active_season() {

		// Get season ID from plugin options.
		$season_id = $this->get_option_value( 'active_season' );

		if ( ! $season_id ) {
			$season_options = anwp_football_leagues()->season->get_seasons_options();

			if ( ! empty( $season_options ) && is_array( $season_options ) ) {
				$season_id = max( array_keys( $season_options ) );
			}
		}

		return (int) $season_id;
	}

	/**
	 * Get active player season.
	 *
	 * @param int $player_id
	 *
	 * @return int
	 * @since 0.11.6
	 */
	public function get_active_player_season( $player_id ) {

		// Get season ID from plugin options.
		$season_id = $this->get_option_value( 'active_season' );

		if ( 'yes' !== AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			if ( ! $season_id ) {
				$season_options = anwp_football_leagues()->season->get_seasons_options();

				if ( ! empty( $season_options ) && is_array( $season_options ) ) {
					$season_id = max( array_keys( $season_options ) );
				}
			}
		} elseif ( absint( $player_id ) ) {

			$filtered_season_slugs = anwp_football_leagues()->helper->get_filtered_seasons( 'player', $player_id );

			// Check if active system season exists in player seasons
			if ( $season_id ) {
				$season_slug = anwp_football_leagues()->season->get_season_slug_by_id( $season_id );

				if ( in_array( $season_slug, $filtered_season_slugs, true ) ) {
					return (int) $season_id;
				}
			}

			if ( ! empty( $filtered_season_slugs ) ) {
				rsort( $filtered_season_slugs, SORT_NUMERIC );
				$season_id = anwp_football_leagues()->season->get_season_id_by_slug( $filtered_season_slugs[0] );
			}
		}

		return (int) $season_id;
	}

	/**
	 * Get active club season.
	 *
	 * @param int $club_id
	 *
	 * @return int
	 * @since 0.11.6
	 */
	public function get_active_club_season( $club_id ) {

		// Get season ID from plugin options.
		$season_id = $this->get_option_value( 'active_season' );

		if ( 'yes' !== AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			if ( ! $season_id ) {
				$season_options = anwp_football_leagues()->season->get_seasons_options();

				if ( ! empty( $season_options ) && is_array( $season_options ) ) {
					$season_id = max( array_keys( $season_options ) );
				}
			}
		} elseif ( absint( $club_id ) ) {

			$filtered_season_slugs = anwp_football_leagues()->helper->get_filtered_seasons( 'club', $club_id );

			// Check if active system season exists in player seasons
			if ( $season_id ) {
				$season_slug = anwp_football_leagues()->season->get_season_slug_by_id( $season_id );

				if ( in_array( $season_slug, $filtered_season_slugs, true ) ) {
					return (int) $season_id;
				}
			}

			if ( ! empty( $filtered_season_slugs ) ) {
				rsort( $filtered_season_slugs, SORT_NUMERIC );
				$season_id = anwp_football_leagues()->season->get_season_id_by_slug( $filtered_season_slugs[0] );
			}
		}

		return (int) $season_id;
	}

	/**
	 * Get active club season.
	 *
	 * @param int $stadium_id
	 *
	 * @return int
	 * @since 0.11.6
	 */
	public function get_active_stadium_season( $stadium_id ) {

		// Get season ID from plugin options.
		$season_id = $this->get_option_value( 'active_season' );

		if ( 'yes' !== AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			if ( ! $season_id ) {
				$season_options = anwp_football_leagues()->season->get_seasons_options();

				if ( ! empty( $season_options ) && is_array( $season_options ) ) {
					$season_id = max( array_keys( $season_options ) );
				}
			}
		} elseif ( absint( $stadium_id ) ) {

			$filtered_season_slugs = anwp_football_leagues()->helper->get_filtered_seasons( 'stadium', $stadium_id );

			// Check if active system season exists in player seasons
			if ( $season_id ) {
				$season_slug = anwp_football_leagues()->season->get_season_slug_by_id( $season_id );

				if ( in_array( $season_slug, $filtered_season_slugs, true ) ) {
					return (int) $season_id;
				}
			}

			if ( ! empty( $filtered_season_slugs ) ) {
				rsort( $filtered_season_slugs, SORT_NUMERIC );
				$season_id = anwp_football_leagues()->season->get_season_id_by_slug( $filtered_season_slugs[0] );
			}
		}

		return (int) $season_id;
	}

	/**
	 * Get active referee's season.
	 *
	 * @param int $referee_id
	 *
	 * @return int
	 * @since 0.11.17
	 */
	public function get_active_referee_season( $referee_id ) {

		// Get season ID from plugin options.
		$season_id = $this->get_option_value( 'active_season' );

		if ( 'yes' !== AnWPFL_Options::get_value( 'hide_not_used_seasons' ) ) {
			if ( ! $season_id ) {
				$season_options = anwp_football_leagues()->season->get_seasons_options();

				if ( ! empty( $season_options ) && is_array( $season_options ) ) {
					$season_id = max( array_keys( $season_options ) );
				}
			}
		} elseif ( absint( $referee_id ) ) {

			$filtered_season_slugs = anwp_football_leagues()->helper->get_filtered_seasons( 'referee', $referee_id );

			// Check if active system season exists in player seasons
			if ( $season_id ) {
				$season_slug = anwp_football_leagues()->season->get_season_slug_by_id( $season_id );

				if ( in_array( $season_slug, $filtered_season_slugs, true ) ) {
					return (int) $season_id;
				}
			}

			if ( ! empty( $filtered_season_slugs ) ) {
				rsort( $filtered_season_slugs, SORT_NUMERIC );
				$season_id = anwp_football_leagues()->season->get_season_id_by_slug( $filtered_season_slugs[0] );
			}
		}

		return (int) $season_id;
	}


	/**
	 * Overrides CMB2 label layout.
	 *
	 * @param            $field_args
	 * @param CMB2_Field $field
	 *
	 * @return string Label html markup.
	 * @since  0.9.0
	 */
	public function cmb2_field_label( $field_args, $field ) {

		if ( ! $field->args( 'name' ) ) {
			return '';
		}

		$output = sprintf( "\n" . '<label class="anwp-cmb2-label" for="%1$s">%2$s', $field->id(), $field->args( 'name' ) );

		// Check tooltip
		if ( ! empty( $field->args( 'label_tooltip' ) ) ) {
			$output .= '<span data-anwpfl_tippy data-tippy-content="' . esc_attr( $field->args( 'label_tooltip' ) ) . '"><svg class="anwp-icon anwp-icon--octi"><use xlink:href="#icon-info"></use></svg></span>';
		}

		$output .= '</label>' . "\n";

		// Check helper text
		if ( ! empty( $field->args( 'label_help' ) ) ) {
			$output .= "\n" . '<span class="anwp-cmb2-label__helper">' . $field->args( 'label_help' ) . '</span>';
		}

		return $output;
	}

	/**
	 * Metabox ShowOn filter for Non Fixed Data
	 *
	 * @param bool  $display
	 * @param array $meta_box
	 *
	 * @return bool display metabox
	 * @since 0.10.0
	 */
	public function show_on_fixed_metabox( $display, $meta_box ) {

		if ( ! isset( $meta_box['show_on']['key'] ) ) {
			return $display;
		}

		if ( 'fixed' !== $meta_box['show_on']['key'] ) {
			return $display;
		}

		// If we're showing it based on ID, get the current ID
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return $display;
		}

		return 'true' === get_post( $post_id )->_anwpfl_fixed;
	}

	/**
	 * Renders notice if CMB2 not installed.
	 *
	 * @since 0.9.0
	 */
	public function notice_cmb_not_installed() {

		if ( defined( 'CMB2_LOADED' ) ) {
			return;
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['action'] ) && 'install-plugin' === $_GET['action'] ) {
			return;
		}

		// Check CMB2 installed
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins      = get_plugins();
		$plugin_installed = isset( $all_plugins['cmb2/init.php'] );
		?>
		<div class="notice anwp-fl-cmb2-notice">
			<img src="<?php echo esc_url( self::url( 'admin/img/anwp-fl-icon.png' ) ); ?>">
			<img src="<?php echo esc_url( self::url( 'admin/img/cmb2-icon.png' ) ); ?>">
			<h3>Please install and activate CMB2 plugin</h3>
			<p>CMB2 is required for proper work of AnWP Football Leagues, and is used for building metaboxes and custom fields.</p>
			<p>

				<?php if ( $plugin_installed && current_user_can( 'activate_plugins' ) ) : ?>
					<a href="<?php echo esc_url( wp_nonce_url( 'plugins.php?action=activate&plugin=' . rawurlencode( 'cmb2/init.php' ), 'activate-plugin_cmb2/init.php' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Activate plugin', 'anwp-football-leagues' ); ?></a>
				<?php elseif ( current_user_can( 'install_plugins' ) ) : ?>
					<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=cmb2' ), 'install-plugin_cmb2' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Install plugin', 'anwp-football-leagues' ); ?></a>
				<?php endif; ?>

				<a class="button" href="https://wordpress.org/plugins/cmb2/" target="_blank"><?php echo esc_html__( 'Plugin page at wp.org', 'anwp-football-leagues' ); ?></a>
			</p>
			<p class="anwp-notice-clear-both"></p>
		</div>
		<?php
	}

	/**
	 * Return localized menu prefix.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function get_l10n_menu_prefix() {
		return sanitize_title( _x( 'Football Leagues', 'admin menu title', 'anwp-football-leagues' ) );
	}

	/**
	 * Return localized settings menu prefix.
	 *
	 * @return string
	 * @since 0.10.14
	 */
	public function get_l10n_menu_settings_prefix() {
		return sanitize_title( _x( 'Settings & Tools', 'admin menu title', 'anwp-football-leagues' ) );
	}

	/**
	 * Get POST season.
	 *
	 * @return int
	 * @since 0.10.14
	 */
	public function get_post_season() {

		static $season_id = null;

		if ( null === $season_id ) {

			// Get Season ID
			$season_id = anwp_football_leagues()->get_active_season();

			// phpcs:ignore WordPress.Security.NonceVerification
			if ( ! empty( $_GET['season'] ) ) {

				// phpcs:ignore WordPress.Security.NonceVerification
				$maybe_season_id = anwp_football_leagues()->season->get_season_id_by_slug( sanitize_key( $_GET['season'] ) );

				if ( absint( $maybe_season_id ) ) {
					return absint( $maybe_season_id );
				}
			}
		}

		return absint( $season_id );
	}
}
