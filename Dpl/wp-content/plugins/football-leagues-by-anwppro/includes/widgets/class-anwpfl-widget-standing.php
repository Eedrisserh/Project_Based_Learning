<?php
/**
 * AnWP Football Leagues :: Widget >> Standing.
 *
 * @since   0.3.0
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Widget Standing class.
 *
 * @since 0.3.0
 * @since 0.4.3 Extends base widget class.
 */
class AnWPFL_Widget_Standing extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-standing';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Standing Table', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show competition standing table.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-standing';
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
				'default' => esc_html_x( 'Standing Table', 'widget default title', 'anwp-football-leagues' ),
			],
			[
				'id'         => 'standing',
				'type'       => 'select_posts',
				'label'      => esc_html__( 'Standing Table:', 'anwp-football-leagues' ),
				'show_empty' => esc_html__( '- select table -', 'anwp-football-leagues' ),
				'default'    => '',
				'args'       => [
					'numberposts' => - 1,
					'post_type'   => 'anwp_standing',
				],
			],
			[
				'id'          => 'exclude_ids',
				'type'        => 'club_id',
				'label'       => esc_html__( 'Exclude:', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Club IDs, separated by commas.', 'anwp-football-leagues' ),
				'single'      => 'no',
			],
			[
				'id'          => 'partial',
				'type'        => 'text',
				'label'       => esc_html__( 'Show Partial Data', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Eg.: "1-5" (show teams from 1 to 5 place), "45" - show table slice with specified team ID in the middle', 'anwp-football-leagues' ),
				'default'     => '',
			],
			[
				'id'      => 'bottom_link',
				'type'    => 'select',
				'label'   => esc_html__( 'Show link to', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''            => esc_html__( 'none', 'anwp-football-leagues' ),
					'competition' => esc_html__( 'competition', 'anwp-football-leagues' ),
					'standing'    => esc_html__( 'standing', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'link_text',
				'type'    => 'text',
				'label'   => esc_html__( 'Alternative bottom link text', 'anwp-football-leagues' ),
				'default' => '',
			],
			[
				'id'      => 'show_notes',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Notes', 'anwp-football-leagues' ),
				'default' => '1',
				'options' => [
					'0' => esc_html__( 'No', 'anwp-football-leagues' ),
					'1' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],
		];
	}
}
