<?php
/**
 * The Template for displaying Standing Table Shortcode.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-standing.php
 *
 * @var object $data - Object with shortcode data.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.3.0
 *
 * @version       0.11.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check for required data
if ( empty( $data->id ) || 'anwp_standing' !== get_post_type( $data->id ) ) {
	return;
}

// Prepare data
$standing_id    = (int) $data->id;
$competition_id = get_post_meta( $standing_id, '_anwpfl_competition', true );
$group_id       = get_post_meta( $standing_id, '_anwpfl_competition_group', true );

$table        = json_decode( get_post_meta( $standing_id, '_anwpfl_table_main', true ) );
$table_colors = json_decode( get_post_meta( $standing_id, '_anwpfl_table_colors', true ) );

// Check data is valid
if ( null === $table ) {
	// something went wrong
	return;
}

// Check table colors
if ( is_object( $table_colors ) ) {
	$table_colors = (array) $table_colors;
}

// Merge with default params
$data = (object) wp_parse_args(
	$data,
	[
		'title'       => '',
		'partial'     => '',
		'bottom_link' => '',
		'link_text'   => '',
		'context'     => 'shortcode',
		'show_notes'  => 1,
	]
);

$table_notes = anwp_football_leagues()->helper->string_to_bool( $data->show_notes ) ? get_post_meta( $standing_id, '_anwpfl_table_notes', true ) : '';

/**
 * Filter: anwpfl/tmpl-standing/columns_order
 *
 * @since 0.7.5
 *
 * @param array
 * @param object  $standing_id
 * @param string  $layout
 * @param integer $competition_id
 * @param integer $group_id
 */
$columns_order = apply_filters(
	'anwpfl/tmpl-standing/columns_order',
	[ 'played', 'won', 'drawn', 'lost', 'gf', 'ga', 'gd', 'points' ],
	$standing_id,
	'',
	$competition_id,
	$group_id
);

// Prepare data
$column_header = anwp_football_leagues()->data->get_standing_headers();
$series_map    = anwp_football_leagues()->data->get_series();

$exclude_ids = [];
if ( ! empty( $data->exclude_ids ) ) {
	$exclude_ids = array_map( 'absint', explode( ',', $data->exclude_ids ) );
}

// Slice table if partial option is set
if ( $data->partial ) {
	$table = anwp_football_leagues()->standing->get_standing_partial_data( $table, $data->partial );
}
?>

