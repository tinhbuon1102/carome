<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Rule_Amount_Full extends WDP_Admin_Report_Rule_Amount {
	public function get_title() {
		return __( 'Rule amount data with gifts, cart discounts, fees and shipping', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function prepare_params( $params ) {
		$params['include_extra']    = true;
		$params['include_shipping'] = true;

		return $params;
	}
}