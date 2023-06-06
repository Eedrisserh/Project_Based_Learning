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

( function( $ ) {

	'use strict';

	$( function() {

		if ( $( '#anwp_map_wrapper' ).length ) {

			var map;
			var marker;
			var $longitude = $( '#anwp_cmb2_map_input_longitude' );
			var $latitude  = $( '#anwp_cmb2_map_input_latitude' );
			var $btnReset  = $( '#anwp_cmb2_map_reset_btn' );
			var $inputSearch  = $( '#anwp_cmb2_map_input_address' );

			var initialZoom     = $latitude.val() && $longitude.val() ? 15 : 8;
			var defaultPosition = {lat: $latitude.val() ? parseFloat( $latitude.val() ) : 51.556, lng: $longitude.val() ? parseFloat( $longitude.val() ) : -0.279575};

			var placesAutocomplete = places( {
				container: '#anwp_cmb2_map_input_address'
			} );

			placesAutocomplete.on( 'change', function( e ) {

				if ( e.suggestion.latlng ) {
					$latitude.val( e.suggestion.latlng.lat );
					$longitude.val( e.suggestion.latlng.lng );

					marker.setPosition( e.suggestion.latlng );
					map.setCenter( e.suggestion.latlng );
					map.setZoom( 17 );
				}
			} );

			$inputSearch.keypress( function( e ) {
				if ( 13 === parseInt( e.keyCode, 10 ) ) {
					e.preventDefault();
				}
			} );

			// Create new map
			map = new google.maps.Map( document.getElementById( 'anwp_map_wrapper' ), {
				center: defaultPosition,
				zoom: initialZoom
			} );

			// Create new marker
			marker = new google.maps.Marker( {
				position: defaultPosition,
				draggable: true,
				map: map
			} );

			// Add listeners
			google.maps.event.addListener( map, 'click', function( e ) {
				marker.setPosition( e.latLng );
				$inputSearch.val( '' );
				$latitude.val( e.latLng.lat() );
				$longitude.val( e.latLng.lng() );
			} );

			google.maps.event.addListener( marker, 'dragend', function( e ) {
				$latitude.val( e.latLng.lat() );
				$longitude.val( e.latLng.lng() );
				$inputSearch.val( '' );
			} );

			$btnReset.on( 'click', function( e ) {
				e.preventDefault();

				$latitude.val( '' );
				$longitude.val( '' );
			} );
		}
	} );

}( jQuery ) );

