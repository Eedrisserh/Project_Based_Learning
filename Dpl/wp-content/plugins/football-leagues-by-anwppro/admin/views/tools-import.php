<?php
/**
 * Tools page for AnWP Football Leagues
 *
 * @link       https://anwp.pro
 * @since      0.8.2
 *
 * @package    AnWP_Football_Leagues
 * @subpackage AnWP_Football_Leagues/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-football-leagues' ) );
}

$custom_fields_players = AnWPFL_Options::get_value( 'player_custom_fields' );

/*
|--------------------------------------------------------------------
| Prepare player columns
|--------------------------------------------------------------------
*/
$columns_player = [
	[
		'slug'  => 'player_name',
		'title' => __( 'Player Name', 'anwp-football-leagues' ) . ' *',
		'attr'  => 'disabled checked',
	],
	[
		'slug'  => 'short_name',
		'title' => __( 'Short Name', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'full_name',
		'title' => __( 'Full Name', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'weight',
		'title' => __( 'Weight (kg)', 'anwp-football-leagues' ),
		'attr'  => 'checked',
	],
	[
		'slug'  => 'height',
		'title' => __( 'Height (cm)', 'anwp-football-leagues' ),
		'attr'  => 'checked',
	],
	[
		'slug'  => 'position',
		'title' => __( 'Position', 'anwp-football-leagues' ),
		'attr'  => 'checked',
	],
	[
		'slug'  => 'current_club',
		'title' => __( 'Current Club', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'national_team',
		'title' => __( 'National Team', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'place_of_birth',
		'title' => __( 'Place of Birth', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'date_of_birth',
		'title' => __( 'Date of Birth', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'date_of_death',
		'title' => __( 'Date of death', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'country_of_birth',
		'title' => __( 'Country of Birth', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'bio',
		'title' => __( 'Bio', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'nationality_1',
		'title' => __( 'Nationality', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'nationality_2',
		'title' => __( 'Nationality 2', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'custom_title_1',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #1',
	],
	[
		'slug'  => 'custom_value_1',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #1',
	],
	[
		'slug'  => 'custom_title_2',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #2',
	],
	[
		'slug'  => 'custom_value_2',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #2',
	],
	[
		'slug'  => 'custom_title_3',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #3',
	],
	[
		'slug'  => 'custom_value_3',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #3',
	],
	[
		'slug'  => 'player_id',
		'title' => __( 'Player ID', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'player_external_id',
		'title' => __( 'Player External ID', 'anwp-football-leagues' ) . ' **',
	],
];

if ( ! empty( $custom_fields_players ) && is_array( $custom_fields_players ) ) {
	foreach ( $custom_fields_players as $custom_field ) {

		$columns_player[] = [
			'slug'  => 'cf__' . esc_html( $custom_field ),
			'title' => 'Custom Field: ' . esc_html( $custom_field ),
		];
	}
}

/*
|--------------------------------------------------------------------
| Prepare referee columns
|--------------------------------------------------------------------
*/
$columns_referee = [
	[
		'slug'  => 'referee_name',
		'title' => __( 'Referee Name', 'anwp-football-leagues' ) . ' *',
		'attr'  => 'disabled checked',
	],
	[
		'slug'  => 'short_name',
		'title' => __( 'Short Name', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'job_title',
		'title' => __( 'Job Title', 'anwp-football-leagues' ),
		'attr'  => 'checked',
	],
	[
		'slug'  => 'place_of_birth',
		'title' => __( 'Place of Birth', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'date_of_birth',
		'title' => __( 'Date of Birth', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'nationality_1',
		'title' => __( 'Nationality', 'anwp-football-leagues' ) . ' #1',
	],
	[
		'slug'  => 'nationality_2',
		'title' => __( 'Nationality', 'anwp-football-leagues' ) . ' #2',
	],
];

/*
|--------------------------------------------------------------------
| Prepare club columns
|--------------------------------------------------------------------
*/
$columns_club = [
	[
		'slug'  => 'club_title',
		'title' => __( 'Club Title', 'anwp-football-leagues' ) . ' *',
		'attr'  => 'disabled checked',
	],
	[
		'slug'  => 'abbreviation',
		'title' => __( 'Abbreviation', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'city',
		'title' => __( 'City', 'anwp-football-leagues' ),
		'attr'  => 'checked',
	],
	[
		'slug'  => 'country',
		'title' => __( 'Country', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'address',
		'title' => __( 'Address', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'website',
		'title' => __( 'Website', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'founded',
		'title' => __( 'Founded', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'is_national_team',
		'title' => __( 'National Team', 'anwp-football-leagues' ),
	],
	[
		'slug'  => 'custom_title_1',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #1',
	],
	[
		'slug'  => 'custom_value_1',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #1',
	],
	[
		'slug'  => 'custom_title_2',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #2',
	],
	[
		'slug'  => 'custom_value_2',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #2',
	],
	[
		'slug'  => 'custom_title_3',
		'title' => __( 'Custom Field - Title', 'anwp-football-leagues' ) . ' #3',
	],
	[
		'slug'  => 'custom_value_3',
		'title' => __( 'Custom Field - Value', 'anwp-football-leagues' ) . ' #3',
	],
];
?>

<div class="alert alert-info my-3" role="alert">
	<div class="d-block mb-1">
		<?php echo esc_html__( 'Select import type. Then copy and paste data from your source into the table below.', 'anwp-football-leagues' ); ?>
	</div>
	<svg class="anwp-icon anwp-icon--s14 anwp-icon--octi"><use xlink:href="#icon-info"></use></svg>
	<a href="https://anwp.pro/football-leagues-documentation/batch-import-tool/" target="_blank"><?php echo esc_html__( 'more info', 'anwp-football-leagues' ); ?></a><br>
</div>

<div class="my-3">
	<select name="anwpfl-import-type" id="anwpfl-import-type-select">
		<option value="">- <?php echo esc_html__( 'select import type', 'anwp-football-leagues' ); ?> -</option>
		<option value="players"><?php echo esc_html__( 'players', 'anwp-football-leagues' ); ?></option>
		<option value="referees"><?php echo esc_html__( 'referees', 'anwp-football-leagues' ); ?></option>
		<option value="clubs"><?php echo esc_html__( 'clubs', 'anwp-football-leagues' ); ?></option>
	</select>
</div>

<div class="my-3 p-2 border anwpfl-batch-import-filter-wrapper d-none" data-type="players">
	<h5 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h5>

	<div class="anwp-overflow-x-auto">
		<div class="anwpfl-tools-sortable mt-2 d-flex ">

			<?php foreach ( $columns_player as $column_player ) : ?>
				<div class="my-1 mr-1 py-1 px-2 border border-secondary anwp-d-flex-not-important flex-column align-items-center">
					<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-drag-handler">
						<use xlink:href="#icon-grabber"></use>
					</svg>
					<div class="my-2" style="writing-mode: vertical-rl;">
						<?php echo esc_html( $column_player['title'] ); ?>
					</div>
					<label data-slug="<?php echo esc_attr( $column_player['slug'] ); ?>" class="mt-auto anwp-cursor-pointer">
						<input class="d-none" type="checkbox" <?php echo esc_attr( ! empty( $column_player['attr'] ) ? $column_player['attr'] : '' ); ?>>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-checkbox-icon--checked">
							<use xlink:href="#icon-eye"></use>
						</svg>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--feather anwp-checkbox-icon--unchecked">
							<use xlink:href="#icon-eye-off"></use>
						</svg>
					</label>
				</div>
			<?php endforeach; ?>

		</div>
	</div>

	<p>** Use "Player ID" or "Player External ID" to update existing players.</p>
	<ol>
		<li>"Player ID" has a higher priority. It is a WordPress Post ID value. If such a player exists in DB, data will be updated. If nothing found, a new player will be created.</li>
		<li>If you set "Player External ID", the import process will update a first player with such ID or create a new one if nothing found.</li>
		<li>If you set them both ( "Player ID" and "Player External ID" ). And the player with this "Player ID" doesn't exist in DB, the Import process will always create it.</li>
	</ol>
</div>

<div class="my-3 p-2 border anwpfl-batch-import-filter-wrapper d-none" data-type="referees">
	<h5 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h5>

	<div class="anwp-overflow-x-auto">
		<div class="anwpfl-tools-sortable mt-2 d-flex">

			<?php foreach ( $columns_referee as $column_referee ) : ?>
				<div class="my-1 mr-1 py-1 px-2 border border-secondary anwp-d-flex-not-important flex-column align-items-center">
					<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-drag-handler">
						<use xlink:href="#icon-grabber"></use>
					</svg>
					<div class="my-2" style="writing-mode: vertical-rl;">
						<?php echo esc_html( $column_referee['title'] ); ?>
					</div>
					<label data-slug="<?php echo esc_attr( $column_referee['slug'] ); ?>" class="mt-auto anwp-cursor-pointer">
						<input class="d-none" type="checkbox" <?php echo esc_attr( ! empty( $column_referee['attr'] ) ? $column_referee['attr'] : '' ); ?>>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-checkbox-icon--checked">
							<use xlink:href="#icon-eye"></use>
						</svg>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--feather anwp-checkbox-icon--unchecked">
							<use xlink:href="#icon-eye-off"></use>
						</svg>
					</label>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

<div class="my-3 p-2 border anwpfl-batch-import-filter-wrapper d-none" data-type="clubs">
	<h5 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h5>

	<div class="anwp-overflow-x-auto">
		<div class="anwpfl-tools-sortable mt-2 d-flex">
			<?php foreach ( $columns_club as $column_club ) : ?>
				<div class="my-1 mr-1 py-1 px-2 border border-secondary anwp-d-flex-not-important flex-column align-items-center">
					<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-drag-handler">
						<use xlink:href="#icon-grabber"></use>
					</svg>
					<div class="my-2" style="writing-mode: vertical-rl;">
						<?php echo esc_html( $column_club['title'] ); ?>
					</div>
					<label data-slug="<?php echo esc_attr( $column_club['slug'] ); ?>" class="mt-auto anwp-cursor-pointer">
						<input class="d-none" type="checkbox" <?php echo esc_attr( ! empty( $column_club['attr'] ) ? $column_club['attr'] : '' ); ?>>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--octi anwp-checkbox-icon--checked">
							<use xlink:href="#icon-eye"></use>
						</svg>
						<svg class="anwp-icon anwp-icon--s24 anwp-icon--feather anwp-checkbox-icon--unchecked">
							<use xlink:href="#icon-eye-off"></use>
						</svg>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div id="anwpfl-batch-import-table" class="invisible"></div>

<div class="anwpfl-batch-import-save-wrapper">
	<div class="anwpfl-batch-import-save-info mt-3"></div>
	<button id="anwpfl-batch-import-save-btn" type="button" class="button button-primary px-4 d-none">
		<?php echo esc_html__( 'Save Data', 'anwp-football-leagues' ); ?>
	</button>
	<img class="mx-2 anwp-request-spinner" src="<?php echo esc_url( admin_url() ); ?>images/loading.gif" style="width: 24px; height: 24px"/>
</div>

<?php
$import_options = anwp_football_leagues()->data->get_import_options();

$jexcel_player_config = [
	'player_name'        => [
		'type'  => 'text',
		'title' => 'Player Name',
		'width' => 120,
	],
	'short_name'         => [
		'type'  => 'text',
		'title' => 'Player Short Name',
		'width' => 120,
	],
	'full_name'          => [
		'type'  => 'text',
		'title' => 'Player Full Name',
		'width' => 150,
	],
	'weight'             => [
		'type'  => 'numeric',
		'title' => 'Weight',
	],
	'height'             => [
		'type'  => 'numeric',
		'title' => 'Height',
	],
	'position'           => [
		'type'         => 'dropdown',
		'title'        => 'Position',
		'autocomplete' => true,
		'width'        => 150,
		'source'       => $import_options['positions'],
	],
	'current_club'       => [
		'type'         => 'dropdown',
		'source'       => $import_options['clubs'],
		'autocomplete' => true,
		'title'        => 'Current Club',
	],
	'national_team'      => [
		'type'         => 'dropdown',
		'source'       => $import_options['clubs'],
		'autocomplete' => true,
		'title'        => 'National Team',
	],
	'place_of_birth'     => [
		'type'  => 'text',
		'title' => 'Place of Birth',
	],
	'date_of_birth'      => [
		'type'  => 'numeric',
		'title' => 'Date of Birth (YYYY-MM-DD)',
		'mask'  => 'yyyy-mm-dd',
	],
	'date_of_death'      => [
		'type'  => 'numeric',
		'title' => 'Date of Death (YYYY-MM-DD)',
		'mask'  => 'yyyy-mm-dd',
	],
	'bio'                => [
		'type'  => 'text',
		'title' => 'Bio',
	],
	'nationality_1'      => [
		'source'       => $import_options['countries'],
		'type'         => 'dropdown',
		'title'        => 'Nationality',
		'autocomplete' => true,
	],
	'country_of_birth'   => [
		'source'       => $import_options['countries'],
		'type'         => 'dropdown',
		'title'        => 'Country of Birth',
		'autocomplete' => true,
	],
	'nationality_2'      => [
		'type'         => 'dropdown',
		'source'       => $import_options['countries'],
		'autocomplete' => true,
		'title'        => 'Nationality 2',
	],
	'custom_title_1'     => [
		'type'  => 'text',
		'title' => 'Custom - title 1',
	],
	'custom_title_2'     => [
		'type'  => 'text',
		'title' => 'Custom - title 2',
	],
	'custom_title_3'     => [
		'type'  => 'text',
		'title' => 'Custom - title 3',
	],
	'custom_value_1'     => [
		'type'  => 'text',
		'title' => 'Custom - Value 1',
	],
	'custom_value_2'     => [
		'type'  => 'text',
		'title' => 'Custom - Value 2',
	],
	'custom_value_3'     => [
		'type'  => 'text',
		'title' => 'Custom - Value 3',
	],
	'player_id'          => [
		'type'  => 'numeric',
		'title' => 'Player ID',
	],
	'player_external_id' => [
		'type'  => 'numeric',
		'title' => 'Player External ID',
	],
];

if ( ! empty( $custom_fields_players ) && is_array( $custom_fields_players ) ) {

	foreach ( $custom_fields_players as $custom_field ) {
		$jexcel_player_config[ 'cf__' . $custom_field ] = [
			'type'  => 'text',
			'title' => $custom_field,
		];
	}
}
?>

<script>
	(function( $ ) {
		'use strict';

		$( function() {

			var $wrapper = $( '#anwpfl-import-wrapper' );
			var anwpImportOptions = <?php echo wp_json_encode( anwp_football_leagues()->data->get_import_options() ); ?>;

			if ( ! $( '#anwpfl-batch-import-table' ).length || typeof jexcel === 'undefined' ) {
				return;
			}

			var jExcelOptions = {
				data: [],
				allowToolbar: true,
				columnSorting: false,
				rowDrag: false,
				allowInsertRow: true,
				allowManualInsertRow: true,
				allowInsertColumn: false,
				allowManualInsertColumn: false,
				allowDeleteRow: false,
				allowDeletingAllRows: false,
				allowDeleteColumn: false,
				allowRenameColumn: false,
				defaultColWidth: '110px',
				rowResize: true,
				minDimensions: [ 1, 5 ],
				contextMenu: function() {
					return null;
				},
				columns: []
			};

			var container = document.getElementById( 'anwpfl-batch-import-table' );
			var tableData = jexcel( container, jExcelOptions );

			var importSelector = $( '#anwpfl-import-type-select' );
			var btnSave        = $( '#anwpfl-batch-import-save-btn' );
			var infoSave       = $wrapper.find( '.anwpfl-batch-import-save-info' );
			var activeRequest  = false;

			var columns = {
				players: <?php echo wp_json_encode( $jexcel_player_config ); ?>,
				referees: {
					'referee_name': {
						type: 'text',
						title: 'Referee Name',
						width: 120
					},
					'short_name': {
						type: 'text',
						title: 'Referee Short Name',
						width: 120
					},
					'job_title': {
						type: 'text',
						title: 'Job Title',
						width: 120
					},
					'place_of_birth': {
						type: 'text',
						title: 'Place of Birth'
					},
					'date_of_birth': {
						type: 'numeric',
						title: 'Date of Birth (YYYY-MM-DD)',
						mask: 'yyyy-mm-dd'
					},
					'nationality_1': {
						source: anwpImportOptions.countries,
						type: 'dropdown',
						title: 'Nationality',
						autocomplete: true
					},
					'nationality_2': {
						type: 'dropdown',
						source: anwpImportOptions.countries,
						autocomplete: true,
						title: 'Nationality 2',
					}
				},
				clubs: {
					'club_title': {
						type: 'text',
						title: 'Club Name'
					},
					'city': {
						type: 'text',
						title: 'City'
					},
					'abbreviation': {
						type: 'text',
						title: 'Club Abbreviation'
					},
					'country': {
						title: 'Country',
						type: 'dropdown',
						autocomplete: true,
						source: anwpImportOptions.countries
					},
					'address': {
						type: 'text',
						title: 'Address'
					},
					'website': {
						type: 'text',
						title: 'Website'
					},
					'founded': {
						type: 'text',
						title: 'Founded'
					},
					'is_national_team': {
						type: 'dropdown',
						title: 'National Team',
						width: 150,
						source: [ 'yes', 'no' ]
					},
					'custom_title_1': {
						type: 'text',
						title: 'Custom - Title 1'
					},
					'custom_title_2': {
						type: 'text',
						title: 'Custom - Title 2'
					},
					'custom_title_3': {
						type: 'text',
						title: 'Custom - Title 3'
					},
					'custom_value_1': {
						type: 'text',
						title: 'Custom - Value 1'
					},
					'custom_value_2': {
						type: 'text',
						title: 'Custom - Value 2'
					},
					'custom_value_3': {
						type: 'text',
						title: 'Custom - Value 3'
					}
				}
			};

			var dataColumns = [];

			$wrapper.find( '.anwpfl-tools-sortable' ).sortable( {
				handle: '.anwp-drag-handler'
			} );

			importSelector.on( 'change', function() {

				container.classList.add( 'invisible' );
				container.innerHTML = '';

				$wrapper.find( '.anwpfl-batch-import-filter-wrapper' ).addClass( 'd-none' );

				switch ( importSelector.val() ) {
					case 'players':

						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="players"]' ).removeClass( 'd-none' );
						btnSave.removeClass( 'd-none' );
						infoSave.html( '' );

						dataColumns = [];

						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="players"] .anwpfl-tools-sortable label' ).each( function() {
							var $this = $( this );

							if ( $this.find( 'input' ).prop( 'checked' ) ) {
								dataColumns.push( columns.players[ $this.data( 'slug' ) ] );
							}
						} );

						jExcelOptions.columns = dataColumns;
						jExcelOptions.data    = [];

						container.classList.remove( 'invisible' );
						tableData = jexcel( container, jExcelOptions );
						break;

					case 'referees':

						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="referees"]' ).removeClass( 'd-none' );
						btnSave.removeClass( 'd-none' );
						infoSave.html( '' );

						dataColumns = [];

						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="referees"] .anwpfl-tools-sortable label' ).each( function() {
							var $this = $( this );

							if ( $this.find( 'input' ).prop( 'checked' ) ) {
								dataColumns.push( columns.referees[ $this.data( 'slug' ) ] );
							}
						} );

						jExcelOptions.columns = dataColumns;
						jExcelOptions.data    = [];

						container.classList.remove( 'invisible' );
						tableData = jexcel( container, jExcelOptions );
						break;

					case 'clubs':
						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="clubs"]' ).removeClass( 'd-none' );

						btnSave.removeClass( 'd-none' );
						infoSave.html( '' );

						dataColumns = [];

						$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="clubs"] .anwpfl-tools-sortable label' ).each( function() {
							var $this = $( this );

							if ( $this.find( 'input' ).prop( 'checked' ) ) {
								dataColumns.push( columns.clubs[ $this.data( 'slug' ) ] );
							}
						} );

						jExcelOptions.columns = dataColumns;
						jExcelOptions.data    = [];

						container.classList.remove( 'invisible' );
						tableData = jexcel( container, jExcelOptions );
						break;
				}
			} );

			$wrapper.on( 'click', '.anwpfl-batch-import-update-settings', function( e ) {
				e.preventDefault();
				importSelector.trigger( 'change' );

				toastr.success( 'New settings have been applied' );
			} );

			// Save data
			btnSave.on( 'click', function( e ) {

				var importType = importSelector.val();

				e.preventDefault();

				// Check for active request and type
				if ( activeRequest || ! importType ) {
					return;
				}

				activeRequest = true;
				btnSave.addClass( 'anwp-request-active' );

				var data = {
					table: tableData.getData(),
					headers: []
				};

				$wrapper.find( '.anwpfl-batch-import-filter-wrapper[data-type="' + importType + '"] .anwpfl-tools-sortable label' ).each( function() {
					var $this = $( this );

					if ( $this.find( 'input' ).prop( 'checked' ) ) {
						data.headers.push( $this.data( 'slug' ) );
					}
				} );

				jQuery.ajax( {
					dataType: 'json',
					method: 'POST',
					data: data,
					beforeSend: function( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', anwpImportOptions.rest_nonce );
					}.bind( this ),
					url: anwpImportOptions.rest_root + 'anwpfl/v1/import/' + importType
				} ).done( function( responseText ) {
					toastr.success( responseText );
					importSelector.val( '' );
					importSelector.trigger( 'change' );
				} ).fail( function( response ) {
					toastr.error( response.responseJSON.message ? response.responseJSON.message : 'Error' );
				} ).always( function() {
					activeRequest = false;
					btnSave.removeClass( 'anwp-request-active' );
				} );
			} );
		} );
	}( jQuery ));
</script>
