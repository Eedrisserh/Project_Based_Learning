<?php
/**
 * Widgets Helper Class
 * AnWP Football Leagues :: Widget.
 *
 * @since   0.4.3 (2018-02-18)
 * @package AnWP_Football_Leagues
 *
 * Inspired by - https://carlalexander.ca/polymorphism-wordpress-abstract-classes/
 *
 * Based on:
 * - https://github.com/woocommerce/woocommerce/blob/master/includes/abstracts/abstract-wc-widget.php
 * - WordPress Widget Boilerplate - https://github.com/tommcfarlin/WordPress-Widget-Boilerplate
 * - WordPress Widgets Helper Class - https://github.com/alessandrotesoro/wp-widgets-helper by Alessandro Tesoro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AnWP Football Leagues :: Widget class.
 *
 * @since 0.4.3 (2018-02-18)
 * @extends WP_Widget
 */
abstract class AnWPFL_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since  0.4.3
	 */
	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			$this->get_widget_name(),
			[
				'classname'                   => $this->get_widget_css_classes(),
				'description'                 => $this->get_widget_description(),
				'customize_selective_refresh' => false, // TODO test "true"
			]
		);

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'switch_theme', [ $this, 'flush_widget_cache' ] );
	}

	/**
	 * Get widget name.
	 *
	 * @return string
	 * @since  0.4.3
	 */
	abstract protected function get_widget_name();

	/**
	 * Get widget description.
	 *
	 * @return string
	 * @since  0.4.3
	 */
	abstract protected function get_widget_description();

	/**
	 * Get widget slug.
	 *
	 * @return string
	 * @since  0.4.3
	 */
	abstract protected function get_widget_slug();

	/**
	 * Get widget CSS classes.
	 *
	 * @return string
	 * @since  0.4.3
	 */
	abstract protected function get_widget_css_classes();

	/**
	 * Get widget fields.
	 *
	 * @return array
	 * @since  0.4.3
	 */
	abstract protected function get_widget_fields();

	/**
	 * Flush widget cache.
	 *
	 * @since  0.4.3
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 *
	 * @since  0.4.3
	 */
	public function form( $instance ) {

		$widget_fields = $this->get_widget_fields();

		if ( empty( $widget_fields ) || ! is_array( $widget_fields ) ) {
			return;
		}

		foreach ( $widget_fields as $field ) {

			$field = wp_parse_args(
				$field,
				[
					'type'    => '',
					'id'      => '',
					'label'   => '',
					'classes' => '',
					'default' => '',
				]
			);

			$value = isset( $instance[ $field['id'] ] ) ? $instance[ $field['id'] ] : $field['default'];

			switch ( $field['type'] ) {

				case 'text':
					?>
					<p>
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<input
							class="widefat <?php echo esc_attr( $field['classes'] ); ?>"
							id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
							type="text"
							value="<?php echo esc_attr( $value ); ?>"/>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<br>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;

				case 'player_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="player"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'club_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="club"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'match_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="match"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'competition_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="competition"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'season_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="season"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'league_id':
					?>
					<div class="anwp-mt-2">
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>
						<div class="anwp-d-flex">
							<input
								class="<?php echo esc_attr( $field['classes'] ); ?> anwp-flex-grow-1"
								id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
								type="text"
								value="<?php echo esc_attr( $value ); ?>"/>

							<button type="button" class="button anwp-fl-selector anwp-fl-selector--visible anwp-ml-2" style="display: none;" data-context="league"
								data-single="<?php echo esc_attr( 'no' === $field['single'] ? 'no' : 'yes' ); ?>">
								<span class="dashicons dashicons-search"></span>
							</button>
						</div>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</div>
					<?php
					break;

				case 'select_posts':
					$field['show_empty'] = empty( $field['show_empty'] ) ? '' : $field['show_empty'];
					$args                = ( ! empty( $field['args'] ) && is_array( $field['args'] ) ) ? $field['args'] : [];
					?>
					<p>
						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>

						<select
							id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
							class="widefat <?php echo esc_attr( $field['classes'] ); ?>">

							<?php if ( $field['show_empty'] ) : ?>
								<option value=""><?php echo esc_html( $field['show_empty'] ); ?></option>
							<?php endif; ?>

							<?php foreach ( get_posts( $args ) as $item ) : ?>
								<option <?php selected( $value, $item->ID ); ?> value="<?php echo intval( $item->ID ); ?>"><?php echo esc_html( $item->post_title ); ?></option>
							<?php endforeach; ?>
						</select>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<br>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;

				case 'number':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<input class="small-text <?php echo esc_attr( $field['classes'] ); ?>" type="number"
							id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
							step="<?php echo esc_attr( empty( $field['step'] ) ? '' : $field['step'] ); ?>"
							min="<?php echo esc_attr( empty( $field['min'] ) ? '' : $field['min'] ); ?>"
							max="<?php echo esc_attr( empty( $field['max'] ) ? '' : $field['max'] ); ?>"
							value="<?php echo esc_attr( $value ); ?>"/>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<br>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;

				case 'hidden':
					?>
					<input type="hidden"
						id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
						value="<?php echo esc_attr( $value ); ?>"/>
					<?php
					break;

				case 'checkbox':
					?>
					<p>
						<input class="checkbox <?php echo esc_attr( $field['classes'] ); ?>" type="checkbox"
							value="yes" <?php checked( $value, 1 ); ?>
							id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"/>
						<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php if ( ! empty( $field['description'] ) ) : ?>
							<br>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;

				case 'select':
					$field['show_empty'] = empty( $field['show_empty'] ) ? '' : $field['show_empty'];

					// Prepare options
					// Check general options first
					$options = ( ! empty( $field['options'] ) && is_array( $field['options'] ) ) ? $field['options'] : [];

					// Check callback if options empty
					if ( empty( $options ) && isset( $field['options_cb'] ) && is_callable( $field['options_cb'] ) ) {
						$options = call_user_func( $field['options_cb'] );
					}
					?>
					<p>

						<?php if ( $field['label'] ) : ?>
							<label for="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						<?php endif; ?>

						<select
							id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $field['id'] ) ); ?>"
							class="widefat <?php echo esc_attr( $field['classes'] ); ?>">

							<?php if ( $field['show_empty'] ) : ?>
								<option value=""><?php echo esc_html( $field['show_empty'] ); ?></option>
							<?php endif; ?>

							<?php foreach ( $options as $option_key => $option_text ) : ?>
								<option <?php selected( $value, $option_key ); ?> value="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_html( $option_text ); ?></option>
							<?php endforeach; ?>
						</select>

						<?php if ( ! empty( $field['description'] ) ) : ?>
							<br>
							<small><?php echo esc_html( $field['description'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
					break;
			}
		}
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 *
	 * @since  0.4.3 (2018-02-18)
	 */
	public function widget( $args, $instance ) {

		$atts = array_merge(
			$instance,
			[
				'before_widget' => $args['before_widget'],
				'after_widget'  => $args['after_widget'],
				'before_title'  => $args['before_title'],
				'after_title'   => $args['after_title'],
			]
		);

		// Display the widget.
		echo $this->get_widget( $atts ); // WPCS XSS OK.
	}

	/**
	 * Return the widget output
	 *
	 * @param  array $atts Array of widget attributes/args.
	 *
	 * @return string      Widget output
	 * @since  0.4.3 (2018-02-18)
	 */
	public function get_widget( $atts ) {

		// Prepare widget name
		$widget_name = str_replace( 'anwpfl-widget-', '', $this->get_widget_slug() );

		return anwp_football_leagues()->template->widget_loader( $widget_name, $atts );
	}

	/**
	 * Update form values as they are saved.
	 *
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 * @since  0.4.3 (2018-02-18)
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance      = $old_instance;
		$widget_fields = $this->get_widget_fields();

		if ( empty( $widget_fields ) ) {
			return $instance;
		}

		foreach ( $widget_fields as $field ) {

			if ( empty( $field['type'] ) ) {
				continue;
			}

			switch ( $field['type'] ) {

				case 'number':
					$instance[ $field['id'] ] = empty( $new_instance[ $field['id'] ] ) ? 0 : (int) $new_instance[ $field['id'] ];

					if ( isset( $field['min'] ) && '' !== $field['min'] ) {
						$instance[ $field['id'] ] = max( $instance[ $field['id'] ], $field['min'] );
					}

					if ( isset( $field['max'] ) && '' !== $field['max'] ) {
						$instance[ $field['id'] ] = min( $instance[ $field['id'] ], $field['max'] );
					}
					break;

				case 'select_posts':
					$instance[ $field['id'] ] = empty( $new_instance[ $field['id'] ] ) ? '' : (int) $new_instance[ $field['id'] ];
					break;

				case 'checkbox':
					$instance[ $field['id'] ] = empty( $new_instance[ $field['id'] ] ) ? 0 : 1;
					break;

				default:
					$instance[ $field['id'] ] = isset( $new_instance[ $field['id'] ] ) ? sanitize_text_field( $new_instance[ $field['id'] ] ) : '';
					break;
			}
		}

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}
}
