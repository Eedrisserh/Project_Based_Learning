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

		ob_start();
		?>
		<div class="anwpfl-shortcode-modal__header">
			<label for="anwpfl-shortcode-modal__selector"><?php echo esc_html__( 'Shortcode', 'anwp-football-leagues' ); ?></label>
			<select id="anwpfl-shortcode-modal__selector">
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

		/**
		 * Render form with shortcode options.
		 *
		 * @since 0.12.7
		 */
		do_action( 'anwpfl/shortcode/get_shortcode_form_' . sanitize_text_field( $shortcode ) );

		/**
		 * Hook: anwpfl/shortcodes/modal_form_shortcode
		 *
		 * @since 0.10.8
		 */
		do_action( 'anwpfl/shortcodes/modal_form_shortcode', $shortcode );

		$html_output = ob_get_clean();

		wp_send_json_success( [ 'html' => $html_output ] );
	}
}

// Bump
new AnWPFL_Shortcode();
