<?php

/**
 * Plugin Name: WooCommerce Dynamic Pricing & Discounts
 * Plugin URI: http://www.rightpress.net/woocommerce-dynamic-pricing-and-discounts
 * Description: All-purpose product pricing, cart discount and checkout fee tool for WooCommerce
 * Author: RightPress
 * Author URI: http://www.rightpress.net
 *
 * Text Domain: rp_wcdpd
 * Domain Path: /languages
 *
 * Version: 2.2.10
 *
 * Requires at least: 4.0
 * Tested up to: 5.0
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.5
 *
 * @package WooCommerce Dynamic Pricing & Discounts
 * @category Core
 * @author RightPress
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('RP_WCDPD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RP_WCDPD_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define('RP_WCDPD_PLUGIN_KEY', 'wc-dynamic-pricing-and-discounts');
define('RP_WCDPD_VERSION', '2.2.10');
define('RP_WCDPD_SUPPORT_PHP', '5.3');
define('RP_WCDPD_SUPPORT_WP', '4.0');
define('RP_WCDPD_SUPPORT_WC', '3.0');

if (!class_exists('RP_WCDPD')) {

/**
 * Main plugin class
 *
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
class RP_WCDPD
{
    // Singleton instance
    private static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Load translation
        load_textdomain('rp_wcdpd', WP_LANG_DIR . '/wc-dynamic-pricing-and-discounts/rp_wcdpd-' . apply_filters('plugin_locale', get_locale(), 'rp_wcdpd') . '.mo');
        load_plugin_textdomain('rp_wcdpd', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // Additional Plugins page links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugins_page_links'));

        // Include RightPress library loaded class
        require_once RP_WCDPD_PLUGIN_PATH . 'rightpress/rightpress-loader.class.php';

        // Execute other code when all plugins are loaded
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'), 1);
    }

    /**
     * Code executed when all plugins are loaded
     *
     * @access public
     * @return void
     */
    public function on_plugins_loaded()
    {
        // Load helper classes
        RightPress_Loader::load();

        // Check environment
        if (!RP_WCDPD::check_environment()) {
            return;
        }

        // Load class directories in particular order
        foreach (array('abstract', 'controllers', 'methods', 'conditions', 'condition-methods', 'condition-fields', 'pricing-methods', 'limit') as $directory) {
            foreach (glob(RP_WCDPD_PLUGIN_PATH . 'classes/' . $directory . '/*.class.php') as $filename) {
                require_once $filename;
            }
        }

        // Load extensions
        foreach (glob(RP_WCDPD_PLUGIN_PATH . 'extensions/*') as $directory_name) {
            require_once $directory_name . '/rp-wcdpd-' . str_replace(RP_WCDPD_PLUGIN_PATH . 'extensions/', '', $directory_name) . '.class.php';
        }

        // Load other classes
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-ajax.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-assets.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-cart-discounts.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-conditions.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-helper.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-pricing.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-product-price-override.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-product-pricing.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-rules.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-wc-cart.class.php';
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-wc-order.class.php';

        // Load settings class in the end so that other classes can register their settings
        require_once RP_WCDPD_PLUGIN_PATH . 'classes/rp-wcdpd-settings.class.php';

        // Initialize automatic updates
        require_once(plugin_dir_path(__FILE__) . 'rightpress-updates/rightpress-updates.class.php');
        RightPress_Updates_7119279::init(__FILE__, RP_WCDPD_VERSION);
    }

    /**
     * Check if current user has admin capability
     *
     * @access public
     * @return bool
     */
    public static function is_admin()
    {
        return current_user_can(RP_WCDPD::get_admin_capability());
    }

    /**
     * Get admin capability
     *
     * @access public
     * @return string
     */
    public static function get_admin_capability()
    {
        return apply_filters('rp_wcdpd_capability', 'manage_woocommerce');
    }

    /**
     * Check if environment meets requirements
     *
     * @access public
     * @return bool
     */
    public static function check_environment()
    {
        $is_ok = true;

        // Check PHP version
        if (!version_compare(PHP_VERSION, RP_WCDPD_SUPPORT_PHP, '>=')) {
            add_action('admin_notices', array('RP_WCDPD', 'php_version_notice'));
            return false;
        }

        // Check WordPress version
        if (!RightPress_Help::wp_version_gte(RP_WCDPD_SUPPORT_WP)) {
            add_action('admin_notices', array('RP_WCDPD', 'wp_version_notice'));
            $is_ok = false;
        }

        // Check if WooCommerce is enabled
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array('RP_WCDPD', 'wc_disabled_notice'));
            $is_ok = false;
        }
        else if (!RightPress_Help::wc_version_gte(RP_WCDPD_SUPPORT_WC)) {
            add_action('admin_notices', array('RP_WCDPD', 'wc_version_notice'));
            $is_ok = false;
        }

        return $is_ok;
    }

    /**
     * Display PHP version notice
     *
     * @access public
     * @return void
     */
    public static function php_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Dynamic Pricing & Discounts</strong> requires PHP %s or later. Please update PHP on your server to use this plugin.', 'rp_wcdpd'), RP_WCDPD_SUPPORT_PHP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'rp_wcdpd'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'rp_wcdpd') . '</a>') . '</p></div>';
    }

    /**
     * Display WP version notice
     *
     * @access public
     * @return void
     */
    public static function wp_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Dynamic Pricing & Discounts</strong> requires WordPress version %s or later. Please update WordPress to use this plugin.', 'rp_wcdpd'), RP_WCDPD_SUPPORT_WP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'rp_wcdpd'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'rp_wcdpd') . '</a>') . '</p></div>';
    }

    /**
     * Display WC disabled notice
     *
     * @access public
     * @return void
     */
    public static function wc_disabled_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Dynamic Pricing & Discounts</strong> requires WooCommerce to be active. You can download WooCommerce %s.', 'rp_wcdpd'), '<a href="http://url.rightpress.net/woocommerce-download-page">' . __('here', 'rp_wcdpd') . '</a>') . ' ' . sprintf(__('If you have any questions, please contact %s.', 'rp_wcdpd'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'rp_wcdpd') . '</a>') . '</p></div>';
    }

    /**
     * Display WC version notice
     *
     * @access public
     * @return void
     */
    public static function wc_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Dynamic Pricing & Discounts</strong> requires WooCommerce version %s or later. Please update WooCommerce to use this plugin.', 'rp_wcdpd'), RP_WCDPD_SUPPORT_WC) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'rp_wcdpd'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'rp_wcdpd') . '</a>') . '</p></div>';
    }

    /**
     * Add settings link on plugins page
     *
     * @access public
     * @param array $links
     * @return void
     */
    public function plugins_page_links($links)
    {
        // Support
        $settings_link = '<a href="http://url.rightpress.net/7119279-support">'.__('Support', 'rp_wcdpd').'</a>';
        array_unshift($links, $settings_link);

        // Settings
        if (RP_WCDPD::check_environment()) {
            $settings_link = '<a href="admin.php?page=rp_wcdpd_settings">'.__('Settings', 'rp_wcdpd').'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Get items filter prefix
     *
     * @access public
     * @param string $context
     * @param string $group_key
     * @return string
     */
    public static function get_items_filter_prefix($context = null, $group_key = null)
    {
        $prefix = 'rp_wcdpd_';

        if ($context !== null) {
            $prefix .= $context . '_';
        }

        if ($group_key !== null) {
            $prefix .= $group_key . '_';
        }

        return $prefix;
    }



}

RP_WCDPD::get_instance();

}
