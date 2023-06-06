<?php
/*
CMB2 Field Type: Ordering List by anwp.pro
Version: 0.1.0
License: GPLv2+

Usage example:
'type'    => 'anwpfl_ordering_list',
'options' => [
	'goals'            => esc_html__( 'Goals', 'anwp-football-leagues' ),
	'penalty_shootout' => esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ),
	'missed_penalties' => esc_html__( 'Missed Penalties', 'anwp-football-leagues' ),
	'line_ups'         => esc_html__( 'Line Ups', 'anwp-football-leagues' ),
	'substitutes'      => esc_html__( 'Substitutes', 'anwp-football-leagues' ),
	'referees'         => esc_html__( 'Referees', 'anwp-football-leagues' ),
	'video'            => esc_html__( 'Video', 'anwp-football-leagues' ),
	'cards'            => esc_html__( 'Cards', 'anwp-football-leagues' ),
	'stats'            => esc_html__( 'Stats', 'anwp-football-leagues' ),
	'summary'          => esc_html__( 'Summary', 'anwp-football-leagues' ),
	'latest'           => esc_html__( 'Latest', 'anwp-football-leagues' ),
],
*/

add_action( 'cmb2_render_anwpfl_ordering_list', 'cmb2_render_anwpfl_ordering_list', 10, 2 );
add_filter( 'cmb2_sanitize_anwpfl_ordering_list', 'cmb2_sanitize_anwpfl_ordering_list', 10, 4 );

if ( ! function_exists( 'cmb2_render_anwpfl_ordering_list' ) ) {

	/**
	 * Render Ordering List field
	 *
	 * @param array $field               The passed in `CMB2_Field` object
	 * @param mixed $value               The value of this field escaped.
	 *                                   It defaults to `sanitize_text_field`.
	 *                                   If you need the unescaped value, you can access it
	 */
	function cmb2_render_anwpfl_ordering_list( $field, $value ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			$value = [];
		}
		?>
		<div class="clearfix">
			<?php foreach ( $field->args['options'] as $key => $option ) : ?>

				<div class="d-block">
					<label for="<?php printf( '%1$s_%2$s', esc_attr( $field->args['id'] ), esc_attr( $key ) ); ?>">
					<?php

					$section_value = 0;

					if ( isset( $value[ $key ] ) ) {
						$section_value = $value[ $key ];
					}

					printf(
						'<input class="anwp-input-number-medium mr-2" id="%1$s_%2$s" name="%1$s[%2$s]" type="number" value="%3$d">',
						esc_attr( $field->args['id'] ),
						esc_attr( $key ),
						(int) $section_value
					);
					echo esc_html( $option );
					?>
					</label>
				</div>
			<?php endforeach; ?>
			<p class="cmb2-metabox-description"><?php echo esc_html( $field->args['desc'] ); ?></p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cmb2_sanitize_anwpfl_ordering_list' ) ) {
	/**
	 * Sanitize Simple Trigger field
	 *
	 * @param        $null
	 * @param mixed  $value     The value to be saved to this field.
	 * @param int    $object_id The ID of the object where the value will be saved
	 * @param array  $args      The current field's arguments
	 *
	 * @return mixed
	 */
	function cmb2_sanitize_anwpfl_ordering_list( $null, $value, $object_id, $args ) {

		$sanitized_value = [];

		if ( ! is_array( $value ) || ! is_array( $args['options'] ) ) {
			return $sanitized_value;
		}

		foreach ( $args['options'] as $key => $option ) {
			if ( isset( $value[ $key ] ) ) {
				$sanitized_value[ $key ] = (int) $value[ $key ];
			}
		}

		return $sanitized_value;
	}
}
