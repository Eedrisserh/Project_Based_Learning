<?php
/**
 * AnWP Football Leagues :: Shortcode > Referee.
 *
 * @since   0.12.4
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Referee.
 *
 * @since 0.12.4
 */
class AnWPFL_Shortcode_Referee {

	private $shortcode = 'anwpfl-referee';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_referee', [ $this, 'load_shortcode_form' ] );

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
			'referee_id'        => '',
			'options_text'      => '',
			'profile_link'      => '',
			'profile_link_text' => '',
			'show_job'          => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'referee', $atts );
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
		$data['referee'] = __( 'Referee', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-referee_id"><?php echo esc_html__( 'Referee ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="referee_id" data-fl-type="text" type="text" id="fl-form-shortcode-referee_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="referee" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_job"><?php echo esc_html__( 'Show Job Title', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_job" data-fl-type="select" id="fl-form-shortcode-show_job" class="postform fl-shortcode-attr">
						<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-profile_link"><?php echo esc_html__( 'Show Link to Profile', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="profile_link" data-fl-type="select" id="fl-form-shortcode-profile_link" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-profile_link_text"><?php echo esc_html__( 'Profile link text', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="profile_link_text" data-fl-type="text" type="text" id="fl-form-shortcode-profile_link_text" value="profile" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-options_text"><?php echo esc_html__( 'Options Text', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="options_text" data-fl-type="text" type="text" id="fl-form-shortcode-options_text" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'Separate line by "|", number and label - with ":". E.q.: "Goals: 8 | Assists: 5"', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-referee">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Referee();
