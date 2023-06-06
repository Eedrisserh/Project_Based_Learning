<?php
/**
 *Sports Club Lite About Theme
 *
 * @package Sports Club Lite
 */

//about theme info
add_action( 'admin_menu', 'sports_club_lite_abouttheme' );
function sports_club_lite_abouttheme() {    	
	add_theme_page( __('About Theme Info', 'sports-club-lite'), __('About Theme Info', 'sports-club-lite'), 'edit_theme_options', 'sports_club_lite_guide', 'sports_club_lite_mostrar_guide');   
} 

//Info of the theme
function sports_club_lite_mostrar_guide() { 	
?>
<div class="wrap-GT">
	<div class="gt-left">
   		   <div class="heading-gt">
			  <h3><?php esc_html_e('About Theme Info', 'sports-club-lite'); ?></h3>
		   </div>
          <p><?php esc_html_e('SSports Club Lite is a resourceful, modern and engaging, youthful and lively, purposeful and versatile, highly responsive sports league WordPress theme. This sports theme has been designed to provide an easy and specialized platform for the streamlined development of professional, awesome and tech-savvy websites for various sports club. This flexible theme can be adjusted to be used for rugby, football, basketball, volleyball, hockey, tennis, baseball or any other individual sport. This feature-rich, highly customizable and multisport WordPress theme can also be used to create a fully functional website template for fitness clubs, gyms, dance schools, martial arts, personal trainers and any other health & fitness related business.','sports-club-lite'); ?></p>
<div class="heading-gt"> <?php esc_html_e('Theme Features', 'sports-club-lite'); ?></div>
 

<div class="col-2">
  <h4><?php esc_html_e('Theme Customizer', 'sports-club-lite'); ?></h4>
  <div class="description"><?php esc_html_e('The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'sports-club-lite'); ?></div>
</div>

<div class="col-2">
  <h4><?php esc_html_e('Responsive Ready', 'sports-club-lite'); ?></h4>
  <div class="description"><?php esc_html_e('The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'sports-club-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_html_e('Cross Browser Compatible', 'sports-club-lite'); ?></h4>
<div class="description"><?php esc_html_e('Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'sports-club-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_html_e('E-commerce', 'sports-club-lite'); ?></h4>
<div class="description"><?php esc_html_e('Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'sports-club-lite'); ?></div>
</div>
<hr />  
</div><!-- .gt-left -->
	
<div class="gt-right">			
        <div>				
            <a href="<?php echo esc_url( SPORTS_CLUB_LITE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'sports-club-lite'); ?></a> | 
            <a href="<?php echo esc_url( SPORTS_CLUB_LITE_PROTHEME_URL ); ?>" target="_blank"><?php esc_html_e('Purchase Pro', 'sports-club-lite'); ?></a> | 
            <a href="<?php echo esc_url( SPORTS_CLUB_LITE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'sports-club-lite'); ?></a>
        </div>		
</div><!-- .gt-right-->
<div class="clear"></div>
</div><!-- .wrap-GT -->
<?php } ?>