<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Sports Club Lite
 */

get_header(); ?>

<div class="container">
    <div id="sport_innerpage_area">
        <section class="sport_innerpage_content_wrapper">
            <header class="page-header">
                <h1 class="entry-title"><?php esc_html_e( '404 Not Found', 'sports-club-lite' ); ?></h1>                
            </header><!-- .page-header -->
            <div class="page-content">
                <p><?php esc_html_e( 'Looks like you have taken a wrong turn.....<br />Don\'t worry... it happens to the best of us.', 'sports-club-lite' ); ?></p>  
            </div><!-- .page-content -->
        </section>
        <?php get_sidebar();?>       
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>