<?php
/**
 * The Template for displaying Players.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-players--mini.php.
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
	]
);

// Get players
$players = anwp_football_leagues()->player->tmpl_get_players_by_type( $data );

if ( empty( $players ) ) {
	return;
}

// Limit number of players
if ( (int) $data->limit > 0 ) {
	$players = anwp_football_leagues()->player->tmpl_limit_players( $players, $data->limit, $data->soft_limit );
}

// Prepare players cache
$ids = wp_list_pluck( $players, 'player_id' );
anwp_football_leagues()->player->set_players_cache( $ids );

// Stats name
$stats_name = 'scorers' === $data->type ? AnWPFL_Text::get_value( 'players__shortcode__goals', __( 'Goals', 'anwp-football-leagues' ) ) : AnWPFL_Text::get_value( 'players__shortcode__assists', __( 'Assists', 'anwp-football-leagues' ) )
?>
<div class="anwp-b-wrap">
	<table class="table table-sm small table-bordered player-list layout--mini player-list--<?php echo esc_attr( $data->type ); ?> context--<?php echo esc_attr( $data->context ); ?>">

		<tbody>
		<tr class="anwp-bg-light">
			<th class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__rank_n', _x( '#', 'Rank', 'anwp-football-leagues' ) ) ); ?></th>
			<th width="90%"><?php echo esc_html( AnWPFL_Text::get_value( 'players__shortcode__player', __( 'Player', 'anwp-football-leagues' ) ) ); ?></th>
			<th class="anwp-text-center"><?php echo esc_html( $stats_name ); ?></th>
		</tr>
		</tbody>

		<tbody>
		<?php
		foreach ( $players as $index => $p ) :

			// Get player data
			$player = anwp_football_leagues()->player->get_player( $p->player_id );

			$clubs = explode( ',', $p->clubs );
			?>
			<tr class="anwp-text-center">
				<td class="player-list__rank text-nowrap"><?php echo intval( $index + 1 ); ?></td>
				<td class="text-left text-truncate anwp-max-width-1">
					<div class="d-flex align-items-center">
						<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_photo ) ) : ?>
							<div class="player__photo-wrapper player__photo-wrapper--list anwp-text-center mr-1">
								<img class="player__photo mx-auto" src="<?php echo esc_url( $player->photo ?: $default_photo ); ?>">
							</div>
						<?php endif; ?>
						<div class="text-truncate">
							<a class="anwp-link d-block text-truncate" title="<?php echo esc_attr( $player->name ); ?>"
								href="<?php echo esc_url( $player->link ); ?>"><?php echo esc_html( $player->name_short ); ?></a>
							<?php foreach ( $clubs as $ii => $club ) : ?>
								<div class="player-list__club">
									<?php echo esc_html( $ii > 0 ? ' | ' : '' ); ?>
									<a class="club__link anwp-link align-middle" href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $club ) ); ?>">
										<?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $club ) ); ?>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</td>
				<td class="player-list__stat text-nowrap"><?php echo (int) $p->countable; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
