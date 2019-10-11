<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class WDP_Condition_Cart_Items_Abstract extends WDP_Condition_Abstract {
	protected $used_items;
	protected $has_product_dependency = true;
	protected $filter_type = '';

	/**
	 * @param WDP_Cart $cart
	 *
	 * @return bool
	 */
	public function check( $cart ) {
		$this->used_items = array();

		$options                      = $this->data['options'];
		$comparison_qty               = (float) $options[0];
		$comparison_qty_finish_exists = isset( $options[3] ) ? "" !== $options[3] : false;
		$comparison_qty_finish        = $comparison_qty_finish_exists ? (float) $options[3] : 0.0;
		$comparison_method            = $options[1];
		$comparison_list              = (array) $options[2];

		if ( empty( $comparison_qty ) ) {
			return true;
		}

		$qty   = 0;
		$product_filtering = WDP_Loader::get_product_filtering_class();
		$product_filtering->prepare( $this->filter_type, $comparison_list, $comparison_method );

		foreach ( $cart->get_items() as $item_key => $item ) {
			/**
			 * @var $item WDP_Cart_Item
			 */
			$item_data = $cart->get_item_data_by_hash( $item->get_hash() );
			$checked   = $product_filtering->check_product_suitability( $item_data['data'] );

			if ( $checked ) {
				$qty                += $item->get_qty();
//				$this->used_items[] = $item_key;
			}
		}

		return $comparison_qty_finish_exists ? ( $comparison_qty <= $qty ) && ( $qty <= $comparison_qty_finish ) : $comparison_qty <= $qty;
	}

	public function get_involved_cart_items() {
		return $this->used_items;
	}

	public function match( $cart ) {
		return $this->check($cart);
	}

	public function get_product_dependency() {
		return array(
			'qty'    => $this->data['options'][0],
			'type'   => $this->filter_type,
			'method' => $this->data['options'][1],
			'value'  => (array) $this->data['options'][2],
		);
	}
}