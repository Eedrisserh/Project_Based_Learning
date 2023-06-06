<?php
/**
 * The Template for displaying Player >> Birthday Card.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-birthday-card.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.10.19
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'ID'            => '',
		'current_club'  => '',
		'date_of_birth' => '',
		'post_title'    => '',
		'post_type'     => '',
		'photo'         => '',
		'permalink'     => true,
	]
);

if ( ! anwp_football_leagues()->helper->validate_date( $data->date_of_birth, 'Y-m-d' ) ) {
	return;
}

$default_photo = anwp_football_leagues()->helper->get_default_player_photo();

if ( 'anwp_staff' === $data->post_type ) {
	$position = get_post_meta( $data->ID, '_anwpfl_job_title', true );
} else {
	$position = anwp_football_leagues()->player->get_translated_position( $data->ID );
}

$birth_date_obj = DateTime::createFromFormat( 'Y-m-d', $data->date_of_birth );
$diff_date_obj  = DateTime::createFromFormat( 'Y-m-d', date( 'Y' ) . '-' . date( 'm-d', strtotime( $data->date_of_birth ) ) );
$age            = $birth_date_obj->diff( $diff_date_obj )->y;
?>
<div class="player__birthday-card player-birthday-card border">
	<div class="d-flex">
		<div class="position-relative player-birthday-card__photo-wrapper anwp-text-center d-flex align-items-center">
			<img class="player-birthday-card__photo" src="<?php echo esc_url( $data->photo ?: $default_photo ); ?>" alt="player photo">
		</div>
		<div class="d-flex flex-column flex-grow-1 player-birthday-card__meta py-2 pl-1">
			<div class="player-birthday-card__name mb-1"><?php echo esc_html( $data->player_name ); ?></div>

			<div class="player-birthday-card__position"><?php echo esc_html( $position ); ?></div>
			<?php
			if ( absint( $data->current_club ) ) :

				$club_title = anwp_football_leagues()->club->get_club_abbr_by_id( $data->current_club );
				$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $data->current_club );
				?>
				<div class="player-birthday-card__club-wrapper d-flex align-items-center">
					<?php if ( $club_logo ) : ?>
						<span class="club-logo__cover club-logo__cover--mini mr-1 align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></span>
					<?php endif; ?>
					<?php echo esc_html( $club_title ); ?>
				</div>
			<?php endif; ?>

			<div class="player-birthday-card__date-wrapper d-flex align-items-end">
				<div class="player-birthday-card__date d-flex align-items-center">
					<svg class="anwp-icon anwp-icon--octi mr-1">
						<use xlink:href="#icon-calendar"></use>
					</svg>
					<span class="player-birthday-card__date-text"><?php echo esc_html( date_i18n( 'M d', get_date_from_gmt( $data->date_of_birth, 'U' ) ) ); ?></span>
				</div>
				<div class="player-birthday-card__years ml-auto">
					<?php echo esc_html( AnWPFL_Text::get_value( 'player__birthday__years', __( 'years', 'anwp-football-leagues' ) ) ); ?>
				</div>
				<div class="player-birthday-card__age px-1 mt-n1"><?php echo absint( $age ); ?></div>
			</div>
		</div>
	</div>
</div>
