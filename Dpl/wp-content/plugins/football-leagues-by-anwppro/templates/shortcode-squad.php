<?php
/**
 * The Template for displaying Club Squad.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-squad.php.
 *
 * @var object $data - Object with shortcode data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.5.0
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check required params
if ( empty( $data->club_id ) || empty( $data->season_id ) ) {
	return;
}

// Prevent errors with new params
$data = (object) wp_parse_args(
	$data,
	[
		'class'     => 'mt-4',
		'season_id' => '',
		'club_id'   => '',
		'header'    => true,
	]
);

// Prepare squad
$squad = anwp_football_leagues()->club->tmpl_prepare_club_squad( $data->club_id, $data->season_id, true );

// Prepare staff
$staff = anwp_football_leagues()->club->tmpl_prepare_club_staff( $data->club_id, $data->season_id );

// Initialize staff groups
$staff_group_attached = '';

// Default photo
$default_photo = anwp_football_leagues()->helper->get_default_player_photo();

// Prepare positions
$positions      = anwp_football_leagues()->data->get_positions_plural();
$positions_l10n = [
	'g' => anwp_football_leagues()->get_option_value( 'text_multiple_goalkeeper' ) ?: $positions['g'],
	'd' => anwp_football_leagues()->get_option_value( 'text_multiple_defender' ) ?: $positions['d'],
	'm' => anwp_football_leagues()->get_option_value( 'text_multiple_midfielder' ) ?: $positions['m'],
	'f' => anwp_football_leagues()->get_option_value( 'text_multiple_forward' ) ?: $positions['f'],
];
?>
<div class="anwp-b-wrap squad squad--shortcode <?php echo esc_attr( $data->class ); ?>">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header__wrapper d-flex justify-content-between">
			<div class="anwp-block-header"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__squad', __( 'Squad', 'anwp-football-leagues' ) ) ); ?></div>
			<?php if ( 'hide' !== $data->season_dropdown ) : ?>
				<?php
				$dropdown_filter = [
					'context' => 'club',
					'id'      => $data->club_id,
				];

				anwp_football_leagues()->helper->season_dropdown( $data->season_id, true, '', $dropdown_filter );
				?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( empty( $squad ) ) : ?>
		<div class="my-3 alert alert-info"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__no_players_in_the_squad', __( 'No players in the squad', 'anwp-football-leagues' ) ) ); ?></div>
	<?php else : ?>
		<div class="table-responsive">
			<table class="club__squad club__squad--table mb-0">
				<tbody>
				<?php foreach ( $positions_l10n as $loop_key => $loop_title ) : ?>

					<tr class="anwp-bg-light text-dark">
						<td class="anwp-text-center px-0 align-middle">##</td>
						<td class="px-2 text-uppercase" colspan="4"><?php echo esc_html( $loop_title ); ?></td>
					</tr>

					<?php
					foreach ( $squad as $player_id => $player ) :
						if ( $player['position'] !== $loop_key ) {
							continue;
						}

						// Check player status. Do not show players "on trial" or "left"
						if ( in_array( $player['status'], [ 'left', 'on trial' ], true ) ) {
							continue;
						}
						?>
						<tr class="">
							<td class="anwp-bg-secondary text-white anwp-text-center py-0 px-2 align-middle club__player-number">
								<?php echo (int) $player['number'] ? (int) $player['number'] : ''; ?>
							</td>
							<td class="position-relative club__player-photo-wrapper anwp-text-center">
								<img class="club__player-photo" src="<?php echo esc_url( $player['photo'] ?: $default_photo ); ?>">
								<?php if ( 'on loan' === $player['status'] ) : ?>
									<span class="badge badge-info font-weight-normal small text-uppercase club__player-status-badge"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__on_loan', __( 'On Loan', 'anwp-football-leagues' ) ) ); ?></span>
								<?php endif; ?>
							</td>
							<td class="align-middle">
								<h5 class="card-title club__player-name mb-0 ml-1">
									<a href="<?php echo esc_url( get_permalink( $player_id ) ); ?>" class="club__player-link anwp-link-without-effects">
										<?php echo esc_html( $player['name'] ); ?>
									</a>
								</h5>
							</td>
							<td class="px-2">
								<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__age', __( 'Age', 'anwp-football-leagues' ) ) ); ?></span>
								<span class="mt-2 d-block"><?php echo esc_html( $player['age'] ?: '-' ); ?></span>
							</td>
							<td class="text-right">
								<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></span>
								<?php if ( ! empty( $player['nationality'] ) && is_array( $player['nationality'] ) ) : ?>
									<?php foreach ( $player ['nationality'] as $country_code ) : ?>
										<span class="options__flag f32" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>

				<?php
				foreach ( $staff as $staff_id => $staff_member ) :

					if ( 'no' !== $staff_member['grouping'] ) {
						continue;
					}

					if ( $staff_member['job'] !== $staff_group_attached ) :
						?>
						<tr class="anwp-bg-light text-dark">
							<td class="anwp-text-center px-0 align-middle">##</td>
							<td class="px-2 text-uppercase" colspan="4"><?php echo esc_html( $staff_member['job'] ); ?></td>
						</tr>
						<?php $staff_group_attached = $staff_member['job']; ?>
					<?php endif; ?>

					<tr>
						<td class="anwp-bg-secondary text-white anwp-text-center py-0 px-2 align-middle club__player-number"></td>
						<td class="position-relative anwp-text-center">
							<img class="club__player-photo" src="<?php echo esc_url( $staff_member['photo'] ?: $default_photo ); ?>">
						</td>
						<td class="align-middle">
							<h5 class="card-title club__player-name mb-0 ml-1">
								<a href="<?php echo esc_url( get_permalink( $staff_id ) ); ?>" class="club__player-link anwp-link-without-effects">
									<?php echo esc_html( $staff_member['name'] ); ?>
								</a>
							</h5>
						</td>
						<td class="px-2">
							<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__age', __( 'Age', 'anwp-football-leagues' ) ) ); ?></span>
							<span class="mt-2 d-block"><?php echo esc_html( $staff_member['age'] ?: '-' ); ?></span>
						</td>
						<td class="text-right">
							<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></span>
							<?php if ( ! empty( $staff_member['nationality'] ) && is_array( $staff_member['nationality'] ) ) : ?>
								<?php foreach ( $staff_member ['nationality'] as $country_code ) : ?>
									<span class="options__flag f32" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
								<?php endforeach; ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

	<?php
	$staff_data = [];

	// Prepare staff data
	foreach ( $staff as $staff_id => $staff_member ) :
		if ( 'yes' !== $staff_member['grouping'] ) {
			continue;
		}

		$staff_data[ $staff_member['group'] ][ $staff_id ] = $staff_member;
	endforeach;

	foreach ( $staff_data as $staff_group => $staff_group_items ) :
		$staff_job = '';

		if ( $staff_group ) :
			?>
			<div class="anwp-block-header__wrapper d-flex justify-content-between mt-5">
				<div class="anwp-block-header"><?php echo esc_html( $staff_group ); ?></div>
				<?php if ( 'hide' !== $data->season_dropdown ) : ?>
					<?php
					$dropdown_filter = [
						'context' => 'club',
						'id'      => $data->club_id,
					];

					anwp_football_leagues()->helper->season_dropdown( $data->season_id, true, '', $dropdown_filter );
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="table-responsive">
			<table class="club__squad club__squad--table w-100">
				<tbody>
				<?php foreach ( $staff_group_items as $staff_group_item_id => $staff_group_item ) : ?>
					<?php if ( $staff_group_item['job'] !== $staff_job ) : ?>
						<tr class="anwp-bg-light text-dark">
							<td class="px-2 text-uppercase" colspan="4"><?php echo esc_html( $staff_group_item['job'] ); ?></td>
						</tr>
						<?php $staff_job = $staff_group_item['job']; ?>
					<?php endif; ?>

					<tr>
						<td class="position-relative club__player-photo-wrapper anwp-text-center">
							<img class="club__player-photo" src="<?php echo esc_url( $staff_group_item['photo'] ?: $default_photo ); ?>">
						</td>
						<td class="align-middle w-75">
							<h5 class="card-title club__player-name mb-0 ml-1">
								<a href="<?php echo esc_url( get_permalink( $staff_group_item_id ) ); ?>" class="club__player-link anwp-link-without-effects">
									<?php echo esc_html( $staff_group_item['name'] ); ?>
								</a>
							</h5>
						</td>
						<td class="px-2">
							<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__age', __( 'Age', 'anwp-football-leagues' ) ) ); ?></span>
							<span class="mt-2 d-block"><?php echo esc_html( $staff_group_item['age'] ?: '-' ); ?></span>
						</td>
						<td class="text-right">
							<span class="club__player-param-name d-block"><?php echo esc_html( AnWPFL_Text::get_value( 'squad__shortcode__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></span>
							<?php if ( ! empty( $staff_group_item['nationality'] ) && is_array( $staff_group_item['nationality'] ) ) : ?>
								<?php foreach ( $staff_group_item ['nationality'] as $country_code ) : ?>
									<span class="options__flag f32" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
								<?php endforeach; ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endforeach; ?>
</div>
