<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Customer_Is_First_Order extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$user_id = $cart->get_context()->get_customer_id();
		$order = wc_get_customer_last_order( $user_id );

		$options           = $this->data['options'];
		$comparison_method = $options['0'];

		if ( 'yes' == $comparison_method ) {
			return !$order;
		} elseif ( 'no' == $comparison_method ) {
			return $order;
		}

		return false;
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}