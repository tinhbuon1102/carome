<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Rule_Range_Adjustments_Bulk_Qty_Based_Product_Selected_Categories extends WDP_Rule_Range_Adjustments_Qty_Based_Product_Selected_Categories {
	public static function get_available_discount_types() {
		return WDP_Rule_Range_Discount_Types::get_all_types();
	}
}