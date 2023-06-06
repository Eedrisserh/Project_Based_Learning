<?php
/**
 * @package Sports Club Lite
 */
?>
 <div class="blogpost_layout">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>  
        <?php if( get_theme_mod( 'sports_club_lite_removethumb_blogpost_and_singlepost' ) == '') { ?> 
        <?php if (has_post_thumbnail() ){ ?>
			<div class="blogpost_imagebx">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			</div>
		<?php } }  ?>
        
        <header class="entry-header">           
            <h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
            <?php if ( 'post' == get_post_type() ) : ?>
                <div class="blogpost_meta">
                  <div class="post-author"><i class="fas fa-user"></i>
				  <?php printf(
				/* translators: %s: post author */
				   esc_html_e( 'Published by %s', 'sports-club-lite' ),
				    esc_html( get_the_author() )
			        );
			       ?>
                    </div><!-- .post-author--> 
                    <div class="blogpost_date"><i class="fas fa-calendar-alt"></i> <?php the_date(); ?></div><!-- blogpost_date -->                    
                   <?php edit_post_link( __( 'Edit', 'sports-club-lite' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- blogpost_meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->       
          
          
        <?php if ( is_search() || !is_single() ) : // Only display Excerpts for Search ?>
        <div class="entry-summary">
           	<?php the_excerpt(); ?>
            <a class="blogreadmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more...','sports-club-lite'); ?></a>	      
        </div><!-- .entry-summary -->
        <?php else : ?>
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sports-club-lite' ) ); ?>
            <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'sports-club-lite' ),
                    'after'  => '</div>',
                ) );
            ?>
        </div><!-- .entry-content -->
        <?php endif; ?>
        <div class="clear"></div>
    </article><!-- #post-## -->
</div><!-- .blogpost_layout-->