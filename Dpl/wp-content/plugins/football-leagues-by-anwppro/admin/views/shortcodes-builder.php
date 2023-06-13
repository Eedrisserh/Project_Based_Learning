<?php
/**
 * Shortcodes page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.10.8
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all core shortcode options.
 *
 * @param array $data Options
 *
 * @since 0.12.7
 */
$available_core_shortcodes = apply_filters( 'anwpfl/shortcode/get_shortcode_options', [] );

if ( ! empty( $available_core_shortcodes ) && is_array( $available_core_shortcodes ) ) {
	asort( $available_core_shortcodes );
}
?>
<div class="anwp-b-wrap">
	<div class="inside p-3" style="max-width: 800px;">
		<h1 class="mb-4"><?php echo esc_html__( 'Shortcode Builder', 'anwp-football-leagues' ); ?></h1>

		<div id="anwp-shortcode-builder__header" class="d-flex align-items-center">
			<label for="anwp-shortcode-builder__selector"><?php echo esc_html__( 'Shortcode', 'anwp-football-leagues' ); ?></label>
			<select id="anwp-shortcode-builder__selector" class="mx-2">
				<option value="">- <?php echo esc_html__( 'select', 'anwp-football-leagues' ); ?> -</option>

				<?php foreach ( $available_core_shortcodes as $shortcode_slug => $shortcode_name ) : ?>
					<option value="<?php echo esc_attr( $shortcode_slug ); ?>"><?php echo esc_html( $shortcode_name ); ?></option>
					<?php
				endforeach;

				/**
				 * Hook: anwpfl/shortcodes/selector_bottom
				 *
				 * @since 0.10.8
				 */
				do_action( 'anwpfl/shortcodes/selector_bottom' );
				?>
			</select>
			<span class="spinner"></span>
		</div>
		<div id="anwp-shortcode-builder__composed" class="d-none">
			<hr>
			<a class="font-weight-bold text-muted" href="#" id="anwp-shortcode-builder__copy"><?php echo esc_html__( 'copy code', 'anwp-football-leagues' ); ?></a>
			<pre class="p-2 bg-white border mt-1" style="white-space: normal;"></pre>
			<hr class="mb-0">
		</div>
		<div id="anwp-shortcode-builder__content" class="py-3"></div>
	</div>
</div>

<script>
	<?php
	$vars = [
		'nonce'               => wp_create_nonce( 'fl_shortcodes_nonce' ),
		'copied_to_clipboard' => esc_html__( 'Copied to Clipboard', 'anwp-football-leagues' ),
	];
	?>
	var _fl_shortcode_builder_l10n = <?php echo wp_json_encode( $vars ); ?>;

	window.FootballLeaguesShortcodeBuilder = window.FootballLeaguesShortcodeBuilder || {};

	( function( window, document, $, plugin ) {

		'use strict';

		var $c = {};

		plugin.init = function() {
			plugin.cache();
			plugin.initBuilderControls();
		};

		plugin.cache = function() {
			$c.window = $( window );
			$c.body   = $( document.body );
			$c.xhr    = null;
		};

		plugin.builtShortcode = function() {
			// Shortcode params
			var shortcodeTitle = $c.builderFormWrap.find( '.fl-shortcode-name' ).val();
			var shortcodeAttrs = [];

			$c.builderFormWrap.find( '.fl-shortcode-attr' ).each( function() {
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

			$c.builderComposed.text( '[' + shortcodeTitle + ' ' + shortcodeAttrs.join( ' ' ) + ']' );
		};

		plugin.initBuilderControls = function() {
			$c.builderSelector     = $c.body.find( '#anwp-shortcode-builder__selector' );
			$c.builderSpinner      = $c.body.find( '#anwp-shortcode-builder__header .spinner' );
			$c.builderFormWrap     = $c.body.find( '#anwp-shortcode-builder__content' );
			$c.builderComposedWrap = $c.body.find( '#anwp-shortcode-builder__composed' );
			$c.builderComposed     = $c.builderComposedWrap.find( 'pre' );

			$c.builderFormWrap.on( 'change input', '.fl-shortcode-attr', function( e ) {
				e.preventDefault();
				plugin.builtShortcode();
			} );

			$( '#anwp-shortcode-builder__copy' ).on( 'click', function( e ) {

				e.preventDefault();

				var $temp = $( '<input>' );
				$c.body.append( $temp );
				$temp.val( $c.builderComposed.text() ).select();
				document.execCommand( 'copy' );
				$temp.remove();

				toastr.success( _fl_shortcode_builder_l10n.copied_to_clipboard );
			} );

			$c.builderSelector.on( 'change', function() {
				var $this = $( this );

				$c.builderFormWrap.empty();
				$c.builderComposed.empty();
				$c.builderComposedWrap.addClass( 'd-none' );

				if ( ! $this.val() ) {
					return false;
				}

				$c.builderSpinner.addClass( 'is-active' );

				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'fl_shortcodes_modal_form',
						nonce: _fl_shortcode_builder_l10n.nonce,
						shortcode: $this.val()
					}
				} ).done( function( response ) {
					if ( response.success ) {
						$c.builderComposedWrap.removeClass( 'd-none' );
						$c.builderFormWrap.html( response.data.html );

						if ( $c.builderFormWrap.find( '.fl-shortcode-select2' ).length && $.fn.select2 ) {
							$c.builderFormWrap.find( '.fl-shortcode-select2' ).each(
								function() {
									$( this ).select2( {
										width: '25em'
									} );
								}
							);
						}

						plugin.builtShortcode();
						$c.body.trigger( 'anwp-fl-admin-content-updated' );
					}
				} ).always( function() {
					$c.builderSpinner.removeClass( 'is-active' );
				} );
			} );
		};

		$( plugin.init );
	}( window, document, jQuery, window.FootballLeaguesShortcodeBuilder ) );
</script>
