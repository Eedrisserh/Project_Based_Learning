<?php
/**
 * AnWP Football Leagues :: Shortcode > Clubs.
 *
 * @since   0.4.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Clubs.
 *
 * @since 0.4.3
 */
class AnWPFL_Shortcode_Clubs {

	private $shortcode = 'anwpfl-clubs';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'shortcode_init' ] );

		// Load shortcode form
		add_action( 'anwpfl/shortcode/get_shortcode_form_clubs', [ $this, 'load_shortcode_form' ] );

		// Add shortcode option
		add_filter( 'anwpfl/shortcode/get_shortcode_options', [ $this, 'add_shortcode_option' ] );
	}

	/**
	 * Add shortcode.
	 */
	public function shortcode_init() {
		add_shortcode( $this->shortcode, [ $this, 'render_shortcode' ] );
	}

	/**
	 * Rendering shortcode.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode( $atts ) {

		$defaults = [
			'competition_id' => '',
			'logo_size'      => 'big',
			'layout'         => '',
			'logo_height'    => '50px',
			'logo_width'     => '50px',
			'exclude_ids'    => '',
			'include_ids'    => '',
			'show_club_name' => false,
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'clubs', $atts );
	}

	/**
	 * Add shortcode options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 *
	 * @param array $data Shortcode options.
	 *
	 * @return array
	 * @since 0.12.7
	 */
	public function add_shortcode_option( $data ) {
		$data['clubs'] = __( 'Clubs', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/158-clubs-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Clubs', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-competition_id"><?php echo esc_html__( 'Competition ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="competition_id" data-fl-type="text" type="text" id="fl-form-shortcode-competition_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="competition" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-logo_size"><?php echo esc_html__( 'Logo Size', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="logo_size" data-fl-type="select" id="fl-form-shortcode-logo_size" class="postform fl-shortcode-attr">
						<option value="small"><?php echo esc_html__( 'Small', 'anwp-football-leagues' ); ?></option>
						<option value="big" selected><?php echo esc_html__( 'Big', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Custom Height and Width', 'anwp-football-leagues' ); ?></option>
						<option value="2col"><?php echo esc_html__( '2 Columns', 'anwp-football-leagues' ); ?></option>
						<option value="3col"><?php echo esc_html__( '3 Columns', 'anwp-football-leagues' ); ?></option>
						<option value="4col"><?php echo esc_html__( '4 Columns', 'anwp-football-leagues' ); ?></option>
						<option value="6col"><?php echo esc_html__( '6 Columns', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-logo_height"><?php echo esc_html__( 'Logo Height', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="logo_height" data-fl-type="text" type="text" id="fl-form-shortcode-logo_height" value="50px" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'Height value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-logo_width"><?php echo esc_html__( 'Logo Width', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="logo_width" data-fl-type="text" type="text" id="fl-form-shortcode-logo_width" value="50px" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'Width value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-exclude_ids"><?php echo esc_html__( 'Exclude Clubs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="exclude_ids" data-fl-type="text" type="text" id="fl-form-shortcode-exclude_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-include_ids"><?php echo esc_html__( 'Include Clubs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="include_ids" data-fl-type="text" type="text" id="fl-form-shortcode-include_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_club_name"><?php echo esc_html__( 'Show club name', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<select name="show_club_name" data-fl-type="select" id="fl-form-shortcode-show_club_name" class="postform fl-shortcode-attr">
						<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-clubs">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Clubs();
