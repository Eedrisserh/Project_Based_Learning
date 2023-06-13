<?php
if (!defined('ABSPATH')) {
    exit;
}
$left_item = array(
    array(
            'title' => __('5 advanced themes', 'wp-latest-posts'),
            'content' => __('Advanced configuration for the default theme + 5 advanced themes and layouts', 'wp-latest-posts')
    ),
    array(
        'title' => __('Content & Advanced Filters', 'wp-latest-posts'),
        'content' => __('Load content from tags, custom post type categories or page selection and filter by news dates', 'wp-latest-posts')
    ),
    array(
        'title' => __('One click design', 'wp-latest-posts'),
        'content' => __('In advanced themes you got a color picker to make your news fit your theme in just one click', 'wp-latest-posts')
    ),
    array(
        'title' => __('Automatically crop content', 'wp-latest-posts'),
        'content' => __('Automatically crop your content title and text by selecting the number of words, chars or lines', 'wp-latest-posts')
    ),
    array(
        'title' => __('Focus on content', 'wp-latest-posts'),
        'content' => __('Create news layouts with an infinite number of widgets, so you and your clients can focus on content', 'wp-latest-posts')
    )
);
$right_item = array(
    array(
        'title' => __('WPML & Polylang', 'wp-latest-posts'),
        'content' => __('The plugin is integrated with Polylang and WPML, filter news by language', 'wp-latest-posts')
    ),
    array(
        'title' => __('Image source', 'wp-latest-posts'),
        'content' => __('Select image source, featured or first content image and setup a default image to prevent content without image', 'wp-latest-posts')
    ),
    array(
        'title' => __('PHP shortcode', 'wp-latest-posts'),
        'content' => __('Add your latest news and configuration wherever you want with a PHP shortcode, for example in your page layouts', 'wp-latest-posts')
    ),
    array(
        'title' => __('Animation', 'wp-latest-posts'),
        'content' => __('Select animation for default and smooth hover slider theme: fade or slide and duration', 'wp-latest-posts')
    ),
    array(
        'title' => __('Advanced news Selection', 'wp-latest-posts'),
        'content' => __('Option to load post content using OR and AND selectors', 'wp-latest-posts')
    )
);
$image_src = WPLP_PLUGIN_DIR . '/img/welcome-illustration.png';
?>
<div class="more-advanced-news">
    <div class="content-image">
        <img src="<?php echo esc_url($image_src); ?>" class="Illustration" />
    </div>
    <div class="content-title">
        <label><?php esc_html_e('Do more with your content with ', 'wp-latest-posts') ?></label>
        <br>
        <label><?php esc_html_e('WP Latest Posts PRO ADDON', 'wp-latest-posts') ?></label>
    </div>
    <div class="content">
        <div class="bc-left">
            <div class="bot-panel">
                <ul>
                    <?php
                    foreach ($left_item as $item) :
                        ?>
                    <li>
                        <div class="title"><?php echo esc_html($item['title']) ?>
                        </div>
                        <div class="panel-addon"><?php esc_html_e('Pro addon feature', 'wp-latest-posts') ?></div>
                        <div class="panel">
                            <span><?php echo esc_html($item['content'])?></span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="bc-right">
            <div class="bot-panel">
                <ul>
                    <?php
                    foreach ($right_item as $item) :
                        ?>
                        <li>
                            <div class="title"><?php echo esc_html($item['title']) ?>
                            </div>
                            <div class="panel-addon"><?php esc_html_e('Pro addon feature', 'wp-latest-posts') ?></div>
                            <div class="panel">
                                <span><?php echo esc_html($item['content'])?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="bottom-content">
        <a href="https://www.joomunited.com/wordpress-products/wp-latest-posts" target="_blank" class="ju-button orange-button waves-effect waves-light">
            <?php esc_html_e('Check our product page', 'wp-latest-posts') ?>
        </a>
    </div>
</div>
