/**
 * AnWP Football Leagues
 * https://anwp.pro
 *
 * Licensed under the GPLv2+ license.
 */

window.AnWPFootballLeaguesGlobal = window.AnWPFootballLeaguesGlobal || {};

( function( window, document, $, plugin ) {

	'use strict';

	var $c = {};

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
	};

	plugin.cache = function() {
		$c.window   = $( window );
		$c.body     = $( document.body );
		$c.document = $( document );

		$c.searchData = {
			context: '',
			s: ''
		};

		$c.activeLink   = null;
		$c.singleSelect = true;

		$c.xhr = null;

		$c.selectorInElementorInitialized = false;
	};

	plugin.bindEvents = function() {

		if ( document.readyState !== 'loading' ) {
			plugin.onPageReady();
		} else {
			document.addEventListener( 'DOMContentLoaded', plugin.onPageReady );
		}

		$c.body.on( 'anwp-fl-admin-content-updated', plugin.initSelectorModaal );

		$c.document.on( 'widget-added', plugin.initSelectorModaal );
		$c.document.on( 'widget-updated', plugin.initSelectorModaal );

		if ( 'undefined' !== typeof elementor ) {

			// ToDo - improve - https://github.com/elementor/elementor/issues/1886
			$c.body.on( 'click', '.anwp-fl-selector', function( e ) {

				var $this = $( this );

				if ( ! $this.closest( '#elementor-controls' ).length ) {
					return false;
				}

				if ( ! $c.selectorInElementorInitialized ) {

					$c.btnCancel.on( 'click', function( env ) {
						env.preventDefault();
						$c.activeLink.modaal( 'close' );
					} );

					$c.resultContext.on( 'click', '.anwp-fl-selector-action', function( env ) {
						env.preventDefault();
						plugin.addSelected( $( this ).closest( 'tr' ).data( 'id' ), $( this ).closest( 'tr' ).data( 'name' ) );
					} );

					$c.selectedItems.on( 'click', '.anwp-fl-selector-action-no', function( env ) {
						env.preventDefault();
						$( this ).closest( '.anwp-fl-selector-modaal__selected-item' ).remove();
					} );

					$c.btnInsert.on( 'click', function( env ) {
						env.preventDefault();

						var output = [];

						$c.selectedItems.find( '.anwp-fl-selector-modaal__selected-item' ).each( function() {
							output.push( $( this ).find( '.anwp-fl-selector-action-no' ).data( 'id' ) );
						} );

						$c.activeLink.modaal( 'close' );
						$c.activeLink.prev( 'input' ).val( output.join( ',' ) );
						$c.activeLink.prev( 'input' ).trigger( 'change' );
					} );

					$c.searchInput.on( 'keyup', _.debounce( function() {
						plugin.sendSearchRequest();
					}, 500 ) );

					$c.searchStages.on( 'change', plugin.sendSearchRequest );

					$c.selectorInElementorInitialized = true;
				}

				// Initialize modaal
				$this.modaal(
					{
						content_source: '#anwp-fl-selector-modaal',
						custom_class: 'anwpfl-shortcode-modal anwp-fl-selector-modal wp-core-ui',
						hide_close: true,
						animation: 'none',
						before_close: plugin.clearSelector
					}
				);

				$c.activeLink = $this;
				$c.activeLink.modaal( 'open' );
				$c.singleSelect = $c.activeLink.data( 'single' ) === 'yes';
				plugin.initializeSelectorContent();

				e.preventDefault();
			} );
		}
	};

	plugin.onPageReady = function() {

		if ( typeof anwpflGlobals !== 'undefined' ) {
			$c.body.append( anwpflGlobals.selectorHtml );
		}

		$c.searchBar      = $c.body.find( '#anwp-fl-selector-modaal__search-bar' );
		$c.searchSpinner  = $c.body.find( '#anwp-fl-selector-modaal__search-spinner' );
		$c.initialSpinner = $c.body.find( '#anwp-fl-selector-modaal__initial-spinner' );
		$c.searchInput    = $c.body.find( '#anwp-fl-selector-modaal__search' );
		$c.searchStages   = $c.body.find( '#anwp-fl-selector-modaal__stages' );
		$c.headerContext  = $c.body.find( '#anwp-fl-selector-modaal__header-context' );
		$c.resultContext  = $c.body.find( '#anwp-fl-selector-modaal__content' );
		$c.selectedItems  = $c.body.find( '#anwp-fl-selector-modaal__selected' );
		$c.btnCancel      = $c.body.find( '#anwp-fl-selector-modaal__cancel' );
		$c.btnInsert      = $c.body.find( '#anwp-fl-selector-modaal__insert' );

		plugin.initSelectorModaal();
		plugin.initDatepicker();
	};

	plugin.initDatepicker = function() {
		var pickers = $c.body.find( 'input.anwp-fl-admin-datepicker' );

		if ( pickers.length && typeof jQuery.datepicker !== 'undefined' ) {
			pickers.each( function() {
				$( this ).datepicker( {
					dateFormat: 'yy-mm-dd',
					changeMonth: true,
					changeYear: true,
					beforeShow: function( input, inst ) {
						inst.dpDiv.addClass( 'cmb2-element' );
					}
				} );
			} );
		}
	};

	plugin.initSelectorModaal = function() {

		// Check modaal placeholder exists
		if ( ! $c.body.find( '#anwp-fl-selector-modaal' ).length || ! $c.body.find( '.anwp-fl-selector' ).length ) {
			return false;
		}

		var modalOpenLink = $c.body.find( '.anwp-fl-selector' );

		// Initialize modaal
		modalOpenLink.modaal(
			{
				content_source: '#anwp-fl-selector-modaal',
				custom_class: 'anwpfl-shortcode-modal anwp-fl-selector-modal',
				hide_close: true,
				animation: 'none',
				before_close: plugin.clearSelector
			}
		);

		modalOpenLink.on( 'click', function( e ) {
			e.preventDefault();

			$c.activeLink = $( this );
			$c.activeLink.modaal( 'open' );

			$c.singleSelect = $c.activeLink.data( 'single' ) === 'yes';

			plugin.initializeSelectorContent();
		} );

		$c.btnCancel.on( 'click', function( e ) {
			e.preventDefault();
			$c.activeLink.modaal( 'close' );
		} );

		$c.resultContext.on( 'click', '.anwp-fl-selector-action', function( e ) {
			e.preventDefault();
			plugin.addSelected( $( this ).closest( 'tr' ).data( 'id' ), $( this ).closest( 'tr' ).data( 'name' ) );
		} );

		$c.selectedItems.on( 'click', '.anwp-fl-selector-action-no', function( e ) {
			e.preventDefault();
			$( this ).closest( '.anwp-fl-selector-modaal__selected-item' ).remove();
		} );

		$c.btnInsert.on( 'click', function( e ) {
			e.preventDefault();

			var output = [];

			$c.selectedItems.find( '.anwp-fl-selector-modaal__selected-item' ).each( function() {
				output.push( $( this ).find( '.anwp-fl-selector-action-no' ).data( 'id' ) );
			} );

			$c.activeLink.modaal( 'close' );
			$c.activeLink.prev( 'input' ).val( output.join( ',' ) );
			$c.activeLink.prev( 'input' ).trigger( 'change' );
		} );

		$c.searchInput.on( 'keyup', _.debounce( function() {
			plugin.sendSearchRequest();
		}, 500 ) );

		$c.searchStages.on( 'change', plugin.sendSearchRequest );
	};

	plugin.addSelected = function( id, name ) {

		if ( $c.selectedItems.find( '[data-id="' + id + '"]' ).length ) {
			return false;
		}

		var appendHTML = '<div class="anwp-fl-selector-modaal__selected-item"><button type="button" class="button button-small anwp-fl-selector-action-no" data-id="' + id + '"><span class="dashicons dashicons-no"></span></button><span>' + name + '</span></div>';

		if ( $c.singleSelect ) {
			$c.selectedItems.html( appendHTML );
		} else {
			$c.selectedItems.append( appendHTML );
		}
	};

	plugin.clearSelector = function() {
		$c.searchBar.find( '.anwp-selector-select2--active' ).val( '' );
		$c.searchBar.find( '.anwp-selector-select2--active' ).select2( 'destroy' );
		$c.searchBar.find( '.anwp-selector-select2--active' ).removeClass( 'anwp-selector-select2--active' );

		$c.searchBar.find( '.anwp-fl-selector-modaal__bar-group' ).addClass( 'd-none' );
	};

	plugin.initializeSelectorContent = function() {

		$c.searchData.context = $c.activeLink.data( 'context' );
		$c.searchData.s       = '';

		$c.initialSpinner.addClass( 'is-active' );

		// Load Initial Values
		if ( $c.activeLink.prev( 'input' ).val() ) {
			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'anwp_fl_selector_initial',
					initial: $c.activeLink.prev( 'input' ).val(),
					nonce: anwpflGlobals.ajaxNonce,
					data_context: $c.searchData.context
				}
			} ).done( function( response ) {
				if ( response.success && response.data.items ) {
					_.each( response.data.items, function( pp ) {
						plugin.addSelected( pp.id, pp.name );
					} );
				}
			} ).always( function() {
				$c.initialSpinner.removeClass( 'is-active' );
			} );
		} else {
			$c.initialSpinner.removeClass( 'is-active' );
		}

		// Update form
		$c.headerContext.html( $c.searchData.context );
		$c.resultContext.html( '' );
		$c.selectedItems.html( '' );
		$c.searchInput.val( '' );

		// Init Search Bar options
		$c.searchBar.find( '.anwp-fl-selector-modaal__bar-group' ).each( function() {
			var $this = $( this );

			if ( $this.hasClass( 'anwp-fl-selector-modaal__bar-group--' + $c.searchData.context ) ) {

				var $select = $this.find( 'select.anwp-selector-select2' );

				if ( $select.length ) {
					if ( anwpflGlobals && anwpflGlobals[ $select.attr( 'name' ) ] && anwpflGlobals[ $select.attr( 'name' ) ].length ) {

						var select2Options = {
							width: '170px',
							placeholder: {
								id: '',
								placeholder: '- select -'
							},
							allowClear: true,
							dropdownCssClass: 'anwpfl-shortcode-modal__select2-dropdown'
						};

						// Show select el
						$this.removeClass( 'd-none' );

						// Add Select2 active class
						$select.addClass( 'anwp-selector-select2--active' );

						if ( 'yes' !== $select.data( 'anwp-s2-initialized' ) ) {
							select2Options.data = anwpflGlobals[ $select.attr( 'name' ) ];
							$select.data( 'anwp-s2-initialized', 'yes' );
						}

						// Init Select2
						$select.select2( select2Options );

						$select.on( 'change.select2', function() {
							plugin.sendSearchRequest();
						} );

						$select.on( 'select2:clear', function() {
							$( this ).on( 'select2:opening.cancelOpen', function( env ) {
								env.preventDefault();
								$( this ).off( 'select2:opening.cancelOpen' );
							} );
						} );

					}
				} else {
					$this.removeClass( 'd-none' );
				}
			}
		} );

		plugin.sendSearchRequest();
	};

	plugin.sendSearchRequest = function() {

		if ( $c.xhr && $c.xhr.readyState !== 4 ) {
			$c.xhr.abort();
		}

		$c.searchSpinner.addClass( 'is-active' );
		$c.resultContext.addClass( 'anwp-search-is-active' );
		$c.resultContext.html( '' );

		// Search Data
		$c.searchData.s         = $c.searchInput.val();
		$c.searchData.club      = $c.searchBar.find( '#anwp-fl-selector-modaal__search-club' ).val();
		$c.searchData.club_home = $c.searchBar.find( '#anwp-fl-selector-modaal__search-club-home' ).val();
		$c.searchData.club_away = $c.searchBar.find( '#anwp-fl-selector-modaal__search-club-away' ).val();
		$c.searchData.season    = $c.searchBar.find( '#anwp-fl-selector-modaal__search-season' ).val();
		$c.searchData.country   = $c.searchBar.find( '#anwp-fl-selector-modaal__search-country' ).val();
		$c.searchData.league    = $c.searchBar.find( '#anwp-fl-selector-modaal__search-league' ).val();
		$c.searchData.stages    = $c.searchBar.find( '#anwp-fl-selector-modaal__stages' ).prop( 'checked' ) ? 'yes' : 'no';

		$c.xhr = $.ajax( {
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'anwp_fl_selector_data',
				nonce: anwpflGlobals.ajaxNonce,
				search_data: $c.searchData
			}
		} ).done( function( response ) {
			if ( response.success ) {
				$c.resultContext.html( response.data.html );
			}
		} ).always( function() {
			$c.searchSpinner.removeClass( 'is-active' );
			$c.resultContext.removeClass( 'anwp-search-is-active' );
		} );
	};

	$( plugin.init );
}( window, document, jQuery, window.AnWPFootballLeaguesGlobal ) );
