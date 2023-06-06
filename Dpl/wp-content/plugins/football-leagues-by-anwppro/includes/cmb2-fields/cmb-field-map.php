<?php
/*
CMB2 Field Type: Maps by anwp.pro
Version: 0.1.0
License: GPLv2+
*/

// Field Hooks
add_filter( 'cmb2_sanitize_anwp_map', 'cmb2_sanitize_anwp_map_callback', 10, 2 );
add_action( 'cmb2_render_anwp_map', 'cmb2_render_anwp_map_callback', 10, 5 );

if ( ! function_exists( 'cmb2_sanitize_anwp_map_callback' ) ) {

	/**
	 * Filter the value before it is saved.
	 *
	 * @param bool|mixed $override_value Sanitization/Validation override value to return. Default false to skip it.
	 * @param mixed      $value          The value to be saved to this field.
	 *
	 * @return mixed
	 */
	function cmb2_sanitize_anwp_map_callback( $override_value, $value ) {
		return $value;
	}
}

if ( ! function_exists( 'cmb2_render_anwp_map_callback' ) ) {

	/**
	 * @param array  $field              The passed in `CMB2_Field` object
	 * @param mixed  $escaped_value      The value of this field escaped.
	 *                                   It defaults to `sanitize_text_field`.
	 *                                   If you need the unescaped value, you can access it
	 *                                   via `$field->value()`
	 * @param int    $field_object_id    The ID of the current object
	 * @param string $field_object_type  The type of object you are working with.
	 *                                   Most commonly, `post` (this applies to all post-types),
	 *                                   but could also be `comment`, `user` or `options-page`.
	 * @param object $field_type_object  This `CMB2_Types` object
	 */
	function cmb2_render_anwp_map_callback( $field, $escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		ob_start(); ?>

		<span class="d-block h6"><?php echo esc_html__( 'Set location', 'anwp-football-leagues' ); ?></span>

		<div class="form-row">
			<div class="col">
				<label for=""><?php echo esc_html__( 'Latitude', 'anwp-football-leagues' ); ?></label>
				<?php
				echo $field_type_object->input(
					[
						'type'     => 'text',
						'name'     => $field->args( '_name' ) . '[lat]',
						'value'    => empty( $escaped_value['lat'] ) ? '' : esc_attr( $escaped_value['lat'] ),
						'class'    => 'form-control',
						'readonly' => 'true',
						'id'       => 'anwp_cmb2_map_input_latitude',
					]
				); // WPCS: XSS ok.
				?>
			</div>
			<div class="col">
				<label for="anwp_cmb2_map_input_longitude"><?php echo esc_html__( 'Longitude', 'anwp-football-leagues' ); ?></label>
				<?php
				echo $field_type_object->input(
					[
						'type'     => 'text',
						'name'     => $field->args( '_name' ) . '[longitude]',
						'value'    => empty( $escaped_value['longitude'] ) ? '' : esc_attr( $escaped_value['longitude'] ),
						'class'    => 'form-control',
						'readonly' => 'true',
						'id'       => 'anwp_cmb2_map_input_longitude',
					]
				); // WPCS: XSS ok.
				?>
			</div>
			<div class="col">
				<button id="anwp_cmb2_map_reset_btn" type="button" class="button mt-4"><?php echo esc_html__( 'Reset location', 'anwp-football-leagues' ); ?></button>
			</div>
		</div>

		<div class="form-row mt-3">
			<div class="col-lg-6">
				<label for="anwp_cmb2_map_input_address"><?php echo esc_html__( 'Address Search', 'anwp-football-leagues' ); ?></label>
				<input type="text" class="form-control" name="" id="anwp_cmb2_map_input_address" value="">
			</div>
		</div>

		<?php if ( anwp_football_leagues()->options->get_value( 'google_maps_api' ) ) : ?>
			<div id="anwp_map_wrapper"></div>
		<?php else : ?>
			<div class="alert alert-warning my-2"><?php echo esc_html__( 'Please insert Google Maps API Key in plugin settings.', 'anwp-football-leagues' ); ?></div>
		<?php endif; ?>

		<?php

		// grab the data from the output buffer.
		echo ob_get_clean(); // WPCS: XSS ok.
	}
}
