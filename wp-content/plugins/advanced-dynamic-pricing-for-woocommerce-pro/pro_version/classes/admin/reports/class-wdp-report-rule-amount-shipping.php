<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Rule_Amount_Shipping extends WDP_Admin_Report_Rule_Amount {
	public function get_title() {
		return __( 'Rule amount shipping data', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function prepare_params( $params ) {
		$params['include_amount']        = false;
		$params['include_gifted_amount'] = false;
		$params['include_shipping']      = true;

		return parent::load_raw_data( $params );
	}
}