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
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

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
}

// Bump
new AnWPFL_Shortcode_Matches();
