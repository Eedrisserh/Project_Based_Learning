<?php
/**
 * AnWP Football Leagues :: Shortcode > Competition Header.
 *
 * @since   0.5.1
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Competition Header.
 *
 * @since 0.4.3
 */
class AnWPFL_Shortcode_Competition_Header {

	private $shortcode = 'anwpfl-competition-header';

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
			'id'            => '',
			'title'         => '',
			'title_as_link' => 0,
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'competition_header', $atts );
	}

}

// Bump
new AnWPFL_Shortcode_Competition_Header();
