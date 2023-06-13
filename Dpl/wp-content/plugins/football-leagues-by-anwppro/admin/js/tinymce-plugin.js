/* eslint-disable camelcase */

// Initialize plugin object
window.FootballLeaguesShortcodeButton = window.FootballLeaguesShortcodeButton || {};

// Localization vars
_fl_shortcodes_l10n = _fl_shortcodes_l10n || {};

( function( window, document, $, plugin ) {

	'use strict';

	var $c = {};

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();

		tinymce.create( 'tinymce.plugins.football_leagues_button', {
			init: function( editor ) {
				editor.addButton( 'football_leagues', {
					title: _fl_shortcodes_l10n.football_leagues,
					icon: 'icon anwpfl-button-icon',
					classes: 'anwpfl-shortcode-modal-bump',
					onclick: function() {
						if ( $.fn.modaal && $c.body.hasClass( 'block-editor-page' ) ) {
							$c.modal.modaal( 'open' );
						}
					}
				} );
			},
			createControl: function() {
				return null;
			}
		} );

		tinymce.PluginManager.add( 'football_leagues_button', tinymce.plugins.football_leagues_button );
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body   = $( document.body );
		$c.xhr    = null;
	};

	plugin.bindEvents = function() {
		if ( document.readyState === 'complete' ) {
			plugin.onPageReady();
		} else {
			window.onload = plugin.onPageReady;
		}
	};

	plugin.onPageReady = function() {

		if ( ! $.fn.modaal ) {
			return;
		}

		var modalOptions = {};

		if ( $c.body.hasClass( 'block-editor-page' ) ) {

			$c.body.append( '<a href="#anwpfl-shortcode-modal" id="anwpfl-shortcode-modal-bump"></a><div id="anwpfl-shortcode-modal"></div>' );
			$c.modal        = $( '#anwpfl-shortcode-modal-bump' );
			$c.modalWrapper = $( '#anwpfl-shortcode-modal' );

			modalOptions = {
				custom_class: 'anwpfl-shortcode-modal',
				hide_close: true,
				animation: 'none',
				after_close: function() {
					tinymce.activeEditor.focus();
					$c.modalFormWrap.empty();
					$c.modalSelector.val( '' );
				}
			};
		} else {
			$c.body.append( '<div id="anwpfl-shortcode-modal"></div>' );
			$c.modal        = $( '.mce-anwpfl-shortcode-modal-bump' );
			$c.modalWrapper = $( '#anwpfl-shortcode-modal' );

			modalOptions = {
				content_source: '#anwpfl-shortcode-modal',
				custom_class: 'anwpfl-shortcode-modal',
				hide_close: true,
				animation: 'none',
				after_close: function() {
					tinymce.activeEditor.focus();
					$c.modalFormWrap.empty();
					$c.modalSelector.val( '' );
				}
			};
		}

		// Init
		$c.modal.modaal( modalOptions );

		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'fl_shortcodes_modal_structure',
				nonce: _fl_shortcodes_l10n.nonce
			}
		} ).done( function( response ) {
			if ( response.success ) {
				$c.modalWrapper.html( response.data.html );

				plugin.initModalControls();
			}
		} );
	};

	plugin.initModalControls = function() {
		$c.modalSelector  = $c.modalWrapper.find( '#anwpfl-shortcode-modal__selector' );
		$c.modalSpinner   = $c.modalWrapper.find( '.anwpfl-shortcode-modal__header .spinner' );
		$c.modalBtnInsert = $c.modalWrapper.find( '#anwpfl-shortcode-modal__insert' );
		$c.modalBtnClose  = $c.modalWrapper.find( '#anwpfl-shortcode-modal__cancel' );
		$c.modalFormWrap  = $c.modalWrapper.find( '.anwpfl-shortcode-modal__content' );

		$c.modalBtnClose.on( 'click', function( e ) {
			e.preventDefault();

			$c.modal.modaal( 'close' );
		} );

		$c.modalBtnInsert.on( 'click', function( e ) {

			e.preventDefault();

			if ( $c.modalBtnInsert.prop( 'disabled' ) ) {
				return false;
			}

			// Shortcode params
			var shortcodeTitle = $c.modalFormWrap.find( '.fl-shortcode-name' ).val();
			var shortcodeAttrs = [];

			$c.modalFormWrap.find( '.fl-shortcode-attr' ).each( function() {
				var $thisAttr = $( this );

				switch ( $thisAttr.data( 'fl-type' ) ) {
					case 'text':
					case 'select':
						shortcodeAttrs.push( $thisAttr.attr( 'name' ) + '="' + $thisAttr.val() + '"' );
						break;

					case 'select2':
						if ( $thisAttr.val() && _.isArray( $thisAttr.val() ) ) {
							shortcodeAttrs.push( $thisAttr.attr( 'name' ) + '="' + $thisAttr.val().toString() + '"' );
						} else if ( $thisAttr.val() ) {
							shortcodeAttrs.push( $thisAttr.attr( 'name' ) + '="' + $thisAttr.val() + '"' );
						} else {
							shortcodeAttrs.push( $thisAttr.attr( 'name' ) + '=""' );
						}
						break;

					default:
						shortcodeAttrs.push( $thisAttr.attr( 'name' ) + '="' + $thisAttr.val() + '"' );
				}
			} );

			tinymce.activeEditor.insertContent( '[' + shortcodeTitle + ' ' + shortcodeAttrs.join( ' ' ) + ']' );
			$c.modal.modaal( 'close' );
		} );

		$c.modalSelector.on( 'change', function() {
			var $this = $( this );

			$c.modalFormWrap.empty();
			$c.modalBtnInsert.prop( 'disabled', true );

			if ( ! $this.val() ) {
				return false;
			}

			$c.modalSpinner.addClass( 'is-active' );

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'fl_shortcodes_modal_form',
					nonce: _fl_shortcodes_l10n.nonce,
					shortcode: $this.val()
				}
			} ).done( function( response ) {
				if ( response.success ) {
					$c.modalFormWrap.html( response.data.html );
					$c.modalBtnInsert.prop( 'disabled', false );

					if ( $c.modalFormWrap.find( '.fl-shortcode-select2' ).length && $.fn.select2 ) {
						$c.modalFormWrap.find( '.fl-shortcode-select2' ).each(
							function() {
								$( this ).select2( {
									dropdownParent: $( '.anwpfl-shortcode-modal .modaal-content-container' ),
									closeOnSelect: true,
									dropdownCssClass: 'anwp-shortcode-select2-dropdown',
									width: '100%'
								} ).on( 'select2:select', function( e ) {
									var id     = e.params.data.id;
									var option = $( e.target ).children( '[value=' + id + ']' );
									option.detach();
									$( e.target ).append( option ).trigger( 'change' );
								} );
							}
						);
					}

					$c.body.trigger( 'anwp-fl-admin-content-updated' );
				}
			} ).always( function() {
				$c.modalSpinner.removeClass( 'is-active' );
			} );
		} );

		$c.modalBtnInsert.prop( 'disabled', true );
	};

	plugin.init();
}( window, document, jQuery, window.FootballLeaguesShortcodeButton ) );
