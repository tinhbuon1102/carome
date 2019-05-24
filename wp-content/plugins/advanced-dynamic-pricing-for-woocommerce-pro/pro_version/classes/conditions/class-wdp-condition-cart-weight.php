<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Weight extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$weight = $cart->get_cart_contents_weight();

		$options           = $this->data['options'];
		$comparison_value  = (float) $options[1];
		$comparison_method = $options[0];

		return $this->compare_values( $weight, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}