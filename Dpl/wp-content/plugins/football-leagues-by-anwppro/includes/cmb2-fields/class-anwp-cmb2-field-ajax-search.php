<?php
/**
 * *********************
 * Modified version
 * by anwppro
 *
 * @version 0.1.0
 * *********************
 *
 * Original Plugin:
 *
 * @package      CMB2\Field_Ajax_Search
 * @author       Tsunoa
 * @copyright    Copyright (c) Tsunoa
 *
 * Plugin Name: CMB2 Field Type: Ajax Search
 * Plugin URI: https://github.com/rubengc/cmb2-field-ajax-search
 * GitHub Plugin URI: https://github.com/rubengc/cmb2-field-ajax-search
 * Description: CMB2 field type to attach posts, users or terms.
 * Version: 1.0.
 * Author: Tsunoa
 * Author URI: https://tsunoa.com/
 * License: GPLv2+
 */

// This plugin is based on CMB2 Field Type: Post Search Ajax (https://github.com/alexis-magina/cmb2-field-post-search-ajax)
// Special thanks to Magina (http://magina.fr/) for him awesome work

if ( ! class_exists( 'AnWP_CMB2_Field_Ajax_Search' ) ) {

	/**
	 * Class CMB2_Field_Ajax_Search
	 */
	class AnWP_CMB2_Field_Ajax_Search {

		/**
		 * Current version number
		 */
		const VERSION = '0.1.1';

		/**
		 * Initialize the plugin by hooking into CMB2
		 */
		public function __construct() {

			// Render
			add_action( 'cmb2_render_anwp_post_ajax_search', [ $this, 'render' ], 10, 5 );
			add_action( 'cmb2_render_anwp_user_ajax_search', [ $this, 'render' ], 10, 5 );
			add_action( 'cmb2_render_anwp_term_ajax_search', [ $this, 'render' ], 10, 5 );

			// Display
			add_filter( 'cmb2_pre_field_display_anwp_post_ajax_search', [ $this, 'display' ], 10, 3 );
			add_filter( 'cmb2_pre_field_display_anwp_user_ajax_search', [ $this, 'display' ], 10, 3 );
			add_filter( 'cmb2_pre_field_display_anwp_term_ajax_search', [ $this, 'display' ], 10, 3 );

			// Sanitize
			add_action( 'cmb2_sanitize_anwp_post_ajax_search', [ $this, 'sanitize' ], 10, 4 );
			add_action( 'cmb2_sanitize_anwp_user_ajax_search', [ $this, 'sanitize' ], 10, 4 );
			add_action( 'cmb2_sanitize_anwp_term_ajax_search', [ $this, 'sanitize' ], 10, 4 );

			// Ajax request
			add_action( 'wp_ajax_anwp_cmb_ajax_search_get_results', [ $this, 'get_ajax_results' ] );

			add_filter( 'cmb__anwpfl_player_id_ajax_search_result_text', [ $this, 'modify_text_for_player_id' ], 10, 3 );
		}

		/**
		 * Modify player output.
		 *
		 * @param $text
		 * @param $object_id
		 * @param $object_type
		 *
		 * @return string
		 */
		public function modify_text_for_player_id( $text, $object_id, $object_type ) {

			if ( ! in_array( $object_type, [ 'anwp_post' ], true ) ) {
				return $text;
			}

			$post_type = get_post_type( $object_id );

			switch ( $post_type ) {
				case 'anwp_player':

					// Age
					$dob = get_post_meta( $object_id, '_anwpfl_date_of_birth', true );
					if ( ! empty( $dob ) ) {
						$text .= ' [' . $dob . ']';
					}

					// Country
					$country = get_post_meta( $object_id, '_anwpfl_nationality', true );
					if ( ! empty( $country ) && is_array( $country ) ) {
						$text .= mb_strtoupper( ' - ' . implode( ', ', $country ) );
					}
					break;
			}

			return $text;
		}

		/**
		 * Render field
		 */
		public function render( $field, $value, $object_id, $object_type, $field_type ) {
			$field_name    = $field->_name();
			$default_limit = 1;

			// Current filter is cmb2_render_{$object_to_search}_ajax_search ( post, user or term )
			$object_to_search = str_replace( 'cmb2_render_', '', str_replace( '_ajax_search', '', current_filter() ) );

			if ( true === $field->args( 'multiple' ) ) {
				$default_limit = - 1; // 0 or -1 means unlimited

				?>
				<ul id="<?php echo esc_attr( $field_name ); ?>_results" class="anwp-cmb-ajax-search-results cmb-<?php echo esc_attr( $object_to_search ); ?>-ajax-search-results">
				<?php
				if ( isset( $value ) && ! empty( $value ) ) {
					if ( ! is_array( $value ) ) {
						$value = [ $value ];
					}

					foreach ( $value as $val ) :
						?>
						<li>
							<?php if ( $field->args( 'sortable' ) ) : ?>
								<span class="hndl"></span>
							<?php endif; ?>
							<input type="hidden" name="<?php echo esc_attr( $field_name ); ?>[]" value="<?php echo esc_attr( $val ); ?>">
							<a href="<?php echo esc_url( $this->object_link( $field_name, $val, $object_to_search ) ); ?>" target="_blank" class="edit-link">
								<?php echo esc_html( $this->object_text( $field_name, $val, $object_to_search ) ); ?>
							</a>
							<a class="remover"><span class="dashicons dashicons-no"></span><span class="dashicons dashicons-dismiss"></span></a>
						</li>
						<?php
					endforeach;
				}
				?>
				</ul>
				<?php

				$input_value = '';
			} else {
				if ( is_array( $value ) ) {
					$value = $value[0];
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $field_type->input(
					[
						'type'  => 'hidden',
						'name'  => $field_name,
						'value' => $value,
						'desc'  => false,
					]
				);

				$input_value = ( $value ? $this->object_text( $field_name, $value, $object_to_search ) : '' );
			}

			$input_args = [
				'type'             => 'text',
				'name'             => $field_name . '_input',
				'id'               => $field_name . '_input',
				'class'            => 'anwp-cmb-ajax-search anwp-cmb-ajax-search--single cmb-' . $object_to_search . '-ajax-search',
				'value'            => $input_value,
				'desc'             => false,
				'data-multiple'    => $field->args( 'multiple' ) ? $field->args( 'multiple' ) : '0',
				'data-limit'       => $field->args( 'limit' ) ? $field->args( 'limit' ) : $default_limit,
				'data-sortable'    => $field->args( 'sortable' ) ? $field->args( 'sortable' ) : '0',
				'data-field-id'    => $field_name,
				'data-object-type' => $object_to_search,
				'data-query-args'  => $field->args( 'query_args' ) ? htmlspecialchars( wp_json_encode( $field->args( 'query_args' ) ), ENT_QUOTES, 'UTF-8' ) : '',
			];

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $field_type->input( $input_args );
			echo '<span class="spinner"></span>';

			$field_type->_desc( true, true );

		}

		/**
		 * Display field
		 */
		public function display( $pre_output, $field, $display ) {
			$object_type = str_replace( 'cmb2_pre_field_display_', '', str_replace( '_ajax_search', '', current_filter() ) );

			ob_start();

			$field->peform_param_callback( 'before_display_wrap' );

			printf( "<div class=\"cmb-column %s\" data-fieldtype=\"%s\">\n", $field->row_classes( 'display' ), $field->type() );

			$field->peform_param_callback( 'before_display' );

			if ( is_array( $field->value ) ) : ?>
				<?php foreach ( $field->value as $value ) : ?>
					<a href="<?php echo $this->object_link( $field->args['id'], $value, $object_type ); ?>" class="edit-link">
						<?php echo $this->object_text( $field->args['id'], $value, $object_type ); ?>
					</a> <br>
				<?php endforeach; ?>
			<?php else : ?>
				<a href="<?php echo $this->object_link( $field->args['id'], $field->value, $object_type ); ?>" class="edit-link">
					<?php echo $this->object_text( $field->args['id'], $field->value, $object_type ); ?>
				</a>
			<?php endif;

			$field->peform_param_callback( 'after_display' );

			echo "\n</div>";

			$field->peform_param_callback( 'after_display_wrap' );

			$pre_output = ob_get_clean();

			return $pre_output;
		}

		/**
		 * Optionally save the latitude/longitude values into two custom fields
		 */
		public function sanitize( $override_value, $value, $object_id, $field_args ) {
			$fid = $field_args['id'];

			if ( ! empty( $field_args['render_row_cb'][0]->data_to_save[ $field_args['id'] ] ) && $field_args['render_row_cb'][0]->data_to_save[ $field_args['id'] ] ) {
				$value = $field_args['render_row_cb'][0]->data_to_save[ $field_args['id'] ];
			} else {
				$value = false;
			}

			return $value;
		}

		/**
		 * Get results for ajax request.
		 *
		 * @since 0.1.0
		 */
		public function get_ajax_results() {

			// Check if our nonce is set.
			if ( ! isset( $_POST['nonce'] ) ) {
				wp_send_json_error( 'Error : Unauthorized action' );
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax_anwpfl_nonce' ) ) {
				wp_send_json_error( 'Error : Unauthorized action' );
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'Error : Unauthorized action' );
			}

			// Check mandatory fields
			if ( empty( $_POST['field_id'] ) || empty( $_POST['object_type'] ) ) {
				wp_send_json_error( 'Error : Unauthorized action' );
			}

			$object_type = sanitize_key( $_POST['object_type'] );
			$field_id    = sanitize_key( $_POST['field_id'] );

			$query_args = $this->recursive_sanitize( wp_unslash( $_POST['query_args'] ) );

			$data    = [];
			$results = [];

			switch ( $object_type ) {
				case 'anwp_post':
					$query_args['s'] = sanitize_text_field( $_POST['query'] );
					$query           = new WP_Query( $query_args );
					$results         = $query->posts;
					break;

				case 'anwp_user':
					$query_args['search'] = '*' . sanitize_text_field( $_POST['query'] ) . '*';
					$query                = new WP_User_Query( $query_args );
					$results              = $query->results;
					break;

				case 'anwp_term':
					$query_args['search'] = sanitize_text_field( $_POST['query'] );
					$query                = new WP_Term_Query( $query_args );
					$results              = $query->terms;
					break;
			}

			foreach ( $results as $result ) {

				$result_id = $result->ID;

				if ( 'anwp_term' === $_POST['object_type'] ) {
					$result_id = $result->term_id;
				}

				$data[] = [
					'id'    => $result_id,
					'value' => $this->object_text( $field_id, $result_id, $object_type ),
					'link'  => $this->object_link( $field_id, $result_id, $object_type ),
				];
			}

			wp_send_json_success( $data );
		}

		/**
		 * Method returns object text.
		 *
		 * @param $field_id
		 * @param $object_id
		 * @param $object_type
		 *
		 * @return mixed|string|void
		 * @since 0.1.0
		 */
		public function object_text( $field_id, $object_id, $object_type ) {
			$text = '';

			if ( 'anwp_post' === $object_type ) {
				$text = get_the_title( $object_id );
			} elseif ( 'anwp_user' === $object_type ) {
				$text = get_the_author_meta( 'display_name', $object_id );
			} elseif ( 'anwp_term' === $object_type ) {
				$term = get_term( $object_id );
				$text = $term->name;
			}

			$text = apply_filters( "cmb_{$field_id}_ajax_search_result_text", $text, $object_id, $object_type );

			return $text;
		}

		/**
		 * Returns object link.
		 *
		 * @param $field_id
		 * @param $object_id
		 * @param $object_type
		 *
		 * @return mixed|string|void|null
		 * @since 0.1.0
		 */
		public function object_link( $field_id, $object_id, $object_type ) {
			$link = '#';

			if ( 'anwp_post' === $object_type ) {
				$link = get_edit_post_link( $object_id );
			} elseif ( 'anwp_user' === $object_type ) {
				$link = get_edit_user_link( $object_id );
			} elseif ( 'anwp_term' === $object_type ) {
				$link = get_edit_term_link( $object_id );
			}

			$link = apply_filters( "cmb_{$field_id}_ajax_search_result_link", $link, $object_id, $object_type );

			return $link;
		}

		/**
		 * Recursive sanitization.
		 *
		 * @param string|array
		 *
		 * @return string|array
		 */
		public function recursive_sanitize( $value ) {
			if ( is_array( $value ) ) {
				return array_map( [ $this, 'recursive_sanitize' ], $value );
			} else {
				return is_scalar( $value ) ? sanitize_text_field( $value ) : $value;
			}
		}

	}

	new AnWP_CMB2_Field_Ajax_Search();
}
