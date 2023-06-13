( function( api ) {
	// Extends our custom "sports-club-lite" section.
	api.sectionConstructor['sports-club-lite'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );
} )( wp.customize );