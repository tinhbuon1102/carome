<?php
/**
 * Plugin Name: Advanced Order Export For WooCommerce (Pro)
 * Plugin URI:
 * Description: Export orders from WooCommerce with ease (Excel/CSV/XML/JSON supported)
 * Author: AlgolPlus
 * Author URI: https://algolplus.com/
 * Version: 1111111.5.2
 * Text Domain: woocommerce-order-export
 * Domain Path: /i18n/languages/
 * WC requires at least: 2.6.0
 * WC tested up to: 3.2.0
 *
 * Copyright: (c) 2015 AlgolPlus LLC. (algol.plus@gmail.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     woocommerce-order-export
 * @author      AlgolPlus LLC
 * @Category    Plugin
 * @copyright   Copyright (c) 2015 AlgolPlus LLC
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
	
// a small function to check startup conditions 
if( ! function_exists("woe_check_running_options") ) {
	function woe_check_running_options() {
		$is_backend           = is_admin();
		$is_cron              = defined( 'DOING_CRON' );
		$is_frontend_checkout = isset( $_REQUEST['wc-ajax'] ) && $_REQUEST['wc-ajax'] === 'checkout'
								|| isset( $_POST['woocommerce_checkout_place_order'] )
								|| preg_match( '/\bwc\-api\b/', filter_input( INPUT_SERVER, 'REQUEST_URI' ) );

		return $is_backend || $is_cron || $is_frontend_checkout;
	}
}		

if ( ! woe_check_running_options() ) {
	return;
} //don't load for frontend !

//Stop if another version is active!
if( class_exists( 'WC_Order_Export_Admin' ) ) {
	if( ! function_exists( 'woe_warn_free_admin') ) {
		function woe_warn_free_admin() {
			?>
			<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Please, <a href="plugins.php">deactivate</a> Free version of Advanced Order Export For WooCommerce!', 'woocommerce-order-export' ); ?></p>
			</div>
			<?php
		}
	}
	add_action('admin_notices', 'woe_warn_free_admin');
	return;
}

// EDD
include 'classes/updater/class-wc-order-export-updater.php';
include 'classes/updater/class-wc-order-export-edd.php';
define( 'WOE_MAIN_URL', WC_Order_Export_EDD::woe_get_main_url() );
define( 'WOE_STORE_URL', 'https://algolplus.com/plugins/' );
define( 'WOE_ITEM_NAME', 'Advanced Orders Export For WooCommerce (Pro)' );
define( 'WOE_AUTHOR', 'AlgolPlus' );

include 'classes/class-wc-order-export-admin.php';
include 'classes/admin/class-wc-order-export-ajax.php';
include 'classes/admin/class-wc-order-export-manage.php';
include 'classes/admin/class-wc-order-export-cron.php';
include 'classes/core/class-wc-order-export-engine.php';
include 'classes/core/class-wc-order-export-data-extractor.php';
include 'classes/core/class-wc-order-export-data-extractor-ui.php';

define( 'WOE_VERSION', '1.5.2' );
define( 'WOE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
$wc_order_export = new WC_Order_Export_Admin();
register_activation_hook( __FILE__, array($wc_order_export,'install') );
register_deactivation_hook( __FILE__, array($wc_order_export,'deactivate') );
register_uninstall_hook( __FILE__, array('WC_Order_Export_Admin','uninstall') );

// fight with ugly themes which add empty lines
if ( $wc_order_export->must_run_ajax_methods() AND !ob_get_level() ) {
	ob_start();
}	
//Done