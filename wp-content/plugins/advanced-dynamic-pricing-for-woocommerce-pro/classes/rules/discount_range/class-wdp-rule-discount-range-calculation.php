<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Rule_Discount_Range_Calculation {
	private $calculator;

	public function __construct( $type, $discount_type, $ranges ) {
		if ( 'bulk' === $type ) {
			$this->calculator = new WDP_Rule_Bulk_Calculator();
		} elseif ( 'tier' === $type ) {
			$this->calculator = new WDP_Rule_Tier_Calculator();
		}

		$this->calculator->set_discount_type( $discount_type );
		$this->calculator->set_ranges( $ranges );
	}
	
	public function apply_price_individually() {
		$ranges = $this->calculator->get_ranges();
		foreach ( $ranges as &$range ) {
			/** @var $range WDP_Rule_Discount_Range */
			$range->apply_price_individually();
		}

		$this->calculator->set_ranges( $ranges );
	}

	/**
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 *
	 * @return array
	 */
	public function calculate_items_prices( $items ) {
		return $this->calculator->calculate_items_prices( $items );
	}

	/**
	 * @param $price float
	 * @param $qty int
	 *
	 * @return float
	 */
	public function calculate_item_price( $price, $qty = 1 ) {
		return $this->calculator->calculate_item_price( $price, $qty );
	}

	/**
	 * @param $ranges array
	 *
	 * @return WDP_Rule_Discount_Range[]
	 */
	public static function make_ranges( $ranges ) {
		$ranges_objs = array();

		foreach ( $ranges as $range ) {
			$from  = ! empty( $range['from'] ) ? $range['from'] : 1;
			$to    = ! empty( $range['to'] ) ? $range['to'] : '';
			$value =  isset( $range['value'] ) ? (float)$range['value'] : null;

			if ( null === $value ) {
				continue;
			}

			$ranges_objs[] = new WDP_Rule_Discount_Range( $from, $to, $value );
		}

		return $ranges_objs;
	}

	/**
	 * @param $items array
	 *
	 * @return WDP_Rule_Discount_Range_Calculation_Item[]
	 */
	public static function make_items( $items ) {
		$combined_items = array();

		foreach ( $items as $item ) {
			$price = $item['price'];
//			unset( $item['price'] );
			$qty = $item['quantity'];
			unset( $item['quantity'] );
			unset( $item['rules'] );
			unset( $item['original_item']['wdp_gifted'] );
			unset( $item['original_item']['wdp_rules'] );
			unset( $item['original_item']['wdp_original_price'] );
			unset( $item['original_item']['wdp_rules_for_singular'] );
			$hash = md5( json_encode( $item ) );

			if ( isset( $combined_items[ $hash ] ) ) {
				$combined_items[ $hash ]['qty'] += $qty;
			} else {
				$combined_items[ $hash ] = array(
					'qty'   => $qty,
					'price' => $price,
				);
			}
		}

		$items_objs = array();
		foreach ( $combined_items as $hash => $item ) {
			$items_objs[] = new WDP_Rule_Discount_Range_Calculation_Item( $hash, $item['price'], $item['qty'] );
		}

		return $items_objs;
	}


	/**
	 * @param $sets array
	 *
	 * @return WDP_Rule_Discount_Range_Calculation_Item[]
	 */
	public static function convert_sets_to_items( $sets ) {
		$items_objs = array();
		foreach ( $sets as $hash => $set ) {
			$price          = 0;
			foreach ( $set as $item ) {
				$price += $item['price'];
			}

			$items_objs[] = new WDP_Rule_Discount_Range_Calculation_Item( $hash, $price, 1 );
		}

		return $items_objs;
	}

}
