<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Adjustment_Discount_Percentage implements WDP_Cart_Adjustment {
	private $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function apply_to_cart( $cart, $rule_id ) {
		$options = $this->data['options'];

		$cart->add_coupon_percentage( (float) $options[0], $rule_id, $options[1] );

		return true;
	}
}