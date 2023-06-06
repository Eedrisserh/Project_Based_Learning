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

}

// Bump
new AnWPFL_Shortcode_Squad();
