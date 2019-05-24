<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Limit_Registry_Extension {
	public function __construct() {
		add_filter( 'wdp_limits', array( $this, 'add_limits' ), 10, 1 );
	}

	public function add_limits( $conditions ) {
//		$conditions['max_usage_per_customer'] = array(
//			'class'    => 'WDP_Limit_Max_Usage_Per_Customer',
//			'label'    => __( 'Max usage per customer', 'advanced-dynamic-pricing-for-woocommerce' ),
//			'group'    => __( 'Usage restrictions', 'advanced-dynamic-pricing-for-woocommerce' ),
//			'template' => WC_ADP_PLUGIN_PATH . 'views/limits/max-usage-per-customer.php',
//		);

		return $conditions;
	}
}

new WDP_Limit_Registry_Extension();