<?php
/**
 * Plugin Name: WooCommerce For Epsilon payments link
 * Plugin URI: http://ec.artws.info/shop/plugins/wc-epsilon-link-pro/
 * Description: Woocommerce For Epsilon payments link
 * Version: 1.0.5
 * Author: Artisan Workshop
 * Author URI: http://wc.artws.info/
 * Requires at least: 4.0
 * Tested up to: 4.1.1
 *
 * Text Domain: wc4jp-epsilon
 * Domain Path: /i18n/
 *
 * @package wc-epsilon-link-pro
 * @category Core
 * @author Artisan Workshop
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerceEpsilonLinkPro' ) ) :

/**
 * Load plugin functions.
 */
add_action( 'plugins_loaded', 'WooCommerceEpsilonLinkPro_plugin', 0 );

class WooCommerceEpsilonLinkPro{

	/**
	 * WooCommerce Constructor.
	 * @access public
	 * @return WooCommerce
	 */
	public function __construct() {
		// Include required files
		$this->includes();
		$this->init();
	}
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Module
		define('WC_EPSILON_PLUGIN_PATH',plugin_dir_path( __FILE__ ));
		define('EPSILON_DEBUG_FLG', 0);//Debug Option
		define('EPSILON_TESTMODE_URL_REQUEST', 'https://beta.epsilon.jp/cgi-bin/order/receive_order3.cgi');//Test Mode Url for Order
		define('EPSILON_RUNMODE_URL_REQUEST', 'https://secure.epsilon.jp/cgi-bin/order/receive_order3.cgi');//Real Running Mode Url for Order
		define('EPSILON_TESTMODE_URL_CHECK', 'https://beta.epsilon.jp/cgi-bin/order/getsales2.cgi');//Test Mode Url for Order
		define('EPSILON_RUNMODE_URL_CHECK', 'https://secure.epsilon.jp/cgi-bin/order/getsales2.cgi');//Real Running Mode Url for Order
		// Epsilon 14 Payment Gateways and Include All in One Gateways
		if(get_option('wc-epsilon-pro-cc')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-00cc.php' );	// Credit Card
		if(get_option('wc-epsilon-pro-cs')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-01cs.php' );	// Convenience store
		if(get_option('wc-epsilon-pro-nb')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-02nb.php' );	// Net Bank
		if(get_option('wc-epsilon-pro-wm')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-03wm.php' );	// Web Money
		if(get_option('wc-epsilon-pro-bc')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-04bc.php' );	// BitCash
		if(get_option('wc-epsilon-pro-em')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-05em.php' );	// Electric Money
		if(get_option('wc-epsilon-pro-pe')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-06pe.php' );	// Pay-easy
		if(get_option('wc-epsilon-pro-pp')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-07pp.php' );	// Pay Pal
		if(get_option('wc-epsilon-pro-yw')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-08yw.php' );	// Yahoo! Walet
		if(get_option('wc-epsilon-pro-sc')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-09sc.php' );	// SmartPhone Carrier
		if(get_option('wc-epsilon-pro-sn')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-10sn.php' );	// SBI Net Bank
		if(get_option('wc-epsilon-pro-gp')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-11gp.php' );	// GMO Postpay Payment
		if(get_option('wc-epsilon-pro-mccc')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-12mccc.php' );	// Multi-currency Credit Card
		if(get_option('wc-epsilon-pro-jp')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/epsilon/class-wc-gateway-epsilon-13jp.php' );	// JCB PREMO

		// Admin Setting Screen 
		include_once( plugin_dir_path( __FILE__ ).'/includes/class-wc-admin-screen-epsilon.php' );
	}
	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/*
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wc4jp-epsilon' );
		// Global + Frontend Locale
		load_plugin_textdomain( 'wc4jp-epsilon', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n" );
	}
	public function plugin_activation(){
		$wc_epsilon_dir = WP_CONTENT_DIR.'/uploads/wc-epsilon';
		if( !is_dir( $wc_epsilon_dir ) ){
		mkdir($wc_epsilon_dir, 0755);
		}
	}
		/**
	 * Install default settings
	 *	 * @since 1.0.0
	 */
	protected function install() {
		
	}
}

endif;

//Return Page Shortcode to Site from Epsilon after payment
function epsilon_checkout_func( $atts ){
	$noexist_order =__('Order Data is not exist.','wc4jp-epsilon');
	include_once( 'includes/gateways/epsilon/includes/class-wc-gateway-epsilon-check.php' );
	$epsilon_request = new WC_Gateway_Epsilon_Check( $this );

	if(isset($_GET['trans_code'])){
	$response_array = $epsilon_request->get_order_info_to_epsilon( $_GET['trans_code'] );
	}
	if(isset($_GET['order_number'])){
		$order_id = mb_ereg_replace('[^0-9]', '', $_GET['order_number']);
		$order   = wc_get_order( $order_id );
		if($order->order_total){
			echo __('Wait a moment.','wc4jp-epsilon');
			$order->payment_complete( $_GET['trans_code'] );
			$redirect_url = $order->get_checkout_order_received_url();
			echo '<SCRIPT language="JavaScript">
<!--
url = "'.$redirect_url.'";
function jumpPage() {
  location.href = url;
}
setTimeout("jumpPage()",1)
//-->
</SCRIPT>';
		}else{
			echo $noexist_order;
		}
	}else{
		echo $noexist_order;
	}
//	print_r($response_array);
}
add_shortcode( 'epsilon-checkout', 'epsilon_checkout_func' );

//Return Page Shortcode to Site from Epsilon after payment
function epsilon_return_func( $atts ){
	include_once( 'includes/gateways/epsilon/includes/class-wc-gateway-epsilon-check.php' );
	$epsilon_request = new WC_Gateway_Epsilon_Check( $this );
	if(isset($_GET['order_number'])){
		global $woocommerce;
		$order_id = mb_ereg_replace('[^0-9]', '', $_GET['order_number']);
		$order   = wc_get_order( $order_id );
		$note = __('Return from Epsilon payment page. Sorry, this action is coused your order is cancel now. Please recart.','wc4jp-epsilon');
		$order->cancel_order( $note );
		$redirect_url = $woocommerce->cart->get_cart_url();
		echo __('Wait a moment.','wc4jp-epsilon');
		echo '<SCRIPT language="JavaScript">
<!--
url = "'.$redirect_url.'";
function jumpPage() {
  location.href = url;
}
setTimeout("jumpPage()",1)
//-->
</SCRIPT>';
	}else{
		echo __('This page is especially for Epsilon Return Page. Your action is incorrect or Error has happened. Please check it.','wc4jp-epsilon');
	}
}
add_shortcode( 'epsilon-return', 'epsilon_return_func' );

//If WooCommerce Plugins is not activate notice
function WooCommerceEpsilonLinkPro_fallback_notice(){
	?>
    <div class="error">
        <ul>
            <li><?php echo __( 'WooCommerce for Epsilon method is enabled but not effective. It requires WooCommerce in order to work.', 'wc4jp-epsilon' );?></li>
        </ul>
    </div>
    <?php
}
function WooCommerceEpsilonLinkPro_plugin() {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $wcEpsilonLinkPro = new WooCommerceEpsilonLinkPro();
    } else {
        add_action( 'admin_notices', 'WooCommerceEpsilonLinkPro_fallback_notice' );
    }
}

/**
 * Recieved Credit Payment return from Epsilon
 */
function epsilon_cc_return(){
	if(isset($_GET['order_number'])){
		$order_id = mb_ereg_replace('[^0-9]', '', $_GET['order_number']);
		$order = wc_get_order( $order_id );
		// Mark as pending
		$order->update_status( 'cancelled', __( 'Epsilon Payment cancelled, because renumber.', 'wc4jp-epsilon' ) );
	}
}

add_action( 'woocommerce_before_checkout_form', 'epsilon_cc_return');

/**
 * Recieved Credit Payment complete from Epsilon
 */
function epsilon_cc_recieved(){
	global $woocommerce;
	global $wpdb;
	if(is_cart()){

	if(isset($_GET['trans_code'])){
	// Remove cart
	WC()->cart->empty_cart();
	}
	if(isset($_GET['order_number'])){
		$order_id = mb_ereg_replace('[^0-9]', '', $_GET['order_number']);
		$order   = wc_get_order( $order_id );
		$payment_method = get_post_meta( $order_id, '_payment_method', true );
		include_once( 'includes/gateways/epsilon/includes/class-wc-gateway-epsilon-check.php' );
		$epsilon_request = new WC_Gateway_Epsilon_Check();
		$response_array = $epsilon_request->get_order_info_to_epsilon( $_GET['trans_code'], $testmode );
		if($order->order_total){
			$order->payment_complete( $_GET['trans_code'] );
			if (!isset($_GET['payment_code']) || !$_GET['payment_code'])
			{
				// CC cart
				update_post_meta($order_id, '_payment_method_title', __( 'Epsilon Credit Card', 'wc4jp-epsilon' ));
			}
			$redirect_url = $order->get_checkout_order_received_url();
echo '<script type="text/javascript">
      location.href  = "'.$redirect_url.'";
    </script>';
		}
	}
	}
}
add_action( 'wp_enqueue_scripts', 'epsilon_cc_recieved' );

//add_action( 'woocommerce_before_cart', 'epsilon_cc_recieved');

// Load the API Key library if it is not already loaded. Must be placed in the root plugin file.
//if ( ! class_exists( 'AM_License_Menu' ) ) {
    // Uncomment next line if this is a plugin
//     require_once( plugin_dir_path( __FILE__ ) . 'am-license-menu.php' );

    /**
     * @param string $file             Must be __FILE__ from the root plugin file, or theme functions file.
     * @param string $software_title   Must be exactly the same as the Software Title in the product.
     * @param string $software_version This product's current software version.
     * @param string $plugin_or_theme  'plugin' or 'theme'
     * @param string $api_url          The URL to the site that is running the API Manager. Example: https://www.toddlahman.com/
     *
     * @return \AM_License_Submenu|null
     */
//    AM_License_Menu::instance( __FILE__, 'Woo GMO epsilon Payment','woogmoepsilon', '1.0.2', 'plugin', 'https://wc.artws.info/', 'wc4jp-epsilon' );
//}
