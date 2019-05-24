<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Product_Gifted_Qty extends WDP_Admin_Report_Product_Amount {
	public function get_title() {
		return __( 'Product number of gifts', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function prepare_params( $params ) {
		$params['include_amount']        = false;
		$params['include_gifted_amount'] = false;
		$params['include_gifted_qty']    = true;

		return $params;
	}
}