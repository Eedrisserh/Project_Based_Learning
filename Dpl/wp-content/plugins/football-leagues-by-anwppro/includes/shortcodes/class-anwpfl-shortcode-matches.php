<?php
/**
 * AnWP Football Leagues :: Shortcode > Matches.
 *
 * @since   0.4.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Matches.
 *
 * @since 0.4.3
 */
class AnWPFL_Shortcode_Matches {

	private $shortcode = 'anwpfl-matches';

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
		add_action( 'anwpfl/shortcode/get_shortcode_form_matches', [ $this, 'load_shortcode_form' ] );

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
			'competition_id'        => '',
			'show_secondary'        => 0,
			'season_id'             => '',
			'league_id'             => '',
			'group_id'              => '',
			'type'                  => '',
			'limit'                 => 0,
			'date_from'             => '',
			'date_to'               => '',
			'stadium_id'            => '',
			'filter_by'             => '',
			'filter_values'         => '',
			'filter_by_clubs'       => '',
			'filter_by_matchweeks'  => '',
			'days_offset'           => '',
			'days_offset_to'        => '',
			'sort_by_date'          => '',
			'sort_by_matchweek'     => '',
			'club_links'            => 1,
			'priority'              => '',
			'class'                 => 'mt-4',
			'group_by'              => '',
			'group_by_header_style' => '',
			'show_club_logos'       => 1,
			'show_match_datetime'   => 1,
			'competition_logo'      => 1,
			'exclude_ids'           => '',
			'include_ids'           => '',
			'outcome_id'            => '',
			'no_data_text'          => '',
			'home_club'             => '',
			'away_club'             => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( '%competition_id%' === $atts['competition_id'] && ! empty( $_GET['competition_id'] ) && absint( $_GET['competition_id'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification
			$atts['competition_id'] = absint( $_GET['competition_id'] );
		}

		// Validate shortcode attr
		$atts['show_secondary']      = (int) $atts['show_secondary'];
		$atts['limit']               = (int) $atts['limit'];
		$atts['competition_id']      = (int) $atts['competition_id'] ? sanitize_text_field( $atts['competition_id'] ) : '';
		$atts['season_id']           = (int) $atts['season_id'] ? (int) $atts['season_id'] : '';
		$atts['stadium_id']          = (int) $atts['stadium_id'] ? (int) $atts['stadium_id'] : '';
		$atts['show_club_logos']     = (int) $atts['show_club_logos'];
		$atts['show_match_datetime'] = (int) $atts['show_match_datetime'];
		$atts['club_links']          = (int) $atts['club_links'];

		$atts['type']                  = in_array( $atts['type'], [ 'result', 'fixture' ], true ) ? $atts['type'] : '';
		$atts['filter_by']             = in_array( $atts['filter_by'], [ 'club', 'matchweek' ], true ) ? $atts['filter_by'] : '';
		$atts['group_by']              = in_array( $atts['group_by'], [ 'day', 'month', 'matchweek', 'stage', 'competition' ], true ) ? $atts['group_by'] : '';
		$atts['group_by_header_style'] = esc_attr( $atts['group_by_header_style'] );
		$atts['sort_by_date']          = in_array( strtolower( $atts['sort_by_date'] ), [ 'asc', 'desc' ], true ) ? strtolower( $atts['sort_by_date'] ) : '';

		return anwp_football_leagues()->template->shortcode_loader( 'matches', $atts );
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
		$data['matches'] = __( 'Matches', 'anwp-football-leagues' );

		return $data;
	}

	/**
	 * Load shortcode form with options.
	 * Used in Shortcode Builder and Shortcode TinyMCE tool.
	 */
	public function load_shortcode_form() {

		$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/160-matches-shortcode';
		$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Matches', 'anwp-football-leagues' );

		anwp_football_leagues()->helper->render_docs_template( $shortcode_link, $shortcode_title );
		?>
		<table class="form-table">
			<tbody>
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
				<th scope="row"><label for="fl-form-shortcode-show_secondary"><?php echo esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_secondary" data-fl-type="select" id="fl-form-shortcode-show_secondary" class="postform fl-shortcode-attr">
						<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
						<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
					</select>
					<span class="anwp-option-desc"><?php echo esc_html__( 'Applied for multistage main stage competitions only.', 'anwp-football-leagues' ); ?></span>
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
				<th scope="row"><label for="fl-form-shortcode-group-id"><?php echo esc_html__( 'Group ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="group_id" data-fl-type="text" type="text" id="fl-form-shortcode-group-id" value="" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Match Type', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
						<option value="" selected><?php echo esc_html__( 'All', 'anwp-football-leagues' ); ?></option>
						<option value="result"><?php echo esc_html__( 'Result', 'anwp-football-leagues' ); ?></option>
						<option value="fixture"><?php echo esc_html__( 'Fixture', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-limit"><?php echo esc_html__( 'Matches Limit (0 - for all)', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="limit" data-fl-type="text" type="text" id="fl-form-shortcode-limit" value="0" class="fl-shortcode-attr regular-text code">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-date_from"><?php echo esc_html__( 'Date From', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="date_from" data-fl-type="text" type="text" id="fl-form-shortcode-date_from" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-date_to"><?php echo esc_html__( 'Date To', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="date_to" data-fl-type="text" type="text" id="fl-form-shortcode-date_to" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-stadium_id"><?php echo esc_html__( 'Filter by Stadium', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<select name="stadium_id" data-fl-type="select2" id="fl-form-shortcode-stadium_id" class="postform fl-shortcode-attr fl-shortcode-select2">
						<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
						<?php foreach ( anwp_football_leagues()->stadium->get_stadiums_options() as $stadium_id => $stadium_title ) : ?>
							<option value="<?php echo esc_attr( $stadium_id ); ?>"><?php echo esc_html( $stadium_title ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-filter_by_clubs"><?php echo esc_html__( 'Filter by Clubs', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="filter_by_clubs" data-fl-type="text" type="text" id="fl-form-shortcode-filter_by_clubs" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-home_club"><?php echo esc_html__( 'Filter by Home Club', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="home_club" data-fl-type="text" type="text" id="fl-form-shortcode-home_club" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-away_club"><?php echo esc_html__( 'Filter by Away Club', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<input name="away_club" data-fl-type="text" type="text" id="fl-form-shortcode-away_club" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-filter_by_matchweeks"><?php echo esc_html__( 'Filter by Matchweeks or Round IDs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="filter_by_matchweeks" data-fl-type="text" type="text" id="fl-form-shortcode-filter_by_matchweeks" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of matchweeks or rounds to filter', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-days_offset"><?php echo esc_html__( 'Dynamic days filter', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="days_offset" data-fl-type="text" type="text" id="fl-form-shortcode-days_offset" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'For example: "-2" from 2 days ago and newer; "2" from the day after tomorrow and newer', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-days_offset_to"><?php echo esc_html__( 'Dynamic days filter to', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="days_offset_to" data-fl-type="text" type="text" id="fl-form-shortcode-days_offset_to" value="" class="fl-shortcode-attr regular-text code">
					<span class="anwp-option-desc"><?php echo esc_html__( 'For example: "1" - till tomorrow (tomorrow not included)', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-sort_by_date"><?php echo esc_html__( 'Sort By Date', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="sort_by_date" data-fl-type="select" id="fl-form-shortcode-sort_by_date" class="postform fl-shortcode-attr">
						<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
						<option value="asc"><?php echo esc_html__( 'Oldest', 'anwp-football-leagues' ); ?></option>
						<option value="desc"><?php echo esc_html__( 'Latest', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-sort_by_matchweek"><?php echo esc_html__( 'Sort By MatchWeek', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="sort_by_matchweek" data-fl-type="select" id="fl-form-shortcode-sort_by_matchweek" class="postform fl-shortcode-attr">
						<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
						<option value="asc"><?php echo esc_html__( 'Ascending', 'anwp-football-leagues' ); ?></option>
						<option value="desc"><?php echo esc_html__( 'Descending', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-group_by"><?php echo esc_html__( 'Group By', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="group_by" data-fl-type="select" id="fl-form-shortcode-group_by" class="postform fl-shortcode-attr">
						<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
						<option value="day"><?php echo esc_html__( 'Day', 'anwp-football-leagues' ); ?></option>
						<option value="month"><?php echo esc_html__( 'Month', 'anwp-football-leagues' ); ?></option>
						<option value="matchweek"><?php echo esc_html__( 'Matchweek', 'anwp-football-leagues' ); ?></option>
						<option value="stage"><?php echo esc_html__( 'Stage', 'anwp-football-leagues' ); ?></option>
						<option value="competition"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-group_by_header_style"><?php echo esc_html__( 'Group By Header Style', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="group_by_header_style" data-fl-type="select" id="fl-form-shortcode-group_by_header_style" class="postform fl-shortcode-attr">
						<option value=""><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
						<option value="secondary"><?php echo esc_html__( 'Secondary', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_club_logos"><?php echo esc_html__( 'Show club logos', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_club_logos" data-fl-type="select" id="fl-form-shortcode-show_club_logos" class="postform fl-shortcode-attr">
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-show_match_datetime"><?php echo esc_html__( 'Show match datetime', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="show_match_datetime" data-fl-type="select" id="fl-form-shortcode-show_match_datetime" class="postform fl-shortcode-attr">
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-competition_logo"><?php echo esc_html__( 'Show Competition Logo', 'anwp-football-leagues' ); ?></label>
				</th>
				<td>
					<select name="competition_logo" data-fl-type="select" id="fl-form-shortcode-competition_logo" class="postform fl-shortcode-attr">
						<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
						<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-include_ids"><?php echo esc_html__( 'Include IDs', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="include_ids" data-fl-type="text" type="text" id="fl-form-shortcode-include_ids" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="match" data-single="no">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
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
			<tr>
				<th scope="row">
					<label for="fl-form-shortcode-outcome_id"><?php echo esc_html__( 'Show Outcome for club ID', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="outcome_id" data-fl-type="text" type="text" id="fl-form-shortcode-outcome_id" value="" class="fl-shortcode-attr code">
					<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
						<span class="dashicons dashicons-search"></span>
					</button>
					<span class="anwp-option-desc"><?php echo esc_html__( 'works only in "slim" layout', 'anwp-football-leagues' ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="fl-form-shortcode-no_data_text"><?php echo esc_html__( 'No data text', 'anwp-football-leagues' ); ?></label></th>
				<td>
					<input name="no_data_text" data-fl-type="text" type="text" id="fl-form-shortcode-no_data_text" value="" class="fl-shortcode-attr regular-text">
				</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-matches">
		<?php
	}
}

// Bump
new AnWPFL_Shortcode_Matches();
