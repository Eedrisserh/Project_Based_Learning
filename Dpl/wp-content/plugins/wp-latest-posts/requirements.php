<?php
/**
 * WP Latest Posts
 *
 * @package WP Latest Posts
 * @author  Joomunited
 * @version 1.0
 */
namespace Joomunited\WPLP;

defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class JUCheckRequirements
 */
class JUCheckRequirements
{
    /**
     * Error string
     *
     * @var string
     */
    private static $error = '';
    /**
     * Warning string
     *
     * @var string
     */
    private static $warning = '';
    /**
     * Information string
     *
     * @var string
     */
    private static $info = '';
    /**
     * If requirements are pass return true
     *
     * @var boolean
     */
    public static $isMeetRequirements = true;
    /**
     * Plugins name
     *
     * @var string
     */
    public static $name = '';
    /**
     * Plugin Domain name
     *
     * @var string
     */
    public static $namedomain = '';
    /**
     * Plugins path to disable
     *
     * @var string
     */
    public static $path = '';
    /**
     * Plugin current version
     *
     * @var string
     */
    public static $version = '';
    /**
     * Minimum php version are requirement
     *
     * @var string
     */
    public static $requirePhpVersion = '';
    /**
     * Requirement classes
     *
     * @var array
     */
    public static $requireClasses = array();
    /**
     * Requirement functions
     *
     * @var array
     */
    public static $requireFunctions = array();
    /**
     * Requirement php modules
     *
     * @var array
     */
    public static $requirePhpModules = array();
    /**
     * Requirement plugins
     *
     * @var array
     */
    public static $requirePlugins = array();
    /**
     * Minimum addons version
     *
     * @var array
     */
    public static $addonsVersion = array();
    /**
     * Addons meet requirements to load
     *
     * @var array
     */
    public static $loadAddons = array();

