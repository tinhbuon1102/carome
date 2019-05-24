<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Shipping_Method extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$shipping_methods_from_context = $cart->get_context()->get_shipping_methods();
		if ( !$shipping_methods_from_context ) {
			return false;
		}

		$shipping_methods = array();
		foreach ( $shipping_methods_from_context as $shipping_method) {
			$shipping_methods[] = explode(':', $shipping_method)[0];
		}

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : array();
		$comparison_method = $options[0];

		return $this->compare_lists( $shipping_methods, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}