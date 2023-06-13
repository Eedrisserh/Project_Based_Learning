<?php
/**
 * AnWP Football Leagues :: Shortcode > Player.
 *
 * @since   0.8.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Player.
 *
 * @since 0.8.3
 */
class AnWPFL_Shortcode_Player {

	private $shortcode = 'anwpfl-player';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_player', [ $this, 'load_shortcode_form' ] );

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
			'player_id'         => '',
			'options_text'      => '',
			'profile_link'      => '',
			'profile_link_text' => '',
			'show_club'         => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'player', $atts );
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
		$data['player'] = __( 'Player Card', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/163-player-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Player', 'anwp-football-leagues' );

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
				<th scope="row"><label for="fl-form-shortcode-show_club"><?php echo esc_html__( 'Show Club', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_club" data-fl-type="select" id="fl-form-shortcode-show_club" class="postform fl-shortcode-attr">
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
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-player">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Player();
