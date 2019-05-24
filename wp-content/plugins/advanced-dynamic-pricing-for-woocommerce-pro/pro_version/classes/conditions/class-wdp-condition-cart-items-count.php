<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Items_Count extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$qty = $cart->get_cart_qty();

		$options           = $this->data['options'];
		$comparison_value  = (float) $options[1];
		$comparison_method = $options[0];

		return $this->compare_values( $qty, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}