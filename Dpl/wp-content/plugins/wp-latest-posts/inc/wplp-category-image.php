<?php
/**
 * Class WPLPCategoryImage
 * WP Latest Posts Add-on category image class
 */
class WPLPCategoryImage
{
    /**
     * WPLPCategoryImage constructor.
     */
    public function __construct()
    {
        add_action('admin_init', array($this, 'wplpAddonInit'));
        // save image while edit or save term
        add_action('edit_term', array($this, 'wplpSaveCategoryImage'));
        add_action('create_term', array($this, 'wplpSaveCategoryImage'));
        // Quick edit
        if (strpos($_SERVER['SCRIPT_NAME'], 'edit-tags.php') > 0) {
            add_action('quick_edit_custom_box', array($this, 'wplpQuickEditCustomBox'), 10, 3);
        }
    }

    /**
     * Add image field to category by filter,action
     *
     * @return void
     */
    public function wplpAddonInit()
    {
        $taxonomies = get_taxonomies();

        if (is_array($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy !== 'category') {
                    continue;
                }
                // Add category for taxonomy
                add_action($taxonomy . '_add_form_fields', array($this, 'addImageTexonomyField'));
                add_action($taxonomy . '_edit_form_fields', array($this, 'editImageTexonomyField'));
                add_filter('manage_edit-' . $taxonomy . '_columns', array($this, 'taxonomyColumns'));
                add_filter('manage_' . $taxonomy . '_custom_column', array($this, 'taxonomyColumn'), 10, 3);
            }
        }
    }

    /**
     * Thumbnail column added to category admin.
     *
     * @param array $columns List column of category
     *
     * @return array
     */
    public function taxonomyColumns($columns)
    {
        $new_columns = array();

        if (isset($columns['cb'])) {
            $new_columns['cb']         = $columns['cb'];
            $new_columns['wplp_thumb'] = __('Image', 'wp-latest-posts');

            unset($columns['cb']);

            return array_merge($new_columns, $columns);
        }
    }

    /**
     * Thumbnail column value added to category admin.
     *
     * @param array   $columns List column of category
     * @param string  $column  Column of category
     * @param integer $id      Id of category
     *
     * @return string
     */
    public function taxonomyColumn($columns, $column, $id)
    {
        $style = "style='width:48px;height: 48px;'";
        // Get image of category by id
        if ($this->getImageUrl($id, 'full')) {
            $image_src = $this->getImageUrl($id, 'full');
            $image     = $this->getImageUrl($id, 'full');
        } else {
            $image_src = plugins_url(DEFAULT_IMG, dirname(__FILE__));
        }
        //Display image for thumbnail
        if ($column === 'wplp_thumb') {
            $columns = '<span><img src="' . $image_src . '" alt="' .
                       __('Thumbnail', 'wp-latest-posts') . '" ' . $style . 'class="wp-post-image" /></span>';
        }

        return $columns;
    }


    /**
     *  Add category image form
     *
     * @return void
     */
    public function addImageTexonomyField()
    {
        // Enqueue script and style
        wp_enqueue_script(
            'wplp-category-script',
            plugins_url('/js/wplp_addon_category_image.js', dirname(__FILE__)),
            array(),
            1.0
        );
        wp_enqueue_style(
            'wplp-category-style',
            plugins_url('/css/wplp_addon_category_image.css', dirname(__FILE__)),
            array(),
            '1.0'
        );
        wp_enqueue_media();
        wp_localize_script(
            'wplp-category-script',
            'default_image',
            array('image' => plugins_url(DEFAULT_IMG, dirname(__FILE__)))
        );
        // Display form add image
        echo '<div class="form-field">';
        echo '<label for="wplp-category-image">' . esc_html__('Category image', 'wp-latest-posts') . '</label>';
        echo '<input type="text" id="wplp-category-image" name="wplp-category-image" value="" />';
        echo '<input type="button" id="wplp-add-category-image"
         class="wplp-add-category-image wplp-category-image-button" value="'
             . esc_html__('Choose Image', 'wp-latest-posts') . '" />';
        echo '<input type="button" id="wplp-remove-category-image"
         class="wplp-category-image-button wplp-remove-category-image" value="'
             . esc_html__('Remove Image', 'wp-latest-posts') . '" />';
        echo '<p>' . esc_html__('Upload image for this category.', 'wp-latest-posts') . '</p>';
        echo '</div>';
    }

    /**
     *  Edit category image form
     *
     * @param object $taxonomy Taxonomy term
     *
     * @return void
     */
    public function editImageTexonomyField($taxonomy)
    {
        // Enqueue script and style
        wp_enqueue_script(
            'wplp-category-script',
            plugins_url('/js/wplp_addon_category_image.js', dirname(__FILE__)),
            array(),
            1.0
        );
        wp_enqueue_style(
            'wplp-category-style',
            plugins_url('/css/wplp_addon_category_image.css', dirname(__FILE__)),
            array(),
            '1.0'
        );
        wp_enqueue_media();
        wp_localize_script(
            'wplp-category-script',
            'default_image',
            array('image' => plugins_url(DEFAULT_IMG, dirname(__FILE__)))
        );

        $style = '';
        $image = '';
        // Get image of category by id
        if ($this->getImageUrl($taxonomy->term_id, 'full')) {
            $image_src = $this->getImageUrl($taxonomy->term_id, 'full');
            $image     = $this->getImageUrl($taxonomy->term_id, 'full');
            $style     = "style='max-width:300px;max-height: 300px;'";
        } else {
            $image_src = plugins_url(DEFAULT_IMG, dirname(__FILE__));
            $style     = "style='width:150px;height: 150px;'";
        }
        echo '<tr class="form-field">';
        echo '<th scope="row"><label for="wplp-category-image">' .
             esc_html__('Category image', 'wp-latest-posts') . '</label></th>';
        echo '<td>';
        echo '<img src="' . esc_url($image_src) . '" class="wplp-category-image" ' . esc_attr($style) . '/>';
        echo '<input type="text" id="wplp-category-image" name="wplp-category-image" value="' . esc_html($image) . '" />';
        echo '<input type="button" id="wplp-add-category-image"
         class="wplp-add-category-image wplp-category-image-button" value="'
             . esc_html__('Choose Image', 'wp-latest-posts') . '" />';
        echo '<input type="button" id="wplp-remove-category-image"
         class="wplp-category-image-button wplp-remove-category-image" value="'
             . esc_html__('Remove Image', 'wp-latest-posts') . '" />';
        echo '<p class="description">' . esc_html__('Image for this category.', 'wp-latest-posts') . '<p>';
        echo '</td>';
        echo '</tr>';
    }

    /**
     * Quick edit image in category
     *
     * @param string $column_name Name column of category
     * @param string $screen      Name screen
     * @param string $name        Name
     *
     * @return void
     */
    public function wplpQuickEditCustomBox($column_name, $screen, $name)
    {
        if ($column_name === 'wplp_thumb') {
            // Create quick edit image form
            echo '<fieldset>
                <div class="wplp_thumb inline-edit-col">
                    <label>
                        <span class="title">' . esc_html__('Thumbnail', 'wp-latest-posts') . '</span>
                        <span class="input-text-wrap"><input type="text" name="wplp-category-image" value="" 
                        class="wplp-category-image-quick tax_list" /></span>
                        <span class="input-text-wrap">
                            <input type="button" id="wplp-add-category-image" 
                            class="wplp-add-category-image wplp-category-image-button" value="'
                 . esc_html__('Choose Image', 'wp-latest-posts') . '" />
                            <input type="button" id="wplp-remove-category-image" 
                            class="wplp-category-image-button wplp-remove-category-image" value="'
                 . esc_html__('Remove Image', 'wp-latest-posts') . '" />
                        </span>
                    </label>
                </div>
            </fieldset>';
        }
    }


    /**
     * Get image url from category
     *
     * @param integer $term_id Id of category
     * @param string  $size    Size of image
     *
     * @return array|false|string
     */
    public function getImageUrl($term_id, $size = 'full')
    {
        $wplp_image         = get_option('wplp_category_image');
        $image_url          = '';
        $category_image_url = '';
        if (!empty($wplp_image)) {
            foreach ($wplp_image as $val) {
                if ((int) $term_id === (int) $val->term_id) {
                    $image_url = $val->image;
                }
            }
        }
        // Get image id from url
        if (!empty($image_url)) {
            $attachment_id = $this->getAttachmentIdByUrl($image_url);
            if (!empty($attachment_id)) {
                $category_image_url = wp_get_attachment_image_src($attachment_id, $size);
                $category_image_url = $category_image_url[0];
            }
        }

        if (!empty($category_image_url)) {
            return $category_image_url;
        } else {
            return '';
        }
    }


    /**
     * Get attachment ID by image url
     *
     * @param string $image_src Url of image
     *
     * @return null|string
     */
    public function getAttachmentIdByUrl($image_src)
    {
        global $wpdb;

        $id = $wpdb->get_var($wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . ' WHERE guid = %s', $image_src));

        return (!empty($id)) ? $id : null;
    }

    /**
     * Save image for category when save term
     *
     * @param integer $term_id Id of category
     *
     * @return void
     */
    public function wplpSaveCategoryImage($term_id)
    {

        $wplp_image = get_option('wplp_category_image');
        if (empty($wplp_image)) {
            $wplp_image = array();
        }
        $image        = '';
        $check_addnew = true;
        // phpcs:disable WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (isset($_POST['wplp-category-image'])) {
            $image = $_POST['wplp-category-image'];
        }
        // phpcs:enable
        //Edit
        if (!empty($wplp_image)) {
            foreach ($wplp_image as $val) {
                if ($term_id === $val->term_id) {
                    $check_addnew = false;
                    $val->image   = $image;
                }
            }
        }
        if ($check_addnew) {
            $cat_img          = new stdClass();
            $cat_img->term_id = $term_id;
            $cat_img->image   = $image;

            array_push($wplp_image, $cat_img);
        }
        update_option('wplp_category_image', $wplp_image);
    }
}
