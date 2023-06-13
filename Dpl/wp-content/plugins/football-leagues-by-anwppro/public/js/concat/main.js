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
			plugin.windowLoaded();
		} else {
			document.addEventListener( 'DOMContentLoaded', plugin.windowLoaded );
		}
	};

	plugin.windowLoaded = function() {
		plugin.addBodyClass();
		plugin.initStadiumMap();
		plugin.initTooltips();
		plugin.initTabs();
		plugin.initResponsiveBlocks();
		plugin.initStadiumJustifiedGallery();
		plugin.initVideoPlayers();
		plugin.initSeasonDropdown();
		plugin.initCompetitionList();
	};

	plugin.initSeasonDropdown = function() {

		if ( ! $c.body.find( '.anwp-season-dropdown' ).length ) {
			return false;
		}

		$c.body.find( '.anwp-season-dropdown' ).on( 'change', function() {
			window.location = $( this ).find( 'option:selected' ).data( 'href' );
		} );
	};

	// Add a class to <body>.
	plugin.addBodyClass = function() {
		$c.body.addClass( 'anwpfl-ready' );
	};

	plugin.initTooltips = function() {
		tippy( '[data-toggle="anwp-tooltip"]', {
			arrow: true,
			zIndex: 100600
		} );
	};

	plugin.initCompetitionList = function() {

		if ( ! $c.body.find( '.competition-list__country_collapsed' ).length ) {
			return false;
		}

		$c.body.find( '.competition-list' ).on( 'click', '.competition-list__country_collapsed', function( e ) {

			var $this = $( this );
			e.preventDefault();

			if ( $this.hasClass( 'list__country_collapsed--active' ) ) {
				$this.removeClass( 'list__country_collapsed--active' );
				$this.siblings( '.competition-list__competition[data-anwp-country="' + $this.data( 'anwp-country' ) + '"]' ).addClass( 'd-none' );
			} else {
				$this.addClass( 'list__country_collapsed--active' );
				$this.siblings( '.competition-list__competition[data-anwp-country="' + $this.data( 'anwp-country' ) + '"]' ).removeClass( 'd-none' );
			}
		} );
	};

	plugin.initTabs = function() {

		if ( ! $c.body.find( '.anwp-fl-tabs' ).length ) {
			return false;
		}

		$c.body.find( '.anwp-fl-tabs' ).on( 'click', '.anwp-fl-tabs__item', function( e ) {

			var $this  = $( this );
			var target = $( $this.data( 'target' ) );

			e.preventDefault();

			if ( $this.hasClass( 'anwp-active active' ) ) {
				return false;
			}

			$this.addClass( 'anwp-active active' ).siblings( '.anwp-fl-tabs__item' ).removeClass( 'anwp-active active ' );
			target.removeClass( 'd-none' ).siblings( '.anwp-fl-tab__content' ).addClass( 'd-none' );
		} );

		$c.body.find( '.anwp-fl-tabs .anwp-fl-tabs__item:first-child' ).trigger( 'click' );
	};

	plugin.initResponsiveBlocks = function() {
		if ( $.fn.overlayScrollbars && $c.body.find( '.anwp-scroll-responsive' ).length ) {
			$( '.anwp-scroll-responsive' ).overlayScrollbars( {
				className: 'os-theme-thick-dark',
				scrollbars: {
					visibility: 'auto',
					autoHide: 'never',
					dragScrolling: true,
					clickScrolling: true,
					touchSupport: true
				},
				overflowBehavior: {
					x: 'scroll',
					y: 'hidden'
				}
			} );
		}
	};

	plugin.initVideoPlayers = function() {
		if ( 'undefined' !== typeof Plyr && $c.body.find( '.anwp-video-player' ).length ) {
			Plyr.setup( '.anwp-video-player', {
				youtube: {
					noCookie: true, // Whether to use an alternative version of YouTube without cookies
					rel: 0, // No related vids
					showinfo: 0, // Hide info
					iv_load_policy: 3, // Hide annotations
					modestbranding: 1 // Hide logos as much as possible (they still show one in the corner when paused)
				}
			} );
		}
	};

	plugin.initStadiumJustifiedGallery = function() {
		if ( $c.body.find( '.anwp-justified-gallery' ).length && $.fn.justifiedGallery ) {
			var $target = $c.body.find( '.anwp-justified-gallery' );

			if ( $target.closest( '.anwp-navbar__content' ).length && ! $target.closest( '.anwp-navbar__content' ).is( ':first-child' ) ) {
				$target.addClass( 'anwp-justified-gallery--refresh-required' );
			} else {
				$target.justifiedGallery( {
					rowHeight: 150,
					lastRow: 'nojustify',
					margins: 3
				} );
			}
		}
	};

	plugin.initStadiumMap = function() {
		if ( $c.body.find( '#map--stadium' ).length && 'undefined' !== typeof google && 'undefined' !== typeof google.maps ) {

			var map;
			var mapWrapper = document.getElementById( 'map--stadium' );

			var initialZoom = 16;
			var position    = {lat: parseFloat( $( mapWrapper ).data( 'lat' ) ), lng: parseFloat( $( mapWrapper ).data( 'longitude' ) )};

			// Create new map
			map = new google.maps.Map( mapWrapper, {
				center: position,
				zoom: initialZoom,
				mapTypeId: 'satellite'
			} );

			// Create new marker
			new google.maps.Marker( {
				position: position,
				draggable: false,
				map: map
			} );
		}
	};

	$( plugin.init );
}( window, document, jQuery, window.AnWPFootballLeagues ) );
