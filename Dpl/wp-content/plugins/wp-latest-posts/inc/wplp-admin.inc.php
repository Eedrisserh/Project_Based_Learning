<?php


/**
 * Class WPLPAdmin
 * WP Latest Posts main class
 */
class WPLPAdmin
{
    //TODO: separate front-end and back-end methods, only include necessary code
    /**
     * Init version params
     *
     * @var boolean
     */
    public $version = false;
    /**
     * Specific field value properties to enforce
     *
     * @var array
     */
    private $enforce_fields = array(
        'amount_pages' => POSITIVE_INT_GT1,
        'amount_cols'  => POSITIVE_INT_GT1,
        'amount_rows'  => POSITIVE_INT_GT1,
        'per_page'     => POSITIVE_INT_GT1,
        'default_img'  => FILE_UPLOAD,
        'box_top'      => LI_TO_ARRAY,
        'box_left'     => LI_TO_ARRAY,
        'box_right'    => LI_TO_ARRAY,
        'box_bottom'   => LI_TO_ARRAY,
        'dfThumbnail'  => STRING_UNSET,
        'dfTitle'      => STRING_UNSET,
        'dfText'       => STRING_UNSET,
        'dfDate'       => STRING_UNSET,
        'dfCategory'   => STRING_UNSET,
        'dfAuthor'     => STRING_UNSET,
        'dfReadMore'   => STRING_UNSET
    );
    /**
     * Init width unit value params
     *
     * @var boolean
     */
    public $width_unit_values = array(
        '%',
        'em',
        'px'
    );

    /**
     * Field default values
     *
     * @var array
     */
    private $field_defaults = array(
        'source_type'            => 'src_category',
        'cat_post_source_order'  => 'date',
        'cat_post_source_asc'    => 'desc',
        'cat_source_order'       => 'date',
        'cat_source_asc'         => 'desc',
        'pg_source_order'        => 'order',
        'pg_source_asc'          => 'desc',
        'cat_list_source_order'  => 'id',
        'cat_list_source_asc'    => 'desc',
        'content_language'       => 'en',
        'content_include'        => 1,
        'show_title'             => 1, // Wether or not to display the block title
        'amount_pages'           => 1,
        'amount_cols'            => 3,
        'pagination'             => 2,
        'max_elts'               => 30,
        'per_page'               => 10,
        'off_set'                => 0, //number posts to skip
        'total_width'            => 100,
        'total_width_unit'       => 0, //%
        'crop_title'             => 2,
        'crop_title_len'         => 1,
        'crop_text'              => 2,
        'crop_text_len'          => 2,
        'autoanimation'          => 0,
        'autoanimation_trans'    => 1,
        'autoanimation_slidedir' => 0,
        'autoanim_loop'          => 1,
        'autoanim_pause_hover'   => 1,
        'autoanim_pause_action'  => 1,
        'autoanim_touch_action'  => 1,
        'layzyload_default'      => 0,
        'open_link'              => 0,
        'load_more'              => 0,
        'force_icon'             => 0,
        'theme'                  => 'default',
        'box_top'                => array(),
        'box_left'               => array('Thumbnail'),
        'box_right'              => array('Title', 'Date', 'Text'),
        'box_bottom'             => array(),
        'thumb_img'              => 1, // 0 == use featured image
        'image_size'             => 'automatic',
        'thumb_width'            => 150, // in px
        'thumb_height'           => 150, // in px
        'crop_img'               => 0, // 0 == do not crop (== resize to fit)
        'margin_left'            => 0,
        'margin_top'             => 0,
        'margin_right'           => 4,
        'custom_css'             => '',
        'margin_bottom'          => 4,
        'date_fmt'               => '',
        'no_post_text'           => '',
        'read_more'              => '',
        'default_img_previous'   => '', // Overridden in constructor
        'default_img'            => '', // Overridden in constructor
        'dfThumbnail'            => 'Thumbnail',
        'dfTitle'                => 'Title',
        'dfText'                 => 'Text',
        'dfDate'                 => 'Date',
        'dfCategory'             => 'Category',
        'image_position_width'   => '30'
    );


    /**
     * Headers for style.css files.
     *
     * @var array
     */
    private static $file_headers = array(
        'Name'        => 'Theme Name',
        'ThemeURI'    => 'Theme URI',
        'Description' => 'Description',
        'Author'      => 'Author',
        'AuthorURI'   => 'Author URI',
        'Version'     => 'Version',
        'Template'    => 'Template',
        'Status'      => 'Status',
        'Tags'        => 'Tags',
        'TextDomain'  => 'Text Domain',
        'DomainPath'  => 'Domain Path',
    );

    /**
     * Default settings value of WPLP
     *
     * @var array
     */
    private $default_settings = array(
        'data_cache' => '0',
        'cache_interval_value' => '1'
    );

    /**
     * Switch button checkbox
     *
     * @var array
     */
    private $switch_button = array(
        'show_title',
        'full_height',
        'crop_img'
    );

    /**
     * Counts how many widgets are being displayed
     *
     * @var integer
     */
    public $widget_count = 0;

    /**
     * Settings of WPLP
     *
     * @var array
     */
    private $wplp_settings = array();

    /**
     * Constructor
     *
     * @param array $opts Option
     */
    public function __construct($opts)
    {
        global $field_defaults;
        /**
         * Setup default image
         */
        $this->field_defaults['default_img_previous'] = plugins_url(DEFAULT_IMG, dirname(__FILE__));
        $this->field_defaults['default_img']          = plugins_url(DEFAULT_IMG, dirname(__FILE__));
        $this->version                                = $opts['version'];
        $this->tdomain                                = $opts['translation_domain'];
        $this->plugin_file                            = $opts['plugin_file'];
        $this->plugin_dir                             = dirname(plugin_basename($this->plugin_file));

        $field_defaults = $this->field_defaults;
        //load language
        load_plugin_textdomain(
            $this->tdomain,
            WP_PLUGIN_URL . '/' . $this->plugin_dir . '/languages',
            $this->plugin_dir . '/languages'
        );

        $this->wplp_settings = get_option('wplp_settings', $this->default_settings);
        /**
         * Register our widget (implemented in separate wplp-widget.inc.php class file)
         */
        add_action(
            'widgets_init',
            function () {
                register_widget('WPLPWidget');
            }
        );

        /**
         * Sets up custom post types
         */
        add_action('init', array($this, 'setupCustomPostTypes'));
        /**
         * Register our shortcode
         */
        add_shortcode('frontpage_news', array($this, 'applyShortcode'));
        add_action('wp_ajax_wplp_load_html', array($this, 'wplpLoadHtml'));
        if (is_admin()) {
            /**
             * Customize custom post editor screen
             */
            add_action('admin_menu', array($this, 'setupWPLPMenu'));
            add_action('load-toplevel_page_wplp-widget', array($this, 'saveWPLPData'));

            add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'), 100);
            add_action('enqueue_block_editor_assets', array($this, 'addEditorAssets'));
            if (isset($_GET['page']) && $_GET['page'] === 'wplp-widget') { // phpcs:disable WordPress.Security.NonceVerification.Recommended -- view only
                add_action('wp_print_scripts', array($this, 'dequeueAdminScripts'));
                // Add action after css of hueman theme
                add_action('ot_admin_styles_after', array($this, 'dequeueAdminHuemanStyles'));
            }

            /**
             * Customize Tiny MCE Editor
             */
            add_action('admin_init', array($this, 'setupTinyMce'));
            add_action('in_admin_footer', array($this, 'editorFooterScript'));

            /**
             * Tiny MCE 4.0 fix
             */
            if (get_bloginfo('version') >= 3.9) {
                add_action('media_buttons', array($this, 'editorButton'), 1000); //1000 = put it at the end
            }

            if (!class_exists('WPLPAddonAdmin')) {
                add_filter('plugin_row_meta', array($this, 'addProLink'), 10, 2);
            }
            //ajax of mutilsite
            add_action('wp_ajax_change_cat_multisite', array($this, 'changeCatMultisite'));
            // Ajax of content language
            add_action(
                'wp_ajax_change_source_type_by_language',
                array('WPLPLanguageContent', 'changeSourceTypeByLanguage')
            );
            add_action('wp_ajax_wplp_delete_blocks', array($this, 'deleteBlocks'));
            add_action('wp_ajax_wplp_duplicate_block', array($this, 'duplicateBlock'));
            add_action('wp_ajax_wplp_set_close_notification', array($this, 'setCookieNotification'));
            add_action('wp_ajax_wplp_get_count_posts', array($this, 'getCountPosts'));

            // Disable all admin notice for page belong to plugin
            add_action('admin_print_scripts', function () {
                global $wp_filter;
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
                if ((!empty($_GET['page']) && in_array($_GET['page'], array('wplp-widget')))) {
                    if (is_user_admin()) {
                        if (isset($wp_filter['user_admin_notices'])) {
                            unset($wp_filter['user_admin_notices']);
                        }
                    } elseif (isset($wp_filter['admin_notices'])) {
                        unset($wp_filter['admin_notices']);
                    }
                    if (isset($wp_filter['all_admin_notices'])) {
                        unset($wp_filter['all_admin_notices']);
                    }
                }
            });
            add_action('save_post', array($this, 'addPostMetaKey'), 10, 3);
        } else {
            if ($this->wplp_settings['data_cache'] === '0') {
                add_action('wp_head', array($this, 'setPostViews'));
            } else {
                add_action('wp_head', array($this, 'savePostTransient'));
            }
            /**
             * Load our theme stylesheet on the front if necessary
             */
            add_action('wp_print_styles', array($this, 'addStylesheet'));

            /**
             * Load our fonts on the front if necessary
             */
            add_action('wp_print_styles', array($this, 'addFonts'));

            /**
             * Load our front-end slide control script *
             */
            add_action('the_posts', array($this, 'prefixEnqueue'), 100);
        }
        add_filter('cron_schedules', array($this, 'addCronSchedules'));
        // Schedule update post views
        $time = intval($this->wplp_settings['cache_interval_value']) ? (int) $this->wplp_settings['cache_interval_value'] : 2;
        if (! wp_next_scheduled('wplp_update_post_views')) {
            wp_schedule_event(time(), $time.'min', 'wplp_update_post_views');
        }

        add_action('wplp_update_post_views', array($this, 'updatePostViews'));
    }

