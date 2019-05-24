<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Products_Combination extends WDP_Condition_Abstract {
	use WDP_Product_Filtering;
	use WDP_Comparison;

	protected $has_product_dependency = true;

	public function check( $cart ) {
		$options = $this->data['options'];


		$comparison_type   = $options[0];
		$comparison_list   = (array) $options[1];
		$comparison_method = $options[2];
		$comparison_count  = (int) $options[3];

		if ( ! empty( $options[4] ) ) {
			$comparison_count = array( $comparison_count, (int) $options[4] );
		}

		if ( empty( $comparison_list ) && $this->is_correct_type( $comparison_type ) ) {
			return true;
		}

		$products_qty = array();
		foreach ( $comparison_list as $item ) {
			$products_qty[ $item ] = 0;
		}

		foreach ( WC()->cart->get_cart() as $item_key => $item ) {
			$checked = $this->check_product_suitability(
				$item['product_id'],
				'products',
				$comparison_list,
				'in_list'
			);

			if ( $checked && ! empty( $item['quantity'] ) ) {
				if ( isset( $products_qty[ $item['product_id'] ] ) ) {
					$products_qty[ $item['product_id'] ] += $item['quantity'];
				}
			}
		}

		return ! empty( $products_qty ) ? call_user_func( array(
			$this,
			"check_comparison_type_{$comparison_type}",
		), $products_qty, $comparison_count, $comparison_method ) : false;
	}

	private function is_correct_type( $type ) {
		return ! empty( $type ) && in_array( $type, array( 'combine', 'any', 'each' ) );
	}

	private function check_comparison_type_combine( $products_qty, $comparison_count, $method ) {
		$total_count = array_sum( array_values( $products_qty ) );

		return $this->compare_values( $total_count, $comparison_count, $method );
	}

	private function check_comparison_type_any( $products_qty, $comparison_count, $method ) {
		$suitable_products = array_filter( $products_qty, function ( $product_qty ) use ( $comparison_count, $method ) {
			return $this->compare_values( $product_qty, $comparison_count, $method );
		} );

		return ! empty( $suitable_products );
	}

	private function check_comparison_type_each( $products_qty, $comparison_count, $method ) {
		$suitable_products = array_filter( $products_qty, function ( $product_qty ) use ( $comparison_count, $method ) {
			return $this->compare_values( $product_qty, $comparison_count, $method );
		} );

		return count( $suitable_products ) === count( $products_qty );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}