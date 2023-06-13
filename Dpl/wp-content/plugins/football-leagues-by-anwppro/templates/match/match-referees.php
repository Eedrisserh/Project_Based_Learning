<?php
/**
 * The Template for displaying Match >> Referees Section.
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/match/match-referees.php.
 *
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 *
 * @var object $data - Object with args.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.7.3
 *
 * @version       0.13.0
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = (object) wp_parse_args(
	$data,
	[
		'context'           => '',
		'match_id'          => '',
		'referee_id'        => '',
		'assistant_1'       => '',
		'assistant_2'       => '',
		'referee_fourth_id' => '',
		'header'            => true,
	]
);

if ( empty( $data->match_id ) ) {
	return '';
}

// Try to get data directly when used in shortcode
if ( 'shortcode' === $data->context ) {
	$data->referee_id        = get_post_meta( $data->match_id, '_anwpfl_referee', true );
	$data->assistant_1       = get_post_meta( $data->match_id, '_anwpfl_assistant_1', true );
	$data->assistant_2       = get_post_meta( $data->match_id, '_anwpfl_assistant_2', true );
	$data->referee_fourth_id = get_post_meta( $data->match_id, '_anwpfl_referee_fourth', true );
}

if ( empty( $data->referee_id ) && empty( $data->assistant_1 ) && empty( $data->assistant_2 ) && empty( $data->referee_fourth_id ) ) {
	return '';
}

$additional_referees = get_post_meta( $data->match_id, '_anwpfl_additional_referees', true );

/**
 * Hook: anwpfl/tmpl-match/referees_before
 *
 * @param object $data Match data
 *
 * @since 0.7.5
 */
do_action( 'anwpfl/tmpl-match/referees_before', $data );
?>
<div class="anwp-section">

	<?php if ( anwp_football_leagues()->helper->string_to_bool( $data->header ) ) : ?>
		<div class="anwp-block-header">
			<?php echo esc_html( AnWPFL_Text::get_value( 'match__referees__referees', __( 'Referees', 'anwp-football-leagues' ) ) ); ?>
		</div>
	<?php endif; ?>

	<div class="list-group-item match__list-item py-2 px-3 d-flex flex-wrap">
		<?php if ( ! empty( $data->referee_id ) ) : ?>
			<div class="match__referee-wrapper d-flex align-items-center mr-4">
				<span class="text-muted font-weight-bold mr-2"><?php echo esc_html( AnWPFL_Text::get_value( 'match__referees__referee', __( 'Referee', 'anwp-football-leagues' ) ) ); ?>:</span>

				<?php
				// Nationality
				$nationality = maybe_unserialize( get_post_meta( $data->referee_id, '_anwpfl_nationality', true ) );

				if ( $nationality && is_array( $nationality ) ) :
					foreach ( $nationality as $country_code ) :
						?>
						<span class="options__flag f16 mr-1" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>">
								<span class="flag <?php echo esc_attr( $country_code ); ?>"></span>
							</span>
						<?php
					endforeach;
				endif;
				?>

				<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->referee_id ) ); ?>">
					<?php echo esc_html( get_the_title( $data->referee_id ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $data->assistant_1 ) ) : ?>
			<div class="match__referee-wrapper d-flex align-items-center mr-4">
				<span class="text-muted font-weight-bold mr-2"><?php echo esc_html( AnWPFL_Text::get_value( 'match__referees__assistant', __( 'Assistant Referee', 'anwp-football-leagues' ) ) ); ?> 1:</span>

				<?php
				// Nationality
				$nationality = maybe_unserialize( get_post_meta( $data->assistant_1, '_anwpfl_nationality', true ) );

				if ( $nationality && is_array( $nationality ) ) :
					foreach ( $nationality as $country_code ) :
						?>
						<span class="options__flag f16 mr-1" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>">
								<span class="flag <?php echo esc_attr( $country_code ); ?>"></span>
							</span>
						<?php
					endforeach;
				endif;
				?>

				<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->assistant_1 ) ); ?>">
					<?php echo esc_html( get_the_title( $data->assistant_1 ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $data->assistant_2 ) ) : ?>
			<div class="match__referee-wrapper d-flex align-items-center mr-4">
				<span class="text-muted font-weight-bold mr-2"><?php echo esc_html( AnWPFL_Text::get_value( 'match__referees__assistant', __( 'Assistant Referee', 'anwp-football-leagues' ) ) ); ?> 2:</span>

				<?php
				// Nationality
				$nationality = maybe_unserialize( get_post_meta( $data->assistant_2, '_anwpfl_nationality', true ) );

				if ( $nationality && is_array( $nationality ) ) :
					foreach ( $nationality as $country_code ) :
						?>
						<span class="options__flag f16 mr-1" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>">
								<span class="flag <?php echo esc_attr( $country_code ); ?>"></span>
							</span>
						<?php
					endforeach;
				endif;
				?>

				<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->assistant_2 ) ); ?>">
					<?php echo esc_html( get_the_title( $data->assistant_2 ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $data->referee_fourth_id ) ) : ?>
			<div class="match__referee-wrapper d-flex align-items-center mr-4">
				<span class="text-muted font-weight-bold mr-2"><?php echo esc_html( AnWPFL_Text::get_value( 'match__referees__fourth_official', __( 'Fourth official', 'anwp-football-leagues' ) ) ); ?>:</span>

				<?php
				$nationality = maybe_unserialize( get_post_meta( $data->referee_fourth_id, '_anwpfl_nationality', true ) );

				if ( $nationality && is_array( $nationality ) ) :
					foreach ( $nationality as $country_code ) :
						?>
						<span class="options__flag f16 mr-1" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>">
								<span class="flag <?php echo esc_attr( $country_code ); ?>"></span>
							</span>
						<?php
					endforeach;
				endif;
				?>

				<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $data->referee_fourth_id ) ); ?>">
					<?php echo esc_html( get_the_title( $data->referee_fourth_id ) ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $additional_referees ) ) : ?>
		<div class="list-group-item match__list-item py-2 px-3 d-flex flex-wrap">
			<?php foreach ( $additional_referees as $additional_referee ) : ?>
				<?php if ( ! empty( $additional_referee['_anwpfl_referee'] ) && absint( $additional_referee['_anwpfl_referee'] ) ) : ?>
					<div class="match__referee-wrapper d-flex align-items-center mr-4">
						<?php if ( ! empty( $additional_referee['role'] ) ) : ?>
							<span class="text-muted font-weight-bold mr-2"><?php echo esc_html( $additional_referee['role'] ); ?>:</span>
						<?php endif; ?>
						<?php
						$nationality = maybe_unserialize( get_post_meta( $additional_referee['_anwpfl_referee'], '_anwpfl_nationality', true ) );

						if ( $nationality && is_array( $nationality ) ) :
							foreach ( $nationality as $country_code ) :
								?>
								<span class="options__flag f16 mr-1" data-toggle="anwp-tooltip" data-tippy-content="<?php echo esc_attr( anwp_football_leagues()->data->get_value_by_key( $country_code, 'country' ) ); ?>">
									<span class="flag <?php echo esc_attr( $country_code ); ?>"></span>
								</span>
								<?php
							endforeach;
						endif;
						?>
						<a class="anwp-link anwp-link-without-effects" href="<?php echo esc_url( get_permalink( $additional_referee['_anwpfl_referee'] ) ); ?>">
							<?php echo esc_html( get_the_title( $additional_referee['_anwpfl_referee'] ) ); ?>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
