<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Sports Club Lite
 */

get_header(); ?>

<div class="container">
     <div id="sport_innerpage_area">
        <section class="sport_innerpage_content_wrapper <?php if( get_theme_mod( 'sports_club_lite_removesidebar_from_singlepost' ) ) { ?>fullwidth<?php } ?>">            
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', 'single' ); ?>
                    <?php the_post_navigation(); ?>
                    <div class="clear"></div>
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() )
                    	comments_template();
                    ?>
                <?php endwhile; // end of the loop. ?>                  
         </section>       
           <?php if( get_theme_mod( 'sports_club_lite_removesidebar_from_singlepost' ) == '') { ?> 
          		<?php get_sidebar();?>
        	<?php } ?>   
       
        <div class="clear"></div>
    </div><!-- sport_innerpage_area -->
</div><!-- container -->	
<?php get_footer(); ?>