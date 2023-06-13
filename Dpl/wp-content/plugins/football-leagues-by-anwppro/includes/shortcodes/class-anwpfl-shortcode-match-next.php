<?php
/**
 * AnWP Football Leagues :: Shortcode > Match.
 *
 * @since   0.12.7
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Match Next.
 *
 * @since 0.12.7
 */
class AnWPFL_Shortcode_Match_Next {

	private $shortcode = 'anwpfl-match-next';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_match-next', [ $this, 'load_shortcode_form' ] );

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
			'club_id'         => '',
			'competition_id'  => '',
			'season_id'       => '',
			'match_link_text' => '',
			'show_club_name'  => 1,
			'exclude_ids'     => '',
			'include_ids'     => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'match-next', $atts );
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
		$data['match-next'] = __( 'Next Match', 'anwp-football-leagues' );

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
					<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="club_id" data-fl-type="text" type="text" id="fl-form-shortcode-club_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-competition_id"><?php echo esc_html__( 'Competition ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="competition_id" data-fl-type="text" type="text" id="fl-form-shortcode-competition_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="competition" data-single="no">
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
				<th scope="row"><label for="fl-form-shortcode-show_club_name"><?php echo esc_html__( 'Show Club Name', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_club_name" data-fl-type="select" id="fl-form-shortcode-show_club_name" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-match_link_text"><?php echo esc_html__( 'Match link text', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="match_link_text" data-fl-type="text" type="text" id="fl-form-shortcode-match_link_text" value="<?php echo esc_html__( '- match preview -', 'anwp-football-leagues' ); ?>" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-exclude_ids"><?php echo esc_html__( 'Exclude IDs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="exclude_ids" data-fl-type="text" type="text" id="fl-form-shortcode-exclude_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="match" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-match-next">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Match_Next();
