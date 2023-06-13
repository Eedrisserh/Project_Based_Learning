<?php
/**
 * The Template for displaying Competition content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-competition--tabs.php.
 *
 * @author        Andrei Strekozov <anwp.pro>
 * @package       AnWP-Football-Leagues/Templates
 * @since         0.3.0
 * @since         0.4.2 Modified multistage logic
 * @since         0.7.2 Modified logic and structure
 *
 * @version       0.11.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Prepare data
$competition_post_id = get_the_ID();
$is_multistage       = in_array( get_post_meta( $competition_post_id, '_anwpfl_multistage', true ), [ 'main', 'secondary' ], true );

// Get competition matches ( or all matches from the same league and season - if multistage )
$matches = anwp_football_leagues()->competition->tmpl_get_competition_matches( $competition_post_id, $is_multistage );

// Get competitions
$competitions = anwp_football_leagues()->competition->tmpl_get_prepared_competitions( $competition_post_id, $is_multistage, $matches );

/**
 * Filter: anwpfl/tmpl-competition/competitions_prepared
 *
 * @since 0.7.5
 *
 * @param array   $competitions
 * @param integer $competition_post_id - Main Competition ID
 * @param bool    $is_multistage
 * @param array   $matches
 */
$competitions = apply_filters( 'anwpfl/tmpl-competition/competitions_prepared', $competitions, $competition_post_id, $is_multistage, $matches );

/**
 * Hook: anwpfl/tmpl-competition/before_wrapper
 *
 * @since 0.7.5
 *
 * @param int $competition_post_id Post ID
 */
