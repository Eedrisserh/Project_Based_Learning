<?php
if (!defined('ABSPATH')) {
    exit;
}
$default_settings = array(
    'data_cache' => '0',
    'cache_interval_value' => '1'
);
$wplp_settings = get_option('wplp_settings', $default_settings);
?>
<form method="post">
    <input type="hidden" name="action" value="wplp_save_tool_settings" >
    <input type="hidden" name="page" value="wplp_widget" />
    <?php wp_nonce_field('wplp_tool_settings', '_wplp_nonce'); ?>
    <div class="wplp-tools content-tools">
        <div class="widget-header" style="padding-top: 40px">
            <h1 class="header-title"><?php esc_html_e('Settings', 'wp-latest-posts'); ?></h1>
            <div class="inline-button-wrapper">
                <button type="submit" class="ju-rect-button waves-effect waves-light action-button public-button" name="wplp_save_tool_settings"><?php esc_html_e('Save', 'wp-latest-posts'); ?></button>
            </div>
        </div>
        <?php if (isset($_GET['save_tool'])) : // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- display message, no action ?>
            <div class="ju-notice-msg ju-notice-success">
                <?php esc_html_e('Settings saved.', 'wp-latest-posts') ?>
                <i class="dashicons dashicons-dismiss ju-notice-close"></i>
            </div>
        <?php endif; ?>
        <div class="wplp-widget-container tab-content">
            <div class="settings-wrapper">
                <h4><?php esc_html_e('Most popular news block', 'wp-latest-posts'); ?></h4>
                <div class="settings-wrapper-field" id="data-cache">
                    <div class="data-cache half-width">
                        <label class="settings-wrapper-title" for="data_cache"><?php esc_html_e('Data Caching', 'wp-latest-posts') ?></label>
                        <select name="wplp_data_cache" class="browser-default" id="wplp_data_cache" style="width: 50%">
                            <option value="0" <?php selected($wplp_settings['data_cache'], '0'); ?>><?php esc_html_e('Never cache', 'wp-latest-posts'); ?></option>
                            <option value="1" <?php selected($wplp_settings['data_cache'], '1'); ?>><?php esc_html_e('Enable caching', 'wp-latest-posts'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="settings-wrapper-field" id="cache-interval-value">
                    <div class="cache-interval-value">
                        <label class="settings-wrapper-title" for="data_cache"><?php esc_html_e('Refresh cache every', 'wp-latest-posts') ?></label>
                        <input type="text" name="wplp_cache_interval_value" style="max-width: 90px;" class="wplp-short-text wplp-font-style center-text wplp-max-elts" value="<?php esc_html($wplp_settings['cache_interval_value']); ?>" id="wplp_cache_interval_value" /> <?php esc_html_e('min(s)', 'wp-latest-posts'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
        jQuery( document ).ready( function ($) {
            var curr_val = $('#data-cache select').val();
            if (curr_val === '1') {
                $('#cache-interval-value').show();
            } else {
                $('#cache-interval-value').hide();
            }
            // Select data caching
            $('#data-cache select').change(function () {
                var val = $(this).val();
                if (val === '1') {
                    $('#cache-interval-value').show();
                } else {
                    $('#cache-interval-value').hide();
                }
            });
        } )
</script>