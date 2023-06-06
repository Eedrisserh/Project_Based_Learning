<?php
/**
 * The Template for displaying Cards of players or teams.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-cards--mini.php.
 *
 * @var object $data - Object with widget data.
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

$default_photo = anwp_football_leagues()->helper->get_default_player_photo();

$data = (object) wp_parse_args(
	$data,
	[
		'competition_id' => '',
		'join_secondary' => 0,
		'season_id'      => '',
		'league_id'      => '',
		'club_id'        => '',
		'type'           => 'players',
		'limit'          => 0,
		'soft_limit'     => 'yes',
		'context'        => 'shortcode',
		'show_photo'     => 'yes',
		'points_r'       => '5',
		'points_yr'      => '2',
		'hide_zero'      => 0,
		'hide_points'    => 0,
		'sort_by_point'  => '',
	]
);

// Get list of items
$items = anwp_football_leagues()->player->tmpl_get_players_cards( $data );

if ( empty( $items ) ) {
	return;
}

// Limit number of players
if ( (int) $data->limit > 0 ) {
	$items = anwp_football_leagues()->player->tmpl_limit_players( $items, $data->limit, $data->soft_limit );
}

// Prepare players cache
if ( 'players' === $data->type ) {
	$ids = wp_list_pluck( $items, 'player_id' );
	anwp_football_leagues()->player->set_players_cache( $ids );
}

/**
 * Add option to hide points column.
 *
 * @since 0.9.0
 */
$hide_points = anwp_football_leagues()->helper->string_to_bool( $data->hide_points );
?>
<div class="anwp-b-wrap">
	<table class="align-middle table table-sm small table-bordered player-list layout--mini card-list--<?php echo esc_attr( $data->type ); ?> context--<?php echo esc_attr( $data->context ); ?>">

		<tbody>
		<tr class="anwp-bg-light">
			<th class="anwp-text-center"><?php echo esc_html( AnWPFL_Text::get_value( 'cards__shortcode__n', _x( '#', 'Rank', 'anwp-football-leagues' ) ) ); ?></th>
			<th width="80%"><?php echo esc_html( 'clubs' === $data->type ? AnWPFL_Text::get_value( 'cards__shortcode__clubs', __( 'Clubs', 'anwp-football-leagues' ) ) : AnWPFL_Text::get_value( 'cards__shortcode__player', __( 'Player', 'anwp-football-leagues' ) ) ); ?></th>
			<th class="anwp-text-center px-1">
				<svg class="icon__card">
					<use xlink:href="#icon-card_y"></use>
				</svg>
			</th>
			<th class="anwp-text-center px-1">
				<svg class="icon__card">
					<use xlink:href="#icon-card_yr"></use>
				</svg>
			</th>
			<th class="anwp-text-center px-1">
				<svg class="icon__card">
					<use xlink:href="#icon-card_r"></use>
				</svg>
			</th>

			<?php if ( ! $hide_points ) : ?>
				<th class="anwp-text-center px-1"><?php echo esc_html( AnWPFL_Text::get_value( 'cards__shortcode__pts', _x( 'Pts', 'points', 'anwp-football-leagues' ) ) ); ?></th>
			<?php endif; ?>
		</tr>
		</tbody>

		<tbody>
		<?php foreach ( $items as $index => $p ) : ?>
			<tr class="anwp-text-center">
				<td class="player-list__rank align-middle"><?php echo intval( $index + 1 ); ?></td>
				<td class="text-left pl-1 py-1 text-truncate anwp-max-width-1">
					<?php
					if ( 'players' === $data->type ) :

						// Get player data
						$player = anwp_football_leagues()->player->get_player( $p->player_id );

						$clubs = explode( ',', $p->clubs );
						?>
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
									<div class="d-inline-block player-list__club"><?php echo esc_html( $ii > 0 ? ' | ' : '' ); ?>
										<a class="club__link anwp-link align-middle" title="<?php echo esc_attr( anwp_football_leagues()->club->get_club_title_by_id( $club ) ); ?>"
											href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $club ) ); ?>">
											<?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $club ) ); ?>
										</a>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php elseif ( 'clubs' === $data->type ) : ?>
						<div class="d-flex align-items-center text-truncate">
							<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->show_photo ) ) : ?>
								<div class="club-logo__cover club-logo__cover--small mr-1"
									style="background-image: url('<?php echo esc_url( anwp_football_leagues()->club->get_club_logo_by_id( $p->club_id, true ) ); ?>')"></div>
							<?php endif; ?>
							<a class="club__link anwp-link text-truncate align-middle" title="<?php echo esc_attr( anwp_football_leagues()->club->get_club_title_by_id( $p->club_id ) ); ?>"
								href="<?php echo esc_url( anwp_football_leagues()->club->get_club_link_by_id( $p->club_id ) ); ?>">
								<?php echo esc_html( anwp_football_leagues()->club->get_club_title_by_id( $p->club_id ) ); ?>
							</a>
						</div>
					<?php endif; ?>
				</td>
				<td class="player-list__stat align-middle"><?php echo (int) $p->cards_y; ?></td>
				<td class="player-list__stat align-middle"><?php echo (int) $p->cards_yr; ?></td>
				<td class="player-list__stat align-middle"><?php echo (int) $p->cards_r; ?></td>

				<?php if ( ! $hide_points ) : ?>
					<td class="player-list__stat align-middle"><?php echo (int) $p->countable; ?></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
