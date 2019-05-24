<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Product_Taxonomies_Amount extends WDP_Condition_Cart_Items_Amount_Abstract {
	public function __construct( array $data ) {
		parent::__construct( $data );
		$this->filter_type = str_replace( 'amount_', '', $data['type'] );
	}
}