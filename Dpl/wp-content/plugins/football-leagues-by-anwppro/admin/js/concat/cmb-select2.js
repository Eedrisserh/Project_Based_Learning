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
