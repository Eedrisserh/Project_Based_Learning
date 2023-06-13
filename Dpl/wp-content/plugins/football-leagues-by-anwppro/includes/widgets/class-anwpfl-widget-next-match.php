<?php
/**
 * AnWP Football Leagues :: Widget >> Next Match
 *
 * @since   0.10.6
 * @package AnWP_Football_Leagues
 */

class AnWPFL_Widget_Next_Match extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-next-match';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Next Match', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Next Match.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-next-match';
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
				'default' => esc_html_x( 'Next Match', 'widget default title', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'club_id',
				'type'    => 'club_id',
				'label'   => esc_html__( 'Club', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'yes',
			],
			[
				'id'      => 'competition_id',
				'type'    => 'competition_id',
				'label'   => esc_html__( 'Competition ID', 'anwp-football-leagues' ),
				'default' => '',
				'single'  => 'no',
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
				'id'      => 'show_club_name',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show Club Name', 'anwp-football-leagues' ),
				'default' => 1,
			],
			[
				'id'      => 'match_link_text',
				'type'    => 'text',
				'label'   => esc_html__( 'Match link text', 'anwp-football-leagues' ),
				'default' => esc_html__( '- match preview -', 'anwp-football-leagues' ),
			],
			[
				'id'          => 'exclude_ids',
				'single'      => 'no',
				'type'        => 'match_id',
				'label'       => esc_html__( 'Exclude', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Match IDs, separated by commas.', 'anwp-football-leagues' ),
			],
		];
	}
}
