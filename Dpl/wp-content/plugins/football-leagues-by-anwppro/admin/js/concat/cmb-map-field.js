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
