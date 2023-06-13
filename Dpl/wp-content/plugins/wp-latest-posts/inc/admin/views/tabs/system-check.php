<?php
if (!defined('ABSPATH')) {
    exit;
}
$icon = array(
    'ok' => '<i class="material-icons system-checkbox material-icons-success">check_circle</i>',
    'alert' => '<i class="material-icons system-checkbox material-icons-alert">info</i>',
    'info' => '<img class="system-checkbox material-icons-info bell" src="'.WPLP_PLUGIN_DIR.'/img/icon-notification.png" />'
);
?>
<div class="wplp-check content-system-check">
    <div class="title" style="margin-bottom: 25px">
        <div class="widget-header" style="margin-bottom: 0">
            <h1 class="header-title"><?php esc_html_e('System check', 'wp-latest-posts') ?></h1>
        </div>
        <div class="text-intro">
            <blockquote>
                <?php esc_html_e('We have checked your server environment. 
        If you see some warning below it means that some plugin features may not work properly.
        Reload the page to refresh the results', 'wp-latest-posts') ?>
            </blockquote>
        </div>
    </div>
    <div class="environment-wizard-content">
        <div class="version-container">
            <div class="title"><?php esc_html_e('PHP Version', 'wp-latest-posts'); ?></div>
            <div class="details">
                <?php esc_html_e('PHP ', 'wp-latest-posts'); ?>
                <?php echo esc_html(PHP_VERSION) ?>
                <?php esc_html_e('version', 'wp-latest-posts'); ?>
                <?php
                if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
                    //phpcs:ignore WordPress.Security.EscapeOutput -- Echo icon html
                    echo $icon['ok'];
                } elseif (version_compare(PHP_VERSION, '7.2.0', '<') &&
                          version_compare(PHP_VERSION, '7.0.0', '>=')) {
                    //phpcs:ignore WordPress.Security.EscapeOutput -- Echo icon html
                    echo $icon['info'];
                } else {
                    //phpcs:ignore WordPress.Security.EscapeOutput -- Echo icon html
                    echo $icon['alert'];
                }
                ?>
            </div>

            <?php if (version_compare(PHP_VERSION, '7.2.0', '<')) : ?>
                <p>
                    <?php esc_html_e('Your PHP version is ', 'wp-latest-posts') ?>
                    <?php echo esc_html(PHP_VERSION) ?>
                    <?php esc_html_e('. For performance and security reasons it better to run PHP 7.2+.
            Comparing to previous versions the execution time of PHP 7.X is more than twice as fast and has 30 percent lower memory consumption', 'wp-latest-posts'); ?>
                </p>
            <?php else : ?>
                <p style="height: auto">
                    <?php esc_html_e('Great ! Your PHP version is ', 'wp-latest-posts'); ?>
                    <?php echo esc_html(PHP_VERSION) ?>
                </p>
            <?php endif; ?>

        </div>
    </div>
</div>
