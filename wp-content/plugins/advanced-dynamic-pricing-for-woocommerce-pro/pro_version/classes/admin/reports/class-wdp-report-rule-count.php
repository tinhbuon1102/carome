<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Report_Rule_Count extends WDP_Admin_Report_Rule_Amount {
	public function get_title() {
		return __( 'Rule number of usages', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function get_subtitle() {
		return __( 'TOP 5', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	protected function load_raw_data( $params ) {
		$rule_count_stats = WDP_Statistics_Db_Helper::get_rules_count_rows( $params );
		if ( empty( $rule_count_stats ) ) {
			$rule_count_stats = array();
		}

		return $rule_count_stats;
	}
}