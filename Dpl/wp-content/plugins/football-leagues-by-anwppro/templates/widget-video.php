<?php
/**
 * The Template for displaying Match Video.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/widget-video.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.22
 *
 * @version       0.11.10
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prevent errors with new params
$args = (object) wp_parse_args(
	$data,
	[
		'club_id'        => '',
		'competition_id' => '',
		'include_ids'    => '',
	]
);

// Get competition matches
$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches_extended(
	[
		'competition_id'  => $args->competition_id,
		'show_secondary'  => 1,
		'type'            => 'result',
		'filter_by_clubs' => $args->club_id,
		'limit'           => 0,
		'sort_by_date'    => 'desc',
		'include_ids'     => $args->include_ids,
	],
	'ids'
);

if ( empty( $matches ) ) {
	return;
}

$match_id_with_video = '';
$available_match_ids = anwp_football_leagues()->match->get_matches_with_video();

foreach ( $matches as $match_id ) {

	if ( ! in_array( absint( $match_id ), $available_match_ids, true ) ) {
		continue;
	}

	$video_source = get_post_meta( $match_id, '_anwpfl_video_source', true );

	if ( ( in_array( $video_source, [ 'youtube', 'vimeo' ], true ) && get_post_meta( $match_id, '_anwpfl_video_id', true ) )
			|| ( 'site' === $video_source && get_post_meta( $match_id, '_anwpfl_video_media_url', true ) ) ) {

		$match_id_with_video = $match_id;
		break;
	}
}

if ( empty( $match_id_with_video ) ) {
	return;
}

// Prepare video data
$video_info      = get_post_meta( $match_id_with_video, '_anwpfl_video_info', true );
$video_source    = get_post_meta( $match_id_with_video, '_anwpfl_video_source', true );
$video_id        = get_post_meta( $match_id_with_video, '_anwpfl_video_id', true );
$video_media_url = get_post_meta( $match_id_with_video, '_anwpfl_video_media_url', true );
?>
<div class="anwp-b-wrap">
	<div class="anwp-video-module">
		<div class="anwp-video-grid__item-inner">

			<?php
			if ( 'youtube' === AnWPFL_Options::get_value( 'preferred_video_player' ) || ( 'mixed' === AnWPFL_Options::get_value( 'preferred_video_player' ) && 'youtube' === $video_source && $video_id ) ) :
				$video_id = anwp_football_leagues()->helper->get_youtube_id( $video_id );
				?>
				<div class="embed-responsive embed-responsive-16by9 anwp-fl-yt-video"
					style="background-image: url('https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/0.jpg')"
					data-video="<?php echo esc_attr( $video_id ); ?>">
				</div>
			<?php else : ?>

				<?php if ( 'site' === $video_source && $video_media_url ) : ?>
					<video class="anwp-video-player" playsinline controls>
						<source src="<?php echo esc_url( $video_media_url ); ?>" type="video/mp4">
					</video>
				<?php elseif ( 'youtube' === $video_source && $video_id ) : ?>
					<div class="anwp-video-player" data-plyr-provider="youtube" data-plyr-embed-id="<?php echo esc_attr( $video_id ); ?>"></div>
				<?php elseif ( 'vimeo' === $video_source && $video_id ) : ?>
					<div class="anwp-video-player" data-plyr-provider="vimeo" data-plyr-embed-id="<?php echo esc_attr( $video_id ); ?>"></div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( $video_info ) : ?>
				<div class="anwp-video-grid__item-info mt-1"><?php echo esc_html( $video_info ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
