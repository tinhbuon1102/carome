<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Options_Page_Pro extends WDP_Admin_Options_Page {
	private $pro_sections = array();
	private $pro_templates;

	public function __construct() {
		$this->pro_sections = array(
			"rules"         => array(
				'templates' => array(
					'show_qty_range_in_product_filter',
				),
			),
			"category_page" => array(
				'templates' => array(
					'dont_modify_prices_in_cat_tag_page',
				),
			),
			"product_page"  => array(
				'templates' => array(
					'update_price_with_qty',
					'show_striked_prices_product_page',
				),
			),
			"cart"          => array(
				'templates' => array(
					'update_cross_sells',
					'readonly_price_for_free_products',
					'cart_item_sorting',
				),
			),
			"calculation" => array(
				'templates' => array(
					'show_onsale_badge_for_variable',
				),
			),
			"order"         => array(
				'title'     => __( "Order", 'advanced-dynamic-pricing-for-woocommerce' ),
				'templates' => array(
					'show_striked_prices_in_order'
				),
			),
		);

		parent::__construct();
		$this->pro_templates = $this->get_pro_templates();
	}

	protected function get_validate_filters() {
		return array_merge( parent::get_validate_filters(), WDP_Helpers_Pro::get_options_with_prop( 'filter' ) );
	}

	protected function get_sections() {
		$sections = parent::get_sections();
		foreach ( $this->pro_sections as $section => $section_data ) {
			if ( isset( $sections[ $section ] ) ) {
				$sections[$section]['templates'] = array_merge( $sections[ $section ]['templates'], $section_data['templates'] );
			} else {
				$sections[$section] = $section_data;
			}
		}

		return $sections;
	}

	protected function render_options_template( $template, $data ) {
		if ( in_array( $template, $this->pro_templates ) ) {
			$this->render_template( WC_ADP_PRO_VERSION_PATH . "views/tabs/options/{$template}.php", $data );
		} else {
			parent::render_options_template( $template, $data );
		}
	}

	private function get_pro_templates() {
		$templates = array();
		foreach ( $this->pro_sections as $section => $section_data ) {
			if ( ! empty( $section_data['templates'] ) && is_array( $section_data['templates'] ) ) {
				$templates = array_merge( $templates, $section_data['templates'] );
			}
		}

		return $templates;
	}

}
