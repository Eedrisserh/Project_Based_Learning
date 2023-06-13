<?php
/**
 * AnWP Football Leagues :: Shortcode > Cards.
 *
 * @since   0.7.4
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Cards.
 *
 * @since 0.7.4
 */
class AnWPFL_Shortcode_Cards {

	private $shortcode = 'anwpfl-cards';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_cards', [ $this, 'load_shortcode_form' ] );

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
			'type'           => 'players',
			'limit'          => 0,
			'soft_limit'     => 'yes',
			'show_photo'     => 'yes',
			'points_r'       => '5',
			'points_yr'      => '2',
			'hide_zero'      => 0,
			'sort_by_point'  => '',
			'layout'         => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'cards', $atts );
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

		$data['cards'] = __( 'Cards', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/164-cards-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Cards', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Type', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
						<option value="players" selected><?php echo esc_html__( 'Players', 'anwp-football-leagues' ); ?></option>
						<option value="clubs"><?php echo esc_html__( 'Clubs', 'anwp-football-leagues' ); ?></option>
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
				<th scope="row"><label for="fl-form-shortcode-show_photo"><?php echo esc_html__( 'Show Photo/Logo', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_photo" data-fl-type="select" id="fl-form-shortcode-show_photo" class="postform fl-shortcode-attr">
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-sort_by_point"><?php echo esc_html__( 'Sort By Points', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="sort_by_point" data-fl-type="select" id="fl-form-shortcode-sort_by_point" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'Descending', 'anwp-football-leagues' ); ?></option>
						<option value="asc"><?php echo esc_html__( 'Ascending', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-points_r"><?php echo esc_html__( 'Points for Red card', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="points_r" data-fl-type="text" type="text" id="fl-form-shortcode-points_r" value="5" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-points_yr"><?php echo esc_html__( 'Points for Yellow/Red card', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="points_yr" data-fl-type="text" type="text" id="fl-form-shortcode-points_yr" value="2" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-hide_zero"><?php echo esc_html__( 'Hide with zero points', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="hide_zero" data-fl-type="select" id="fl-form-shortcode-hide_zero" class="postform fl-shortcode-attr">
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
						<option value="mini"><?php echo esc_html__( 'Mini', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-cards">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Cards();
