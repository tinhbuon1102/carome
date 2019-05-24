<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Cart_Adjustment_Discount_Percentage_Shipping implements WDP_Cart_Adjustment {
	private $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function apply_to_cart( $cart, $rule_id ) {
		$cart->add_shipping_percentage( (float)$this->data['options'][0], $rule_id );

		return true;
	}
}