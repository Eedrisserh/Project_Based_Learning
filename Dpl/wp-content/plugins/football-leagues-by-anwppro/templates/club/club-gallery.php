<?php
/**
 * The Template for displaying Club >> Gallery Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-gallery.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.9
 *
 * @version       0.13.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'club_id' => '',
		'header'  => true,
	]
);

$club = get_post( $data->club_id );

// Get Gallery Data
$gallery = $club->_anwpfl_gallery;

if ( empty( $gallery ) || ! is_array( $gallery ) ) {
	return;
}

$gallery_alts = anwp_football_leagues()->data->get_image_alt( array_keys( $gallery ) );

/**
 * Hook: anwpfl/tmpl-club/before_gallery
 *
 * @since 0.10.9
 *
 * @param WP_Post $club
 */
do_action( 'anwpfl/tmpl-club/before_gallery', $club );
?>
<div class="club__gallery-wrapper club-section anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'club__gallery__gallery', __( 'Gallery', 'anwp-football-leagues' ) ) ); ?></div>
	<?php endif; ?>

	<div class="anwpfl-not-ready-0 anwp-justified-gallery" id="club__gallery" data-featherlight-gallery data-featherlight-filter="a">
		<?php foreach ( $gallery as $image_id => $image ) : ?>
			<a href="<?php echo esc_attr( $image ); ?>"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( isset( $gallery_alts[ $image_id ] ) ? $gallery_alts[ $image_id ] : '' ); ?>"></a>
		<?php endforeach; ?>
	</div>

	<?php if ( $club->_anwpfl_gallery_notes ) : ?>
		<p class="mt-2 small text-muted"><?php echo wp_kses_post( $club->_anwpfl_gallery_notes ); ?></p>
	<?php endif; ?>
</div>
