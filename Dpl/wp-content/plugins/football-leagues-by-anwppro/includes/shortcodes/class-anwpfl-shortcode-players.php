<?php
/**
 * AnWP Football Leagues :: Shortcode > Players.
 *
 * @since   0.5.1
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Matches.
 *
 * @since 0.5.1
 */
class AnWPFL_Shortcode_Players {

	private $shortcode = 'anwpfl-players';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_players', [ $this, 'load_shortcode_form' ] );

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
			'join_secondary' => 0,
			'season_id'      => '',
			'league_id'      => '',
			'club_id'        => '',
			'type'           => 'scorers',
			'limit'          => 0,
			'soft_limit'     => 'yes',
			'layout'         => '',
			'hide_zero'      => 0,
			'show_photo'     => 'yes',
			'compact'        => 0,
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'players', $atts );
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
		$data['players'] = __( 'Players', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/162-players-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Players', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Type', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
						<option value="scorers" selected><?php echo esc_html__( 'Scorers', 'anwp-football-leagues' ); ?></option>
						<option value="assists"><?php echo esc_html__( 'Assists', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
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
				<th scope="row"><label for="fl-form-shortcode-join_secondary"><?php echo esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="join_secondary" data-fl-type="select" id="fl-form-shortcode-join_secondary" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
					<span class="anwp-option-desc"><?php echo esc_html__( 'Include stats from secondary stages. Works for multistage competitions only. You should set main stage ID in "competition_id" parameter.', 'anwp-football-leagues' ); ?></span>
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
				<th scope="row"><label for="fl-form-shortcode-league_id"><?php echo esc_html__( 'League ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="league_id" data-fl-type="text" type="text" id="fl-form-shortcode-league_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="league" data-single="yes">
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
				<th scope="row"><label for="fl-form-shortcode-limit"><?php echo esc_html__( 'Players Limit (0 - for all)', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="limit" data-fl-type="text" type="text" id="fl-form-shortcode-limit" value="0" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-soft_limit"><?php echo esc_html__( 'Soft Limit', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="soft_limit" data-fl-type="select" id="fl-form-shortcode-soft_limit" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
					<span class="anwp-option-desc"><?php echo esc_html__( 'Increase number of players to the end of players with equal stats value.', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_photo"><?php echo esc_html__( 'Show Photo', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_photo" data-fl-type="select" id="fl-form-shortcode-show_photo" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-players-compact"><?php echo esc_html__( 'Compact', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="compact" data-fl-type="select" id="fl-form-shortcode-players-compact" class="postform fl-shortcode-attr">
						<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-players-hide_zero"><?php echo esc_html__( 'Hide Zeros', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="hide_zero" data-fl-type="select" id="fl-form-shortcode-players-hide_zero" class="postform fl-shortcode-attr">
						<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
						<option value="small"><?php echo esc_html__( 'Small', 'anwp-football-leagues' ); ?></option>
						<option value="mini"><?php echo esc_html__( 'Mini', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-players">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Players();
