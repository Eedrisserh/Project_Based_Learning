<div class="ju-main-wrapper">
    <div class="ju-right-panel">
        <div class="ju-top-tabs-wrapper">
            <ul class="tabs ju-top-tabs">
                <li class="tab">
                    <a href="#translation" class="link-tab">
                        <?php esc_html_e('Translation', 'wp-latest-posts') ?>
                    </a>
                </li>
                <li class="tab">
                    <a href="#about" class="link-tab">
                        <?php esc_html_e('About', 'wp-latest-posts') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div id="translation" class="tab-content">
            <?php \Joomunited\WPLatestPosts\Jutranslation\Jutranslation::getInput(); ?>
        </div>
        <div id="about" class="tab-content">
            <div class="about_content">
            <p> </p>
                <?php
                /**
                 * Support information *
                 */
                if (!class_exists('WPLPAddonAdmin')) {
                    ?>
                <div class="card wplp_notice light-orange" style="margin-right:20px">
                    <div class="card-content white-text">
                        <span class="card-title"><?php esc_html_e('Get Pro version', 'wp-latest-posts') ?></span>
                        <p>
                            <em>
                                <?php esc_html_e('Optional add-on is currently not installed or not enabled', 'wp-latest-posts') ?>&nbsp;&rarr;
                                <a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">
                                    <?php esc_html_e('get it here !', 'wp-latest-posts') ?>
                                </a>
                            </em>
                        </p>
                        <iframe src="//player.vimeo.com/video/77775570" width="485" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>

                        </iframe>
                        <p>
                            <a href="http://vimeo.com/77775570">
                            <table class="feature-listing">
                                <tbody>
                                    <tr class="header-feature">
                                        <th class="feature col1"><strong></strong></th>
                                        <th class="feature col2"><strong>FREE </strong></th><th class="feature col2"><strong>PRO </strong></th>
                                    </tr>
                                    <tr class="ligne2">
                                        <td><p>5 pro responsive themes</p></td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="ligne1">
                                        <td>
                                            <p>Visual custom theme design</p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="ligne2">
                                        <td>
                                            <p>Automatically crop text content</p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="ligne1">
                                        <td>
                                            <p>Advanced transition effect</p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="ligne2">
                                        <td>
                                            <p>Advanced content filters</p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="ligne1">
                                        <td>
                                            <p>Private ticket help support</p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/no.png') ?>" alt="no" width="16" height="16">
                                            </p>
                                        </td>
                                        <td class="feature-text">
                                            <p style="text-align: center;">
                                                <img style="margin: 0px;" src="<?php echo esc_url(WPLP_PLUGIN_DIR . '/img/yes.png') ?>" alt="yes" width="16" height="15">
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><br/>
                                            <i>And more...</i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <br/>
                        <a href="http://www.joomunited.com/wordpress-products/wp-latest-posts"
                            target="_blank" class="getthepro"><?php esc_html_e('Get the Pro version now !', 'wp-latest-posts') ?>
                        </a>
                    </div>
                </div>
                    <?php
                } else {
                    do_action('wplp_addon_configuration_display_about', $this->version);
                }
                ?>
            <div class="card wplp_notice light-orange"><div class="card-content white-text">
            <p><?php esc_html_e('Initially released in october 2013 by ', 'wp-latest-posts') ?>
                <a href="http://www.joomunited.com/"><?php esc_html_e('JoomUnited', 'wp-latest-posts') ?>
                </a>
            </p>
            <p>WP Latest Posts WordPress plugin version <?php echo esc_html($this->version) ?></p>
            <p><?php esc_html_e('Author: ', 'wp-latest-posts') ?> JoomUnited</p>
            <p><?php echo esc_html('Your current version of WordPress is: ', 'wp-latest-posts') . esc_html(get_bloginfo('version')) ?></p>
            <p><?php echo esc_html('Your current version of PHP is: ', 'wp-latest-posts') . esc_html(phpversion()) ?></p>
            <p><?php echo esc_html('Your hosting provider\'s web server currently runs: ', 'wp-latest-posts') . esc_html($_SERVER['SERVER_SOFTWARE']) ?></p>
            <p><em><?php esc_html_e('Please specify all of the above information when contacting us for support.', 'wp-latest-posts')?></em></p>
            <p><a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">WP Latest Posts official support site</a></p>
            <a href="http://www.joomunited.com/wordpress-products/wp-latest-posts">
            <img src="<?php echo esc_url(WPLP_PLUGIN_DIR .'img/wplp-logo-white.png') ?>" alt="JoomUnited Logo" /></a>
            </div></div>
            </div>
        </div>
    </div>
</div>