( function( $ ) {
		'use strict';

		var repeatableGroup = $( '.cmb-repeatable-group' );

		$( '.anwp_fl_cmb_select2' ).each( function() {
			$( this ).select2( {
				allowClear: true
			} );
		} );

		$.fn.extend( {
			select2_sortable: function() {
				var select = $( this );
				$( select ).select2();
				var ul = $( select ).next( '.select2-container' ).first( 'ul.select2-selection__rendered' );
				ul.sortable( {
					containment: 'parent',
					items: 'li:not(.select2-search--inline)',
					tolerance: 'pointer',
					stop: function() {
						$( $( ul ).find( '.select2-selection__choice' ).get().reverse() ).each( function() {
							var id     = $( this ).data( 'data' ).id;
							var option = select.find( 'option[value="' + id + '"]' )[ 0 ];
							$( select ).prepend( option );
						} );
					}
				} );
			}
		} );

		$( '.anwp_fl_cmb_select2_multi' ).each( function() {
			$( this ).select2_sortable();
		} );

		// Before a new group row is added, destroy Select2. We'll reinitialise after the row is added
		repeatableGroup.on( 'cmb2_add_group_row_start', function( event, instance ) {
			var $table  = $( document.getElementById( $( instance ).data( 'selector' ) ) );
			var $oldRow = $table.find( '.cmb-repeatable-grouping' ).last();

			$oldRow.find( '.anwp_fl_cmb_select2' ).each( function() {
				$( this ).select2( 'destroy' );
			} );
		} );

		// When a new group row is added, clear selection and initialise Select2
		repeatableGroup.on( 'cmb2_add_row', function( event, newRow ) {
			$( newRow ).find( '.anwp_fl_cmb_select2' ).each( function() {
				$( 'option:selected', this ).removeAttr( 'selected' );
				$( this ).select2( {
					allowClear: true
				} );
			} );

			$( newRow ).find( '.anwp_fl_cmb_select2_multi' ).each( function() {
				$( 'option:selected', this ).removeAttr( 'selected' );
				$( this ).select2_sortable();
			} );

			// Reinitialise the field we previously destroyed
			$( newRow ).prev().find( '.anwp_fl_cmb_select2' ).each( function() {
				$( this ).select2( {
					allowClear: true
				} );
			} );

			// Reinitialise the field we previously destroyed
			$( newRow ).prev().find( '.anwp_fl_cmb_select2_multi' ).each( function() {
				$( this ).select2_sortable();
			} );
		} );

		// Before a group row is shifted, destroy Select2. We'll reinitialise after the row shift
		repeatableGroup.on( 'cmb2_shift_rows_start', function( event, instance ) {
			var groupWrap = $( instance ).closest( '.cmb-repeatable-group' );
			groupWrap.find( '.anwp_fl_cmb_select2' ).each( function() {
				$( this ).select2( 'destroy' );
			} );

		} );

		// When a group row is shifted, reinitialise Select2
		repeatableGroup.on( 'cmb2_shift_rows_complete', function( event, instance ) {
			var groupWrap = $( instance ).closest( '.cmb-repeatable-group' );
			groupWrap.find( '.anwp_fl_cmb_select2' ).each( function() {
				$( this ).select2( {
					allowClear: true
				} );
			} );

			groupWrap.find( '.anwp_fl_cmb_select2_multi' ).each( function() {
				$( this ).select2_sortable();
			} );
		} );

		// Before a new repeatable field row is added, destroy Select2. We'll reinitialise after the row is added
		$( '.cmb-add-row-button' ).on( 'click', function( event ) {
			var $table  = $( document.getElementById( $( event.target ).data( 'selector' ) ) );
			var $oldRow = $table.find( '.cmb-row' ).last();

			$oldRow.find( '.anwp_fl_cmb_select2' ).each( function() {
				$( this ).select2( 'destroy' );
			} );
		} );

		// When a new repeatable field row is added, clear selection and initialise Select2
		$( '.cmb-repeat-table' ).on( 'cmb2_add_row', function( event, newRow ) {

			// Reinitialise the field we previously destroyed
			$( newRow ).prev().find( '.anwp_fl_cmb_select2' ).each( function() {
				$( 'option:selected', this ).removeAttr( 'selected' );
				$( this ).select2( {
					allowClear: true
				} );
			} );

			// Reinitialise the field we previously destroyed
			$( newRow ).prev().find( '.anwp_fl_cmb_select2_multi' ).each( function() {
				$( 'option:selected', this ).removeAttr( 'selected' );
				$( this ).select2_sortable();
			} );
		} );
	}
)( jQuery );

/**
 * AnWP Football Leagues
 * https://anwp.pro
 *
 * Licensed under the GPLv2+ license.
 */

window.AnWPFootballLeagues = window.AnWPFootballLeagues || {};

