<?php
/**
 * The Template for displaying Standing Table Shortcode. Layout "mini". Used for widget.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/shortcode-standing--mini.php.
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

$data = (object) wp_parse_args(
	$data,
	[
		'title'       => '',
		'id'          => '',
		'exclude_ids' => '',
		'context'     => '',
		'bottom_link' => '',
		'link_text'   => '',
		'partial'     => '',
		'show_notes'  => 1,
	]
);

if ( ! empty( $data->context ) && 'widget' === $data->context ) {
	$data->id = $data->standing;
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
$table_notes  = anwp_football_leagues()->helper->string_to_bool( $data->show_notes ) ? get_post_meta( $standing_id, '_anwpfl_table_notes', true ) : '';

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
	[ 'title' => '' ]
);

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
	[ 'played', 'won', 'drawn', 'lost', 'points' ],
	$standing_id,
	'mini',
	$competition_id,
	$group_id
);

$column_header = anwp_football_leagues()->data->get_standing_headers();

$exclude_ids = [];

if ( ! empty( $data->exclude_ids ) ) {
	$exclude_ids = array_map( 'absint', explode( ',', $data->exclude_ids ) );
}

// Slice table if partial option is set
if ( $data->partial ) {
	$table = anwp_football_leagues()->standing->get_standing_partial_data( $table, $data->partial );
}
?>

<div class="anwp-b-wrap standing standing--widget standing__inner competition-<?php echo (int) $competition_id; ?> standing-<?php echo (int) $standing_id; ?> context--<?php echo esc_attr( $data->context ); ?>">

	<?php if ( $data->title ) : ?>
		<h4 class="standing__title"><?php echo esc_html( $data->title ); ?></h4>
	<?php endif; ?>

	<table class="table table-sm table-bordered standing-table anwp-text-center mb-0 <?php echo esc_attr( 'yes' === AnWPFL_Options::get_value( 'standing_font_mono' ) ? 'standing-text-mono' : '' ); ?>">
		<thead class="anwp-bg-light">
		<tr>
			<th class="anwp-text-center" scope="col">#</th>
			<th scope="col"><?php echo esc_html( AnWPFL_Text::get_value( 'standing__shortcode__club', __( 'Club', 'anwp-football-leagues' ) ) ); ?></th>

			<?php foreach ( $columns_order as $col ) : ?>
				<?php $classes = in_array( $col, [ 'won', 'drawn', 'lost' ], true ) ? 'd-none d-sm-table-cell' : ''; ?>
				<th scope="col" class="anwp-text-center <?php echo esc_attr( $classes ); ?>" data-toggle="anwp-tooltip"
					data-tippy-content="<?php echo esc_html( empty( $column_header[ $col ]['tooltip'] ) ? '' : $column_header[ $col ]['tooltip'] ); ?>">
					<?php echo esc_html( empty( $column_header[ $col ]['text'] ) ? '' : $column_header[ $col ]['text'] ); ?>
				</th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody>

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

			?>
			<tr class="standing-table__row club-<?php echo (int) $row->club_id; ?> place-<?php echo (int) $row->place; ?>">
				<td class="px-0 align-middle standing-table__cell-number <?php echo esc_attr( $color_class ); ?>"><?php echo esc_html( $row->place ); ?></td>
				<td class="text-left">

					<?php
					$club_title = 'no' !== AnWPFL_Options::get_value( 'use_abbr_in_standing_mini' ) ? anwp_football_leagues()->club->get_club_abbr_by_id( $row->club_id ) : anwp_football_leagues()->club->get_club_title_by_id( $row->club_id );
					$club_logo  = anwp_football_leagues()->club->get_club_logo_by_id( $row->club_id );
					$club_link  = anwp_football_leagues()->club->get_club_link_by_id( $row->club_id );
					?>

					<?php if ( $club_logo ) : ?>
						<div class="club-logo__cover club-logo__cover--small mr-1 align-middle" style="background-image: url('<?php echo esc_url( $club_logo ); ?>')"></div>
					<?php endif; ?>

					<a class="club__link anwp-link align-middle" href="<?php echo esc_url( $club_link ); ?>">
						<?php echo esc_html( $club_title ); ?>
					</a>
				</td>

				<?php foreach ( $columns_order as $col ) : ?>
					<?php $classes = in_array( $col, [ 'won', 'drawn', 'lost' ], true ) ? 'd-none d-sm-table-cell' : ''; ?>
					<td class="align-middle standing-table__cell-number <?php echo esc_attr( $classes ); ?>">
						<?php echo esc_html( $row->{$col} ); ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>

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
