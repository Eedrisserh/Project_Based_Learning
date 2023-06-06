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
}

// Bump
new AnWPFL_Shortcode_Players();
