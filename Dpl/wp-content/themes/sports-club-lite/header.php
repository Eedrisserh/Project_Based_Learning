<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Sports Club Lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	//wp_body_open hook from WordPress 5.2
	if ( function_exists( 'wp_body_open' ) ) {
	    wp_body_open();
	}
?>
<a class="skip-link screen-reader-text" href="#sport_innerpage_area">
<?php esc_html_e( 'Skip to content', 'sports-club-lite' ); ?>
</a>
<?php
$sports_club_lite_show_topheader_contactinfo 	  	= get_theme_mod('sports_club_lite_show_topheader_contactinfo', false);
$sports_club_lite_show_headersocialsection 	  		= get_theme_mod('sports_club_lite_show_headersocialsection', false);
$sports_club_lite_showslider_sections 	  		    = get_theme_mod('sports_club_lite_showslider_sections', false);
$sports_club_lite_show_5boxes_sections 	  	        = get_theme_mod('sports_club_lite_show_5boxes_sections', false);
$sports_club_lite_show_aboutus_pgecolumn	        = get_theme_mod('sports_club_lite_show_aboutus_pgecolumn', false);
$sports_club_lite_show_ourservices_sections 	  	= get_theme_mod('sports_club_lite_show_ourservices_sections', false);
?>
<div id="layout_forsite" <?php if( get_theme_mod( 'sports_club_lite_boxlayout' ) ) { echo 'class="boxlayout"'; } ?>>
<?php
if ( is_front_page() && !is_home() ) {
	if( !empty($sports_club_lite_showslider_sections)) {
	 	$inner_cls = '';
	}
	else {
		$inner_cls = 'siteinner';
	}
}
else {
$inner_cls = 'siteinner';
}
?>

<div class="header_fixer <?php echo esc_attr($inner_cls); ?> <?php if( get_theme_mod( 'sports_club_lite_stickyhdroption' ) ) { ?>no-sticky<?php } ?>">  
  <div class="container">  
     <div class="logo">
        <?php sports_club_lite_the_custom_logo(); ?>
           <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ) : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div><!-- logo -->
        
       <div class="header_right">
       
       <?php if( $sports_club_lite_show_headersocialsection != ''){ ?> 
           <div class="contactinfo">
            <div class="header-socialicons">                                                
                   <?php $sports_club_lite_fblink = get_theme_mod('sports_club_lite_fblink');
                    if( !empty($sports_club_lite_fblink) ){ ?>
                    <a title="facebook" class="fab fa-facebook-f" target="_blank" href="<?php echo esc_url($sports_club_lite_fblink); ?>"></a>
                   <?php } ?>
                
                   <?php $sports_club_lite_twittlink = get_theme_mod('sports_club_lite_twittlink');
                    if( !empty($sports_club_lite_twittlink) ){ ?>
                    <a title="twitter" class="fab fa-twitter" target="_blank" href="<?php echo esc_url($sports_club_lite_twittlink); ?>"></a>
                   <?php } ?>
            
                  <?php $sports_club_lite_gpluslink = get_theme_mod('sports_club_lite_gpluslink');
                    if( !empty($sports_club_lite_gpluslink) ){ ?>
                    <a title="google-plus" class="fab fa-google-plus" target="_blank" href="<?php echo esc_url($sports_club_lite_gpluslink); ?>"></a>
                  <?php }?>
            
                  <?php $sports_club_lite_linkedlink = get_theme_mod('sports_club_lite_linkedlink');
                    if( !empty($sports_club_lite_linkedlink) ){ ?>
                    <a title="linkedin" class="fab fa-linkedin" target="_blank" href="<?php echo esc_url($sports_club_lite_linkedlink); ?>"></a>
                  <?php } ?>                  
             </div><!--end .header-socialicons--> 
         </div><!--end .contactinfo--> 
      <?php } ?> 
       
       
        <?php if( $sports_club_lite_show_topheader_contactinfo != ''){ ?> 
             <?php
               $sports_club_lite_emailaddress = get_theme_mod('sports_club_lite_emailaddress');
               if( !empty($sports_club_lite_emailaddress) ){ ?> 
               <div class="contactinfo">
                 <i class="fas fa-envelope"></i>
                 <span><a href="<?php echo esc_url('mailto:'.get_theme_mod('sports_club_lite_emailaddress')); ?>"><?php echo esc_html(get_theme_mod('sports_club_lite_emailaddress')); ?></a></span>
                </div>
               <?php } ?>               
                            
           <?php } ?>          
        </div><!--.header_right -->
        
       <div class="sitenavigation">
           <div class="toggle">
             <a class="toggleMenu" href="#"><?php esc_html_e('Menu','sports-club-lite'); ?></a>
           </div><!-- toggle --> 
           <div class="sitemenu">                   
             <?php wp_nav_menu( array('theme_location' => 'primary') ); ?>
           </div><!--.sitemenu -->
       </div><!--.sitenavigation -->
        
        
      <div class="clear"></div>  
 
  </div><!-- .container -->  
</div><!--.header_fixer -->   
  
