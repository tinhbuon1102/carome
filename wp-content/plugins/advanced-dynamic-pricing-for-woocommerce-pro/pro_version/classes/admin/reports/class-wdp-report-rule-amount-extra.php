<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Rule_Amount_Extra extends WDP_Admin_Report_Rule_Amount {
	public function get_title() {
		return __( 'Rule amount data with gifts, cart discounts and fees', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function prepare_params( $params ) {
		$params['include_extra'] = true;

		return $params;
	}
}