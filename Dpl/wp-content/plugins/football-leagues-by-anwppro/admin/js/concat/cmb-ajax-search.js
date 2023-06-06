/* eslint-disable camelcase */
/**
 * *********************
 * Modified version
 * by anwppro
 *
 * TODO needs more improvements
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
( function( document, $ ) {
	function init_ajax_search() {
		$( '.anwp-cmb-ajax-search:not([data-ajax-search="true"])' ).each( function() {

			var $this       = $( this );
			var $list       = $this.closest( 'div' ).find( 'ul' );
			var field_id    = $this.data( 'field-id' );
			var object_type = $this.data( 'object-type' );
			var query_args  = $this.data( 'query-args' );

			$this.attr( 'data-ajax-search', true );

			$this.devbridgeAutocomplete( {
				serviceUrl: ajaxurl,
				deferRequestBy: 200,
				type: 'POST',
				dataType: 'json',
				triggerSelectOnValidInput: false,
				showNoSuggestionNotice: true,
				params: {
					action: 'anwp_cmb_ajax_search_get_results',
					nonce: anwpflGlobals.ajaxNonce,
					field_id: field_id,		// Field id for hook purposes
					object_type: object_type, 	// post, user or term
					query_args: query_args 	// Query args passed to field
				},
				transformResult: function( results ) {

					if ( results.success !== true ) {
						toastr.error( 'Data Error' );
						return {suggestions: []};
					}

					if ( $( '#' + field_id + '_results li' ).length ) {
						var selected = [];

						$list.find( 'li input[type="hidden"]' ).each( function() {
							selected.push( Number( $( this ).val() ) );
						} );

						// Remove already picked suggestions
						results.data = _.reject( results.data, function( i ) {
							return _.contains( selected, i.id );
						} );
					}

					return {suggestions: results.data};
				},
				onSearchStart: function() {
					$this.next( '.spinner' ).addClass( 'is-active' );
				},
				onSearchComplete: function() {
					$this.next( '.spinner' ).removeClass( 'is-active' );
				},
				onSelect: function( suggestion ) {
					$this.devbridgeAutocomplete( 'clearCache' );

					var field_name = $this.attr( 'id' ).replace( new RegExp( '_input$' ), '' );
					var multiple   = Number( $this.attr( 'data-multiple' ) );
					var limit      = Number( $this.attr( 'data-limit' ) );
					var sortable   = $this.attr( 'data-sortable' );

					if ( multiple === 1 ) {

						// Multiple
						$( '#' + field_name + '_results' ).append( '<li>' +
							( ( sortable == 1 ) ? '<span class="hndl"></span>' : '' ) +
							'<input type="hidden" name="' + field_name + '[]" value="' + suggestion.id + '">' +
							'<a href="' + suggestion.link + '" target="_blank" class="edit-link">' + suggestion.value + '</a>' +
							'<a class="remover"><span class="dashicons dashicons-no"></span><span class="dashicons dashicons-dismiss"></span></a>' +
							'</li>' );

						$this.val( '' );

						// Checks if there is the max allowed results, limit < 0 means unlimited
						if ( limit > 0 && limit == $( '#' + field_name + '_results li' ).length ) {
							$this.prop( 'disabled', 'disabled' );
						} else {
							$this.focus();
						}
					} else {

						// Singular
						$( 'input[name=' + field_name + ']' ).val( suggestion.id ).change();
					}
				}
			} );

			if ( Number( $this.attr( 'data-sortable' ) ) === 1 ) {
				$( '#' + field_id + '_results' ).sortable( {
					handle: '.hndl',
					placeholder: 'ui-state-highlight',
					forcePlaceholderSize: true
				} );
			}
		} );
	}

	// Initialize ajax search
	init_ajax_search();

	// Initialize on group fields add row
	$( document ).on( 'cmb2_add_row', function( evt, $row ) {
		$row.find( '.anwp-cmb-ajax-search' ).attr( 'data-ajax-search', false );

		init_ajax_search();
	} );

	// Initialize on widgets area
	$( document ).on( 'widget-updated widget-added', function() {
		init_ajax_search();
	} );

	// On click remover listener
	$( 'body' ).on( 'click', '.anwp-cmb-ajax-search-results a.remover', function() {
		$( this ).parent( 'li' ).fadeOut( 400, function() {
			var field_id = $( this ).parents( 'ul' ).attr( 'id' ).replace( '_results', '' );

			$( '#' + field_id ).removeProp( 'disabled' );
			$( '#' + field_id ).devbridgeAutocomplete( 'clearCache' );

			$( this ).remove();
		} );
	} );
} )( document, jQuery );
