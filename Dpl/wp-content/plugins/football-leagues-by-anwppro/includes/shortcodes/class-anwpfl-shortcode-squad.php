<?php
/**
 * AnWP Football Leagues :: Shortcode > Squad.
 *
 * @since   0.5.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Squad.
 *
 * @since 0.5.0
 */
class AnWPFL_Shortcode_Squad {

	private $shortcode = 'anwpfl-squad';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_squad', [ $this, 'load_shortcode_form' ] );

		// Add shortcode option
		add_filter( 'anwpfl/shortcode/get_shortcode_options', [ $this, 'add_shortcode_option' ] );
	}

	/**
	 * Add shortcode.
	 *
	 * @since 0.3.0
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
			'season_id'            => '',
			'club_id'              => '',
			'header'               => 1,
			'layout'               => '',
			'layout_block_columns' => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'squad', $atts );
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
		$data['squad'] = __( 'Squad', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/161-squad-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Squad', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
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
				<th scope="row">
					<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club ID', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="club_id" data-fl-type="text" type="text" id="fl-form-shortcode-club_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-header"><?php echo esc_html__( 'Show Header', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="header" data-fl-type="select" id="fl-form-shortcode-header" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
						<option value="blocks"><?php echo esc_html__( 'Blocks', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-layout_block_columns"><?php echo esc_html__( 'Columns in Blocks Layout', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="layout_block_columns" data-fl-type="select" id="fl-form-shortcode-layout_block_columns" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-squad">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Squad();
