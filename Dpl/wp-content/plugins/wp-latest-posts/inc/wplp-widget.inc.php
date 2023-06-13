<?php

/**
 * Adds WP Front Page News Widget widget.
 *
 * This class is instantiated by registering the widget
 * with WP in wplp-admin.inc.php's constructor
 */
class WPLPWidget extends WP_Widget
{

    const PRO_VERSION_URL = 'http://www.joomunited.com/wordpress-products/wp-latest-posts';
    /**
     * Init did script params
     *
     * @var boolean
     */
    protected static $did_script = false;

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'wplp_widget', // Base ID
            'WP Latest Posts Widget', // Name
            array('description' => __('WP Latest Posts Widget instance', 'wp-latest-posts'),) // Args
        );
        if (!is_admin()) {
            add_action('init', array($this, 'addStyleScript'));
        }
    }

    /**
     * Add style and script in themes
     *
     * @return void
     */
    public function addStyleScript()
    {
        $news_widget_id = $this->get_settings();

        foreach ($news_widget_id as $widgetfind) {
            if (isset($widgetfind['news_widget_id']) && !empty($widgetfind['news_widget_id'])) {
                $widget = get_post($widgetfind['news_widget_id']);
                if (isset($widget) && !empty($widget)) {
                    $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                    $front            = new WPLPFront($widget);
                    add_action('wp_print_styles', array($front, 'loadThemeStyle'));
                    add_action('wp_head', array($front, 'customCSS'));
                    add_action('wp_print_scripts', array($front, 'loadThemeScript'));
                }
            }
        }
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     *
     * @return void
     */
    public function widget($args, $instance)
    {
        //phpcs:ignore WordPress.Security.EscapeOutput -- Render widget directly
        echo $args['before_widget'];

        if (isset($instance['news_widget_id'])) {
            $widget = get_post($instance['news_widget_id']);
            if (isset($widget) && !empty($widget)) {
                $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                $front            = new WPLPFront($widget);

                if (isset($front->widget->settings['show_title'])
                    && (int) $front->widget->settings['show_title'] === 1
                ) {
                    //phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped after line
                    echo $args['before_title'] . esc_html($front->widget->post_title) . $args['after_title'];
                }
                $front->display(true, true);
                //phpcs:ignore WordPress.Security.EscapeOutput -- Render widget directly
                echo $args['after_widget'];
            }
        }
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     *
     * @return boolean|void
     */
    public function form($instance)
    {
        if (isset($instance['news_widget_id'])) {
            $news_widget_id = $instance['news_widget_id'];
        } else {
            $widget = $this->findFirstWidget();
            if ($widget) {
                $news_widget_id = $widget->ID;
            } else {
                echo '<p>' . esc_html__('No Frontpage News Widget has been created.', 'wp-latest-posts') . '</p>';
                //TODO: add link to widget creation edit page
                echo '<p>' . esc_html__('Please create one to use this widget.', 'wp-latest-posts') . '</p>';
                return false;
            }
        }
        $selected[$news_widget_id] = ' selected="selected"';
        $widgets                   = get_posts(
            array(
                'post_type'      => CUSTOM_POST_NEWS_WIDGET_NAME,
                'post_status'    => array(
                    'publish',
                    'future',
                    'private'
                ),
                'posts_per_page' => - 1
            )
        );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('news_widget_id')); ?>">
                <?php esc_html_e('News Widget:', 'wp-latest-posts'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('news_widget_id')); ?>"
                    name="<?php echo esc_attr($this->get_field_name('news_widget_id')); ?>">
                <option value="0"><?php esc_html_e('Select a block', 'wp-latest-posts'); ?></option>
                <?php foreach ($widgets as $widget) : ?>
                    <option value="<?php echo esc_attr($widget->ID); ?>"
                        <?php echo esc_attr(isset($selected[$widget->ID]) ? $selected[$widget->ID] : ''); ?>>
                        <?php echo esc_html($widget->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php

        wp_reset_postdata();
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance                   = array();
        $instance['news_widget_id'] = (!empty($new_instance['news_widget_id'])) ?
            strip_tags($new_instance['news_widget_id']) : '';

        /**
         * Action after WP Latest Posts saves settings to database.
         *
         * @param integer widget ID
         */
        do_action('wplp_save_widget', $new_instance['news_widget_id']);

        return $instance;
    }

    /**
     * TODO: CHECK: Is this really useful?
     *
     * @return mixed|boolean
     */
    private function findFirstWidget()
    {
        $widgets = get_posts(array(
            'post_type'   => CUSTOM_POST_NEWS_WIDGET_NAME,
            'post_status' => array(
                'publish',
                'future',
                'private'
            )
        ));
        if (is_array($widgets) && !empty($widgets)) {
            return array_shift($widgets);
        } else {
            return false;
        }
        wp_reset_postdata();
    }
}

?>