    /**
     * Class JUCheckRequirements constructor.
     *
     * @param array $args Options
     *
     * @return array
     */
    public static function init($args)
    {

        if (is_array($args) && !empty($args)) {
            foreach ($args as $key => $value) {
                switch ($key) {
                    case 'plugin_name':
                        self::$name = $value;
                        break;
                    case 'plugin_path':
                        self::$path = $value;
                        break;
                    case 'plugin_textdomain':
                        self::$namedomain = $value;
                        break;
                    case 'requirements':
                        if (is_array($value) && !empty($value)) {
                            // Requirement plugins
                            if (isset($value['plugins']) && is_array($value['plugins'])) {
                                self::$requirePlugins = $value['plugins'];
                            }
                            // Requirement minimum php version
                            if (isset($value['php_version']) && $value['php_version'] !== '') {
                                self::$requirePhpVersion = $value['php_version'];
                            }
                            // Requirement php modules
                            if (isset($value['php_modules']) && is_array($value['php_modules'])) {
                                self::$requirePhpModules = $value['php_modules'];
                            }
                            // Requirement php classes
                            if (isset($value['classes']) && is_array($value['classes'])) {
                                self::$requireClasses = $value['classes'];
                            }
                            // Requirement php functions
                            if (isset($value['functions']) && is_array($value['functions'])) {
                                self::$requireFunctions = $value['functions'];
                            }
                            // Requirement addons version
                            if (isset($value['addons_version']) && is_array($value['addons_version'])) {
                                self::$addonsVersion = $value['addons_version'];
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
            if (!function_exists('get_plugin_data')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }
            $addonData = get_plugin_data(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::$path);
            self::$version = isset($addonData['Version']) ? $addonData['Version'] : null;
        }

            // self::checkPhpVersion();
            self::checkRequirementModules();
            self::checkRequirementClasses();
            self::checkRequirementFunctions();
            self::checkRequiredPlugins();
            self::checkMinimumAddonsVersion();
        if (is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) {
            self::displayNotify();
        }

        return array(
            'success' => self::$isMeetRequirements,
            'load' => self::$loadAddons
        );
    }

    /**
     * Check requirement php modules
     *
     * @return void
     */
    private static function checkRequirementModules()
    {
        $modules = self::$requirePhpModules;
        if (is_array($modules) && !empty($modules)) {
            foreach ($modules as $module => $type) {
                if (function_exists('extension_loaded')) {
                    if (!extension_loaded($module)) {
                        $additionsMessage = '';
                        if ($type === 'warning') {
                            $additionsMessage = ' Some function may not work correctly!';
                        }
                        /* translators: Plugins name and requirement module name */
                        // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                        $message = __('<strong>%s</strong> requires php extension <strong>%s</strong> installed.' . $additionsMessage, self::$namedomain);
                        self::addText(
                            $type,
                            sprintf(strip_tags($message, '<strong>'), self::$name, $module)
                        );
                    }
                }
            }
        }
    }
    /**
     * Check requirement classes
     *
     * @return void
     */
    private static function checkRequirementClasses()
    {
        $classes = self::$requireClasses;
        if (is_array($classes) && !empty($classes)) {
            foreach ($classes as $class => $type) {
                if (!class_exists($class)) {
                    $additionsMessage = '';
                    if ($type === 'warning') {
                        $additionsMessage = ' Some function may not work correctly!';
                    }
                    /* translators: Plugins name and requirement class name */
                    // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                    $message = __('<strong>%s</strong> requires php class <strong>%s</strong> to be enable.' . $additionsMessage, self::$namedomain);
                    self::addText(
                        $type,
                        sprintf(strip_tags($message, '<strong>'), self::$name, $class)
                    );
                }
            }
        }
    }
    /**
     * Check requirement functions
     *
     * @return void
     */
    private static function checkRequirementFunctions()
    {
        $functions =self::$requireFunctions;
        if (is_array($functions) && !empty($functions)) {
            foreach ($functions as $function => $type) {
                if (!function_exists($function)) {
                    $additionsMessage = '';
                    if ($type === 'warning') {
                        $additionsMessage = ' Some function may not work correctly!';
                    }
                    /* translators: Plugins name and requirement function name */
                    // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                    $message = __('<strong>%s</strong> requires php function <strong>%s</strong> to be enable.' . $additionsMessage, self::$namedomain);
                    self::addText(
                        $type,
                        sprintf(strip_tags($message, '<strong>'), self::$name, $function)
                    );
                }
            }
        }
    }
    /**
     * Check for php version is meet requirements
     *
     * @return void
     */
    private static function checkPhpVersion()
    {
        if (self::$requirePhpVersion !== '') {
            if (version_compare(PHP_VERSION, self::$requirePhpVersion, '<')) {
                /* translators: Plugins name and version */
                // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                $message = __('<strong>%1$s</strong> need at least PHP %2$s version, please update php before installing the plugin.', self::$namedomain);
                self::addText(
                    'error',
                    sprintf(strip_tags($message, '<strong>'), self::$name, self::$requirePhpVersion)
                );
            }
        }
    }

    /**
     * Check requirement plugins
     *
     * @return void
     */
    private static function checkRequiredPlugins()
    {
        if (!function_exists('is_plugin_active') || !function_exists('get_plugin_data')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $plugins = self::$requirePlugins;
        if (is_array($plugins) && !empty($plugins)) {
            foreach ($plugins as $plugin) {
                if (function_exists('is_plugin_active')) {
                    if (!is_plugin_active($plugin['path'])) {
                        /* translators: Plugins name and plugin requirement name */
                        // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                        $message = __('<strong>%1$s</strong> requires <strong>%2$s</strong> plugin to be activated.', self::$namedomain);
                        self::addText(
                            isset($plugin['type']) ? $plugin['type'] : 'error',
                            sprintf(strip_tags($message, '<strong>'), self::$name, $plugin['name'])
                        );
                    } else {
                        $pluginPath = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin['path'];
                        if (file_exists($pluginPath)) {
                            // Check minimum of require plugin version if set
                            if (isset($plugin['requireVersion']) && $plugin['requireVersion'] !== '') {
                                $requireVersion = $plugin['requireVersion'];
                                $addonData = get_plugin_data($pluginPath);
                                $installedVersion = isset($addonData['Version']) ? $addonData['Version'] : null;
                                if ($installedVersion !== null) {
                                    if (self::versionCompare((string) $installedVersion, '<', (string) $requireVersion)) {
                                        /* translators: Plugins name and requirement function name */
                                        // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                                        $message = __('<strong>%1$s %2$s</strong> requires at least <strong>%3$s %4$s</strong>.', self::$namedomain);
                                        self::addText(
                                            'error',
                                            sprintf(strip_tags($message, '<strong>'), self::$name, self::$version, $plugin['name'], $requireVersion)
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * Check minimum requirement version of addons
     *
     * @return void
     */
    public static function checkMinimumAddonsVersion()
    {

        if (!empty(self::$addonsVersion)) {
            if (!function_exists('get_plugin_data') || !function_exists('deactivate_plugins') || !function_exists('is_plugin_active')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            foreach (self::$addonsVersion as $addonName => $requireVersion) {
                $addonPath = '';
                if (function_exists($addonName.'_getPath')) {
                    $addonPath = call_user_func($addonName.'_getPath');
                }

                if (!is_plugin_active($addonPath)) {
                    continue;
                }
                if (function_exists('get_plugin_data')) {
                    $pluginPath = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $addonPath;
                    if (file_exists($pluginPath)) {
                        $addonData = get_plugin_data($pluginPath);
                        $addonVersion = (isset($addonData['Version']) && strpos($addonData['Version'], '{{version') === false) ? $addonData['Version'] : null;

                        if ($addonVersion !== null) {
                            if (self::versionCompare($addonVersion, '<', $requireVersion)) {
                                /* translators: Plugins name and requirement function name */
                                // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.NonSingularStringLiteralText
                                $message = __('Minimum required version of <strong>%1$s is %2$s</strong> to work with <strong>%3$s %4$s</strong>. Please update the plugin.', self::$namedomain);
                                self::addText(
                                    'warning',
                                    sprintf(strip_tags($message, '<strong>'), $addonData['Name'], $requireVersion, self::$name, self::$version)
                                );
                                //deactivate_plugins(array($path));
                            } else {
                                // Load this addons
                                self::$loadAddons[] = $addonName;
                            }
                        }
                        // Fix for developer
                        if (isset($addonData['Version']) && strpos($addonData['Version'], '{{version') === 0) {
                            // Load addons
                            self::$loadAddons[] = $addonName;
                        }
                    }
                }
            }
        }
    }
    /**
     * Disable plugin
     *
     * @return void
     */
    public static function disablePlugin()
    {
        if (!function_exists('is_plugin_active') || !function_exists('deactivate_plugins')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(self::$path))) {
            deactivate_plugins(array(self::$path));
        }
    }

    /**
     * Add notify text
     *
     * @param string $type   Type of notify
     * @param string $string Notify message
     *
     * @return void
     */
    private static function addText($type, $string)
    {
        switch ($type) {
            case 'error':
                self::$error .= '<p>'. $string .'</p>';
                self::$isMeetRequirements = false;
                break;
            case 'warning':
                self::$warning .= '<p>'. $string .'</p>';
                break;
            case 'info':
                self::$info .= '<p>'. $string .'</p>';
                break;
            default:
                break;
        }
    }

    /**
     * Add notify text to admin_notices
     *
     * @return void
     */
    private static function displayNotify()
    {
        if (self::$error !== '') {
            add_action('admin_notices', array(__NAMESPACE__ . '\JUCheckRequirements', 'printError'));
        }
        if (self::$warning !== '') {
            add_action('admin_notices', array(__NAMESPACE__ . '\JUCheckRequirements', 'printWarning'));
        }
        if (self::$info !== '') {
            add_action('admin_notices', array(__NAMESPACE__ . '\JUCheckRequirements', 'printInfo'));
        }
        if (!self::$isMeetRequirements) {
            add_action('admin_init', array(__NAMESPACE__ . '\JUCheckRequirements', 'disablePlugin'));
        }
    }

    /**
     * Render Error text
     *
     * @return void
     */
    public static function printError()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <?php echo self::$error; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Notify text?>
        </div>
        <?php
    }

    /**
     * Render Warning text
     *
     * @return void
     */
    public static function printWarning()
    {
        ?>
        <div class="notice notice-warning is-dismissible">
            <?php echo self::$warning; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Notify text?>
        </div>
        <?php
    }

    /**
     * Render Info text
     *
     * @return void
     */
    public static function printInfo()
    {
        ?>
        <div class="notice notice-info is-dismissible">
            <?php echo self::$info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Notify text?>
        </div>
        <?php
    }

    /**
     * Compare version
     *
     * @param string $version1 Version 1
     * @param string $operator Operator
     * @param string $version2 Version 2
     *
     * @return boolean
     */
    private static function versionCompare($version1, $operator, $version2)
    {
        $_fv = intval(trim(str_replace('.', '', $version1)));
        $_sv = intval(trim(str_replace('.', '', $version2)));

        if (strlen($_fv) > strlen($_sv)) {
            $_sv = str_pad($_sv, strlen($_fv), 0);
        }

        if (strlen($_fv) < strlen($_sv)) {
            $_fv = str_pad($_fv, strlen($_sv), 0);
        }

        return version_compare((string) $_fv, (string) $_sv, $operator);
    }
}
