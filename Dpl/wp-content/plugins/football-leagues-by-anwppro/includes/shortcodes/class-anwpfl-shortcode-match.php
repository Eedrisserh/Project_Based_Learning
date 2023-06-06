<?php
/**
 * AnWP Football Leagues :: Shortcode > Match.
 *
 * @since   0.6.1
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Match.
 *
 * @since 0.6.1
 */
class AnWPFL_Shortcode_Match {

	private $shortcode = 'anwpfl-match';

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
			'layout'      => '',
			'match_id'    => '',
			'club_last'   => '',
			'club_next'   => '',
			'sections'    => '',
			'show_header' => 1,
			'class'       => 'mt-4',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		// Validate shortcode attr
		$atts['match_id'] = (int) $atts['match_id'];
		$atts['layout']   = in_array( $atts['layout'], [ 'full', 'slim' ], true ) ? $atts['layout'] : '';

		return anwp_football_leagues()->template->shortcode_loader( 'match', $atts );
	}
}

// Bump
new AnWPFL_Shortcode_Match();
