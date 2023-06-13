<?php

require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Class WPLPFront
 * WP Latest Post front display class
 */
class WPLPFront
{

    const CSS_DEBUG = false;

    /**
     * Config crop in here
     * crop const
     */
    const DEFAULT_TITLE_EM_SIZE = 1.24;
    const TIMELINE_TITLE_EM_SIZE = 1.35;
    const SMOOTH_TITLE_EM_SIZE = 1.35;
    const MASONRY_GID_TITLE_EM_SIZE = 1.35;
    const MASONRY_CATEGORY_TITLE_EM_SIZE = 1.35;
    const PORTFOLIO_TITLE_EM_SIZE = 1.15;
    const DEFAULT_TEXT_EM_SIZE = 1.4;
    const TIMELINE_TEXT_EM_SIZE = 1.6875;
    const SMOOTH_TEXT_EM_SIZE = 1.4;
    const MASONRY_GID_TEXT_EM_SIZE = 1.21;
    const MASONRY_MATERIAL_TEXT_EM_SIZE = 1.6875;
    const MASONRY_CATEGORY_TEXT_EM_SIZE = 1.23;
    const PORTFOLIO_TEXT_EM_SIZE = 1.1;
    /**
     * Init widget params
     *
     * @var void
     */
    public $widget;
    /**
     * Init html string params
     *
     * @var string
     */
    private $html = '';
    /**
     * Init posts params
     *
     * @var array
     */
    public $posts = array();
    /**
     * Init count posts params
     *
     * @var integer
     */
    public $count_posts = 0;
    /**
     * Init prepared params
     *
     * @var boolean
     */
    private $prepared = false;
    /**
     * Init boxes params
     *
     * @var array
     */
    private $boxes = array();

    /**
     * Sets up widget options
     *
     * @param object $widget Widget parameter
     */
    public function __construct($widget)
    {
        $this->widget = $widget;
        if (strpos($this->widget->settings['theme'], 'portfolio') !== false) {
            $this->widget->settings['theme'] = 'portfolio';
        } elseif (strpos($this->widget->settings['theme'], 'masonry-category') !== false) {
            $this->widget->settings['theme'] = 'masonry-category';
        } elseif (strpos($this->widget->settings['theme'], 'masonry') !== false) {
            $this->widget->settings['theme'] = 'masonry';
        } elseif (strpos($this->widget->settings['theme'], 'material-vertical') !== false) {
            $this->widget->settings['theme'] = 'material-vertical';
        } elseif (strpos($this->widget->settings['theme'], 'smooth') !== false) {
            $this->widget->settings['theme'] = 'smooth-effect';
        } elseif (strpos($this->widget->settings['theme'], 'timeline') !== false) {
            $this->widget->settings['theme'] = 'timeline';
        } else {
            $this->widget->settings['theme'] = 'default';
        }
        /*
         * If Premium Theme ! reset box
         */
        if ($this->widget->settings['theme'] === 'portfolio'
            || $this->widget->settings['theme'] === 'masonry'
            || $this->widget->settings['theme'] === 'material-vertical'
            || $this->widget->settings['theme'] === 'masonry-category'
            || $this->widget->settings['theme'] === 'smooth-effect'
            || $this->widget->settings['theme'] === 'timeline'
        ) {
            $this->resetsettingsPremium();
        } else {
            /**
             * Check WPLP Block
             */
            $this->widget->settings['theme'] = 'default';
            if (empty($this->widget->settings['image_size'])) {
                $this->widget->settings['image_size'] = 'thumbnailSize';
            }
            if (empty($this->widget->settings['source_category'])) {
                $this->widget->settings['source_category'][0] = '_all';
            }

            $this->setupDefaultlayout();
        }

        $this->posts = $this->queryPosts();
        // Hook WP

        $this->prepared = true;

        //TODO: boxes setup will depend on theme template + pro filter
        $this->boxes = array('top', 'left', 'right', 'bottom');
    }

    /**
     * Set element of default theme
     *
     * @return void
     */
    private function setupDefaultlayout()
    {
        if ((isset($this->widget->settings['dfThumbnail']))
            && (isset($this->widget->settings['dfTitle']))
            && (isset($this->widget->settings['dfAuthor']))
            && (isset($this->widget->settings['dfDate']))
            && (isset($this->widget->settings['dfCategory']))
            && (isset($this->widget->settings['dfText']))
            && (isset($this->widget->settings['dfReadMore']))
        ) {
            $top_box    = array();
            $left_box   = array();
            $right_box  = array();
            $bottom_box = array();

            if ($this->widget->settings['dfThumbnailPosition'] === 'left') {
                $left_box  = array($this->widget->settings['dfThumbnail']);
                $right_box = array(
                    $this->widget->settings['dfTitle'],
                    $this->widget->settings['dfAuthor'],
                    $this->widget->settings['dfDate'],
                    $this->widget->settings['dfCategory'],
                    $this->widget->settings['dfText'],
                    'Custom_Fields',
                    $this->widget->settings['dfReadMore'],
                );

                /**
                 * Post fields to display on each part of the layout
                 * You can add / remove post fields on any layout part (top, right, bottom, left)
                 *
                 * @param array   Default items
                 * @param string  Box position
                 * @param integer Widget ID
                 * @param string  Theme name
                 *
                 * @return array
                 */
                $right_box = apply_filters('wplp_box_layout_position', $right_box, 'right', $this->widget->ID, 'default');
            } elseif ($this->widget->settings['dfThumbnailPosition'] === 'right') {
                $left_box = array(
                    $this->widget->settings['dfTitle'],
                    $this->widget->settings['dfAuthor'],
                    $this->widget->settings['dfDate'],
                    $this->widget->settings['dfCategory'],
                    $this->widget->settings['dfText'],
                    'Custom_Fields',
                    $this->widget->settings['dfReadMore'],
                );

                /**
                 * Post fields to display on each part of the layout
                 * You can add / remove post fields on any layout part (top, right, bottom, left)
                 *
                 * @param array   Default items
                 * @param string  Box position
                 * @param integer Widget ID
                 * @param string  Theme name
                 *
                 * @return array
                 *
                 * @ignore Hook already documented
                 */
                $left_box = apply_filters('wplp_box_layout_position', $left_box, 'left', $this->widget->ID, 'default');

                $right_box = array($this->widget->settings['dfThumbnail']);
            } else {
                $top_box    = array(
                    $this->widget->settings['dfThumbnail'],
                    $this->widget->settings['dfTitle']
                );
                $bottom_box = array(
                    $this->widget->settings['dfAuthor'],
                    $this->widget->settings['dfDate'],
                    $this->widget->settings['dfCategory'],
                    $this->widget->settings['dfText'],
                    'Custom_Fields',
                    $this->widget->settings['dfReadMore'],
                );

                /**
                 *  Filter list layout to displayed on each box of the content.
                 *  You can change position of item in the box.
                 *
                 * @param array   Default item
                 * @param integer ID of widget
                 * @param string  Theme name
                 *
                 * @return array
                 *
                 * @ignore Hook already documented
                 */
                $top_box = apply_filters('wplp_box_layout_position', $top_box, 'top', $this->widget->ID, 'default');
                /**
                 * Post fields to display on each part of the layout
                 * You can add / remove post fields on any layout part (top, right, bottom, left)
                 *
                 * @param array   Default items
                 * @param string  Box position
                 * @param integer Widget ID
                 * @param string  Theme name
                 *
                 * @return array
                 *
                 * @ignore Hook already documented
                 */
                $bottom_box = apply_filters('wplp_box_layout_position', $bottom_box, 'bottom', $this->widget->ID, 'default');
            }

            $this->widget->settings['box_top']    = $top_box;
            $this->widget->settings['box_left']   = $left_box;
            $this->widget->settings['box_right']  = $right_box;
            $this->widget->settings['box_bottom'] = $bottom_box;
        }
    }

    /**
     * Reset Box Settings
     *
     * @return void
     */
    private function resetSettingsPremium()
    {
        $top_box    = array();
        $bottom_box = array();

        // Get default box
        if ($this->widget->settings['theme'] === 'masonry-category') {
            $top_box = array('Thumbnail', 'Title');
        } elseif ($this->widget->settings['theme'] === 'portfolio') {
            $top_box = array('Thumbnail', 'Title');
        } elseif ($this->widget->settings['theme'] === 'smooth-effect') {
            $top_box = array('Category', 'Date');
        } elseif ($this->widget->settings['theme'] === 'timeline') {
            $top_box = array('Thumbnail');
        } elseif ($this->widget->settings['theme'] === 'material-vertical') {
            $top_box = array('Thumbnail', 'Category', 'Title', 'Text', 'Custom_Fields');
        } else {
            $top_box = array('Thumbnail', 'Title', 'Date', 'Text', 'Custom_Fields');
        }

        if ($this->widget->settings['theme'] === 'masonry-category') {
            $bottom_box = array('Category');
        } elseif ($this->widget->settings['theme'] === 'portfolio') {
            $bottom_box = array('Category');
        } elseif ($this->widget->settings['theme'] === 'smooth-effect') {
            $bottom_box = array('Title', 'Text', 'Custom_Fields', 'Read more');
        } elseif ($this->widget->settings['theme'] === 'timeline') {
            $bottom_box = array(
                'Thumbnail',
                'Title',
                'Category',
                'Text',
                'Custom_Fields',
                'Read more',
                'Date'
            );
        } elseif ($this->widget->settings['theme'] === 'material-vertical') {
            $bottom_box = array('Author', 'Read more');
        } else {
            $bottom_box = array('Read more');
        }

        /**
         * Post fields to display on each part of the layout
         * You can add / remove post fields on any layout part (top, right, bottom, left)
         *
         * @param array   Default items
         * @param string  Box position
         * @param integer Widget ID
         * @param string  Theme name
         *
         * @return array
         *
         * @ignore Hook already documented
         */
        $top_box = apply_filters('wplp_box_layout_position', $top_box, 'top', $this->widget->ID, $this->widget->settings['theme']);
        /**
         * Post fields to display on each part of the layout
         * You can add / remove post fields on any layout part (top, right, bottom, left)
         *
         * @param array   Default items
         * @param string  Box position
         * @param integer Widget ID
         * @param string  Theme name
         *
         * @return array
         *
         * @ignore Hook already documented
         */
        $bottom_box = apply_filters('wplp_box_layout_position', $bottom_box, 'bottom', $this->widget->ID, $this->widget->settings['theme']);


        // Render box
        $this->widget->settings['box_top']    = $top_box;
        $this->widget->settings['box_left']   = null;
        $this->widget->settings['box_right']  = null;
        $this->widget->settings['box_bottom'] = $bottom_box;

        $this->widget->settings['margin_top']    = 0;
        $this->widget->settings['margin_right']  = 0;
        $this->widget->settings['margin_bottom'] = 0;
        $this->widget->settings['margin_left']   = 0;
    }

