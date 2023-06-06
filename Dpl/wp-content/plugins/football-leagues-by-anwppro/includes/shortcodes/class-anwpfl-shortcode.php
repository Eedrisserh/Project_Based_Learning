<?php
/**
 * Add Shortcodes Button in TinyMCE.
 *
 * @since   0.5.4
 * @package AnWP_Football_Leagues
 */

/**
 * AnWP Football Leagues :: Shortcode
 */
class AnWPFL_Shortcode {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'mce_button' ] );

		add_action( 'after_wp_tiny_mce', [ $this, 'tinymce_l10n_vars' ] );
		add_action( 'enqueue_block_assets', [ $this, 'add_scripts_to_gutenberg' ] );

		// Ajax request
		add_action( 'wp_ajax_fl_shortcodes_modal_structure', [ $this, 'get_modal_structure' ] );
		add_action( 'wp_ajax_fl_shortcodes_modal_form', [ $this, 'get_modal_form' ] );
	}

	/**
	 * Load TinyMCE localized vars
	 *
	 * @since 0.5.5
	 */
	public function tinymce_l10n_vars() {

		$vars = [
			'football_leagues' => esc_html__( 'Football Leagues', 'anwp-football-leagues' ),
			'nonce'            => wp_create_nonce( 'fl_shortcodes_nonce' ),
		];

		?>
		<script type="text/javascript">
			var _fl_shortcodes_l10n = <?php echo wp_json_encode( $vars ); ?>;
		</script>
		<?php
	}

	/**
	 * Filter Functions with Hooks
	 *
	 * @since 0.5.4
	 */
	public function mce_button() {

		// Check if user have permission
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// Disable loading UI helper programmatically
		if ( ! apply_filters( 'anwpfl/config/load_shortcodes_ui_helper', true ) ) {
			return;
		}

		// Check if WYSIWYG is enabled
		if ( 'true' === get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', [ $this, 'add_tinymce_plugin' ] );
			add_filter( 'mce_buttons', [ $this, 'register_tinymce_button' ] );
		}
	}

	/**
	 * @param $plugin_array
	 *
	 * @return mixed
	 * @since 0.5.4
	 */
	public function add_tinymce_plugin( $plugin_array ) {
		$plugin_array['football_leagues_button'] = AnWP_Football_Leagues::url( 'admin/js/tinymce-plugin.js?ver=' . anwp_football_leagues()->version );

		return $plugin_array;
	}

	/**
	 * @param $buttons
	 *
	 * @return mixed
	 * @since 0.5.4
	 */
	public function register_tinymce_button( $buttons ) {

		array_push( $buttons, 'football_leagues' );

		return $buttons;
	}

	/**
	 * Added TinyMCE scripts to the Gutenberg Classic editor Block
	 *
	 * @since 0.8.3
	 */
	public function add_scripts_to_gutenberg() {
		global $current_screen;

		$is_gutenberg_old = function_exists( 'is_gutenberg_page' ) && is_gutenberg_page();
		$is_gutenberg_new = $current_screen instanceof WP_Screen && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();

		if ( is_admin() && ( $is_gutenberg_new || $is_gutenberg_old ) ) {
			$this->tinymce_l10n_vars();
		}
	}

	/**
	 * Get results for ajax request.
	 *
	 * @since 0.10.8
	 */
	public function get_modal_structure() {

		// Check if our nonce is set.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'fl_shortcodes_nonce' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		ob_start();
		?>
		<div class="anwpfl-shortcode-modal__header">
			<label for="anwpfl-shortcode-modal__selector"><?php echo esc_html__( 'Shortcode', 'anwp-football-leagues' ); ?></label>
			<select id="anwpfl-shortcode-modal__selector">
				<option value="">- <?php echo esc_html__( 'select', 'anwp-football-leagues' ); ?> -</option>
				<option value="cards"><?php echo esc_html__( 'Cards', 'anwp-football-leagues' ); ?></option>
				<option value="club"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></option>
				<option value="clubs"><?php echo esc_html__( 'Clubs', 'anwp-football-leagues' ); ?></option>
				<option value="competition-header"><?php echo esc_html__( 'Competition Header', 'anwp-football-leagues' ); ?></option>
				<option value="match"><?php echo esc_html__( 'Match', 'anwp-football-leagues' ); ?></option>
				<option value="matches"><?php echo esc_html__( 'Matches', 'anwp-football-leagues' ); ?></option>
				<option value="player"><?php echo esc_html__( 'Player Card', 'anwp-football-leagues' ); ?></option>
				<option value="player-data"><?php echo esc_html__( 'Player Data', 'anwp-football-leagues' ); ?></option>
				<option value="players"><?php echo esc_html__( 'Players', 'anwp-football-leagues' ); ?></option>
				<option value="squad"><?php echo esc_html__( 'Squad', 'anwp-football-leagues' ); ?></option>
				<option value="standing"><?php echo esc_html__( 'Standing Table', 'anwp-football-leagues' ); ?></option>
				<?php
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
		<div class="anwpfl-shortcode-modal__content"></div>
		<div class="anwpfl-shortcode-modal__footer">
			<button id="anwpfl-shortcode-modal__cancel" class="button"><?php echo esc_html__( 'Close', 'anwp-football-leagues' ); ?></button>
			<button id="anwpfl-shortcode-modal__insert" class="button button-primary"><?php echo esc_html__( 'Insert Shortcode', 'anwp-football-leagues' ); ?></button>
		</div>
		<?php
		$html_output = ob_get_clean();

		wp_send_json_success( [ 'html' => $html_output ] );
	}

	/**
	 * Get results for ajax request.
	 *
	 * @since 0.10.8
	 */
	public function get_modal_form() {

		// Check if our nonce is set.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'fl_shortcodes_nonce' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			wp_send_json_error( 'Error : Unauthorized action' );
		}

		$shortcode = isset( $_POST['shortcode'] ) ? sanitize_key( $_POST['shortcode'] ) : '';

		if ( ! $shortcode ) {
			wp_send_json_error( 'Error : Incorrect Data' );
		}

		ob_start();

		// ToDo move to own classes
		switch ( $shortcode ) {
			case 'standing':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'standing' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-title"><?php echo esc_html__( 'Title', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="title" data-fl-type="text" type="text" id="fl-form-shortcode-title" value="" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Standing Table', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<?php foreach ( anwp_football_leagues()->standing->get_standing_options() as $standing_id => $standing_title ) : ?>
									<option value="<?php echo esc_attr( $standing_id ); ?>"><?php echo esc_html( $standing_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-standing-exclude"><?php echo esc_html__( 'Exclude Clubs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="exclude_ids" data-fl-type="select2" id="fl-form-shortcode-standing-exclude" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-standing-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-standing-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'default', 'anwp-football-leagues' ); ?></option>
								<option value="mini"><?php echo esc_html__( 'mini', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-partial"><?php echo esc_html__( 'Show Partial Data', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="partial" data-fl-type="text" type="text" id="fl-form-shortcode-partial" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Eg.: "1-5" (show teams from 1 to 5 place), "45" - show table slice with specified team ID in the middle', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-standing-bottom_link"><?php echo esc_html__( 'Show link to', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="bottom_link" data-fl-type="select" id="fl-form-shortcode-standing-bottom_link" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
								<option value="competition"><?php echo esc_html__( 'competition', 'anwp-football-leagues' ); ?></option>
								<option value="standing"><?php echo esc_html__( 'standing', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-link_text"><?php echo esc_html__( 'Alternative bottom link text', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="link_text" data-fl-type="text" type="text" id="fl-form-shortcode-link_text" value="" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_notes"><?php echo esc_html__( 'Show Notes', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="show_notes" data-fl-type="select" id="fl-form-shortcode-show_notes" class="postform fl-shortcode-attr">
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-standing">
				<?php
				break;

			case 'clubs':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'clubs' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="competition_id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'anwp-football-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->competition->get_competition_options() as $competition_id => $competition_title ) : ?>
									<option value="<?php echo esc_attr( $competition_id ); ?>"><?php echo esc_html( $competition_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-logo_size"><?php echo esc_html__( 'Logo Size', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="logo_size" data-fl-type="select" id="fl-form-shortcode-logo_size" class="postform fl-shortcode-attr">
								<option value="small"><?php echo esc_html__( 'Small', 'anwp-football-leagues' ); ?></option>
								<option value="big" selected><?php echo esc_html__( 'Big', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Custom Height and Width', 'anwp-football-leagues' ); ?></option>
								<option value="2col"><?php echo esc_html__( '2 Columns', 'anwp-football-leagues' ); ?></option>
								<option value="3col"><?php echo esc_html__( '3 Columns', 'anwp-football-leagues' ); ?></option>
								<option value="4col"><?php echo esc_html__( '4 Columns', 'anwp-football-leagues' ); ?></option>
								<option value="6col"><?php echo esc_html__( '6 Columns', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-logo_height"><?php echo esc_html__( 'Logo Height', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="logo_height" data-fl-type="text" type="text" id="fl-form-shortcode-logo_height" value="50px" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Height value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-logo_width"><?php echo esc_html__( 'Logo Width', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="logo_width" data-fl-type="text" type="text" id="fl-form-shortcode-logo_width" value="50px" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Width value with units. Example: "50px" or "3rem".', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-exclude"><?php echo esc_html__( 'Exclude Clubs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="exclude_ids" data-fl-type="select2" id="fl-form-shortcode-exclude" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-include"><?php echo esc_html__( 'Include Clubs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="include_ids" data-fl-type="select2" id="fl-form-shortcode-include" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'If this option is set, "Competition" option will be ignored.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_club_name"><?php echo esc_html__( 'Show club name', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="show_club_name" data-fl-type="select" id="fl-form-shortcode-show_club_name" class="postform fl-shortcode-attr">
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-clubs">
				<?php
				break;

			case 'competition-header':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'competition-header' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<?php foreach ( anwp_football_leagues()->competition->get_competition_options() as $competition_id => $competition_title ) : ?>
									<option value="<?php echo esc_attr( $competition_id ); ?>"><?php echo esc_html( $competition_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-title_as_link"><?php echo esc_html__( 'Title as a link', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="title_as_link" data-fl-type="select" id="fl-form-shortcode-title_as_link" class="postform fl-shortcode-attr">
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-title"><?php echo esc_html__( 'Custom Title', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="title" data-fl-type="text" type="text" id="fl-form-shortcode-title" value="" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-competition-header">
				<?php
				break;

			case 'match':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'match' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-match_id"><?php echo esc_html__( 'Match ID', 'anwp-football-leagues' ); ?>*</label></th>
						<td>
							<input name="match_id" data-fl-type="text" type="text" id="fl-form-shortcode-match_id" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="match" data-single="yes">
								<span class="dashicons dashicons-search"></span>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-match-club_last"><?php echo esc_html__( 'Last finished match of the club', 'anwp-football-leagues' ); ?>*</label></th>
						<td>
							<select name="club_last" data-fl-type="select2" id="fl-form-shortcode-match-club_last" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-match-club_next"><?php echo esc_html__( 'Next match of the club', 'anwp-football-leagues' ); ?>*</label></th>
						<td>
							<select name="club_next" data-fl-type="select2" id="fl-form-shortcode-match-club_next" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="slim"><?php echo esc_html__( 'Slim', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sections"><?php echo esc_html__( 'Sections', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="sections" data-fl-type="select2" id="fl-form-shortcode-sections" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<option value="goals"><?php echo esc_html__( 'Goals', 'anwp-football-leagues' ); ?></option>
								<option value="cards"><?php echo esc_html__( 'Cards', 'anwp-football-leagues' ); ?></option>
								<option value="line_ups"><?php echo esc_html__( 'Line Ups', 'anwp-football-leagues' ); ?></option>
								<option value="substitutes"><?php echo esc_html__( 'Substitutes', 'anwp-football-leagues' ); ?></option>
								<option value="stats"><?php echo esc_html__( 'Stats', 'anwp-football-leagues' ); ?></option>
								<option value="referees"><?php echo esc_html__( 'Referees', 'anwp-football-leagues' ); ?></option>
								<option value="missed_penalties"><?php echo esc_html__( 'Missed Penalties', 'anwp-football-leagues' ); ?></option>
								<option value="summary"><?php echo esc_html__( 'Summary', 'anwp-football-leagues' ); ?></option>
								<option value="penalty_shootout"><?php echo esc_html__( 'Penalty Shootout', 'anwp-football-leagues' ); ?></option>
								<option value="video"><?php echo esc_html__( 'Video', 'anwp-football-leagues' ); ?></option>
								<option value="missing"><?php echo esc_html__( 'Missing Players', 'anwp-football-leagues' ); ?></option>
								<?php do_action( 'anwpfl/shortcodes/match_shortcode_options' ); ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_header"><?php echo esc_html__( 'Show Match Header', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_header" data-fl-type="select" id="fl-form-shortcode-show_header" class="postform fl-shortcode-attr">
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-match">
				<?php
				break;

			case 'player-data':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'player-data' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-player_id"><?php echo esc_html__( 'Player ID', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="player_id" data-fl-type="text" type="text" id="fl-form-shortcode-player_id" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="player" data-single="yes">
								<span class="dashicons dashicons-search"></span>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sections"><?php echo esc_html__( 'Sections', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="sections" data-fl-type="select2" id="fl-form-shortcode-sections" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<option value="header"><?php echo esc_html__( 'Header', 'anwp-football-leagues' ); ?></option>
								<option value="description"><?php echo esc_html__( 'Description', 'anwp-football-leagues' ); ?></option>
								<option value="gallery"><?php echo esc_html__( 'Gallery', 'anwp-football-leagues' ); ?></option>
								<option value="matches"><?php echo esc_html__( 'Matches', 'anwp-football-leagues' ); ?></option>
								<option value="missed"><?php echo esc_html__( 'Missed', 'anwp-football-leagues' ); ?></option>
								<option value="stats"><?php echo esc_html__( 'Stats', 'anwp-football-leagues' ); ?></option>
								<?php do_action( 'anwpfl/shortcodes/player_shortcode_options' ); ?>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-player-data">
				<?php
				break;

			case 'club':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'club' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club ID', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="club_id" data-fl-type="text" type="text" id="fl-form-shortcode-club_id" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="club" data-single="yes">
								<span class="dashicons dashicons-search"></span>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sections"><?php echo esc_html__( 'Sections', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="sections" data-fl-type="select2" id="fl-form-shortcode-sections" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<option value="header"><?php echo esc_html__( 'Header', 'anwp-football-leagues' ); ?></option>
								<option value="description"><?php echo esc_html__( 'Description', 'anwp-football-leagues' ); ?></option>
								<option value="gallery"><?php echo esc_html__( 'Gallery', 'anwp-football-leagues' ); ?></option>
								<option value="fixtures"><?php echo esc_html__( 'Fixtures', 'anwp-football-leagues' ); ?></option>
								<option value="latest"><?php echo esc_html__( 'Latest', 'anwp-football-leagues' ); ?></option>
								<option value="squad"><?php echo esc_html__( 'Squad', 'anwp-football-leagues' ); ?></option>
								<?php do_action( 'anwpfl/shortcodes/club_shortcode_options' ); ?>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-club">
				<?php
				break;

			case 'matches':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'matches' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="competition_id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->competition->get_competition_options() as $competition_id => $competition_title ) : ?>
									<option value="<?php echo esc_attr( $competition_id ); ?>"><?php echo esc_html( $competition_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_secondary"><?php echo esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_secondary" data-fl-type="select" id="fl-form-shortcode-show_secondary" class="postform fl-shortcode-attr">
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'Applied for multistage main stage competitions only.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-group-id"><?php echo esc_html__( 'Group ID', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="group_id" data-fl-type="text" type="text" id="fl-form-shortcode-group-id" value="" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Match Type', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'All', 'anwp-football-leagues' ); ?></option>
								<option value="result"><?php echo esc_html__( 'Result', 'anwp-football-leagues' ); ?></option>
								<option value="fixture"><?php echo esc_html__( 'Fixture', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-limit"><?php echo esc_html__( 'Matches Limit (0 - for all)', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="limit" data-fl-type="text" type="text" id="fl-form-shortcode-limit" value="0" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-date_from"><?php echo esc_html__( 'Date From', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="date_from" data-fl-type="text" type="text" id="fl-form-shortcode-date_from" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-date_to"><?php echo esc_html__( 'Date To', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="date_to" data-fl-type="text" type="text" id="fl-form-shortcode-date_to" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Format: YYYY-MM-DD. E.g.: 2019-04-21', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-stadium_id"><?php echo esc_html__( 'Filter by Stadium', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="stadium_id" data-fl-type="select2" id="fl-form-shortcode-stadium_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->stadium->get_stadiums_options() as $stadium_id => $stadium_title ) : ?>
									<option value="<?php echo esc_attr( $stadium_id ); ?>"><?php echo esc_html( $stadium_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-filter_by_clubs"><?php echo esc_html__( 'Filter by Clubs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="filter_by_clubs" data-fl-type="select2" id="fl-form-shortcode-filter_by_clubs" class="postform fl-shortcode-attr fl-shortcode-select2" multiple="multiple">
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-filter_by_matchweeks"><?php echo esc_html__( 'Filter by Matchweeks or Round IDs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="filter_by_matchweeks" data-fl-type="text" type="text" id="fl-form-shortcode-filter_by_matchweeks" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of matchweeks or rounds to filter', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-days_offset"><?php echo esc_html__( 'Dynamic days filter', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="days_offset" data-fl-type="text" type="text" id="fl-form-shortcode-days_offset" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'For example: "-2" from 2 days ago and newer; "2" from the day after tomorrow and newer', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-days_offset_to"><?php echo esc_html__( 'Dynamic days filter to', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="days_offset_to" data-fl-type="text" type="text" id="fl-form-shortcode-days_offset_to" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'For example: "1" - till tomorrow (tomorrow not included)', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sort_by_date"><?php echo esc_html__( 'Sort By Date', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="sort_by_date" data-fl-type="select" id="fl-form-shortcode-sort_by_date" class="postform fl-shortcode-attr">
								<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
								<option value="asc"><?php echo esc_html__( 'Oldest', 'anwp-football-leagues' ); ?></option>
								<option value="desc"><?php echo esc_html__( 'Latest', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sort_by_matchweek"><?php echo esc_html__( 'Sort By MatchWeek', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="sort_by_matchweek" data-fl-type="select" id="fl-form-shortcode-sort_by_matchweek" class="postform fl-shortcode-attr">
								<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
								<option value="asc"><?php echo esc_html__( 'Ascending', 'anwp-football-leagues' ); ?></option>
								<option value="desc"><?php echo esc_html__( 'Descending', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-group_by"><?php echo esc_html__( 'Group By', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="group_by" data-fl-type="select" id="fl-form-shortcode-group_by" class="postform fl-shortcode-attr">
								<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
								<option value="day"><?php echo esc_html__( 'Day', 'anwp-football-leagues' ); ?></option>
								<option value="month"><?php echo esc_html__( 'Month', 'anwp-football-leagues' ); ?></option>
								<option value="matchweek"><?php echo esc_html__( 'Matchweek', 'anwp-football-leagues' ); ?></option>
								<option value="stage"><?php echo esc_html__( 'Stage', 'anwp-football-leagues' ); ?></option>
								<option value="competition"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-group_by_header_style"><?php echo esc_html__( 'Group By Header Style', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="group_by_header_style" data-fl-type="select" id="fl-form-shortcode-group_by_header_style" class="postform fl-shortcode-attr">
								<option value=""><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="secondary"><?php echo esc_html__( 'Secondary', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_club_logos"><?php echo esc_html__( 'Show club logos', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_club_logos" data-fl-type="select" id="fl-form-shortcode-show_club_logos" class="postform fl-shortcode-attr">
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_match_datetime"><?php echo esc_html__( 'Show match datetime', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_match_datetime" data-fl-type="select" id="fl-form-shortcode-show_match_datetime" class="postform fl-shortcode-attr">
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-competition_logo"><?php echo esc_html__( 'Show Competition Logo', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="competition_logo" data-fl-type="select" id="fl-form-shortcode-competition_logo" class="postform fl-shortcode-attr">
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-include_ids"><?php echo esc_html__( 'Include IDs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="include_ids" data-fl-type="text" type="text" id="fl-form-shortcode-include_ids" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="match" data-single="no">
								<span class="dashicons dashicons-search"></span>
							</button>
							<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-exclude_ids"><?php echo esc_html__( 'Exclude IDs', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="exclude_ids" data-fl-type="text" type="text" id="fl-form-shortcode-exclude_ids" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="match" data-single="no">
								<span class="dashicons dashicons-search"></span>
							</button>
							<span class="anwp-option-desc"><?php echo esc_html__( 'comma-separated list of IDs', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-outcome_id"><?php echo esc_html__( 'Show Outcome for club ID', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="outcome_id" data-fl-type="select2" id="fl-form-shortcode-outcome_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value=""><?php echo esc_html__( 'none', 'anwp-football-leagues' ); ?></option>
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'works only in "slim" layout', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-no_data_text"><?php echo esc_html__( 'No data text', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="no_data_text" data-fl-type="text" type="text" id="fl-form-shortcode-no_data_text" value="" class="fl-shortcode-attr regular-text">
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-matches">
				<?php
				break;

			case 'squad':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'squad' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="club_id" data-fl-type="select2" id="fl-form-shortcode-club_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-header"><?php echo esc_html__( 'Show Header', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="header" data-fl-type="select" id="fl-form-shortcode-header" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="blocks"><?php echo esc_html__( 'Blocks', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout_block_columns"><?php echo esc_html__( 'Columns in Blocks Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout_block_columns" data-fl-type="select" id="fl-form-shortcode-layout_block_columns" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-squad">
				<?php
				break;

			case 'players':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'players' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Type', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
								<option value="scorers" selected><?php echo esc_html__( 'Scorers', 'anwp-football-leagues' ); ?></option>
								<option value="assists"><?php echo esc_html__( 'Assists', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="competition_id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->competition->get_competition_options() as $competition_id => $competition_title ) : ?>
									<option value="<?php echo esc_attr( $competition_id ); ?>"><?php echo esc_html( $competition_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-join_secondary"><?php echo esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="join_secondary" data-fl-type="select" id="fl-form-shortcode-join_secondary" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'Include stats from secondary stages. Works for multistage competitions only. You should set main stage ID in "competition_id" parameter.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-league_id"><?php echo esc_html__( 'League', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="league_id" data-fl-type="select2" id="fl-form-shortcode-league_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->league->get_league_options() as $league_id => $league_title ) : ?>
									<option value="<?php echo esc_attr( $league_id ); ?>"><?php echo esc_html( $league_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="club_id" data-fl-type="select2" id="fl-form-shortcode-club_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-limit"><?php echo esc_html__( 'Players Limit (0 - for all)', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="limit" data-fl-type="text" type="text" id="fl-form-shortcode-limit" value="0" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-soft_limit"><?php echo esc_html__( 'Soft Limit', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="soft_limit" data-fl-type="select" id="fl-form-shortcode-soft_limit" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'Increase number of players to the end of players with equal stats value.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_photo"><?php echo esc_html__( 'Show Photo', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_photo" data-fl-type="select" id="fl-form-shortcode-show_photo" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-players-compact"><?php echo esc_html__( 'Compact', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="compact" data-fl-type="select" id="fl-form-shortcode-players-compact" class="postform fl-shortcode-attr">
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-players-hide_zero"><?php echo esc_html__( 'Hide Zeros', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="hide_zero" data-fl-type="select" id="fl-form-shortcode-players-hide_zero" class="postform fl-shortcode-attr">
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="small"><?php echo esc_html__( 'Small', 'anwp-football-leagues' ); ?></option>
								<option value="mini"><?php echo esc_html__( 'Mini', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-players">
				<?php
				break;

			case 'player':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'player' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-player_id"><?php echo esc_html__( 'Player ID', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="player_id" data-fl-type="text" type="text" id="fl-form-shortcode-player_id" value="" class="fl-shortcode-attr code">
							<button type="button" class="button anwp-fl-selector" data-context="player" data-single="yes">
								<span class="dashicons dashicons-search"></span>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_club"><?php echo esc_html__( 'Show Club', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_club" data-fl-type="select" id="fl-form-shortcode-show_club" class="postform fl-shortcode-attr">
								<option value="0" selected><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1"><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-profile_link"><?php echo esc_html__( 'Show Link to Profile', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="profile_link" data-fl-type="select" id="fl-form-shortcode-profile_link" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-profile_link_text"><?php echo esc_html__( 'Profile link text', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="profile_link_text" data-fl-type="text" type="text" id="fl-form-shortcode-profile_link_text" value="profile" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-options_text"><?php echo esc_html__( 'Options Text', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="options_text" data-fl-type="text" type="text" id="fl-form-shortcode-options_text" value="" class="fl-shortcode-attr regular-text code">
							<span class="anwp-option-desc"><?php echo esc_html__( 'Separate line by "|", number and label - with ":". E.q.: "Goals: 8 | Assists: 5"', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-player">
				<?php
				break;

			case 'cards':
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_docs_link( 'cards' );
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-type"><?php echo esc_html__( 'Type', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="type" data-fl-type="select" id="fl-form-shortcode-type" class="postform fl-shortcode-attr">
								<option value="players" selected><?php echo esc_html__( 'Players', 'anwp-football-leagues' ); ?></option>
								<option value="clubs"><?php echo esc_html__( 'Clubs', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-id"><?php echo esc_html__( 'Competition', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="competition_id" data-fl-type="select2" id="fl-form-shortcode-id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->competition->get_competition_options() as $competition_id => $competition_title ) : ?>
									<option value="<?php echo esc_attr( $competition_id ); ?>"><?php echo esc_html( $competition_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-join_secondary"><?php echo esc_html__( 'Include matches from secondary stages', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="join_secondary" data-fl-type="select" id="fl-form-shortcode-join_secondary" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'Include stats from secondary stages. Works for multistage competitions only. You should set main stage ID in "competition_id" parameter.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-season_id"><?php echo esc_html__( 'Season', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="season_id" data-fl-type="select2" id="fl-form-shortcode-season_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->season->get_seasons_options() as $season_id => $season_title ) : ?>
									<option value="<?php echo esc_attr( $season_id ); ?>"><?php echo esc_html( $season_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-league_id"><?php echo esc_html__( 'League', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="league_id" data-fl-type="select2" id="fl-form-shortcode-league_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->league->get_league_options() as $league_id => $league_title ) : ?>
									<option value="<?php echo esc_attr( $league_id ); ?>"><?php echo esc_html( $league_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fl-form-shortcode-club_id"><?php echo esc_html__( 'Club', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<select name="club_id" data-fl-type="select2" id="fl-form-shortcode-club_id" class="postform fl-shortcode-attr fl-shortcode-select2">
								<option value="">- <?php echo esc_html__( 'select', 'sports-leagues' ); ?> -</option>
								<?php foreach ( anwp_football_leagues()->club->get_clubs_options() as $club_id => $club_title ) : ?>
									<option value="<?php echo esc_attr( $club_id ); ?>"><?php echo esc_html( $club_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-limit"><?php echo esc_html__( 'Players Limit (0 - for all)', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="limit" data-fl-type="text" type="text" id="fl-form-shortcode-limit" value="0" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-soft_limit"><?php echo esc_html__( 'Soft Limit', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="soft_limit" data-fl-type="select" id="fl-form-shortcode-soft_limit" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
							<span class="anwp-option-desc"><?php echo esc_html__( 'Increase number of players to the end of players with equal stats value.', 'anwp-football-leagues' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-show_photo"><?php echo esc_html__( 'Show Photo/Logo', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="show_photo" data-fl-type="select" id="fl-form-shortcode-show_photo" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-sort_by_point"><?php echo esc_html__( 'Sort By Points', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="sort_by_point" data-fl-type="select" id="fl-form-shortcode-sort_by_point" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Descending', 'anwp-football-leagues' ); ?></option>
								<option value="asc"><?php echo esc_html__( 'Ascending', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-points_r"><?php echo esc_html__( 'Points for Red card', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="points_r" data-fl-type="text" type="text" id="fl-form-shortcode-points_r" value="5" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-points_yr"><?php echo esc_html__( 'Points for Yellow/Red card', 'anwp-football-leagues' ); ?></label></th>
						<td>
							<input name="points_yr" data-fl-type="text" type="text" id="fl-form-shortcode-points_yr" value="2" class="fl-shortcode-attr regular-text code">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-hide_zero"><?php echo esc_html__( 'Hide with zero points', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="hide_zero" data-fl-type="select" id="fl-form-shortcode-hide_zero" class="postform fl-shortcode-attr">
								<option value="0"><?php echo esc_html__( 'No', 'anwp-football-leagues' ); ?></option>
								<option value="1" selected><?php echo esc_html__( 'Yes', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fl-form-shortcode-layout"><?php echo esc_html__( 'Layout', 'anwp-football-leagues' ); ?></label>
						</th>
						<td>
							<select name="layout" data-fl-type="select" id="fl-form-shortcode-layout" class="postform fl-shortcode-attr">
								<option value="" selected><?php echo esc_html__( 'Default', 'anwp-football-leagues' ); ?></option>
								<option value="mini"><?php echo esc_html__( 'Mini', 'anwp-football-leagues' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" class="fl-shortcode-name" name="fl-slug" value="anwpfl-cards">
				<?php
				break;

		}

		/**
		 * Hook: anwpfl/shortcodes/modal_form_shortcode
		 *
		 * @since 0.10.8
		 */
		do_action( 'anwpfl/shortcodes/modal_form_shortcode', $shortcode );

		$html_output = ob_get_clean();

		wp_send_json_success( [ 'html' => $html_output ] );
	}

	/**
	 * Renders documentation link.
	 *
	 * @param string $shortcode
	 *
	 * @return string
	 * @since 0.10.8
	 */
	private function render_docs_link( $shortcode ) {

		$shortcode_link  = '';
		$shortcode_title = '';

		switch ( $shortcode ) {
			case 'standing':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/155-standing-table-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Standing Table', 'anwp-football-leagues' );
				break;

			case 'clubs':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/158-clubs-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Clubs', 'anwp-football-leagues' );
				break;

			case 'competition-header':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/157-competition-header-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Competition Header', 'anwp-football-leagues' );
				break;

			case 'match':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/159-match-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Match', 'anwp-football-leagues' );
				break;

			case 'matches':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/160-matches-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Matches', 'anwp-football-leagues' );
				break;

			case 'squad':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/161-squad-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Squad', 'anwp-football-leagues' );
				break;

			case 'players':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/162-players-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Players', 'anwp-football-leagues' );
				break;

			case 'player':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/163-player-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Player', 'anwp-football-leagues' );
				break;

			case 'player-data':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/945-player-data-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Player Data', 'anwp-football-leagues' );
				break;

			case 'club':
				$shortcode_link  = 'https://anwppro.userecho.com/en/knowledge-bases/2/articles/1032-club-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Club', 'anwp-football-leagues' );
				break;

			case 'cards':
				$shortcode_link  = 'https://anwppro.userecho.com/knowledge-bases/2/articles/164-cards-shortcode';
				$shortcode_title = esc_html__( 'Shortcodes', 'anwp-football-leagues' ) . ' :: ' . esc_html__( 'Cards', 'anwp-football-leagues' );
				break;
		}

		/**
		 * Modify shortcode documentation link.
		 *
		 * @param string $shortcode_link
		 * @param string $shortcode
		 *
		 * @since 0.10.8
		 */
		$shortcode_link = apply_filters( 'anwpfl/shortcode/docs_link', $shortcode_link, $shortcode );

		/**
		 * Modify shortcode title.
		 *
		 * @param string $shortcode_title
		 * @param string $shortcode
		 *
		 * @since 0.10.8
		 */
		$shortcode_title = apply_filters( 'anwpfl/shortcode/docs_title', $shortcode_title, $shortcode );

		$output = '<div class="anwp-shortcode-docs-link">';

		$output .= '<svg class="anwp-icon anwp-icon--octi anwp-icon--s16"><use xlink:href="#icon-book"></use></svg>';
		$output .= '<b class="mx-2">' . esc_html__( 'Documentation', 'anwp-football-leagues' ) . ':</b> ';
		$output .= '<a target="_blank" href="' . esc_url( $shortcode_link ) . '">' . esc_html( $shortcode_title ) . '</a>';
		$output .= '</div>';

		return $output;
	}
}

// Bump
new AnWPFL_Shortcode();
