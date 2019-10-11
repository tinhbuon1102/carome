<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class WDP_Rule_Range_Adjustments_Qty_Based_Sets implements WDP_Rule_Range_Adjustments_Qty_Based {
	private $key = 'sets';
	private $rule_id;

	public function __construct( $rule_id ) {
		$this->rule_id = $rule_id;
	}

	public function get_key() {
		return $this->key;
	}

	public static function get_label() {
		return __( 'Qty based on sets', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	/**
	 * @param $bulk_calc WDP_Rule_Discount_Range_Calculation
	 * @param $cart WDP_Cart
	 * @param $set_collection WDP_Cart_Set_Collection
	 *
	 * @return WDP_Cart_Set_Collection|WDP_Cart_Items_Collection
	 */
	public function process( $bulk_calc, $cart, $set_collection ) {
		$qty = $set_collection->get_total_sets_qty();
		if ( $qty < 1 ) {
			return $set_collection;
		}

		$calculator         = new WDP_Price_Calculator();
		$new_set_collection = new WDP_Cart_Set_Collection();

		$range_calculation_items = array_map( function ( $set ) {
			/**
			 * @var $set WDP_Cart_Set
			 */
			return new WDP_Rule_Discount_Range_Calculation_Item( $set->get_hash(), $set->get_qty() );
		}, $set_collection->get_sets() );

		foreach ( $bulk_calc->calculate_items_discounts( $range_calculation_items, $qty ) as $item ) {
			/**
			 * @var $item WDP_Rule_Discount_Range_Calculation_Item
			 */
			$new_set = $set_collection->get_set_by_hash( $item->get_initial_hash() );
			$new_set->set_qty( $item->get_qty() );

			if ( $item->has_discount() ) {
				$calculator->set_type( $item->get_discount_type() )->set_value( $item->get_discount_value() );
				$new_set = $calculator->calculate_price_for_set( $new_set );
			}

			$new_set_collection->add( $new_set );
		}

		return $new_set_collection;
	}

	public function apply_additional_data( $data ) {
		return;
	}
}