<div class="anwp-b-wrap standing standing--shortcode standing__inner standing-<?php echo (int) $standing_id; ?> context--<?php echo esc_attr( $data->context ); ?>">

	<?php if ( $data->title ) : ?>
		<h4 class="standing__title"><?php echo esc_html( $data->title ); ?></h4>
	<?php endif; ?>

	<div class="standing-table anwp-text-center <?php echo esc_attr( 'yes' === AnWPFL_Options::get_value( 'standing_font_mono' ) ? 'standing-text-mono' : '' ); ?>">
		<div class="standing-table__header-row anwp-bg-light d-flex align-items-center border font-weight-bold">
			<div class="standing-table__cell">#</div>
			<div class="standing-table__cell mr-auto py-1"><?php echo esc_html( AnWPFL_Text::get_value( 'standing__shortcode__club', __( 'Club', 'anwp-football-leagues' ) ) ); ?></div>

			<?php foreach ( $columns_order as $col ) : ?>
				<?php $classes = in_array( $col, [ 'won', 'drawn', 'lost', 'gf', 'ga' ], true ) ? 'd-none d-sm-block' : ''; ?>
				<div class="standing-table__cell <?php echo esc_attr( $classes ); ?>" data-toggle="anwp-tooltip"
					data-tippy-content="<?php echo esc_html( empty( $column_header[ $col ]['tooltip'] ) ? '' : $column_header[ $col ]['tooltip'] ); ?>">
					<?php echo esc_html( empty( $column_header[ $col ]['text'] ) ? '' : $column_header[ $col ]['text'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		foreach ( $table as $row ) :

			if ( in_array( (int) $row->club_id, $exclude_ids, true ) ) {
				continue;
			}

			// Prepare Color Class
			$color_class = '';

			if ( ! empty( $table_colors[ 'p' . $row->place ] ) ) {
				$color_class = 'table-' . $table_colors[ 'p' . $row->place ];
			}

			if ( ! empty( $table_colors[ 'c' . $row->club_id ] ) ) {
				$color_class = 'table-' . $table_colors[ 'c' . $row->club_id ];
			}

			$series = str_split( substr( $row->series, - 5 ) );

			$club_title = anwp_football_leagues()->club->get_club_title_by_id( $row->club_id );
			$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $row->club_id );
			$club_link  = anwp_football_leagues()->club->get_club_link_by_id( $row->club_id );
			?>
			<div class="standing-table__row d-flex align-items-center border-bottom border-left border-right club-<?php echo (int) $row->club_id; ?> place-<?php echo (int) $row->place; ?>">

				<div class="standing-table__cell standing-table__cell-number align-self-stretch d-flex align-items-center justify-content-center <?php echo esc_attr( $color_class ); ?>">
					<span><?php echo esc_html( $row->place ); ?></span>
				</div>

				<?php if ( $club_logo ) : ?>
					<div class="standing-table__cell m-1 d-flex align-items-center">
						<div class="club-logo__cover club-logo__cover--small" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></div>
					</div>
				<?php endif; ?>

				<div class="standing-table__cell mr-auto text-left py-1">
					<a class="club__link anwp-link anwp-link-without-effects" href="<?php echo esc_url( $club_link ); ?>">
						<?php echo esc_html( $club_title ); ?>
					</a>

					<div class="d-flex d-lg-none mt-1">
						<?php
						if ( $row->series ) :
							for ( $ii = 0; $ii < 5; $ii ++ ) :
								$class = 'anwp-bg-secondary';
								if ( ! empty( $series[ $ii ] ) ) {
									$class = 'w' === $series[ $ii ] ? 'anwp-bg-success' : ( 'd' === $series[ $ii ] ? 'anwp-bg-warning' : 'anwp-bg-danger' );
								}
								?>
								<span class="standing-table__mini-cell-form <?php echo esc_attr( $class ); ?>"></span>
								<?php
							endfor;
						endif;
						?>
					</div>
				</div>

				<?php
				if ( $row->series ) :
					for ( $ii = 0; $ii < 5; $ii ++ ) :
						$class = 'anwp-bg-secondary';
						if ( ! empty( $series[ $ii ] ) ) {
							$class = 'w' === $series[ $ii ] ? 'anwp-bg-success' : ( 'd' === $series[ $ii ] ? 'anwp-bg-warning' : 'anwp-bg-danger' );
						}
						?>
						<div class="standing-table__cell-form d-none d-lg-flex text-white align-items-center justify-content-center <?php echo esc_attr( $class ); ?>">
							<?php if ( ! empty( $series[ $ii ] ) && ! empty( $series_map[ strtolower( $series[ $ii ] ) ] ) ) : ?>
								<span><?php echo esc_html( mb_strtoupper( $series_map[ strtolower( $series[ $ii ] ) ] ) ); ?></span>
							<?php else : ?>
								<span>&nbsp;</span>
							<?php endif; ?>
						</div>
						<?php
					endfor;
				endif;
				?>

				<?php foreach ( $columns_order as $col ) : ?>
					<?php $classes = in_array( $col, [ 'won', 'drawn', 'lost', 'gf', 'ga' ], true ) ? 'd-none d-sm-block' : ''; ?>
					<div class="standing-table__cell standing-table__cell-number <?php echo esc_attr( $classes ); ?>">
						<?php echo esc_html( $row->{$col} ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>

	</div>

	<?php if ( $table_notes ) : ?>
		<div class="standing-table__notes mt-2">
			<?php echo wp_kses_post( anwp_football_leagues()->standing->prepare_table_notes( $table_notes ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $data->bottom_link ) ) : ?>
		<div class="standing-table__competition-link mt-2">
			<?php
			if ( 'competition' === $data->bottom_link ) :
				$link_competition_id = anwp_football_leagues()->competition->get_main_competition_id( $competition_id );
				?>
				<a href="<?php echo esc_url( get_permalink( $link_competition_id ) ); ?>"><?php echo esc_html( $data->link_text ? $data->link_text : get_post( $link_competition_id )->post_title ); ?></a>
			<?php elseif ( 'standing' === $data->bottom_link ) : ?>
				<a href="<?php echo esc_url( get_permalink( $standing_id ) ); ?>"><?php echo esc_html( $data->link_text ? $data->link_text : get_the_title( $standing_id ) ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
