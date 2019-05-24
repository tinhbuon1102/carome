<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Customer_Capability extends WDP_Condition_Abstract {

	public function check( $cart ) {

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : array();
		$comparison_method = $options[0];

		$user_id = $cart->get_context()->get_customer_id();
		$user_data = get_userdata( $user_id );
		if (!$user_data) {
			return false;
		}
		$customer_capabilities = array_keys( $user_data->allcaps );

		return $this->compare_lists($customer_capabilities, $comparison_value, $comparison_method);
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}