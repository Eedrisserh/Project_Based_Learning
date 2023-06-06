<?php
/**
 * AnWP Football Leagues :: Shortcode > Standing.
 *
 * @since   0.3.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Standing.
 *
 * @since 0.3.0
 */
class AnWPFL_Shortcode_Standing {

	private $shortcode = 'anwpfl-standing';

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since 0.3.0
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
			'title'       => '',
			'id'          => '',
			'exclude_ids' => '',
			'layout'      => '',
			'partial'     => '',
			'bottom_link' => '',
			'link_text'   => '',
			'show_notes'  => 1,
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'standing', $atts );
	}
}

// Bump
new AnWPFL_Shortcode_Standing();
