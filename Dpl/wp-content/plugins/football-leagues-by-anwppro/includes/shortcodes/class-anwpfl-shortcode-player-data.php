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

}

// Bump
new AnWPFL_Shortcode_Player_Data();
