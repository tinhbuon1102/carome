<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Customer_Users extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$user_id = $cart->get_context()->get_customer_id();

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : array();
		$comparison_method = $options[0];
		return $this->compare_value_with_list( $user_id, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}