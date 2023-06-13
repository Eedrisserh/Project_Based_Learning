<?php
/**
 * AnWP Football Leagues :: Shortcode > Competition List.
 *
 * @since   0.12.3
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Shortcode_Competition_List {

	private $shortcode = 'anwpfl-competition-list';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_competition-list', [ $this, 'load_shortcode_form' ] );

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
			'league_ids'  => '',
			'season_ids'  => '',
			'include_ids' => '',
			'exclude_ids' => '',
			'group_by'    => '',
			'display'     => '',
			'show_logo'   => 'yes',
			'show_flag'   => 'big',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'competition_list', $atts );
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
		$data['competition-list'] = __( 'Competition List', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/en/knowledge-bases/2/articles/1348-competition-list';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Competition List', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-league_ids"><?php echo esc_html__( 'League IDs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="league_ids" data-fl-type="text" type="text" id="fl-form-shortcode-league_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="league" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'Optional. Empty - for all.', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-season_ids"><?php echo esc_html__( 'Season IDs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="season_ids" data-fl-type="text" type="text" id="fl-form-shortcode-season_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="season" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'Optional. Empty - for all.', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-include_ids"><?php echo esc_html__( 'Include Competitions', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="include_ids" data-fl-type="text" type="text" id="fl-form-shortcode-include_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="competition" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-exclude_ids"><?php echo esc_html__( 'Exclude Competitions', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="exclude_ids" data-fl-type="text" type="text" id="fl-form-shortcode-exclude_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="competition" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-group_by"><?php echo esc_html__( 'Group By', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="group_by" data-fl-type="select" id="fl-form-shortcode-group_by" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
						<option value="country"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></option>
						<option value="country_collapsed"><?php echo esc_html__( 'Country - collapsed', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-display"><?php echo esc_html__( 'Display Competition as', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="display" data-fl-type="select" id="fl-form-shortcode-display" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></option>
						<option value="league"><?php echo esc_html__( 'League', 'anwp-football-leagues' ); ?></option>
						<option value="league_season"><?php echo esc_html__( 'League', 'anwp-football-leagues' ); ?> - <?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-show_logo"><?php echo esc_html__( 'Show Competition Logo', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_logo" data-fl-type="select" id="fl-form-shortcode-show_logo" class="postform fl-shortcode-attr">
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-show_flag"><?php echo esc_html__( 'Show Country Flag', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_flag" data-fl-type="select" id="fl-form-shortcode-show_flag" class="postform fl-shortcode-attr">
						<option value="big" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?> - <?php echo esc_html__( 'Big', 'anwp-football-leagues' ); ?></option>
						<option value="small"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?> - <?php echo esc_html__( 'Small', 'anwp-football-leagues' ); ?></option>
						<option value=""><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-competition-list">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Competition_List();
