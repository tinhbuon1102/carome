<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Helpers_Pro {
	private static $pro_options = array(
		"update_cross_sells"    => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 1,
		),
		"update_price_with_qty" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),
		"show_striked_prices_product_page" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 1,
		),
		"show_qty_range_in_product_filter" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),
		"dont_modify_prices_in_cat_tag_page" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),
		"readonly_price_for_free_products" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),
		"cart_item_sorting" => array(
			'filter'  => FILTER_SANITIZE_STRING,
			'default' => 'no',
		),		
		"show_onsale_badge_for_variable" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),		
		"show_striked_prices_in_order" => array(
			'filter'  => FILTER_VALIDATE_BOOLEAN,
			'default' => 0,
		),
	);

	public static function get_options_with_prop( $prop ) {
		if ( ! $prop || ! is_string( $prop ) ) {
			return array();
		}

		$options = array();
		foreach ( self::$pro_options as $option => $option_data ) {
			if ( isset( $option_data[ $prop ] ) ) {
				$options[ $option ] = $option_data[ $prop ];
			}
		}

		return $options;
	}

	public static function wdp_get_default_settings( $settings ) {
		return array_merge( $settings, self::get_options_with_prop( 'default' ) );
	}
}