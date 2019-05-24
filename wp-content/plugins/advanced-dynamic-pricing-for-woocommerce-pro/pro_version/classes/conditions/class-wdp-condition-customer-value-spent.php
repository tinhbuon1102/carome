<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_CustomerValue_Spent extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$options           = $this->data['options'];
		$time = $order_count = $cart->get_context()->convert_for_strtotime( $options[0] );

		$comparison_value  = isset( $options[2] ) ? $options[2] : '';
		$comparison_method = $options[1];

		$customer_paid_orders = get_posts( array(
			'numberposts' => - 1,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( array( 'wc-completed' ) ),
			'fields'      => 'ids',
			'date_query'  => array(
				array(
					'column' => 'post_date',
					'after'  => $time,
				),
			),
		) );

		$customer_spent = 0;
		if ( $time !== false ) {
			foreach ( $customer_paid_orders as $order_id ) {
				$order = wc_get_order( $order_id );
				// TODO comparison work incorrectly for current(this) option
				$customer_spent = $customer_spent + $order->get_total();
			}
		}
		return $this->compare_values( $customer_spent, $comparison_value, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}