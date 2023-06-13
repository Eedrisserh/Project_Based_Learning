<?php
/**
 * AnWP Football Leagues :: Widget >> Matches.
 *
 * @since   0.4.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Matches
 *
 * @since 0.4.3
 */
class AnWPFL_Widget_Matches extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-matches';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Matches', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Football Matches.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-matches';
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
				'id'      => 'competition_id',
				'type'    => 'competition_id',
				'label'   => esc_html__( 'Competition ID', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'no',
			],
			[
				'id'    => 'show_secondary',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ),
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
				'id'      => 'type',
				'type'    => 'select',
				'label'   => esc_html__( 'Match Type', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''        => esc_html__( 'All', 'anwp-football-leagues' ),
					'result'  => esc_html__( 'Result', 'anwp-football-leagues' ),
					'fixture' => esc_html__( 'Fixture', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'limit',
				'type'    => 'number',
				'label'   => esc_html__( 'Matches Limit (0 - for all)', 'anwp-football-leagues' ),
				'default' => 0,
			],
			[
				'id'      => 'filter_by',
				'type'    => 'select',
				'label'   => esc_html__( 'Filter By', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''          => esc_html__( 'none', 'anwp-football-leagues' ),
					'club'      => esc_html__( 'Club', 'anwp-football-leagues' ),
					'matchweek' => esc_html__( 'Matchweek', 'anwp-football-leagues' ),
				],
			],
			[
				'id'          => 'filter_values',
				'type'        => 'text',
				'label'       => esc_html__( 'Filter By Values', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Comma separated list of options (if more than one).', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'group_by',
				'type'    => 'select',
				'label'   => esc_html__( 'Group By', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''          => esc_html__( 'none', 'anwp-football-leagues' ),
					'day'       => esc_html__( 'Day', 'anwp-football-leagues' ),
					'month'     => esc_html__( 'Month', 'anwp-football-leagues' ),
					'matchweek' => esc_html__( 'Matchweek', 'anwp-football-leagues' ),
					'stage'     => esc_html__( 'Stage', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'group_by_header_style',
				'type'    => 'select',
				'label'   => esc_html__( 'Group By Header Style', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''          => esc_html__( 'Default', 'anwp-football-leagues' ),
					'secondary' => esc_html__( 'Secondary', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'sort_by_date',
				'type'    => 'select',
				'label'   => esc_html__( 'Sort By Date', 'anwp-football-leagues' ),
				'default' => 'desc',
				'options' => [
					''     => esc_html__( 'none', 'anwp-football-leagues' ),
					'asc'  => esc_html__( 'Oldest', 'anwp-football-leagues' ),
					'desc' => esc_html__( 'Latest', 'anwp-football-leagues' ),
				],
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
			[
				'id'      => 'show_club_logos',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show club logos', 'anwp-football-leagues' ),
				'default' => 1,
			],
			[
				'id'      => 'show_match_datetime',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show match datetime', 'anwp-football-leagues' ),
				'default' => 1,
			],
			[
				'id'      => 'show_club_name',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show Club Name', 'anwp-football-leagues' ),
				'default' => 1,
			],
			[
				'id'      => 'layout',
				'type'    => 'select',
				'label'   => esc_html__( 'Layout', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''       => esc_html__( 'Default', 'anwp-football-leagues' ),
					'modern' => esc_html__( 'Modern', 'anwp-football-leagues' ),
				],
			],
			[
				'id'          => 'date_from',
				'type'        => 'text',
				'label'       => esc_html__( 'Date From', 'anwp-football-leagues' ),
				'default'     => '',
				'description' => esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'date_to',
				'type'        => 'text',
				'label'       => esc_html__( 'Date To', 'anwp-football-leagues' ),
				'default'     => '',
				'description' => esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'days_offset',
				'type'        => 'text',
				'label'       => esc_html__( 'Dynamic days filter', 'anwp-football-leagues' ),
				'default'     => '',
				'description' => esc_html__( 'For example: "-2" from 2 days ago and newer; "2" from the day after tomorrow and newer', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'days_offset_to',
				'type'        => 'text',
				'label'       => esc_html__( 'Dynamic days filter to', 'anwp-football-leagues' ),
				'default'     => '',
				'description' => esc_html__( 'For example: "1" - till tomorrow (tomorrow not included)', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'exclude_ids',
				'type'        => 'match_id',
				'label'       => esc_html__( 'Exclude', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Match IDs, separated by commas.', 'anwp-football-leagues' ),
				'single'      => 'no',
			],
			[
				'id'          => 'include_ids',
				'type'        => 'match_id',
				'label'       => esc_html__( 'Include', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Match IDs, separated by commas.', 'anwp-football-leagues' ),
				'single'      => 'no',
			],
		];
	}
}
