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
