<?php
/**
 * Plugin Name: CleverSwatches
 * Description: Swatch color for your woocommerce variable products.
 * Version: 2.0.4
 * Author: cleversoft.co <hello.cleversoft@gmail.com>
 * Requires at least: 4.6.1
 * Tested up to: 4.9.2
 *
 * WC requires at least: 3.2.0
 * WC tested up to: 3.2.6
 *
 * Text Domain: clever-swatches
 * Domain Path: /i18n/languages/
 *
 * @package clever-swatches
 * @author cleversoft.co
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// load config
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatches-config.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatches-install.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatches-helper.php';

// install default config
register_activation_hook( __FILE__, array( $zoo_clever_swatch_install, 'zoo_cw_active_action' ) );
register_deactivation_hook( __FILE__, array( $zoo_clever_swatch_install, 'zoo_cw_deactive_action' ) );

if (check_woocommerce_active()) {
    add_action('init','zoo_cw_load_classes');
    if(is_admin()){
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatches-admin-manager.php');
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatches-product-page.php');
    }else{
        add_action('wp_enqueue_scripts', 'zoo_cw_frontend_assets', -1, 0);
    }
} else {
    add_action( 'admin_init', 'zoo_clever_swatch_plugin_deactivate' );

    /**
     * callback function for deactivating the plugin if woocommerce is not activated.
     *
     * @since 1.0.0
     */
    function zoo_clever_swatch_plugin_deactivate(){
        deactivate_plugins( plugin_basename( __FILE__ ) );
        add_action('admin_notices', 'zoo_clever_swatch_woo_missing_notice' );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }

    /**
     * callback function for sending notice if woocommerce is not activated.
     *
     * @since 1.0.0
     * @return string
     */
    function zoo_clever_swatch_woo_missing_notice(){
        echo '<div class="error"><p>' . sprintf(__('CleverSwatches requires WooCommerce to be installed and active. You can download %s here.', 'clever-swatches'), '<a href="http://www.google.com/" target="_blank">WooCommerce</a>') . '</p></div>';
    }
}
function zoo_cw_load_classes(){
    require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatches-product-page.php');
    require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatches-cart-page.php');
    require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatches-shop-page.php');
}
/**
 * Get plugin version
 *
 * @since 1.0.2
 * @return string
 */
function zoo_cw_plugin_ver(){
    $zoo_cw_plugin_data= get_plugin_data( dirname(__FILE__).'/clever-swatches.php');
    return $zoo_cw_plugin_data['Version'];
}
/**
 * Load Front Assets
 *
 * @since 1.0.0
 * @return string
 */
function zoo_cw_frontend_assets()
{
    $general_settings = get_option('zoo-cw-settings', true);

    wp_enqueue_style('zoo-cw', ZOO_CW_CSSPATH . 'clever-swatches-style.css');
    wp_enqueue_style('jquery-ui-tooltip', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('jquery-ui-tooltip');
    wp_register_script('zoo-cw-shop-page-swatches', ZOO_CW_JSPATH . "shop-page-swatch.js",
        array('jquery', 'wp-util'), ZOO_CW_VERSION, TRUE);
    wp_register_script('zoo-cw-single-product-swatches', ZOO_CW_JSPATH . "single-product-page-swatch.js",
        array('jquery'), ZOO_CW_VERSION, TRUE);
    wp_localize_script('zoo-cw-single-product-swatches', 'zoo_cw_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'product_image_custom_class' => $general_settings['product_image_custom_class'],
            'slider_support'=>get_theme_support('wc-product-gallery-slider')
        )
    );
    if ($general_settings['display_shop_page'] == 1) {
        wp_enqueue_script('zoo-cw-shop-page-swatches');
    }
    wp_enqueue_script('zoo-cw-single-product-swatches');
}

function check_woocommerce_active(){

    if ( function_exists('is_multisite') && is_multisite() ){

        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

            return true;
        }
        return false;
    }else{

        if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ){

            return true;
        }
        return false;
    }
}

add_action( 'wp_ajax_clever_swatch_action', 'clever_swatch_action' );
add_action( 'wp_ajax_nopriv_clever_swatch_action', 'clever_swatch_action' );

function clever_swatch_action() {

    $selected_options = $_POST['selected_options'];
    $product = wc_get_product($_POST['product_id']);
    $data_store   = WC_Data_Store::load( 'product' );
    $variation_id = $data_store->find_matching_product_variation( $product, $selected_options );
    $variation_data = $product->get_available_variation( $variation_id );

    $zoo_cw_template = get_template_directory().'/woocommerce/single-product/clever-swatches-image.php';
    if(file_exists($zoo_cw_template)){
        ob_start();
        require($zoo_cw_template);
        $html_content = ob_get_contents();
        ob_end_clean();

        $return['html_content'] = $html_content;
        $return['variation_id'] = $variation_id;
        $return['variation_data'] = $variation_data;
        wp_send_json($return);
    }
    else{
        ob_start();
        require(ZOO_CW_TEMPLATES_PATH . 'woocommerce/single-product/clever-swatches-image.php');
        $html_content = ob_get_contents();
        ob_end_clean();

        $return['html_content'] = $html_content;
        $return['variation_id'] = $variation_id;
        $return['variation_data'] = $variation_data;
        wp_send_json($return);
    }
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_zoo_cw_shop_page_swatch','zoo_cw_shop_page_swatch' );
add_action( 'wp_ajax_nopriv_zoo_cw_shop_page_swatch', 'zoo_cw_shop_page_swatch' );
function zoo_cw_shop_page_swatch() {
    $data = array (
        'result' => 'error',
        'image_url' => ''
    );

    if (isset($_POST['image_size'])) {
        $image_size = $_POST['image_size'];
    } else {
        $image_size = 'shop_catalog';
    }

    $selected_options = $_POST['selected_options'];

    $product = wc_get_product($_POST['product_id']);

    $data_store   = WC_Data_Store::load( 'product' );
    $variation_id = $data_store->find_matching_product_variation( $product, $selected_options );

    $post_thumbnail_id = get_post_thumbnail_id( $variation_id );
    $full_size_image_src   = wp_get_attachment_image_src( $post_thumbnail_id, $image_size );
    $full_size_image_srcset   = wp_get_attachment_image_srcset( $post_thumbnail_id);

    if (!isset($full_size_image_src) || !isset($full_size_image_src[0]) || $full_size_image_src[0] == '') {
        $data = array (
            'result' => 'done',
            'image_src' => wc_placeholder_img_src(),
            'image_srcset' => ''
        );
    } else {
        $data = array (
            'result' => 'done',
            'image_src' => $full_size_image_src[0],
            'image_srcset' => $full_size_image_srcset
        );
    }

    wp_send_json( $data );

    wp_die();
}

add_action( 'wp_ajax_zoo_cw_ajax_cart_variation','zoo_cw_ajax_cart_variation' );
add_action( 'wp_ajax_nopriv_zoo_cw_ajax_cart_variation', 'zoo_cw_ajax_cart_variation' );
function zoo_cw_ajax_cart_variation() {
    ob_start();

    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
    $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
    $variation_id = $_POST['variation_id'];
    $variations = $_POST['variations'];

    $passed_validation 	= apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
    $product_status    = get_post_status( $product_id );

    if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
        // Return fragments
        WC_AJAX::get_refreshed_fragments();

    } else {
        // If there was an error adding to the cart, redirect to the product page to show any errors
        $data = array(
            'error'       => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
        );
        wp_send_json( $data );
    }

    wp_die();
}