<?php
/**
 * AnWP Football Leagues :: Widget >> Cards.
 *
 * @since   0.7.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Cards
 */
class AnWPFL_Widget_Cards extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-cards';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Cards', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Players or Clubs with Cards.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-cards';
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
				'label'   => esc_html__( 'Title', 'anwp-football-leagues' ),
				'default' => '',
			],
			[
				'id'      => 'type',
				'type'    => 'select',
				'label'   => esc_html__( 'Type', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					'players' => esc_html__( 'Players', 'anwp-football-leagues' ),
					'clubs'   => esc_html__( 'Clubs', 'anwp-football-leagues' ),
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
				'label'  => esc_html__( 'Club ID', 'anwp-football-leagues' ) . '*',
				'single' => 'yes',
			],
			[
				'id'      => 'limit',
				'type'    => 'number',
				'label'   => esc_html__( 'Players Limit (0 - for all)', 'anwp-football-leagues' ) . '*',
				'default' => 0,
			],
			[
				'id'      => 'soft_limit',
				'type'    => 'select',
				'label'   => esc_html__( 'Soft Limit', 'anwp-football-leagues' ) . '*',
				'default' => 'yes',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'show_photo',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Photo/Logo', 'anwp-football-leagues' ),
				'default' => 'yes',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'points_yr',
				'type'    => 'number',
				'label'   => esc_html__( 'Points for Yellow/Red card', 'anwp-football-leagues' ),
				'default' => 2,
			],
			[
				'id'      => 'points_r',
				'type'    => 'number',
				'label'   => esc_html__( 'Points for Red card', 'anwp-football-leagues' ),
				'default' => 5,
			],
			[
				'id'      => 'sort_by_point',
				'type'    => 'select',
				'label'   => esc_html__( 'Sort By Points', 'anwp-football-leagues' ),
				'default' => 'desc',
				'options' => [
					''    => esc_html__( 'Descending ', 'anwp-football-leagues' ),
					'asc' => esc_html__( 'Ascending', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'hide_zero',
				'type'    => 'checkbox',
				'default' => 1,
				'label'   => esc_html__( 'Hide with zero points', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'hide_points',
				'type'    => 'checkbox',
				'default' => 0,
				'label'   => esc_html__( 'Hide points', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'link_text',
				'type'    => 'text',
				'label'   => esc_html__( 'Bottom Link Text', 'anwp-football-leagues' ),
				'default' => '',
			],
			[
				'id'      => 'link_target',
				'type'    => 'number',
				'label'   => esc_html__( 'Bottom Link Target', 'anwp-football-leagues' ) . ' (' . esc_html__( 'Competition ID or Page ID', 'anwp-football-leagues' ) . ')',
				'default' => '',
			],
		];
	}
}
