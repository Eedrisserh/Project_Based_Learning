<?php
/**
 * AnWP Football Leagues :: Shortcode > Cards.
 *
 * @since   0.7.4
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode > Cards.
 *
 * @since 0.7.4
 */
class AnWPFL_Shortcode_Cards {

	private $shortcode = 'anwpfl-cards';

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
			'type'           => 'players',
			'limit'          => 0,
			'soft_limit'     => 'yes',
			'show_photo'     => 'yes',
			'points_r'       => '5',
			'points_yr'      => '2',
			'hide_zero'      => 0,
			'sort_by_point'  => '',
			'layout'         => '',
		];

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, $this->shortcode );

		return anwp_football_leagues()->template->shortcode_loader( 'cards', $atts );
	}

}

// Bump
new AnWPFL_Shortcode_Cards();
