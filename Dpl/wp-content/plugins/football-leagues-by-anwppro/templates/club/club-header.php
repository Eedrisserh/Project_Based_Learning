<?php
/**
 * The Template for displaying Club >> Header Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/club/club-header.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.4
 *
 * @version       0.13.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'logo_big'    => '',
		'club_id'     => '',
		'city'        => '',
		'nationality' => '',
		'address'     => '',
		'website'     => '',
		'founded'     => '',
		'stadium'     => '',
		'club_kit'    => '',
		'twitter'     => '',
		'youtube'     => '',
		'facebook'    => '',
		'instagram'   => '',
		'vk'          => '',
		'tiktok'      => '',
		'linkedin'    => '',
	]
);

$club = get_post( $data->club_id );

/**
 * Hook: anwpfl/tmpl-club/before_header
 *
 * @since 0.8.4
 *
 * @param object $data
 */
do_action( 'anwpfl/tmpl-club/before_header', $data );
?>
<div class="club__header club-section anwp-section">
	<div class="anwp-row mb-4">

		<?php if ( $data->logo_big ) : ?>
			<div class="anwp-col-sm-auto">
				<img class="club__main-logo" src="<?php echo esc_attr( $data->logo_big ); ?>" alt="<?php echo get_post_meta( $club->_anwpfl_logo_big_id, '_wp_attachment_image_alt', true ) ?: 'club logo'; ?>">
			</div>
		<?php endif; ?>

		<div class="anwp-col-sm">
			<table class="table bg-light table-bordered table-sm options-list mb-4">
				<tbody>

				<?php
				/**
				 * Hook: anwpfl/tmpl-club/fields_top
				 *
				 * @since 0.7.5
				 *
				 * @param WP_Post $club
				 * @param array   $data
				 */
				do_action( 'anwpfl/tmpl-club/fields_top', $club, $data );
				?>

				<?php if ( $data->city ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__city', __( 'City', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $data->city ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->nationality ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__country', __( 'Country', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value py-0">
							<span class="options__flag f32" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $data->nationality, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $data->nationality ); ?>"></span></span>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->address ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__address', __( 'Address', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $data->address ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->website ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__website', __( 'Website', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><a target="_blank" rel="nofollow" href="<?php echo esc_attr( $data->website ); ?>">
								<?php echo esc_html( trim( str_replace( [ 'http://', 'https://' ], '', $data->website ), '/' ) ); ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->founded ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__founded', __( 'Founded', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $data->founded ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->stadium ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__stadium', __( 'Stadium', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<a href="<?php echo esc_url( get_permalink( (int) $data->stadium ) ); ?>"><?php echo esc_html( get_the_title( (int) $data->stadium ) ); ?></a>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $data->club_kit ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__club_kit', __( 'Club Kit', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<img class="club__kit-photo" src="<?php echo esc_attr( $data->club_kit ); ?>" alt="club kit photo">
						</td>
					</tr>
				<?php endif; ?>

				<?php
				// Rendering custom fields
				for ( $ii = 1; $ii <= 3; $ii ++ ) :

					$custom_title = get_post_meta( $data->club_id, '_anwpfl_custom_title_' . $ii, true );
					$custom_value = get_post_meta( $data->club_id, '_anwpfl_custom_value_' . $ii, true );

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
				$custom_fields = get_post_meta( $data->club_id, '_anwpfl_custom_fields', true );

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
				?>

				<?php if ( $data->twitter || $data->facebook || $data->youtube || $data->instagram || $data->vk || $data->linkedin || $data->tiktok ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__social', __( 'Social', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<?php if ( $data->twitter ) : ?>
								<a href="<?php echo esc_url( $data->twitter ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-twitter"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->youtube ) : ?>
								<a href="<?php echo esc_url( $data->youtube ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-youtube"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->facebook ) : ?>
								<a href="<?php echo esc_url( $data->facebook ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-facebook"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->instagram ) : ?>
								<a href="<?php echo esc_url( $data->instagram ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-instagram"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->vk ) : ?>
								<a href="<?php echo esc_url( $data->vk ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-vk"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->tiktok ) : ?>
								<a href="<?php echo esc_url( $data->tiktok ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-tiktok"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $data->linkedin ) : ?>
								<a href="<?php echo esc_url( $data->linkedin ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-linkedin"></use>
									</svg>
								</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php
				/**
				 * Hook: anwpfl/tmpl-club/fields_bottom
				 *
				 * @since 0.7.5
				 *
				 * @param WP_Post $club
				 * @param array   $data
				 */
				do_action( 'anwpfl/tmpl-club/fields_bottom', $club, $data );
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
