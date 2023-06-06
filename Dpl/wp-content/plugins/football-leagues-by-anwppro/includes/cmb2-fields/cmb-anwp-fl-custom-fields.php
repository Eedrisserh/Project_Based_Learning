<?php
/*
CMB2 Field Type: AnWP FL Custom Fields
Version: 0.1.0
License: GPLv2+
Author: Andrei Strekozov - https://anwp.pro

Usage example:
	'type'        => 'anwp_fl_custom_fields',
	'option_slug' => 'player_custom_fields',
*/

add_action( 'cmb2_render_anwp_fl_custom_fields', 'cmb2_render_anwp_fl_custom_fields', 10, 5 );
add_filter( 'cmb2_sanitize_anwp_fl_custom_fields', 'cmb2_sanitize_anwp_fl_custom_fields', 10, 2 );

if ( ! function_exists( 'cmb2_render_anwp_fl_custom_fields' ) ) {

	/**
	 * Render AnWP Custom Fields
	 *
	 * @param array      $field               The passed in `CMB2_Field` object
	 * @param mixed      $saved_options       The value of this field escaped.
	 *                                        It defaults to `sanitize_text_field`.
	 *                                        If you need the unescaped value, you can access it
	 *                                        via `$field->value()`
	 * @param int        $field_object_id     The ID of the current object
	 * @param string     $field_object_type   The type of object you are working with.
	 *                                        Most commonly, `post` (this applies to all post-types),
	 *                                        but could also be `comment`, `user` or `options-page`.
	 * @param CMB2_Types $field_type_object   The `CMB2_Types` object
	 *
	 * @return void
	 */
	function cmb2_render_anwp_fl_custom_fields( $field, $saved_options, $field_object_id, $field_object_type, $field_type_object ) {

		// Prepare saved values
		if ( empty( $saved_options ) || ! is_array( $saved_options ) ) {
			$saved_options = [];
		}

		// Get custom fields config
		$option_slug = empty( $field->args['option_slug'] ) ?: $field->args['option_slug'];

		if ( ! $option_slug ) {
			return;
		}

		// Get Custom fields list
		$options = AnWPFL_Options::get_value( $option_slug );

		if ( empty( $options ) || ! is_array( $options ) ) {
			echo '<div class="alert alert-info px-5">' . esc_html__( 'Dynamic custom fields not exist', 'anwp-football-leagues' ) . '</div>';

			return;
		}
		?>
		<div class="anwp-cmb2-custom-fields__wrapper">
			<?php foreach ( $options as $option ) : ?>
				<div class="d-block mb-2">
					<label class="d-block" for="<?php echo esc_attr( $field_type_object->_id( '_' . sanitize_text_field( $option ) ) ); ?>"><?php echo esc_html( $option ); ?></label>
					<?php
					$input_args = [
						'name'  => $field_type_object->_name( '[' . sanitize_text_field( $option ) . ']' ),
						'id'    => $field_type_object->_id( '_' . sanitize_text_field( $option ) ),
						'value' => isset( $saved_options[ $option ] ) ? $saved_options[ $option ] : '',
					];

					echo $field_type_object->input( $input_args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cmb2_sanitize_anwp_fl_custom_fields' ) ) {

	/**
	 * Sanitize AnWP Custom Fields
	 *
	 * @param        $null
	 * @param mixed  $value The value to be saved to this field.
	 *
	 * @return mixed
	 */
	function cmb2_sanitize_anwp_fl_custom_fields( $null, $value ) {

		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}

		return [];
	}
}
