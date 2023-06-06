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
?>
<div class="anwp-b-wrap wrap about-wrap" id="anwpfl-import-wrapper">

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active text-dark h6 mb-0" href="#"><?php echo esc_html__( 'Batch Import', 'anwp-football-leagues' ); ?></a>
	</h2>

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
		<h6 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="small anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h6>
		<div class="anwpfl-tools-sortable mt-2">
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="player_name" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Player Name', 'anwp-football-leagues' ); ?> *</span><input type="checkbox" disabled checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="short_name" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Short Name', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="weight" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Weight (kg)', 'anwp-football-leagues' ); ?></span><input type="checkbox" checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="height" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Height (cm)', 'anwp-football-leagues' ); ?></span><input type="checkbox" checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="position" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Position', 'anwp-football-leagues' ); ?></span><input type="checkbox" checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="current_club" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Current Club', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="place_of_birth" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Place of Birth', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="date_of_birth" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Date of Birth', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="nationality_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Nationality', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="nationality_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Nationality', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_3" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #3</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_3" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #3</span><input type="checkbox"></label>
			</div>
		</div>
	</div>

	<div class="my-3 p-2 border anwpfl-batch-import-filter-wrapper d-none" data-type="referees">
		<h6 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="small anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h6>
		<div class="anwpfl-tools-sortable mt-2">
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="referee_name" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Referee Name', 'anwp-football-leagues' ); ?> *</span><input type="checkbox" disabled checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="short_name" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Short Name', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="job_title" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Job Title', 'anwp-football-leagues' ); ?></span><input type="checkbox" checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="place_of_birth" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Place of Birth', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="date_of_birth" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Date of Birth', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="nationality_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Nationality', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="nationality_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Nationality', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
		</div>
	</div>

	<div class="my-3 p-2 border anwpfl-batch-import-filter-wrapper d-none" data-type="clubs">
		<h6 class="my-1"><?php echo esc_html__( 'Columns order and visibility', 'anwp-football-leagues' ); ?> <a href="#" class="small anwpfl-batch-import-update-settings ml-2"><?php echo esc_html__( 'apply new settings', 'anwp-football-leagues' ); ?></a></h6>

		<div class="anwpfl-tools-sortable mt-2">
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="club_title" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Club Title', 'anwp-football-leagues' ); ?> *</span><input type="checkbox" disabled checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="abbreviation" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Abbreviation', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="city" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'City', 'anwp-football-leagues' ); ?></span><input type="checkbox" checked></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="country" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Country', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="address" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Address', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="website" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Website', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="founded" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Founded', 'anwp-football-leagues' ); ?></span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_1" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #1</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_2" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #2</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_title_3" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Title', 'anwp-football-leagues' ); ?> #3</span><input type="checkbox"></label>
			</div>
			<div class="my-1 mr-1 py-1 px-2 d-inline-block border border-secondary anwp-d-flex-not-important align-items-center">
				<svg class="anwp-icon anwp-icon--s16 anwp-icon--octi anwp-drag-handler"><use xlink:href="#icon-grabber"></use></svg>
				<label data-slug="custom_value_3" class="mb-0"><span class="mr-2 text-secondary"><?php echo esc_html__( 'Custom Field - Value', 'anwp-football-leagues' ); ?> #3</span><input type="checkbox"></label>
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

</div>

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
				players: {
					'player_name': {
						type: 'text',
						title: 'Player Name',
						width: 120
					},
					'short_name': {
						type: 'text',
						title: 'Player Short Name',
						width: 120
					},
					'weight': {
						type: 'numeric',
						title: 'Weight'
					},
					'height': {
						type: 'numeric',
						title: 'Height'
					},
					'position': {
						type: 'dropdown',
						title: 'Position',
						width: 150,
						source: anwpImportOptions.positions
					},
					'current_club': {
						type: 'dropdown',
						source: anwpImportOptions.clubs,
						autocomplete: true,
						title: 'Current Club'
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
				},
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

			$wrapper.on( 'click', '.anwpfl-batch-import-update-settings', function() {
				importSelector.trigger( 'change' );
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
