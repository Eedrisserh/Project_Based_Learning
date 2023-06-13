<?php
global $settings;

$source_cat_list_checked = array();

if (!isset($settings['source_category_list'])
    || empty($settings['source_category_list'])
    || !$settings['source_category_list']
) {
    $settings['source_category_list'] = array('_all');
}

if (!isset($settings['mutilsite_cat'])) {
    $settings['mutilsite_cat'] = 'all_blog';
}

if (!isset($settings['mutilsite_cat_list'])) {
    $settings['mutilsite_cat_list'] = 'all_blog';
}

if (!isset($settings['mutilsite_page'])) {
    $settings['mutilsite_page'] = 'all_blog';
}

foreach ($settings['source_category_list'] as $cat_list) {
    $source_cat_list_checked[$cat_list] = ' checked="checked"';
};

if (isset($settings['cat_list_source_order'])) {
    $source_catlist_order_selected[$settings['cat_list_source_order']] = ' checked="checked"';
}

if (isset($settings['cat_list_source_asc'])) {
    $source_catlist_asc_selected[$settings['cat_list_source_asc']] = ' checked="checked"';
}

if (!function_exists('pll_languages_list') && function_exists('icl_object_id')) {
    $active_languages = apply_filters('wpml_active_languages', null, 'orderby=name&order=asc');
}
$poly_languages = array();
if (function_exists('pll_languages_list')) {
    foreach (pll_languages_list(array('fields' => 'slug')) as $pll_language) {
        $code = $pll_language;
        if (strpos($code, '_') !== false) {
            $code = substr($code, 0, strpos($code, '_'));
        }
        $poly_languages[$code] = $pll_language;
    }
}

$selected_content_language = '';
if (isset($settings['content_language'])) {
    $selected_content_language = $settings['content_language'];
}

$content_type = array(
    'src_category_list'    => __('Category list', 'wp-latest-posts'),
    'src_category'         => __('Posts', 'wp-latest-posts'),
    'src_page'             => __('Pages', 'wp-latest-posts'),
    'src_tags'             => __('Tags', 'wp-latest-posts'),
    'src_custom_post_type' => __('Custom posts', 'wp-latest-posts'),
);
?>
<script>
    (function ($) {
        $(document).ready(function () {
            function wplpGetCountPosts() {
                var wplp_id = $('[name="wplp_id"]').val();
                $.ajax({
                    url : ajaxurl,
                    dataType : 'json',
                    method : 'POST',
                    data : {
                        action : 'wplp_get_count_posts',
                        wplp_id : wplp_id,
                        settings: $('form').serialize(),
                        wplp_nonce: '<?php echo esc_html(wp_create_nonce('wplp_nonce')) ?>'
                    },
                    beforeSend: function() {
                        $('.wplp-source-content-selector span.content-selector').text('<?php esc_html_e('Loading...', 'wp-latest-posts') ?>');
                    },
                    success: function(res){
                        $('.wplp-source-content-selector span.content-selector').text(res.count + res.text);
                    }
                });
            }

            wplpGetCountPosts();

            var type = $('input[name=wplp_source_type]').val();
            $('.content-source-tab li[data-id="' + type + '"] a').addClass('active');

            $('.content-source-tab li.tab').click(function() {
                dataID = $(this).data('id');
                $('input[name=wplp_source_type]').val(dataID);
                wplpGetCountPosts();
            });

            $('.wplp_change_content').change(function() {
                wplpGetCountPosts();
            });

            $('form').attr('enctype', 'multipart/form-data');
        });
    })(jQuery);
</script>
<div class="wplp-source-content-selector" style="margin-bottom: 20px">
    <label class="source-content-selector-label wplp-wrapper-title title-color">
        <?php esc_html_e('Content selected :', 'wp-latest-posts'); ?>
        <span class="content-selector"></span>
    </label>

