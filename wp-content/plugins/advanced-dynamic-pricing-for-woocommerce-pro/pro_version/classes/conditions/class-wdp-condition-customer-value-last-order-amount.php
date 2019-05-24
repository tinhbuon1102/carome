<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_CustomerValue_Last_Order_Amount extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$user_id = $cart->get_context()->get_customer_id();
		$order = wc_get_order( $this->get_customer_last_paid_order( $user_id ) );
		if ( ! $order ) {
			return false;
		}


		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? $options[1] : '';
		$comparison_method = $options[0];

		$order_amount = $order->get_total();
		return $this->compare_values( $order_amount, $comparison_value, $comparison_method );
	}

	private function get_customer_last_paid_order( $user_id ) {
		$customer_paid_orders = get_posts( array(
			'numberposts' => - 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id,
			'post_type'   => array( 'shop_order' ),
			'post_status' => array( 'wc-completed' ),
		) );
		$order = wc_get_order( array_pop($customer_paid_orders) );
		return $order;
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}