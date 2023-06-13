<?php
/**
 * AnWP Football Leagues :: Widget >> Competition List
 *
 * @since   0.12.3
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Widget_Competition_List extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-competition-list';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Competition List', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show list of competitions', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-competition-list';
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
				'default' => esc_html_x( 'Competitions', 'widget default title', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'league_ids',
				'label'       => esc_html__( 'League IDs', 'anwp-football-leagues' ),
				'single'      => 'no',
				'type'        => 'league_id',
				'description' => esc_html__( 'Optional. Empty - for all.', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'season_ids',
				'label'       => esc_html__( 'Season IDs', 'anwp-football-leagues' ),
				'single'      => 'no',
				'type'        => 'season_id',
				'description' => esc_html__( 'Optional. Empty - for all.', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'group_by',
				'type'    => 'select',
				'label'   => esc_html__( 'Group By', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''                  => esc_html__( 'none', 'anwp-football-leagues' ),
					'country'           => esc_html__( 'Country', 'anwp-football-leagues' ),
					'country_collapsed' => esc_html__( 'Country - collapsed', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'display',
				'type'    => 'select',
				'label'   => esc_html__( 'Display Competition as', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''              => esc_html__( 'Competition', 'anwp-football-leagues' ),
					'league'        => esc_html__( 'League', 'anwp-football-leagues' ),
					'league_season' => esc_html__( 'League', 'anwp-football-leagues' ) . ' - ' . esc_html__( 'Season', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'show_logo',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Competition Logo', 'anwp-football-leagues' ),
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Yes', 'anwp-football-leagues' ),
					'0' => esc_html__( 'No', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'show_flag',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Country Flag', 'anwp-football-leagues' ),
				'default' => 'big',
				'options' => [
					'big'   => esc_html__( 'Yes', 'anwp-football-leagues' ) . ' - ' . esc_html__( 'Big', 'anwp-football-leagues' ),
					'small' => esc_html__( 'Yes', 'anwp-football-leagues' ) . ' - ' . esc_html__( 'Small', 'anwp-football-leagues' ),
					''      => esc_html__( 'No', 'anwp-football-leagues' ),
				],
			],
			[
				'id'          => 'include_ids',
				'single'      => 'no',
				'type'        => 'competition_id',
				'label'       => esc_html__( 'Include Competitions', 'anwp-football-leagues' ),
				'description' => esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'exclude_ids',
				'single'      => 'no',
				'type'        => 'competition_id',
				'label'       => esc_html__( 'Exclude Competitions', 'anwp-football-leagues' ),
				'description' => esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ),
			],
		];
	}
}
