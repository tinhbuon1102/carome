<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Pro_Loader {
	public function __construct() {
		add_action( 'wdp_include_core_classes', array( $this, 'wdp_include_core_classes' ) );
		add_action( 'wdp_include_cart_adjustments', array( $this, 'wdp_include_cart_adjustments' ) );
		add_action( 'wdp_include_conditions', array( $this, 'wdp_include_conditions' ) );
		add_action( 'wdp_include_limits', array( $this, 'wdp_include_limits' ) );

		add_filter( 'wdp_admin_tabs', array( $this, 'wdp_admin_tabs' ), 10, 1 );

		add_action( 'wp_print_styles', array( $this, 'load_frontend_assets_pro' ) );

		include_once WC_ADP_PRO_VERSION_PATH . 'classes/class-wdp-importer.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/class-wdp-cron-export.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/common/class-wdp-helpers-pro.php';

		add_filter( "wdp_get_default_settings", array( 'WDP_Helpers_Pro', "wdp_get_default_settings" ) );

		$options = WDP_Helpers::get_settings();

		if ( !is_admin() || $options['load_in_backend'] || WDP_Frontend::is_nopriv_ajax_processing() ) {
			include_once WC_ADP_PRO_VERSION_PATH . '/classes/class-wdp-frontend-pro.php';
			new WDP_Frontend_pro();
		}

		add_filter( 'woocommerce_hidden_order_itemmeta', function ( $keys ) {
			/** @see  WDP_Frontend_pro::save_initial_price_to_order_item */
			$keys[] = '_wdp_initial_cost';
			$keys[] = '_wdp_initial_cost_tax';

			return $keys;
		}, 10, 1 );
		
		if ( $options['show_striked_prices_in_order'] ) {
			add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_format_striked_price' ), 10, 3 );
		}
	}

	/**
	 * @param $subtotal
	 * @param $item WC_Order_Item_Product
	 * @param $order WC_Order
	 *
	 * @return string
	 */
	public function order_item_format_striked_price( $subtotal, $item, $order ) {
		$tax_display = get_option( 'woocommerce_tax_display_cart' );
		$inc_tax     = 'excl' === $tax_display;

		$initial_subtotal = null;
		if ( $item->meta_exists('_wdp_initial_cost') ) {
			if ( ! $inc_tax ) {
				$initial_subtotal = (float) $item->get_meta( '_wdp_initial_cost' ) + (float) $item->get_meta( '_wdp_initial_cost_tax' );
			} else {
				$initial_subtotal = (float) $item->get_meta( '_wdp_initial_cost' );
			}
			$initial_subtotal = $initial_subtotal * $item->get_quantity();
		} else {
			return $subtotal;
		}

		$initial_subtotal = round( $initial_subtotal, wc_get_price_decimals() );

		if ( $order->get_line_subtotal( $item ) < $initial_subtotal ) {
			if ( $inc_tax ) {
				$ex_tax_label = $order->get_prices_include_tax() ? 1 : 0;

				$initial_subtotal_html = wc_price( $initial_subtotal, array(
					'ex_tax_label' => $ex_tax_label,
					'currency'     => $order->get_currency(),
				) );
			} else {
				$initial_subtotal_html = wc_price( $initial_subtotal, array( 'currency' => $order->get_currency() ) );
			}

			$subtotal = wc_format_sale_price( $initial_subtotal_html, $subtotal );
		}

		return $subtotal;
	}

	public function wdp_include_core_classes() {
		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/helpers/class-*.php' ) as $filename ) {
			include_once $filename;
		}

		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/extensions/class-*.php' ) as $filename ) {
			include_once $filename;
		}
	}

	public function wdp_include_cart_adjustments() {
		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/cart_adjustments/class-*.php' ) as $filename ) {
			include_once $filename;
		}
	}

	public function wdp_include_conditions() {
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/conditions/abstract-wdp-condition-cart-items-amount.php'; 
		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/conditions/class-*.php' ) as $filename ) {
			include_once $filename;
		}
	}

	public function wdp_include_limits() {
		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/limits/class-*.php' ) as $filename ) {
			include_once $filename;
		}
	}

	public function wdp_admin_tabs( $tabs ) {
		foreach ( glob( WC_ADP_PRO_VERSION_PATH . 'classes/admin/tabs/class-*.php' ) as $filename ) {
			include_once $filename;
		}

		$tabs['exclusive'] = new WDP_Admin_Advanced_Exclusive_Page();
		$tabs['statistics'] = new WDP_Admin_Advanced_Statistics_Page();
		$tabs['tools']      = new WDP_Admin_Advanced_Tools_Page();
		$tabs['license']    = new WDP_Admin_Advanced_License_Page();
		$tabs['options']    = new WDP_Admin_Options_Page_Pro();

		return $tabs;
	}

	public function load_frontend_assets_pro(){
		wp_enqueue_script( 'wdp_update_checkout', WC_ADP_PRO_VERSION_URL . '/assets/js/update-checkout.js' , array( 'wc-checkout' ), WC_ADP_VERSION );
	}
}

new WDP_Pro_Loader();