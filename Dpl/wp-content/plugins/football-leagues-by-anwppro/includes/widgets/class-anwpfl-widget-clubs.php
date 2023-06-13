<?php
/**
 * AnWP Football Leagues :: Widget >> Clubs.
 *
 * @since   0.4.3
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Clubs
 *
 * @since 0.4.3
 */
class AnWPFL_Widget_Clubs extends AnWPFL_Widget {

	/**
	 * Get unique identifier for this widget.
	 *
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'anwpfl-widget-clubs';
	}

	/**
	 * Get widget name, displayed in Widgets dashboard.
	 *
	 * @return string
	 */
	protected function get_widget_name() {
		return esc_html__( 'FL Clubs', 'anwp-football-leagues' );
	}

	/**
	 * Get widget description.
	 *
	 * @return string
	 */
	protected function get_widget_description() {
		return esc_html__( 'Show Football Clubs.', 'anwp-football-leagues' );
	}

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 */
	protected function get_widget_css_classes() {
		return 'anwpfl-widget-clubs';
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
				'default' => esc_html_x( 'Clubs', 'widget default title', 'anwp-football-leagues' ),
			],
			[
				'id'      => 'competition_id',
				'type'    => 'competition_id',
				'default' => '',
				'label'   => esc_html__( 'Competition ID', 'anwp-football-leagues' ),
				'single'  => 'yes',
			],
			[
				'id'      => 'logo_size',
				'type'    => 'select',
				'label'   => esc_html__( 'Logo Size', 'anwp-football-leagues' ),
				'default' => 'big',
				'options' => [
					'small' => esc_html__( 'Small', 'anwp-football-leagues' ),
					'big'   => esc_html__( 'Big', 'anwp-football-leagues' ),
				],
			],
			[
				'id'      => 'layout',
				'type'    => 'select',
				'label'   => esc_html__( 'Layout', 'anwp-football-leagues' ),
				'default' => '',
				'options' => [
					''     => esc_html__( 'Custom Height and Width', 'anwp-football-leagues' ),
					'2col' => esc_html__( '2 Columns', 'anwp-football-leagues' ),
					'3col' => esc_html__( '3 Columns', 'anwp-football-leagues' ),
					'4col' => esc_html__( '4 Columns', 'anwp-football-leagues' ),
					'6col' => esc_html__( '6 Columns', 'anwp-football-leagues' ),
				],
			],
			[
				'id'          => 'logo_height',
				'type'        => 'text',
				'label'       => esc_html__( 'Logo Height', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Height value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ),
				'default'     => '50px',
			],
			[
				'id'          => 'logo_width',
				'type'        => 'text',
				'label'       => esc_html__( 'Logo Width', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Width value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ),
				'default'     => '50px',
			],
			[
				'id'          => 'exclude_ids',
				'type'        => 'club_id',
				'label'       => esc_html__( 'Exclude', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Club IDs, separated by commas.', 'anwp-football-leagues' ),
				'single'      => 'no',
			],
			[
				'id'          => 'include_ids',
				'type'        => 'club_id',
				'label'       => esc_html__( 'Include', 'anwp-football-leagues' ),
				'description' => esc_html__( 'Club IDs, separated by commas.', 'anwp-football-leagues' ),
				'single'      => 'no',
			],
			[
				'id'      => 'show_club_name',
				'type'    => 'select',
				'label'   => esc_html__( 'Show Club Name', 'anwp-football-leagues' ),
				'default' => 'no',
				'options' => [
					'no'  => esc_html__( 'No', 'anwp-football-leagues' ),
					'yes' => esc_html__( 'Yes', 'anwp-football-leagues' ),
				],
			],

		];
	}
}
