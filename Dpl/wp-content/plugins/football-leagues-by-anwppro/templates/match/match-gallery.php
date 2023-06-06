<?php
/**
 * The Template for displaying Match >> Gallery Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-gallery.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.21
 *
 * @version       0.10.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'match_id' => '',
		'header'   => true,
	]
);

if ( empty( $data->match_id ) ) {
	return;
}

// Get Gallery Data
$gallery = get_post_meta( $data->match_id, '_anwpfl_gallery', true );

if ( empty( $gallery ) || ! is_array( $gallery ) ) {
	return;
}

$gallery_notes = get_post_meta( $data->match_id, '_anwpfl_gallery_notes', true );
?>
<div class="match__gallery-wrapper anwp-section">
	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'match__gallery__gallery', __( 'Gallery', 'anwp-football-leagues' ) ) ); ?></div>
	<?php endif; ?>
	<div class="anwpfl-not-ready-0 anwp-justified-gallery" id="match__gallery" data-featherlight-gallery data-featherlight-filter="a">
		<?php foreach ( $gallery as $image ) : ?>
			<a href="<?php echo esc_attr( $image ); ?>"><img src="<?php echo esc_url( $image ); ?>" alt=""></a>
		<?php endforeach; ?>
	</div>

	<?php if ( $gallery_notes ) : ?>
		<p class="mt-2 small text-muted"><?php echo wp_kses_post( $gallery_notes ); ?></p>
	<?php endif; ?>
</div>
