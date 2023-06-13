<?php
/**
 * AnWP Football Leagues :: Shortcode > Match.
 *
 * @since   0.6.1
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Match.
 *
 * @since 0.6.1
 */
class AnWPFL_Shortcode_Match {

	private $shortcode = 'anwpfl-match';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_match', [ $this, 'load_shortcode_form' ] );

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
			'layout'      => '',
			'match_id'    => '',
			'club_last'   => '',
			'club_next'   => '',
			'sections'    => '',
			'show_header' => 1,
			'class'       => 'mt-4',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		// Validate shortcode attr
		$atts['match_id'] = (int) $atts['match_id'];
		$atts['layout']   = in_array( $atts['layout'], [ 'full', 'slim' ], true ) ? $atts['layout'] : '';

		return anwp_football_leagues()->template->shortcode_loader( 'match', $atts );
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
		$data['match'] = __( 'Match', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/159-match-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Match', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-match_id"><?php echo esc_html__( 'Match ID', 'anwp-football-leagues' ); ?>*</label></th>
				<td>
					<input name="match_id" data-fl-type="text" type="text" id="fl-form-shortcode-match_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="match" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-club_last"><?php echo esc_html__( 'Last finished match of the club', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="club_last" data-fl-type="text" type="text" id="fl-form-shortcode-club_last" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-club_next"><?php echo esc_html__( 'Next match of the club', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="club_next" data-fl-type="text" type="text" id="fl-form-shortcode-club_next" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
						<option value="slim"><?php echo esc_html__( 'Slim', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-sections"><?php echo esc_html__( 'Sections', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="sections" data-fl-type="select2" id="fl-form-shortcode-sections" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
						<option value="goals"><?php echo esc_html__( 'Goals', 'anwp-football-leagues' ); ?></option>
						<option value="cards"><?php echo esc_html__( 'Cards', 'anwp-football-leagues' ); ?></option>
						<option value="line_ups"><?php echo esc_html__( 'Line Ups', 'anwp-football-leagues' ); ?></option>
						<option value="substitutes"><?php echo esc_html__( 'Substitutes', 'anwp-football-leagues' ); ?></option>
						<option value="stats"><?php echo esc_html__( 'Stats', 'anwp-football-leagues' ); ?></option>
						<option value="referees"><?php echo esc_html__( 'Referees', 'anwp-football-leagues' ); ?></option>
						<option value="missed_penalties"><?php echo esc_html__( 'Missed Penalties', 'anwp-football-leagues' ); ?></option>
						<option value="summary"><?php echo esc_html__( 'Summary', 'anwp-football-leagues' ); ?></option>
						<option value="penalty_shootout"><?php echo esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ); ?></option>
						<option value="video"><?php echo esc_html__( 'Video', 'anwp-football-leagues' ); ?></option>
						<option value="missing"><?php echo esc_html__( 'Missing Players', 'anwp-football-leagues' ); ?></option>
						<?php do_action( 'anwpfl/shortcodes/match_shortcode_options' ); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_header"><?php echo esc_html__( 'Show Match Header', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_header" data-fl-type="select" id="fl-form-shortcode-show_header" class="postform fl-shortcode-attr">
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-match">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Match();
