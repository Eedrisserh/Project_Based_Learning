<?php
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- view only
global $post;
global $settings;
global $field_defaults;

$act = '';
if (isset($_GET['view']) && $_GET['view'] === 'block') {
    if (isset($_GET['id']) && $_GET['id'] === 'addnew') {
        $act = 'addnew';
        $settings = $field_defaults;
    }
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $post = get_post($_GET['id']); //phpcs:ignore WordPress.WP.GlobalVariablesOverride -- Global post variable
        $now = gmdate('Y-m-d H:i:59');
        if ((mysql2date('U', $post->post_date, false) > mysql2date('U', $now, false)) && $post->post_status !== 'future') {
            $act = 'scheduled';
        } else {
            $act = 'update';
            $settings = get_post_meta($_GET['id'], '_wplp_settings', true);
            if (empty($settings)) {
                $settings = $field_defaults;
            }
        }
    }
}
?>
<form method="post">
<input type="hidden" name="action" value="wplp_save_settings">
<input type="hidden" name="page" value="wplp_widget" />
<input type="hidden" name="wplp_id" value="<?php echo (isset($_GET['id'])) ? esc_html($_GET['id']) : ''?>">
<?php wp_nonce_field('wplp_settings', '_wplp_nonce'); ?>
<div class="wplp-content widget-content">
    <div class="wplp-widget-header">
        <div class="title">
            <?php if ($act ==='addnew') : ?>
                <?php esc_html_e('Add New WPLP Block', 'wp-latest-posts')?>
            <?php else : ?>
                <?php esc_html_e('Edit WPLP Block', 'wp-latest-posts')?>
            <?php endif; ?>
        </div>
        <div class="action-field">
            <?php if ($act === 'addnew') : ?>
            <button type="submit" class="ju-rect-button waves-effect waves-light action-button draft-button" name="wplp_save_draft">
                <?php esc_html_e('Save to draft', 'wp-latest-posts')?>
            </button>
            <button type="submit" class="ju-rect-button waves-effect waves-light action-button public-button" name="wplp_addnew_settings">
                <?php esc_html_e('Publish', 'wp-latest-posts')?>
            </button>
            <?php elseif ($act === 'update') :?>
            <button type="submit" class="ju-rect-button waves-effect waves-light action-button public-button" name="wplp_update_settings">
                <?php esc_html_e('Update', 'wp-latest-posts')?>
            </button>
            <?php elseif ($act === 'scheduled') :?>
            <button type="submit" class="ju-rect-button waves-effect waves-light action-button public-button" name="wplp_sheduled_settings">
                <?php esc_html_e('Scheduled', 'wp-latest-posts')?>
            </button>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php if (isset($_GET['save_block'])) : // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- display message, no action ?>
        <div class="ju-notice-msg ju-notice-success">
            <?php esc_html_e('Block saved successfully!', 'wp-latest-posts') ?>
            <i class="dashicons dashicons-dismiss ju-notice-close"></i>
        </div>
    <?php endif; ?>
    <div class="wplp-widget-container">
        <div class="widget-section">
            <div class="title-box">
               <h4 class="widget-title-content title-color"><?php esc_html_e('Title', 'wp-latest-posts')?></h4>
                <div class="widget-search-wrapper title-section-field">
                    <input type="text" name="post_title"
                           class="wplp-widget-title widget-search-input wplp-font-style"
                           placeholder="<?php esc_html_e('Enter title here', 'wp-latest-posts')?>"
                           value="<?php echo (isset($post->post_title)) ? esc_html($post->post_title) : ''; ?>"
                    />
                </div>
            </div>
            <div class="settings-box">

