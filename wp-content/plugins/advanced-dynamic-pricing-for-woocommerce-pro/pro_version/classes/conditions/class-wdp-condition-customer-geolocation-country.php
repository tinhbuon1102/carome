<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Customer_Geolocation_Country extends WDP_Condition_Abstract {

	public function check( $cart ) {
		$location = WC_Geolocation::geolocate_ip();
		$country  = $location['country'];

		$options           = $this->data['options'];
		$comparison_list   = (array) $options[1];
		$comparison_method = $options[0];

		return $this->compare_value_with_list( $country, $comparison_list, $comparison_method );
	}

	public function match( $cart ) {
		return $this->check( $cart );
	}
}