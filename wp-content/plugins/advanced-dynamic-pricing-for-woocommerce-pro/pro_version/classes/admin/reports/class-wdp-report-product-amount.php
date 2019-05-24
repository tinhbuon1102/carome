<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Product_Amount extends WDP_Admin_Report_Rule_Amount {
	public function get_title() {
		return __( 'Product amount data', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function load_raw_data( $params ) {
		$product_amount_stats = WDP_Statistics_Db_Helper::get_products_rows_summary( $params );
		if ( empty( $product_amount_stats ) ) {
			$product_amount_stats = array();
		}

		return $product_amount_stats;
	}
}