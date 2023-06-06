<?php
/**
 * The Template for displaying Competition Header Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-competition_header.php.
 *
 * @var object $data - Object with shortcode data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.5.1
 * @since         0.7.4 Added link wrapper
 *
 * @version       0.11.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check for required data
if ( empty( $data->id ) ) {
	return;
}

$data = (object) wp_parse_args(
	$data,
	[
		'title_as_link' => 0,
		'title'         => '',
	]
);

$terms = anwp_football_leagues()->competition->tmpl_get_competition_terms( $data->id );
$logo  = get_post_meta( $data->id, '_anwpfl_logo', true );

// Prepare season data
if ( ! empty( $terms['season_title'] ) && is_array( $terms['season_title'] ) ) {

	$seasons = $terms['season_title'];
	$seasons = array_filter( $seasons, 'ctype_digit' );
	sort( $seasons, SORT_NUMERIC );

	if ( 0 === count( $seasons ) ) {
		$terms['season_title'] = $terms['season_title'][0];
	} elseif ( 1 === count( $seasons ) ) {
		$terms['season_title'] = $seasons[0];
	} else {
		$terms['season_title'] = $seasons[0] . '-' . end( $seasons );
	}
} else {
	$terms['season_title'] = '';
}

/*
|--------------------------------------------------------------------------
| Prepare link
|--------------------------------------------------------------------------
*/
$link_post_id = 0;

if ( anwp_football_leagues()->helper->string_to_bool( $data->title_as_link ) ) {
	$link_post_id = anwp_football_leagues()->competition->get_main_competition_id( $data->id );
}
?>
<div class="anwp-b-wrap competition__header">
	<div class="d-flex align-items-center border p-3 mb-4 position-relative">
		<?php if ( $logo ) : ?>
			<div class="competition__logo-wrapper mr-3">
				<img class="competition__logo" src="<?php echo esc_url( $logo ); ?>">
			</div>
		<?php endif; ?>
		<div class="competition__title-wrapper">
			<h2 class="competition__title mb-2"><?php echo esc_html( empty( $data->title ) ? $terms['league_title'] : $data->title ); ?></h2>
			<span class="d-block text-muted"><?php echo esc_html( $terms['season_title'] ); ?></span>
		</div>
		<?php if ( $link_post_id ) : ?>
			<a href="<?php echo esc_url( get_permalink( $link_post_id ) ); ?>" class="anwp-link-cover anwp-link-without-effects"></a>
		<?php endif; ?>
	</div>
</div>
