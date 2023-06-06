<?php    
/**
 *Sports Club Lite Theme Customizer
 *
 * @package Sports Club Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sports_club_lite_customize_register( $wp_customize ) {	
	
	function sports_club_lite_sanitize_dropdown_pages( $page_id, $setting ) {
	  // Ensure $input is an absolute integer.
	  $page_id = absint( $page_id );
	
	  // If $page_id is an ID of a published page, return it; otherwise, return the default.
	  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	function sports_club_lite_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}  
		
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	 //Panel for section & control
	$wp_customize->add_panel( 'sports_club_lite_panel_area', array(
		'priority' => null,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Theme Options Panel', 'sports-club-lite' ),		
	) );
	
	//Layout Options
	$wp_customize->add_section('sports_club_lite_sitelayoutoptions',array(
		'title' => __('Site Layout','sports-club-lite'),			
		'priority' => 1,
		'panel' => 	'sports_club_lite_panel_area',          
	));		
	
	$wp_customize->add_setting('sports_club_lite_boxlayout',array(
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'sports_club_lite_boxlayout', array(
    	'section'   => 'sports_club_lite_sitelayoutoptions',    	 
		'label' => __('Check to Box Layout','sports-club-lite'),
		'description' => __('If you want to box layout please check the Box Layout Options','sports-club-lite'),
    	'type'      => 'checkbox'
     )); //Layout Section 
	
	$wp_customize->add_setting('sports_club_lite_color_scheme',array(
		'default' => '#ec4613',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'sports_club_lite_color_scheme',array(
			'label' => __('Site Color Scheme','sports-club-lite'),			
			'description' => __('More color options in PRO Version','sports-club-lite'),
			'section' => 'colors',
			'settings' => 'sports_club_lite_color_scheme'
		))
	);		
	 
	 // Sticky Header Options
	$wp_customize->add_section('sports_club_lite_stickyheader_options', array(
		'title' => __('Sticky Header','sports-club-lite'),		
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',          
	));	
	
	$wp_customize->add_setting('sports_club_lite_stickyhdroption',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_stickyhdroption', array(
	   'settings' => 'sports_club_lite_stickyhdroption',
	   'section'   => 'sports_club_lite_stickyheader_options',
	   'label'     => __('Check to hide sticky header','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Sticky Header Options	
	
	
	//Top Header Contact info
	$wp_customize->add_section('sports_club_lite_topheader_contactsections',array(
		'title' => __('Header Contact info','sports-club-lite'),				
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',
	));	
	
	
	$wp_customize->add_setting('sports_club_lite_emailaddress',array(
		'sanitize_callback' => 'sanitize_email'
	));
	
	$wp_customize->add_control('sports_club_lite_emailaddress',array(
		'type' => 'text',
		'label' => __('Add email address here.','sports-club-lite'),
		'section' => 'sports_club_lite_topheader_contactsections'
	));	
	
	
	$wp_customize->add_setting('sports_club_lite_show_topheader_contactinfo',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_show_topheader_contactinfo', array(
	   'settings' => 'sports_club_lite_show_topheader_contactinfo',
	   'section'   => 'sports_club_lite_topheader_contactsections',
	   'label'     => __('Check To show This Section','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Show top header contact info
	
	 
	 //Header social icons
	$wp_customize->add_section('sports_club_lite_headersocialsection',array(
		'title' => __('Header social icons','sports-club-lite'),
		'description' => __( 'Add social icons link here to display icons in header.', 'sports-club-lite' ),			
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area', 
	));
	
	$wp_customize->add_setting('sports_club_lite_fblink',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'	
	));
	
	$wp_customize->add_control('sports_club_lite_fblink',array(
		'label' => __('Add facebook link here','sports-club-lite'),
		'section' => 'sports_club_lite_headersocialsection',
		'setting' => 'sports_club_lite_fblink'
	));	
	
	$wp_customize->add_setting('sports_club_lite_twittlink',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('sports_club_lite_twittlink',array(
		'label' => __('Add twitter link here','sports-club-lite'),
		'section' => 'sports_club_lite_headersocialsection',
		'setting' => 'sports_club_lite_twittlink'
	));
	
	$wp_customize->add_setting('sports_club_lite_gpluslink',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('sports_club_lite_gpluslink',array(
		'label' => __('Add google plus link here','sports-club-lite'),
		'section' => 'sports_club_lite_headersocialsection',
		'setting' => 'sports_club_lite_gpluslink'
	));
	
	$wp_customize->add_setting('sports_club_lite_linkedlink',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('sports_club_lite_linkedlink',array(
		'label' => __('Add linkedin link here','sports-club-lite'),
		'section' => 'sports_club_lite_headersocialsection',
		'setting' => 'sports_club_lite_linkedlink'
	));
	
	$wp_customize->add_setting('sports_club_lite_show_headersocialsection',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_show_headersocialsection', array(
	   'settings' => 'sports_club_lite_show_headersocialsection',
	   'section'   => 'sports_club_lite_headersocialsection',
	   'label'     => __('Check To show This Section','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Show Header Social icons Section 			
	
	// Slider Section		
	$wp_customize->add_section( 'sports_club_lite_headerslider_sections', array(
		'title' => __('Slider Section', 'sports-club-lite'),
		'priority' => null,
		'description' => __('Default image size for slider is 1400 x 655 pixel.','sports-club-lite'), 
		'panel' => 	'sports_club_lite_panel_area',           			
    ));
	
	$wp_customize->add_setting('sports_club_lite_sliderpgbx1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('sports_club_lite_sliderpgbx1',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide one:','sports-club-lite'),
		'section' => 'sports_club_lite_headerslider_sections'
	));	
	
	$wp_customize->add_setting('sports_club_lite_sliderpgbx2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('sports_club_lite_sliderpgbx2',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide two:','sports-club-lite'),
		'section' => 'sports_club_lite_headerslider_sections'
	));	
	
	$wp_customize->add_setting('sports_club_lite_sliderpgbx3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('sports_club_lite_sliderpgbx3',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide three:','sports-club-lite'),
		'section' => 'sports_club_lite_headerslider_sections'
	));	// Slider Section	
	
	$wp_customize->add_setting('sports_club_lite_slidereadmore_btn',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('sports_club_lite_slidereadmore_btn',array(	
		'type' => 'text',
		'label' => __('Add slider Read more button name here','sports-club-lite'),
		'section' => 'sports_club_lite_headerslider_sections',
		'setting' => 'sports_club_lite_slidereadmore_btn'
	)); // Slider Read More Button Text
	
	$wp_customize->add_setting('sports_club_lite_showslider_sections',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_showslider_sections', array(
	    'settings' => 'sports_club_lite_showslider_sections',
	    'section'   => 'sports_club_lite_headerslider_sections',
	     'label'     => __('Check To Show This Section','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Show Slider Section	
	 
	 
	 // Five boxes Services section
	$wp_customize->add_section('sports_club_lite_5boxs_sections', array(
		'title' => __('Five Column Features Services','sports-club-lite'),
		'description' => __('Select pages from the dropdown for 5 boxes services section','sports-club-lite'),
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',          
	));	
	
	
	$wp_customize->add_setting('sports_club_lite_5colservicespg1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_5colservicespg1',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_5boxs_sections',
	));		
	
	$wp_customize->add_setting('sports_club_lite_5colservicespg2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_5colservicespg2',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_5boxs_sections',
	));
	
	$wp_customize->add_setting('sports_club_lite_5colservicespg3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_5colservicespg3',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_5boxs_sections',
	));
	
	$wp_customize->add_setting('sports_club_lite_5colservicespg4',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_5colservicespg4',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_5boxs_sections',
	));
	
	$wp_customize->add_setting('sports_club_lite_5colservicespg5',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_5colservicespg5',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_5boxs_sections',
	));
	
	
	$wp_customize->add_setting('sports_club_lite_show_5boxes_sections',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_show_5boxes_sections', array(
	   'settings' => 'sports_club_lite_show_5boxes_sections',
	   'section'   => 'sports_club_lite_5boxs_sections',
	   'label'     => __('Check To Show This Section','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Show services section	
	 
	 
	 // About Sports Club
	$wp_customize->add_section('sports_club_lite_aboutus_sections', array(
		'title' => __('About Us Section','sports-club-lite'),
		'description' => __('Select Pages from the dropdown for about us section','sports-club-lite'),
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',          
	));		
	
	$wp_customize->add_setting('sports_club_lite_aboutus_pgecolumn',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_aboutus_pgecolumn',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_aboutus_sections',
	));		
	
	$wp_customize->add_setting('sports_club_lite_show_aboutus_pgecolumn',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_show_aboutus_pgecolumn', array(
	    'settings' => 'sports_club_lite_show_aboutus_pgecolumn',
	    'section'   => 'sports_club_lite_aboutus_sections',
	    'label'     => __('Check To Show This Section','sports-club-lite'),
	    'type'      => 'checkbox'
	));//Show About Sports Club 
	 
	
	// Three Column Our Services section
	$wp_customize->add_section('sports_club_lite_our_services_sections', array(
		'title' => __('Our Services Section','sports-club-lite'),
		'description' => __('Select pages from the dropdown for our services section','sports-club-lite'),
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',          
	));	
	
	$wp_customize->add_setting('sports_club_lite_servicestittlebx',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('sports_club_lite_servicestittlebx',array(	
		'type' => 'text',
		'label' => __('Add services title here','sports-club-lite'),
		'section' => 'sports_club_lite_our_services_sections',
		'setting' => 'sports_club_lite_servicestittlebx'
	)); 
	
	$wp_customize->add_setting('sports_club_lite_services_descbx',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('sports_club_lite_services_descbx',array(	
		'type' => 'text',
		'label' => __('Add services description here','sports-club-lite'),
		'section' => 'sports_club_lite_our_services_sections',
		'setting' => 'sports_club_lite_services_descbx'
	));	
	
	
	$wp_customize->add_setting('sports_club_lite_ourservices_pagecolumn1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_ourservices_pagecolumn1',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_our_services_sections',
	));		
	
	$wp_customize->add_setting('sports_club_lite_ourservices_pagecolumn2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_ourservices_pagecolumn2',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_our_services_sections',
	));
	
	$wp_customize->add_setting('sports_club_lite_ourservices_pagecolumn3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'sports_club_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'sports_club_lite_ourservices_pagecolumn3',array(
		'type' => 'dropdown-pages',			
		'section' => 'sports_club_lite_our_services_sections',
	));
	
	
	$wp_customize->add_setting('sports_club_lite_show_ourservices_sections',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_show_ourservices_sections', array(
	   'settings' => 'sports_club_lite_show_ourservices_sections',
	   'section'   => 'sports_club_lite_our_services_sections',
	   'label'     => __('Check To Show This Section','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//Show Our Services section 	 
	 
	 
	// Sidebar Options
	$wp_customize->add_section('sports_club_lite_sidebar_options', array(
		'title' => __('Sidebar Options','sports-club-lite'),		
		'priority' => null,
		'panel' => 	'sports_club_lite_panel_area',          
	));	
	
	$wp_customize->add_setting('sports_club_lite_removesidebar_from_frontapge',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_removesidebar_from_frontapge', array(
	   'settings' => 'sports_club_lite_removesidebar_from_frontapge',
	   'section'   => 'sports_club_lite_sidebar_options',
	   'label'     => __('Check to remove sidebar from frontpage','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//sidebar options
	 
	 
	 $wp_customize->add_setting('sports_club_lite_removesidebar_from_singlepost',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_removesidebar_from_singlepost', array(
	   'settings' => 'sports_club_lite_removesidebar_from_singlepost',
	   'section'   => 'sports_club_lite_sidebar_options',
	   'label'     => __('Check to remove sidebar from single post','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//single post sidebar options
	 
	 $wp_customize->add_setting('sports_club_lite_removethumb_blogpost_and_singlepost',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_removethumb_blogpost_and_singlepost', array(
	   'settings' => 'sports_club_lite_removethumb_blogpost_and_singlepost',
	   'section'   => 'sports_club_lite_sidebar_options',
	   'label'     => __('Check to remove thumbnail from blogpost and single post','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//remove features image for blog post
	 
	 
	 $wp_customize->add_setting('sports_club_lite_removesidebar_from_pages',array(
		'default' => false,
		'sanitize_callback' => 'sports_club_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'sports_club_lite_removesidebar_from_pages', array(
	   'settings' => 'sports_club_lite_removesidebar_from_pages',
	   'section'   => 'sports_club_lite_sidebar_options',
	   'label'     => __('Check to remove sidebar from pages','sports-club-lite'),
	   'type'      => 'checkbox'
	 ));//single post sidebar options
	 
		 
}
add_action( 'customize_register', 'sports_club_lite_customize_register' );

function sports_club_lite_custom_css(){ 
?>
	<style type="text/css"> 					
        a, .blogpost_layout h2 a:hover,
        #sidebar ul li a:hover,								
        .blogpost_layout h3 a:hover,
		.aboutus_contentcol h3 span,					
        .recent-post h6:hover,
		.blogreadmore:hover,						
        .blogpost_meta a:hover,		
        .button:hover,			           
		.footer-wrapper h2 span,
		.footer-wrapper ul li a:hover, 
		.footer-wrapper ul li.current_page_item a        				
            { color:<?php echo esc_html( get_theme_mod('sports_club_lite_color_scheme','#ec4613')); ?>;}					 
            
        .pagination ul li .current, .pagination ul li a:hover, 
        #commentform input#submit:hover,		
        .nivo-controlNav a.active,				
        .learnmore,
		.news-title,		
		.donatenow,		
		.sports_5col:hover .imgbx_5col,
		.sports_5col .readmorebtn,
		.nivo-caption .slide_more, 		
		.threecol_box:hover .pagereadmore,	
		.threecol_box:hover .pagereadmore:after,													
        #sidebar .search-form input.search-submit,				
        .wpcf7 input[type='submit'],				
        nav.pagination .page-numbers.current,
        .toggle a,
		.header_right,
		.header_right:after,
		.threecol_box:hover .threecol_thumbx,
		.nivo-caption .slide_more:before,
		.sitemenu ul li a:hover, 
	   .sitemenu ul li.current-menu-item a,
	   .sitemenu ul li.current-menu-parent a.parent,
	   .sitemenu ul li.current-menu-item ul.sub-menu li a:hover
            { background-color:<?php echo esc_html( get_theme_mod('sports_club_lite_color_scheme','#ec4613')); ?>;}
			
		.nivo-caption .slide_more:hover,	
		.tagcloud a:hover,
		.orange-dot,
		.blogreadmore:hover,
		.header_fixer.siteinner,		
		.sports_5col .imgbx_5col,
		 blockquote	        
            { border-color:<?php echo esc_html( get_theme_mod('sports_club_lite_color_scheme','#ec4613')); ?>;}	
			
         	
    </style> 
<?php                                
}
         
add_action('wp_head','sports_club_lite_custom_css');	 

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sports_club_lite_customize_preview_js() {
	wp_enqueue_script( 'sports_club_lite_customizer', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20191002', true );
}
add_action( 'customize_preview_init', 'sports_club_lite_customize_preview_js' );