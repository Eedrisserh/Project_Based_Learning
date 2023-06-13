<?php
/**
 * The Template for displaying Competition content.
 * Content only (without title and comments).
 *
 * This template can be overridden by copying it to yourtheme/anwp-football-leagues/content-competition.php.
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
		echo anwp_football_leagues()->template->shortcode_loader(
			'competition_header',
			[ 'id' => $competition_post_id ]
		); // WPCS: XSS ok.
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
		foreach ( $competitions as $ii => $competition ) :

			$competition_type = get_post_meta( $competition->ID, '_anwpfl_type', true );

			/**
			 * Hook: anwpfl/tmpl-competition/before_stage
			 *
			 * @since 0.7.5
			 *
			 * @param WP_Post $competition
			 * @param int     $competition_post_id Post ID
			 */
			do_action( 'anwpfl/tmpl-competition/before_stage', $competition, $competition_post_id );

			/**
			 * Filter: anwpfl/tmpl-competition/render_stage_title
			 *
			 * @since 0.7.5
			 *
			 * @param bool
			 * @param object  $competition
			 * @param integer $competition_post_id - Main Competition ID
			 */
			if ( apply_filters( 'anwpfl/tmpl-competition/render_stage_title', true, $competition, $competition_post_id ) ) :
				if ( $is_multistage ) :
					?>
					<div class="anwp-section-header anwp-section mt-5">
						<?php echo esc_html( get_post_meta( $competition->ID, '_anwpfl_stage_title', true ) ); ?>
					</div>
					<?php
				endif;
			endif;

			/**
			 * Hook: anwpfl/tmpl-competition/after_stage_title
			 *
			 * @since 0.7.5
			 *
			 * @param WP_Post $competition
			 * @param int     $competition_post_id Post ID
			 */
			do_action( 'anwpfl/tmpl-competition/after_stage_title', $competition, $competition_post_id );

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

			foreach ( $rounds as $round ) :

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

				$render_round_title = count( $rounds ) > 1 && ! empty( $round->title );

				/**
				 * Filter: anwpfl/tmpl-competition/render_round_title
				 *
				 * @since 0.10.0
				 *
				 * @param bool    $render_round_title
				 * @param object  $round
				 * @param object  $competition
				 * @param integer $competition_post_id - Main Competition ID
				 */
				if ( apply_filters( 'anwpfl/tmpl-competition/render_round_title', $render_round_title, $round, $competition, $competition_post_id ) ) :
					?>
					<div class="anwp-block-header mt-4"><?php echo esc_html( $round->title ); ?></div>
					<?php
				endif;

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
					<div class="competition__group-wrapper <?php echo esc_attr( 'round-robin' === $competition_type ? 'mt-4' : 'mt-3' ); ?>">
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
						 * @since 0.8.0
						 *
						 * @param bool
						 * @param object  $competition
						 * @param integer $competition_post_id - Main Competition ID
						 */
						if ( apply_filters( 'anwpfl/tmpl-competition/render_list_of_matches', true, $competition, $competition_post_id ) ) :
							?>
							<div class="list-group <?php echo esc_attr( $is_multistage ? 'mt-4' : '' ); ?>">
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
						 * @since 0.8.0
						 *
						 * @param object  $group
						 * @param object  $competition
						 * @param integer $competition_post_id - Main Competition ID
						 */
						do_action( 'anwpfl/tmpl-competition/after_list_of_matches', $group, $competition, $competition_post_id );
						?>
					</div>
					<?php
				endforeach; // End of Groups Loop
			endforeach; // End of Round Loop
		endforeach; // End of Competitions Loop
	endif;
	?>
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