    /**
     * Selects posts to display in our widget
     *
     * @return array
     */
    private function queryPosts()
    {

        wp_reset_postdata();
        $language = '';
        if (function_exists('icl_object_id')) {
            if (isset($this->widget->settings['content_language'])) {
                $language = $this->widget->settings['content_language'];
            }
        }
        $content_include = 'category__in';
        if (class_exists('WPLPAddonAdmin')) {
            if (isset($this->widget->settings['content_include']) && (int) $this->widget->settings['content_include'] === 0) {
                //Content include all
                $content_include = 'category__and';
            }
        }
        $posts = array();

        /**
         * For posts and page source_types *
         */
        if ('src_category' === $this->widget->settings['source_type']
            || 'src_page' === $this->widget->settings['source_type']
            || 'src_custom_post_type' === $this->widget->settings['source_type']
        ) {
            /**
             * Source_types (post_type) *
             */
            $post_type = 'post';
            if ('src_category' === $this->widget->settings['source_type']) {
                $post_type = 'post';
            }
            if ('src_page' === $this->widget->settings['source_type']) {
                $post_type = 'page';
            }
            if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                $post_type = $this->widget->settings['custom_post_type'];
            }

            $post_type = apply_filters('wplp_post_type', $post_type);
            /**
             * Source_order (order_by) *
             */
            if (defined('WPLP_ORDER_BY_MODIFY')) {
                $order_by = WPLP_ORDER_BY_MODIFY;
            } else {
                $order_by = 'date';
            }

            if ('src_category' === $this->widget->settings['source_type']) {
                if ('date' === $this->widget->settings['cat_post_source_order']) {
                    if (defined('WPLP_ORDER_BY_MODIFY')) {
                        $order_by = WPLP_ORDER_BY_MODIFY;
                    } else {
                        $order_by = 'date';
                    }
                }
                if ('title' === $this->widget->settings['cat_post_source_order']) {
                    $order_by = 'title';
                }
                if ('order' === $this->widget->settings['cat_post_source_order']) {
                    $order_by = 'menu_order';
                }
                if ('random' === $this->widget->settings['cat_post_source_order']) {
                    $order_by = 'rand';
                }
                if ('modified' === $this->widget->settings['cat_post_source_order']) {
                    $order_by = 'modified';
                }
                if ('view' === $this->widget->settings['cat_post_source_order']) {
                    $order_by = 'meta_value_num';
                }
            }
            if ('src_page' === $this->widget->settings['source_type']) {
                if ('date' === $this->widget->settings['pg_source_order']) {
                    if (defined('WPLP_ORDER_BY_MODIFY')) {
                        $order_by = WPLP_ORDER_BY_MODIFY;
                    } else {
                        $order_by = 'date';
                    }
                }
                if ('title' === $this->widget->settings['pg_source_order']) {
                    $order_by = 'title';
                }
                if ('order' === $this->widget->settings['pg_source_order']) {
                    $order_by = 'menu_order';
                }
                if ('random' === $this->widget->settings['pg_source_order']) {
                    $order_by = 'rand';
                }
                if ('modified' === $this->widget->settings['pg_source_order']) {
                    $order_by = 'modified';
                }
            }
            if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                if ('date' === $this->widget->settings['cat_source_order']) {
                    if (defined('WPLP_ORDER_BY_MODIFY')) {
                        $order_by = WPLP_ORDER_BY_MODIFY;
                    } else {
                        $order_by = 'date';
                    }
                }
                if ('title' === $this->widget->settings['cat_source_order']) {
                    $order_by = 'title';
                }
                if ('order' === $this->widget->settings['cat_source_order']) {
                    $order_by = 'menu_order';
                }
                if ('random' === $this->widget->settings['cat_source_order']) {
                    $order_by = 'rand';
                }
                if ('modified' === $this->widget->settings['cat_source_order']) {
                    $order_by = 'modified';
                }
                if ('view' === $this->widget->settings['cat_source_order']) {
                    $order_by = 'meta_value_num';
                }
            }
            /**
             * Source_asc (order) *
             */
            $order = 'DESC';
            if ('src_category' === $this->widget->settings['source_type']) {
                if ('desc' === $this->widget->settings['cat_post_source_asc']) {
                    $order = 'DESC';
                }
                if ('asc' === $this->widget->settings['cat_post_source_asc']) {
                    $order = 'ASC';
                }
            }
            if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                if ('desc' === $this->widget->settings['cat_source_asc']) {
                    $order = 'DESC';
                }
                if ('asc' === $this->widget->settings['cat_source_asc']) {
                    $order = 'ASC';
                }
            }
            if ('src_page' === $this->widget->settings['source_type']) {
                if ('desc' === $this->widget->settings['pg_source_asc']) {
                    $order = 'DESC';
                }
                if ('asc' === $this->widget->settings['pg_source_asc']) {
                    $order = 'ASC';
                }
            }
            /**
             * Max_elts (limit / posts_per_page) *
             */
            $limit = 10;
            if (isset($this->widget->settings['per_page']) && $this->widget->settings['per_page'] > 0) {
                $limit = $this->widget->settings['per_page'];
            }

            $offSet = null;
            if (isset($this->widget->settings['off_set']) && $this->widget->settings['off_set'] > 0) {
                $offSet = $this->widget->settings['off_set'];
            }
            $args = array(
                'post_type'      => $post_type,
                'orderby'        => $order_by,
                'order'          => $order,
                'posts_per_page' => $limit,
                'offset'         => $offSet
            );

            if (isset($this->widget->settings['load_more']) && (int) $this->widget->settings['load_more'] === 1) {
                if (is_multisite()) {
                    $args['posts_per_page'] = -1;
                    $args['offset'] = 0;
                }
            }
            //add meta key to query
            if ('src_category' === $this->widget->settings['source_type']) {
                if ('view' === $this->widget->settings['cat_post_source_order']) {
                    $args['meta_key'] = WPLP_POST_VIEWS_COUNT_META_KEY;
                }
            }
            //add meta key to query custom post
            if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                if ('view' === $this->widget->settings['cat_source_order']) {
                    $args['meta_key'] = WPLP_POST_VIEWS_COUNT_META_KEY;
                }
            }
            if (is_multisite()) {
                if ('src_category' === $this->widget->settings['source_type']) {
                    if (isset($this->widget->settings['mutilsite_cat'])
                        && 'all_blog' === $this->widget->settings['mutilsite_cat']
                    ) {
                        if ('_all' === $this->widget->settings['source_category'][0]) {
                            $blogs = get_sites();
                        } else {
                            $source = $this->widget->settings['source_category'];
                            $blocks = array();
                            $all_blogs = get_sites();
                            foreach ($source as $v) {
                                $explode = explode('_blog', $v);
                                $blocks[] = (int) $explode[1];
                            }

                            $blogs = array();
                            foreach ($all_blogs as $all_blog) {
                                if (in_array($all_blog->blog_id, $blocks)) {
                                    $blogs[] = $all_blog;
                                }
                            }
                        }

                        $posts = array();
                        foreach ($blogs as $blog) {
                            switch_to_blog((int) $blog->blog_id);
                            if ('_all' === $this->widget->settings['source_category'][0]
                            ) {
                                $cat_all = get_categories();
                                foreach ($cat_all as $cat) {
                                    $args[$content_include][] = (string) ($cat->term_id);
                                }
                            } else {
                                $cat_in = array();
                                foreach ($source as $v) {
                                    $explode = explode('_blog', $v);
                                    if (isset($explode[1]) && (int) $explode[1] === (int) $blog->blog_id) {
                                        $explode1 = explode('_', $explode[0]);
                                        $cat_in[] = (int) $explode1[1];
                                    }
                                }

                                $args[$content_include] = $cat_in;
                            }

                            /**
                             * Filter list argument to get posts.
                             *
                             * @param array List argument
                             * @param array List settings
                             *
                             * @return array
                             */
                            $args     = apply_filters('wplp_src_category_args', $args, $this->widget->settings);
                            $allposts = get_posts($args);
                            foreach ($allposts as $post) {
                                $post->curent_blog_id = (int) $blog->blog_id;
                                $posts[]              = $post;
                            }
                            restore_current_blog();
                        }
                        $this->count_posts = count($posts);
                    } elseif (isset($this->widget->settings['mutilsite_cat'])) {
                        switch_to_blog((int) $this->widget->settings['mutilsite_cat']);
                        if ('src_category' === $this->widget->settings['source_type']
                            && '_all' === $this->widget->settings['source_category'][0]
                        ) {
                            $cat_all = get_categories();
                            foreach ($cat_all as $cat) {
                                $args[$content_include][] = (string) ($cat->term_id);
                            }
                        } elseif ('src_category' === $this->widget->settings['source_type']) {
                            $source = $this->widget->settings['source_category'];
                            foreach ($source as $v) {
                                $sour                     = substr($v, strpos($v, '_') + 1);
                                $args[$content_include][] = $sour;
                            }
                        }
                        /**
                         * Filter list argument to get posts.
                         *
                         * @param array List argument
                         * @param array List settings
                         *
                         * @return array
                         *
                         * @ignore Hook already documented
                         */
                        $args  = apply_filters('wplp_src_category_args', $args, $this->widget->settings);

                        $allposts = get_posts($args);
                        foreach ($allposts as $post) {
                            $post->curent_blog_id = (int) (int) $this->widget->settings['mutilsite_cat'];
                            $posts[]              = $post;
                        }
                        restore_current_blog();
                        $this->count_posts = count($posts);
                    }
                }
                //source page
                if ('src_page' === $this->widget->settings['source_type']) {
                    if (isset($this->widget->settings['mutilsite_page'])
                        && 'all_blog' === $this->widget->settings['mutilsite_page']
                    ) {
                        if (isset($this->widget->settings['source_pages'])) {
                            if (!in_array('_all', $this->widget->settings['source_pages'])) {
                                $source = $this->widget->settings['source_pages'];
                                foreach ($source as $v) {
                                    $sour               = substr($v, strpos($v, '_') + 1);
                                    $args['post__in'][] = $sour;
                                }
                            }
                            /**
                             * Filter list argument to get posts.
                             *
                             * @param array List argument
                             * @param array List settings
                             *
                             * @return array
                             *
                             * @ignore Hook already documented
                             */
                            $args = apply_filters('wplp_src_category_args', $args, $this->widget->settings);

                            $blogs = get_sites();
                            foreach ($blogs as $blog) {
                                switch_to_blog((int) $blog->blog_id);
                                $allposts = get_posts($args);
                                foreach ($allposts as $post) {
                                    $post->curent_blog_id = (int) $blog->blog_id;
                                    $posts[]              = $post;
                                }
                                restore_current_blog();
                            }
                            $this->count_posts = count($posts);
                        }
                    } elseif (isset($this->widget->settings['mutilsite_page'])) {
                        switch_to_blog((int) $this->widget->settings['mutilsite_page']);
                        if ('src_page' === $this->widget->settings['source_type']
                            && isset($this->widget->settings['source_pages'])
                        ) {
                            if (!in_array('_all', $this->widget->settings['source_pages'])) {
                                $source = $this->widget->settings['source_pages'];
                                foreach ($source as $v) {
                                    $sour               = substr($v, strpos($v, '_') + 1);
                                    $args['post__in'][] = $sour;
                                }
                            }
                        }
                        /**
                         * Filter list argument to get posts.
                         *
                         * @param array List argument
                         * @param array List settings
                         *
                         * @return array
                         *
                         * @ignore Hook already documented
                         */
                        $args  = apply_filters('wplp_src_category_args', $args, $this->widget->settings);
                        $posts = get_posts($args);
                        foreach ($posts as $post) {
                            $post->curent_blog_id = (int) $this->widget->settings['mutilsite_page'];
                        }
                        restore_current_blog();
                        $this->count_posts = count($posts);
                    }
                }

                //source custom post

                if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                    if (!empty($this->widget->settings['custom_post_type'])) {
                        $custom            = $this->widget->settings['custom_post_type'];
                        $blog_id           = substr($custom, 0, strpos($custom, '_'));
                        $post_type         = substr($custom, strpos($custom, '_') + 1);
                        $args['post_type'] = $post_type;
                    }
                    if (!empty($blog_id)) {
                        switch_to_blog((int) $blog_id);
                        if (isset($this->widget->settings['custom_post_taxonomy'])) {
                            if (empty($this->widget->settings['custom_post_taxonomy'])
                                || $this->widget->settings['custom_post_taxonomy'] === 'all_taxonomies'
                            ) {
                                //get post by all
                                $ssettings = $this->widget->settings;
                            } else {
                                $args['tax_query'] = array(
                                    array(
                                        'taxonomy' => $this->widget->settings['custom_post_taxonomy'],
                                        'field'    => 'term_id'
                                    )
                                );
                            }
                            // get posts by terms
                            if (isset($args['tax_query'][0]['taxonomy'])) {
                                if ($this->widget->settings['custom_post_term'] === ''
                                    || 'all_terms' === $this->widget->settings['custom_post_term']
                                ) {
                                    $terms                         = get_terms(
                                        $this->widget->settings['custom_post_taxonomy'],
                                        array('hide_empty' => false)
                                    );
                                    $term_ids                      = wp_list_pluck($terms, 'term_id');
                                    $args['tax_query'][0]['terms'] = array_values($term_ids);
                                } else {
                                    $args['tax_query'][0]['terms'] = (int) $this->widget->settings['custom_post_term'];
                                }
                            }
                        }
                        /**
                         * Filter list argument to get posts.
                         *
                         * @param array List argument
                         * @param array List settings
                         *
                         * @return array
                         *
                         * @ignore Hook already documented
                         */
                        $args = apply_filters('wplp_src_category_args', $args, $this->widget->settings);

                        $posts = get_posts($args);
                        foreach ($posts as $post) {
                            $post->curent_blog_id = (int) $blog_id;
                        }
                        restore_current_blog();
                        $this->count_posts = count($posts);
                    }
                }
            } else {
                //fix custom post type again
                if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                    if (isset($this->widget->settings['custom_post_taxonomy'])) {
                        if ($this->widget->settings['custom_post_taxonomy'] === ''
                            || $this->widget->settings['custom_post_taxonomy'] === 'all_taxonomies'
                        ) {
                            //get post by all
                            $ssettings = $this->widget->settings;
                        } else {
                            $args['tax_query'] = array(
                                array(
                                    'taxonomy' => $this->widget->settings['custom_post_taxonomy'],
                                    'field'    => 'term_id'
                                )
                            );
                        }
                        // get posts by terms
                        if (isset($args['tax_query'][0]['taxonomy'])) {
                            if ($this->widget->settings['custom_post_term'] === ''
                                || 'all_terms' === $this->widget->settings['custom_post_term']
                            ) {
                                $terms                         = get_terms(
                                    $this->widget->settings['custom_post_taxonomy'],
                                    array('hide_empty' => false)
                                );
                                $term_ids                      = wp_list_pluck($terms, 'term_id');
                                $args['tax_query'][0]['terms'] = array_values($term_ids);
                            } else {
                                $args['tax_query'][0]['terms'] = (int) $this->widget->settings['custom_post_term'];
                            }
                        }
                    }

                    //fix custom post type
                    if (!empty($this->widget->settings['custom_post_type'])) {
                        $args['post_type'] = $this->widget->settings['custom_post_type'];
                    }
                }

                /**
                 * Include specifics pages *
                 */
                if ('src_page' === $this->widget->settings['source_type']
                    && isset($this->widget->settings['source_pages'])
                ) {
                    if (!in_array('_all', $this->widget->settings['source_pages'])) {
                        $args['post__in'] = $this->widget->settings['source_pages'];
                    }
                }

                /**
                 * Filter by category *
                 */
                if ('src_category' === $this->widget->settings['source_type']
                    && isset($this->widget->settings['source_category'])
                    && '_all' !== $this->widget->settings['source_category'][0]
                ) {
                    $args[$content_include] = $this->widget->settings['source_category'];
                } elseif ('src_category' === $this->widget->settings['source_type']) {
                    $cat_all = get_categories();
                    foreach ($cat_all as $cat) {
                        $args[$content_include][] = (string) ($cat->term_id);
                    }
                }
                /**
                 * Filter list argument to get posts.
                 *
                 * @param array List argument
                 * @param array List settings
                 *
                 * @return array
                 *
                 * @ignore Hook already documented
                 */
                $args  = apply_filters('wplp_src_category_args', $args, $this->widget->settings);
                $posts = get_posts($args);
            }
            /**
             * Get Posts by language via WPML.
             *
             * @param array|object List of posts
             * @param string       Type of post
             * @param array        Language to translate
             *
             * @internal
             *
             * @return array|object
             */
            $posts = apply_filters('wplp_get_posts_by_language', $posts, $post_type, $language);
        } elseif ('src_tags' === $this->widget->settings['source_type']) {
            $post_type = 'post';
            $order_by  = 'date';
            $order    = 'DESC';
            if (function_exists('icl_object_id')) {
                $language = ICL_LANGUAGE_CODE;
                if (isset($this->widget->settings['content_language'])) {
                    $language = $this->widget->settings['content_language'];
                }
            }
            $limit = 10;

            if (isset($this->widget->settings['per_page']) && $this->widget->settings['per_page'] > 0) {
                $limit = $this->widget->settings['per_page'];
            }

            $offSet = null;
            if (isset($this->widget->settings['off_set']) && $this->widget->settings['off_set'] > 0) {
                $offSet = $this->widget->settings['off_set'];
            }

            if (is_multisite()) {
                if (isset($this->widget->settings['mutilsite_tag'])
                    && !empty($this->widget->settings['mutilsite_tag'])
                ) {
                    if ('all_blog' === $this->widget->settings['mutilsite_tag']) {
                        if ('_all' === $this->widget->settings['source_tags'][0]) {
                            $blogs = get_sites();
                        } else {
                            $source = $this->widget->settings['source_tags'];
                            $blocks = array();
                            $all_blogs = get_sites();
                            foreach ($source as $v) {
                                $explode = explode('_blog', $v);
                                $blocks[] = (int) $explode[1];
                            }

                            $blogs = array();
                            foreach ($all_blogs as $all_blog) {
                                if (in_array($all_blog->blog_id, $blocks)) {
                                    $blogs[] = $all_blog;
                                }
                            }
                        }

                        $posts = array();
                        foreach ($blogs as $blog) {
                            switch_to_blog((int) $blog->blog_id);
                            if (isset($this->widget->settings['source_tags'])
                                && !empty($this->widget->settings['source_tags'])
                            ) {
                                $source_tag = array();
                                foreach ($this->widget->settings['source_tags'] as $tag) {
                                    if ($tag === '_all') {
                                        $tags = get_tags();
                                        foreach ($tags as $tg) {
                                            $source_tag[] = $tg->term_id;
                                        }
                                    } else {
                                        $explode = explode('_blog', $tag);
                                        if (isset($explode[1]) && (int) $explode[1] === (int) $blog->blog_id) {
                                            $explode1 = explode('_', $explode[0]);
                                            $source_tag[] = (int) $explode1[1];
                                        }
                                    }
                                }
                            }
                            $args     = array(
                                'posts_per_page' => -1,
                                'post_type'      => $post_type,
                                'orderby'        => $order_by,
                                'order'          => isset($order) ? $order : '',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'post_tag',
                                        'field'    => 'term_id',
                                        'terms'    => isset($source_tag) ? $source_tag : ''
                                    )
                                )
                            );

                            if (is_plugin_active('polylang/polylang.php')) {
                                $args['lang'] = $language;
                            }

                            if (isset($this->widget->settings['load_more']) && (int) $this->widget->settings['load_more'] === 1) {
                                $args['posts_per_page'] = -1;
                                $args['offset'] = 0;
                            }

                            $allposts = get_posts($args);
                            foreach ($allposts as $post) {
                                $post->curent_blog_id = (int) $blog->blog_id;
                                $posts[]              = $post;
                            }
                            restore_current_blog();
                        }

                        $this->count_posts = count($posts);
                        /*if (empty($this->widget->settings['load_more_ajax'])) {
                            $posts = array_slice($posts, $this->widget->settings['off_set'], $limit);
                        }*/
                    } else {
                        $posts = array();
                        switch_to_blog((int) $this->widget->settings['mutilsite_tag']);
                        if (isset($this->widget->settings['source_tags'])
                            && !empty($this->widget->settings['source_tags'])
                        ) {
                            $source_tag = array();
                            foreach ($this->widget->settings['source_tags'] as $tag) {
                                if ($tag === '_all') {
                                    $tags = get_tags();
                                    foreach ($tags as $tagg) {
                                        $source_tag[] = $tagg->term_id;
                                    }
                                } else {
                                    $explode = explode('_blog', $tag);
                                    if (isset($explode[1]) && (int) $explode[1] === (int) $this->widget->settings['mutilsite_tag']) {
                                        $explode1 = explode('_', $explode[0]);
                                        $source_tag[] = (int) $explode1[1];
                                    }
                                }
                            }
                        }
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type'      => $post_type,
                            'orderby'        => $order_by,
                            'order'          => isset($order) ? $order : '',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'post_tag',
                                    'field'    => 'term_id',
                                    'terms'    => isset($source_tag) ? $source_tag : ''
                                )
                            )
                        );

                        if (is_plugin_active('polylang/polylang.php')) {
                            $args['lang'] = $language;
                        }

                        if (isset($this->widget->settings['load_more']) && (int) $this->widget->settings['load_more'] === 1) {
                            $args['posts_per_page'] = -1;
                            $args['offset'] = 0;
                        }

                        $allposts = get_posts($args);
                        foreach ($allposts as $post) {
                            $post->curent_blog_id = (int) $this->widget->settings['mutilsite_tag'];
                            $posts[]              = $post;
                        }
                        restore_current_blog();
                        $this->count_posts = count($posts);
                        /*if (empty($this->widget->settings['load_more_ajax'])) {
                            $posts = array_slice($posts, $this->widget->settings['off_set'], $limit);
                        }*/
                    }
                }
            } else {
                if (isset($this->widget->settings['source_tags']) && !empty($this->widget->settings['source_tags'])) {
                    foreach ($this->widget->settings['source_tags'] as $tag) {
                        if ($tag === '_all') {
                            $tags = get_tags();
                            foreach ($tags as $tagg) {
                                $source_tag[] = $tagg->term_id;
                            }
                        } else {
                            $source_tag[] = $tag;
                        }
                    }
                }

                $args = array(
                    'posts_per_page' => $limit,
                    'offset'         => $offSet,
                    'post_type'      => $post_type,
                    'orderby'        => $order_by,
                    'order'          => isset($order) ? $order : '',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'post_tag',
                            'field'    => 'term_id',
                            'terms'    => isset($source_tag) ? $source_tag : ''
                        )
                    )
                );

                if (is_plugin_active('polylang/polylang.php')) {
                    $args['lang'] = $language;
                }
                $posts = get_posts($args);
            }

            if (!is_plugin_active('polylang/polylang.php')) {
                /**
                 * Get Posts by language via WPML.
                 *
                 * @param array|object List         of posts
                 * @param string       Type of post
                 * @param array        Language to translate
                 *
                 * @internal
                 *
                 * @return array|object
                 */
                $posts = apply_filters('wplp_get_posts_by_language', $posts, $post_type, $language);
            }
        } elseif ('src_category_list' === $this->widget->settings['source_type']) {
            // Display list category
            $order_by = 'id';
            $order    = 'DESC';
            /**
             * Source_by (order) *
             */
            if ('id' === $this->widget->settings['cat_list_source_order']) {
                $order_by = 'id';
            }
            if ('name' === $this->widget->settings['cat_list_source_order']) {
                $order_by = 'name';
            }
            if ('description' === $this->widget->settings['cat_list_source_order']) {
                $order_by = 'description';
            }
            /**
             * Source_asc (order) *
             */
            if ('desc' === $this->widget->settings['cat_list_source_asc']) {
                $order = 'DESC';
            }
            if ('asc' === $this->widget->settings['cat_list_source_asc']) {
                $order = 'ASC';
            }
            /**
             * Max_elts (limit / posts_per_page) *
             */
            $limit = 10;
            if ($this->widget->settings['max_elts'] > 0) {
                $limit = $this->widget->settings['max_elts'];
            }
            $offSet = null;
            if (isset($this->widget->settings['off_set']) && $this->widget->settings['off_set'] > 0) {
                $offSet = $this->widget->settings['off_set'];
            }
            $args = array(
                'taxonomy'   => 'category',
                'orderby'    => $order_by,
                'order'      => $order,
                'number'     => $limit,
                'offset'     => $offSet,
                'hide_empty' => false,
            );
            // Check in multisite
            if (is_multisite()) {
                if (isset($this->widget->settings['mutilsite_cat_list'])
                    && 'all_blog' === $this->widget->settings['mutilsite_cat_list']
                ) {
                    $cats = array();
                    if (!empty($this->widget->settings['source_category_list'])) {
                        if (in_array('_all', $this->widget->settings['source_category_list'])) {
                            $blogs = get_sites();

                            foreach ($blogs as $blog) {
                                switch_to_blog((int) $blog->blog_id);
                                $cat_all = get_terms($args);
                                /**
                                 * Filter category by language plugin
                                 *
                                 * @param array  List of argument
                                 * @param string Language to translate
                                 * @param array  List category
                                 *
                                 * @internal
                                 *
                                 * @return array
                                 */
                                $cat_all = apply_filters('wplp_category_list_by_language', $args, $language, $cat_all);
                                // Set blog id
                                foreach ($cat_all as $cat) {
                                    $cat->curent_blog_id = (int) $blog->blog_id;
                                    $cats[]              = $cat;
                                }
                                restore_current_blog();
                            }
                        } else {
                            foreach ($this->widget->settings['source_category_list'] as $v) {
                                $v               = substr($v, strpos($v, '_') + 1);
                                $catId           = substr($v, 0, strpos($v, '_blog'));
                                $blog_id         = substr($v, strpos($v, '_blog') + strlen('_blog'));
                                $args['include'] = $catId;
                                // Switch to multisite
                                switch_to_blog((int) $blog_id);
                                $cat_all = get_terms($args);
                                /**
                                 * Filter category by language plugin
                                 *
                                 * @param array  List of argument
                                 * @param string Language to translate
                                 * @param array  List category
                                 *
                                 * @internal
                                 *
                                 * @return array
                                 */
                                $cat_all = apply_filters('wplp_category_list_by_language', $args, $language, $cat_all);
                                foreach ($cat_all as $cat) {
                                    $cat->curent_blog_id = (int) $blog_id;
                                    $cats[]              = $cat;
                                }
                                restore_current_blog();
                            }
                        }
                    }
                } elseif (isset($this->widget->settings['mutilsite_cat_list'])) {
                    switch_to_blog((int) $this->widget->settings['mutilsite_cat_list']);
                    if (!in_array('_all', $this->widget->settings['source_category_list'])) {
                        $sour = array();
                        foreach ($this->widget->settings['source_category_list'] as $v) {
                            $v      = substr($v, strpos($v, '_') + 1);
                            $v      = substr($v, 0, strpos($v, '_blog'));
                            $sour[] = $v;
                        }

                        $args['include'] = $sour;
                    }

                    $cats = get_terms($args);
                    /**
                     * Filter category by language plugin
                     *
                     * @param array  List of argument
                     * @param string Language to translate
                     * @param array  List category
                     *
                     * @internal
                     *
                     * @return array
                     */
                    $cats = apply_filters('wplp_category_list_by_language', $args, $language, $cats);
                    // Set blog id
                    foreach ($cats as $cat) {
                        $cat->curent_blog_id = (int) $this->widget->settings['mutilsite_cat_list'];
                    }
                    restore_current_blog();
                }
            } else {
                if (isset($this->widget->settings['source_category_list'])
                    && !empty($this->widget->settings['source_category_list'])
                ) {
                    if (!in_array('_all', $this->widget->settings['source_category_list'])) {
                        $args['include'] = $this->widget->settings['source_category_list'];
                    }
                    $cats = get_terms($args);
                    /**
                     * Filter category by language plugin
                     *
                     * @param array  List of argument
                     * @param string Language to translate
                     * @param array  List category
                     *
                     * @internal
                     *
                     * @return array
                     */
                    $cats = apply_filters('wplp_category_list_by_language', $args, $language, $cats);
                }
            }

            if (!empty($cats)) {
                $posts = array();
                foreach ($cats as $cat) {
                    $post               = new stdClass();
                    $post->ID           = $cat->term_id;
                    $post->post_title   = $cat->name;
                    $post->post_content = $cat->description;
                    if (isset($cat->curent_blog_id)) {
                        $post->curent_blog_id = $cat->curent_blog_id;
                    }
                    $posts[] = $post;
                }

                $this->count_posts = count($posts);
            }
        }

        wp_reset_postdata();

        $posts = (isset($posts) ? $posts : '');
        //sort array posts to get most recent one sort by date
        if (is_multisite()) {
            if ('src_category_list' !== $this->widget->settings['source_type']) {
                if (is_array($posts)) {
                    if ($order_by === 'title') {
                        if ($order === 'ASC') {
                            usort(
                                $posts,
                                function ($a, $b) {
                                    $al = strtolower($a->post_title);
                                    $bl = strtolower($b->post_title);
                                    if ($al === $bl) {
                                        return 0;
                                    }
                                    return ($al > $bl) ? +1 : -1;
                                }
                            );
                        } else {
                            usort(
                                $posts,
                                function ($a, $b) {
                                    $al = strtolower($a->post_title);
                                    $bl = strtolower($b->post_title);
                                    if ($al === $bl) {
                                        return 0;
                                    }
                                    return ($al < $bl) ? +1 : -1;
                                }
                            );
                        }
                    } elseif ($order_by === 'date') {
                        if ($order === 'ASC') {
                            usort(
                                $posts,
                                function ($a, $b) {
                                    return strtotime($a->post_date) - strtotime($b->post_date);
                                }
                            );
                        } else {
                            usort(
                                $posts,
                                function ($a, $b) {
                                    return strtotime($b->post_date) - strtotime($a->post_date);
                                }
                            );
                        }
                    }

                    if (empty($this->widget->settings['load_more_ajax'])) {
                        if (isset($this->widget->settings['per_page'])) {
                            $posts = array_slice($posts, 0, $this->widget->settings['per_page'], true);
                        } else {
                            $posts = array_slice($posts, 0, 10, true);
                        }
                    }
                }
            }
        }

        $this->posts = $posts;
        return $this->posts;
    }

    /**
     * Add Custom CSS in HTML footer
     *
     * @return void
     */
    public function customCSS()
    {
        $customCSS = $this->widget->settings['custom_css'];

        echo '<style type="text/css">' . esc_html($customCSS) . '</style>';
    }

    /**
     * Front end display
     *
     * @param boolean $echo              Check display front end
     * @param boolean $is_sidebar_widget Check sidebar widget
     *
     * @return mixed|string|void
     */
    public function display($echo = true, $is_sidebar_widget = false)
    {
        $widgetID = $this->widget->ID;
        if (!empty($widgetID)) {
            $widget = get_post($widgetID);
            if (post_password_required($widgetID)) {
                return $this->wplpPasswordForm($widgetID);
            }

            if ($widget->post_status === 'private' && !current_user_can('read_private_posts')) {
                return esc_html__('Sorry, you are not allowed to access widget on this site.', 'wp-latest-posts');
            }

            if ($widget->post_status === 'future') {
                $now = gmdate('Y-m-d H:i:s');
                if ((mysql2date('U', $widget->post_date, false) > mysql2date('U', $now, false))) {
                    return esc_html__('The content will be displayed in the future :)', 'wp-latest-posts');
                }
            }

            if ($widget->post_status === 'draft') {
                return esc_html__('No content has been found here, sorry :)', 'wp-latest-posts');
            }
        }

        if ($this->posts) {
            $this->container($is_sidebar_widget);
        } elseif (isset($this->widget->settings['no_post_text']) && !empty($this->widget->settings['no_post_text'])) {
            $this->html .= $this->widget->settings['no_post_text'];
        } else {
            $this->html .= esc_html__('No content has been found here, sorry :)', 'wp-latest-posts');
        }

        if ($echo) {
            //phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
            echo $this->html;
        }

        return $this->html;
    }

    /**
     * Call display ajax *
     *
     * @param boolean $echo       Check display front end by ajax
     * @param array   $themeclass Name of theme
     *
     * @return mixed|string|void
     */
    public function displayByAjax($echo = true, $themeclass = null)
    {
        if ($this->posts) {
            $this->loop($themeclass);
        } elseif (isset($this->widget->settings['no_post_text']) && !empty($this->widget->settings['no_post_text'])) {
            $this->html .= $this->widget->settings['no_post_text'];
        }

        if ($echo) {
            //phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
            echo $this->html;
        }

        return $this->html;
    }

    /**
     * This dynamically loads theme styles as inline html styles
     *
     * @return void
     */
    public function loadThemeScript()
    {
        global $wpcu_wpfn;
        wp_enqueue_script('jquery');
        if ($this->widget->settings['theme'] === 'masonry'
            || $this->widget->settings['theme'] === 'material-vertical'
            || $this->widget->settings['theme'] === 'masonry-category'
            || $this->widget->settings['theme'] === 'smooth-effect'
            || $this->widget->settings['theme'] === 'timeline'
            || $this->widget->settings['theme'] === 'portfolio'
        ) {
            $theme_root = '';
            /**
             * Filter root of theme in settings
             *
             * @param string Theme root
             *
             * @internal
             *
             * @return string
             */
            $dirs  = apply_filters('wplp_plugindir', $theme_root);
            $theme = $dirs . '/' . $this->widget->settings['theme'];
        } else {
            $theme = dirname(plugin_dir_path(__FILE__)) . '/themes/' . $this->widget->settings['theme'];
        }
        $theme_dir = basename($theme);
        if ($theme_dir === 'masonry' || $theme_dir === 'masonry-category' || $theme_dir === 'material-vertical') {
            wp_enqueue_script('jquery-masonry');
            wp_enqueue_script(
                'wplp_addon_front',
                plugins_url('wp-latest-posts-addon/js') . '/wplp_addon_front.js',
                array('jquery'),
                '0.1',
                true
            );
            $ajax_non = wp_create_nonce('wplp-addon-front-nonce');
            wp_localize_script('wplp_addon_front', 'wpsolAddonFrontJS', array('ajaxnonce' => $ajax_non));
            wp_enqueue_script(
                'wplp_addon_imagesloaded',
                plugins_url('js/imagesloaded.pkgd.min.js', dirname(__FILE__)),
                array('jquery'),
                '0.1',
                true
            );
        }

        $id               = $this->widget->ID;
        $nbcol            = (isset($this->widget->settings['amount_cols']) ? $this->widget->settings['amount_cols'] : 4);
        $nbrow            = (isset($this->widget->settings['amount_rows']) ? $this->widget->settings['amount_rows'] : 0);
        $pagination       = (isset($this->widget->settings['pagination']) ? $this->widget->settings['pagination'] : 0);
        $autoanimate      = (isset($this->widget->settings['autoanimation']) ? $this->widget->settings['autoanimation'] : 0);
        $autoanimatetrans = (isset($this->widget->settings['autoanimation_trans']) ?
            $this->widget->settings['autoanimation_trans'] : 0);
        $animationloop    = (isset($this->widget->settings['autoanim_loop']) ?
            $this->widget->settings['autoanim_loop'] : 0);
        $slideshowspeed   = (isset($this->widget->settings['autoanim_slideshowspeed']) ?
            $this->widget->settings['autoanim_slideshowspeed'] : 0);
        $slidespeed       = (isset($this->widget->settings['autoanim_slidespeed']) ?
            $this->widget->settings['autoanim_slidespeed'] : 0);
        $pausehover       = (isset($this->widget->settings['autoanim_pause_hover']) ?
            $this->widget->settings['autoanim_pause_hover'] : 0);
        $pauseaction      = (isset($this->widget->settings['autoanim_pause_action']) ?
            $this->widget->settings['autoanim_pause_action'] : 0);
        $slidedirection   = (isset($this->widget->settings['autoanimation_slidedir']) ?
            $this->widget->settings['autoanimation_slidedir'] : 0);
        $touchaction      = (isset($this->widget->settings['autoanim_touch_action']) ?
            $this->widget->settings['autoanim_touch_action'] : 0);
        if (file_exists($theme . '/script.js')) {
            $handle = 'themes-wplp-' . $theme_dir;
            wp_enqueue_script(
                $handle,
                plugins_url('wp-latest-posts-addon/themes/') . $theme_dir . '/script.js',
                array('jquery'),
                $wpcu_wpfn->version,
                true
            );
            $data_array = array(
                'id'               => $id,
                'nbcol'            => $nbcol,
                'nbrow'            => $nbrow,
                'pagination'       => $pagination,
                'autoanimate'      => $autoanimate,
                'autoanimatetrans' => $autoanimatetrans,
                'animationloop'    => $animationloop,
                'slideshowspeed'   => $slideshowspeed,
                'slidespeed'       => $slidespeed,
                'pausehover'       => $pausehover,
                'pauseaction'      => $pauseaction,
                'slidedirection'   => $slidedirection,
                'touch'            => $touchaction

            );
            wp_localize_script($handle, 'WPLP_' . (int) $id, $data_array);
        } else {
            wp_enqueue_script(
                'wplp-flexslider',
                plugins_url('wp-latest-posts/js/') . '/flexslider.min.js',
                array('jquery'),
                '2.5.0',
                true
            );
            wp_enqueue_script(
                'scriptdefault-wplp',
                plugins_url('wp-latest-posts/js/') . '/wplp_front.js',
                array('jquery'),
                '1.0',
                true
            );
            $data_array = array(
                'id'               => $id,
                'pagination'       => $pagination,
                'autoanimate'      => $autoanimate,
                'autoanimatetrans' => $autoanimatetrans,
                'animationloop'    => $animationloop,
                'slideshowspeed'   => $slideshowspeed,
                'slidespeed'       => $slidespeed,
                'pausehover'       => $pausehover,
                'pauseaction'      => $pauseaction,
                'slidedirection'   => $slidedirection,
                'touch'            => $touchaction,
            );
            wp_localize_script('scriptdefault-wplp', 'WPLP_' . (int) $id, $data_array);
        }
    }

    /**
     * This dynamically loads theme styles as inline html styles
     *
     * @return void
     */
    public function loadThemeStyle()
    {
        if ($this->widget->settings['theme'] === 'masonry'
            || $this->widget->settings['theme'] === 'material-vertical'
            || $this->widget->settings['theme'] === 'masonry-category'
            || $this->widget->settings['theme'] === 'smooth-effect'
            || $this->widget->settings['theme'] === 'timeline'
            || $this->widget->settings['theme'] === 'portfolio'
        ) {
            $theme_root = '';
            /**
             * Filter root of theme in settings
             *
             * @param string Theme root
             *
             * @internal
             *
             * @return string
             */
            $dirs  = apply_filters('wplp_plugindir', $theme_root);
            $theme = $dirs . '/' . $this->widget->settings['theme'];
        } else {
            // Load default css
            wp_register_style('wplpStyleDefault', plugins_url('themes/default/style.css', dirname(__FILE__)));
            wp_enqueue_style('wplpStyleDefault');

            $theme = dirname(plugin_dir_path(__FILE__)) . '/themes/' . $this->widget->settings['theme'];

            $css = '';
            if (isset($this->widget->settings['arrow_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container.default:hover .flex-next,';
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container .flex-direction-nav .flex-prev';
                $css .= '{color : ' . $this->widget->settings['arrow_color'] . ' !important}';
            }
            if (isset($this->widget->settings['arrow_hover_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container.default:hover .flex-next:hover,';
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container.default:hover .flex-prev:hover';
                $css .= '{color :' . $this->widget->settings['arrow_hover_color'] . ' !important}';
            }
            if (isset($this->widget->settings['readmore_bg_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container .read-more{';
                $css .= 'float:right;';
                $css .= 'background-color : ' . $this->widget->settings['readmore_bg_color'] . '}';
            }
            if (isset($this->widget->settings['readmore_border'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container .read-more{';
                $css .= 'border :1px solid ' . $this->widget->settings['readmore_bg_color'] . ';';
                $css .= 'border-radius: ' . $this->widget->settings['readmore_border'] . 'px;}';
            }
            if (isset($this->widget->settings['readmore_text_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container .read-more{color : ' . $this->widget->settings['readmore_text_color'] . '}';
            }
            if (isset($this->widget->settings['readmore_size'])) {
                $fontsize = '';
                if ((int) $this->widget->settings['readmore_size'] === 2) {
                    $padding  = 'padding: 10px 20px';
                    $fontsize = 'font-size: 1em;';
                } elseif ((int) $this->widget->settings['readmore_size'] === 1) {
                    $padding = 'padding: 5px 10px';
                } else {
                    $padding = 'padding: 3px 7px';
                }
                $css .= '#wplp_widget_' . $this->widget->ID . '.wplp_container .read-more{' . $fontsize . $padding . '}';
            }


            //Load OVERLAY
            if (isset($this->widget->settings['overlay_transparent'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li.parent ul li:hover .img_cropper:before,';
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li.parent ul li:hover .img_cropper:after {';
                $css .= 'opacity:' . $this->widget->settings['overlay_transparent'] . '}';
            }
            if (isset($this->widget->settings['overlay_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li .img_cropper:after {';
                $css .= 'background:' . $this->widget->settings['overlay_color'] . ';';
                $css .= '-webkit-box-shadow: inset 0 0 10px 2px ' . $this->widget->settings['overlay_color'] . ';';
                $css .= 'box-shadow: inset 0 0 10px 2px ' . $this->widget->settings['overlay_color'] . ';}';
            }
            if (isset($this->widget->settings['overlay_icon_selected'])) {
                if ($this->widget->settings['overlay_icon_selected'] !== '') {
                    $content = 'content:\'\\' . $this->widget->settings['overlay_icon_selected'] . '\';';
                } else {
                    $content = 'content: none;';
                }
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li .img_cropper:before{';
                $css .= $content . '}';
            }

            if (isset($this->widget->settings['over_icon_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li .img_cropper:before{';
                $css .= 'color:' . $this->widget->settings['over_icon_color'] . '}';
            }
            if (isset($this->widget->settings['over_bg_icon_color'])) {
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li .img_cropper:before{';
                $css .= 'background:' . $this->widget->settings['over_bg_icon_color'] . '}';
            }

            if (!isset($this->widget->settings['force_icon']) || (int) $this->widget->settings['force_icon'] === 0) {
                $css .= '#wplp_widget_' . $this->widget->ID . ' .wplp_listposts li .img_cropper:before {';
                $css .= 'content: none ;';
                $css .= '}';
            }

            // Echo css
            if (!empty($css)) {
                wp_add_inline_style('wplpStyleDefault', $css);
            }
        }
        $theme_dir = basename($theme);

        if (file_exists($theme . '/style.css')) {
            /**
             * Filter to load inline style to front-end
             *
             * @param array   List settings
             * @param string  Theme directory
             * @param integer Id of widget
             *
             * @internal
             *
             * @return void
             */
            apply_filters('wplp_load_inline_style', $this->widget->settings, $theme_dir, $this->widget->ID);
        }

        if ($this->widget->settings['theme'] === 'default') {
            wp_enqueue_style('dashicons');
        }
    }

    /**
     * This is the main container of our widget
     * also acts as outside framing container of a slideshow
     *
     * @param boolean $is_sidebar_widget Check is sidebar widget
     *
     * @return void
     */
    private function container($is_sidebar_widget = false)
    {

        $style_cont  = '';
        $orientation = 'vertical';

        /**
         * Container width *
         */
        if (isset($this->widget->settings['total_width'])
            && 'auto' !== strtolower($this->widget->settings['total_width'])
            && $this->widget->settings['total_width']
        ) {
            global $wpcu_wpfn;
            $style_cont .= 'max-width:' . $this->widget->settings['total_width'];
            $style_cont .= $wpcu_wpfn->width_unit_values[$this->widget->settings['total_width_unit']] . ';';
        }

        /**
         * Slider width *
         */
        if (isset($this->widget->settings['amount_pages'])
            && $this->widget->settings['amount_pages'] > 1
        ) {
            $percent     = $this->widget->settings['amount_pages'] * 100;
            $style_slide = 'width: ' . $percent . '%;';
            $orientation = 'horizontal';

            /**
             * Test colonnes *
             */
            $style_slide .= '-webkit-column-count: 1;';
            $style_slide .= '-moz-column-count: 1;';
            $style_slide .= 'column-count: 1;';
        } else {
            $style_slide = 'width: 100%;';
        }

        if (self::CSS_DEBUG) {
            $style_cont  .= 'border:1px solid #C00;';
            $style_slide .= 'border:1px solid #0C0;';
        }

        $this->html .= '<div class="wplp_outside wplp_widget_' . $this->widget->ID . '" style="' . $style_cont . '">';

        /**
         * Widget block title *
         */
        if (!$is_sidebar_widget
            && isset($this->widget->settings['show_title'])
            && (int) $this->widget->settings['show_title'] === 1
        ) {
            $this->html .= '<span class="wpcu_block_title">' . $this->widget->post_title . '</span>';
        }

        $theme_class = ' ' . basename($this->widget->settings['theme']);

        $default_class   = '';
        $theme_classpro  = '';
        $masonry_class   = '';
        $smooth_class    = '';
        $slideClass      = '';
        $timelineClass   = '';
        $portfolio_Class = '';

        /**
         * Theme $portfolioClass
         */
        if ($theme_class === ' portfolio') {
            $theme_classpro  = ' pro';
            $portfolio_Class = 'portfolioContainer_' . $this->widget->ID;
        }

        if ($theme_class === ' masonry' || $theme_class === ' material-vertical' || $theme_class === ' masonry-category') {
            $theme_classpro = ' pro';
            $masonry_class  = 'masonrycontainer_' . $this->widget->ID;
        }

        if ($theme_class === ' smooth-effect') {
            $theme_classpro = ' pro';
            $smooth_class   = 'smoothcontainer_' . $this->widget->ID;
            $style_cont     = '';
            $style_slide    = '';
            $slideClass     = ' slides';
        }

        if ($theme_class === ' timeline') {
            $theme_classpro = ' pro';
            $timelineClass  = 'timeline_' . $this->widget->ID;
        }

        if ($theme_class === ' default') {
            $default_class = 'default_' . $this->widget->ID;
        }
        $themeclass  = '';
        $themedefaut = '';
        if ($themeclass === '') {
            $style_cont  = '';
            $themedefaut = ' defaultflexslide';
        }

        $amount_cols_class = ' cols' . $this->widget->settings['amount_cols'];

        /**
         * Container div *
         */
        $this->html .= '<div id="wplp_widget_' . $this->widget->ID . '" class="wplp_widget_';
        $this->html .= basename($this->widget->settings['theme']) . ' wplp_container ' . $orientation;
        $this->html .= $themedefaut . $theme_class . $theme_classpro . $amount_cols_class . '" data-post="';
        $this->html .= $this->widget->ID . '" style="' . $style_cont . '" data-max-elts="'. $this->widget->settings['max_elts'] .'" data-per-page="'. $this->widget->settings['per_page'] .'">';
        $this->html .= '<ul class="wplp_listposts' . $slideClass . $themedefaut . '" id="' . $default_class;
        $this->html .= $portfolio_Class . $masonry_class . $smooth_class;
        $this->html .= $timelineClass . '" style="' . $style_slide . '" >';
        $this->loop($theme_class);
        $this->html .= '</ul>';
        if (is_plugin_active('wp-latest-posts-addon/wp-latest-posts-addon.php')) {
            if ($theme_class === ' masonry' || $theme_class === ' material-vertical' || $theme_class === ' masonry-category') {
                if (isset($this->widget->settings['load_more']) && (int) $this->widget->settings['load_more'] === 1) {
                    if (is_multisite()) {
                        if ((int)$this->count_posts > (int)$this->widget->settings['max_elts']) {
                            $this->html .= '<div id="wplp_front_loadmore" >';
                            $this->html .= '<input type="button" data-count="'. esc_attr($this->count_posts) .'" id="wplp_front_load_element" class="wplp_front_load_element"';
                            $this->html .= 'value="' . __('Load more', 'wp-latest-posts') . '" />';
                            $this->html .= '</div>';
                        }
                    } else {
                        $this->html .= '<div id="wplp_front_loadmore" >';
                        $this->html .= '<input type="button" id="wplp_front_load_element" class="wplp_front_load_element"';
                        $this->html .= 'value="' . __('Load more', 'wp-latest-posts') . '" />';
                        $this->html .= '</div>';
                    }
                }
            }
        }
        $this->html .= '</div>';
        $this->html .= '</div>';
    }

    /**
     * This loops through the posts to display in our widget
     * Each post is like a frame if there is a slider
     * although the slider may list more than one frame in a page
     * depending on the theme template
     *
     * @param null $themeclass Class of theme
     *
     * @return void
     */
    private function loop($themeclass = null)
    {
        global $post;

        $style = '';
        if (isset($this->widget->settings['amount_cols']) && ($this->widget->settings['amount_cols'] > 0)) {
            $percent = 100 / $this->widget->settings['amount_cols'];
            if (isset($this->widget->settings['amount_pages'])
                && $this->widget->settings['amount_pages'] > 1
            ) {
                $percent = $percent / $this->widget->settings['amount_pages'];
            }
            if (self::CSS_DEBUG) {
                $percent --;
            }
            $style .= 'width:' . $percent . '%;';
        }
        if (self::CSS_DEBUG) {
            $style .= 'border:1px solid #00C;';
        }


        /*
          if( isset( $this->widget->settings['amount_rows'] ) && ( $this->widget->settings['amount_rows'] > 0 ) ) {
          $this->html .= '';
          }
         */

        /*
         * If themeClass = masonry
         */

        if ($themeclass === ' masonry'
            || $themeclass === ' masonry-category'
            || $themeclass === ' material-vertical'
            || $themeclass === ' smooth-effect'
            || $themeclass === ' timeline'
            || $themeclass === ' portfolio'
        ) {
            $style = '';
        }

        if ($themeclass === ' default') {
            $style = '';
        }

        $backgroundimageLI = false;
        if ($themeclass === ' smooth-effect') {
            $backgroundimageLI = true;
        }

        if ($themeclass === ' default') {
            $i           = 0;
            $counter     = 0;
            $countercols = 0;
            $correcstyle = $style;
            //phpcs:ignore WordPress.WP.GlobalVariablesOverride -- Globals variable is overwritten at the top
            foreach ($this->posts as $post) {
                $i ++;
                $counter ++;
                $countercols ++;

                setup_postdata($post);

                if ($counter !== 1) {
                    $style       = 'width:' . (100 / $this->widget->settings['amount_cols']);
                    $style       .= '%;box-sizing: border-box;-moz-box-sizing: border-box;';
                    $parentClass = '';
                } else {
                    $style       = $correcstyle;
                    $parentClass = 'parent ';
                }


                $this->html .= '<li class="' . $parentClass . '" style="' . $style . '"><div class="insideframe">';
                if ($counter === 1) {
                    $this->html .= '<ul style="' . $style . '">';
                    $this->html .= '<li class="" style="width:' . (100 / $this->widget->settings['amount_cols']);
                    $this->html .= '%;box-sizing: border-box;-moz-box-sizing: border-box;"><div class="insideframe">';
                }
                $this->frame();

                if ($counter === ($this->widget->settings['amount_rows'] * $this->widget->settings['amount_cols'])
                    || $i === count($this->posts)
                ) {
                    $this->html .= '</div></li>';
                    $this->html .= '</ul>';
                    $counter    = 0;
                }

                $this->html .= '</div></li>';
            }
            wp_reset_postdata();
        } else {
            $i = 0;
            //phpcs:ignore WordPress.WP.GlobalVariablesOverride -- Globals variable is overwritten at the top
            foreach ($this->posts as $post) {
                $i ++;
                if (is_multisite()) {
                    if (!empty($this->widget->settings['load_more_ajax'])) {
                        if (($i - 1) < (int)$this->widget->settings['off_set'] || ($i - 1) >= ((int)$this->widget->settings['off_set'] + $this->widget->settings['per_page']) || ($i - 1) > $this->widget->settings['max_elts']) {
                            continue;
                        }
                    }
                }
                setup_postdata($post);
                if ($backgroundimageLI) {
                    // Smooth Hover
                    $imgsrc = '';
                    if ('src_category_list' === $this->widget->settings['source_type']) {
                        if (is_multisite()) {
                            $category_image = get_blog_option($post->curent_blog_id, 'wplp_category_image');
                        } else {
                            $category_image = get_option('wplp_category_image');
                        }
                        if (!empty($category_image)) {
                            foreach ($category_image as $item) {
                                if ($post->ID === $item->term_id) {
                                    $image = $item->image;
                                }
                            }
                        }
                        // Get image size
                        if (!empty($image)) {
                            if (is_multisite()) {
                                switch_to_blog((int) $post->curent_blog_id);
                                $attachment_id = $this->getAttachmentIdByUrl($image);
                                if (!empty($attachment_id)) {
                                    $srca = wp_get_attachment_image_src($attachment_id, 'full');
                                }
                                restore_current_blog();
                            } else {
                                $attachment_id = $this->getAttachmentIdByUrl($image);
                                if (!empty($attachment_id)) {
                                    $srca = wp_get_attachment_image_src($attachment_id, 'full');
                                }
                            }
                        }

                        if (isset($srca[0]) && !empty($image)) {
                            $imgsrc = $srca[0];
                        }
                    } else {
                        if ((int) $this->widget->settings['thumb_img'] === 0) {
                            //echo "feature image";
                            if (is_multisite()) {
                                switch_to_blog($post->curent_blog_id);
                                $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                                if (!empty($post_thumbnail_id)) {
                                    $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                                } else {
                                    if (class_exists('acf')) {
                                        $postID = get_field('image', $post->ID, false);  //image est ACF field
                                        $srca   = wp_get_attachment_image_src(intval($postID), 'full');
                                    }
                                }
                                restore_current_blog();
                            } else {
                                $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                                if (!empty($post_thumbnail_id)) {
                                    $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                                } else {
                                    if (class_exists('acf')) {
                                        $postID = get_field('image', $post->ID, false);  //image est ACF field
                                        $srca   = wp_get_attachment_image_src(intval($postID), 'full');
                                    }
                                }
                            }

                            if (isset($srca[0])) {
                                $imgsrc = $srca[0];
                            }
                        } else {
                            $img = preg_match_all(
                                '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',
                                get_the_content(get_the_ID()),
                                $matches
                            );
                            if ($img) {
                                $img = $matches[1][0];
                                global $wpdb;
                                $attachment_id  = false;
                                $attachment_url = $img;
                                // Get the upload directory paths
                                $upload_dir_paths = wp_upload_dir();
                                if (false !== strpos($attachment_url, $upload_dir_paths['baseurl'])) {
                                    $attachment_url = preg_replace(
                                        '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i',
                                        '',
                                        $attachment_url
                                    );
                                    $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);
                                    $attachment_id  = $wpdb->get_var(
                                        $wpdb->prepare(
                                            'SELECT wposts.ID FROM ' . $wpdb->posts . ' wposts, ' . $wpdb->postmeta . ' wpostmeta ' .
                                            ' WHERE wposts.ID = wpostmeta.post_id ' .
                                            ' AND wpostmeta.meta_key = "_wp_attached_file" ' .
                                            ' AND wpostmeta.meta_value = %s AND wposts.post_type = "attachment"',
                                            $attachment_url
                                        )
                                    );
                                }
                                if ($attachment_id) {
                                    $srca   = wp_get_attachment_image_src($attachment_id, 'full');
                                    $imgsrc = $srca[0];
                                } else {
                                    $imgsrc = $img;
                                }
                            }
                        }
                    }

                    if (!$imgsrc) {
                        $imgsrc = $this->widget->settings['default_img'];
                    }

                    $style = "background-image:url('" . $imgsrc . "')";
                }

                $this->html .= '<li id="wplp_li_' . $this->widget->ID . '_' . $post->ID . '" data-post="';
                $this->html .= $post->ID . '" class="postno_' . $i . $themeclass . ' li-item-id" style="';
                $this->html .= $style . '"><div class="insideframe">';
                $this->frame();
                $this->html .= '</div></li>';
            }
            wp_reset_postdata();
        }
    }

    /**
     * One frame displays data about just one post or article
     * The data is organized geometrically into template boxes or blocks
     *
     * @return void
     */
    private function frame()
    {
        foreach ($this->boxes as $box) {
            //$function = 'box_' . $box;    //Maybe later to have full customization of a box
            $function = 'boxMisc';
            $this->$function($box);  //Variable function name
        }
    }

    /**
     * Builds the content of a block of info for a post
     * inside a frame.
     * $before and $after are only output if there is actual content for that box
     *
     * @param string $before   Before HTML
     * @param string $after    After HTML
     * @param string $box_name Name of boxes
     *
     * @return void
     */
    private function boxContent($before, $after, $box_name)
    {
        $my_html = '';

        //TODO: retrieve fields from theme to display inside this box?
        $fields = $this->widget->settings['box_' . $box_name];
        //if( !$fields )
        //    return;
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $inner = $this->field($field);
                if ($inner) {
                    //var_dump($field);
                    $my_html .= '<span class="' . sanitize_title($field) . '">';
                    $my_html .= $inner;
                    $my_html .= '</span>';
                }
            }
        }
        //if( !$my_html )
        //    return;

        $this->html .= $before;
        $this->html .= $my_html;
        $this->html .= $after;
    }

    /**
     * Formats a field for front-end display
     *
     * @param string $field Field of theme
     *
     * @return string : html output
     */
    private function field($field)
    {
        if (empty($field)) {
            return '';
        }
        global $post;
        if ($this->widget->settings['theme'] === 'portfolio') {
            $cropTextSize  = self::PORTFOLIO_TEXT_EM_SIZE;
            $cropTitleSize = self::PORTFOLIO_TITLE_EM_SIZE;
        } elseif (strpos($this->widget->settings['theme'], 'masonry') !== false) {
            if ($this->widget->settings['theme'] === 'masonry-category') {
                $cropTextSize  = self::MASONRY_CATEGORY_TEXT_EM_SIZE;
                $cropTitleSize = self::MASONRY_CATEGORY_TITLE_EM_SIZE;
            } else {
                $cropTextSize  = self::MASONRY_GID_TEXT_EM_SIZE;
                $cropTitleSize = self::MASONRY_GID_TITLE_EM_SIZE;
            }
        } elseif (strpos($this->widget->settings['theme'], 'material-vertical') !== false) {
            $cropTextSize  = self::MASONRY_MATERIAL_TEXT_EM_SIZE;
            $cropTitleSize = self::MASONRY_GID_TITLE_EM_SIZE;
        } elseif ($this->widget->settings['theme'] === 'smooth-effect') {
            $cropTextSize  = self::SMOOTH_TEXT_EM_SIZE;
            $cropTitleSize = self::SMOOTH_TITLE_EM_SIZE;
        } elseif ($this->widget->settings['theme'] === 'timeline') {
            $cropTextSize  = self::TIMELINE_TEXT_EM_SIZE;
            $cropTitleSize = self::TIMELINE_TITLE_EM_SIZE;
        } else {
            $cropTextSize  = self::DEFAULT_TEXT_EM_SIZE;
            $cropTitleSize = self::DEFAULT_TITLE_EM_SIZE;
        }

        /**
         * Title field *
         */

        if ('Title' === $field) {
            $before = '';
            $after  = '';

            $title = $post->post_title;
            if ('src_category_list' !== $this->widget->settings['source_type']) {
                // Using for posts
                $title = apply_filters('the_title', $title, $post->ID);
            }

            if (class_exists('WPLPAddonAdmin')) {
                if ((int) $this->widget->settings['crop_title'] === 0) {  // word cropping
                    if (function_exists('wp_trim_words')) {
                        $title = wp_trim_words($title, $this->widget->settings['crop_title_len']);
                    }
                }
                if ((int) $this->widget->settings['crop_title'] === 1) {  // char cropping
                    $title = strip_tags($title);
                    $title = mb_substr($title, 0, $this->widget->settings['crop_title_len'], 'UTF-8');
                }
                if ((int) $this->widget->settings['crop_title'] === 2) { // line limitting
                    $style = 'height:' . ($this->widget->settings['crop_title_len'] * $cropTitleSize) . 'em';
                    if (1 === $this->widget->settings['crop_title_len']) {
                        $before = '<span style="' . $style . '" class="line_limit">';
                    } else {
                        $before = '<span style="' . $style . '" class="line_limit">';
                    }
                    $after = '</span>';
                }
            }

            $title_field = $before . $title . $after;

            /**
             *  Filter title for front-end display
             *
             * @param string Title
             *
             * @return string
             */
            $title_field = apply_filters('wplp_front_title_field', $title_field);

            return $title_field;
        }

        if ('product_price' === $field) {
            $add_to_cart = do_shortcode('[add_to_cart_url id="'.$post->ID.'"]');
            $product    = wc_get_product($post->ID);
            $product_html = '<div class="wplp-product-info-wrap" style="padding: 0 20px">';
            $product_html .= '<div class="wplp-price">' . $product->get_price_html() . '</div>';
            $product_html .= '<a class="wplp_add_to_cart" href="'. $add_to_cart .'" style="background: #a46497; padding: 5px 10px; border-radius: 4px; color: #fff">Buy now</a>';
            $product_html .= '</div>';
            return $product_html;
        }

        /**
         * Text field *
         */
        if ('Text' === $field) {
            $before = '';
            $after  = '';

            if (is_multisite()) {
                switch_to_blog($post->curent_blog_id);
                if (isset($this->widget->settings['text_content']) && $this->widget->settings['text_content'] === '0') {
                    $text = (defined('WPLP_KEEP_CONTENT_SHORTCODE')) ? str_replace('[frontpage_news', '[frontpage_news1', $post->post_content) : strip_shortcodes($post->post_content);
                } else {
                    setup_postdata($post);
                    $text = get_the_excerpt($post);
                }
                restore_current_blog();
            } else {
                if (isset($this->widget->settings['text_content']) && $this->widget->settings['text_content'] === '0') {
                    $text = (defined('WPLP_KEEP_CONTENT_SHORTCODE')) ? str_replace('[frontpage_news', '[frontpage_news1', $post->post_content) : strip_shortcodes($post->post_content);
                } else {
                    setup_postdata($post);
                    $text = get_the_excerpt($post);
                }
            }

            // Remove divi builder shortcode
            if (isset($this->widget->settings['text_content']) && $this->widget->settings['text_content'] === '0') {
                $text = preg_replace('/\[\/?et_pb.*?\]/', '', $text);
                $text = preg_replace('~(?:\[/?)[^/\]]+/?\]~s', '', $text);
            }

            if ('src_category_list' !== $this->widget->settings['source_type']) {
                if (isset($this->widget->settings['text_content']) && $this->widget->settings['text_content'] === '0') {
                    $text = apply_filters('the_content', $text);
                } elseif (isset($this->widget->settings['text_content'])
                    && $this->widget->settings['text_content'] === '1'
                ) {
                    $text = apply_filters('the_excerpt', $text);
                }
            }
            $text   = str_replace(']]>', ']]&gt;', $text);
            if (!defined('WPLP_CONTENT_ALL_TAGS')) {
                $text   = wp_strip_all_tags($text);
            }
            $strlen = $text;

            $croplength = (int) $this->widget->settings['crop_text_len'];
            if ((int) $this->widget->settings['crop_text'] === 0) {  // word cropping
                if (function_exists('wp_trim_words')) {
                    $text = wp_trim_words($text, $this->widget->settings['crop_text_len']);
                }
                if ($croplength < str_word_count($strlen)) {
                    $text .= '...';
                }
            }
            if ((int) $this->widget->settings['crop_text'] === 1) {  // char cropping
                $text = strip_tags($text);
                $text = mb_substr($text, 0, $this->widget->settings['crop_text_len'], 'UTF-8');
                $text = mb_substr($text, 0, mb_strripos($text, ' ', 0, 'UTF-8'), 'UTF-8');
                if ($croplength < strlen($strlen)) {
                    $text .= '...';
                }
            }
            if ((int) $this->widget->settings['crop_text'] === 2) {  // line limitting
                $before = '<span style="max-height:' . ((int) $this->widget->settings['crop_text_len'] * $cropTextSize);
                $before .= 'em" class="line_limit">';
                $after  = '</span>';
                $text   .= '...';
            }

            $text_field = $before . $text . $after;

            /**
             *  Filter main text for front-end display
             *
             * @param string Post text
             *
             * @return string
             */
            $text_field = apply_filters('wplp_front_text_field', $text_field);

            return $text_field;
        }

        if ('ImageFull' === $field) {
            if ((int) $this->widget->settings['thumb_img'] === 0) {
                //echo "feature image";
                if (is_multisite()) {
                    switch_to_blog($post->curent_blog_id);
                    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                    if (!empty($post_thumbnail_id)) {
                        $imgsrc = wp_get_attachment_image_src($post_thumbnail_id, 'full');
                    } else {
                        if (class_exists('acf')) {
                            $postID = get_field('image', $post->ID, false);  //image est ACF field
                            $imgsrc = wp_get_attachment_image_src(intval($postID), 'full');
                        }
                    }
                    restore_current_blog();
                } else {
                    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                    if (!empty($post_thumbnail_id)) {
                        $imgsrc = wp_get_attachment_image_src($post_thumbnail_id, 'full');
                    } else {
                        if (class_exists('acf')) {
                            $postID = get_field('image', $post->ID, false);  //image est ACF field
                            $imgsrc = wp_get_attachment_image_src(intval($postID), 'full');
                        }
                    }
                }
            } else {
                $img = preg_match_all(
                    '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',
                    get_the_content(get_the_ID()),
                    $matches
                );
                if ($img) {
                    $img = $matches[1][0];
                    global $wpdb;
                    $attachment_id  = false;
                    $attachment_url = $img;
                    // Get the upload directory paths
                    $upload_dir_paths = wp_upload_dir();
                    if (false !== strpos($attachment_url, $upload_dir_paths['baseurl'])) {
                        $attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);
                        $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);

                        $attachment_id = $wpdb->get_var(
                            $wpdb->prepare(
                                'SELECT wposts.ID FROM ' . $wpdb->posts . ' wposts, ' . $wpdb->postmeta . ' wpostmeta ' .
                                ' WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = "_wp_attached_file"' .
                                ' AND wpostmeta.meta_value = %s AND wposts.post_type = "attachment"',
                                $attachment_url
                            )
                        );
                    }
                    if ($attachment_id) {
                        $imgsrc = wp_get_attachment_image_src($attachment_id, 'full');
                    } else {
                        $imgsrc[0] = $img;
                    }
                }
            }

            if (!isset($imgsrc[0])) {
                $imgsrc[0] = $this->widget->settings['default_img'];
            }

            $img    = '<img src="' . $imgsrc[0] . '"  alt="';
            $img    .= htmlentities($post->post_title, null, 'UTF-8') . '"  class="wplp_default" />';
            $before = '<span class="img_cropper ' . get_post_format() . '">';
            $after  = '</span>';

            $img_field = $before . $img . $after;

            /**
             *  Filter image displayed for front-end display
             *
             * @param string Image
             * @param array  Latest posts settings
             *
             * @return string
             */
            $img_field = apply_filters('wplp_front_image_field', $img_field, $this->widget->settings);

            return $img_field;
        }

        /**
         * First image field *
         */
        /**
         * Thumbnail field *
         */
        if ('Thumbnail' === $field) {
            $sizing         = null;
            $fetchImageSize = null;
            $style          = '';
            $srcset         = '';

            if (isset($this->widget->settings['crop_img']) && (int) $this->widget->settings['crop_img'] === 0) {
                /*
                 * cropping mode off
                 */
                $imageSize = '';
                if (isset($this->widget->settings['image_size']) && !empty($this->widget->settings['image_size'])) {
                    $imageSize = $this->widget->settings['image_size'];
                }
                $fetchImageSize = $this->fetchImageSize($imageSize);
            } elseif (isset($this->widget->settings['crop_img']) && (int) $this->widget->settings['crop_img'] === 1) {
                /**
                 * Cropping mode on
                 */
                $sizing = array(
                    $this->widget->settings['thumb_width'],
                    $this->widget->settings['thumb_height']
                );

                $style .= 'position: absolute;';
                $style .= 'top: 50%;';
                $style .= 'margin-top: ' . (0 - ($this->widget->settings['thumb_height'] / 2)) . 'px;';
            }

            if ('src_category_list' === $this->widget->settings['source_type']) {
                if (is_multisite()) {
                    $category_image = get_blog_option($post->curent_blog_id, 'wplp_category_image');
                } else {
                    $category_image = get_option('wplp_category_image');
                }
                $src = '';
                if (!empty($category_image)) {
                    foreach ($category_image as $item) {
                        if ($post->ID === $item->term_id) {
                            $src = $item->image;
                        }
                    }
                }
                // Get image size
                if (!empty($src)) {
                    if (is_multisite()) {
                        switch_to_blog((int) $post->curent_blog_id);
                        $attachment_id = $this->getAttachmentIdByUrl($src);
                        if (!empty($attachment_id)) {
                            if (isset($sizing) && !empty($sizing)) {
                                $srca = wp_get_attachment_image_src($attachment_id, $sizing);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($attachment_id, $sizing);
                                }
                            } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                $srca = wp_get_attachment_image_src($attachment_id, $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($attachment_id, $fetchImageSize);
                                }
                            }
                        }
                        restore_current_blog();
                    } else {
                        $attachment_id = $this->getAttachmentIdByUrl($src);
                        if (!empty($attachment_id)) {
                            if (isset($sizing) && !empty($sizing)) {
                                $srca = wp_get_attachment_image_src($attachment_id, $sizing);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($attachment_id, $sizing);
                                }
                            } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                $srca = wp_get_attachment_image_src($attachment_id, $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($attachment_id, $fetchImageSize);
                                }
                            }
                        }
                    }
                    // Check if the image is not ,using the default image
                    if (isset($srca[0])) {
                        $src = $srca[0];
                    } else {
                        $src = '';
                    }
                }
            } else {
                // Image posst
                /**
                 * Find image *
                 */
                if ((isset($this->widget->settings['thumb_img']) && (int) $this->widget->settings['thumb_img'] === 0)) { //
                    /**
                     * Use featured image of post *
                     */
                    $srca = false;
                    if ($this->widget->settings['theme'] === 'portfolio') {
                        if (is_multisite()) {
                            switch_to_blog($post->curent_blog_id);
                            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                            if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $fetchImageSize);
                            }
                            if (!empty($post_thumbnail_id)) {
                                $srca = wp_get_attachment_image_src($post_thumbnail_id, $fetchImageSize);
                            } else {
                                if (class_exists('acf')) {
                                    $postID = get_field('image', $post->ID, false);  //image est ACF field
                                    $srca   = wp_get_attachment_image_src(intval($postID), $fetchImageSize);
                                }
                            }
                            restore_current_blog();
                        } else {
                            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                            if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $fetchImageSize);
                            }
                            if (!empty($post_thumbnail_id)) {
                                $srca = wp_get_attachment_image_src($post_thumbnail_id, $fetchImageSize);
                            } else {
                                if (class_exists('acf')) {
                                    $postID = get_field('image', $post->ID, false);  //image est ACF field
                                    $srca   = wp_get_attachment_image_src(intval($postID), $fetchImageSize);
                                }
                            }
                        }
                    } else {
                        if (is_multisite()) {
                            switch_to_blog($post->curent_blog_id);
                            $post_thumbnail_id = get_post_thumbnail_id($post->ID);

                            if (!empty($post_thumbnail_id)) {
                                if (isset($sizing) && !empty($sizing)) {
                                    $srca = wp_get_attachment_image_src($post_thumbnail_id, $sizing);
                                    if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                        $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $sizing);
                                    }
                                } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                    $srca = wp_get_attachment_image_src($post_thumbnail_id, $fetchImageSize);
                                    if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                        $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $fetchImageSize);
                                    }
                                }
                            } else {
                                if (class_exists('acf')) {
                                    $postID = get_field('image', $post->ID, false);  //image est ACF field
                                    if (isset($sizing) && !empty($sizing)) {
                                        $srca = wp_get_attachment_image_src(intval($postID), $sizing);
                                        if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                            $srcset = wp_get_attachment_image_srcset(intval($postID), $sizing);
                                        }
                                    } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                        $srca = wp_get_attachment_image_src(intval($postID), $fetchImageSize);
                                        if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                            $srcset = wp_get_attachment_image_srcset(intval($postID), $fetchImageSize);
                                        }
                                    }
                                }
                            }
                            restore_current_blog();
                        } else {
                            $post_thumbnail_id = get_post_thumbnail_id($post->ID);

                            if (!empty($post_thumbnail_id)) {
                                if (isset($sizing) && !empty($sizing)) {
                                    $srca = wp_get_attachment_image_src($post_thumbnail_id, $sizing);
                                    if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                        $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $sizing);
                                    }
                                } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                    $srca = wp_get_attachment_image_src($post_thumbnail_id, $fetchImageSize);
                                    if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                        $srcset = wp_get_attachment_image_srcset($post_thumbnail_id, $fetchImageSize);
                                    }
                                }
                            } else {
                                if (class_exists('acf')) {
                                    $postID = get_field('image', $post->ID, false);  //image est ACF field
                                    if (isset($sizing) && !empty($sizing)) {
                                        $srca = wp_get_attachment_image_src(intval($postID), $sizing);
                                        if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                            $srcset = wp_get_attachment_image_srcset(intval($postID), $sizing);
                                        }
                                    } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                        $srca = wp_get_attachment_image_src(intval($postID), $fetchImageSize);
                                        if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                            $srcset = wp_get_attachment_image_srcset(intval($postID), $fetchImageSize);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $src = $srca[0];
                } elseif (isset($this->widget->settings['thumb_img']) && (int) $this->widget->settings['thumb_img'] === 1) {
                    /**
                     * Use first image of post *
                     */
                    $imageSize = '';
                    /**
                     * Get Image Size from setting
                     */
                    if (isset($this->widget->settings['image_size']) && !empty($this->widget->settings['image_size'])) {
                        $imageSize = $this->widget->settings['image_size'];
                    }
                    $fetchImageSize = $this->fetchImageSize($imageSize);
                    global $post;
                    $pos_img = strpos($post->post_content, '<img');
                    $pos_single_image = strpos($post->post_content, 'vc_single_image');
                    if ($pos_img && (int)$pos_img < (int)$pos_single_image) {
                        $content = $post->post_content;
                    } elseif ($pos_single_image) {
                        // get first image from single image module of bakery builder
                        if (preg_match_all('/\[vc_single_image(.*?)\]/', $post->post_content, $vc_single_images)) {
                            if (!empty($vc_single_images)) {
                                $content = do_shortcode($vc_single_images[0][0]);
                            }
                        }
                    } else {
                        $content = $post->post_content;
                    }

                    /**
                     * Get post content
                     */
                    if (preg_match('/< *img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches)) {
                        $imageTag = $matches[0];
                    }
                    $class = '';
                    $src   = '';
                    /**
                     * Get src Image
                     */
                    if (!empty($imageTag)) {
                        $xmlDoc = new DOMDocument();
                        libxml_use_internal_errors(true);
                        $xmlDoc->loadHTML($imageTag);
                        $tags = $xmlDoc->getElementsByTagName('img');

                        foreach ($tags as $order => $tag) {
                            $class = $tag->getAttribute('class');
                            $src   = $tag->getAttribute('src');
                        }
                        preg_match('/\d+/', $class, $matches);
                        $firstImageId = $matches;
                        if (!empty($firstImageId)) {
                            $firstImageId = implode(' ', $firstImageId);
                            if (is_multisite()) {
                                switch_to_blog($post->curent_blog_id);
                                $srca = wp_get_attachment_image_src(intval($firstImageId), $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(intval($firstImageId), $fetchImageSize);
                                }
                                $src = $srca[0];
                                restore_current_blog();
                            } else {
                                $srca = wp_get_attachment_image_src(intval($firstImageId), $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(intval($firstImageId), $fetchImageSize);
                                }
                                $src = $srca[0];
                            }
                        }
                    } else {
                        if (is_multisite()) {
                            switch_to_blog($post->curent_blog_id);
                            $attachments = get_children(
                                array(
                                    'post_parent'    => get_the_ID(),
                                    'post_type'      => 'attachment',
                                    'post_mime_type' => 'image',
                                    'orderby'        => 'menu_order'
                                )
                            );
                            if (is_array($attachments) && !empty($attachments)) {
                                $first_attachment = array_shift($attachments);
                                $srca             = wp_get_attachment_image_src($first_attachment->ID, $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($first_attachment->ID, $fetchImageSize);
                                }
                                $src = $srca[0];
                            }
                            restore_current_blog();
                        } else {
                            $attachments = get_children(
                                array(
                                    'post_parent'    => get_the_ID(),
                                    'post_type'      => 'attachment',
                                    'post_mime_type' => 'image',
                                    'orderby'        => 'menu_order'
                                )
                            );

                            if (is_array($attachments) && !empty($attachments)) {
                                $first_attachment = array_shift($attachments);
                                $srca             = wp_get_attachment_image_src($first_attachment->ID, $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset($first_attachment->ID, $fetchImageSize);
                                }
                                $src = $srca[0];
                            }
                        }
                    }
                } else {
                    /**
                     * Use default WP thumbnail *
                     */
                    if ($this->widget->settings['theme'] === 'portfolio') {
                        if (is_multisite()) {
                            switch_to_blog($post->curent_blog_id);
                            $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $fetchImageSize);
                            if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $fetchImageSize);
                            }
                            restore_current_blog();
                        } else {
                            $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $fetchImageSize);
                            if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $fetchImageSize);
                            }
                        }
                    } else {
                        if (is_multisite()) {
                            switch_to_blog($post->curent_blog_id);
                            if (isset($sizing) && !empty($sizing)) {
                                $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $sizing);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $sizing);
                                }
                            } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $fetchImageSize);
                                }
                            }
                            restore_current_blog();
                        } else {
                            if (isset($sizing) && !empty($sizing)) {
                                $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $sizing);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $sizing);
                                }
                            } elseif (isset($fetchImageSize) && !empty($fetchImageSize)) {
                                $srca = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $fetchImageSize);
                                if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                                    $srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), $fetchImageSize);
                                }
                            }
                        }
                    }
                    $src = $srca[0];
                }
            }

            /**
             *  Filter post thumbnail link for front-end display
             *
             * @param string  Thumbnail url
             * @param integer Post ID
             * @param string  Image size
             *
             * @return string
             */
            $src = apply_filters('wplp_change_thumbnail_link', $src, $post->ID, $fetchImageSize);


            $style_img = '';
            if ($this->widget->settings['theme'] === 'portfolio') {
                if (isset($this->widget->settings['crop_img']) && (int) $this->widget->settings['crop_img'] === 1) {
                    $style_img .= 'width:100% !important';
                } else {
                    $style_img .= ' position: absolute;
                                top: -9999px;
                                bottom: -9999px;
                                left: -9999px;
                                right: -9999px;
                                margin: auto;';
                }
            }

            /**
             * If no thumb or first image get default image *
             */
            if (isset($src) && $src) {
                $img = '<img src="' . $src . '" style="' . $style . $style_img;
                $img .= '" srcset="' . $srcset . '" alt="' . get_the_title() . '" class="wplp_thumb" />';
            } else {
                if (isset($this->widget->settings['default_img']) && $this->widget->settings['default_img']) {
                    $srcDefaultImage = $this->widget->settings['default_img'];
                    if (isset($this->widget->settings['default_img_id']) && !empty($this->widget->settings['default_img_id'])) {
                        if (isset($this->widget->settings['image_size']) && $this->widget->settings['image_size'] === 'automatic') {
                            $srcset = wp_get_attachment_image_srcset((int) ($this->widget->settings['default_img_id']), 'full');
                        }
                        $srca = wp_get_attachment_image_src((int) ($this->widget->settings['default_img_id']), $fetchImageSize);
                        if (isset($srca[0]) && $srca[0]) {
                            $srcDefaultImage = $srca[0];
                        }
                    }
                    $img = '<img src="' . $srcDefaultImage . '" style="' . $style . $style_img;
                    $img .= '" srcset="' . $srcset . '" alt="' . get_the_title() . '"  class="wplp_default" />';
                } else {
                    $img = '<span class="img_placeholder" style="' . $style . $style_img . '" class="wplp_placeholder"></span>';
                }
            }
            /**
             * Image cropping & margin *
             */
            $style = '';

            if (isset($this->widget->settings['crop_img']) && (int) $this->widget->settings['crop_img'] === 1) {
                $style .= 'width:' . $this->widget->settings['thumb_width'] . 'px;';
                $style .= 'height:' . $this->widget->settings['thumb_height'] . 'px;';
            } else {
                if ($this->widget->settings['theme'] === 'portfolio') {
                    if (isset($this->widget->settings['full_height']) && (int) $this->widget->settings['full_height'] === 1) {
                        $style .= 'height : auto;';
                    } else {
                        $style .= 'height:' . $this->widget->settings['thumb_height'] . 'px;';
                    }
                } elseif ($this->widget->settings['theme'] === 'default') {
                    $imageSize = '';
                    if (isset($this->widget->settings['image_size']) && !empty($this->widget->settings['image_size'])) {
                        $imageSize = $this->widget->settings['image_size'];
                    }
                    $fetchImageSize = $this->fetchImageSize($imageSize);


                    if (($fetchImageSize === 'medium') || ($fetchImageSize === 'large')) {
                        if (isset($this->widget->settings['full_height'])
                            && (int) $this->widget->settings['full_height'] === 1
                        ) {
                            $style .= 'height : auto;';
                        } else {
                            $style .= 'height:' . $this->widget->settings['thumb_height'] . 'px;';
                        }
                    }
                }
            }

            if (isset($this->widget->settings['margin_top']) && $this->widget->settings['margin_top'] > 0) {
                $style .= 'margin-top:' . $this->widget->settings['margin_top'] . 'px;';
            }
            if (isset($this->widget->settings['margin_right']) && $this->widget->settings['margin_right'] > 0) {
                $style .= 'margin-right:' . $this->widget->settings['margin_right'] . 'px;';
            }
            if (isset($this->widget->settings['margin_bottom']) && $this->widget->settings['margin_bottom'] > 0) {
                $style .= 'margin-bottom:' . $this->widget->settings['margin_bottom'] . 'px;';
            }
            if (isset($this->widget->settings['margin_left']) && $this->widget->settings['margin_left'] > 0) {
                $style .= 'margin-left:' . $this->widget->settings['margin_left'] . 'px;';
            }
            $style .= 'max-width:100%;';

            $before = '<span class="img_cropper" style="' . $style . '">';
            $after  = '</span>';
            /**
             * CHANGE SRC TO LAZY LOADING
             */
            if ($this->widget->settings['theme'] === 'default') {
                if (isset($this->widget->settings['layzyload_default'])
                    && (int) $this->widget->settings['layzyload_default'] === 1
                ) {
                    $placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
                    if (!preg_match("/src=['\"]data:image/is", $img)) {
                        // replace the src and add the data-src attribute
                        $imgtag_data = preg_replace(
                            '/<img(.*?)src=/is',
                            '<img$1src="' . esc_attr($placeholder_url) . '" data-wplp-src=',
                            $img
                        );

                        // replace the srcset
                        $imgtag_data = str_replace('srcset', 'data-wplp-srcset', $imgtag_data);
                        // add the lazy class to the img element
                        if (preg_match('/class=["\']/i', $imgtag_data)) {
                            $imgtag_data = preg_replace(
                                '/class=(["\'])(.*?)["\']/is',
                                'class=$1wplp-lazy wplp-lazy-hidden $2$1',
                                $imgtag_data
                            );
                        } else {
                            $imgtag_data = preg_replace(
                                '/<img/is',
                                '<img class="wplp-lazy wplp-lazy-hidden"',
                                $imgtag_data
                            );
                        }
                        $noscript    = '<noscript>' . $img . '</noscript>';
                        $imgtag_data .= $noscript;

                        // Replace new img tag to old img tag
                        $img = $imgtag_data;
                    }
                }
            }

            $thumbnail_field = $before . $img . $after;

            /**
             *  Filter image displayed for front-end display
             *
             * @param string Image
             * @param array  Latest posts settings
             *
             * @ignore Hook already documented
             *
             * @return string
             */
            $thumbnail_field = apply_filters('wplp_front_image_field', $thumbnail_field, $this->widget->settings);

            return $thumbnail_field;
        }

        /**
         * Read more field *
         */
        if ('Read more' === $field) {
            if (isset($this->widget->settings['read_more']) && $this->widget->settings['read_more']) {
                if ($this->widget->settings['theme'] === 'material-vertical') {
                    $readmore = '<span>' . esc_html($this->widget->settings['read_more']) . '</span>';
                    $readmore .= ' <svg class="icon" width="24" height="24" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <polygon class="shape" points="18.5,7.6 17.8,8.3 21.5,12 13,12 13,13 21.5,13 17.8,16.7 18.5,17.4 23.4,12.5"></polygon> </svg>';
                } else {
                    $readmore = esc_html($this->widget->settings['read_more']);
                }
            } else {
                if ($this->widget->settings['theme'] === 'material-vertical') {
                    $readmore = '<span>' . esc_html__('View', 'wp-latest-posts') . '</span>';
                    $readmore .= ' <svg class="icon" width="24" height="24" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <polygon class="shape" points="18.5,7.6 17.8,8.3 21.5,12 13,12 13,13 21.5,13 17.8,16.7 18.5,17.4 23.4,12.5"></polygon> </svg>';
                } else {
                    $readmore = esc_html__('Read more...', 'wp-latest-posts');
                }
            }

            /**
             *  Filter readmore link for front-end display.
             *
             * @param string Readmore link
             *
             * @return string
             */
            $readmore = apply_filters('wplp_front_readmore_field', $readmore);

            return $readmore;
        }

        if (is_plugin_active('advanced-custom-fields/acf.php')) {
            //advanced custom fields
            if ('Custom_Fields' === $field) {
                if (function_exists('acf_get_field_groups')) {
                    $post_groups = array();
                    $idPost      = get_the_ID();
                    $p_type = 'post';
                    if ('src_category' === $this->widget->settings['source_type']) {
                        $post_groups = acf_get_field_groups(array('post_type' => 'post'));
                    } elseif ('src_page' === $this->widget->settings['source_type']) {
                        $p_type = 'page';
                        $post_groups = acf_get_field_groups(array('post_type' => 'page'));
                    } elseif ('src_custom_post_type' === $this->widget->settings['source_type']) {
                        $p_type = $this->widget->settings['custom_post_type'];
                        $post_groups = acf_get_field_groups(array('post_type' => $this->widget->settings['custom_post_type']));
                    }

                    if (empty($this->widget->settings['advanced_fields_taxonomy_' . $p_type])) {
                        return '';
                    }

                    $outputHtml = array();
                    foreach ($post_groups as $post_group) {
                        $child_fields = get_posts(array(
                            'posts_per_page' => - 1,
                            'post_type'      => 'acf-field',
                            'post_parent'    => (int) $post_group['ID']
                        ));

                        foreach ($child_fields as $child_field) {
                            if (in_array($child_field->ID, $this->widget->settings['advanced_fields_taxonomy_' . $p_type]) || in_array('all_fields', $this->widget->settings['advanced_fields_taxonomy_' . $p_type])) {
                                $outputHtml[] = $this->displayCustomField($this->widget->settings, $idPost, $child_field);
                            }
                        }
                    }

                    return implode('<br/>', $outputHtml);
                } else {
                    return '';
                }
            }
        }

        if ('Category' === $field) {
            if ('src_custom_post_type' === $this->widget->settings['source_type']) {
                //$cats= get_the_taxonomies($this->widget->settings['custom_post_taxonomy']);
                if (is_multisite()) {
                    switch_to_blog($post->curent_blog_id);
                    $argstax = array();
                    $cats    = wp_get_post_terms(
                        get_the_ID(),
                        (isset($this->widget->settings['custom_post_taxonomy']) ?
                            $this->widget->settings['custom_post_taxonomy'] : ''),
                        $argstax
                    );
                    if (isset($this->widget->settings['custom_post_term'])
                        && $this->widget->settings['custom_post_term'] !== ''
                    ) {
                        $cats = array(
                            get_term_by(
                                'id',
                                $this->widget->settings['custom_post_term'],
                                $this->widget->settings['custom_post_taxonomy']
                            )
                        );
                    }
                    restore_current_blog();
                } else {
                    $argstax = array();

                    $cats = wp_get_post_terms(
                        get_the_ID(),
                        (isset($this->widget->settings['custom_post_taxonomy']) ?
                            $this->widget->settings['custom_post_taxonomy'] : ''),
                        $argstax
                    );
                    if (isset($this->widget->settings['custom_post_term'])
                        && $this->widget->settings['custom_post_term'] !== ''
                    ) {
                        $cats = array(
                            get_term_by(
                                'id',
                                $this->widget->settings['custom_post_term'],
                                $this->widget->settings['custom_post_taxonomy']
                            )
                        );
                    }
                }

                $listcat    = '';
                $count_cats = count($cats);
                for ($i = 0; $i < $count_cats; $i ++) {
                    if ($i > 0) {
                        if ($this->widget->settings['theme'] === 'material-vertical') {
                            $listcat .= ', ';
                        } else {
                            $listcat .= ' / ';
                        }
                    }
                    $listcat .= $cats[$i]->name;
                }
            } else {
                if (is_multisite()) {
                    switch_to_blog($post->curent_blog_id);
                    $cats = get_the_category();
                    restore_current_blog();
                } else {
                    $cats = get_the_category();
                }

                $listcat    = '';
                $count_cats = count($cats);
                for ($i = 0; $i < $count_cats; $i ++) {
                    if ($i > 0) {
                        if ($this->widget->settings['theme'] === 'material-vertical') {
                            $listcat .= ', ';
                        } else {
                            $listcat .= ' / ';
                        }
                    }
                    $listcat .= $cats[$i]->cat_name;
                }
            }

            if ('src_category_list' === $this->widget->settings['source_type']) {
                $listcat = '';
            }

            /**
             *  Filter category name for front-end display.
             *
             * @param string Category name
             *
             * @return string
             */
            $listcat = apply_filters('wplp_front_category_field', $listcat);

            return $listcat;
        }

        /**
         * Author field *
         */
        if ('Author' === $field) {
            if (is_multisite()) {
                switch_to_blog($post->curent_blog_id);
                $author = get_the_author();
                restore_current_blog();
            } else {
                if ('src_category_list' === $this->widget->settings['source_type']) {
                    $author = '';
                } else {
                    $author = get_the_author();
                }
            }

            /**
             *  Filter author name for front-end display.
             *
             * @param string Author field
             *
             * @return string
             */
            $author = apply_filters('wplp_front_author_field', $author);

            return $author;
        }

        /**
         * Date field *
         */
        if ('Date' === $field) {
            if (isset($this->widget->settings['date_fmt']) && $this->widget->settings['date_fmt']) {
                $date = get_post_time($this->widget->settings['date_fmt'], false, $post, true);
            } else {
                if ('src_category_list' === $this->widget->settings['source_type']) {
                    $date = '';
                } else {
                    $date = get_post_time(get_option('date_format'), false, $post, true);
                }
            }

            /**
             *  Filter date for front-end display
             *
             * @param string Date
             *
             * @return string
             */
            $date = apply_filters('wplp_front_date_field', $date);

            return $date;
        }

        return "\n<!-- WPLP Unknown field: " . strip_tags($field) . " -->\n";
    }

    /**
     * Default template for standard boxes
     *
     * @param object  $settings    Settings of widget
     * @param integer $idPost      Id of post
     * @param string  $child_field Field name
     *
     * @return mixed
     */
    public function displayCustomField($settings, $idPost, $child_field)
    {
        $result  = '';
        $fields  = get_field_object($child_field->post_excerpt, $idPost);
        $acf_val = get_field($fields['key'], $idPost, true);
        $before  = '<span class="acf-' . $fields['type'] . '">';
        $after   = '</span>';
        if ($settings['display_custom_field_title'] === 1) {
            $title = '<span class="act-title-' . $fields['type'] . '" >' . $fields['label'] . ': </span>';
        } else {
            $title = '';
        }

        if (!empty($acf_val)) {
            switch ($fields['type']) {
                case 'image':
                    if (is_numeric($fields['value'])) {
                        $urls = wp_get_attachment_image_src($fields['value'], $fields['preview_size']);
                        $url  = $urls[0];
                    } else {
                        $url = $fields['value'];
                    }

                    $result = '<img src="' . esc_url($url) . '"  alt="' . htmlentities($fields['name'], null, 'UTF-8');
                    $result .= '"  class="custom-fields-image" />';
                    break;
                case 'date_picker':
                    if (isset($this->widget->settings['date_fmt']) && $this->widget->settings['date_fmt']) {
                        $display_format = $this->widget->settings['date_fmt'];
                    } else {
                        $display_format = get_option('date_format');
                    }

                    $result = date($display_format, strtotime($fields['value']));
                    break;
                case 'file':
                    if (is_numeric($fields['value'])) {
                        $url           = wp_get_attachment_url($fields['value']);
                        $filetype      = get_post_mime_type($fields['value']);
                        $meta          = get_post_meta($fields['value'], '_wp_attachment_metadata', true);
                        $attached_file = get_attached_file($fields['value']);
                        if (isset($meta['filesize'])) {
                            $size = $meta['filesize'];
                        } elseif (file_exists($attached_file)) {
                            $size = filesize($attached_file);
                        } else {
                            $size = '';
                        }
                    } else {
                        $url      = $fields['value'];
                        $filetype = 'default';
                        $size     = '';
                    }

                    $icon    = wp_mime_type_icon($filetype);
                    $explode = explode('/', $url);
                    $name    = end($explode);
                    $result  = ' <ul class="hl file">
                                 <li>
                                         <img class="acf-file-icon" src="' . esc_url($icon) . '" alt=""/>
                                         
                                 </li>
                                 <li>
                                    <a class="acf-file-name" href="' . esc_url($url) .
                        '" target="_blank">' . esc_html($name) . '</a>
                                    <span>Size:</span>
                                    <span class="acf-file-size">' . esc_html($size) . '</span>
                                 </li>
                         </ul>   ';

                    break;
                default:
                    $result = $acf_val;
                    break;
            }

            $result = $title . $before . $result . $after;
        }

        /**
         *  Filter custom field for front-end display.
         *
         * @param string Custom field
         *
         * @internal
         *
         * @return string
         */
        $result = apply_filters('wplp_front_customfield', $result);

        return $result;
    }

    /**
     * Get taxonomy from ACF
     *
     * @param integer $id        If of post
     * @param array   $taxonomys Taxonomy of term
     *
     * @return array
     */
    public function getACF($id, $taxonomys)
    {
        $result = array();
        if ('all_fields' === $taxonomys[0]) {
            /**
             *  Filter custom field for front-end display.
             *
             * @param string  Default value
             * @param integer ID of field
             *
             * @internal
             *
             * @return array
             */
            $custom_field_postmeta = apply_filters('wplp_get_fields', array(), $id);
            foreach ($custom_field_postmeta as $v) {
                $values = get_post_meta($id, $v['key']);
                foreach ($values as $value) {
                    $result[] = $value['key'];
                }
            }
        } else {
            $result = $taxonomys;
        }

        return $result;
    }

    /**
     * Mix boxes
     *
     * @param string $box_name Name of boxes
     *
     * @return void
     */
    private function boxMisc($box_name)
    {
        global $post;
        $target = '';
        $style  = '';
        $class  = '';
        if (self::CSS_DEBUG) {
            $style = 'style="border:1px solid #999"';
        }
        if (isset($this->widget->settings['open_link']) && $this->widget->settings['open_link']) {
            $target = 'target="_blank"';
        }
        $image_width = 'auto';
        $text_with   = 'auto';

        if (isset($this->widget->settings['image_position_width'])) {
            if ((int) $this->widget->settings['image_position_width'] < 100) {
                $image_width = $this->widget->settings['image_position_width'] . '%';
                $number      = 100 - (int) $this->widget->settings['image_position_width'];
                $text_with   = (string) $number . '%';
            }
        }

        $before = '';

        if ('left' === $box_name || 'right' === $box_name) {
            $class = 'wpcu-custom-position';
        }

        if ($this->widget->settings['dfThumbnailPosition'] === 'right') {
            if ((int) $this->widget->settings['image_position_width'] < 100) {
                if ('left' === $box_name) {
                    $style = 'style="width: ' . $text_with . '"';
                }
                if ('right' === $box_name) {
                    $style = 'style="width: ' . $image_width . '"';
                }
            }
        }

        if ($this->widget->settings['dfThumbnailPosition'] === 'left') {
            if (!isset($this->widget->settings['image_position_width'])
                || (int) $this->widget->settings['image_position_width'] < 100
            ) {
                if ('left' === $box_name) {
                    $style = 'style="width: ' . $image_width . '"';
                }
                if ('right' === $box_name) {
                    $style = 'style="width: ' . $text_with . '"';
                }
            }
        }


        $before .= '<div ';
        $before .= 'id="wplp_box_' . $box_name . '_' . $this->widget->ID . '_' . $post->ID;
        $before .= '" class="wpcu-front-box ' . $box_name . ' ' . $class . '" ' . $style . '>';
        if (is_multisite()) {
            if ('src_category_list' === $this->widget->settings['source_type']) {
                switch_to_blog((int) $post->curent_blog_id);
                $before .= '<a href="' . get_term_link($post->ID) . '" ' . $target . '>';
                restore_current_blog();
            } else {
                $before .= '<a href="' . get_blog_permalink($post->curent_blog_id, $post->ID) . '" ' . $target . '>';
            }
        } else {
            if ('src_category_list' === $this->widget->settings['source_type']) {
                $links    = get_term_link($post->ID);
                $language = $this->widget->settings['content_language'];
                /**
                 *  Filter term link by wpml
                 *
                 * @param integer ID of posts
                 * @param string  Language to translate
                 * @param string  Term link
                 *
                 * @internal
                 *
                 * @return array
                 */
                $links  = apply_filters('wplp_get_term_link_by_language', $post->ID, $language, $links);
                $before .= '<a href="' . $links . '" ' . $target . '>';
            } else {
                $before .= '<a href="' . get_permalink() . '" ' . $target . '>';
            }
        }

        $after = '';
        $after .= '</a>';
        $after .= '</div>';

        $this->boxContent($before, $after, $box_name);
    }

    /**
     * Color separation by rgb
     *
     * @param array   $color   List color
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
     * Return size of image
     *
     * @param string $imageSize Size of image to fetch
     *
     * @return string
     */
    private function fetchImageSize($imageSize)
    {
        $fetchImageSize = 'medium';
        switch ($imageSize) {
            case 'thumbnailSize':
                $fetchImageSize = 'thumbnail';
                break;

            case 'mediumSize':
                $fetchImageSize = 'medium';
                break;

            case 'largeSize':
                $fetchImageSize = 'large';
                break;

            case 'automatic':
                $fetchImageSize = 'full';
                break;
        }

        global $_wp_additional_image_sizes;
        if (isset($_wp_additional_image_sizes[$imageSize])) {
            $fetchImageSize = $imageSize;
        }

        return $fetchImageSize;
    }

    /**
     * Get attachment ID by image url
     *
     * @param string $image_src Url of image
     *
     * @return null|string
     */
    public function getAttachmentIdByUrl($image_src)
    {
        global $wpdb;

        $id = $wpdb->get_var($wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . ' WHERE guid = %s', $image_src));

        return (!empty($id)) ? $id : null;
    }

    /**
     * Password Form
     *
     * @param integer $postID ID of post
     *
     * @return string
     */
    public function wplpPasswordForm($postID)
    {
        $label = 'pwbox-' . (empty($postID) ? rand() : $postID);
        $o     = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">
                ' . __('To view this protected post, enter the password below:', 'wp-latest-posts') . '
        <label for="' . $label . '">' . __('Password:', 'wp-latest-posts') . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" />
        <input type="submit" name="Submit" value="' . esc_attr__('Submit', 'wp-latest-posts') . '" />
                    </form>';

        return $o;
    }
}
