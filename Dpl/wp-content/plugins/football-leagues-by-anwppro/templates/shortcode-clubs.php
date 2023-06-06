<?php
/**
 * The Template for displaying Standing Clubs Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-clubs.php.
 *
 * @var object $data - Object with shortcode data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.23
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'competition_id' => '',
		'logo_size'      => 'big',
		'layout'         => '',
		'context'        => 'shortcode',
		'logo_height'    => '50px',
		'logo_width'     => '50px',
		'show_club_name' => false,
		'exclude_ids'    => '',
		'include_ids'    => '',
	]
);

if ( ! empty( $data->include_ids ) ) {
	$clubs_array = wp_parse_id_list( $data->include_ids );
} else {

	if ( empty( $data->competition_id ) ) {
		return;
	}

	$clubs_array = anwp_football_leagues()->competition->get_competition_clubs( $data->competition_id, 'all' );

	// Check exclude ids
	if ( ! empty( $data->exclude_ids ) ) {
		$exclude     = $data->exclude_ids ? wp_parse_id_list( $data->exclude_ids ) : [];
		$clubs_array = array_diff( $clubs_array, $exclude );
	}
}

if ( empty( $clubs_array ) ) {
	return;
}

// Prepare data
$clubs = get_posts(
	[
		'numberposts'      => - 1,
		'post_type'        => 'anwp_club',
		'suppress_filters' => false,
		'include'          => $clubs_array,
		'post_status'      => 'publish',
		'order'            => 'ASC',
		'orderby'          => 'title',
		'show_club_name'   => false,
	]
);

if ( '' === $data->layout ) : ?>
	<div class="anwp-b-wrap clubs clubs--shortcode clubs__inner context--<?php echo esc_attr( $data->context ); ?>">
		<div class="d-flex flex-wrap">
			<?php
			foreach ( $clubs as $club ) :
				$logo = 'small' === $data->logo_size ? $club->_anwpfl_logo : $club->_anwpfl_logo_big;
				?>
				<div class="club-logo position-relative">
					<div class="anwp-image-background-contain club-logo__image mx-auto" style="width: <?php echo esc_attr( $data->logo_width ); ?>; height: <?php echo esc_attr( $data->logo_height ); ?>; background-image: url('<?php echo esc_attr( $logo ); ?>')"></div>
					<a class="anwp-link-without-effects anwp-link-cover" href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $club->ID ) ); ?>"></a>
					<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_club_name ) ) : ?>
						<div class="club-logo__text anwp-text-center text-truncate text-nowrap small" style="width: <?php echo esc_attr( $data->logo_width ); ?>;"><?php echo esc_html( $club->_anwpfl_abbr ? : $club->post_title ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php elseif ( in_array( $data->layout, [ '2col', '3col', '4col', '6col' ], true ) ) : ?>
	<div class="anwp-b-wrap clubs clubs--shortcode clubs__inner context--<?php echo esc_attr( $data->context ); ?>">
		<div class="anwp-row anwp-no-gutters layout--grid">
			<?php
			$col_class = [
				'2col' => 'anwp-col-6',
				'3col' => 'anwp-col-4',
				'4col' => 'anwp-col-3',
				'6col' => 'anwp-col-2',
			];

			foreach ( $clubs as $club ) :
				$logo = 'small' === $data->logo_size ? $club->_anwpfl_logo : $club->_anwpfl_logo_big;
				?>
				<div class="<?php echo esc_attr( $col_class[ $data->layout ] ); ?> p-1 d-flex align-self-stretch">
					<div class="club-logo club-logo--grid w-100 d-flex flex-column">
						<a class="anwp-link-without-effects d-flex align-items-center justify-content-center w-100 h-100 anwp-club-logo--widget"
							style="background-image: url('<?php echo esc_attr( $logo ); ?>')"
							href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $club->ID ) ); ?>">
							<img class="invisible" src="<?php echo esc_attr( $logo ); ?>" alt="">
						</a>
						<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_club_name ) ) : ?>
							<div class="club-logo__text anwp-text-center text-truncate text-nowrap small py-1"><?php echo esc_html( $club->_anwpfl_abbr ? : $club->post_title ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
endif;
