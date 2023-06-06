<?php
/**
 * AnWP Football Leagues :: Widget >> BirthDay.
 *
 * @since   0.10.19
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Birthday
 */
class AnWPFL_Widget_Birthday extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-birthday';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Birthdays', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Upcoming Birthdays.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-birthday';
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
				'id'          => 'club_id',
				'type'        => 'club_id',
				'label'       => esc_html__( 'Club ID', 'anwp-football-leagues' ),
				'default'     => '',
				'single'      => 'yes',
				'description' => esc_html__( 'Optional, leave empty for all', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'type',
				'type'    => 'select',
				'label'   => esc_html__( 'Type', 'anwp-football-leagues' ),
				'default' => 'players',
				'options' => [
					'players' => esc_html__( 'Players only', 'anwp-football-leagues' ),
					'staff'   => esc_html__( 'Staff only', 'anwp-football-leagues' ),
					'all'     => esc_html__( 'Players and Staff', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'days_before',
				'type'    => 'number',
				'label'   => esc_html__( 'Days before birthday', 'anwp-football-leagues' ),
				'default' => 5,
			],
			[
				'id'      => 'days_after',
				'type'    => 'number',
				'label'   => esc_html__( 'Days after birthday', 'anwp-football-leagues' ),
				'default' => 3,
			],
			[
				'id'      => 'group_by_date',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Group by date', 'anwp-football-leagues' ),
				'default' => 0,
			],
		];
	}
}
