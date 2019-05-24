<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

interface WDP_Rule_Discount_Range_Calculator {
	/**
	 * @var $discount_type string
	 */
	public function set_discount_type( $discount_type );

	/**
	 * @var $ranges WDP_Rule_Discount_Range[]
	 */
	public function set_ranges( $ranges );

	/**
	 * @return WDP_Rule_Discount_Range[]
	 */
	public function get_ranges();

	/**
	 * @param $price float
	 * @param $qty int
	 *
	 * @return float
	 */
	public function calculate_item_price( $price, $qty = 1 );

	/**
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 *
	 * @return array
	 */
	public function calculate_items_prices( $items );
}