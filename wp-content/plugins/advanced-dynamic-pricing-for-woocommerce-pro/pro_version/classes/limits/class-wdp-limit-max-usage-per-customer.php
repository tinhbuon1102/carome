<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Limit_Max_Usage_Per_Customer implements WDP_Limit {
	private $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function check( $cart, $rule_id ) {
		// TODO: Implement check() method.
		throw new Exception( 'Implement check() method.' );
	}
}