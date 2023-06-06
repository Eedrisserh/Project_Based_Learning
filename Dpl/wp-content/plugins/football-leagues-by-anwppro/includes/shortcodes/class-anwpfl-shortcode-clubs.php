<?php
/**
 * AnWP Football Leagues :: Shortcode > Clubs.
 *
 * @since   0.4.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Clubs.
 *
 * @since 0.4.3
 */
class AnWPFL_Shortcode_Clubs {

	private $shortcode = 'anwpfl-clubs';

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
			'logo_size'      => 'big',
			'layout'         => '',
			'logo_height'    => '50px',
			'logo_width'     => '50px',
			'exclude_ids'    => '',
			'include_ids'    => '',
			'show_club_name' => false,
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'clubs', $atts );
	}

}

// Bump
new AnWPFL_Shortcode_Clubs();
