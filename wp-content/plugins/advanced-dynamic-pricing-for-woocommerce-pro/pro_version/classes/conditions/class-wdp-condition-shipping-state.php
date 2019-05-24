<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Shipping_State extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$customer_shipping_state = $cart->get_context()->get_shipping_state();

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : array();
		$comparison_method = $options[0];
		return $this->compare_value_with_list( $customer_shipping_state, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}