( function( window, document, $, plugin ) {

	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	var $c = {};

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body   = $( document.body );
	};

	plugin.bindEvents = function() {

		if ( document.readyState !== 'loading' ) {
			plugin.onPageReady();
		} else {
			document.addEventListener( 'DOMContentLoaded', plugin.onPageReady );
		}

		$c.body.on( 'click', '[data-anwpfl-recalculate-match-stats]', function( e ) {
			e.preventDefault();

			var $this = $( this );
			$this.data( 'oldText', $this.text() );

			jQuery.ajax( {
				dataType: 'json',
				method: 'GET',
				data: { option: $this.siblings( 'select' ).val() },
				beforeSend: function( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', anwp.rest_nonce );
					$this.text( 'processing request ...' );
				},
				url: anwp.rest_root + 'anwpfl/v1/helper/recalculate-matches-stats'
			} ).always( function() {
				$this.text( $this.data( 'oldText' ) );
			} ).fail( function() {
				toastr.error( 'ERROR !!!' );
			} );
		} );
	};

	plugin.onPageReady = function() {
		plugin.initTooltips();
		plugin.initBtnPublishClick();
		plugin.initMatchListHelper();
		plugin.tableInputNavigation();
		plugin.initTextSearch();
		plugin.initOptionTabs();
		plugin.initDependentOptions();
		plugin.initCompetitionCloneModaal();
	};

	/**
	 * Usage example
	 * ///////////////////////////////
	 * $cmb->add_field(
			[
				'name'       => esc_html__( 'Custom Outcome', 'anwp-football-leagues' ),
				'id'         => $prefix . 'custom_outcome',
				'type'       => 'select',
				'options'    => [
					''    => __( 'No', 'anwp-football-leagues' ),
					'yes' => __( 'Yes', 'anwp-football-leagues' ),
				],
				'attributes' => [
					'class'     => 'cmb2_select anwp-fl-parent-of-dependent',
					'data-name' => $prefix . 'custom_outcome',
				],
				'default'    => '',
				'before_row' => '<div id="anwp-tabs-outcome-match_metabox" class="anwp-metabox-tabs__content-item d-none">',
			]
		);

		$cmb->add_field(
			[
				'name'       => esc_html__( 'Outcome for home team', 'anwp-football-leagues' ),
				'id'         => $prefix . 'outcome_home',
				'type'       => 'select',
				'options'    => [
					''      => __( '- not selected -', 'anwp-football-leagues' ),
					'won'   => __( 'Won', 'anwp-football-leagues' ),
					'drawn' => __( 'Drawn', 'anwp-football-leagues' ),
					'lost'  => __( 'Lost', 'anwp-football-leagues' ),
				],
				'attributes' => [
					'class'       => 'regular-text anwp-fl-dependent-field',
					'data-parent' => $prefix . 'custom_outcome',
					'data-action' => 'show',
					'data-value'  => 'yes',
				],
			]
		);
	 */
	plugin.initDependentOptions = function() {
		var $wrapper = $( '.cmb2-metabox' );

		$wrapper.on( 'change', '.anwp-fl-parent-of-dependent', function() {

			var $parent = $( this );

			$wrapper.find( '.anwp-fl-dependent-field[data-parent="' + $parent.data( 'name' ) + '"]' ).each( function() {
				var childWrapper   = $( this );
				var childDataValue = childWrapper.data( 'value' ).split( ',' );

				if ( ( _.contains( childDataValue, $parent.val() ) && childWrapper.data( 'action' ) === 'show' ) || ( ! _.contains( childDataValue, $parent.val() ) && childWrapper.data( 'action' ) === 'hide' ) ) {
					childWrapper.closest( '.cmb-row' ).removeClass( 'd-none' );
				} else {
					childWrapper.closest( '.cmb-row' ).addClass( 'd-none' );
				}
			} );
		} );

		$wrapper.find( '.anwp-fl-parent-of-dependent' ).trigger( 'change' );
	};

	/**
	 * Initialize options and metabox tabs.
	 *
	 * @return {boolean} False if metabox not exists at the page.
	 */
	plugin.initOptionTabs = function() {

		var wrapper = $c.body.find( '.anwp-metabox-tabs' );

		if ( ! wrapper.length ) {
			return false;
		}

		wrapper.on( 'click', '.anwp-metabox-tabs__control-item', function( e ) {

			e.preventDefault();

			var $this  = $( this );

			if ( $this.hasClass( 'anwp-active-tab' ) ) {
				return false;
			}

			var target = $( $this.data( 'target' ) );

			$this.addClass( 'anwp-active-tab' ).siblings( '.anwp-metabox-tabs__control-item.anwp-active-tab' ).removeClass( 'anwp-active-tab' );
			target.removeClass( 'd-none invisible' ).siblings( '.anwp-metabox-tabs__content-item:not( .d-none )' ).addClass( 'd-none' );

			// Add hash to URL
			if ( $this.data( 'target' ) && $c.body.find( '#anwp_football_leagues_options_metabox' ).length ) {
				if ( history.pushState ) {
					history.pushState( {}, '', $this.data( 'target' ) );
				} else {
					window.location.hash = $this.data( 'target' ).substr( 1 );
				}

				wrapper.find( '#anwp_current_page_hash' ).val( $this.data( 'target' ) );
			}
		} );

		// Get initial active tab
		var initialTab;

		if ( window.location.hash && wrapper.find( '.anwp-metabox-tabs__control-item[data-target="' + window.location.hash + '"]' ).length ) {
			initialTab = wrapper.find( '.anwp-metabox-tabs__control-item[data-target="' + window.location.hash + '"]' );
		}

		if ( ! initialTab && wrapper.find( '#anwp_current_page_hash' ).val() ) {
			initialTab = wrapper.find( '.anwp-metabox-tabs__control-item[data-target="' + wrapper.find( '#anwp_current_page_hash' ).val() + '"]' );
		}

		if ( ! initialTab || ! initialTab.length ) {
			initialTab = wrapper.find( '.anwp-metabox-tabs__control-item:first-child' );
		}

		initialTab.trigger( 'click' );
	};

	plugin.initTextSearch = function() {
		var $input = $c.body.find( '#anwp-fl-live-text-search' );

		if ( ! $input.length ) {
			return false;
		}

		$input.on( 'input', function( e ) {
			e.preventDefault();

			var filter = $input.val().toLowerCase();

			$c.body.find( '#anwp_fl_text_metabox .anwp-fl-search-data' ).each( function() {
				var $this   = $( this );
				var search1 = $this.data( 'search-origin' );
				var search2 = $this.data( 'search-modified' );

				if ( search1.indexOf( filter ) !== -1 || search2.indexOf( filter ) !== -1 ) {
					$this.closest( '.cmb-type-anwp-fl-text' ).removeClass( 'd-none' );
				} else {
					$this.closest( '.cmb-type-anwp-fl-text' ).addClass( 'd-none' );
				}
			} );

			return false;
		} );
	};

	plugin.tableInputNavigation = function() {
		$( '.anwp-fl-input-table' ).find( 'td input' ).keyup( function( e ) {
			if ( e.which === 39 ) { // right arrow
				$( this ).closest( 'td' ).next().find( 'input' ).focus();
			} else if ( e.which === 37 ) { // left arrow
				$( this ).closest( 'td' ).prev().find( 'input' ).focus();
			} else if ( e.which === 40 ) { // down arrow
				$( this ).closest( 'tr' ).next().find( 'td:eq(' + $( this ).closest( 'td' ).index() + ')' ).find( 'input' ).focus();
			} else if ( e.which === 38 ) { // up arrow
				$( this ).closest( 'tr' ).prev().find( 'td:eq(' + $( this ).closest( 'td' ).index() + ')' ).find( 'input' ).focus();
			}
		} );

		$( '.anwp-fl-input-table' ).find( 'td input[type=number]' ).keydown( function( e ) {
			if ( e.which === 38 || e.which === 40 ) {
				e.preventDefault();
			}
		} );

	};

	plugin.initTooltips = function() {
		tippy( '[data-anwpfl_tippy]', {
			arrow: true,
			size: 'small'
		} );
	};

	plugin.initBtnPublishClick = function() {
		var btnClick   = $( '#anwp-publish-click-proxy' );
		var btnPublish = $( '#publish' );

		if ( btnClick.length ) {
			btnClick.on( 'click', function( e ) {
				e.preventDefault();

				if ( btnClick.prop( 'disabled' ) ) {
					return false;
				}

				btnClick.prop( 'disabled', true );
				btnClick.next( '.spinner' ).addClass( 'is-active' );

				if ( btnPublish.length ) {
					btnPublish.click();
				}
			} );
		}
	};

	plugin.initMatchListHelper = function() {

		if ( $c.body.find( 'input[name="_anwpfl_date_from"]' ).length && typeof jQuery.datepicker !== 'undefined' ) {
			var inputFrom = $c.body.find( 'input[name="_anwpfl_date_from"]' );
			var inputTo   = $c.body.find( 'input[name="_anwpfl_date_to"]' );

			$( inputFrom ).add( inputTo ).datepicker( {
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				beforeShow: function( input, inst ) {
					inst.dpDiv.addClass( 'cmb2-element' );
				}
			} );

			inputFrom.on( 'change', function() {
				inputTo.datepicker( 'option', 'minDate', inputFrom.val() );
			} );

			inputTo.on( 'change', function() {
				inputFrom.datepicker( 'option', 'maxDate', inputTo.val() );
			} );
		}
	};

	plugin.initCompetitionCloneModaal = function() {

		var cloneLink   = $c.body.find( '.anwp-fl-competition-clone-action' );

		var activeData = {
			link: false,
			process: false
		};

		if ( $c.body.find( '#anwp-fl-competition-clone-modaal' ).length && cloneLink.length ) {

			cloneLink.modaal(
				{
					content_source: '#anwp-fl-competition-clone-modaal',
					custom_class: 'anwpfl-shortcode-modal',
					hide_close: true,
					animation: 'none'
				}
			);

			cloneLink.on( 'click', function( e ) {
				e.preventDefault();
				activeData.link = $( this );
				activeData.link.modaal( 'open' );
			} );

			$( '#anwp-fl-competition-clone-modaal__cancel' ).on( 'click', function( e ) {
				e.preventDefault();
				activeData.link.modaal( 'close' );
			} );

			$( '#anwp-fl-competition-clone-modaal__clone' ).on( 'click', function( e ) {

				if ( activeData.process ) {
					return false;
				}

				activeData.process = true;
				e.preventDefault();

				var $this = $( this );
				$this.next( '.spinner' ).addClass( 'is-active' );

				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'fl_clone_competition',
						nonce: anwpflGlobals.ajaxNonce,
						competition_id: activeData.link.data( 'competition-id' ),
						season_id: $c.body.find( '#anwp-fl-clone-season-id' ).val()
					}
				} ).done( function( response ) {
					if ( response.success ) {
						location.href = response.data.link;
					} else {
						location.reload();
					}

					$this.next( '.spinner' ).removeClass( 'is-active' );
				} );
			} );
		}
	};

	$( plugin.init );
}( window, document, jQuery, window.AnWPFootballLeagues ) );
