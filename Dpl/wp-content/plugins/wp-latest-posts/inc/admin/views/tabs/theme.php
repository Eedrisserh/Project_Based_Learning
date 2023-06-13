<?php
global $settings;

$dfThumbnailPositionTop = '';
$dfThumbnailPositionLeft = '';
$dfThumbnailPositionRight = '';
if (isset($settings['dfThumbnailPosition']) && (!empty($settings['dfThumbnailPosition']))) {
    if ($settings['dfThumbnailPosition'] === 'top') {
        $dfThumbnailPositionTop = 'checked="checked"';
    }
    if ($settings['dfThumbnailPosition'] === 'left') {
        $dfThumbnailPositionLeft = 'checked="checked"';
    }
    if ($settings['dfThumbnailPosition'] === 'right') {
        $dfThumbnailPositionRight = 'checked="checked"';
    }
} else {
    $dfThumbnailPositionTop = 'checked="checked"';
}

$titleChecked = '';
$textChecked = '';
$dateChecked = '';
$categoryChecked = '';
$authorChecked = '';
$readMoreChecked = '';
$imageChecked = '';




if (isset($settings['dfTitle']) && (!empty($settings['dfTitle']))) {
    $titleChecked = 'checked';
}
if (isset($settings['dfText']) && (!empty($settings['dfText']))) {
    $textChecked = 'checked';
}
if (isset($settings['dfDate']) && (!empty($settings['dfDate']))) {
    $dateChecked = 'checked';
}
if (isset($settings['dfCategory']) && (!empty($settings['dfCategory']))) {
    $categoryChecked = 'checked';
}
if (isset($settings['dfAuthor']) && (!empty($settings['dfAuthor']))) {
    $authorChecked = 'checked';
}
if (isset($settings['dfReadMore']) && (!empty($settings['dfReadMore']))) {
    $readMoreChecked = 'checked';
}
if (isset($settings['dfThumbnail']) && (!empty($settings['dfThumbnail']))) {
    $imageChecked = 'checked';
}

if (isset($settings['show_title'])) {
    $show_title_checked[$settings['show_title']] = ' checked="checked"';
}

