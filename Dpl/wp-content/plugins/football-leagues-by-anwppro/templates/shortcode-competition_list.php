<?php
/**
 * The Template for displaying Competition List Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-competition_list.php.
 *
 * @var object $data - Object with shortcode data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.12.3
 *
 * @version       0.12.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'league_ids'  => '',
		'season_ids'  => '',
		'include_ids' => '',
		'exclude_ids' => '',
		'group_by'    => '',
		'display'     => '',
		'show_logo'   => 'yes',
		'show_flag'   => 'big',
	]
);

$competition_list = anwp_football_leagues()->competition->get_competition_list( $data );

if ( empty( $competition_list ) ) {
	return;
}

$current_country      = '';
$is_country_collapsed = 'country_collapsed' === $data->group_by;
$country_classes      = $is_country_collapsed ? 'anwp-cursor-pointer competition-list__country_collapsed' : '';
?>
<div class="anwp-b-wrap" data-anwp-v="0.12.3">
	<div class="competition-list anwp-border-top anwp-border-gray-200 anwp-user-select-none">
		<?php foreach ( $competition_list as $country_data ) : ?>
			<?php if ( $country_data['country_code'] !== $current_country ) : ?>
				<div class="competition-list__country d-flex align-items-center py-1 px-2 anwp-bg-gray-100 anwp-border-bottom anwp-border-gray-200 <?php echo esc_html( $country_classes ); ?>"
					data-anwp-country="<?php echo esc_html( $country_data['country_code'] ); ?>">

					<?php if ( in_array( $data->show_flag, [ 'big', 'small' ], true ) ) : ?>
						<div class="anwp-w-30 options__flag <?php echo 'small' === $data->show_flag ? 'f16 mr-n1' : 'f32 mr-2'; ?> d-flex align-items-center">
							<span class="flag <?php echo esc_attr( $country_data['country_code'] ); ?>"></span>
						</div>
					<?php endif; ?>

					<div class="competition-list__country-name flex-grow-1"><?php echo esc_html( $country_data['country_name'] ); ?></div>

					<?php if ( $is_country_collapsed ) : ?>
						<div class="px-2 mr-n2">
							<svg class="anwp-icon anwp-icon--feather competition-list__country-collapsed-icon">
								<use xlink:href="#icon-chevron-down"></use>
							</svg>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php foreach ( $country_data['items'] as $competition ) : ?>
				<div class="competition-list__competition d-flex align-items-center py-1 px-2 anwp-border-bottom anwp-border-gray-200 position-relative <?php echo esc_html( $is_country_collapsed && $competition['country'] ? 'd-none' : '' ); ?>"
					data-anwp-country="<?php echo esc_html( $competition['country'] ); ?>">

					<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_logo ) ) : ?>
						<div class="anwp-w-30 anwp-flex-none">
							<?php if ( $competition['logo'] ) : ?>
								<img src="<?php echo esc_url( $competition['logo'] ); ?>" alt="competition logo" class="anwp-object-contain anwp-w-25 anwp-h-20 m-0">
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="competition-list__competition-name flex-grow-1">
						<?php echo esc_html( in_array( $data->display, [ 'league', 'league_season' ], true ) ? $competition['league'] : $competition['title'] ); ?>
					</div>

					<?php if ( 'league_season' === $data->display ) : ?>
						<div class="competition-list__season-name ml-2 text-nowrap">
							<?php echo esc_html( $competition['season'] ); ?>
						</div>
					<?php endif; ?>

					<a href="<?php echo esc_url( $competition['link'] ); ?>" class="anwp-link-cover anwp-link-without-effects"></a>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>
