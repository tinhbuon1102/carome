<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Items_Amount_Abstract extends WDP_Condition_Abstract {
	use WDP_Product_Filtering;

	protected $used_items;
	protected $has_product_dependency = true;
	protected $filter_type = '';

	public function check( $cart ) {
		$this->used_items = array();

		$options           = $this->data['options'];
		$comparison_list   = (array) $options[0];
		$comparison_method = $options[1];
		$comparison_amount   = (int) $options[2];

		if ( empty( $comparison_list ) ) {
			return true;
		}

		$amount   = 0;
		$items = $cart->get_cart_items();
		foreach ( $items as $item_key => $item ) {
			$checked = $this->check_product_suitability(
				$item['product_id'],
				$this->filter_type,
				$comparison_list,
				'in_list'
			);
			if ( $checked ) {
				$amount                += $item['total'];
				$this->used_items[] = $item_key;

				if ( $this->compare_values($amount, $comparison_amount, $comparison_method) ) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function get_involved_cart_items() {
		return $this->used_items;
	}

	public function match( $cart ) {
		return $this->check($cart);
	}

	//TODO public function get_product_dependency() {	}
}