<?php 
if ( is_front_page() && !is_home() ) {
if($sports_club_lite_showslider_sections != '') {
	for($i=1; $i<=3; $i++) {
	  if( get_theme_mod('sports_club_lite_sliderpgbx'.$i,false)) {
		$slider_Arr[] = absint( get_theme_mod('sports_club_lite_sliderpgbx'.$i,true));
	  }
	}
?> 
<div class="slider_section">                
<?php if(!empty($slider_Arr)){ ?>
<div id="slider" class="nivoSlider">
<?php 
$i=1;
$slidequery = new WP_Query( array( 'post_type' => 'page', 'post__in' => $slider_Arr, 'orderby' => 'post__in' ) );
while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); 
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
?>
<?php if(!empty($image)){ ?>
<img src="<?php echo esc_url( $image ); ?>" title="#slidecaption<?php echo esc_attr( $i ); ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php }else{ ?>
<img src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/images/slides/slider-default.jpg" title="#slidecaption<?php echo esc_attr( $i ); ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php } ?>
<?php $i++; endwhile; ?>
</div>   

<?php 
$j=1;
$slidequery->rewind_posts();
while( $slidequery->have_posts() ) : $slidequery->the_post(); ?>                 
    <div id="slidecaption<?php echo esc_attr( $j ); ?>" class="nivo-html-caption">     
      <div class="custominfo">       
    	<h2><?php the_title(); ?></h2>
    	<?php the_excerpt(); ?>
		<?php
        $sports_club_lite_slidereadmore_btn = get_theme_mod('sports_club_lite_slidereadmore_btn');
        if( !empty($sports_club_lite_slidereadmore_btn) ){ ?>
            <a class="slide_more" href="<?php the_permalink(); ?>"><?php echo esc_html($sports_club_lite_slidereadmore_btn); ?></a>
        <?php } ?>
       </div><!-- .custominfo -->                    
    </div>   
<?php $j++; 
endwhile;
wp_reset_postdata(); ?>  
<div class="clear"></div>  
</div><!--end .slider_section -->     
<?php } ?>
<?php } } ?>
       
        
<?php if ( is_front_page() && ! is_home() ) {
 if( $sports_club_lite_show_5boxes_sections != ''){ ?>  
  <div id="fivecol_sports_section">
     <div class="container">        
		<?php 
        for($n=1; $n<=5; $n++) {    
        if( get_theme_mod('sports_club_lite_5colservicespg'.$n,false)) {      
            $queryvar = new WP_Query('page_id='.absint(get_theme_mod('sports_club_lite_5colservicespg'.$n,true)) );		
            while( $queryvar->have_posts() ) : $queryvar->the_post(); ?>     
            <div class="sports_5col <?php if($n % 5 == 0) { echo "last_column"; } ?>">                                       
                <?php if(has_post_thumbnail() ) { ?>
                <div class="imgbx_5col"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a></div>        
                <?php } ?>		
               <div class="contentbx_5col">
                 <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              </div> 
             <a class="readmorebtn" href="<?php the_permalink(); ?>"><i class="fas fa-link fa-rotate-90"></i></a>	       
                          
            </div>
            <?php endwhile;
            wp_reset_postdata();                                  
        } } ?>                                 
    <div class="clear"></div>  
   </div><!-- .container -->
</div><!-- #fivecol_sports_section -->                	      
<?php } ?>

<?php if( $sports_club_lite_show_aboutus_pgecolumn != ''){ ?>  
<section id="about_section">
<div class="container">                               
<?php 
if( get_theme_mod('sports_club_lite_aboutus_pgecolumn',false)) {     
$queryvar = new WP_Query('page_id='.absint(get_theme_mod('sports_club_lite_aboutus_pgecolumn',true)) );			
    while( $queryvar->have_posts() ) : $queryvar->the_post(); ?>                               
     <div class="aboutus_contentcol">   
        <h3><?php the_title(); ?></h3>   
       <?php the_content();  ?>      
    </div>
    <div class="aboutus_thumbox"><?php the_post_thumbnail();?></div>                                          
    <?php endwhile;
     wp_reset_postdata(); ?>                                    
    <?php } ?>                                 
  <div class="clear"></div>                       
 </div><!-- container -->
</section><!-- #welcome_section-->
<?php } ?>


<?php if( $sports_club_lite_show_ourservices_sections != ''){ ?>  
<section id="services_3column_section">
<div class="container">  
<?php
    $sports_club_lite_servicestittlebx = get_theme_mod('sports_club_lite_servicestittlebx');
       if( !empty($sports_club_lite_servicestittlebx) ){ ?>
       <h2 class="section-title"><?php echo esc_html($sports_club_lite_servicestittlebx); ?></h2>
 <?php } ?>
 
 <?php
    $sports_club_lite_services_descbx = get_theme_mod('sports_club_lite_services_descbx');
       if( !empty($sports_club_lite_services_descbx) ){ ?>
       <p class="srvshortdesc"><?php echo esc_html($sports_club_lite_services_descbx); ?></p>
 <?php } ?>
 
                    
<?php 
for($n=1; $n<=3; $n++) {    
if( get_theme_mod('sports_club_lite_ourservices_pagecolumn'.$n,false)) {      
	$queryvar = new WP_Query('page_id='.absint(get_theme_mod('sports_club_lite_ourservices_pagecolumn'.$n,true)) );		
	while( $queryvar->have_posts() ) : $queryvar->the_post(); ?>     
	<div class="threecol_box <?php if($n % 3 == 0) { echo "last_column"; } ?>">                                       
		<?php if(has_post_thumbnail() ) { ?>
		  <div class="threecol_thumbx"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a></div>        
		<?php } ?>
		<div class="threecol_contentbx">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span class="white-dot"></span> <span class="orange-dot"></span></h3>                                     
		<?php the_excerpt(); ?>	
        <a class="pagereadmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more...','sports-club-lite'); ?></a>	                        
		</div>                                   
	</div>
	<?php endwhile;
	wp_reset_postdata();                                  
} } ?>                                 
<div class="clear"></div>  
</div><!-- .container -->                  
</section><!-- #services_3column_section-->                      	      
<?php } ?>
<?php } ?>