<?php
/**
 * The Template for displaying Player >> Header Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/player/player-header.php.
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.8.3
 *
 * @version       0.13.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parse template data
$data = (object) wp_parse_args(
	$data,
	[
		'position_code' => '',
		'player_id'     => '',
		'club_id'       => '',
		'club_title'    => '',
		'club_link'     => '',
		'twitter'       => '',
		'youtube'       => '',
		'facebook'      => '',
		'instagram'     => '',
		'linkedin'      => '',
		'tiktok'        => '',
		'vk'            => '',
	]
);

// Populate Player data
$player                   = (object) [];
$player->photo_id         = get_post_meta( $data->player_id, '_anwpfl_photo_id', true );
$player->weight           = get_post_meta( $data->player_id, '_anwpfl_weight', true );
$player->position         = anwp_football_leagues()->data->get_value_by_key( $data->position_code, 'position' );
$player->height           = get_post_meta( $data->player_id, '_anwpfl_height', true );
$player->place_of_birth   = get_post_meta( $data->player_id, '_anwpfl_place_of_birth', true );
$player->country_of_birth = get_post_meta( $data->player_id, '_anwpfl_country_of_birth', true );
$player->nationality      = maybe_unserialize( get_post_meta( $data->player_id, '_anwpfl_nationality', true ) );
$player->birth_date       = get_post_meta( $data->player_id, '_anwpfl_date_of_birth', true );
$player->death_date       = get_post_meta( $data->player_id, '_anwpfl_date_of_death', true );
$player->full_name        = get_post_meta( $data->player_id, '_anwpfl_full_name', true );
$player->club_id          = get_post_meta( $data->player_id, '_anwpfl_current_club', true );
$player->national_team    = get_post_meta( $data->player_id, '_anwpfl_national_team', true );

// Socials
$player->twitter   = get_post_meta( $data->player_id, '_anwpfl_twitter', true );
$player->youtube   = get_post_meta( $data->player_id, '_anwpfl_youtube', true );
$player->facebook  = get_post_meta( $data->player_id, '_anwpfl_facebook', true );
$player->instagram = get_post_meta( $data->player_id, '_anwpfl_instagram', true );
$player->vk        = get_post_meta( $data->player_id, '_anwpfl_vk', true );
$player->linkedin  = get_post_meta( $data->player_id, '_anwpfl_linkedin', true );
$player->tiktok    = get_post_meta( $data->player_id, '_anwpfl_tiktok', true );

// Check position translation
$translated_position = '';

switch ( $data->position_code ) {
	case 'g':
		$translated_position = anwp_football_leagues()->get_option_value( 'text_single_goalkeeper' );
		break;
	case 'd':
		$translated_position = anwp_football_leagues()->get_option_value( 'text_single_defender' );
		break;
	case 'm':
		$translated_position = anwp_football_leagues()->get_option_value( 'text_single_midfielder' );
		break;
	case 'f':
		$translated_position = anwp_football_leagues()->get_option_value( 'text_single_forward' );
		break;
}

if ( $translated_position ) {
	$player->position = $translated_position;
}

/**
 * Hook: anwpfl/tmpl-player/before_header
 *
 * @since 0.8.3
 *
 * @param object $player
 * @param object $data
 */
