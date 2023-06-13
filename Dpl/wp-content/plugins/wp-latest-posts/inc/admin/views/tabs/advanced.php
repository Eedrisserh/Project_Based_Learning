<?php
global $settings;
global $post;

if (!isset($settings['text_content'])) {
    $settings['text_content'] = 0;
}
?>
<div id="advanced-tab" class="tab-content">
    <div class="settings-wrapper">
        <h4><?php esc_html_e('Advanced', 'wp-latest-posts') ?></h4>
        <div class="date-format settings-wrapper-field">
            <label class="settings-wrapper-title"><?php esc_html_e('Date Format', 'wp-latest-posts') ?></label>
            <input id="date_fmt" type="text" name="wplp_date_fmt" class="wplp-short-text wplp-font-style"
                   value="<?php esc_html(htmlspecialchars(isset($settings['date_fmt']) ? $settings['date_fmt'] : '')) ?>" />
            <a id="wplp_dateFormat" target="_blank" href="http://php.net/manual/en/function.date.php" class="ju-rect-button date-link wplp-font-style date-style-button">
                <?php esc_html_e('Date format', 'wp-latest-posts'); ?>
            </a>
            <div class="clearfix"></div>
        </div>
        <hr>
        <div class="text-content settings-wrapper-field">
            <label class="settings-wrapper-title"><?php esc_html_e('Text Content', 'wp-latest-posts') ?></label>
            <select name="wplp_text_content" class="browser-default wplp-font-style width-30" id="text_content">
                <option value="0" class="short-text" <?php selected((int) $settings['text_content'], 0) ?>>
                    <?php esc_html_e('Full content', 'wp-latest-posts') ?>
                </option>
                <option value="1" class="short-text" <?php selected((int) $settings['text_content'], 1) ?>>
                    <?php esc_html_e('Excerpt content', 'wp-latest-posts') ?>
                </option>
            </select>
        </div>
        <hr>
        <div class="after-content-text settings-wrapper-field">
            <div class="no-post float half-width">
                <label class="settings-wrapper-title"><?php esc_html_e('No posts text', 'wp-latest-posts') ?></label>
                <input id="no_post_text" type="text" name="wplp_no_post_text" class="wplp-short-text wplp-font-style"
                       value=" <?php echo esc_html(htmlspecialchars(isset($settings['no_post_text']) ? $settings['no_post_text'] : '')) ?>" />
            </div>
            <?php
            if (class_exists('WPLPAddonAdmin')) {
                do_action('wplp_addon_advanced_display_readmore_text', $settings);
            }
            ?>
            <div class="clearfix"></div>
        </div>
        <hr>
        <div class="content-text settings-wrapper-field">
            <label class="settings-wrapper-title"><?php esc_html_e('Custom CSS', 'wp-latest-posts') ?></label>
            <textarea id="wplp_custom_css" cols="100" rows="5" name="wplp_custom_css"><?php echo (isset($settings['custom_css']) ? esc_html($settings['custom_css']) : '') ?></textarea>
        </div>
            <?php
            if (isset($post->ID) && isset($post->post_title)) :
                ?>
        <div class="content-text settings-wrapper-field">
            <label class="settings-wrapper-title" for="phpCodeInsert"><?php esc_html_e('Copy & paste this code into a template file to display this WPLP block', 'wp-latest-posts') ?></label>
            <textarea readonly cols="100" rows="2" class="wplp-font-style phpCodeInsert" name="wplp_phpCodeInsert">echo do_shortcode('[frontpage_news widget="<?php echo esc_html($post->ID) ?>" name="<?php echo esc_html($post->post_title) ?>"]');</textarea>
        </div>
            <?php endif; ?>
    </div>
</div>

