<?php
/**
 * The Template for displaying stadium content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-stadium.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.3.0
 *
 * @version       0.13.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$stadium = get_post();

// Map Data
$map_data = maybe_unserialize( $stadium->_anwpfl_map );

/**
 * Hook: anwpfl/tmpl-stadium/before_wrapper
 *
 * @since 0.7.5
 *
 * @param WP_Post $stadium
 */
do_action( 'anwpfl/tmpl-stadium/before_wrapper', $stadium );
?>
<div class="anwp-b-wrap stadium stadium__inner">

	<div class="anwp-row anwp-section">
		<?php if ( $stadium->_anwpfl_photo ) : ?>
			<div class="anwp-col-md-6">
				<img class="stadium__main-photo" src="<?php echo esc_attr( $stadium->_anwpfl_photo ); ?>" alt="<?php echo get_post_meta( $stadium->_anwpfl_photo_id, '_wp_attachment_image_alt', true ) ?: 'stadium photo'; ?>">
			</div>
		<?php endif; ?>
		<div class="anwp-col-md-6">
			<table class="table bg-light table-bordered table-sm options-list mb-4">
				<tbody>

				<?php
				/**
				 * Hook: anwpfl/tmpl-stadium/fields_top
				 *
				 * @since 0.7.5
				 *
				 * @param WP_Post $stadium
				 */
				do_action( 'anwpfl/tmpl-stadium/fields_top', $stadium );
				?>

				<?php if ( $stadium->_anwpfl_city ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__city', __( 'City', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $stadium->_anwpfl_city ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_clubs && is_array( $stadium->_anwpfl_clubs ) ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__clubs', __( 'Clubs', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<div class="d-flex flex-wrap">
								<?php foreach ( $stadium->_anwpfl_clubs as $stadium_club ) : ?>
									<span class="d-flex align-items-center mr-3">
										<?php if ( anwp_football_leagues()->club->get_club_logo_by_id( $stadium_club ) ) : ?>
											<span class="club-logo__cover club-logo__cover--mini mr-1" style="background-image: url('<?php echo esc_url( anwp_football_leagues()->club->get_club_logo_by_id( $stadium_club ) ); ?>')"></span>
										<?php endif; ?>
										<a href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $stadium_club ) ); ?>"><?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $stadium_club ) ); ?></a>
									</span>
								<?php endforeach; ?>
							</div>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_address ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__address', __( 'Address', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $stadium->_anwpfl_address ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_capacity ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__capacity', __( 'Capacity', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $stadium->_anwpfl_capacity ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_opened ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__opened', __( 'Opened', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $stadium->_anwpfl_opened ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_surface ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__surface', __( 'Surface', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $stadium->_anwpfl_surface ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $stadium->_anwpfl_website ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__website', __( 'Website', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><a target="_blank" rel="nofollow" href="<?php echo esc_attr( $stadium->_anwpfl_website ); ?>">
								<?php echo esc_html( str_replace( [ 'http://', 'https://' ], '', $stadium->_anwpfl_website ) ); ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php
				// Rendering custom fields
				for ( $ii = 1; $ii <= 3; $ii ++ ) :

					$custom_title = get_post_meta( $stadium->ID, '_anwpfl_custom_title_' . $ii, true );
					$custom_value = get_post_meta( $stadium->ID, '_anwpfl_custom_value_' . $ii, true );

					if ( $custom_title && $custom_value ) :
						?>
						<tr>
							<th scope="row" class="options-list__term"><?php echo esc_html( $custom_title ); ?></th>
							<td class="options-list__value"><?php echo do_shortcode( esc_html( $custom_value ) ); ?></td>
						</tr>
						<?php
					endif;
				endfor;

				// Rendering dynamic custom fields - @since v0.10.17
				$custom_fields = get_post_meta( $stadium->ID, '_anwpfl_custom_fields', true );

				if ( ! empty( $custom_fields ) && is_array( $custom_fields ) ) {
					foreach ( $custom_fields as $field_title => $field_text ) {
						if ( empty( $field_text ) ) {
							continue;
						}
						?>
						<tr>
							<th scope="row" class="options-list__term"><?php echo esc_html( $field_title ); ?></th>
							<td class="options-list__value"><?php echo do_shortcode( $field_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
					}
				}

				/**
				 * Hook: anwpfl/tmpl-stadium/fields_bottom
				 *
				 * @since 0.7.5
				 *
				 * @param WP_Post $stadium
				 */
				do_action( 'anwpfl/tmpl-stadium/fields_bottom', $stadium );
				?>

				</tbody>
			</table>
		</div>
	</div>

	<?php if ( $stadium->_anwpfl_description ) : ?>
		<div class="stadium__description anwp-section">
			<?php echo do_shortcode( wp_kses_post( wpautop( $stadium->_anwpfl_description ) ) ); ?>
		</div>
	<?php endif; ?>

	<?php
	/*
	|--------------------------------------------------------------------------
	| Fixture Matches
	|--------------------------------------------------------------------------
	*/
	$render_fixture_matches_option = 'hide' !== AnWPFL_Options::get_value( 'stadium_rendering_fixture_matches' );

	/**
	 * Rendering stadium fixture matches.
	 *
	 * @since 0.7.5
	 *
	 * @param string $render_fixture_matches_option
	 * @param int    $stadium ->ID
	 */
	if ( apply_filters( 'anwpfl/tmpl-stadium/render_fixture_matches', $render_fixture_matches_option, $stadium->ID ) ) :
		?>

		<div class="anwp-section">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__fixtures', __( 'Fixtures', 'anwp-football-leagues' ) ) ); ?></div>
			<?php
			$matches_args = [
				'stadium_id'   => $stadium->ID,
				'type'         => 'fixture',
				'limit'        => 3,
				'sort_by_date' => 'asc',
				'class'        => 'mt-2',
			];

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo anwp_football_leagues()->template->shortcode_loader( 'matches', $matches_args );
			?>
		</div>
		<?php
	endif;

	/*
	|--------------------------------------------------------------------------
	| Finished Matches
	|--------------------------------------------------------------------------
	*/
	$render_finished_matches_option = 'hide' !== AnWPFL_Options::get_value( 'stadium_rendering_finished_matches' );

	/**
	 * Rendering stadium finished matches.
	 *
	 * @since 0.7.5
	 *
	 * @param string $render_finished_matches_option
	 * @param int    $stadium ->ID
	 */
	if ( apply_filters( 'anwpfl/tmpl-stadium/render_finished_matches', $render_finished_matches_option, $stadium->ID ) ) :
		?>
		<div class="anwp-section">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__latest_matches', __( 'Latest Matches', 'anwp-football-leagues' ) ) ); ?></div>
			<?php
			$matches_args = [
				'stadium_id'   => $stadium->ID,
				'type'         => 'result',
				'limit'        => 3,
				'sort_by_date' => 'desc',
				'class'        => 'mt-2',
			];

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo anwp_football_leagues()->template->shortcode_loader( 'matches', $matches_args );
			?>
		</div>
		<?php
	endif;

	/*
	|--------------------------------------------------------------------------
	| Gallery
	|--------------------------------------------------------------------------
	*/
	/**
	 * Hook: anwpfl/tmpl-stadium/before_gallery
	 *
	 * @since 0.7.5
	 *
	 * @param WP_Post $stadium
	 */
	do_action( 'anwpfl/tmpl-stadium/before_gallery', $stadium );

	/**
	 * Rendering stadium gallery.
	 *
	 * @since 0.7.5
	 *
	 * @param bool
	 * @param WP_Post $stadium
	 */
	if ( apply_filters( 'anwpfl/tmpl-stadium/render_gallery', true, $stadium ) ) :
		if ( ! empty( $stadium->_anwpfl_gallery ) && is_array( $stadium->_anwpfl_gallery ) ) :

			$gallery_alts = anwp_football_leagues()->data->get_image_alt( array_keys( $stadium->_anwpfl_gallery ) );
			?>
			<div class="stadium__gallery-wrapper anwp-section">
				<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__stadium_gallery', __( 'Stadium Gallery', 'anwp-football-leagues' ) ) ); ?></div>
				<div class="anwpfl-not-ready-0 anwp-justified-gallery" id="stadium__gallery" data-featherlight-gallery data-featherlight-filter="a">
					<?php foreach ( $stadium->_anwpfl_gallery as $image_id => $image ) : ?>
						<a href="<?php echo esc_attr( $image ); ?>"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( isset( $gallery_alts[ $image_id ] ) ? $gallery_alts[ $image_id ] : '' ); ?>"></a>
					<?php endforeach; ?>
				</div>

				<?php if ( $stadium->_anwpfl_gallery_notes ) : ?>
					<p class="mt-2 small text-muted"><?php echo wp_kses_post( $stadium->_anwpfl_gallery_notes ); ?></p>
				<?php endif; ?>
			</div>
			<?php
		endif;
	endif;

	/**
	 * Hook: anwpfl/tmpl-stadium/before_map
	 *
	 * @since 0.7.5
	 *
	 * @param WP_Post $stadium
	 */
	do_action( 'anwpfl/tmpl-stadium/before_map', $stadium );
	?>

	<?php if ( is_array( $map_data ) && ! empty( $map_data['lat'] ) && ! empty( $map_data['longitude'] ) ) : ?>
		<div class="anwp-section">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'stadium__content__location', __( 'Location', 'anwp-football-leagues' ) ) ); ?></div>
			<div id="map--stadium" class="map map--stadium" data-lat="<?php echo esc_attr( $map_data['lat'] ); ?>" data-longitude="<?php echo esc_attr( $map_data['longitude'] ); ?>"></div>
		</div>
	<?php endif; ?>

</div>
<?php
/**
 * Hook: anwpfl/tmpl-stadium/after_wrapper
 *
 * @since 0.7.5
 *
 * @param WP_Post $stadium
 */
do_action( 'anwpfl/tmpl-stadium/after_wrapper', $stadium );
