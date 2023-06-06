<?php
/*
 * AnWP_FL_CMB2_Field_Select2
 * Author: Andrei Strekozov <anwp.pro>
 * License: GPLv2+
 *
 * --
 * Based on - CMB2 Field Type: Select2
 * URI: https://github.com/mustardBees/cmb-field-select2
 * Author: Phil Wylie
 * Author URI: https://www.philwylie.co.uk/
 * License: GPLv2+
*/

if ( ! class_exists( 'AnWP_FL_CMB2_Field_Select2' ) ) :

	class AnWP_FL_CMB2_Field_Select2 {

		/**
		 * Current version number
		 */
		const VERSION = '1.0.0';

		/**
		 * Initialize the plugin by hooking into CMB2
		 */
		public function __construct() {
			add_filter( 'cmb2_render_anwp_fl_select', [ $this, 'render_select' ], 10, 5 );
			add_filter( 'cmb2_render_anwp_fl_multiselect', [ $this, 'render_multiselect' ], 10, 5 );
			add_filter( 'cmb2_sanitize_anwp_fl_multiselect', [ $this, 'multiselect_sanitize' ], 10, 4 );
			add_filter( 'cmb2_types_esc_anwp_fl_multiselect', [ $this, 'multiselect_escaped_value' ], 10, 3 );
			add_filter( 'cmb2_repeat_table_row_types', [ $this, 'multiselect_table_row_class' ], 10, 1 );
		}

		/**
		 * Render single select box field.
		 *
		 * @version 1.0.0
		 */
		public function render_select( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

			$attr = [
				'class'            => 'anwp_fl_cmb_select2',
				'desc'             => $field_type_object->_desc( true ),
				'options'          => '<option></option>' . $field_type_object->concat_items(),
				'data-placeholder' => $field->args( 'attributes', 'placeholder' ) ? $field->args( 'attributes', 'placeholder' ) : $field->args( 'description' ),
			];

			echo $field_type_object->select( $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Render multi-value select input field
		 */
		public function render_multiselect( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

			$options = [
				'multiple'         => 'multiple',
				'style'            => 'width: 99%',
				'class'            => 'anwp_fl_cmb_select2_multi',
				'name'             => $field_type_object->_name() . '[]',
				'id'               => $field_type_object->_id(),
				'desc'             => $field_type_object->_desc( true ),
				'options'          => $this->get_multiselect_options( $field_escaped_value, $field_type_object ),
				'data-placeholder' => $field->args( 'attributes', 'placeholder' ) ? $field->args( 'attributes', 'placeholder' ) : $field->args( 'description' ),
			];

			$a = $field_type_object->parse_args( 'anwp_fl_multiselect', $options );

			$attrs = $field_type_object->concat_attrs( $a, [ 'desc', 'options' ] );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sprintf( '<select%s>%s</select>%s', $attrs, $a['options'], $a['desc'] );
		}

		/**
		 * Return list of options for anwp_fl_multiselect
		 *
		 * Return the list of options, with selected options at the top preserving their order. This also handles the
		 * removal of selected options which no longer exist in the options array.
		 */
		public function get_multiselect_options( $field_escaped_value = [], $field_type_object ) {
			$options = (array) $field_type_object->field->options();

			// If we have selected items, we need to preserve their order
			if ( ! empty( $field_escaped_value ) ) {
				$options = $this->sort_array_by_array( $options, $field_escaped_value );
			}

			$selected_items = '';
			$other_items    = '';

			foreach ( $options as $option_value => $option_label ) {

				// Clone args & modify for just this item
				$option = [
					'value' => $option_value,
					'label' => $option_label,
				];

				// Split options into those which are selected and the rest
				if ( in_array( $option_value, (array) $field_escaped_value ) ) {
					$option['checked']  = true;
					$selected_items    .= $field_type_object->select_option( $option );
				} else {
					$other_items .= $field_type_object->select_option( $option );
				}
			}

			return $selected_items . $other_items;
		}

		/**
		 * Sort an array by the keys of another array
		 *
		 * @author Eran Galperin
		 * @link   http://link.from.pw/1Waji4l
		 */
		public function sort_array_by_array( array $array, array $orderArray ) {
			$ordered = [];

			foreach ( $orderArray as $key ) {
				if ( array_key_exists( $key, $array ) ) {
					$ordered[ $key ] = $array[ $key ];
					unset( $array[ $key ] );
				}
			}

			return $ordered + $array;
		}

		/**
		 * Handle sanitization for repeatable fields
		 */
		public function multiselect_sanitize( $check, $meta_value, $object_id, $field_args ) {
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}

			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
			}

			return $meta_value;
		}

		/**
		 * Handle escaping for repeatable fields
		 */
		public function multiselect_escaped_value( $check, $meta_value, $field_args ) {
			if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
				return $check;
			}

			foreach ( $meta_value as $key => $val ) {
				$meta_value[ $key ] = array_map( 'esc_attr', $val );
			}

			return $meta_value;
		}

		/**
		 * Add 'table-layout' class to multi-value select field
		 */
		public function multiselect_table_row_class( $check ) {
			$check[] = 'anwp_fl_cmb_select2_multi';

			return $check;
		}
	}

endif;

new AnWP_FL_CMB2_Field_Select2();