$classdisabledsmooth = '';
$classdisabled = '';
if (isset($settings['theme'])) {
    if (strpos($settings['theme'], 'timeline')) {
        $classdisabledsmooth = ' disabled';
    }
    if (strpos($settings['theme'], 'masonry') || strpos($settings['theme'], 'material-vertical') || strpos($settings['theme'], 'portfolio')) {
        $classdisabled = ' disabled';
    }
    $theme_selected[$settings['theme']] = ' selected="selected"';
}
?>
<div id="wplp-settings-content-source">
    <div class="wplp-top-bar">
        <ul class="tabs ju-top-tabs">
            <li class="tab">
                <a href="#theme-settings" class="link-tab">
                    <?php esc_html_e('Theme', 'wp-latest-posts') ?>
                </a>
            </li>
            <li class="tab">
                <a href="#text-settings" class="link-tab">
                    <?php esc_html_e('Theme setup', 'wp-latest-posts') ?>
                </a>
            </li>
            <?php if (class_exists('WPLPAddonAdmin')) : ?>
            <li class="tab">
                <a href="#animation" class="link-tab">
                    <?php esc_html_e('Animation', 'wp-latest-posts') ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="theme-settings" class="tab-content">
        <div class="settings-wrapper">
            <h4><?php esc_html_e('Theme choice & Preview', 'wp-latest-posts') ?></h4>
            <div class="theme-choice settings-wrapper-field">
                <select id="theme" name="wplp_theme" class="wplp-font-style">
                    <?php
                    $all_themes = (array)WPLPAdmin::themeLister();
                    wp_localize_script('wplp_admin_block_js', 'themes', $all_themes);
                    foreach ($all_themes as $dir => $theme) {
                        echo '<option  value="' . esc_html($dir) . '" ' . (isset($theme_selected[$dir]) ? esc_html($theme_selected[$dir]) : '') . '>';
                        echo esc_html($theme['name']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <div class="wplp-theme-preview">
                    <?php
                    /**
                     * Enforce default (first found theme) *
                     */
                    if (!isset($settings['theme']) || 'default' === $settings['theme']) {
                        reset($all_themes);
                        $settings['theme'] = key($all_themes);
                    }

                    if (isset($all_themes[$settings['theme']]['theme_url'])) {
                        $screenshot_file_url = $all_themes[$settings['theme']]['theme_url'] . '/screenshot.svg';
                        $screenshot_file_path = $all_themes[$settings['theme']]['theme_root'] . '/screenshot.svg';
                    }
                    if (isset($screenshot_file_path) && file_exists($screenshot_file_path)) {
                        echo '<img alt="preview" src="' . esc_url($screenshot_file_url) . '" />';
                    }
                    ?>
                </div>
            </div>
            <div class="theme-image-position settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Image Position', 'wp-latest-posts') ?></label>
                <ul class="un-craft">
                    <li>
                        <input type="radio" name="wplp_dfThumbnailPosition" id="dfThumbnailPosition1" value="top" class="ju-radiobox"
                            <?php echo esc_html($dfThumbnailPositionTop) ?>
                        />
                        <label for="dfThumbnailPosition1" class="radio-label"><?php esc_html_e('Top', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="radio" name="wplp_dfThumbnailPosition" id="dfThumbnailPosition2" value="left" class="ju-radiobox"
                            <?php echo esc_html($dfThumbnailPositionLeft) ?>
                        />
                        <label for="dfThumbnailPosition2" class="radio-label"><?php esc_html_e('Left', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="radio" name="wplp_dfThumbnailPosition" id="dfThumbnailPosition3" value="right" class="ju-radiobox"
                            <?php echo esc_html($dfThumbnailPositionRight) ?>
                        />
                        <label for="dfThumbnailPosition3" class="radio-label"><?php esc_html_e('Right', 'wp-latest-posts') ?></label>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="theme-new-item settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('New Item', 'wp-latest-posts') ?></label>
                <ul class="craft">
                    <li>
                        <input type="checkbox" name="wplp_dfThumbnail" id="dfThumbnail" value="Thumbnail" class="ju-checkbox" <?php echo esc_attr($imageChecked) ?> />
                        <label for="dfThumbnail"><?php esc_html_e('Thumbnail', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfTitle" id="dfTitle" value="Title" class="ju-checkbox" <?php echo esc_attr($titleChecked) ?> />
                        <label for="dfTitle"><?php esc_html_e('Title', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfAuthor" id="dfAuthor" value="Author" class="ju-checkbox" <?php echo esc_attr($authorChecked) ?> />
                        <label for="dfAuthor"><?php esc_html_e('Author', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfDate" id="dfDate" value="Date" class="ju-checkbox" <?php echo esc_attr($dateChecked) ?> />
                        <label for="dfDate"><?php esc_html_e('Date', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfCategory" id="dfCategory" value="Category" class="ju-checkbox" <?php echo esc_attr($categoryChecked) ?> />
                        <label for="dfCategory"><?php esc_html_e('Category', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfText" id="dfText" value="Text" class="ju-checkbox" <?php echo esc_attr($textChecked) ?> />
                        <label for="dfText"><?php esc_html_e('Text', 'wp-latest-posts') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="wplp_dfReadMore" id="dfReadMore" value="Read more" class="ju-checkbox" <?php echo esc_attr($readMoreChecked) ?> />
                        <label for="dfReadMore"><?php esc_html_e('Read more', 'wp-latest-posts') ?></label>
                    </li>
                </ul>
                <div class="clearfix"></div>
                <hr style="margin: 30px 0">
            </div>
            <div id="wplp-readmore-config">
                <h4><?php esc_html_e('Read More Button', 'wp-latest-posts') ?></h4>
                <div class="button-color-field settings-wrapper-field">
                    <div class="button-color float width-50">
                        <label class="settings-wrapper-title"><?php esc_html_e('Button Color', 'wp-latest-posts') ?></label>
                        <div id="readmoreBgColor" class="wplp-pick-color" data-id="readmoreBgColor">
                            <input id="readmoreBgColor" name="wplp_readmore_bg_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['readmore_bg_color']) ? $settings['readmore_bg_color'] : 'transparent')) ?>"/>
                        </div>
                    </div>
                    <div class="button-text-color float">
                        <label class="settings-wrapper-title"><?php esc_html_e('Button Text Color', 'wp-latest-posts') ?></label>
                        <div id="readmoreTextColor" class="wplp-pick-color" data-id="readmoreTextColor">
                            <input id="readmoreTextColor" name="wplp_readmore_text_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['readmore_text_color']) ? $settings['readmore_text_color'] : '#0c0c0c')) ?>"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="button-type-field settings-wrapper-field">
                    <div class="button-size float width-50">
                        <label class="settings-wrapper-title"><?php esc_html_e('Button Size', 'wp-latest-posts') ?></label>
                        <select id="readmore_size" name="wplp_readmore_size"  class="browser-default wplp-font-style wplp-short-input" style="">
                            <?php
                            $button_sizes = array('Small', 'Medium','Large');
                            if (isset($settings['readmore_size'])) {
                                $readmore_size_selected[$settings['readmore_size']] = ' selected="selected"';
                            }
                            foreach ($button_sizes as $value => $text) {
                                echo '<option value="' . esc_html($value) . '" ' .
                                     (isset($readmore_size_selected[$value]) ? esc_html($readmore_size_selected[$value]) : '') . '>';
                                $text = htmlspecialchars($text);
                                echo esc_html($text);
                                echo '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="button-border float">
                        <label class="settings-wrapper-title"><?php esc_html_e('Button Border Radius', 'wp-latest-posts') ?></label>
                        <input id="readmore_border" type="text" name="wplp_readmore_border" style="width: 30%"
                               value="<?php echo esc_html(htmlspecialchars(isset($settings['readmore_border']) ? $settings['readmore_border'] : '0')) ?>"
                               class="wplp-short-text wplp-font-style center-text" />
                        <span class="readmore-border-param"><?php esc_html_e('px', 'wp-latest-posts') ?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr style="margin: 30px 0">
            </div>
            <div id="wplp-overlay-config">
                <h4><?php esc_html_e('Overlay Image', 'wp-latest-posts') ?></h4>
                <div class="overlay-icon settings-wrapper-field" style="position: relative">
                    <span class="verlay-icon-box" id="verlay-icon-box" data-before="&#x<?php echo esc_html(htmlspecialchars(isset($settings['overlay_icon_selected']) ? $settings['overlay_icon_selected'] : 'f109')) ?>"></span>
                    <input id="overlayIcon" class="btn overlay-icon-select-btn" type="button"
                           value="<?php esc_html_e('Choose an icon', 'wp-latest-posts') ?>" />
                    <input id="overlayIconSelected" name="wplp_overlay_icon_selected"
                           type="hidden"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['overlay_icon_selected']) ? $settings['overlay_icon_selected'] : 'f109')) ?>" />
                    <div class="popUp" id="overlayIconList">
                        <span class="wplp-overlay-close">×</span>
                        <h4>Admin Menu</h4>
                        <span alt="f333" class="dashicons dashicons-menu"></span>
                        <span alt="f319" class="dashicons dashicons-admin-site"></span>
                        <span alt="f226" class="dashicons dashicons-dashboard"></span>
                        <span alt="f109" class="dashicons dashicons-admin-post"></span>
                        <span alt="f104" class="dashicons dashicons-admin-media"></span>
                        <span alt="f103" class="dashicons dashicons-admin-links"></span>
                        <span alt="f105" class="dashicons dashicons-admin-page"></span>
                        <span alt="f101" class="dashicons dashicons-admin-comments"></span>
                        <span alt="f100" class="dashicons dashicons-admin-appearance"></span>
                        <span alt="f106" class="dashicons dashicons-admin-plugins"></span>
                        <span alt="f110" class="dashicons dashicons-admin-users"></span>
                        <span alt="f107" class="dashicons dashicons-admin-tools"></span>
                        <span alt="f108" class="dashicons dashicons-admin-settings"></span>
                        <span alt="f112" class="dashicons dashicons-admin-network"></span>
                        <span alt="f102" class="dashicons dashicons-admin-home"></span>
                        <span alt="f111" class="dashicons dashicons-admin-generic"></span>
                        <span alt="f148" class="dashicons dashicons-admin-collapse"></span>
                        <span alt="f536" class="dashicons dashicons-filter"></span>
                        <span alt="f540" class="dashicons dashicons-admin-customizer"></span>
                        <span alt="f541" class="dashicons dashicons-admin-multisite"></span>
                        <h4>Welcome Screen</h4>
                        <span alt="f119" class="dashicons dashicons-welcome-write-blog"></span>
                        <span alt="f113" class="dashicons dashicons-welcome-add-page"></span>
                        <span alt="f115" class="dashicons dashicons-welcome-view-site"></span>
                        <span alt="f116" class="dashicons dashicons-welcome-widgets-menus"></span>
                        <span alt="f117" class="dashicons dashicons-welcome-comments"></span>
                        <span alt="f118" class="dashicons dashicons-welcome-learn-more"></span>
                        <h4>Post Formats</h4>
                        <span alt="f123" class="dashicons dashicons-format-aside"></span>
                        <span alt="f128" class="dashicons dashicons-format-image"></span>
                        <span alt="f161" class="dashicons dashicons-format-gallery"></span>
                        <span alt="f126" class="dashicons dashicons-format-video"></span>
                        <span alt="f130" class="dashicons dashicons-format-status"></span>
                        <span alt="f122" class="dashicons dashicons-format-quote"></span>
                        <span alt="f125" class="dashicons dashicons-format-chat"></span>
                        <span alt="f127" class="dashicons dashicons-format-audio"></span>
                        <span alt="f306" class="dashicons dashicons-camera"></span>
                        <span alt="f232" class="dashicons dashicons-images-alt"></span>
                        <span alt="f233" class="dashicons dashicons-images-alt2"></span>
                        <span alt="f234" class="dashicons dashicons-video-alt"></span>
                        <span alt="f235" class="dashicons dashicons-video-alt2"></span>
                        <span alt="f236" class="dashicons dashicons-video-alt3"></span>
                        <h4>Media</h4>
                        <span alt="f501" class="dashicons dashicons-media-archive"></span>
                        <span alt="f500" class="dashicons dashicons-media-audio"></span>
                        <span alt="f499" class="dashicons dashicons-media-code"></span>
                        <span alt="f498" class="dashicons dashicons-media-default"></span>
                        <span alt="f497" class="dashicons dashicons-media-document"></span>
                        <span alt="f496" class="dashicons dashicons-media-interactive"></span>
                        <span alt="f495" class="dashicons dashicons-media-spreadsheet"></span>
                        <span alt="f491" class="dashicons dashicons-media-text"></span>
                        <span alt="f490" class="dashicons dashicons-media-video"></span>
                        <span alt="f492" class="dashicons dashicons-playlist-audio"></span>
                        <span alt="f493" class="dashicons dashicons-playlist-video"></span>
                        <span alt="f522" class="dashicons dashicons-controls-play"></span>
                        <span alt="f523" class="dashicons dashicons-controls-pause"></span>
                        <span alt="f519" class="dashicons dashicons-controls-forward"></span>
                        <span alt="f517" class="dashicons dashicons-controls-skipforward"></span>
                        <span alt="f518" class="dashicons dashicons-controls-back"></span>
                        <span alt="f516" class="dashicons dashicons-controls-skipback"></span>
                        <span alt="f515" class="dashicons dashicons-controls-repeat"></span>
                        <span alt="f521" class="dashicons dashicons-controls-volumeon"></span>
                        <span alt="f520" class="dashicons dashicons-controls-volumeoff"></span>
                        <h4>Image Editing</h4>
                        <span alt="f165" class="dashicons dashicons-image-crop"></span>
                        <span alt="f531" class="dashicons dashicons-image-rotate"></span>
                        <span alt="f166" class="dashicons dashicons-image-rotate-left"></span>
                        <span alt="f167" class="dashicons dashicons-image-rotate-right"></span>
                        <span alt="f168" class="dashicons dashicons-image-flip-vertical"></span>
                        <span alt="f169" class="dashicons dashicons-image-flip-horizontal"></span>
                        <span alt="f533" class="dashicons dashicons-image-filter"></span>
                        <span alt="f171" class="dashicons dashicons-undo"></span>
                        <span alt="f172" class="dashicons dashicons-redo"></span>
                        <h4>TinyMCE</h4>

                        <span alt="f200" class="dashicons dashicons-editor-bold"></span>
                        <span alt="f201" class="dashicons dashicons-editor-italic"></span>
                        <span alt="f203" class="dashicons dashicons-editor-ul"></span>
                        <span alt="f204" class="dashicons dashicons-editor-ol"></span>
                        <span alt="f205" class="dashicons dashicons-editor-quote"></span>
                        <span alt="f206" class="dashicons dashicons-editor-alignleft"></span>
                        <span alt="f207" class="dashicons dashicons-editor-aligncenter"></span>
                        <span alt="f208" class="dashicons dashicons-editor-alignright"></span>
                        <span alt="f209" class="dashicons dashicons-editor-insertmore"></span>
                        <span alt="f210" class="dashicons dashicons-editor-spellcheck"></span>
                        <span alt="f211" class="dashicons dashicons-editor-expand"></span>
                        <span alt="f506" class="dashicons dashicons-editor-contract"></span>
                        <span alt="f212" class="dashicons dashicons-editor-kitchensink"></span>
                        <span alt="f213" class="dashicons dashicons-editor-underline"></span>
                        <span alt="f214" class="dashicons dashicons-editor-justify"></span>
                        <span alt="f215" class="dashicons dashicons-editor-textcolor"></span>
                        <span alt="f216" class="dashicons dashicons-editor-paste-word"></span>
                        <span alt="f217" class="dashicons dashicons-editor-paste-text"></span>
                        <span alt="f218" class="dashicons dashicons-editor-removeformatting"></span>
                        <span alt="f219" class="dashicons dashicons-editor-video"></span>
                        <span alt="f220" class="dashicons dashicons-editor-customchar"></span>
                        <span alt="f221" class="dashicons dashicons-editor-outdent"></span>
                        <span alt="f222" class="dashicons dashicons-editor-indent"></span>
                        <span alt="f223" class="dashicons dashicons-editor-help"></span>
                        <span alt="f224" class="dashicons dashicons-editor-strikethrough"></span>
                        <span alt="f225" class="dashicons dashicons-editor-unlink"></span>
                        <span alt="f320" class="dashicons dashicons-editor-rtl"></span>
                        <span alt="f474" class="dashicons dashicons-editor-break"></span>
                        <span alt="f475" class="dashicons dashicons-editor-code"></span>
                        <span alt="f476" class="dashicons dashicons-editor-paragraph"></span>
                        <span alt="f535" class="dashicons dashicons-editor-table"></span>
                        <h4>Posts Screen</h4>
                        <span alt="f135" class="dashicons dashicons-align-left"></span>
                        <span alt="f136" class="dashicons dashicons-align-right"></span>
                        <span alt="f134" class="dashicons dashicons-align-center"></span>
                        <span alt="f138" class="dashicons dashicons-align-none"></span>
                        <span alt="f160" class="dashicons dashicons-lock"></span>
                        <span alt="f528" class="dashicons dashicons-unlock"></span>>
                        <span alt="f145" class="dashicons dashicons-calendar"></span>
                        <span alt="f508" class="dashicons dashicons-calendar-alt"></span>
                        <span alt="f177" class="dashicons dashicons-visibility"></span>
                        <span alt="f530" class="dashicons dashicons-hidden"></span>
                        <span alt="f173" class="dashicons dashicons-post-status"></span>
                        <span alt="f464" class="dashicons dashicons-edit"></span>
                        <span alt="f182" class="dashicons dashicons-trash"></span>
                        <span alt="f537" class="dashicons dashicons-sticky"></span>
                        <h4>Sorting</h4>
                        <span alt="f504" class="dashicons dashicons-external"></span>
                        <span alt="f142" class="dashicons dashicons-arrow-up"></span>
                        <span alt="f140" class="dashicons dashicons-arrow-down"></span>
                        <span alt="f139" class="dashicons dashicons-arrow-right"></span>
                        <span alt="f141" class="dashicons dashicons-arrow-left"></span>
                        <span alt="f342" class="dashicons dashicons-arrow-up-alt"></span>
                        <span alt="f346" class="dashicons dashicons-arrow-down-alt"></span>
                        <span alt="f344" class="dashicons dashicons-arrow-right-alt"></span>
                        <span alt="f340" class="dashicons dashicons-arrow-left-alt"></span>
                        <span alt="f343" class="dashicons dashicons-arrow-up-alt2"></span>
                        <span alt="f347" class="dashicons dashicons-arrow-down-alt2"></span>
                        <span alt="f345" class="dashicons dashicons-arrow-right-alt2"></span>
                        <span alt="f341" class="dashicons dashicons-arrow-left-alt2"></span>
                        <span alt="f156" class="dashicons dashicons-sort"></span>
                        <span alt="f229" class="dashicons dashicons-leftright"></span>
                        <span alt="f503" class="dashicons dashicons-randomize"></span>
                        <span alt="f163" class="dashicons dashicons-list-view"></span>
                        <span alt="f164" class="dashicons dashicons-exerpt-view"></span>
                        <span alt="f509" class="dashicons dashicons-grid-view"></span>
                        <span alt="f545" class="dashicons dashicons-move"></span>
                        <h4>Social</h4>
                        <span alt="f237" class="dashicons dashicons-share"></span>
                        <span alt="f240" class="dashicons dashicons-share-alt"></span>
                        <span alt="f242" class="dashicons dashicons-share-alt2"></span>
                        <span alt="f301" class="dashicons dashicons-twitter"></span>
                        <span alt="f303" class="dashicons dashicons-rss"></span>
                        <span alt="f465" class="dashicons dashicons-email"></span>
                        <span alt="f466" class="dashicons dashicons-email-alt"></span>
                        <span alt="f304" class="dashicons dashicons-facebook"></span>
                        <span alt="f305" class="dashicons dashicons-facebook-alt"></span>
                        <span alt="f462" class="dashicons dashicons-googleplus"></span>
                        <span alt="f325" class="dashicons dashicons-networking"></span>
                        <h4>WordPress.org Specific: Jobs, Profiles, WordCamps</h4>
                        <span alt="f308" class="dashicons dashicons-hammer"></span>
                        <span alt="f309" class="dashicons dashicons-art"></span>
                        <span alt="f310" class="dashicons dashicons-migrate"></span>
                        <span alt="f311" class="dashicons dashicons-performance"></span>
                        <span alt="f483" class="dashicons dashicons-universal-access"></span>
                        <span alt="f507" class="dashicons dashicons-universal-access-alt"></span>
                        <span alt="f486" class="dashicons dashicons-tickets"></span>
                        <span alt="f484" class="dashicons dashicons-nametag"></span>
                        <span alt="f481" class="dashicons dashicons-clipboard"></span>
                        <span alt="f487" class="dashicons dashicons-heart"></span>
                        <span alt="f488" class="dashicons dashicons-megaphone"></span>
                        <span alt="f489" class="dashicons dashicons-schedule"></span>
                        <h4>Products</h4>
                        <span alt="f120" class="dashicons dashicons-wordpress"></span>
                        <span alt="f324" class="dashicons dashicons-wordpress-alt"></span>
                        <span alt="f157" class="dashicons dashicons-pressthis"></span>
                        <span alt="f463" class="dashicons dashicons-update"></span>
                        <span alt="f180" class="dashicons dashicons-screenoptions"></span>
                        <span alt="f348" class="dashicons dashicons-info"></span>
                        <span alt="f174" class="dashicons dashicons-cart"></span>
                        <span alt="f175" class="dashicons dashicons-feedback"></span>
                        <span alt="f176" class="dashicons dashicons-cloud"></span>
                        <span alt="f326" class="dashicons dashicons-translation"></span>
                        <h4>Taxonomies</h4>
                        <span alt="f323" class="dashicons dashicons-tag"></span>
                        <span alt="f318" class="dashicons dashicons-category"></span>
                        <h4>Widgets</h4>
                        <span alt="f480" class="dashicons dashicons-archive"></span>
                        <span alt="f479" class="dashicons dashicons-tagcloud"></span>
                        <span alt="f478" class="dashicons dashicons-text"></span>
                        <h4>Notifications</h4>
                        <span alt="f147" class="dashicons dashicons-yes"></span>
                        <span alt="f158" class="dashicons dashicons-no"></span>
                        <span alt="f335" class="dashicons dashicons-no-alt"></span>
                        <span alt="f132" class="dashicons dashicons-plus"></span>
                        <span alt="f502" class="dashicons dashicons-plus-alt"></span>
                        <span alt="f460" class="dashicons dashicons-minus"></span>
                        <span alt="f153" class="dashicons dashicons-dismiss"></span>
                        <span alt="f159" class="dashicons dashicons-marker"></span>
                        <span alt="f155" class="dashicons dashicons-star-filled"></span>
                        <span alt="f459" class="dashicons dashicons-star-half"></span>
                        <span alt="f154" class="dashicons dashicons-star-empty"></span>
                        <span alt="f227" class="dashicons dashicons-flag"></span>
                        <span alt="f534" class="dashicons dashicons-warning"></span>
                        <h4>Misc</h4>
                        <span alt="f230" class="dashicons dashicons-location"></span>
                        <span alt="f231" class="dashicons dashicons-location-alt"></span>
                        <span alt="f178" class="dashicons dashicons-vault"></span>
                        <span alt="f332" class="dashicons dashicons-shield"></span>
                        <span alt="f334" class="dashicons dashicons-shield-alt"></span>
                        <span alt="f468" class="dashicons dashicons-sos"></span>
                        <span alt="f179" class="dashicons dashicons-search"></span>
                        <span alt="f181" class="dashicons dashicons-slides"></span>
                        <span alt="f183" class="dashicons dashicons-analytics"></span>
                        <span alt="f184" class="dashicons dashicons-chart-pie"></span>
                        <span alt="f185" class="dashicons dashicons-chart-bar"></span>
                        <span alt="f238" class="dashicons dashicons-chart-line"></span>
                        <span alt="f239" class="dashicons dashicons-chart-area"></span>
                        <span alt="f307" class="dashicons dashicons-groups"></span>
                        <span alt="f338" class="dashicons dashicons-businessman"></span>
                        <span alt="f336" class="dashicons dashicons-id"></span>
                        <span alt="f337" class="dashicons dashicons-id-alt"></span>
                        <span alt="f312" class="dashicons dashicons-products"></span>
                        <span alt="f313" class="dashicons dashicons-awards"></span>
                        <span alt="f314" class="dashicons dashicons-forms"></span>
                        <span alt="f473" class="dashicons dashicons-testimonial"></span>
                        <span alt="f322" class="dashicons dashicons-portfolio"></span>
                        <span alt="f330" class="dashicons dashicons-book"></span>
                        <span alt="f331" class="dashicons dashicons-book-alt"></span>
                        <span alt="f316" class="dashicons dashicons-download"></span>
                        <span alt="f317" class="dashicons dashicons-upload"></span>
                        <span alt="f321" class="dashicons dashicons-backup"></span>
                        <span alt="f469" class="dashicons dashicons-clock"></span>
                        <span alt="f339" class="dashicons dashicons-lightbulb"></span>
                        <span alt="f482" class="dashicons dashicons-microphone"></span>
                        <span alt="f472" class="dashicons dashicons-desktop"></span>
                        <span alt="f547" class="dashicons dashicons-laptop"></span>
                        <span alt="f471" class="dashicons dashicons-tablet"></span>
                        <span alt="f470" class="dashicons dashicons-smartphone"></span>
                        <span alt="f525" class="dashicons dashicons-phone"></span>
                        <span alt="f510" class="dashicons dashicons-index-card"></span>
                        <span alt="f511" class="dashicons dashicons-carrot"></span>
                        <span alt="f512" class="dashicons dashicons-building"></span>
                        <span alt="f513" class="dashicons dashicons-store"></span>
                        <span alt="f514" class="dashicons dashicons-album"></span>
                        <span alt="f527" class="dashicons dashicons-palmtree"></span>
                        <span alt="f524" class="dashicons dashicons-tickets-alt"></span>
                        <span alt="f526" class="dashicons dashicons-money"></span>
                        <span alt="f526" class="dashicons dashicons-money"></span>
                        <span alt="f529" class="dashicons dashicons-thumbs-up"></span>
                        <span alt="f542" class="dashicons dashicons-thumbs-down"></span>
                        <span alt="f538" class="dashicons dashicons-layout"></span>
                        <span alt="f546" class="dashicons dashicons-paperclip"></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="overlay-color-field settings-wrapper-field">
                    <div class="overlay-icon-color float width-50">
                        <label class="settings-wrapper-title"><?php esc_html_e('Icon Color', 'wp-latest-posts') ?></label>
                        <div id="overIconColor" class="wplp-pick-color" data-id="overIconColor">
                            <input id="overIconColor" name="wplp_over_icon_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['over_icon_color']) ? $settings['over_icon_color'] : '#ffffff')) ?>"/>
                        </div>
                    </div>
                    <div class="button-text-color float">
                        <label class="settings-wrapper-title"><?php esc_html_e('Icon Background Color', 'wp-latest-posts') ?></label>
                        <div id="overBGIconColor" class="wplp-pick-color" data-id="overBGIconColor">
                            <input id="overBGIconColor" name="wplp_over_bg_icon_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['over_bg_icon_color']) ? $settings['over_bg_icon_color'] : '#444444')) ?>"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="overlay-type-field settings-wrapper-field">
                    <div class="overlay-color float width-50">
                        <label class="settings-wrapper-title"><?php esc_html_e('Overlay Color', 'wp-latest-posts') ?></label>
                        <div id="overlayColor" class="wplp-pick-color" data-id="overlayColor">
                            <input id="overlayColor" name="wplp_overlay_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['overlay_color']) ? $settings['overlay_color'] : 'ffffff')) ?>"/>
                        </div>
                    </div>
                    <div class="overlay-tranparancy float">
                        <label class="settings-wrapper-title"><?php esc_html_e('Overlay Transparency', 'wp-latest-posts') ?></label>
                        <span data-option="overlayTransparent" class="overlay-slider overlayTransparent" style="margin-left: 12px"></span>
                        <input id="overlayTransparent" type="text" name="wplp_overlay_transparent" style="width: 25%;"
                               value="<?php echo esc_html(htmlspecialchars(isset($settings['overlay_transparent']) ? $settings['overlay_transparent'] : '0.7')) ?>"
                               class="wplp-short-text wplp-font-style center-text wplp-slider-input" />
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr style="margin: 30px 0">
            </div>
            <div id="wplp-arrow-color">
                <h4><?php esc_html_e('Arrow settings', 'wp-latest-posts') ?></h4>
                <div class="arrow-settings-field settings-wrapper-field">
                    <div class="arrow-color float width-50">
                        <label class="settings-wrapper-title"><?php esc_html_e('Arrow Color', 'wp-latest-posts') ?></label>
                        <div id="arrowColor" class="wplp-pick-color" data-id="arrowColor">
                            <input id="arrowColor" name="wplp_arrow_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['arrow_color']) ? $settings['arrow_color'] : 'rgb(51, 51, 51)')) ?>"/>
                        </div>
                    </div>
                    <div class="arrow-hover float">
                        <label class="settings-wrapper-title"><?php esc_html_e('Arrow Mouse Hover', 'wp-latest-posts') ?></label>
                        <div id="arrowHoverColor" class="wplp-pick-color" data-id="arrowHoverColor">
                            <input id="arrowHoverColor" name="wplp_arrow_hover_color" class="wplp_colorPicker"
                                   value="<?php echo esc_html(htmlspecialchars(isset($settings['arrow_hover_color']) ? $settings['arrow_hover_color'] : 'rgb(54, 54, 54)')) ?>"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php
            if (class_exists('WPLPAddonAdmin')) {
                echo '<hr style="margin: 30px 0">';
                do_action('wplp_addon_theme_display_background_color', $settings);
            }
            ?>
        </div>
    </div>
    <div id="text-settings" class="tab-content">
        <div class="settings-wrapper">
            <div class="fit-block" style="margin-bottom: 20px">
                <label class="ju-setting-label image-fit-label" for="show_title" style="color: #404852"><?php esc_html_e('Show title', 'wp-latest-posts') ?></label>
                <div class="ju-switch-button">
                    <label class="switch">
                        <input type="checkbox"
                               name="wplp_show_title"
                               id="show_title"
                               value="1"
                            <?php echo (isset($show_title_checked[1]) ? esc_html($show_title_checked[1]) : ''); ?>
                        />
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php
            if (class_exists('WPLPAddonAdmin')) {
                do_action('wplp_addon_theme_display_loadmore_button', $settings);
            }

            if (isset($settings['layzyload_default'])) {
                $layzyload_default_checked[$settings['layzyload_default']] = ' checked="checked"';
            }
            ?>

            <div class="fit-block" style="margin-bottom: 20px">
                <label class="ju-setting-label image-fit-label" for="layzyload_default" style="color: #404852"><?php esc_html_e('Lazy load image', 'wp-latest-posts') ?></label>
                <div class="ju-switch-button">
                    <label class="switch">
                        <input type="hidden"
                               name="wplp_layzyload_default"
                               value="0"
                        />
                        <input type="checkbox"
                               name="wplp_layzyload_default"
                               id="layzyload_default"
                               value="1"
                            <?php echo (isset($layzyload_default_checked[1]) ? esc_html($layzyload_default_checked[1]) : ''); ?>
                        />
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <?php
            if (class_exists('WPLPAddonAdmin')) {
                do_action('wplp_addon_theme_display_force_hover_icon', $settings);
                do_action('wplp_addon_theme_display_open_newlink', $settings);
                echo '<div class="settings-wrapper-field">';
                do_action('wplp_addon_theme_display_icon_selector', $settings);
                echo '</div>';
            }
            ?>

            <div class="number-element settings-wrapper-field">
                <div class="number-columns float col-li-4">
                    <label class="settings-wrapper-title"><?php esc_html_e('Number of columns', 'wp-latest-posts') ?></label>
                    <input id="amount_cols" type="text" name="wplp_amount_cols" class="wplp-short-text wplp-font-style wplp-short-input center-text"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['amount_cols']) ? $settings['amount_cols'] : '3')) ?>"
                    <?php echo esc_attr($classdisabledsmooth) ?>/>
                </div>
                <div class="number-rows float col-li-4">
                    <label class="settings-wrapper-title"><?php esc_html_e('Number of rows', 'wp-latest-posts') ?></label>
                    <input id="amount_rows" type="text" name="wplp_amount_rows" class="wplp-short-text wplp-font-style wplp-short-input center-text"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['amount_rows']) ? $settings['amount_rows'] : '')) ?>"
                    <?php echo esc_attr($classdisabled) . esc_attr($classdisabledsmooth) ?>/>
                </div>
                <div class="number-ele float col-li-4">
                    <label class="settings-wrapper-title"><?php esc_html_e('Max number of news', 'wp-latest-posts') ?></label>
                    <input id="max_elts" type="text" name="wplp_max_elts" class="wplp_change_content wplp-short-text wplp-font-style wplp-short-input center-text"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['max_elts']) ? $settings['max_elts'] : '30')) ?>" />
                </div>
                <div class="number-per-page float col-li-4">
                    <label class="settings-wrapper-title"><?php esc_html_e('News per page', 'wp-latest-posts') ?></label>
                    <input id="per_page" type="text" name="wplp_per_page" class="wplp-short-text wplp-font-style wplp-short-input center-text"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['per_page']) ? $settings['per_page'] : '10')) ?>" />
                </div>
                <div class="clearfix"></div>
            </div>
            <hr>
            <div class="pagination settings-wrapper-field">
                <label class="settings-wrapper-title"><?php esc_html_e('Pagination', 'wp-latest-posts') ?></label>
                <ul class="un-craft col-li-4">
                    <?php
                    $default_pagination = array(
                        __('None', 'wp-latest-posts'),
                        __('Arrows', 'wp-latest-posts'),
                        __('Arrows with bullets', 'wp-latest-posts'),
                        __('Bullets', 'wp-latest-posts')
                    );
                    if (isset($settings['pagination'])) {
                        $pagination_selected[$settings['pagination']] = ' checked="checked"';
                    }
                    foreach ($default_pagination as $value => $text) :
                        ?>
                    <li>
                        <input type="radio" name="wplp_pagination" id="pagination<?php echo esc_html($value); ?>" value="<?php echo esc_html($value); ?>" class="ju-radiobox"
                            <?php echo (isset($pagination_selected[$value]) ? esc_html($pagination_selected[$value]) : ''); ?> />
                        <label for="pagination<?php echo esc_html($value); ?>" class="radio-label"><?php echo esc_html($text) ?></label>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clearfix"></div>
                <hr>
            </div>
            <div class="settings-wrapper-field">
                <div class="total-width float width-50">
                    <label class="settings-wrapper-title"><?php esc_html_e('Total width', 'wp-latest-posts') ?></label>
                    <input id="total_width" type="text" name="wplp_total_width" class="wplp-short-text wplp-font-style center-text" style="width: 30%"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['total_width']) ? (int)$settings['total_width'] : '100')) ?>"  />
                    <select id="total_width_unit" class="browser-default" name="wplp_total_width_unit" style="width: 30%">
                        <?php
                        $width_unit_values = array(
                            '%',
                            'em',
                            'px'
                        );
                        if (isset($settings['total_width_unit'])) {
                            $units_selected[$settings['total_width_unit']] = ' selected="selected"';
                        }
                        foreach ($width_unit_values as $value => $text) : ?>
                        <option <?php echo (isset($units_selected[$value]) ? esc_html($units_selected[$value]) : '') ?> value="<?php echo esc_html($value) ?>">
                            <?php echo esc_html($text) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="skip-posts float">
                    <label class="settings-wrapper-title"><?php esc_html_e('Number of posts to skip', 'wp-latest-posts') ?></label>
                    <input id="off_set" type="text" name="wplp_off_set" class="wplp-short-text wplp-font-style wplp-short-input center-text"
                           value="<?php echo esc_html(htmlspecialchars(isset($settings['off_set']) ? (int)$settings['off_set'] : '0')) ?>"  />
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
            if (class_exists('WPLPAddonAdmin')) {
                echo '<hr style="margin: 30px 0">';
                do_action('wplp_addon_theme_display_crop_option', $settings);
            }
            ?>
        </div>
    </div>
    <?php
    if (class_exists('WPLPAddonAdmin')) {
        do_action('wplp_addon_theme_display_animation_tab', $settings);
    }
    ?>
</div>
