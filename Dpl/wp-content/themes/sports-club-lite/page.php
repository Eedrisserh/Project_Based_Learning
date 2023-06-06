<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Sports Club Lite
 */

get_header(); ?>

<div class="container">
  <div id="sport_innerpage_area">
         <section class="sport_innerpage_content_wrapper <?php if( get_theme_mod( 'sports_club_lite_removesidebar_from_pages' ) ) { ?>fullwidth<?php } ?>">               
                <?php while( have_posts() ) : the_post(); ?>                               
                    <?php get_template_part( 'content', 'page' ); ?>
                    <?php
                        //If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() )
                            comments_template();
                        ?>                               
                <?php endwhile; ?>                     
        </section><!-- section-->   
      <?php if( get_theme_mod( 'sports_club_lite_removesidebar_from_pages' ) == '') { ?> 
          	<?php get_sidebar();?>
      <?php } ?>      
<div class="clear"></div>
</div><!-- .sport_innerpage_area --> 
</div><!-- .container --> 
<?php get_footer(); ?>