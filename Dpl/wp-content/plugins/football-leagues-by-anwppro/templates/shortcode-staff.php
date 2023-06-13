<?php
/**
 * The Template for displaying Staff.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-staff.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.12.4
 *
 * @version       0.12.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data = (object) wp_parse_args(
	$data,
	[
		'staff_id'          => '',
		'options_text'      => '',
		'context'           => 'shortcode',
		'profile_link'      => 'yes',
		'profile_link_text' => 'profile',
		'show_club'         => 0,
		'show_job'          => 0,
	]
);

if ( empty( $data->staff_id ) ) {
	return;
}

// Check player exists
$staff = get_post( $data->staff_id );

if ( empty( $staff->post_type ) || ! in_array( $staff->post_type, [ 'anwp_staff' ], true ) ) {
	return;
}

// Nationality
$nationality = maybe_unserialize( get_post_meta( $staff->ID, '_anwpfl_nationality', true ) );

$photo_id  = get_post_meta( $staff->ID, '_anwpfl_photo_id', true );
$club_id   = get_post_meta( $staff->ID, '_anwpfl_current_club', true );
$job_title = anwp_football_leagues()->helper->string_to_bool( $data->show_job ) ? get_post_meta( $staff->ID, '_anwpfl_job_title', true ) : '';
?>
<div class="anwp-b-wrap">
	<div class="player-block context--<?php echo esc_attr( $data->context ); ?> border">
		<div class="d-flex align-items-center p-2 anwp-bg-light player-block__header">
			<?php if ( $photo_id ) : ?>
				<?php echo wp_get_attachment_image( $photo_id, 'medium', false, [ 'class' => 'anwp-w-100 anwp-h-100 anwp-object-contain m-0' ] ); ?>
			<?php endif; ?>
			<div class="flex-grow-1">
				<div class="player-block__name text-uppercase px-3 anwp-text-xl anwp-leading-1-25 anwp-font-medium pl-3"><?php echo esc_html( $staff->post_title ); ?></div>

				<?php if ( ( ! empty( $nationality ) && is_array( $nationality ) ) || $job_title ) : ?>
					<div class="player-block__extra d-flex flex-wrap align-items-center mt-2 pl-3">
						<?php if ( ! empty( $nationality ) && is_array( $nationality ) ) : ?>
							<?php foreach ( $nationality as $country_code ) : ?>
								<span class="options__flag f32 mr-3" data-toggle="anwp-tooltip"
									data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
							<?php endforeach; ?>
						<?php endif; ?>
						<span><?php echo esc_html( $job_title ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="player-block__options">

			<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_club ) && $club_id ) : ?>
				<div class="player-block__option d-flex align-items-center border-top">
					<div class="player-block__option-label flex-grow-1"><?php echo esc_html( AnWPFL_Text::get_value( 'player__shortcode__club', __( 'Club', 'anwp-football-leagues' ) ) ); ?></div>
					<div class="player-block__option-value player-block__option-value--wide px-1">
						<?php
						$club_title = anwp_football_leagues()->club->get_club_title_by_id( $club_id );
						$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $club_id );
						$club_link  = anwp_football_leagues()->club->get_club_link_by_id( $club_id );

						if ( $club_logo ) :
							?>
							<span class="club-logo__cover club-logo__cover--small mr-1 align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></span>
						<?php endif; ?>

						<a class="club__link anwp-link align-middle" href="<?php echo esc_url( $club_link ); ?>">
							<?php echo esc_html( $club_title ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( trim( $data->options_text ) ) : ?>

				<?php
				$staff_options = explode( '|', $data->options_text );

				foreach ( $staff_options as $staff_option ) :

					if ( ! trim( $staff_option ) ) {
						continue;
					}

					if ( false === mb_strpos( $staff_option, ':' ) ) {
						continue;
					}

					list( $label, $value ) = explode( ':', $staff_option );
					?>
					<div class="player-block__option d-flex align-items-center border-top">
						<div class="player-block__option-label flex-grow-1"><?php echo esc_html( trim( $label ) ); ?></div>
						<div class="player-block__option-value border-left"><?php echo esc_html( trim( $value ) ); ?></div>
					</div>
				<?php endforeach; ?>

			<?php endif; ?>
		</div>

		<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->profile_link ) ) : ?>
			<div class="player-block__profile-link border-top p-2">
				<a href="<?php echo esc_url( get_permalink( $staff ) ); ?>" class="btn btn-outline-secondary w-100 anwp-text-center"><?php echo esc_html( $data->profile_link_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</div>
