<?php
/*
CMB2 Field Type: AnWP Text Translated by anwp.pro
Version: 0.1.0
License: GPLv2+

Using example:
*/

add_action( 'cmb2_render_anwp_fl_text', 'cmb2_render_anwp_fl_text', 10, 2 );
add_filter( 'cmb2_sanitize_anwp_fl_text', 'cmb2_sanitize_anwp_fl_text', 10, 4 );

if ( ! function_exists( 'cmb2_render_anwp_fl_text' ) ) {

	/**
	 * Render Simple Trigger field
	 *
	 * @param $field
	 * @param $value
	 */
	function cmb2_render_anwp_fl_text( $field, $value ) {
		?>
		<div class="row align-items-center my-n3 anwp-fl-search-data" data-search-origin="<?php echo esc_attr( mb_strtolower( $field->args['name'] ) ); ?>"
			data-search-modified="<?php echo esc_attr( mb_strtolower( $value ) ); ?>">
			<div class="col-sm-4"><?php echo esc_html( $field->args['name'] ); ?></div>
			<div class="col-sm-4">
				<input class="w-100" name="<?php echo esc_attr( $field->args['id'] ); ?>" type="text" value="<?php echo esc_html( $value ); ?>"></div>
			<div class="col-sm-4"><?php echo esc_html( $field->args['desc'] ); ?></div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cmb2_sanitize_anwp_fl_text' ) ) {
	/**
	 * Sanitize Text field
	 *
	 * @param $null
	 * @param $value
	 * @param $object_id
	 * @param $args
	 *
	 * @return mixed
	 */
	function cmb2_sanitize_anwp_fl_text( $null, $value, $object_id, $args ) {

		if ( ! isset( $args['options'][ $value ] ) ) {
			return $args['default'];
		}

		return $value;
	}
}
