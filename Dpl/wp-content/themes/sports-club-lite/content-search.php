<?php
/**
 * The template used for displaying content search.php
 * @package Sports Club Lite
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

 		<?php 
        if (has_post_thumbnail() ){
			echo '<div class="blogpost_imagebx">';
            the_post_thumbnail();
			echo '</div>';
		}
        ?>    
    <header class="entry-header">
        <?php the_title( '<h3 class="single-title">', '</h3>' ); ?>
    </header><!-- .entry-header -->    
     <div class="blogpost_meta">
            <div class="blogpost_date"><?php the_date(); ?></div><!-- blogpost_date -->
            <div class="post-comment"> <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></div>            
    </div><!-- blogpost_meta -->  

    <div class="entry-content">		
        <?php the_content(); ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'sports-club-lite' ),
            'after'  => '</div>',
        ) );
        ?>
        <div class="blogpost_meta">          
            <div class="blog_posttag"><?php the_tags(); ?> </div>
            <div class="clear"></div>
        </div><!-- blogpost_meta -->
    </div><!-- .entry-content -->
   
    <footer class="footer-infometa">
      <?php edit_post_link( __( 'Edit', 'sports-club-lite' ), '<span class="edit-link">', '</span>' ); ?>
    </footer><!-- .footer-infometa -->
</article>