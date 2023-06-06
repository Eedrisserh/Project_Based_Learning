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

}

// Bump
new AnWPFL_Shortcode_Player();
