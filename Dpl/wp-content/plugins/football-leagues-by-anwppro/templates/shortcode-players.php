<?php
/**
 * The Template for displaying Players.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-players.php.
 *
 * @var object $data - Object with widget data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.5.1
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$default_photo = anwp_football_leagues()->helper->get_default_player_photo();

$data = (object) wp_parse_args(
	$data,
	[
		'competition_id' => '',
		'join_secondary' => 0,
		'season_id'      => '',
		'league_id'      => '',
		'club_id'        => '',
		'type'           => 'scorers',
		'limit'          => 0,
		'soft_limit'     => 'yes',
		'context'        => 'shortcode',
		'show_photo'     => 'yes',
		'compact'        => false,
	]
);

// Get players
$players = anwp_football_leagues()->player->tmpl_get_players_by_type( $data );

if ( empty( $players ) ) {
	return;
}

// Limit number of players
if ( 0 < (int) $data->limit ) {
	$players = anwp_football_leagues()->player->tmpl_limit_players( $players, $data->limit, $data->soft_limit );
}

// Prepare players cache
$ids = wp_list_pluck( $players, 'player_id' );
anwp_football_leagues()->player->set_players_cache( $ids );

// Compact layout
$compact_layout = anwp_football_leagues()->helper->string_to_bool( $data->compact );

// Stats name
$stats_name = 'scorers' === $data->type ? AnWPFL_Text::get_value( 'players__shortcode__goals', __( 'Goals', 'anwp-football-leagues' ) ) : AnWPFL_Text::get_value( 'players__shortcode__assists', __( 'Assists', 'anwp-football-leagues' ) );
?>
<div class="anwp-b-wrap">
	<div class="table-responsive">
		<table class="table table-bordered player-list player-list--<?php echo esc_attr( $data->type ); ?> context--<?php echo esc_attr( $data->context ); ?>">

			<tbody>
			<tr class="anwp-bg-light">
				<th class="anwp-text-center"><span class="d-none d-sm-inline-block"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__rank', __( 'Rank', 'anwp-football-leagues' ) ) ); ?></span><span class="d-sm-none">#</span></th>
				<th class="w-100"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__player', __( 'Player', 'anwp-football-leagues' ) ) ); ?></th>

				<?php if ( ! $compact_layout ) : ?>
					<th class="d-none d-lg-table-cell"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__club', __( 'Club', 'anwp-football-leagues' ) ) ); ?></th>
					<th class="d-none d-lg-table-cell"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__nationality', __( 'Nationality', 'anwp-football-leagues' ) ) ); ?></th>
				<?php endif; ?>

				<th><?php echo esc_html( $stats_name ); ?></th>
			</tr>
			</tbody>

			<tbody>
			<?php
			foreach ( $players as $index => $p ) :
				$player = anwp_football_leagues()->player->get_player( $p->player_id );
				$clubs  = explode( ',', $p->clubs );
				?>
				<tr class="anwp-text-center">
					<td class="player-list__rank align-middle text-nowrap"><?php echo intval( $index + 1 ); ?></td>
					<td class="text-left">
						<div class="d-flex">
							<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_photo ) ) : ?>
								<div class="player__photo-wrapper player__photo-wrapper--list anwp-text-center mr-2 d-none d-sm-inline-block">
									<img class="player__photo mx-auto" src="<?php echo esc_url( $player->photo ?: $default_photo ); ?>">
								</div>
							<?php endif; ?>
							<div class="d-flex flex-column">
								<div>
									<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
									<?php if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) : ?>
										<?php foreach ( $player->nationality as $country_code ) : ?>
											<span class="options__flag f16 mx-2 py-n1 <?php echo esc_attr( $compact_layout ? '' : 'd-lg-none' ); ?>" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
								<div class="<?php echo esc_attr( $compact_layout ? '' : 'd-lg-none' ); ?>">
									<?php
									foreach ( $clubs as $ii => $club_id ) :
										$club_title = anwp_football_leagues()->club->get_club_abbr_by_id( $club_id );
										$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $club_id );
										?>
										<div class="player-list__club">
											<?php if ( $club_logo ) : ?>
												<span class="club-logo__cover club-logo__cover--mini mr-1 align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></span>&nbsp;<span class="text-muted small"><?php echo esc_html( $club_title ); ?></span>
											<?php else : ?>
												<span class="text-muted small"><?php echo esc_html( $club_title ); ?></span>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</td>

					<?php if ( ! $compact_layout ) : ?>
						<td class="text-left align-middle d-none d-lg-table-cell">
							<?php
							foreach ( $clubs as $ii => $club_id ) :
								$club_title = anwp_football_leagues()->club->get_club_abbr_by_id( $club_id );
								$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $club_id );
								?>
								<div class="player-list__club">
									<?php if ( $club_logo ) : ?>
										<span class="club-logo__cover club-logo__cover--mini mr-1 align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></span>&nbsp;<span class="text-muted small"><?php echo esc_html( $club_title ); ?></span>
									<?php else : ?>
										<span class="text-muted small"><?php echo esc_html( $club_title ); ?></span>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</td>
						<td class="player-list__nationality align-middle d-none d-lg-table-cell">
							<?php if ( ! empty( $player->nationality ) && is_array( $player->nationality ) ) : ?>
								<?php foreach ( $player->nationality as $country_code ) : ?>
									<span class="options__flag f32" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>"><span class="flag <?php echo esc_attr( $country_code ); ?>"></span></span>
								<?php endforeach; ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>

					<td class="player-list__stat align-middle text-nowrap"><?php echo (int) $p->countable; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
