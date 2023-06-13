/**
 * AnWP Football Leagues
 * https://anwp.pro
 *
 * Licensed under the GPLv2+ license.
 */

/* global getUserSetting, setUserSetting */

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
		plugin.initCollapseMenuClick();
		plugin.initMatchListHelper();
		plugin.tableInputNavigation();
		plugin.initTextSearch();
		plugin.initOptionTabs();
		plugin.initDependentOptions();
		plugin.initCompetitionCloneModaal();

		var metaboxNav = $( '#anwp-fl-metabox-page-nav' );

		if ( 'undefined' !== typeof Gumshoe && metaboxNav.length ) {
			new Gumshoe( '#anwp-fl-metabox-page-nav a', {

				// Active classes
				navClass: 'anwp-fl-metabox-page-nav--active', // applied to the nav list item
				contentClass: 'anwp-scroll-content--active', // applied to the content

				// Nested navigation
				nested: false, // if true, add classes to parents of active link
				nestedClass: '', // applied to the parent items

				// Offset & reflow
				offset: 60, // how far from the top of the page to activate a content area
				reflow: true, // if true, listen for reflows

				// Event support
				events: false // if true, emit custom events
			} );
		}

		if ( 'undefined' !== typeof SmoothScroll && metaboxNav.length ) {
			new SmoothScroll( '.anwp-fl-smooth-scroll', {
				speed: 300,
				speedAsDuration: true,
				offset: 50
			} );
		}
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
					btnPublish.trigger( 'click' );
				}
			} );
		}

		var btnClickNew = $( '#anwp-fl-publish-click-proxy' );

		if ( btnClickNew.length ) {
			btnClickNew.on( 'click', function( e ) {
				e.preventDefault();

				if ( btnClickNew.prop( 'disabled' ) ) {
					return false;
				}

				btnClickNew.prop( 'disabled', true );
				btnClickNew.find( '.spinner' ).addClass( 'is-active' );

				if ( btnPublish.length ) {
					btnPublish.trigger( 'click' );
				}
			} );
		}
	};

	plugin.initCollapseMenuClick = function() {
		var btnCollapse   = $( '.anwp-fl-collapse-menu' );

		if ( btnCollapse.length ) {
			btnCollapse.on( 'click', function( e ) {
				e.preventDefault();

				setUserSetting( 'anwp-fl-collapsed-menu', btnCollapse.closest( '.anwp-fl-menu-wrapper' ).hasClass( 'anwp-fl-collapsed-menu' ) ? '' : 'yes' );
				btnCollapse.closest( '.anwp-fl-menu-wrapper' ).toggleClass( 'anwp-fl-collapsed-menu' );
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