do_action( 'anwpfl/tmpl-competition/before_wrapper', $competition_post_id );
?>
<div class="anwp-b-wrap competition competition__inner competition-<?php echo (int) $competition_post_id; ?> competition-mixed">

	<?php
	/**
	 * Hook: anwpfl/tmpl-competition/before_header
	 *
	 * @since 0.7.5
	 *
	 * @param int $competition_post_id Post ID
	 */
	do_action( 'anwpfl/tmpl-competition/before_header', $competition_post_id );

	/**
	 * Filter: anwpfl/tmpl-competition/render_header
	 *
	 * @since 0.7.5
	 *
	 * @param int $competition_post_id Post ID
	 */
	if ( apply_filters( 'anwpfl/tmpl-competition/render_header', true, $competition_post_id ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo anwp_football_leagues()->template->shortcode_loader( 'competition_header', [ 'id' => $competition_post_id ] );
	}

	/**
	 * Hook: anwpfl/tmpl-competition/after_header
	 *
	 * @since 0.7.5
	 *
	 * @param int $competition_post_id Post ID
	 */
	do_action( 'anwpfl/tmpl-competition/after_header', $competition_post_id );

	if ( ! empty( $competitions ) && is_array( $competitions ) ) :
		if ( count( $competitions ) > 1 ) :
			?>
			<div class="anwp-fl-tabs mb-3 competition__tabs">
				<?php foreach ( $competitions as $ii => $c ) : ?>
					<a class="btn btn-sm btn-outline-secondary anwp-fl-tabs__item" id="anwp_id_<?php echo (int) $c->ID; ?>-tab"
						href="#" data-target="#anwp_c_id_<?php echo (int) $c->ID; ?>"><?php echo esc_html( get_post_meta( $c->ID, '_anwpfl_stage_title', true ) ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="anwp-fl-tabs-content competition__tabs-content">
			<?php foreach ( $competitions as $ii => $competition ) : ?>
				<div class="anwp-fl-tab__content" id="anwp_c_id_<?php echo (int) $competition->ID; ?>">
					<?php

					/**
					 * Hook: anwpfl/tmpl-competition/before_stage
					 *
					 * @since 0.7.5
					 *
					 * @param WP_Post $competition
					 * @param int     $competition_post_id Post ID
					 */
					do_action( 'anwpfl/tmpl-competition/before_stage', $competition, $competition_post_id );

					$competition_type = get_post_meta( $competition->ID, '_anwpfl_type', true );

					// Prepare groups
					$groups = json_decode( get_post_meta( $competition->ID, '_anwpfl_groups', true ) );

					if ( empty( $groups ) || ! is_array( $groups ) ) {
						continue;
					}

					/*
					|--------------------------------------------------------------------
					| Populate round field
					| Backward compatibility for competitions created before v0.10
					| @since 0.10.0
					|--------------------------------------------------------------------
					*/
					foreach ( $groups as $group_id => $group ) {
						if ( empty( $groups[ $group_id ]->round ) ) {
							$groups[ $group_id ]->round = 1;
						}
					}

					/*
					|--------------------------------------------------------------------
					| Prepare rounds
					| @since 0.10.0
					|--------------------------------------------------------------------
					*/
					$rounds = json_decode( get_post_meta( $competition->ID, '_anwpfl_rounds', true ) );

					if ( empty( $rounds ) || ! is_array( $rounds ) ) {
						$rounds = [
							(object) [
								'id'    => 1,
								'title' => '',
							],
						];
					}

					// Check DESC round sorting
					if ( 'desc' === AnWPFL_Options::get_value( 'competition_rounds_order' ) ) {
						$rounds = wp_list_sort( $rounds, 'id', 'DESC' );
					}

					// Round Tabs
					if ( count( $rounds ) > 1 ) :
						?>
						<div class="anwp-fl-tabs" id="anwp-rounds-tabs-c<?php echo (int) $ii; ?>" role="tablist">
							<?php foreach ( $rounds as $rr => $c ) : ?>
								<a class="mr-1 btn btn-sm btn-outline-secondary anwp-fl-tabs__item"
									href="#" data-target="#anwp_round_id_<?php echo (int) $c->id; ?>"><?php echo esc_html( $c->title ); ?></a>
							<?php endforeach; ?>
						</div>
						<?php
					endif;
					?>
					<div class="anwp-fl-tabs-content competition-round__tabs-content">
						<?php foreach ( $rounds as $rr => $round ) : ?>
							<div class="anwp-fl-tab__content <?php echo ( count( $rounds ) > 1 ) ? 'd-none' : ''; ?>" id="anwp_round_id_<?php echo (int) $round->id; ?>">
								<?php

								/**
								 * Hook: anwpfl/tmpl-competition/before_group
								 *
								 * @since 0.10.0
								 *
								 * @param object  $round
								 * @param object  $competition
								 * @param integer $competition_post_id - Competition ID
								 */
								do_action( 'anwpfl/tmpl-competition/before_round', $round, $competition, $competition_post_id );

								foreach ( $groups as $group ) :

									if ( intval( $group->round ) !== intval( $round->id ) ) {
										continue;
									}

									/**
									 * Hook: anwpfl/tmpl-competition/before_group
									 *
									 * @since 0.7.5
									 *
									 * @param object  $group
									 * @param object  $competition
									 * @param integer $competition_post_id - Main Competition ID
									 */
									do_action( 'anwpfl/tmpl-competition/before_group', $group, $competition, $competition_post_id );
									?>
									<div class="competition__group-wrapper mt-3">
										<?php
										if ( 'round-robin' === $competition_type ) :

											/**
											 * Hook: anwpfl/tmpl-competition/before_group_title
											 *
											 * @since 0.7.5
											 *
											 * @param object  $group
											 * @param object  $competition
											 * @param integer $competition_post_id - Main Competition ID
											 */
											do_action( 'anwpfl/tmpl-competition/before_group_title', $group, $competition, $competition_post_id );

											$render_group_title = count( $groups ) > 1 && ! empty( $group->title );

											/**
											 * Filter: anwpfl/tmpl-competition/render_group_title
											 *
											 * @since 0.7.5
											 *
											 * @param bool    $render_group_title
											 * @param object  $group
											 * @param object  $competition
											 * @param integer $competition_post_id - Main Competition ID
											 */
											if ( apply_filters( 'anwpfl/tmpl-competition/render_group_title', $render_group_title, $group, $competition, $competition_post_id ) ) :
												?>
												<div class="competition__group-title mt-4 mb-2 anwp-group-header"><?php echo esc_html( $group->title ); ?></div>
												<?php
											endif;

											/**
											 * Hook: anwpfl/tmpl-competition/after_group_title
											 *
											 * @since 0.7.5
											 *
											 * @param object  $group
											 * @param object  $competition
											 * @param integer $competition_post_id - Main Competition ID
											 */
											do_action( 'anwpfl/tmpl-competition/after_group_title', $group, $competition, $competition_post_id );

											/**
											 * Filter: anwpfl/tmpl-competition/render_group_title
											 *
											 * @since 0.7.5
											 *
											 * @param bool
											 * @param object  $group
											 * @param object  $competition
											 * @param integer $competition_post_id - Main Competition ID
											 */
											if ( apply_filters( 'anwpfl/tmpl-competition/render_group_standing', true, $group, $competition, $competition_post_id ) ) :

												$standing = anwp_football_leagues()->competition->tmpl_get_competition_standings( $competition->ID, $group->id );

												if ( ! empty( $standing[0] ) && ! empty( $standing[0]->ID ) ) :

													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo anwp_football_leagues()->template->shortcode_loader(
														'standing',
														[
															'id'      => $standing[0]->ID,
															'title'   => '',
															'context' => 'competition',
														]
													);
												endif;
											endif;

											/**
											 * Hook: anwpfl/tmpl-competition/after_group_standing
											 *
											 * @since 0.7.5
											 *
											 * @param object  $group
											 * @param object  $competition
											 * @param integer $competition_post_id - Main Competition ID
											 */
											do_action( 'anwpfl/tmpl-competition/after_group_standing', $group, $competition, $competition_post_id );
										endif;

										/**
										 * Filter: anwpfl/tmpl-competition/render_list_of_matches
										 *
										 * @since 0.10.0
										 *
										 * @param bool
										 * @param object  $competition
										 * @param integer $competition_post_id - Main Competition ID
										 */
										if ( apply_filters( 'anwpfl/tmpl-competition/render_list_of_matches', true, $competition, $competition_post_id ) ) :
											?>
											<div class="list-group <?php echo esc_attr( $is_multistage ? 'mt-3' : '' ); ?>">
												<?php
												$match_week = 0;
												foreach ( $matches as $match ) :

													if ( (int) $match->competition_id !== $competition->ID || (int) $match->group_id !== (int) $group->id ) {
														continue;
													}

													if ( 'round-robin' === $competition_type && $match_week !== (int) $match->match_week && (int) $match->match_week ) :
														?>
														<div class="anwp-block-header mt-4">
															<?php echo esc_html( anwp_football_leagues()->options->get_text_matchweek( $match->match_week ) ); ?>
														</div>
														<?php
														$match_week = (int) $match->match_week;
													endif;

													// Get match data to render
													$data = anwp_football_leagues()->match->prepare_match_data_to_render( $match );

													$data['competition_logo'] = false;
													anwp_football_leagues()->load_partial( $data, 'match/match', 'slim' );

												endforeach;
												?>
											</div>
											<?php
										endif;

										/**
										 * Hook: anwpfl/tmpl-competition/after_list_of_matches
										 *
										 * @since 0.10.0
										 *
										 * @param object  $group
										 * @param object  $competition
										 * @param integer $competition_post_id - Main Competition ID
										 */
										do_action( 'anwpfl/tmpl-competition/after_list_of_matches', $group, $competition, $competition_post_id );
										?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<?php
/**
 * Hook: anwpfl/tmpl-competition/after_wrapper
 *
 * @since 0.7.5
 *
 * @param int $competition_post_id Post ID
 */
do_action( 'anwpfl/tmpl-competition/after_wrapper', $competition_post_id );
