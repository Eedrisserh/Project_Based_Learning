<?php
/**
 * Text Countries Class
 * AnWP Football Leagues :: Text Countries.
 *
 * @since   0.12.3
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Text_Countries {

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
	protected static $key = 'anwp_fl_text_countries';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 */
	protected static $metabox_id = 'anwp_fl_text_countries_metabox';

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
		$this->title = esc_html__( 'Football Leagues', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Countries Translations', 'anwp-football-leagues' );
	}

	/**
	 * Initiate our hooks.
	 */
	public function hooks() {

		// Hook in our actions to the admin.
		add_action( 'cmb2_admin_init', [ $this, 'add_countries_config_page_metabox' ] );

		// Inject some HTML before CMB2 form
		add_action( 'cmb2_before_options-page_form_anwp_fl_text_countries_metabox', [ $this, 'cmb2_before_metabox' ] );

		add_filter( 'anwpfl/config/countries', [ $this, 'add_countries_translations' ] );
	}

	/**
	 * Add Translated countries to the array of countries.
	 *
	 * @param array $countries
	 *
	 * @return array
	 * @since 0.12.3
	 */
	public function add_countries_translations( $countries ) {

		$translated = $this->get_value( 'all' );

		if ( ! empty( $translated ) ) {

			if ( isset( $translated['hidden_dummy'] ) ) {
				unset( $translated['hidden_dummy'] );
			}

			foreach ( $translated as $country_code => $country_translation ) {
				if ( isset( $countries[ $country_code ] ) && $country_translation ) {
					$countries[ $country_code ] = $country_translation;
				}
			}
		}

		return $countries;
	}

	/**
	 * Special HTML before CMB2 metabox.
	 */
	public function cmb2_before_metabox() {

		/*
		|--------------------------------------------------------------------
		| Start Output
		|--------------------------------------------------------------------
		*/
		ob_start();
		?>
		<div class="cmb2-wrap form-table anwp-b-wrap anwp-settings">

			<div class="alert alert-info border-info mt-n2 mb-3">
				<svg class="anwp-icon anwp-icon--s14 anwp-icon--octi mr-1">
					<use xlink:href="#icon-light-bulb"></use>
				</svg>
				<?php echo esc_html__( 'Override default countries translations', 'anwp-football-leagues' ); ?>
			</div>

			<div class="cmb2-metabox cmb-field-list">
				<div class="cmb-row bg-light">
					<div class="row align-items-center">
						<div class="col-sm-4"><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></div>
						<div class="col-sm-4"><?php echo esc_html__( 'New', 'anwp-football-leagues' ); ?></div>
						<div class="col-sm-4"><?php echo esc_html__( 'Country Code', 'anwp-football-leagues' ); ?></div>
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
	public function add_countries_config_page_metabox() {

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
				'menu_title'   => esc_html__( 'Countries Translations', 'anwp-football-leagues' ),
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
				'name' => esc_html__( 'Countries', 'anwp-football-leagues' ),
				'id'   => 'title_countries',
				'type' => 'title',
			]
		);

		foreach ( anwp_football_leagues()->data->get_initial_countries() as $country_code => $country_name ) {
			$cmb->add_field(
				[
					'name' => $country_name,
					'desc' => $country_code,
					'id'   => $country_code,
					'type' => 'anwp_fl_text',
				]
			);
		}

		$cmb->add_field(
			[
				'name' => esc_html__( 'Continental confederations', 'anwp-football-leagues' ),
				'id'   => 'title_confederations',
				'type' => 'title',
			]
		);

		foreach ( anwp_football_leagues()->data->get_initial_confederations() as $confederation_code => $confederation_name ) {
			$cmb->add_field(
				[
					'name' => $confederation_name,
					'desc' => $confederation_code,
					'id'   => $confederation_code,
					'type' => 'anwp_fl_text',
				]
			);
		}

		/**
		 * Adds extra fields to the metabox.
		 */
		$extra_fields = apply_filters( 'anwpfl/text/countries_translations_extra_options', [] );

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
}
