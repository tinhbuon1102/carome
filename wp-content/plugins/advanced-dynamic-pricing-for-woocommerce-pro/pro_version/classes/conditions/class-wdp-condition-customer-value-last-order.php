<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_CustomerValue_Last_Order extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$user_id = $cart->get_context()->get_customer_id();
		$order = $this->get_customer_last_paid_order( $user_id );
		if ( ! $order ) {
			return false;
		}
		$order_date = strtotime( $order->get_date_created() );

		$options           = $this->data['options'];
		$comparison_value  = isset( $options[1] ) ? strtotime($options[1]) : '';
		$comparison_method = $options[0];

		return $this->compare_time_unix_format( $order_date, $comparison_value, $comparison_method );
	}

	private function get_customer_last_paid_order( $user_id ) {
		$customer_paid_orders = get_posts( array(
			'numberposts' =>  1,
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