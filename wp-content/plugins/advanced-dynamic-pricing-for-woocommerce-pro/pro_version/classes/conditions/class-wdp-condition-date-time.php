<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Date_Time extends WDP_Condition_Abstract {

	public function check( $cart ) {

		$date_time = $cart->get_context()->time();

		$options              = $this->data['options'];
		$comparison_date_time = strtotime( $options[1] );
		$comparison_method = $options[0];

		return $this->compare_time_unix_format( $date_time, $comparison_date_time, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check($cart);
	}
}