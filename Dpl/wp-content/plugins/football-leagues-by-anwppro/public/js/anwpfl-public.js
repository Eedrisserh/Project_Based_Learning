/**
 * AnWP Football Leagues
 * https://anwp.pro
 *
 * Flip Countdown
 * from - https://github.com/hilios/jQuery.countdown/blob/gh-pages/_includes/main-example.html
 *
 */

( function( $ ) {
	'use strict';
	$( function() {

		$( '.anwp-countdown-simple' ).each( function() {
			var $countdown = $( this );

			var kickoffTime = new Date( Date.now() + $countdown.data( 'kickoff-diff' ) );
			kickoffTime.setSeconds( 0 );

			$countdown.countdown( kickoffTime, function( event ) {
				$( this ).html( event.strftime( '%-D <span class="anwp-countdown-simple-label">' + anwpfl_l10n.days + '</span> %H:%M:%S' ) );
			} );
		} );

		$( '.anwp-match-flip-countdown-container' ).each( function() {
			var $countdown = $( this );
			var labels     = [ anwpfl_l10n.weeks, anwpfl_l10n.days, anwpfl_l10n.hours, anwpfl_l10n.minutes, anwpfl_l10n.seconds ];
			var template   = _.template( '<div class="time <%= label %>"><span class="count curr top"><%= curr %></span><span class="count next top"><%= next %></span><span class="count next bottom"><%= next %></span><span class="count curr bottom"><%= curr %></span><span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span></div>' );
			var currDate   = '00:00:00:00:00';
			var nextDate   = '00:00:00:00:00';
			var parser     = /([0-9]{2})/gi;

			var kickoffTime   = new Date( Date.now() + $countdown.data( 'kickoff-diff' ) );
			kickoffTime.setSeconds( 0 );

			// Parse countdown string to an object
			function strfobj( str ) {
				var parsed = str.match( parser );
				var obj    = {};

				labels.forEach( function( label, i ) {
					obj[ label ] = parsed[ i ];
				} );

				return obj;
			}

			// Return the time components that diffs
			function diff( obj1, obj2 ) {
				var diff = [];
				labels.forEach( function( key ) {
					if ( obj1[ key ] !== obj2[ key ] ) {
						diff.push( key );
					}
				} );
				return diff;
			}

			// Build the layout
			var initData = strfobj( currDate );
			labels.forEach( function( label, i ) {
				$countdown.append( template( {
					curr: initData[ label ],
					next: initData[ label ],
					label: label
				} ) );
			} );

			// Starts the countdown
			$countdown.countdown( kickoffTime, function( event ) {
				var newDate = event.strftime( '%w:%d:%H:%M:%S' );
				var data;

				if ( newDate !== nextDate ) {
					currDate = nextDate;
					nextDate = newDate;

					// Setup the data
					data = {
						'curr': strfobj( currDate ),
						'next': strfobj( nextDate )
					};

					// Apply the new values to each node that changed
					diff( data.curr, data.next ).forEach( function( label ) {
						var selector = '.%s'.replace( /%s/, label );
						var $node    = $countdown.find( selector );

						// Update the node
						$node.removeClass( 'flip' );
						$node.find( '.curr' ).text( data.curr[ label ] );
						$node.find( '.next' ).text( data.next[ label ] );

						// Wait for a repaint to then flip
						_.delay( function( $node ) {
							$node.addClass( 'flip' );
						}, 50, $node );
					} );
				}
			} );
		} );

	} );
}( jQuery ) );

/**
 * jquery.detectSwipe v2.1.3
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch, iPad and Android
 * http://github.com/marcandre/detect_swipe
 * Based on touchwipe by Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {

  $.detectSwipe = {
    version: '2.1.2',
    enabled: 'ontouchstart' in document.documentElement,
    preventDefault: true,
    threshold: 20
  };

  var startX,
    startY,
    isMoving = false;

  function onTouchEnd() {
    this.removeEventListener('touchmove', onTouchMove);
    this.removeEventListener('touchend', onTouchEnd);
    isMoving = false;
  }

  function onTouchMove(e) {
    if ($.detectSwipe.preventDefault) { e.preventDefault(); }
    if(isMoving) {
      var x = e.touches[0].pageX;
      var y = e.touches[0].pageY;
      var dx = startX - x;
      var dy = startY - y;
      var dir;
      var ratio = window.devicePixelRatio || 1;
      if(Math.abs(dx) * ratio >= $.detectSwipe.threshold) {
        dir = dx > 0 ? 'left' : 'right'
      }
      else if(Math.abs(dy) * ratio >= $.detectSwipe.threshold) {
        dir = dy > 0 ? 'up' : 'down'
      }
      if(dir) {
        onTouchEnd.call(this);
        $(this).trigger('swipe', dir).trigger('swipe' + dir);
      }
    }
  }

  function onTouchStart(e) {
    if (e.touches.length == 1) {
      startX = e.touches[0].pageX;
      startY = e.touches[0].pageY;
      isMoving = true;
      this.addEventListener('touchmove', onTouchMove, false);
      this.addEventListener('touchend', onTouchEnd, false);
    }
  }

  function setup() {
    this.addEventListener && this.addEventListener('touchstart', onTouchStart, false);
  }

  function teardown() {
    this.removeEventListener('touchstart', onTouchStart);
  }

  $.event.special.swipe = { setup: setup };

  $.each(['left', 'up', 'down', 'right'], function () {
    $.event.special['swipe' + this] = { setup: function(){
      $(this).on('swipe', $.noop);
    } };
  });
}));

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