do_action( 'anwpfl/tmpl-player/before_header', $player, $data );
?>
<div class="player__header player-section anwp-section">
	<div class="anwp-row">

		<?php
		if ( $player->photo_id ) :
			$caption = wp_get_attachment_caption( $player->photo_id );

			$render_main_photo_caption = 'hide' !== AnWPFL_Options::get_value( 'player_render_main_photo_caption' );

			/**
			 * Rendering player main photo caption.
			 *
			 * @since 0.7.5
			 *
			 * @param string $render_main_photo_caption
			 * @param int    $data->player_id
			 */
			$render_main_photo_caption = apply_filters( 'anwpfl/tmpl-player/render_main_photo_caption', $render_main_photo_caption, $data->player_id );

			?>
			<div class="anwp-col-sm-auto">
				<?php echo wp_get_attachment_image( $player->photo_id, 'medium', false, [ 'class' => 'player__main-photo' ] ); ?>
				<?php if ( $render_main_photo_caption && $caption ) : ?>
					<div class="mt-1 player__main-photo-caption text-muted"><?php echo esc_html( $caption ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="anwp-col-sm">
			<table class="table bg-light table-bordered table-sm options-list mb-4">
				<tbody>

				<?php if ( $player->full_name ) : ?>
					<tr data-fl-option="position">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__full_name', __( 'Full Name', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $player->full_name ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->position ) : ?>
					<tr data-fl-option="position">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__position', __( 'Position', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $player->position ); ?></td>
					</tr>
				<?php endif; ?>

				<?php
				if ( $player->national_team && anwp_football_leagues()->club->get_club_title_by_id( $player->national_team ) && anwp_football_leagues()->club->is_national_team( $player->national_team ) ) :
					$club_logo = anwp_football_leagues()->club->get_club_logo_by_id( $player->national_team );
					?>
					<tr data-fl-option="national_team_title">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__national_team', __( 'National Team', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<div class="d-flex align-items-center">
								<?php if ( $club_logo ) : ?>
									<img class="anwp-w-30 anwp-h-30 mr-2 anwp-object-contain" src="<?php echo esc_attr( $club_logo ); ?>" alt="club logo">
								<?php endif; ?>
								<a class="anwp-leading-1-25" href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $player->national_team ) ); ?>"><?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $player->national_team ) ); ?></a>
							</div>
						</td>
					</tr>
				<?php endif; ?>

				<?php
				if ( $player->club_id && anwp_football_leagues()->club->get_club_title_by_id( $player->club_id ) ) :
					$club_logo = anwp_football_leagues()->club->get_club_logo_by_id( $player->club_id );
					?>
					<tr data-fl-option="club_title">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__current_club', __( 'Current Club', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<div class="d-flex align-items-center">
								<?php if ( $club_logo ) : ?>
									<img class="anwp-w-30 anwp-h-30 mr-2 anwp-object-contain" src="<?php echo esc_attr( $club_logo ); ?>" alt="club logo">
								<?php endif; ?>
								<a class="anwp-leading-1-25" href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $player->club_id ) ); ?>"><?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $player->club_id ) ); ?></a>
							</div>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->nationality && is_array( $player->nationality ) ) : ?>
					<tr data-fl-option="nationality">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value py-0">
							<?php foreach ( $player->nationality as $country_code ) : ?>
								<span class="options__flag f32" data-toggle="anwp-tooltip"
									data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
							<?php endforeach; ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->place_of_birth ) : ?>
					<tr data-fl-option="place_of_birth">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__place_of_birth', __( 'Place Of Birth', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<div class="d-flex align-items-center">
								<?php echo esc_html( $player->place_of_birth ); ?>
								<?php if ( $player->country_of_birth ) : ?>
									<span class="options__flag f32 ml-2" data-toggle="anwp-tooltip"
										data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $player->country_of_birth, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $player->country_of_birth ); ?>"></span></span>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->birth_date ) : ?>
					<tr data-fl-option="birth_date">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__date_of_birth', __( 'Date Of Birth', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $player->birth_date ) ) ); ?></td>
					</tr>
					<?php
					if ( ! $player->death_date ) :
						$birth_date_obj = DateTime::createFromFormat( 'Y-m-d', $player->birth_date );
						$interval       = $birth_date_obj ? $birth_date_obj->diff( new DateTime() )->y : '-';
						?>
						<tr data-fl-option="age">
							<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__age', __( 'Age', 'anwp-football-leagues' ) ) ); ?></th>
							<td class="options-list__value"><?php echo esc_html( $interval ); ?></td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				if ( $player->death_date ) :

					$death_age = '';

					if ( $player->birth_date ) {
						$birth_date_obj = DateTime::createFromFormat( 'Y-m-d', $player->birth_date );
						$death_age      = $birth_date_obj ? $birth_date_obj->diff( DateTime::createFromFormat( 'Y-m-d', $player->death_date ) )->y : '-';
					}

					?>
					<tr data-fl-option="death_date">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__date_of_death', __( 'Date Of Death', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $player->death_date ) ) ); ?>
							<?php echo intval( $death_age ) ? ( ' (' . intval( $death_age ) . ')' ) : ''; ?>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->weight ) : ?>
					<tr data-fl-option="weight">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__weight_kg', __( 'Weight (kg)', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $player->weight ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $player->height ) : ?>
					<tr data-fl-option="height">
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'player__header__height_cm', __( 'Height (cm)', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value"><?php echo esc_html( $player->height ); ?></td>
					</tr>
				<?php endif; ?>

				<?php
				// Rendering custom fields
				for ( $ii = 1; $ii <= 3; $ii ++ ) :

					$custom_title = get_post_meta( $data->player_id, '_anwpfl_custom_title_' . $ii, true );
					$custom_value = get_post_meta( $data->player_id, '_anwpfl_custom_value_' . $ii, true );

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
				$custom_fields = get_post_meta( $data->player_id, '_anwpfl_custom_fields', true );

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

				<?php if ( $player->twitter || $player->facebook || $player->youtube || $player->instagram || $player->vk || $player->linkedin || $player->tiktok ) : ?>
					<tr>
						<th scope="row" class="options-list__term"><?php echo esc_html( AnWPFL_Text::get_value( 'club__header__social', __( 'Social', 'anwp-football-leagues' ) ) ); ?></th>
						<td class="options-list__value">
							<?php if ( $player->twitter ) : ?>
								<a href="<?php echo esc_url( $player->twitter ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-twitter"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->youtube ) : ?>
								<a href="<?php echo esc_url( $player->youtube ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-youtube"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->facebook ) : ?>
								<a href="<?php echo esc_url( $player->facebook ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-facebook"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->instagram ) : ?>
								<a href="<?php echo esc_url( $player->instagram ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-instagram"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->linkedin ) : ?>
								<a href="<?php echo esc_url( $player->linkedin ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-linkedin"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->tiktok ) : ?>
								<a href="<?php echo esc_url( $player->tiktok ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-tiktok"></use>
									</svg>
								</a>
							<?php endif; ?>
							<?php if ( $player->vk ) : ?>
								<a href="<?php echo esc_url( $player->vk ); ?>" class="anwp-link-without-effects ml-1 d-inline-block" target="_blank">
									<svg class="anwp-icon anwp-icon--s24">
										<use xlink:href="#icon-vk"></use>
									</svg>
								</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