    /**
     * Load HTML block
     *
     * @return void
     */
    public function wplpLoadHtml()
    {
        if (!empty($_REQUEST['id'])) {
            $widget = get_post($_REQUEST['id']);
            $html = '';
            if (isset($widget) && !empty($widget)) {
                $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                $styles = $this->loadInlineStyle($widget->settings, $widget->ID);
                $css = $styles['css'];
                $str = $styles['str'];
                $edit_link = '<a href="' . admin_url('admin.php?page=wplp-widget&view=block&id=' . $widget->ID) . '" target="_blank">' . $widget->post_title . '</a>';
                $html .= '<style>' . $css . $str . '</style>';
                $html .= '<div class="wplp_title_block">'. esc_html__('LATEST POSTS BLOCK: ', 'wp-latest-posts') . $edit_link . '</div>';
                $html .= do_shortcode('[frontpage_news widget="' . $_REQUEST['id'] . '"]', true);
                wp_send_json(array('status' => true, 'html' => $html, 'settings' => $widget->settings));
            }
        }

        wp_send_json(array('status' => false));
    }

    /**
     * Change color to rgb format hex2rgba
     *
     * @param string  $color   Color style
     * @param boolean $opacity Opacity style
     *
     * @return string
     */
    private function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }
        //Sanitize $color if "#" is provided
        if ($color[0] === '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) === 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) === 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(',', $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    /**
     * Load inline style
     *
     * @param array   $settings Block settings
     * @param integer $idWidget Block ID
     *
     * @return array
     */
    public function loadInlineStyle($settings, $idWidget)
    {
        $colorTheme          = (isset($settings['defaultColor']) ? $settings['defaultColor'] : '');
        if ($settings['theme'] === 'material-vertical') {
            $overlay_background          = (isset($settings['overlay_background']) ? $settings['overlay_background'] : '0.2');
            $color               = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), $overlay_background);
        } else {
            $overlay_background          = (isset($settings['overlay_background']) ? $settings['overlay_background'] : '0.7');
            $color               = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), $overlay_background);
        }

        $icon_color = (isset($settings['addon_icon_color']) ? $settings['addon_icon_color'] : '#ffffff');
        $bg_icon_color = (isset($settings['addon_bg_icon_color']) ? $settings['addon_bg_icon_color'] : 'transparent');

        if (isset($settings['colorpicker']) && $settings['colorpicker'] !== 'transparent') {
            $colorfull           = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), 1);
        } else {
            $colorfull = 'transparent';
        }

        $nbcol               = $settings['amount_cols'];
        $theme_classDashicon = ' ' . basename($settings['theme']);
        $css = '';
        $str = '';
        if (($theme_classDashicon !== ' default')) {
            $widthtotal     = 100;
            $width1         = $widthtotal / $nbcol;
            $width2         = $widthtotal;
            $margin_element = 10;
            if ($theme_classDashicon === ' material-vertical') {
                $margin_element = 20;
            }
            $gui            = ($margin_element * ($nbcol - 1));

            if ($theme_classDashicon === ' masonry-category') {
                $css .= '#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li .img_cropper:before{color: '. $icon_color .';background: center center no-repeat ' . $colorfull . '} ';
                $css .= '#wplp_widget_' . $idWidget .
                    ' .masonry-category .wpcu-front-box.bottom .category:before{background:' . $color . '}';
                $css .= '#wplp_widget_' . $idWidget .
                    ' .read-more{border-top:1px solid ' . $color . '}';
                $css .= '#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li{ width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important; }}';
            } elseif ($theme_classDashicon === ' masonry') {
                wp_enqueue_style(
                    'wplp-settings-google-icon',
                    'https://fonts.googleapis.com/icon?family=Material+Icons'
                );

                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['material_icon_selector']) && $settings['material_icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:after {';
                        $css .= "content:'\\". $settings['material_icon_selector'] ."';";
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: '. $color .';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:after {';
                        $css .= 'background-color: '. $bg_icon_color .';';
                        $css .= 'color: '. $icon_color .';';
                        $css .= '}';
                    } elseif (isset($settings['icon_selector']) && $settings['icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background: url(' .
                            $settings['icon_selector'] . ') center no-repeat '. $color .';';
                        $css .= '}';
                    } else {
                        $img_dir = plugins_url('wp-latest-posts-addon') . '/themes/masonry/img/overimage.png';
                        $css     .= '#wplp_widget_' . $idWidget .
                            " .wplp_listposts li .img_cropper:before{background: url('" .
                            $img_dir . "') center center no-repeat " . $color . ';}';
                    }
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'background: '. $color .';';
                    $css .= '}';
                }

                $css     .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li{
                width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css     .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important;}}';
            } elseif ($theme_classDashicon === ' material-vertical') {
                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['material_icon_selector']) && $settings['material_icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: '. $color .';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= 'content:"\\'. $settings['material_icon_selector'] .'";';
                        $css .= 'background-color: '. $bg_icon_color .';';
                        $css .= 'color: '. $icon_color .';';
                        $css .= '}';
                    } elseif (isset($settings['icon_selector']) && $settings['icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background: '. $color .';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= "background: url('" .
                            $settings['icon_selector'] . "')  center no-repeat;";
                        $css .= '}';
                    } else {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: '. $color .';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= 'background-color: transparent;';
                        $css .= '}';
                    }
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'background-color: '. $color .';';
                    $css .= '}';

                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                    $css .= 'background-color: transparent;';
                    $css .= '}';
                }

                $css     .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li{
                width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css     .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important;}}';
            } elseif ($theme_classDashicon === ' portfolio') {
                if ($colorTheme === 'yes') {
                    $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts{background-color: ' . $colorfull . '}';
                } else {
                    $colorfull = 'transparent';
                }

                $css .= '#wplp_widget_' . $idWidget .
                    ' .portfolio .wpcu-front-box.bottom .category::before{ background:' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget . ' .read-more{border-top:1px solid ' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li{margin: ' .
                    ($width1 / 20) . '%; width: ' . ($width1 - ($width1 * 2 / 20)) . '%;}';
                $css .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li {width: ' . ($width2 - ($width1 * 2 / 20)) . '% !important;}}';
                $css .= '#wplp_widget_' . $idWidget .
                    ' .wplp_listposts {background: center center no-repeat ' . $colorfull .
                    ';background-color: ' . $colorfull . ';}';
            } elseif ($theme_classDashicon === ' smooth-effect') {
                $css .= '#wplp_widget_' . $idWidget .
                    ' li.smooth-effect:hover .wpcu-front-box a .title { border-top: 1px solid ' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget .
                    ' li.smooth-effect:hover .wpcu-front-box a .text { border-bottom: 1px solid ' . $color . ';}';
            } elseif ($theme_classDashicon === ' timeline') {
                if ($colorfull === 'rgba(255,255,255,1)') {
                    $innercolor = '#000';
                } else {
                    $innercolor = '#fff';
                }
                $css .= '#wplp_widget_' . $idWidget .
                    ' .wplp_listposts  .wpcu-front-box.top .img_cropper:before {color:' .
                    $innercolor . '; background:' . $colorfull . ';}';
            }

            if ($theme_classDashicon === ' masonry-category') {
                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['dashicons_selector'])) {
                        if ($settings['dashicons_selector'] === '') {
                            $str .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                            $str .= 'content: none ;';
                            $str .= '}';
                        } else {
                            $str .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                            $str .= "content:'\\" . $settings['dashicons_selector'] . "';";
                            $str .= '}';
                        }
                    }
                }
            }

            return array('css' => $css, 'str' => $str);
        }
    }

    /**
     * Action save our setting fields into the WP database
     *
     * @return void
     */
    public function saveWPLPData()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (isset($_POST['wplp_update_settings'])) {
            if (isset($_POST['wplp_id'])) {
                $post_id = $_POST['wplp_id'];
                $this->saveCustomPost($post_id, 'update');
            }
        }

        if (isset($_POST['wplp_save_tool_settings'])) {
            $this->saveToolOptions();
        }

        if (isset($_POST['wplp_addnew_settings'])) {
            $this->saveCustomPost('', 'addnew');
        }

        if (isset($_POST['wplp_save_draft'])) {
            $this->saveCustomPost('', 'savedraft');
        }

        if (isset($_POST['wplp_sheduled_settings'])) {
            if (isset($_POST['wplp_id'])) {
                $post_id = $_POST['wplp_id'];
                $this->saveCustomPost($post_id, 'sheduled');
            }
        }
        // phpcs:enable
    }

    /**
     * Save our custom setting fields Tools into the options table
     *
     * @return boolean
     */
    public function saveToolOptions()
    {
        if (!wp_verify_nonce($_POST['_wplp_nonce'], 'wplp_tool_settings')) {
            return false;
        }
        $wplp_settings = get_option('wplp_settings');
        $options = array();
        foreach ($_POST as $field_name => $field_value) {
            if (preg_match('/^wplp_/', $field_name)) {
                if (preg_match('/_none$/', $field_name) || preg_match('/_save_tool_settings$/', $field_name)) {
                    continue;
                }
                $field_name = preg_replace('/^wplp_/', '', $field_name);
                if ($field_name === 'cache_interval_value') {
                    $options[$field_name] = intVal($field_value) > 0 ? intVal($field_value) : 1;
                } else {
                    $options[$field_name] = sanitize_text_field($field_value);
                }
            }
        }
        if ($wplp_settings === false) {
            add_option('wplp_settings', $options);
        } else {
            update_option('wplp_settings', $options);
        }

        wp_safe_redirect(admin_url('admin.php?page=wplp-widget&save_tool=success'));

        return true;
    }

    /**
     * Save our custom setting fields in the WP database
     *
     * @param integer $post_id Id of post
     * @param string  $type    Type of function
     *
     * @return void|boolean
     */
    public function saveCustomPost($post_id, $type)
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['_wplp_nonce'], 'wplp_settings')) {
            return false;
        }
        // Check permission
        if (!current_user_can('publish_posts')) {
            return false;
        }
        // Get old data
        $my_settings = array();
        if (!empty($post_id)) {
            if (is_multisite()) {
                switch_to_blog(get_current_blog_id());
                $my_settings = get_post_meta($post_id, '_wplp_settings', true);
                restore_current_blog();
            } else {
                $my_settings = get_post_meta($post_id, '_wplp_settings', true);
            }
        }
        $my_settings = wp_parse_args($my_settings, $this->field_defaults);
        /**
         * File uploads
         */
        foreach ($_FILES as $field_name => $field_value) {
            if (preg_match('/^wplp_/', $field_name)) {
                //error_log( 'matched wplp' );            //Debug
                $new_field_name = preg_replace('/^wplp_/', '', $field_name);
                if (is_uploaded_file($_FILES[$field_name]['tmp_name'])) {
                    $uploads    = wp_upload_dir();
                    $upload_dir = ($uploads['path']) . '/';
                    $upload_url = ($uploads['url']) . '/';
                    if (preg_match('/(\.[^\.]+)$/', $_FILES[$field_name]['name'], $matches)) {
                        $ext = $matches[1];
                    }
                    $upload_file = 'wplp_default_img_' . date('YmdHis') . $ext;

                    $_FILES[$field_name]['name'] = $upload_file;

                    $attachment_id = media_handle_sideload($_FILES[$field_name], 0);
                    if (!is_wp_error($attachment_id)) {
                        chmod($upload_dir . $upload_file, 0664);
                        $my_settings[$new_field_name]         = $upload_url . $upload_file;
                        $my_settings[$new_field_name . '_id'] = $attachment_id;
                    } else {
                        wp_die(esc_html($attachment_id->get_error_message()));
                    }
                } else {
                    /**
                     * Keep the previous image
                     */
                    if (isset($_POST[$field_name . '_previous'])) {
                        $my_settings[$new_field_name] = $_POST[$field_name . '_previous'];
                    }

                    if (isset($_POST[$field_name . '_id_previous'])) {
                        $my_settings[$new_field_name . '_id'] = $_POST[$field_name . '_id_previous'];
                    }
                }
            }
        }

        if (empty($_POST['wplp_hover_img_check'])) {
            $my_settings['icon_selector'] = '';
        }

        /**
         * Normal fields
         */
        foreach ($_POST as $field_name => $field_value) {
            if (preg_match('/^wplp_/', $field_name)) {
                if (preg_match('/_none$/', $field_name)) {
                    continue;
                }
                $field_name = preg_replace('/^wplp_/', '', $field_name);
                if (is_array($field_value)) {
                    $my_settings[$field_name] = $field_value;
                } else {
                    if (preg_match('/^box_/', $field_name)) {
                        /**
                         * No sanitizing for those fields that are supposed to contain html
                         */
                        $my_settings[$field_name] = $field_value;
                    } else {
                        $my_settings[$field_name] = sanitize_text_field($field_value);
                    }

                    /**
                     * Enforce specific field value properties
                     */
                    if (isset($this->enforce_fields[$field_name])) {
                        if (POSITIVE_INT_GT1 === $this->enforce_fields[$field_name]) {
                            $my_settings[$field_name] = intval($my_settings[$field_name]);
                            if ($my_settings[$field_name] < 1) {
                                $my_settings[$field_name] = 1;
                            }
                        }
                        if (BOOL === $this->enforce_fields[$field_name]) {
                            $my_settings[$field_name] = intval($my_settings[$field_name]);
                            if ($my_settings[$field_name] < 1) {
                                $my_settings[$field_name] = 0;
                            }
                            if ($my_settings[$field_name] >= 1) {
                                $my_settings[$field_name] = 1;
                            }
                        }
                        if (LI_TO_ARRAY === $this->enforce_fields[$field_name]) {
                            if ($field_value) {
                                $values = preg_split('/<\/li><li[^>]*>/i', $field_value);
                            } else {
                                $values = array();
                            }
                            if ($values) {
                                array_walk(
                                    $values,
                                    function (&$value) {
                                        $value = strip_tags($value);
                                    }
                                );
                            }
                            $my_settings[$field_name] = $values;
                        }
                    }
                }
            }
        }

        foreach ($this->enforce_fields as $key => $value) {
            if (STRING_UNSET === $value) {
                $name = WPLP_PREFIX . $key;
                if (!isset($_POST[$name])) {
                    $my_settings[$key] = '';
                }
            }
        }

        if (isset($_POST['wplp_defaultColor'])) {
            $my_settings['defaultColor'] = 'yes';
        } else {
            $my_settings['defaultColor'] = 'no';
        }

        foreach ($this->switch_button as $value) {
            $name = WPLP_PREFIX . $value;
            if (isset($_POST[$name])) {
                $my_settings[$value] = 1;
            } else {
                $my_settings[$value] = 0;
            }
        }


        $post_date = date('Y-m-d h:i:s', current_time('timestamp'));
        if (isset($_POST['wplp_edit_post_date'])) {
            $aa        = $_POST['aa'];
            $mm        = $_POST['mm'];
            $jj        = $_POST['jj'];
            $hh        = $_POST['hh'];
            $mn        = $_POST['mn'];
            $ss        = $_POST['ss'];
            $aa        = ($aa <= 0) ? date('Y') : $aa;
            $mm        = ($mm <= 0) ? date('n') : $mm;
            $jj        = ($jj > 31) ? 31 : $jj;
            $jj        = ($jj <= 0) ? date('j') : $jj;
            $hh        = ($hh > 23) ? $hh - 24 : $hh;
            $mn        = ($mn > 59) ? $mn - 60 : $mn;
            $ss        = ($ss > 59) ? $ss - 60 : $ss;
            $post_date = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $aa, $mm, $jj, $hh, $mn, $ss);

            $valid_date = wp_checkdate($mm, $jj, $aa, $post_date);
            if (!$valid_date) {
                return new WP_Error('Invalid_date', __('Invalid date.', 'wp-latest-posts'));
            }
        }

        if ($type === 'savedraft') {
            $_POST['post_status'] = 'draft';
        } elseif ($type === 'addnew') {
            $_POST['post_status'] = 'publish';
        }

        if (isset($_POST['post_visibility'])) {
            switch ($_POST['post_visibility']) {
                case 'public':
                    $_POST['post_password'] = '';
                    break;
                case 'password':
                    unset($_POST['sticky']);
                    break;
                case 'private':
                    $_POST['post_status']   = 'private';
                    $_POST['post_password'] = '';
                    unset($_POST['sticky']);
                    break;
            }
        }

        if ('publish' === $_POST['post_status']) {
            $now = gmdate('Y-m-d H:i:59');
            if (mysql2date('U', $post_date, false) > mysql2date('U', $now, false)) {
                $_POST['post_status'] = 'future';
            }
        } elseif ('future' === $_POST['post_status']) {
            $now = gmdate('Y-m-d H:i:59');
            if (mysql2date('U', $post_date, false) <= mysql2date('U', $now, false)) {
                $_POST['post_status'] = 'publish';
            }
        }

        if ($type === 'sheduled') {
            $_POST['post_status'] = 'future';
        }

        if ($type === 'addnew' || $type === 'savedraft') { // Create new
            $post_id = wp_insert_post(array(
                'post_title'    => (isset($_POST['post_title']) ? $_POST['post_title'] : ''),
                'post_type'     => 'wplp-news-widget',
                'post_status'   => (isset($_POST['post_status']) ? $_POST['post_status'] : 'publish'),
                'post_password' => (isset($_POST['post_password']) ? $_POST['post_password'] : ''),
                'post_date'     => $post_date,
                'post_date_gmt' => gmdate('Y-m-d H:i:s', strtotime($post_date)),
                'meta_input'    => array(
                    '_wplp_settings' => $my_settings,
                )
            ));
        } else { //Update param with post ID
            wp_update_post(array(
                'ID'            => $post_id,
                'post_title'    => (isset($_POST['post_title']) ? $_POST['post_title'] : ''),
                'post_status'   => (isset($_POST['post_status']) ? $_POST['post_status'] : 'publish'),
                'post_password' => (isset($_POST['post_password']) ? $_POST['post_password'] : ''),
                'post_date'     => $post_date,
                'post_date_gmt' => gmdate('Y-m-d H:i:s', strtotime($post_date))
            ));

            update_post_meta($post_id, '_wplp_settings', $my_settings);
        }

        wp_safe_redirect(admin_url('admin.php?page=wplp-widget&view=block&id=' . $post_id . '&save_block=success'));

        return true;
    }

    /**
     * Duplicate block
     *
     * @return void
     */
    public function duplicateBlock()
    {
        if (empty($_POST['ajaxNonce'])
            || !wp_verify_nonce($_POST['ajaxNonce'], 'wplp_blocks_nonce')) {
            die();
        }

        if (isset($_POST['id'])) {
            $original_id  = $_POST['id'];
            // Duplicate the post
            $duplicate_id = $this->duplicatePost($original_id);
            if ($duplicate_id) {
                wp_send_json(array('status' => true));
            }
        }

        wp_send_json(array('status' => false));
    }

    /**
     * Duplicate block
     *
     * @param integer $original_id Block ID
     *
     * @return integer|WP_Error
     */
    public function duplicatePost($original_id)
    {
        // Get access to the database
        global $wpdb;
        // Get the post as an array
        $duplicate = get_post($original_id, 'ARRAY_A');
        // Modify some of the elements
        $duplicate['post_title'] = $duplicate['post_title'].' Copy';
        $duplicate['post_name'] = sanitize_title($duplicate['post_name'].'-copy');

        // Set the post date
        $timestamp = current_time('timestamp', 0);
        $timestamp_gmt = current_time('timestamp', 1);
        $duplicate['post_date'] = date('Y-m-d H:i:s', $timestamp);
        $duplicate['post_date_gmt'] = date('Y-m-d H:i:s', $timestamp_gmt);
        $duplicate['post_modified'] = date('Y-m-d H:i:s', current_time('timestamp', 0));
        $duplicate['post_modified_gmt'] = date('Y-m-d H:i:s', current_time('timestamp', 1));

        // Remove some of the keys
        unset($duplicate['ID']);
        unset($duplicate['guid']);
        unset($duplicate['comment_count']);

        // Insert the post into the database
        $duplicate_id = wp_insert_post($duplicate);

        // Duplicate all the taxonomies/terms
        $taxonomies = get_object_taxonomies($duplicate['post_type']);
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($original_id, $taxonomy, array('fields' => 'names'));
            wp_set_object_terms($duplicate_id, $terms, $taxonomy);
        }

        // Duplicate all the custom fields
        $custom_fields = get_post_custom($original_id);
        foreach ($custom_fields as $key => $value) {
            if (is_array($value) && count($value) > 0) {
                foreach ($value as $i => $v) {
                    $result = $wpdb->insert($wpdb->prefix . 'postmeta', array(
                        'post_id' => $duplicate_id,
                        'meta_key' => $key,
                        'meta_value' => $v
                    ));
                }
            }
        }

        return $duplicate_id;
    }

    /**
     * Ajax for delete blocks
     *
     * @return boolean,void     Return false if failure, echo json on success
     */
    public function deleteBlocks()
    {
        // Check users permissions
        if (!current_user_can('delete_pages')) {
            wp_send_json(__('No permission!', 'wp-latest-posts'), 403);
            return false;
        }

        if (!wp_verify_nonce($_POST['ajaxNonce'], 'wplp_blocks_nonce')) {
            wp_send_json(__('Fail to verify nonce!', 'wp-latest-posts'), 400);
            return false;
        };

        $id_to_delete = $_POST['ids'];
        if (count($id_to_delete)) {
            $deleted = array();
            foreach ($id_to_delete as $id) {
                if (wp_delete_post($id)) {
                    delete_post_meta($id, '_wplp_settings');
                    array_push($deleted, $id);
                }
            }
            wp_send_json(array('deleted' => $deleted), 200);
        }
    }

    /**
     * Sets up WP custom post types
     *
     * @return void
     */
    public function setupCustomPostTypes()
    {
        $labels = array(
            'name'               => __('WP Latest Posts Blocks', 'wp-latest-posts'),
            'singular_name'      => __('WPLP Block', 'wp-latest-posts'),
            'add_new'            => __('Add New', 'wp-latest-posts'),
            'add_new_item'       => __('Add New WPLP Block', 'wp-latest-posts'),
            'edit_item'          => __('Edit WPLP Block', 'wp-latest-posts'),
            'new_item'           => __('New WPLP Block', 'wp-latest-posts'),
            'all_items'          => __('All News Blocks', 'wp-latest-posts'),
            'view_item'          => __('View WPLP Block', 'wp-latest-posts'),
            'search_items'       => __('Search WPLP Blocks', 'wp-latest-posts'),
            'not_found'          => __('No WPLP Block found', 'wp-latest-posts'),
            'not_found_in_trash' => __('No WPLP Block found in Trash', 'wp-latest-posts'),
            'parent_item_colon'  => '',
            'menu_name'          => __('Latest Posts', 'wp-latest-posts')
        );
        register_post_type(
            CUSTOM_POST_NEWS_WIDGET_NAME,
            array(
                'public'        => false,
                'show_ui'       => false,
                'menu_position' => 5,
                'labels'        => $labels,
                'supports'      => array(
                    'title',
                    'author'
                ),
                'menu_icon'     => 'dashicons-admin-page',
            )
        );
    }

    /**
     * Register menu page
     *
     * @return void
     */
    public function setupWPLPMenu()
    {
        // Add main menu
        add_menu_page(
            __('WP Latest Posts', 'wp-latest-posts'),
            __('WP Latest Posts', 'wp-latest-posts'),
            'manage_options',
            'wplp-widget',
            array($this, 'loadPages'),
            'dashicons-welcome-write-blog',
            5
        );

        // Add submenu
        $submenu_pages = array(
            array(
                'wplp-widget',
                '',
                __('All News Blocks', 'wp-latest-posts'),
                'manage_options',
                'wplp-widget',
                array($this, 'loadPages'),
                null,
                5
            )
        );

        if (count($submenu_pages)) {
            foreach ($submenu_pages as $submenu_page) {
                // Add submenu page
                add_submenu_page(
                    $submenu_page[0],
                    __('Latest Posts', 'wp-latest-posts'),
                    $submenu_page[2],
                    $submenu_page[3],
                    $submenu_page[4],
                    $submenu_page[5]
                );
            }
        }

        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- view only
        if (isset($_GET['page']) && $_GET['page'] === 'wplp-widget') {
            if (isset($_GET['view']) && $_GET['view'] !== 'block') {
                wp_safe_redirect(admin_url('admin.php?page=wplp-widget'));
                exit();
            }
            if (isset($_GET['view']) && $_GET['view'] === 'block') {
                if (!isset($_GET['id'])) {
                    wp_safe_redirect(admin_url('admin.php?page=wplp-widget'));
                    exit();
                }

                if ($_GET['id'] !== 'addnew') {
                    if (!is_numeric($_GET['id'])) {
                        wp_die(esc_html__('You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?', 'wp-latest-posts'));
                    }

                    $post = get_post($_GET['id']);
                    if (!$post || $post->post_type !== 'wplp-news-widget') {
                        wp_die(esc_html__('You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?', 'wp-latest-posts'));
                    }
                }
            }
        }
        // phpcs:enable
    }


    /**
     * Include display page
     *
     * @return void
     */
    public function loadPages()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- View request, no action
        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                // phpcs:enable
                case 'wplp-configuration':
                    require_once WPLP_PLUGIN_PATH . '/inc/admin/views/configuration.php'; //view config page
                    break;
                default:
                    require_once WPLP_PLUGIN_PATH . '/inc/admin/views/widget.php'; //view wplp block settings
                    break;
            }
        }
    }

    /**
     * Require tab
     *
     * @param string $tab Tab name
     *
     * @return void
     */
    public function loadTabs($tab)
    {
        require_once WPLP_PLUGIN_PATH . '/inc/admin/views/tabs/' . $tab . '.php';
    }

    /**
     * Change category of blog
     *
     * @return void
     */
    public function changeCatMultisite()
    {
        check_ajax_referer('wplp-back-nonce', 'ajaxnonce');
        $val_blog         = '';
        $type             = '';
        $content_language = '';
        if (isset($_POST['val_blog'])) {
            $val_blog = $_POST['val_blog'];
        }
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        if (isset($_POST['content_language'])) {
            $content_language = $_POST['content_language'];
        }
        $output = '';
        $cats   = array();
        if ('all_blog' === $val_blog) {
            $allcats = array();
            $blogs = get_sites();
            foreach ($blogs as $blog) {
                switch_to_blog((int) $blog->blog_id);
                if (strpos($type, 'post') !== false) {
                    $allcats = get_categories(array('hide_empty' => false));
                    if (!empty($content_language)) {
                        /**
                         * Get list category via multilanguage plugin.
                         *
                         * @param array  List category
                         * @param string Language to translate
                         *
                         * @internal
                         *
                         * @return array
                         */
                        $allcats = apply_filters('wplp_get_category_by_language', $allcats, $content_language);
                    }
                } elseif (strpos($type, 'page') !== false) {
                    if (class_exists('WPLPAddonAdmin')) {
                        $allcats = get_pages();
                        if (!empty($content_language)) {
                            /**
                             * Get list pages via multilanguage plugin.
                             *
                             * @param array  List pages
                             * @param string Language to translate
                             *
                             * @internal
                             *
                             * @return array
                             */
                            $allcats = apply_filters('wplp_get_pages_by_language', $allcats, $content_language);
                        }
                    }
                } elseif (strpos($type, 'tag') !== false) {
                    $allcats = get_tags();
                    if (!empty($content_language) && !empty($allcats)) {
                        /**
                         * Get list tags via multilanguage plugin.
                         *
                         * @param array  List tags
                         * @param string Language to translate
                         *
                         * @internal
                         *
                         * @return array
                         */
                        $allcats = apply_filters('wplp_get_tags_by_language', $allcats, $content_language);
                    }
                } elseif (strpos($type, 'cat_list') !== false) {
                    $allcats = get_categories(array('hide_empty' => false));
                }

                foreach ($allcats as $allcat) {
                    $allcat->blog = (int) $blog->blog_id;
                    $cats[]       = $allcat;
                }
                restore_current_blog();
            }
        } else {
            switch_to_blog((int) $val_blog);
            if (strpos($type, 'post') !== false) {
                $cats = get_categories(array('hide_empty' => false));
                if (!empty($content_language)) {
                    /**
                     * Get list category via multilanguage plugin.
                     *
                     * @param array  List category
                     * @param string Language to translate
                     *
                     * @internal
                     *
                     * @return array
                     */
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $content_language);
                }
            } elseif (strpos($type, 'page') !== false) {
                if (class_exists('WPLPAddonAdmin')) {
                    $cats = get_pages();
                    if (!empty($content_language)) {
                        /**
                         * Get list pages via multilanguage plugin.
                         *
                         * @param array  List pages
                         * @param string Language to translate
                         *
                         * @internal
                         *
                         * @return array
                         */
                        $cats = apply_filters('wplp_get_pages_by_language', $cats, $content_language);
                    }
                }
            } elseif (strpos($type, 'tag') !== false) {
                if (!empty($content_language) && function_exists('pll_get_term')) {
                    $cats = get_tags(array('lang' => $content_language));
                } else {
                    $cats = get_tags();
                }
                if (!empty($content_language) && !empty($cats)) {
                    /**
                     * Get list tags via multilanguage plugin.
                     *
                     * @param array  List tags
                     * @param string Language to translate
                     *
                     * @internal
                     *
                     * @return array
                     */
                    $cats = apply_filters('wplp_get_tags_by_language', $cats, $content_language);
                }
            } elseif (strpos($type, 'cat_list') !== false) {
                $cats = get_categories(array('hide_empty' => false));
                if (!empty($content_language)) {
                    /**
                     * Get list category via multilanguage plugin.
                     *
                     * @param array  List category
                     * @param string Language to translate
                     *
                     * @internal
                     *
                     * @return array
                     */
                    $cats = apply_filters('wplp_get_category_by_language', $cats, $content_language);
                }
                foreach ($cats as $cat) {
                    $cat->blog = (int) $val_blog;
                }
            }
            restore_current_blog();
        }

        if (strpos($type, 'post') !== false) {
            $output .= '<ul  class="post_field craft">';
            $output .= '<li><input id="cat_all" type="checkbox" name="wplp_source_category[]" value="_all" class="ju-checkbox wplp_change_content" />';
            $output .= '<label for="cat_all" class="post_cb">All</li>';
            foreach ($cats as $k => $cat) {
                $output .= '<li><input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]" class="ju-checkbox wplp_change_content"';
                $output .= 'value="' . $k . '_' . $cat->term_id . '_blog'. $cat->blog .'" class="post_cb" />';
                $output .= '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label></li>';
            }
            $output .= '</ul>';
            $output .= '<div class="clearfix"></div>';
        } elseif (strpos($type, 'page') !== false) {
            $output .= '<ul class="page_field craft">';
            $output .= '<li><input id="page_all" type="checkbox" name="wplp_source_pages[]" value="_all" class="ju-checkbox wplp_change_content" />' .
                       '<label for="page_all" class="page_cb">All Pages</li>';

            foreach ($cats as $k => $page) {
                $output .= '<li><input id="pcb_' . $k . '" type="checkbox" name="wplp_source_pages[]" class="ju-checkbox wplp_change_content"';
                $output .= 'value="' . $k . '_' . $page->ID . '" class="page_cb" />';
                $output .= '<label for="pcb_' . $k . '" class="page_cb">' . $page->post_title . '</label></li>';
            }
            $output .= '</ul>';    //fields
            $output .= '<div class="clearfix"></div>';
        } elseif (strpos($type, 'tag') !== false) {
            $output .= '<ul class="tag_field craft">';
            $output .= '<li><input id="tags_all" type="checkbox" name="wplp_source_tags[]" value="_all"  class="ju-checkbox wplp_change_content" />' .
                       '<label for="tags_all" class="tag_cb">All tags</li>';

            foreach ($cats as $k => $tag) {
                $output .= '<li><input id="tcb_' . $k . '" type="checkbox" name="wplp_source_tags[]" class="ju-checkbox wplp_change_content"';
                $output .= 'value="' . $k . '_' . $tag->term_id . '"  class="tag_cb" />';
                $output .= '<label for="tcb_' . $k . '" class="tag_cb">' . $tag->name . '</label></li>';
            }
            $output .= '</ul>';
            $output .= '<div class="clearfix"></div>';
        } elseif (strpos($type, 'cat_list') !== false) {
            $output .= '<ul class="fields craft">';
            $output .= '<li><input id="cat_list_all" type="checkbox" name="wplp_source_category_list[]" value="_all" class="ju-checkbox wplp_change_content" />';
            $output .= '<label for="cat_list_all" class="cat_list_cb">All</li>';
            foreach ($cats as $k => $cat) {
                $output .= '<li><input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]" class="ju-checkbox wplp_change_content"';
                $output .= 'value="' . $k . '_' . $cat->term_id . '_blog' . $cat->blog . '" class="cat_list_cb" />';
                $output .= '<label for="cl_' . $k . '" class="cat_list_cb">' . $cat->name . '</label></li>';
            }
            $output .= '</ul>';
            $output .= '<div class="clearfix"></div>';
        }

        echo json_encode(array('output' => $output, 'type' => $type));
        exit;
    }

    /**
     * Dequeue ot admin style
     *
     * @return void
     */
    public function dequeueAdminHuemanStyles()
    {
        //fix conflict with hueman theme
        wp_dequeue_style('ot-admin-css');
    }

    /**
     * Check user
     * Use new theme default for new users
     *
     * @return void
     */
    public function checkUsed()
    {
        global $wpdb;
        $oldBlock = get_option('_wplp_onceLoad');
        if (empty($oldBlock)) {
            $meta_key = '_wplp_settings';
            $postsId  = $wpdb->get_results($wpdb->prepare(' SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key = %s ', $meta_key));
            if (!empty($postsId)) {
                foreach ($postsId as $postId) {
                    $postId   = $postId->post_id;
                    $postMeta = get_post_meta($postId, '_wplp_settings', true);
                    if (strpos($postMeta['theme'], 'default')) {
                        $postMeta['theme'] = addslashes($postMeta['theme']);
                        update_post_meta($postId, '_wplp_settings', $postMeta);
                    }
                }
            }
            $onceLoad = 1;
            add_option('_wplp_onceLoad', $onceLoad, '', 'no');
        }
    }


    /**
     * Append our theme stylesheet if necessary
     *
     * @return void
     */
    public function addStylesheet()
    {
        /*
         TODO: is there a way to load our theme stylesheet only where necessary?
        */

        $myStyleUrl  = plugins_url(MAIN_FRONT_STYLESHEET, dirname(__FILE__));
        $myStylePath = plugin_dir_path(dirname(__FILE__)) . MAIN_FRONT_STYLESHEET;

        if (file_exists($myStylePath)) {
            wp_register_style('myStyleSheets', $myStyleUrl);
            wp_enqueue_style('myStyleSheets');
        }
    }

    /**
     * Append our fonts if necessary
     *
     * @return void
     */
    public function addFonts()
    {
        /*
          TODO: is there a way to load our fonts only where necessary?
        */

        $myFontsUrl = 'https://fonts.googleapis.com/css?' .
                      'family=Raleway:400,500,600,700,800,900|' .
                      'Alegreya:400,400italic,700,700italic,900,900italic|' .
                      'Varela+Round' .
                      '&subset=latin,latin-ext';

        wp_register_style('myFonts', $myFontsUrl);
        wp_enqueue_style('myFonts');
    }

    /**
     * Enqueue styles and scripts for gutenberg
     *
     * @return void
     */
    public function addEditorAssets()
    {
        wp_enqueue_script(
            'wplp_addon_imagesloaded',
            plugins_url('js/imagesloaded.pkgd.min.js', dirname(__FILE__)),
            array('jquery'),
            '0.1',
            true
        );

        if (defined('WPLP_ADDON_VERSION')) {
            wp_enqueue_script('jquery-masonry');
            wp_enqueue_script(
                'wplp_isotope',
                WPLPADDON_PLUGIN_DIR . 'themes/portfolio/isotope.js',
                array('jquery'),
                '1.0'
            );
        }

        $this->addStylesheet();
        wp_enqueue_style('wplpStyleDefault', plugins_url('themes/default/style.css', dirname(__FILE__)));
        if (defined('WPLP_ADDON_VERSION')) {
            wp_enqueue_style('wplpStyleMasonry', WPLPADDON_PLUGIN_DIR . 'themes/masonry/style.css');
            wp_enqueue_style('wplpStyleMaterialVertical', WPLPADDON_PLUGIN_DIR . 'themes/material-vertical/style.css');
            wp_enqueue_style('wplpStyleMasonryCategory', WPLPADDON_PLUGIN_DIR . 'themes/masonry-category/style.css');
            wp_enqueue_style('wplpStyleSmooth', WPLPADDON_PLUGIN_DIR . 'themes/smooth-effect/style.css');
            wp_enqueue_style('wplpStyleTimeline', WPLPADDON_PLUGIN_DIR . 'themes/timeline/style.css');
            wp_enqueue_style('wplpStylePortfolio', WPLPADDON_PLUGIN_DIR . 'themes/portfolio/style.css');
        }

        wp_enqueue_script(
            'wplp_blocks',
            plugins_url('js/blocks/insert-news-block/block.js', dirname(__FILE__)),
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-data', 'wp-editor'),
            '1.0'
        );

        wp_enqueue_style(
            'wplp_blocks',
            plugins_url('js/blocks/insert-news-block/style.css', dirname(__FILE__)),
            array(),
            '1.0'
        );

        $wplp_posts = get_posts(array(
            'post_type'      => 'wplp-news-widget',
            'posts_per_page' => - 1,
            'post_status'    => 'any'
        ));

        $posts_select = array();
        foreach ($wplp_posts as $wplp_post) {
            $posts_select[] = array('label' => $wplp_post->post_title, 'value' => $wplp_post->ID);
        }

        $params = array(
            'l18n' => array(
                'block_title'   => __('WP Latest Posts', 'wp-latest-posts'),
                'no_post_found' => __('No post found', 'wp-latest-posts'),
                'select_label'  => __('Select a News Block', 'wp-latest-posts')
            ),
            'vars' => array(
                'posts_select' => $posts_select,
                'ajaxurl' => admin_url('admin-ajax.php'),
                'edit_url' => admin_url('admin.php?page=wplp-widget&view=block&id='),
                'block_cover' => WPLP_PLUGIN_DIR .'js/blocks/insert-news-block/wp-latest-posts.png'
            )
        );

        wp_localize_script('wplp_blocks', 'wplp_blocks', $params);
    }

    /**
     * Loads js/ajax scripts
     *
     * @param string $hook Page name need add hook
     *
     * @return mixed
     */
    public function loadAdminScripts($hook)
    {
        $current_page = get_current_screen();

        if ($current_page->base === 'latest-posts_page_wplp-configuration' ||
            $current_page->base === 'toplevel_page_wplp-widget') {
            wp_enqueue_script('jquery');

            wp_enqueue_script(
                'wplp_framework_velocity_js',
                plugins_url('wordpress-css-framework/js/velocity.min.js', dirname(__FILE__)),
                array('jquery'),
                '1.0',
                true
            );

            wp_enqueue_script(
                'wplp_framework_tabs_js',
                plugins_url('wordpress-css-framework/js/tabs.js', dirname(__FILE__)),
                array('jquery'),
                '1.0',
                true
            );

            wp_enqueue_script(
                'wplp_framework_js',
                plugins_url('wordpress-css-framework/js/script.js', dirname(__FILE__)),
                array('jquery'),
                '1.0',
                true
            );

            wp_enqueue_script(
                'wplp_framework_waves_js',
                plugins_url('wordpress-css-framework/js/waves.js', dirname(__FILE__)),
                array('jquery'),
                '1.0',
                true
            );

            // CSS
            wp_enqueue_style(
                'wplp-material-icon',
                'https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined'
            );
            wp_register_style('wplp_framework_css', plugins_url('wordpress-css-framework/css/style.css', dirname(__FILE__)));
            wp_enqueue_style('wplp_framework_css');

            wp_register_style('wplp_framework_waves', plugins_url('wordpress-css-framework/css/waves.css', dirname(__FILE__)));
            wp_enqueue_style('wplp_framework_waves');
        }

        if ($current_page->base === 'latest-posts_page_wplp-configuration') {
            wp_enqueue_script(
                'javascript',
                plugins_url('/js/wplp_about.js', dirname(__FILE__)),
                array('jquery'),
                '1.0.0',
                true
            );

            wp_register_style('wplpAbout', plugins_url('css/wplp_about.css', dirname(__FILE__)));
            wp_enqueue_style('wplpAbout');
        }

        if ($current_page->base === 'toplevel_page_wplp-widget') {
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-slider');
            // fix conflict with Capella theme
            wp_dequeue_script('jslider');
            wp_enqueue_script(
                'wplp-dropify',
                plugins_url('js/dropify/js/dropify.min.js', dirname(__FILE__)),
                array('jquery'),
                '0.1',
                true
            );

            if (isset($_GET['view']) && $_GET['view'] === 'block') { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- view only
                wp_enqueue_script(
                    'wplp-picker',
                    plugins_url('js/materialize/picker.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );
                wp_enqueue_script(
                    'wplp-picker-date',
                    plugins_url('js/materialize/picker.date.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );
                /**
                 * Add codemirror js
                 */
                wp_enqueue_script(
                    'wplp-codemirror',
                    plugins_url('codemirror/lib/codemirror.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );
                /**
                 * Mode css
                 */
                wp_enqueue_script(
                    'wplp-codemirrorMode',
                    plugins_url('codemirror/mode/css/css.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );

                wp_enqueue_script(
                    'wplp-codemirrorAdmin',
                    plugins_url('js/wplp_codemirrorAdmin.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );

                wp_enqueue_script('wp-color-picker');

                wp_enqueue_script(
                    'wplp-newColorPicker',
                    plugins_url('js/wplp_newColorPicker.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );

                wp_enqueue_script(
                    'wplp_admin_block_js',
                    plugins_url('js/wplp_admin_block.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );
                $ajax_non = wp_create_nonce('wplp-back-nonce');
                wp_localize_script('wplp_admin_block_js', 'wplp_objects', array(
                    'ajaxnonce' => $ajax_non,
                    'per_page_label' => esc_html__('News per page', 'wp-latest-posts'),
                    'max_number_label' => esc_html__('Max number of news', 'wp-latest-posts')
                ));

                wp_enqueue_script(
                    'wplp-content-language',
                    plugins_url('js/wplp_content_language.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );

                //set tokken ajax
                $token_name = array(
                    'wplp_nonce' => wp_create_nonce('wplp_nonce')
                );
                $parameter  = array(
                    'plugin_dir' => WPLP_PLUGIN_DIR,
                    'loading' => esc_html__('Loading...', 'wp-latest-posts')
                );
                wp_localize_script('wplp-content-language', '_token_name', $token_name);
                wp_localize_script('wplp-content-language', 'content_language_param', $parameter);

                wp_enqueue_script(
                    'wplp-color-change',
                    plugins_url('js/colorLibrary.js', dirname(__FILE__)),
                    array('jquery'),
                    '0.1',
                    true
                );
            }

            // qtip
            wp_enqueue_script(
                'wplp_widget_snackbar_js',
                plugins_url('js/snackbar.js', dirname(__FILE__)),
                array('jquery'),
                '2.2.1',
                true
            );

            wp_enqueue_script(
                'wplp_widget_admin_js',
                plugins_url('js/wplp_admin_widget.js', dirname(__FILE__)),
                array('jquery'),
                '1.0',
                true
            );

            wp_localize_script('wplp_widget_admin_js', 'wplp_trans', array(
                'l18n' => array(
                    'copy_error' => esc_html__('Copy failed!', 'wp-latest-posts'),
                    'copy_success' => esc_html__('Shortcode copied!', 'wp-latest-posts'),
                    'copying_block' => esc_html__('Block copying...', 'wp-latest-posts'),
                    'copy_block_success' => esc_html__('Block copied!', 'wp-latest-posts')
                )
            ));

            $this->addAdminStylesheets();
        }
    }

    /**
     * Load additional admin stylesheets of jquery-ui
     *
     * @return void
     */
    public function addAdminStylesheets()
    {
        /**
         * Add color picker css
         */
        wp_enqueue_style('wp-color-picker');

        wp_register_style('uiStyleSheet', plugins_url('css/jquery-ui-custom.css', dirname(__FILE__)));
        wp_enqueue_style('uiStyleSheet');

        wp_register_style('wplp_dropify', plugins_url('js/dropify/css/dropify.min.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_dropify');

        wp_register_style('wplpPicker', plugins_url('css/picker.css', dirname(__FILE__)));
        wp_enqueue_style('wplpPicker');

        wp_register_style('wplpAdmin', plugins_url('css/wplp_admin_widget.css', dirname(__FILE__)));
        wp_enqueue_style('wplpAdmin');

        wp_register_style('wplpAdminSettings', plugins_url('css/wplp_admin_block.css', dirname(__FILE__)));
        wp_enqueue_style('wplpAdminSettings');

        wp_register_style('wplpAdminQuirk', plugins_url('css/quirk.css', dirname(__FILE__)));
        wp_enqueue_style('wplpAdminQuirk');

        wp_register_style('unifStyleSheet', plugins_url('css/uniform/css/uniform.default.css', dirname(__FILE__)));
        wp_enqueue_style('unifStyleSheet');

        /**
         * Add codemirror css
         */
        wp_register_style('wplp_codemirror', plugins_url('codemirror/lib/codemirror.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_codemirror');

        wp_register_style('wplp_codemirrorTheme', plugins_url('codemirror/theme/3024-day.css', dirname(__FILE__)));
        wp_enqueue_style('wplp_codemirrorTheme');
    }

    /**
     * Dequeue some js
     *
     * @return void
     */
    public function dequeueAdminScripts()
    {
        wp_dequeue_script('sdf_bs_js_admin');
        //fix conflict with bootstrap theme
        wp_dequeue_script('bootstrap');
        wp_dequeue_script('cp_scripts_admin');
        //fix conflict with 'All In One Schema.org Rich Snippets' plugin
        wp_dequeue_script('bsf-scripts-media');
        wp_dequeue_style('admin_style');
        //fix conflict with Easy table plugin
        wp_dequeue_script('vc_bootstrap_dropdown');
    }

    /**
     * Builds the drop-down list of available themes
     * for this plugin
     *
     * @return array|boolean
     */
    public static function themeLister()
    {
        $found_themes = array();
        $theme_root   = dirname(dirname(__FILE__)) . '/themes';
        //echo 'theme dir: ' . $theme_root . '<br/>';    //Debug
        $dirs = scandir($theme_root);
        foreach ($dirs as $k => $v) {
            if (!is_dir($theme_root . '/' . $v) || $v[0] === '.' || $v === 'CVS') {
                unset($dirs[$k]);
            } else {
                $dirs[$k] = array(
                    'path' => $theme_root . '/' . $v,
                    'url'  => plugins_url('themes/' . $v, dirname(__FILE__))
                );
            }
        }

        /**
         * Load Pro add-on themes.
         *
         * @param array Theme dir
         *
         * @internal
         *
         * @return array
         */
        $dirs = apply_filters('wplp_themedirs', $dirs);

        if (!$dirs) {
            return false;
        }

        foreach ($dirs as $dir) {
            if (file_exists($dir['path'] . '/style.css')) {
                $headers = get_file_data($dir['path'] . '/style.css', self::$file_headers, 'theme');
                $name    = $headers['Name'];
                if ('Default theme' === $name) {
                    $name = ' ' . $name; // <- this makes it sort always first
                }
                $found_themes[basename($dir['path'])] = array(
                    'name'       => $name,
                    'dir'        => basename($dir['path']),
                    'theme_file' => $dir['path'] . '/style.css',
                    'theme_root' => $dir['path'],
                    'theme_url'  => $dir['url']
                );
            }
        }
        asort($found_themes);

        return $found_themes;
    }

    /**
     * Customize Tiny MCE Editor
     *
     * @return void
     */
    public function setupTinyMce()
    {
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            add_filter('mce_buttons', array($this, 'filterMceButton'));
            add_filter('mce_external_plugins', array($this, 'filterMcePlugin'));
            add_filter('mce_css', array($this, 'pluginMceCss'));
        }
    }

    /**
     * Buttons/features from the WordPress's TinyMCE toolbar(s)
     *
     * @param array $buttons Button of tinyMCE
     *
     * @return mixed
     */
    public function filterMceButton($buttons)
    {
        array_push($buttons, '|', 'wplp_button');

        return $buttons;
    }

    /**
     * Filter list script Mce plugin
     *
     * @param array $plugins List plugin
     *
     * @return mixed
     */
    public function filterMcePlugin($plugins)
    {
        if (get_bloginfo('version') < 3.9) {
            $plugins['wplp'] = plugins_url('js/wplp_tmce_plugin.js', dirname(__FILE__));
        } else {
            $plugins['wplp'] = plugins_url('js/wplp_tmce_plugin-3.9.js', dirname(__FILE__));
        }

        return $plugins;
    }

    /**
     * Add mce style.
     *
     * @param string $mce_css Mce name
     *
     * @return string
     */
    public function pluginMceCss($mce_css)
    {
        if (!empty($mce_css)) {
            $mce_css .= ',';
        }

        $mce_css .= plugins_url('css/wplp_tmce_plugin.css', dirname(__FILE__));

        return $mce_css;
    }

    /**
     * Add insert button above tinyMCE 4.0 (WP 3.9+)
     *
     * @return string
     */
    public function editorButton()
    {
        $args = '';

        $args = wp_parse_args(
            $args,
            array(
                'text'  => __('Add Latest Posts', 'wp-latest-posts'),
                'class' => 'button',
                'echo'  => true
            )
        );

        /**
         * Print button
         */

        $button = '<a href="#TB_inline?height=150&width=150&inlineId=wplp-popup-wrap&modal=true" ' .
                  'class="wplp-button thickbox ' . esc_html($args['class']) . '" ' .
                  'title="' . esc_html($args['text']) . '">' .
                  '<span style = "vertical-align: text-top" class="dashicons dashicons-admin-page"></span>' . esc_html($args['text']) .
                  '</a>';

        /**
         * Prepare insertion popup
         */
        add_action('admin_footer', array($this, 'insertPopup'));

        if ($args['echo']) {
            //phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
            echo $button;
        }

        return $button;
    }

    /**
     * Prepare block insertion popup for admin editor with tinyMCE 4.0 (WP 3.9+)
     *
     * @return void
     */
    public function insertPopup()
    {
        ?>

        <div id="wplp-popup-wrap" class="media-modal wp-core-ui" style="display:none">
            <a class="media-modal-close" href="#" onClick="javascript:tb_remove();" title="Close"><span
                        class="media-modal-icon"></span></a>
            <div id="wplp-select-content" class="media-modal-content">

                <div class="wplp-frame-title" style="margin-left: 30px;">
                    <h1><?php echo esc_html__('WP Latest Posts', 'wp-latest-posts'); ?></h1></div>

                <div id="wplp_widgetlist" style="margin:50px auto;">
                    <?php
                    $widgets = get_posts(
                        array(
                            'post_type'      => CUSTOM_POST_NEWS_WIDGET_NAME,
                            'post_status'    => array(
                                'publish',
                                'future',
                                'private'
                            ),
                            'posts_per_page' => - 1
                        )
                    );
                    ?>
                    <?php if ($widgets) : ?>
                        <select id="wplp_widget_select">
                            <option><?php echo esc_html__('Select which block to insert:', 'wp-latest-posts'); ?></option>
                            <?php foreach ($widgets as $widget) : ?>
                                <option value="<?php echo esc_html($widget->ID); ?>"><?php echo esc_html($widget->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <p><?php echo esc_html__('No Latest Posts Widget has been created.', 'wp-latest-posts'); ?></p>
                        <p><?php echo esc_html__('Please create one to use this button.', 'wp-latest-posts'); ?></p>
                    <?php endif; ?>
                </div>

                <script>
                    (function ($) {
                        $('#wplp_widgetlist').on('change', function (e) {
                            insertShortcode($('option:selected', this).val(), $('option:selected', this).text());
                            $('#wplp_widgetlist').find('option:first').attr('selected', 'selected');
                            tb_remove();
                        });

                        function insertShortcode(widget_id, widget_title) {
                            var shortcode = '[frontpage_news';
                            if (null != widget_id)
                                shortcode += ' widget="' + widget_id + '"';
                            if (null != widget_title)
                                shortcode += ' name="' + widget_title + '"';
                            shortcode += ']';

                            /** Inserts the shortcode into the active editor and reloads display **/
                            //var ed = tinyMCE.activeEditor;
                            //
                            //                            ed.execCommand('mceInsertContent', 0, shortcode);
                            //                            setTimeout(function() { ed.hide(); }, 1);
                            //                            setTimeout(function() { ed.show(); }, 10);
                            //
                            wplp_send_to_editor(shortcode);
                        }

                        var wpActiveEditor, wplp_send_to_editor;

                        wplp_send_to_editor = function (html) {
                            var editor,
                                hasTinymce = typeof tinymce !== 'undefined',
                                hasQuicktags = typeof QTags !== 'undefined';

                            if (!wpActiveEditor) {
                                if (hasTinymce && tinymce.activeEditor) {
                                    editor = tinymce.activeEditor;
                                    wpActiveEditor = editor.id;
                                } else if (!hasQuicktags) {
                                    return false;
                                }
                            } else if (hasTinymce) {
                                editor = tinymce.get(wpActiveEditor);
                            }

                            if (editor && !editor.isHidden()) {
                                editor.execCommand('mceInsertContent', 0, html);
                                setTimeout(function () {
                                    editor.hide();
                                }, 1);
                                setTimeout(function () {
                                    editor.show();
                                }, 10);

                            } else if (hasQuicktags) {
                                QTags.insertContent(html);
                            } else {
                                document.getElementById(wpActiveEditor).value += html;
                            }

                            // If the old thickbox remove function exists, call it
                            if (window.tb_remove) {
                                try {
                                    window.tb_remove();
                                } catch (e) {
                                }
                            }
                        };
                    })(jQuery);
                </script>

                <style>
                    /** tinyMce button + widget selector **/
                    #wplp_widgetlist {
                        min-width: 150px;
                        max-width: 250px;
                        overflow: hidden;
                        border: 3px solid #eee;
                        background: #fff;
                        z-index: 100;
                    }

                    #wplp_widgetlist select {
                        min-height: 70px;
                        min-width: 250px;
                        padding: 5px;
                        margin-bottom: -5px;
                    }
                </style>
            </div>
        </div>
        <?php
    }

    /**
     * Adds a js script to the post and page editor screen footer
     * to configure our tinyMCE extension
     * with the list of available widgets
     *
     * @return void
     */
    public function editorFooterScript()
    {
        //TODO: return false if not page/post edit screen

        echo '<script>';
        echo "var wplp_widgets = new Array();\n";
        $widgets = get_posts(
            array(
                'post_type'      => CUSTOM_POST_NEWS_WIDGET_NAME,
                'post_status'    => array(
                    'publish',
                    'future',
                    'private'
                ),
                'posts_per_page' => - 1
            )
        );
        foreach ($widgets as $widget) {
            echo "wplp_widgets['" . esc_html($widget->ID) . "']='" . esc_html($widget->post_title) . "';\n";
        }
        echo '</script>';
    }

    /**
     * Add Style and script in head and footer
     *
     * @param array $posts List of posts
     *
     * @return mixed
     */
    public function prefixEnqueue($posts)
    {
        if (empty($posts) || is_admin()) {
            return $posts;
        }
        $pattern = get_shortcode_regex();
        foreach ($posts as $post) {
            preg_match_all('/' . $pattern . '/s', $post->post_content, $matches);
            $widgetIDArray = array();
            foreach ($matches as $matchtest) {
                if (is_array($matchtest)) {
                    foreach ($matchtest as $matchtestsub) {
                        preg_match_all('/widget="(.*?)"/s', $matchtestsub, $widgetIDarray);
                        //print_r($widgetIDarray); die();
                        foreach ($widgetIDarray as $widgetID) {
                            if (!empty($widgetID)) {
                                if (is_array($widgetID)) {
                                    foreach ($widgetID as $widgetIDunique) {
                                        if (is_numeric($widgetIDunique)
                                            && !in_array($widgetIDunique, $widgetIDArray, true)
                                        ) {
                                            $widgetIDArray[] = $widgetIDunique;
                                        }
                                    }
                                } else {
                                    if (is_numeric($widgetIDunique)
                                        && !in_array($widgetIDunique, $widgetIDArray, true)
                                    ) {
                                        $widgetIDArray[] = $widgetIDunique;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    preg_match_all('/widget="(.*?)"/s', $matchtestsub, $widgetIDarray);
                    foreach ($widgetIDarray as $widgetID) {
                        if (!empty($widgetID)) {
                            if (is_array($widgetID)) {
                                foreach ($widgetID as $widgetIDunique) {
                                    if (is_numeric($widgetIDunique)
                                        && !in_array($widgetIDunique, $widgetIDArray, true)
                                    ) {
                                        $widgetIDArray[] = $widgetIDunique;
                                    }
                                }
                            } else {
                                if (is_numeric($widgetIDunique) && !in_array($widgetIDunique, $widgetIDArray, true)) {
                                    $widgetIDArray[] = $widgetIDunique;
                                }
                            }
                        }
                    }
                }
            }

            /*
              foreach ($matches[2] as $matche => $matchkey) {
              if ($matchkey == 'frontpage_news') {
              $widgetIDArray[]=$matche;
              }
              }
             */
            foreach ($widgetIDArray as $widgetIDitem) {
                $widget = get_post($widgetIDitem);
                if (isset($widget) && !empty($widget)) {
                    $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                    if (isset($widget->settings) && is_array($widget->settings)) {
                        $front = new WPLPFront($widget);
                        add_action('wp_print_styles', array($front, 'loadThemeStyle'));
                        add_action('wp_head', array($front, 'customCSS'));
                        add_action('wp_print_scripts', array($front, 'loadThemeScript'));
                    }
                }
            }
        }

        return $posts;
    }

    /**
     * Get Count Posts
     *
     * @return void
     */
    public function getCountPosts()
    {
        if (empty($_POST['wplp_nonce'])
            || !wp_verify_nonce($_POST['wplp_nonce'], 'wplp_nonce')) {
            die();
        }

        $text = esc_html__(' Posts', 'wp-latest-posts');
        if (isset($_POST['wplp_id'])) {
            $widget    = get_post($_POST['wplp_id']);
        } else {
            $widget = new stdClass();
        }

        parse_str($_POST['settings'], $settings);
        $configs = array();
        switch ($settings['wplp_source_type']) {
            case 'src_category_list':
                $text = esc_html__(' Categories', 'wp-latest-posts');
                break;
            case 'src_page':
                $text = esc_html__(' Pages', 'wp-latest-posts');
                break;
        }
        foreach ($settings as $key => $setting) {
            $new_key = str_replace('wplp_', '', $key);
            $configs[$new_key] = $setting;
        }

        $widget->settings = $configs;
        $front = new WPLPFront($widget);
        wp_send_json(array('status' => true, 'count' => count($front->posts), 'text' => $text));
    }

    /**
     * Returns content of our shortcode
     *
     * @param array $args List argument
     *
     * @return string
     */
    public function applyShortcode($args = array())
    {

        $html = '';

        $widget_id = $args['widget'];
        $widget    = get_post($widget_id);
        if (isset($widget) && !empty($widget)) {
            $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
            $widget->settings = array_merge($this->field_defaults, $widget->settings);
            $front            = new WPLPFront($widget);
            $front->loadThemeStyle();
            $front->loadThemeScript();
            $html .= $front->display(false);
        } else {
            $html .= "\n<!-- WPFN: this News Widget is not initialized -->\n";
        }

        return $html;
    }

    /**
     * Check system information
     *
     * @return boolean
     */
    public static function systemCheck()
    {
        $check = false;

        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            // Check php version
            $check = true;
        }

        return $check;
    }

    /**
     * Add pro link to joomunited product
     *
     * @param array  $links List link to product
     * @param string $file  Plugin name
     *
     * @return array
     */
    public function addProLink($links, $file)
    {
        $base = plugin_basename($this->plugin_file);
        if ($file === $base) {
            $links[] = '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">'
                       . __('Get "pro" add-on', 'wp-latest-posts') . '</a>';
            $links[] = '<a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">'
                       . __('Support', 'wp-latest-posts') . '</a>';
        }

        return $links;
    }


    /**
     * Set cookie notification
     *
     * @return void
     */
    public function setCookieNotification()
    {
        check_ajax_referer('wplp-back-nonce', 'ajaxnonce');

        if (isset($_POST['task'])) {
            setcookie($_POST['task'], time(), time() + (86400 * 30), '/');
            wp_send_json(true);
        }
        wp_send_json(false);
    }

    /**
     * Set post views
     *
     * @return void
     */
    public function setPostViews()
    {
        $args     = array(
            'public'   => true,
            '_builtin' => false
        );
        $output   = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $custom_post_types = get_post_types($args, $output, $operator);
        if (!is_single()) {
            return;
        }
        global $post;
        $postID = $post->ID;

        $count = get_post_meta($postID, WPLP_POST_VIEWS_COUNT_META_KEY, true);
        if ($count === '') {
            $count = 0;
            delete_post_meta($postID, WPLP_POST_VIEWS_COUNT_META_KEY);
            add_post_meta($postID, WPLP_POST_VIEWS_COUNT_META_KEY, $count);
        } else {
            $count++;
            update_post_meta($postID, WPLP_POST_VIEWS_COUNT_META_KEY, $count);
        }
    }

    /**
     * Add post meta key to count views when save_post
     *
     * @param integer $post_ID Post ID
     * @param object  $post    Post Data
     * @param boolean $update  Update
     *
     * @return void
     */
    public function addPostMetaKey($post_ID, $post, $update)
    {
        $args     = array(
            'public'   => true,
            '_builtin' => false
        );
        $output   = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $custom_post_types = get_post_types($args, $output, $operator);
        /*var_dump('post' !== $post->post_type,in_array($post->post_type, $custom_post_types) === false);exit;*/
        if ('post' !== $post->post_type && in_array($post->post_type, $custom_post_types) === false) {
            return;
        }
        $count = get_post_meta($post_ID, WPLP_POST_VIEWS_COUNT_META_KEY, true);
        if ($count === '') {
            $count = 0;
            delete_post_meta($post_ID, WPLP_POST_VIEWS_COUNT_META_KEY);
            add_post_meta($post_ID, WPLP_POST_VIEWS_COUNT_META_KEY, $count);
        }
    }

    /**
     * Add transient from each post
     *
     * @return void
     */
    public function savePostTransient()
    {
        global $post;
        if (!isset($post)) {
            return;
        }
        $args     = array(
            'public'   => true,
            '_builtin' => false
        );
        $output   = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $custom_post_types = get_post_types($args, $output, $operator);
        if (is_single() === false) {
            return;
        }
        if ('post' !== $post->post_type && !in_array($post->post_type, $custom_post_types)) {
            return;
        }
        $key = WPLP_POST_VIEW_TRANSIENT_KEY;
        $transientData = WPLPCache::get($key);

        if (false === $transientData) {
            $transientData = array(
                    $post->ID => 1
            );
        } else {
            if (!isset($transientData[$post->ID])) {
                $transientData[$post->ID] = 1;
            } else {
                $transientData[$post->ID] = $transientData[$post->ID]+1;
            }
        }
        $time = intval($this->wplp_settings['cache_interval_value']) ? (int) $this->wplp_settings['cache_interval_value'] : 2;
        $timeValue = $time + 2;
        $timeUnit = 'minute';
        WPLPCache::set($key, $transientData, $timeValue, $timeUnit);
    }

    /**
     * Add cron
     *
     * @param string $schedules Schedules name
     *
     * @return mixed
     */
    public function addCronSchedules($schedules)
    {
        $time = intval($this->wplp_settings['cache_interval_value']) ? (int) $this->wplp_settings['cache_interval_value'] : 2;
        if (!isset($schedules[$time . 'min'])) {
            $schedules[$time . 'min'] = array(
                'interval' => $time*60,
                'display' => sprintf(__('Once every %d minutes', 'wp-latest-posts'), $time)
            );
        }
        return $schedules;
    }

    /**
     * Update post views using schedule
     *
     * @return void
     */
    public function updatePostViews()
    {
        $key = WPLP_POST_VIEW_TRANSIENT_KEY;
        $data = WPLPCache::get($key);
        if ($data !== false) {
            foreach ($data as $dataKey => $dataVal) {
                $count = get_post_meta($dataKey, WPLP_POST_VIEWS_COUNT_META_KEY, true);
                if ($count === '') {
                    $count = $dataVal;
                } else {
                    $count += $dataVal;
                }
                update_post_meta($dataKey, WPLP_POST_VIEWS_COUNT_META_KEY, $count);
            }
            delete_transient($key);
        }
    }
}

?>
