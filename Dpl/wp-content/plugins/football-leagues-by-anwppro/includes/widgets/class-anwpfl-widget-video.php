<?php
/**
 * AnWP Football Leagues :: Widget >> Video
 *
 * @since   0.10.22
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Widget_Video extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-video';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return 'FL ' . esc_html__( 'Video', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Last Match Video Review', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-video';
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
				'default' => esc_html_x( 'Video Review', 'widget default title', 'anwp-football-leagues' ),
			],
			[
				'id'     => 'club_id',
				'type'   => 'club_id',
				'label'  => esc_html__( 'Club ID', 'anwp-football-leagues' ),
				'single' => 'yes',
			],
			[
				'id'      => 'competition_id',
				'type'    => 'competition_id',
				'label'   => esc_html__( 'Competition ID', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'no',
			],
			[
				'id'          => 'include_ids',
				'single'      => 'yes',
				'type'        => 'match_id',
				'label'       => esc_html__( 'Match ID', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Fill to show video of the specified match', 'anwp-football-leagues' ),
			],
		];
	}
}