</div>
<?php if (function_exists('icl_object_id') || function_exists('pll_languages_list')) : ?>
    <div class="wplp-multi-language">
        <div class="content-source-language">
            <label for="content_language"
                   class="content-language-label wplp-wrapper-title"><?php esc_html_e('Content language', 'wp-latest-posts'); ?></label>
            <select id="content_language" class="content-language-select browser-default wplp-font-style"
                    name="wplp_content_language">
                <!-- CHECK WPML or Polylang is INSTALLED AND ACTIVED -->
                <?php if (!empty($active_languages)) : ?>
                    <?php foreach ($active_languages as $k => $languages) :
                        if (isset($settings['content_language']) && $settings['content_language'] === $k) :
                            ?>
                            <option value="<?php echo esc_html($k); ?>" selected>
                                <?php echo esc_html($languages['translated_name']) ?></option>
                        <?php else : ?>
                            <option value="<?php echo esc_html($k); ?>">
                                <?php echo esc_html($languages['translated_name']) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php elseif (!empty($poly_languages)) : ?>
                    <?php foreach ($poly_languages as $code => $languages) :
                        if (isset($settings['content_language']) && $settings['content_language'] === $code) :
                            ?>
                            <option value="<?php echo esc_html($code); ?>"
                                    selected><?php echo esc_html($languages); ?></option>
                        <?php else : ?>
                            <option value="<?php echo esc_html($code); ?>"><?php echo esc_html($languages); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <input type="hidden" value="<?php echo esc_html($selected_content_language); ?>" id="selected_content_language">
        <input type="hidden" value="" id="selected_source_type"/>
    </div>
<?php endif; ?>


