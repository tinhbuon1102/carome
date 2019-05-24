<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Rule_Tier_Calculator implements WDP_Rule_Discount_Range_Calculator {
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
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 *
	 * @return array
	 */
	public function calculate_items_prices( $items ) {
		if ( ! $items ) {
			return array();
		}

		foreach ( $this->ranges as $range ) {
			$items = $this->process_range( $items, $range );
		}

		return $items;
	}

	/**
	 * @param $price float
	 * @param $qty int
	 *
	 * @return float
	 */
	public function calculate_item_price( $price, $qty = 1 ) {
		$items = array( new WDP_Rule_Discount_Range_Calculation_Item( '', $price, $qty ) );
		foreach ( $this->ranges as $range ) {
			$items = $this->process_range( $items, $range );
		}

		return array_sum( array_map( function ( $item ) {
			/** @var $item WDP_Rule_Discount_Range_Calculation_Item */
			return $item->get_total_price();
		}, $items ) );
	}

	/**
	 * @param $items WDP_Rule_Discount_Range_Calculation_Item[]
	 * @param $range WDP_Rule_Discount_Range
	 *
	 * @return array
	 */
	private function process_range( $items, $range ) {
		$processed_qty             = 1;
		$new_items                 = array();
		$index_of_items_to_process = array();

		foreach ( $items as $item ) {
			if ( $item->is_processed() ) {
				$new_items[]   = $item;
				$processed_qty += $item->get_qty();
				continue;
			}

			/** @var $item WDP_Rule_Discount_Range_Calculation_Item */
			if ( $range->is_less( $processed_qty ) ) {
				if ( $range->is_in( $processed_qty + $item->get_qty() ) ) {
					$require_qty = $processed_qty + $item->get_qty() - $range->get_from();

					if ( $require_qty > 0 ) {
						$new_items[]                 = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $require_qty );
						$index_of_items_to_process[] = count( $new_items ) - 1;
						$processed_qty               += $require_qty;
					}

					if ( ( $item->get_qty() - $require_qty ) > 0 ) {
						$new_items[] = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $item->get_qty() - $require_qty );
						$processed_qty += $item->get_qty() - $require_qty;
					}
				} elseif ( $range->is_more( $processed_qty + $item->get_qty() ) ) {
					$require_qty = $range->get_qty();

					if ( $require_qty > 0 ) {
						$new_items[]                 = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $require_qty );
						$index_of_items_to_process[] = count( $new_items ) - 1;
						$processed_qty               += $require_qty;
					}

					if ( ( $item->get_qty() - $require_qty ) > 0 ) {
						$new_items[] = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $item->get_qty() - $require_qty );
						$processed_qty += $item->get_qty() - $require_qty;
					}

				} else {
					$new_items[]   = $item;
					$processed_qty += $item->get_qty();
				}
			} elseif ( $range->is_in( $processed_qty ) ) {
				$require_qty = $range->get_to() + 1 - $processed_qty;
				$require_qty = $require_qty < $item->get_qty() ? $require_qty : $item->get_qty();

				if ( $require_qty > 0 ) {
					$new_items[]                 = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $require_qty );
					$index_of_items_to_process[] = count( $new_items ) - 1;
					$processed_qty               += $require_qty;
				}

				if ( ( $item->get_qty() - $require_qty ) > 0 ) {
					$new_items[] = new WDP_Rule_Discount_Range_Calculation_Item( $item->get_hash(), $item->get_price(), $item->get_qty() - $require_qty );
					$processed_qty += $item->get_qty() - $require_qty;
				}

			} elseif ( $range->is_more( $processed_qty ) ) {
				$new_items[] = $item;
				$processed_qty += $item->get_qty();
			}
		}

		$prices = array();
		$total_qty = 0;
		foreach ( $index_of_items_to_process as $index ) {
			$item                        = $new_items[ $index ];
			$prices[ $item->get_hash() ] = $item->get_total_price();
			$total_qty += $item->get_qty();
		}

		if ( $range->is_apply_to_total_price() ) {
			$prices = $this->calculate_prices(
				$prices,
				$this->discount_type,
				$range->get_value()
			);
		} else {
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

		foreach ( $index_of_items_to_process as $index ) {
			$item = $new_items[ $index ];
			$new_items[ $index ]->set_singular_price( $prices[ $item->get_hash() ] / $item->get_qty() );
			$new_items[ $index ]->mark_as_processed();
		}

		return $new_items;
	}

}