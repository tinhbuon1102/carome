<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Product_Categories_Amount extends WDP_Condition_Cart_Items_Amount_Abstract {
	protected $filter_type = 'product_categories';
}