<div id="wplp-settings-content-source">
    <div class="wplp-top-bar">
        <ul class="tabs ju-top-tabs content-source-tab">
            <li class="tab" data-id="src_category">
                <a href="#post-category" class="link-tab">
                    <?php esc_html_e('Posts', 'wp-latest-posts') ?>
                </a>
            </li>
            <li class="tab" data-id="src_category_list">
                <a href="#category-list" class="link-tab">
                    <?php esc_html_e('Category list', 'wp-latest-posts') ?>
                </a>
            </li>
            <li class="tab" data-id="src_page">
                <a href="#pages" class="link-tab">
                    <?php esc_html_e('Pages', 'wp-latest-posts') ?>
                </a>
            </li>
            <?php
            if (class_exists('WPLPAddonAdmin')) :
                ?>
                <li class="tab" data-id="src_tags">
                    <a href="#tags" class="link-tab">
                        <?php esc_html_e('Tags', 'wp-latest-posts') ?>
                    </a>
                </li>
                <li class="tab" data-id="src_custom_post_type">
                    <a href="#custom-posttype" class="link-tab">
                        <?php esc_html_e('Custom Posts', 'wp-latest-posts') ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <input type="hidden" id="wplp_source_type" name="wplp_source_type"
           value="<?php echo(isset($settings['source_type']) ? esc_html($settings['source_type']) : 'src_category') ?>">
    <div id="post-category" class="tab-content">
        <?php
        $source_cat_checked = array();
        if (!isset($settings['source_category'])
            || empty($settings['source_category'])
            || !$settings['source_category']
        ) {
            $settings['source_category'] = array('_all');
        }

        if (is_array($settings['source_category'])) {
            foreach ($settings['source_category'] as $cat_source) {
                $source_cat_checked[$cat_source] = ' checked="checked"';
            };
        }

        if (isset($settings['cat_post_source_order'])) {
            $source_order_selected[$settings['cat_post_source_order']] = ' checked="checked"';
        }

        if (isset($settings['cat_post_source_asc'])) {
            $source_asc_selected[$settings['cat_post_source_asc']] = ' checked="checked"';
        }
        ?>
        <div class="settings-wrapper">
            <?php if (is_multisite()) :
                if (!isset($settings['mutilsite_cat'])
                    || empty($settings['mutilsite_cat'])
                    || !$settings['mutilsite_cat']) {
                    $settings['mutilsite_cat'] = '';
                }

                $mutilsite_selected_post = '';
                if (isset($settings['mutilsite_cat'])) {
                    $mutilsite_selected_post = $settings['mutilsite_cat'];
                }

                $all_blog_cat_post = get_sites();
                ?>
                <div class="multisite-select-field settings-wrapper-field">
                    <label class="settings-wrapper-title"><?php esc_html_e('Multisite selection', 'wp-latest-posts') ?></label>
                    <select id="mutilsite_select_post" class="mutilsite_select wplp-font-style wplp-short-text width-30"
                            name="wplp_mutilsite_cat">
                        <option value="all_blog"><?php esc_html_e('All blog', 'wp-latest-posts') ?></option>
                        <?php
                        foreach ($all_blog_cat_post as $val) {
                            $detail = get_blog_details((int) $val->blog_id);
                            echo '<option ' . selected((int) $settings['mutilsite_cat'], (int) $val->blog_id) . ' value="' . esc_html($val->blog_id) . '"> ' . esc_html($detail->blogname) . ' </option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" value="<?php echo esc_html($mutilsite_selected_post) ?>"
                           id="selected_multisite_post_type"/>
                </div>
            <?php endif; ?>
            <div class="content-search-field">
                <input type="text" class="content-search-input search-input"
                       placeholder="<?php esc_html_e('Search content', 'wp-latest-posts') ?>">
                <i class="material-icons">search</i>
            </div>
            <div class="list-selector-field settings-wrapper-field postcat">
                <ul class="craft">
                    <li><input type="checkbox" name="wplp_source_category[]" id="cat_all" value="_all"
                               class="ju-checkbox wplp_change_content"
                            <?php echo(isset($source_cat_checked['_all']) ? esc_html($source_cat_checked['_all']) : '') ?> />
                        <label for="cat_all"
                               class="radio-label post_cb"><?php esc_html_e('All Category', 'wp-latest-posts') ?></label>
                    </li>
                    <?php
                    if (is_multisite()) {
                        if ('all_blog' === $settings['mutilsite_cat']) {
                            $blogs = get_sites();
                            foreach ($blogs as $blog) {
                                switch_to_blog((int) $blog->blog_id);
                                $allcats = get_categories(array('hide_empty' => false));
                                if (isset($settings['content_language'])) {
                                    $allcats = apply_filters(
                                        'wplp_get_category_by_language',
                                        $allcats,
                                        $settings['content_language']
                                    );
                                }
                                foreach ($allcats as $allcat) {
                                    $allcat->blog = (int) $blog->blog_id;
                                    $cats[] = $allcat;
                                }
                                restore_current_blog();
                            }
                        } else {
                            switch_to_blog((int) $settings['mutilsite_cat']);
                            $cats = get_categories(array('hide_empty' => false));
                            if (isset($settings['content_language'])) {
                                $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
                            }

                            foreach ($cats as $cat_obj) {
                                $cat_obj->blog = (int) $settings['mutilsite_cat'];
                            }
                            restore_current_blog();
                        }

                        foreach ($cats as $k => $cat_source) {
                            echo '<li><input id="ccb_' . esc_html($k) . '" type="checkbox" name="wplp_source_category[]" 
                            value="' . esc_html($k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog) . '" ' .
                                 (isset($source_cat_checked[$k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog]) ?
                                     esc_html($source_cat_checked[$k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog]) : '') .
                                 ' class="post_cb ju-checkbox wplp_change_content" />';
                            echo '<label for="ccb_' . esc_html($k) . '" class="radio-label post_cb">' . esc_html($cat_source->name) . '</label></li>';
                        }
                    } else {
                        $cats = get_categories(array('hide_empty' => false));
                        if (isset($settings['content_language'])) {
                            $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
                        }

                        foreach ($cats as $k => $cat_source) {
                            echo '<li><input id="ccb_' . esc_html($k) . '" type="checkbox" name="wplp_source_category[]" value="' .
                                 esc_html($cat_source->term_id) . '" ' .
                                 (isset($source_cat_checked[$cat_source->term_id]) ? esc_html($source_cat_checked[$cat_source->term_id]) : '') .
                                 ' class="ju-checkbox post_cb wplp_change_content" />';
                            echo '<label for="ccb_' . esc_html($k) . '" class="radio-label post_cb">' . esc_html($cat_source->name) . '</label></li>';
                        }
                    }
                    ?>
                </ul>
                <div class="clearfix"></div>
                <hr>
            </div>

            <div class="max-elts-selector-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Max number of news', 'wp-latest-posts') ?></label>
                <input type="text" class="wplp-short-text wplp-font-style center-text wplp-max-elts"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['max_elts']) ? $settings['max_elts'] : '30')) ?>" />
            </div>

            <?php
            if (class_exists('WPLPAddonAdmin') && is_plugin_active('advanced-custom-fields/acf.php')) {
                $post_groups = acf_get_field_groups(array('post_type' => 'post'));
                //Advanced custom fields
                if (!empty($post_groups)) {
                    do_action('wplp_display_advanced_custom_fields', $settings, 'post');
                } else {
                    echo '<div class="advanced-custom-field settings-wrapper-field"><input type="hidden" name="wplp_advanced_custom_fields" value=""/></div>';
                }
            }
            ?>
            <div class="order-by-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Order by', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li>
                        <input type="radio" name="wplp_cat_post_source_order" id="cat_post_source_order1" value="date"
                               class="ju-radiobox"
                            <?php echo(isset($source_order_selected['date']) ? esc_html($source_order_selected['date']) : '') ?> />
                        <label for="cat_post_source_order1"
                               class="radio-label"><?php echo class_exists('WPLPAddonAdmin') ? esc_html__('Creation date', 'wp-latest-posts') : esc_html__('By date', 'wp-latest-posts') ?></label>
                    </li>
                    <?php
                    if (class_exists('WPLPAddonAdmin')) {
                        do_action('wplp_addon_contentsource_display_post_order_by', $settings);
                    }
                    ?>
                    <li><input type="radio" name="wplp_cat_post_source_order" id="cat_post_source_order2" value="title"
                               class="ju-radiobox"
                            <?php echo(isset($source_order_selected['title']) ? esc_html($source_order_selected['title']) : '') ?> />
                        <label for="cat_post_source_order2"
                               class="radio-label"><?php esc_html_e('By title', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_post_source_order" id="cat_post_source_order3" value="random"
                               class="ju-radiobox"
                            <?php echo(isset($source_order_selected['random']) ? esc_html($source_order_selected['random']) : '') ?> />
                        <label for="cat_post_source_order3"
                               class="radio-label"><?php esc_html_e('By random', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_post_source_order" id="cat_post_source_order4" value="view"
                               class="ju-radiobox"
                            <?php echo(isset($source_order_selected['view']) ? esc_html($source_order_selected['view']) : '') ?> />
                        <label for="cat_post_source_order4"
                               class="radio-label"><?php esc_html_e('Most popular', 'wp-latest-posts') ?></label></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <hr>
            <div class="sort-order-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Order', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li><input type="radio" name="wplp_cat_post_source_asc" id="cat_post_source_asc1" value="asc"
                               class="ju-radiobox"
                            <?php echo(isset($source_asc_selected['asc']) ? esc_html($source_asc_selected['asc']) : '') ?> />
                        <label for="cat_post_source_asc1"
                               class="radio-label"><?php esc_html_e('Ascending', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_post_source_asc" id="cat_post_source_asc2" value="desc"
                               class="ju-radiobox"
                            <?php echo(isset($source_asc_selected['desc']) ? esc_html($source_asc_selected['desc']) : '') ?> />
                        <label for="cat_post_source_asc2"
                               class="radio-label"><?php esc_html_e('Descending', 'wp-latest-posts') ?></label></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <hr>
            <?php
            if (class_exists('WPLPAddonAdmin')) {
                do_action('wplp_addon_contentsource_display_article_date', $settings);
                do_action('wplp_addon_contentsource_display_content_inclusion', $settings);
            }
            ?>
        </div>
    </div>
    <div id="category-list" class="tab-content">
        <div class="settings-wrapper">
            <?php if (is_multisite()) :
                if (!isset($settings['mutilsite_cat_list'])
                    || empty($settings['mutilsite_cat_list'])
                    || !$settings['mutilsite_cat_list']) {
                    $settings['mutilsite_cat_list'] = '';
                }

                $mutilsite_selected_cat_list = '';
                if (isset($settings['mutilsite_cat_list'])) {
                    $mutilsite_selected_cat_list = $settings['mutilsite_cat_list'];
                }

                $all_blog_cat_list = get_sites();
                ?>
                <div class="multisite-select-field settings-wrapper-field">
                    <label class="settings-wrapper-title"><?php esc_html_e('Multisite selection', 'wp-latest-posts') ?></label>
                    <select id="mutilsite_cat_list_select"
                            class="mutilsite_select wplp-font-style wplp-short-text width-30"
                            name="wplp_mutilsite_cat_list">
                        <option value="all_blog"><?php esc_html_e('All blog', 'wp-latest-posts') ?></option>
                        <?php
                        foreach ($all_blog_cat_list as $val) {
                            $detail = get_blog_details((int) $val->blog_id);
                            echo '<option ' . selected((int) $settings['mutilsite_cat_list'], (int) $val->blog_id) . ' value="' . esc_html($val->blog_id) . '"> ' . esc_html($detail->blogname) . ' </option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" value="<?php echo esc_html($mutilsite_selected_cat_list) ?>"
                           id="selected_multisite_cat_list_post_type"/>
                </div>
            <?php endif; ?>
            <div class="content-search-field">
                <input type="text" class="content-search-input search-input"
                       placeholder="<?php esc_html_e('Search content', 'wp-latest-posts') ?>">
                <i class="material-icons">search</i>
            </div>
            <div class="list-selector-field settings-wrapper-field catlistcat">
                <ul class="craft">
                    <li><input type="checkbox" name="wplp_source_category_list[]" id="cat_list_all" value="_all"
                               class="ju-checkbox wplp_change_content"
                            <?php echo(isset($source_cat_list_checked['_all']) ? esc_html($source_cat_list_checked['_all']) : ''); ?> />
                        <label for="cat_list_all"
                               class="radio-label"><?php esc_html_e('All Category', 'wp-latest-posts') ?></label></li>
                    <?php
                    if (is_multisite()) {
                        if ('all_blog' === $settings['mutilsite_cat_list']) {
                            $blogs = get_sites();
                            $all_cats = array();
                            foreach ($blogs as $blog) {
                                switch_to_blog((int) $blog->blog_id);
                                $allcats = get_categories(array('hide_empty' => false));
                                if (isset($settings['content_language'])) {
                                    $allcats = apply_filters('wplp_get_category_by_language', $allcats, $settings['content_language']);
                                }
                                foreach ($allcats as $allcat) {
                                    $allcat->blog = (int) $blog->blog_id;
                                    $all_cats[]       = $allcat;
                                }
                                restore_current_blog();
                            }
                        } else {
                            switch_to_blog((int) $settings['mutilsite_cat_list']);
                            $all_cats = get_categories(array('hide_empty' => false));
                            if (isset($settings['content_language'])) {
                                $all_cats = apply_filters('wplp_get_category_by_language', $all_cats, $settings['content_language']);
                            }
                            foreach ($all_cats as $cat_source) {
                                $cat_source->blog = (int) $settings['mutilsite_cat_list'];
                            }
                            restore_current_blog();
                        }

                        foreach ($all_cats as $k => $cat_source) {
                            echo '<li><input id="cl_' . esc_html($k) . '" type="checkbox" name="wplp_source_category_list[]" value="' .
                                 esc_html($k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog) . '" ' .
                                 (isset($source_cat_list_checked[$k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog]) ?
                                     esc_html($source_cat_list_checked[$k . '_' . $cat_source->term_id . '_blog' . $cat_source->blog]) : '') .
                                 ' class="cat_list_cb ju-checkbox wplp_change_content" />';
                            echo '<label for="cl_' . esc_html($k) . '" class="cat_list_cb radio-label">' . esc_html($cat_source->name) . '</label></li>';
                        }
                    } else {
                        $cats = get_categories(array('hide_empty' => false));
                        if (isset($settings['content_language'])) {
                            $cats = apply_filters('wplp_get_category_by_language', $cats, $settings['content_language']);
                        }
                        foreach ($cats as $k => $cat_source) {
                            echo '<li><input id="cl_' . esc_html($k) . '" type="checkbox" name="wplp_source_category_list[]" value="' .
                                 esc_html($cat_source->term_id) . '" ' .
                                 (isset($source_cat_list_checked[$cat_source->term_id]) ? esc_html($source_cat_list_checked[$cat_source->term_id]) : '') .
                                 ' class="cat_list_cb ju-checkbox wplp_change_content" />';
                            echo '<label for="cl_' . esc_html($k) . '" class="cat_list_cb radio-label">' . esc_html($cat_source->name) . '</label></li>';
                        }
                    }
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <hr>

            <div class="max-elts-selector-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Max number of news', 'wp-latest-posts') ?></label>
                <input type="text" class="wplp-short-text wplp-font-style center-text wplp-max-elts"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['max_elts']) ? $settings['max_elts'] : '30')) ?>" />
            </div>

            <div class="order-by-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Order by', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li><input type="radio" name="wplp_cat_list_source_order" id="cat_list_source_order1" value="id"
                               class="ju-radiobox"
                            <?php echo(isset($source_catlist_order_selected['id']) ? esc_html($source_catlist_order_selected['id']) : '') ?> />
                        <label for="cat_list_source_order1"
                               class="radio-label"><?php esc_html_e('By id', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_list_source_order" id="cat_list_source_order2" value="name"
                               class="ju-radiobox"
                            <?php echo(isset($source_catlist_order_selected['name']) ? esc_html($source_catlist_order_selected['name']) : '') ?> />
                        <label for="cat_list_source_order2"
                               class="radio-label"><?php esc_html_e('By name', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_list_source_order" id="cat_list_source_order3"
                               value="description" class="ju-radiobox"
                            <?php echo(isset($source_catlist_order_selected['description']) ? esc_html($source_catlist_order_selected['description']) : '') ?> />
                        <label for="cat_list_source_order3"
                               class="radio-label"><?php esc_html_e('By description', 'wp-latest-posts') ?></label></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <hr>
            <div class="sort-order-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Category ordering', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li><input type="radio" name="wplp_cat_list_source_asc" id="cat_list_source_asc1" value="asc"
                               class="ju-radiobox"
                            <?php echo(isset($source_catlist_asc_selected['asc']) ? esc_html($source_catlist_asc_selected['asc']) : '') ?> />
                        <label for="cat_list_source_asc1"
                               class="radio-label"><?php esc_html_e('Ascending', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_cat_list_source_asc" id="cat_list_source_asc2" value="desc"
                               class="ju-radiobox"
                            <?php echo(isset($source_catlist_asc_selected['desc']) ? esc_html($source_catlist_asc_selected['desc']) : '') ?> />
                        <label for="cat_list_source_asc2"
                               class="radio-label"><?php esc_html_e('Descending', 'wp-latest-posts') ?></label></li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div id="pages" class="tab-content">
        <div class="settings-wrapper">
            <?php
            if (isset($settings['pg_source_order'])) {
                $source_page_order_selected[$settings['pg_source_order']] = ' checked="checked"';
            }

            if (isset($settings['pg_source_asc'])) {
                $source_page_asc_selected[$settings['pg_source_asc']] = ' checked="checked"';
            }
            ?>
            <?php if (is_multisite()) :
                if (!isset($settings['mutilsite_page'])
                    || empty($settings['mutilsite_page'])
                    || !$settings['mutilsite_page']
                ) {
                    $settings['mutilsite_page'] = '';
                }

                $mutilsite_selected_page = '';
                if (isset($settings['mutilsite_page'])) {
                    $mutilsite_selected_page = $settings['mutilsite_page'];
                }

                $all_blog_pages = get_sites();
                ?>
                <div class="multisite-select-field settings-wrapper-field">
                    <label class="settings-wrapper-title"><?php esc_html_e('Multisite selection', 'wp-latest-posts') ?></label>
                    <select id="mutilsite_select_page" class="mutilsite_select wplp-font-style wplp-short-text width-30"
                            name="wplp_mutilsite_page">
                        <option value="all_blog"><?php esc_html_e('All blog', 'wp-latest-posts') ?></option>
                        <?php
                        foreach ($all_blog_pages as $val) {
                            $detail = get_blog_details((int) $val->blog_id);
                            echo '<option ' . selected((int) $settings['mutilsite_page'], (int) $val->blog_id) . ' value="' . esc_html($val->blog_id) . '"> ' . esc_html($detail->blogname) . ' </option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" value="<?php echo esc_html($mutilsite_selected_page) ?>"
                           id="selected_multisite_page_type"/>
                </div>
            <?php endif; ?>
            <?php if (class_exists('WPLPAddonAdmin')) : ?>
                <div class="content-search-field">
                    <input type="text" class="content-search-input search-input"
                           placeholder="<?php esc_html_e('Search content', 'wp-latest-posts') ?>">
                    <i class="material-icons">search</i>
                </div>
            <?php endif; ?>
            <div class="list-selector-field settings-wrapper-field pagecat">
                <ul class="craft">
                    <?php
                    if (!class_exists('WPLPAddonAdmin')) {
                        ?>
                        <li><input id="pages_all" type="checkbox" name="wplp_source_pages[]" class="ju-checkbox wplp_change_content"
                                   value="_all" checked="checked" disabled="disabled"/>
                            <label for="pages_all" class="radio-label">All pages</label></li>
                        <?php
                    } else {
                        do_action('wplp_addon_contentsource_display_content_pages', $settings);
                    }
                    ?>

                </ul>
                <div class="clearfix"></div>
                <hr>
            </div>

            <div class="max-elts-selector-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Max number of news', 'wp-latest-posts') ?></label>
                <input type="text" class="wplp-short-text wplp-font-style center-text wplp-max-elts"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['max_elts']) ? $settings['max_elts'] : '30')) ?>" />
            </div>

            <?php
            if (class_exists('WPLPAddonAdmin') && is_plugin_active('advanced-custom-fields/acf.php')) {
                $page_groups = acf_get_field_groups(array('post_type' => 'page'));
                //Advanced custom fields
                if (!empty($page_groups)) {
                    do_action('wplp_display_advanced_custom_fields', $settings, 'page');
                } else {
                    echo '<div class="advanced-custom-field settings-wrapper-field"><input type="hidden" name="wplp_advanced_custom_fields_page" value=""/></div>';
                }
            }
            ?>
            <div class="order-by-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Order by', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li><input type="radio" name="wplp_pg_source_order" id="pg_source_order1" value="order"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_order_selected['order']) ? esc_html($source_page_order_selected['order']) : '') ?> />
                        <label for="pg_source_order1"
                               class="radio-label"><?php esc_html_e('By order', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_pg_source_order" id="pg_source_order2" value="title"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_order_selected['title']) ? esc_html($source_page_order_selected['title']) : '') ?> />
                        <label for="pg_source_order2"
                               class="radio-label"><?php esc_html_e('By title', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_pg_source_order" id="pg_source_order4" value="random"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_order_selected['random']) ? esc_html($source_page_order_selected['random']) : '') ?> />
                        <label for="pg_source_order4"
                               class="radio-label"><?php esc_html_e('By random', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_pg_source_order" id="pg_source_order3" value="date"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_order_selected['date']) ? esc_html($source_page_order_selected['date']) : '') ?> />
                        <label for="pg_source_order3"
                               class="radio-label"><?php echo class_exists('WPLPAddonAdmin') ? esc_html__('Creation date', 'wp-latest-posts') : esc_html__('By date', 'wp-latest-posts') ?></label></li>
                    <?php
                    if (class_exists('WPLPAddonAdmin')) {
                        do_action('wplp_addon_contentsource_display_page_order_by', $settings);
                    }
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <hr>
            <div class="sort-order-field settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Order', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li><input type="radio" name="wplp_pg_source_asc" id="pg_source_asc1" value="asc"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_asc_selected['asc']) ? esc_html($source_page_asc_selected['asc']) : ''); ?> />
                        <label for="pg_source_asc1"
                               class="radio-label"><?php esc_html_e('Ascending', 'wp-latest-posts') ?></label></li>
                    <li><input type="radio" name="wplp_pg_source_asc" id="pg_source_asc2" value="desc"
                               class="ju-radiobox"
                            <?php echo(isset($source_page_asc_selected['desc']) ? esc_html($source_page_asc_selected['desc']) : ''); ?>/>
                        <label for="pg_source_asc2"
                               class="radio-label"><?php esc_html_e('Descending', 'wp-latest-posts') ?></label></li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php if (class_exists('WPLPAddonAdmin')) : ?>
        <div id="tags" class="tab-content">
            <?php do_action('wplp_addon_contentsource_display_content_tags', $settings); ?>
        </div>
        <div id="custom-posttype" class="tab-content">
            <?php do_action('wplp_addon_contentsource_display_content_custom_posttype', $settings) ?>
        </div>
    <?php endif; ?>
</div>
