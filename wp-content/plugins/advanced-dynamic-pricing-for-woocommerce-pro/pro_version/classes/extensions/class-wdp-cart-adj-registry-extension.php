<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Adj_Registry_Extension {
	public function __construct() {
		add_filter( 'wdp_cart_adjustments', array( $this, 'add_cart_adjustments' ), 10, 1 );
	}

	public function add_cart_adjustments( $adjustments ) {
		$adjustments['discount__percentage'] = array(
			'class'    => 'WDP_Cart_Adjustment_Discount_Percentage',
			'label'    => __( 'Percentage discount', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Discount', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PLUGIN_PATH . 'views/cart_adjustments/discount.php',
		);

		$adjustments['fee__percentage'] = array(
			'class'    => 'WDP_Cart_Adjustment_Fee_Percentage',
			'label'    => __( 'Percentage fee', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Fee', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PLUGIN_PATH . 'views/cart_adjustments/fee.php',
		);

		$adjustments['shipping__amount'] = array(
			'class'    => 'WDP_Cart_Adjustment_Discount_Amount_Shipping',
			'label'    => __( 'Fixed discount shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PLUGIN_PATH . 'views/cart_adjustments/single-number.php',
		);

		$adjustments['shipping__percentage'] = array(
			'class'    => 'WDP_Cart_Adjustment_Discount_Percentage_Shipping',
			'label'    => __( 'Percentage discount shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PLUGIN_PATH . 'views/cart_adjustments/single-number.php',
		);

		return $adjustments;
	}
}

new WDP_Cart_Adj_Registry_Extension();