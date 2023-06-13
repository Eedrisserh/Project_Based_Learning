<?php
defined('ABSPATH') || die;

if (isset($_GET['view']) && $_GET['view'] === 'block') { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- view only
    $tabs_data = array(
        array(
            'id' => 'content-source',
            'title' => __('Content source', 'wp-latest-posts'),
            'icon' => 'library_books',
        ),
        array(
            'id' => 'theme',
            'title' => __('Display and theme', 'wp-latest-posts'),
            'icon' => 'color_lens',
        ),
        array(
            'id' => 'image-source',
            'title' => __('Images source', 'wp-latest-posts'),
            'icon' => 'collections',
        ),
        array(
            'id' => 'shortcode',
            'title' => __('Shortcode', 'wp-latest-posts'),
            'icon' => 'code',
        ),
        array(
            'id' => 'advanced',
            'title' => __('Advanced', 'wp-latest-posts'),
            'icon' => WPLP_PLUGIN_DIR.'/css/svg/advanced-icon.svg',
        )
    );
} else {
    $tabs_data = array(
        array(
            'id' => 'list-blocks',
            'title' => __('All news blocks', 'wp-latest-posts'),
            'icon' => 'account_circle',
        ),
        array(
            'id' => 'wplp-translation',
            'title' => __('Translation', 'wp-latest-posts'),
            'icon' => 'text_format',
        ),
        array(
            'id' => 'system-check',
            'title' => __('System check', 'wp-latest-posts'),
            'icon' => 'verified_user',
        ),
        array(
            'id' => 'settings',
            'title' => __('Settings', 'wp-latest-posts'),
            'icon' => 'settings',
        ),
        array(
            'id' => 'advanced-news',
            'title' => __('Advanced news', 'wp-latest-posts'),
            'icon' => 'new_releases',
        ),
    );
}

$systemCheck = WPLPAdmin::systemCheck();
?>

<div class="ju-main-wrapper">
    <div class="ju-left-panel-toggle">
        <i class="dashicons dashicons-leftright ju-left-panel-toggle-icon"></i>
    </div>
    <div class="ju-left-panel">
        <div class="ju-logo">
            <a href="https://www.joomunited.com/" target="_blank">
                <img src="<?php echo esc_url(plugins_url('../wordpress-css-framework/images/logo-joomUnited-white.png', dirname(dirname(__FILE__)))) ?>"
                     alt="<?php esc_html_e('JoomUnited logo', 'wp-latest-posts') ?>">
            </a>
        </div>
        <?php
        if (isset($_GET['view']) && $_GET['view'] === 'block') : // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- view only
            ?>
        <div class="ju-menu-search">
            <i class="material-icons ju-menu-search-icon">search</i>
            <input type="text" class="ju-menu-search-input"
                   placeholder="<?php esc_html_e('Search settings', 'wp-latest-posts') ?>"
            >
        </div>
        <ul class="back-menu-tabs">
            <li class="tab" data-tab-title="<?php esc_html_e('Back To Block List', 'wp-latest-posts'); ?>">
                <a href="<?php echo esc_url(admin_url('admin.php?page=wplp-widget')); ?>" class="link-tab white-text waves-effect waves-light">
                    <i class="material-icons menu-tab-icon"> keyboard_backspace </i>
                    <span class="tab-title"><?php esc_html_e('Back To Block List', 'wp-latest-posts'); ?></span>
                </a>
            </li>
        </ul>
        <?php endif; ?>
        <ul class="tabs ju-menu-tabs">
            <?php
            foreach ($tabs_data as $v) :
                if ($v['id'] === 'advanced-news' && class_exists('WPLPAddonAdmin')) {
                    continue;
                }
                ?>
                <li class="tab" data-tab-title="<?php echo esc_attr($v['title']) ?>">
                    <a href="#<?php echo esc_attr($v['id']) ?>"
                       class="link-tab white-text waves-effect waves-light"
                    >
                        <?php if ($v['id'] === 'advanced') :  ?>
                            <img src="<?php echo esc_url($v['icon'])?>" class="menu-advanced-icon">
                        <?php else : ?>
                            <i class="material-icons menu-tab-icon"> <?php echo esc_html($v['icon']) ?> </i>

                        <?php endif; ?>
                        <span class="tab-title" <?php echo (($v['id'] === 'advanced') ? 'style="vertical-align:baseline"' : '') ?>><?php echo esc_html($v['title']) ?></span>
                    </a>
                    <?php if ($v['id'] === 'system-check' && $systemCheck) :?>
                        <i class="material-icons system-checkbox material-icons-menu-alert">info</i>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="ju-right-panel">
        <?php
        if (isset($_GET['view']) && $_GET['view'] === 'block') { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- view only
            $this->loadTabs('header-form');
        }
        ?>

        <?php foreach ($tabs_data as $v) :
            if ($v['id'] === 'advanced-news' && class_exists('WPLPAddonAdmin')) {
                continue;
            }
            ?>
            <div class="ju-content-wrapper" id="<?php echo esc_attr($v['id']) ?>" style="display: none">
                <?php
                if ($v['id'] === 'wplp-translation') {
                    echo '<div class="widget-header">
                            <h1 class="header-title">'.esc_html($v['title']).'</h1>
                          </div>' ;
                    \Joomunited\WPLatestPosts\Jutranslation\Jutranslation::getInput();
                } else {
                    $this->loadTabs($v['id']);
                }
                ?>
            </div>
        <?php endforeach; ?>

        <?php
        if (isset($_GET['view']) && $_GET['view'] === 'block') { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- view only
            $this->loadTabs('bottom-form');
        }
        ?>
    </div>
</div>
