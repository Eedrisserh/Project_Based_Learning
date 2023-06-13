<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WPLPLanguageContent
 */
class WPLPLanguageContent
{
    /**
     * WPLPLanguageContent constructor.
     */
    public function __construct()
    {
        add_filter('wplp_get_posts_by_language', array($this, 'getPostsByLanguage'), 10, 3);
        add_filter('wplp_get_category_by_language', array($this, 'getCategoryByLanguage'), 10, 2);
        add_filter('wplp_get_pages_by_language', array($this, 'getPagesByLanguage'), 10, 2);
        add_filter('wplp_get_tags_by_language', array($this, 'getTagsByLanguage'), 10, 2);
        add_filter('wplp_get_custom_taxonomy_by_language', array($this, 'getCustomTaxonomyByLanguage'), 10, 4);
        add_filter('wplp_category_list_by_language', array($this, 'getCategoryListByLanguage'), 10, 3);
        add_filter('wplp_get_term_link_by_language', array($this, 'getTermLinkByLanguage'), 10, 3);
    }

    /**
     * Get list category by language
     *
     * @param array  $args     An array of arguments
     * @param string $language Language to get translated
     * @param array  $cats     An array of category
     *
     * @return array|integer
     */
    public function getCategoryListByLanguage($args, $language, $cats)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                // Switch to language to get category
                $sitepress->switch_lang($language);
                $cats = get_terms($args);
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        if (function_exists('pll_languages_list')) {
            $cat_ids = array();
            $ppl_cats = array();
            if (empty($cats)) {
                // Get category by ajax selector
                $cats = get_terms($args);
            }
            if (!empty($language)) {
                $list_language = pll_languages_list(array('fields' =>'slug'));
                if (!in_array($language, $list_language)) {
                    // If content language not saved, Using default language
                    return $cats;
                }
                foreach ($cats as $cat) {
                    // Return the category translation by polylang
                    $ppl_cat_id = pll_get_term($cat->term_id, $language);
                    if (!$ppl_cat_id) {
                        continue;
                    }
                    $cat_ids[] = $ppl_cat_id;
                }
                if (!empty($cat_ids)) {
                    // Remove duplicate category id
                    $cat_ids = array_unique($cat_ids);
                    foreach ($cat_ids as $id) {
                        // Get category translated
                        $ppl_cats[] = get_term($id);
                    }
                    return $ppl_cats;
                }
            }
        }

