<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Cart_Coupons extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$coupons = $cart->get_external_coupons();

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : null;
		$comparison_method = $options[0];
		return $this->compare_lists( $coupons, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}