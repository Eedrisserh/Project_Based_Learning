<?php
/**
 * AnWP Football Leagues :: Widget >> Players.
 *
 * @since   0.5.1
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Players
 *
 * @since 0.5.1
 */
class AnWPFL_Widget_Players extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-players';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Players', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Players (scorers or assists).', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-players';
	}

	/**
	 * Get widget options fields.
	 *
	 * @return array
	 */
	protected function get_widget_fields() {
		return [
			[
				'id'      => 'title',
				'type'    => 'text',
				'label'   => esc_html__( 'Title:', 'anwp-football-leagues' ),
				'default' => '',
			],
			[
				'id'      => 'type',
				'type'    => 'select',
				'label'   => esc_html__( 'Type', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					'scorers' => esc_html__( 'Scorers', 'anwp-football-leagues' ),
					'assists' => esc_html__( 'Assists', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'competition_id',
				'type'    => 'competition_id',
				'label'   => esc_html__( 'Competition ID', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'yes',
			],
			[
				'id'    => 'join_secondary',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Include stats from secondary stages', 'anwp-football-leagues' ),
			],
			[
				'id'         => 'league_id',
				'type'       => 'select',
				'label'      => esc_html__( 'League', 'anwp-football-leagues' ),
				'show_empty' => esc_html__( '- select league -', 'anwp-football-leagues' ),
				'default'    => '',
				'options_cb' => [ anwp_football_leagues()->league, 'get_league_options' ],
			],
			[
				'id'         => 'season_id',
				'type'       => 'select',
				'label'      => esc_html__( 'Season', 'anwp-football-leagues' ),
				'show_empty' => esc_html__( '- select season -', 'anwp-football-leagues' ),
				'default'    => '',
				'options_cb' => [ anwp_football_leagues()->season, 'get_seasons_options' ],
			],
			[
				'id'     => 'club_id',
				'type'   => 'club_id',
				'label'  => esc_html__( 'Club ID', 'anwp-football-leagues' ),
				'single' => 'yes',
			],
			[
				'id'      => 'limit',
				'type'    => 'number',
				'label'   => esc_html__( 'Players Limit (0 - for all)', 'anwp-football-leagues' ),
				'default' => 0,
			],
			[
				'id'      => 'soft_limit',
				'type'    => 'select',
				'label'   => esc_html__( 'Soft Limit', 'anwp-football-leagues' ),
				'default' => 'yes',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'show_photo',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Photo', 'anwp-football-leagues' ),
				'default' => 'yes',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
		];
	}
}
