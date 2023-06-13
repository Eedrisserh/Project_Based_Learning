<?php
global $post;
$statuss = array(
    'publish' => __('Published', 'wp-latest-posts'),
    'draft' => __('Draft', 'wp-latest-posts'),
);

$visibily = array(
    'public' => __('Public', 'wp-latest-posts'),
    'password' => __('Password protected', 'wp-latest-posts'),
    'private' => __('Private', 'wp-latest-posts'),
);

if (isset($_GET['id']) && $_GET['id'] === 'addnew') { // phpcs:disable WordPress.Security.NonceVerification.Recommended -- view only
    $visibility = 'public';
} else {
    if ('private' === $post->post_status) {
        $post->post_password = '';
        $visibility = 'private';
        $visibility_trans = __('Private', 'wp-latest-posts');
    } elseif (!empty($post->post_password)) {
        $visibility = 'password';
        $visibility_trans = __('Password protected', 'wp-latest-posts');
    } else {
        $visibility = 'public';
        $visibility_trans = __('Public', 'wp-latest-posts');
    }
}

$time_adj = time();
$jj = (isset($post)) ? get_the_date('d', $post->ID) : gmdate('d', $time_adj);
$mm = (isset($post)) ? get_the_date('m', $post->ID) : gmdate('m', $time_adj);
$aa = (isset($post)) ? get_the_date('Y', $post->ID) : gmdate('Y', $time_adj);
$hh = (isset($post)) ? get_the_date('H', $post->ID) : gmdate('H', $time_adj);
$mn = (isset($post)) ? get_the_date('i', $post->ID) : gmdate('i', $time_adj);
$ss = (isset($post)) ? get_the_date('s', $post->ID) : gmdate('s', $time_adj);

$date = (isset($post)) ? get_the_date('j F Y', $post->ID) : gmdate('j F Y', $time_adj);
?>
<div id="shortcode-tab" class="tab-content">
    <div class="settings-wrapper">
        <div class="widget-aside-field">
            <div class="short-code-box aside-box" style="margin-top: 0">
                <label class="settings-wrapper-title"><?php esc_html_e('Shortcode', 'wp-latest-posts') ?></label>
                <?php
                if (isset($post->ID) && isset($post->post_title)) :
                    ?>
                    <div class="content-text settings-wrapper-field">
                        <textarea readonly cols="100" rows="2" class="wplp-font-style phpCodeInsert"
                                  name="wplp_phpCodeInsert">[frontpage_news widget="<?php echo esc_html($post->ID) ?>" name="<?php echo esc_html($post->post_title) ?>"]</textarea>
                    </div>
                <?php else : ?>
                    <div class="content-text settings-wrapper-field">
                        <textarea readonly cols="100" rows="2" class="wplp-font-style phpCodeInsert"
                                  name="wplp_phpCodeInsert"><?php esc_html_e('Save current block to generate shortcode', 'wp-latest-posts') ?></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <div class="status-box aside-box">
                <label class="settings-wrapper-title"><?php esc_html_e('Status', 'wp-latest-posts') ?></label>
                <div class="status-select">
                    <select name="post_status" id="post_status">
                        <?php if (isset($_GET['id']) && $_GET['id'] === 'addnew') : ?>
                            <?php foreach ($statuss as $k => $v) : ?>
                                <option value='<?php echo esc_html($k) ?>'><?php echo esc_html($v) ?></option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php if ('publish' === $post->post_status) : ?>
                                <option<?php selected($post->post_status, 'publish'); ?>
                                        value='publish'><?php esc_html_e('Published', 'wp-latest-posts') ?></option>
                            <?php elseif ('private' === $post->post_status) : ?>
                                <option<?php selected($post->post_status, 'private'); ?>
                                        value='publish'><?php esc_html_e('Privately Published', 'wp-latest-posts') ?></option>
                            <?php elseif ('future' === $post->post_status) : ?>
                                <option<?php selected($post->post_status, 'future'); ?>
                                        value='future'><?php esc_html_e('Scheduled', 'wp-latest-posts') ?></option>
                            <?php endif; ?>
                            <?php if ('auto-draft' === $post->post_status) : ?>
                                <option<?php selected($post->post_status, 'auto-draft'); ?>
                                        value='draft'><?php esc_html_e('Draft', 'wp-latest-posts') ?></option>
                            <?php else : ?>
                                <?php if ('publish' !== $post->post_status) : ?>
                                    <option value='publish'><?php esc_html_e('Published', 'wp-latest-posts') ?></option>
                                <?php endif; ?>
                                <option<?php selected($post->post_status, 'draft'); ?>
                                        value='draft'><?php esc_html_e('Draft', 'wp-latest-posts') ?></option>
                            <?php endif; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="visible-box aside-box">
                <label class="settings-wrapper-title"><?php esc_html_e('Visibility', 'wp-latest-posts') ?></label>
                <div class="status-select">
                    <select name="post_visibility" id="post-visibility-select">
                        <?php foreach ($visibily as $k => $v) : ?>
                            <option <?php selected($visibility, $k); ?>
                                    value='<?php echo esc_html($k) ?>'><?php echo esc_html($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="password-visibility">
                        <label class="settings-wrapper-title"
                               for="post_password"><?php esc_html_e('Password:', 'wp-latest-posts'); ?></label>
                        <input type="text" name="post_password" id="post_password" class="wplp-font-style"
                               maxlength="255"
                               value="<?php echo (isset($post->post_password)) ? esc_attr($post->post_password) : ''; ?>"/>
                    </div>
                </div>
            </div>
            <div class="public-box aside-box" style="margin-bottom: 10px">
                <label class="settings-wrapper-title"><?php esc_html_e('Publication date', 'wp-latest-posts') ?></label>
                <input id="post-date" type="text"
                       name="post_date" class="wplp_datepicker wplp-short-text wplp-font-style"
                       value="<?php echo esc_html($date) ?>"/>
            </div>
            <div class="date-time-box aside-box" style="margin-bottom: 10px">
                <div class="date">
                    <label class="settings-wrapper-title"><?php esc_html_e('Date', 'wp-latest-posts') ?></label>
                    <input type="text" name="jj" value="<?php echo esc_html($jj) ?>" size="2" maxlength="2"
                           autocomplete="off"/>
                    <input type="text" name="mm" value="<?php echo esc_html($mm) ?>" size="2" maxlength="2"
                           autocomplete="off"/>
                    <input type="text" name="aa" value="<?php echo esc_html($aa) ?>" size="4" maxlength="4"
                           autocomplete="off"/>
                </div>
                <div class="time">
                    <label class="settings-wrapper-title"><?php esc_html_e('Time', 'wp-latest-posts') ?></label>
                    <input type="text" name="hh" value="<?php echo esc_html($hh) ?>" size="2" maxlength="2"
                           autocomplete="off"/>
                    <span style="font-weight: bold">:</span>
                    <input type="text" name="mn" value="<?php echo esc_html($mn) ?>" size="2" maxlength="2"
                           autocomplete="off"/>
                    <input type="hidden" name="ss" value="<?php echo esc_html($ss) ?>"/>
                </div>
                <input type="hidden" name="wplp_edit_post_date" value="1"/>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>