<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Payment_Method extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$payment_method = $cart->get_context()->get_payment_method();
		if ( !$payment_method ) {
			return false;
		}

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : array();
		$comparison_method = $options[0];

		return $this->compare_value_with_list( $payment_method, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}