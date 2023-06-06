<?php
/**
 * AnWP Football Leagues Upgrade.
 *
 * @since   0.7.0
 * @package AnWP_Football_Leagues
 */


/**
 * AnWP Football Leagues Upgrade class.
 */
class AnWPFL_Upgrade {

	/**
	 * Parent plugin class.
	 *
	 * @var    AnWP_Football_Leagues
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @param  AnWP_Football_Leagues $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {

		// Set up plugin instance
		$this->plugin = $plugin;

		$version = get_option( 'anwpfl_version', '0.1.0' );

		if ( version_compare( $version, '0.7.3', '<' ) ) {
			$this->finish_upgrade();
		}
	}

	/**
	 * Finishing Upgrade
	 */
	public function finish_upgrade() {

		add_option( 'anwpfl_version', anwp_football_leagues()->version );
		add_action( 'shutdown', 'flush_rewrite_rules' );
	}
}