        return $cats;
    }
    /**
     * Get link of term by language
     *
     * @param integer $postId   Id of post
     * @param string  $language Language to get translated
     * @param string  $links    Link of term
     *
     * @return array|string
     */
    public function getTermLinkByLanguage($postId, $language, $links)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                // Switch to language to get link category in wpml
                $sitepress->switch_lang($language);
                $links = get_term_link($postId);
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        return $links;
    }

    /**
     * Get posts by language via WPML
     * Return translated posts
     *
     * @param integer $posts     Id of posts
     * @param string  $post_type Type of post
     * @param string  $language  Language to get translated
     *
     * @return array|integer
     */
    public function getPostsByLanguage($posts, $post_type, $language)
    {
        $check = false;
        $return_original_if_missing = false;
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            $check = $this->checkWPMLConfig();
        } elseif (function_exists('pll_languages_list')) {
            $check = pll_languages_list();
            if (!pll_is_translated_post_type($post_type)) {
                //if Polylang should return the ID of the original language element if the translation is missing
                $return_original_if_missing  = true;
            }
        }
        if (!empty($check)) {
            $trid = array();
            $blog_id = '';
            if (function_exists('icl_object_id')) {
                if (!empty($posts)) {
                    foreach ($posts as $k => $post) {
                        if (empty($language)) {
                            continue;
                        }

                        $id = icl_object_id($post->ID, $post_type, $return_original_if_missing, $language);
                        if (is_multisite()) {
                            $blog_id = '_BLOG_ID_' . $post->curent_blog_id;
                        }
                        // Remove old post in language
                        unset($posts[$k]);
                        // Remove post in another language
                        if (empty($id)) {
                            continue;
                        }
                        $trid[] = $id . $blog_id;
                    }
                    // Check duplicate post
                    $trid = array_unique($trid);
                    foreach ($trid as $id) {
                        if (is_multisite()) {
                            $str = substr($id, strpos($id, '_BLOG_ID_'));
                            $blog_id = substr($str, strlen('_BLOG_ID_'));
                            $id = substr($id, 0, strpos($id, '_BLOG_ID_'));
                        }
                        // Get post in selected language
                        $post = get_post((int)$id);
                        if (!empty($blog_id)) {
                            $post->curent_blog_id = (int)$blog_id;
                        }
                        $posts[] = $post;
                    }
                }
            }
        }

        return $posts;
    }

    /**
     * Get category by selected language
     * Return translated term
     *
     * @param array  $cats     List of categories.
     * @param string $language Language to get translated posts
     *
     * @return array
     */
    public static function getCategoryByLanguage($cats, $language)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                $sitepress->switch_lang($language);
                $cats = get_categories();
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        if (function_exists('pll_languages_list')) {
            $cat_ids = array();
            $ppl_cats = array();
            if (empty($cats)) {
                // Get category by ajax selector
                $cats = get_categories();
            }
            if (!empty($language)) {
                $list_language = pll_languages_list(array('fields' =>'slug'));
                if (!in_array($language, $list_language)) {
                    // If content language not saved, Using default language
                    return $cats;
                }
                foreach ($cats as $cat) {
                    // Return the category translation by polylang
                    $ppl_cat_id = pll_get_term($cat->term_id, $language);
                    if (!$ppl_cat_id) {
                        continue;
                    }
                    $cat_ids[] = $ppl_cat_id;
                }
                if (!empty($cat_ids)) {
                    // Remove duplicate category id
                    $cat_ids = array_unique($cat_ids);
                    foreach ($cat_ids as $id) {
                        // Get category translated
                        $ppl_cats[] = get_term($id);
                    }
                    return $ppl_cats;
                }
            }
        }

        return $cats;
    }

    /**
     * Get pages by selected language
     * Return translated pages
     *
     * @param array  $pages    List of pages
     * @param string $language Language to get translated
     *
     * @return array|false
     */
    public static function getPagesByLanguage($pages, $language)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                $sitepress->switch_lang($language);
                $pages = get_pages();
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        if (function_exists('pll_get_post')) {
            $ppl_pages = array();
            $page_ids = array();
            if (empty($pages)) {
                // Get page language through ajax content language
                // If page empty when select by content language
                $pages = get_pages();
            }

            if (!empty($language)) {
                $list_language = pll_languages_list(array('fields' =>'slug'));
                if (!in_array($language, $list_language)) {
                    // If content language not saved, Using default language
                    return $pages;
                }
                foreach ($pages as $page) {
                    $ppl_page_id = pll_get_post($page->ID, $language);
                    if (!$ppl_page_id) {
                        continue;
                    }
                    $page_ids[] = $ppl_page_id;
                }
                // Remove duplicate page
                $page_ids = array_unique($page_ids);
                foreach ($page_ids as $id) {
                    $ppl_pages[] = get_post($id);
                }
                return $ppl_pages;
            }
        }
        return $pages;
    }

    /**
     * Get pages by selected language
     * Return translated term
     *
     * @param array  $tags     List of tags
     * @param string $language Language to get translated
     *
     * @return array
     */
    public static function getTagsByLanguage($tags, $language)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                $sitepress->switch_lang($language);
                $tags = get_tags();
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        if (function_exists('pll_get_term')) {
            $ppl_tags = array();
            $tag_ids = array();
            if (empty($tags)) {
                // If select content langugae by ajax tags is empty
                $tags = get_tags();
            }
            if (!empty($language)) {
                $list_language = pll_languages_list(array('fields' =>'slug'));
                if (!in_array($language, $list_language)) {
                    // If content language not saved, Using default language
                    return $tags;
                }
                foreach ($tags as $tag) {
                    $ppl_tag_id = pll_get_term($tag->term_id, $language);
                    if (!$ppl_tag_id) {
                        continue;
                    }
                    $tag_ids[] = $ppl_tag_id;
                }
                // Remove duplicate tag
                $tag_ids = array_unique($tag_ids);
                foreach ($tag_ids as $id) {
                    // Get tag by translated id
                    $ppl_tags[] = get_term($id);
                }
                return $ppl_tags;
            }
        }
        return $tags;
    }

    /**
     * Get taxonomy by selected language
     *
     * @param array  $terms    List terms
     * @param string $taxname  Tax name
     * @param string $postType Type of posts
     * @param string $language Language to get translated
     *
     * @return array|integer
     */
    public static function getCustomTaxonomyByLanguage($terms, $taxname, $postType, $language)
    {
        if (function_exists('icl_object_id') && !function_exists('pll_languages_list')) {
            global $sitepress;
            if (!empty($language)) {
                $sitepress->switch_lang($language);
                $terms = get_terms(
                    $taxname,
                    array(
                    'post_type' => array($postType),
                    'hide_empty' => false,
                    )
                );
                $sitepress->switch_lang(ICL_LANGUAGE_CODE);
            }
        }
        if (function_exists('pll_languages_list')) {
            $ppl_cpts = array();
            $cpt_ids = array();
            if (empty($terms)) {
                $terms = get_terms(
                    $taxname,
                    array(
                    'post_type' => array($postType),
                    'hide_empty' => false,
                    )
                );
            }
            if (!empty($language)) {
                $list_language = pll_languages_list(array('fields' =>'slug'));
                if (!in_array($language, $list_language)) {
                    // If content language not saved, Using default language
                    return $terms;
                }
                foreach ($terms as $term) {
                    $ppl_cpt_id = pll_get_term($term->term_id, $language);
                    if (!$ppl_cpt_id) {
                        continue;
                    }
                    $cpt_ids[] = $ppl_cpt_id;
                }
                // Remove duplicate tag
                $cpt_ids = array_unique($cpt_ids);
                foreach ($cpt_ids as $id) {
                    // Get tag by translated id
                    $ppl_cpts[] = get_term($id);
                }
            }
            return $ppl_cpts;
        }
        return $terms;
    }

    /**
     * AJAX change source type with language plugin
     *
     * @return void
     */
    public static function changeSourceTypeByLanguage()
    {
        // Check ajax security
        check_ajax_referer('wplp_nonce', 'security');
        $html = '';
        $language = '';
        $type = '';
        $blog_post = '';
        $blog_page = '';
        $blog_tags = '';
        $blog_catlist = '';
        if (isset($_POST['language'])) {
            $language = $_POST['language'];
        }
        if (isset($_POST['page'])) {
            $type = $_POST['page'];
        }
        if (isset($_POST['blog_post'])) {
            $blog_post = $_POST['blog_post'];
        }
        if (isset($_POST['blog_page'])) {
            $blog_page = $_POST['blog_page'];
        }
        if (isset($_POST['blog_tags'])) {
            $blog_tags= $_POST['blog_tags'];
        }

        if (isset($_POST['blog_catlist'])) {
            $blog_catlist= $_POST['blog_catlist'];
        }

        if ($type === 'src_category') {
            $html .= '<li><input id="cat_all" type="checkbox" name="wplp_source_category[]" class="ju-checkbox wplp_change_content"';
            $html .= 'value="_all" checked="checked" /><label for="cat_all" class="post_cb">All</li>';
            if (is_multisite()) {
                if ('all_blog' === $blog_post) {
                    $blogs = get_sites();
                    foreach ($blogs as $blog) {
                        switch_to_blog((int)$blog->blog_id);
                        $allcats = get_categories();
                        foreach ($allcats as $allcat) {
                            $allcat->blog = (int) $blog_post;
                            $cats[] = $allcat;
                        }
                        restore_current_blog();
                    }
                } else {
                    switch_to_blog((int)$blog_post);
                    $cats = get_categories();
                    foreach ($cats as $cat) {
                        $cat->blog = (int) $blog_post;
                    }
                    restore_current_blog();
                }
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
                $cats = apply_filters('wplp_get_category_by_language', $cats, $language);

                foreach ($cats as $k => $cat) {
                    $html .= '<li>';
                    $html .= '<input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]" value="';
                    $html .= $k . '_' . $cat->term_id . '_blog'. $cat->blog . '"  class="post_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label>';
                    $html .= '</li>';
                }
            } else {
                $cats = self::getCategoryByLanguage('', $language);
                foreach ($cats as $k => $cat) {
                    $html .= '<li>';
                    $html .= '<input id="ccb_' . $k . '" type="checkbox" name="wplp_source_category[]" value="';
                    $html .= $cat->term_id . '"  class="post_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="ccb_' . $k . '" class="post_cb">' . $cat->name . '</label>';
                    $html .= '</li>';
                }
            }
        } elseif ($type === 'src_page') {
            $html .= '<li><input id="page_all" type="checkbox" name="wplp_source_pages[]" class="ju-checkbox wplp_change_content"';
            $html .= 'value="_all" checked="checked" /><label for="page_all" class="page_cb">All Pages</li>';
            if (is_multisite()) {
                if ('all_blog' === $blog_page) {
                    $blogs = get_sites();
                    foreach ($blogs as $blog) {
                        switch_to_blog((int)$blog->blog_id);
                        $allcats = get_pages();
                        foreach ($allcats as $allcat) {
                            $pages[] = $allcat;
                        }
                        restore_current_blog();
                    }
                } else {
                    switch_to_blog((int)$blog_page);
                    $pages = get_pages();
                    restore_current_blog();
                }
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
                $pages = apply_filters('wplp_get_pages_by_language', $pages, $language);

                foreach ($pages as $k => $page) {
                    $html .= '<li>';
                    $html .= '<input id="pcb_' . $k . '" type="checkbox" name="wplp_source_pages[]" value="';
                    $html .= $k . '_' . $page->ID . '" class="page_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="pcb_' . $k . '" class="page_cb">' . $page->post_title . '</label>';
                    $html .= '</li>';
                }
            } else {
                $pages = self::getPagesByLanguage('', $language);
                foreach ($pages as $k => $page) {
                    $html .= '<li>';
                    $html .= '<input id="pcb_' . $k . '" type="checkbox" name="wplp_source_pages[]"';
                    $html .= 'value="' . $page->ID . '"  class="page_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="pcb_' . $k . '" class="page_cb">' . $page->post_title . '</label>';
                    $html .= '</li>';
                }
            }
        } elseif ($type === 'src_tags') {
            $html .= '<li><input id="tags_all" type="checkbox" name="wplp_source_tags[]" class="ju-checkbox wplp_change_content"';
            $html .= 'value="_all" checked="checked" /><label for="tags_all" class="tag_cb">All tags</li>';
            if (is_multisite()) {
                if ('all_blog' === $blog_tags) {
                    $blogs = get_sites();
                    foreach ($blogs as $blog) {
                        switch_to_blog((int)$blog->blog_id);
                        $allcats = get_tags();
                        if (!empty($allcats)) {
                            foreach ($allcats as $allcat) {
                                $tags[] = $allcat;
                            }
                        }
                        restore_current_blog();
                    }
                } else {
                    switch_to_blog((int)$blog_tags);
                    $tags = get_tags();
                    restore_current_blog();
                }
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
                $tags = apply_filters('wplp_get_tags_by_language', $tags, $language);
                foreach ($tags as $k => $tag) {
                    $html .= '<li>';
                    $html .= '<input id="tcb_' . $k . '" type="checkbox" name="wplp_source_tags[]" value="';
                    $html .= $k . '_' . $tag->term_id . '" class="tag_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="tcb_' . $k . '" class="tag_cb">' . $tag->name . '</label>';
                    $html .= '</li>';
                }
            } else {
                $tags = self::getTagsByLanguage('', $language);
                foreach ($tags as $k => $tag) {
                    $html .= '<li>';
                    $html .= '<input id="tcb_' . $k . '" type="checkbox" name="wplp_source_tags[]"';
                    $html .= 'value="' . $tag->term_id . '" class="tag_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="tcb_' . $k . '" class="tag_cb">' . $tag->name . '</label></li>';
                    $html .= '</li>';
                }
            }
        } elseif ($type === 'src_category_list') {
            $html .= '<li><input id="cat_list_all" type="checkbox" name="wplp_source_category_list[]" class="ju-checkbox wplp_change_content"';
            $html .= 'value="_all" checked="checked" /><label for="cat_list_all" class="cat_list_cb">All</li>';
            if (is_multisite()) {
                if ('all_blog' === $blog_post) {
                    $blogs = get_sites();
                    foreach ($blogs as $blog) {
                        switch_to_blog((int)$blog->blog_id);
                        $allcats = get_categories();
                        foreach ($allcats as $allcat) {
                            $cats[] = $allcat;
                        }
                        restore_current_blog();
                    }
                } else {
                    switch_to_blog((int)$blog_post);
                    $cats = get_categories();
                    restore_current_blog();
                }
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
                $cats = apply_filters('wplp_get_category_by_language', $cats, $language);

                foreach ($cats as $k => $cat) {
                    $html .= '<li>';
                    $html .= '<input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]" value="';
                    $html .= $k . '_' . $cat->term_id . '"  class="cat_list_cb ju-checkbox wplp_change_content" />';
                    $html .= '<label for="cl_' . $k . '" class="cat_list_cb">' . $cat->name . '</label>';
                    $html .= '</li>';
                }
            } else {
                $cats = self::getCategoryByLanguage('', $language);

                foreach ($cats as $k => $cat) {
                    $html .= '<li>';
                    $html .= '<input id="cl_' . $k . '" type="checkbox" name="wplp_source_category_list[]" value="';
                    $html .= $cat->term_id . '"  class="cat_list_cb wplp_change_content" />';
                    $html .= '<label for="cl_' . $k . '" class="cat_list_cb ju-checkbox">' . $cat->name . '</label>';
                    $html .= '</li>';
                }
            }
        }
        wp_send_json(array('output' => $html, 'type' => $type));
    }
    /**
     * Check WPML configuation to find language actived
     *
     * @return boolean
     */
    public function checkWPMLConfig()
    {
        global $wpdb;
        $query = 'SELECT COUNT(*) FROM '.$wpdb->prefix.'icl_languages WHERE active = 1';
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- The variables was escaped
        $count = $wpdb->get_var($query);
        if (!empty($count)) {
            return true;
        }
        return false;
    }
}
