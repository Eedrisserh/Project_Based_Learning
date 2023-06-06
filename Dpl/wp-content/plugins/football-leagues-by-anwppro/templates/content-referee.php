<?php
/**
 * The Template for displaying referee content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-referee.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.7.3
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prepare data
$staff_id       = get_the_ID();
$photo_id       = get_post_meta( $staff_id, '_anwpfl_photo_id', true );
$place_of_birth = get_post_meta( $staff_id, '_anwpfl_place_of_birth', true );
$job_title      = get_post_meta( $staff_id, '_anwpfl_job_title', true );

// Nationality
$nationality = maybe_unserialize( get_post_meta( $staff_id, '_anwpfl_nationality', true ) );

// Birth Date
$birth_date = get_post_meta( $staff_id, '_anwpfl_date_of_birth', true );

// Description
$post_content = get_post_meta( $staff_id, '_anwpfl_description', true );
/**
 * Hook: anwpfl/tmpl-referee/before_wrapper
 *
 * @since 0.7.5
 *
 * @param int $staff_id
 */
do_action( 'anwpfl/tmpl-referee/before_wrapper', $staff_id );
?>
<div class="anwp-b-wrap referee referee__inner">

	<div class="anwp-row">

		<?php
		if ( $photo_id ) :
			$caption = wp_get_attachment_caption( $photo_id );

			$render_main_photo_caption = 'hide' !== AnWPFL_Options::get_value( 'player_render_main_photo_caption' );

			/**
			 * Rendering player main photo caption.
			 *
			 * @since 0.7.5
			 *
			 * @param string $render_main_photo_caption
			 * @param int    $staff_id
			 */
			$render_main_photo_caption = apply_filters( 'anwpfl/tmpl-player/render_main_photo_caption', $render_main_photo_caption, $staff_id );

			?>
			<div class="anwp-col-sm-auto">
				<?php echo wp_get_attachment_image( $photo_id, 'medium', false, [ 'class' => 'player__main-photo' ] ); ?>
				<?php if ( $render_main_photo_caption && $caption ) : ?>
					<div class="mt-1 player__main-photo-caption text-muted"><?php echo esc_html( $caption ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="anwp-col-sm">
			<table class="table table-striped table-sm options-list mb-4">
				<tbody>

				<?php if ( $job_title ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'referee__content__job_title', __( 'Job Title', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $job_title ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $nationality && is_array( $nationality ) ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'referee__content__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value py-0">
							<?php foreach ( $nationality as $country_code ) : ?>
								<span class="options__flag f32" data-toggle="anwp-tooltip"
									data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
							<?php endforeach; ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $place_of_birth ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'referee__content__place_of_birth', __( 'Place Of Birth', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $place_of_birth ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $birth_date ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'referee__content__date_of_birth', __( 'Date Of Birth', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $birth_date ) ) ); ?></td>
					</tr>

					<?php
					$birth_date_obj = DateTime::createFromFormat( 'Y-m-d', $birth_date );
					$interval       = $birth_date_obj ? $birth_date_obj->diff( new DateTime() )->y : '-';
					?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'referee__content__age', __( 'Age', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $interval ); ?></td>
					</tr>

				<?php endif; ?>

				<?php
				// Rendering custom fields
				for ( $ii = 1; $ii <= 3; $ii ++ ) :

					$custom_title = get_post_meta( $staff_id, '_anwpfl_custom_title_' . $ii, true );
					$custom_value = get_post_meta( $staff_id, '_anwpfl_custom_value_' . $ii, true );

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
				$custom_fields = get_post_meta( $staff_id, '_anwpfl_custom_fields', true );

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

				</tbody>
			</table>
		</div>
	</div>

	<?php if ( $post_content ) : ?>
		<div class="player__description mt-5">
			<?php echo do_shortcode( wp_kses_post( wpautop( $post_content ) ) ); ?>
		</div>
	<?php endif; ?>

</div>
<?php
/**
 * Hook: anwpfl/tmpl-referee/after_wrapper
 *
 * @since 0.7.5
 *
 * @param int $staff_id
 */
do_action( 'anwpfl/tmpl-referee/after_wrapper', $staff_id );
