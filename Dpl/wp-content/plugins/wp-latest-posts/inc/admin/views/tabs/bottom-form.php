</div>
</div>
<div class="clearfix"></div>
</div>

</div>
<?php
if (!empty($_COOKIE['wplp_cookie'])) {
    $check = time() - (int)$_COOKIE['wplp_cookie'];
    $month = 30 * 24 * 60 * 60;
}
if ((empty($_COOKIE['wplp_cookie']) || (!empty($_COOKIE['wplp_cookie']) && $check >= $month)) && !class_exists('WPLPAddonAdmin')) : ?>
    <div class="wplp_notification wplp_wrap_notification">
        <div class="notification_dashboard">
            <div class="panel panel-updates dashboard-card">
                <div class="panel-body">
                    <div class="row">
                        <div class="wplp_dashboard_widgets_content">
                            <p class="dashboard_noti_title">
                                <?php esc_html_e('WP LATEST POSTS PRO ADDON', 'wp-latest-posts') ?>
                            </p>
                            <p class="dashboard-title msg">
                                <?php esc_html_e('Bring your WordPress website to the next level with the PRO Addon:
                                     5 Advanced Themes, Content & Advanced Filters, Automatic Crop Content,
                                      One Click Design and more!', 'wp-latest-posts') ?>
                            </p>
                            <a class="more-info"
                               href="https://www.joomunited.com/wordpress-products/wp-latest-posts"
                               target="_blank"><?php esc_html_e('MORE INFORMATION', 'wp-latest-posts') ?></a>
                            <a data-page="close_dashboard"
                               class="dashboard-title wplp_close_notification close_dashboard">
                                <?php esc_html_e('CLOSE FOR ONE MONTH', 'wp-latest-posts') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
</form>
