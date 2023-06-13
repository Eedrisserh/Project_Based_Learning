<?php
/**
 * AnWP Football Leagues :: Shortcode > Player Data.
 *
 * @since   0.11.7
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Player Data.
 */
class AnWPFL_Shortcode_Player_Data {

	private $shortcode = 'anwpfl-player-data';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_player-data', [ $this, 'load_shortcode_form' ] );

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
			'player_id' => '',
			'season_id' => '',
			'sections'  => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'player-data', $atts );
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
		$data['player-data'] = __( 'Player Data', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/945-player-data-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Player Data', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-player_id"><?php echo esc_html__( 'Player ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="player_id" data-fl-type="text" type="text" id="fl-form-shortcode-player_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="player" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="season_id" data-fl-type="text" type="text" id="fl-form-shortcode-season_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="season" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-sections"><?php echo esc_html__( 'Sections', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="sections" data-fl-type="select2" id="fl-form-shortcode-sections" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
						<option value="header"><?php echo esc_html__( 'Header', 'anwp-football-leagues' ); ?></option>
						<option value="description"><?php echo esc_html__( 'Description', 'anwp-football-leagues' ); ?></option>
						<option value="gallery"><?php echo esc_html__( 'Gallery', 'anwp-football-leagues' ); ?></option>
						<option value="matches"><?php echo esc_html__( 'Matches', 'anwp-football-leagues' ); ?></option>
						<option value="missed"><?php echo esc_html__( 'Missed', 'anwp-football-leagues' ); ?></option>
						<option value="stats"><?php echo esc_html__( 'Stats', 'anwp-football-leagues' ); ?></option>
						<?php do_action( 'anwpfl/shortcodes/player_shortcode_options' ); ?>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-player-data">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Player_Data();
