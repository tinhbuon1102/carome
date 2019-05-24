<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Rule_Bulk_Calculator implements WDP_Rule_Discount_Range_Calculator {
	use WDP_Price_Calc;
	/**
	 * @var array WDP_Rule_Discount_Range[]
	 */
	private $ranges = array();

	/**
	 * @var string
	 */
	private $discount_type;

	public function set_discount_type( $discount_type ) {
		$this->discount_type = $discount_type;
	}

	/**
	 * @var $ranges WDP_Rule_Discount_Range[]
	 */
	public function set_ranges( $ranges ) {
		$this->ranges = $ranges;
	}

	/**
	 * @raturn $ranges WDP_Rule_Discount_Range[]
	 */
	public function get_ranges() {
		return $this->ranges;
	}

	/**
	 * @param $price float
	 * @param $qty int
	 *
	 * @return float
	 */
	public function calculate_item_price( $price, $qty = 1 ) {
		$prices = array( $price * $qty );

		foreach ( $this->ranges as $range ) {
			if ( $range->is_in( $qty ) ) {
				$prices = $this->calculate_bulk_prices( $prices, $range, $qty );

				break;
			}
		}

		$total_price = reset( $prices );

		return $total_price;
	}

	/**
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 *
	 * @return array
	 */
	public function calculate_items_prices( $items ) {
		if ( ! $items ) {
			return array();
		}

		$prices    = array();
		$total_qty = 0;

		foreach ( $items as $item ) {
			/** @var $item WDP_Rule_Discount_Range_Calculation_Item */
			$prices[ $item->get_hash() ] = $item->get_total_price();
			$total_qty                   += $item->get_qty();
		}

		// duplicated hashes
		if ( count( $prices ) !== count( $items ) ) {
			return array();
		}

		foreach ( $this->ranges as $range ) {
			if ( $range->is_in( $total_qty ) ) {
				$prices = $this->calculate_bulk_prices( $prices, $range, $total_qty );

				break;
			}
		}

		$result = array();
		foreach ( $items as $item ) {
			if ( isset( $prices[ $item->get_hash() ] ) ) {
				$item->set_singular_price( $prices[ $item->get_hash() ] / $item->get_qty() );
				$result[] = $item->get_as_flat();
			}
		}

		return $result;
	}

	/**
	 * @param $prices array
	 * @param $range WDP_Rule_Discount_Range
	 * @param $total_qty int
	 *
	 * @return float[]
	 */
	private function calculate_bulk_prices( $prices, $range, $total_qty = null ) {
		if ( $range->is_apply_to_total_price() ) {
			$prices = $this->calculate_prices(
				$prices,
				$this->discount_type,
				$range->get_value()
			);
		} else {
			if ( null !== $total_qty ) {
				foreach ( $prices as $hash => &$price ) {
					$new_price = $this->calculate_prices(
						array( $price / $total_qty ),
						$this->discount_type,
						$range->get_value()
					);
					$price     = count( $new_price ) ? reset( $new_price ) : $price;
					$price     = $price * $total_qty;
				}
			}
		}

		return $prices;
	}

}