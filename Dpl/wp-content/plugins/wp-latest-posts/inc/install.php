<?php


/**
 * Class WPLPInstall
 * WP Latest Posts install class
 */
class WPLPInstall
{
    /**
     * Constructor
     */
    public function __construct()
    {
        /**
         * Check PHP and WP versions upon install
         */
        register_activation_hook(dirname(dirname(__FILE__)), array($this, 'activate'));

        //Update option when update plugin
        add_action('admin_init', array($this, 'wplpUpdateVersion'));
        add_action('admin_init', array($this, 'addPostMetaTrackViews'));
    }



    /**
     * Plugin Activation hook function to check for Minimum PHP and WordPress versions
     *
     * @param string $wp  Minimum version of wordpress required for this plugin
     * @param string $php Minimum version of php required for this plugin
     *
     * @return void
     *
     * @see http://wordpress.stackexchange.com/questions/76007/best-way-to-abort-plugin-in-case-of-insufficient-php-version
     */
    public function activate($wp = '3.2', $php = '5.3.1')
    {
        global $wp_version;
        if (version_compare(PHP_VERSION, $php, '<')) {
            $flag = 'PHP';
        } elseif (version_compare($wp_version, $wp, '<')) {
            $flag = 'WordPress';
        } else {
            $this->checkUsed();

            return;
        }
        $version = 'PHP' === $flag ? $php : $wp;
        deactivate_plugins(basename(__FILE__));
        $text = '<p>The <strong>WP Latest Posts</strong> plugin requires ';
        $text .= $flag . '  version ' . $version . ' or greater.</p>';
        wp_die(
            esc_html($text),
            'Plugin Activation Error',
            array('response' => 200, 'back_link' => true)
        );
    }

    /**
     * Add post_meta to track post view
     *
     * @return void
     */
    public function addPostMetaTrackViews()
    {
        $hasViewsCountPostMetaKey = get_option('has_views_count_post_meta_key', false);
        if (!$hasViewsCountPostMetaKey) {
            $postQueryArgs = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1
            );
            $posts         = get_posts($postQueryArgs);
            $postIDs       = wp_list_pluck($posts, 'ID');
            $numPosts = count($postIDs);
            for ($i = 0; $i < $numPosts; $i ++) {
                $count = get_post_meta($postIDs[$i], WPLP_POST_VIEWS_COUNT_META_KEY, true);
                if ($count === '') {
                    $count = 0;
                    delete_post_meta($postIDs[$i], WPLP_POST_VIEWS_COUNT_META_KEY);
                    add_post_meta($postIDs[$i], WPLP_POST_VIEWS_COUNT_META_KEY, $count);
                }
            }
            add_option('has_views_count_post_meta_key', true);
        }
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
            $postsId  = $wpdb->get_results($wpdb->prepare(' SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key = %s ', $meta_key));
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


    //------------------------------------------ Update version 4.0 ------------------------------------------
    /**
     * Change prefix option from wpcufpn to wplp
     *
     * @return void
     */
    public function wplpUpdateVersion()
    {
        global $wpdb;
        $ver        = 'wplp_db_version';
        $option_ver = get_option($ver, false);
        if (!$option_ver) {
            $meta_key = '_wpcufpn_settings';
            $postsId  = $wpdb->get_results($wpdb->prepare('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key = %s ', $meta_key));

            if (!empty($postsId)) {
                foreach ($postsId as $post) {
                    $settings = get_post_meta($post->post_id, $meta_key, true);
                    update_post_meta($post->post_id, '_wplp_settings', $settings);
                    set_post_type($post->post_id, CUSTOM_POST_NEWS_WIDGET_NAME);
                    delete_post_meta($post->post_id, '_wpcufpn_settings', $settings);
                }
            }

            update_option($ver, '4.0.0');
            $option_ver = '4.0.0';
        }

        //change prefix when update version to 4.0.1
        if (version_compare($option_ver, '4.0.2', '<')) {
            $meta_key = '_wplp_settings';
            $postsId  = $wpdb->get_results($wpdb->prepare('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key = %s ', $meta_key));

            if (!empty($postsId)) {
                foreach ($postsId as $post) {
                    $settings = get_post_meta($post->post_id, $meta_key, true);
                    if (isset($settings['custom_css']) && $settings['custom_css'] !== '') {
                        $settings['custom_css'] = str_replace('wpcufpn', 'wplp', $settings['custom_css']);
                        update_post_meta($post->post_id, '_wplp_settings', $settings);
                    }
                }
            }
            update_option($ver, '4.0.2');
        }
    }
}

if (class_exists('WPLPInstall')) {
    $install = new WPLPInstall();
}
