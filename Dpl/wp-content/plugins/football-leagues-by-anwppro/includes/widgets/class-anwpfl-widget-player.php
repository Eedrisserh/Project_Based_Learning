<?php
/**
 * AnWP Football Leagues :: Widget >> Player.
 *
 * @since   0.8.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Player
 */
class AnWPFL_Widget_Player extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-player';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Player', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Single Player.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-player';
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
				'id'      => 'player_id',
				'type'    => 'player_id',
				'label'   => esc_html__( 'Player ID', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'yes',
			],
			[
				'id'          => 'options_text',
				'type'        => 'text',
				'label'       => esc_html__( 'Options Text', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Separate line by "|", number and label - with ":". E.q.: "Goals: 8 | Assists: 5"', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'show_club',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show Club', 'anwp-football-leagues' ),
				'default' => 0,
			],
			[
				'id'      => 'profile_link',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Link to Profile', 'anwp-football-leagues' ),
				'default' => 'yes',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'profile_link_text',
				'type'    => 'text',
				'label'   => esc_html__( 'Profile link text', 'anwp-football-leagues' ),
				'default' => esc_html__( 'profile', 'anwp-football-leagues' ),
			],
		];
	}
}
