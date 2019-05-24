<?php

class WDP_Frontend_pro {

	private $last_onsale_product_price;
	private $last_regular_product_price;

	public function __construct() {
		$options = WDP_Helpers::get_settings();

		add_action( 'wp_print_styles', array( $this, 'load_frontend_assets' ) );

		if ( $options['update_cross_sells'] ) {
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'woocommerce_add_to_cart_fragments' ), 10, 2 );
		}

		add_filter( 'wdp_woocommerce_variable_discounted_price_html', array( $this, 'wdp_woocommerce_discounted_price_html', ), 10, 4 );
		add_filter( 'wdp_woocommerce_discounted_price_html', array( $this, 'wdp_woocommerce_discounted_price_html', ), 10, 4 );
		add_filter( 'wdp_modify_price_html', array( $this, 'wdp_modify_price_html', ), 10, 4 );

		if ( ! empty( $options['readonly_price_for_free_products'] ) ) {
			add_filter( 'woocommerce_cart_item_quantity', function ( $product_quantity, $cart_item_key, $cart_item ) {
				if ( ! empty( $cart_item['wdp_gifted'] ) ) {
					$product_quantity = sprintf( '%1$s <input type="hidden" name="cart[%2$s][qty]" value="%1$s" />', $cart_item['quantity'], $cart_item_key );
				}

				return $product_quantity;
			}, 10, 3 );
		}

		add_action( 'wdp_after_apply_to_wc_cart', array( $this, 'apply_to_wc_cart_option_cart_item_sorting' ), 10, 1 );

		if ( $options['show_onsale_badge'] && $options['show_onsale_badge_for_variable'] ) {
			add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'woocommerce_product_variation_get_sale_price' ), 100, 2 );
			add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'woocommerce_product_variation_get_regular_price' ), 100, 2 );
			add_filter( 'wdp_product_variation_is_on_sale', function ( $onsale, $calculated_html, $product ) use ( $options ) {
				return (boolean) $calculated_html;
			}, 10, 3 );
		}

		/** Hooks to supply format striked price in order item */
		add_action( 'woocommerce_checkout_create_order_line_item_object', array( $this, 'save_initial_price_to_order_item' ), 10, 4 );
		add_filter( 'woocommerce_calculate_item_totals_taxes', array( $this, 'calculate_taxes_for_original_price' ), 10, 3 );
	}
	
	/**
	 * @param $total_taxes
	 * @param $item
	 * @param $totals WC_Cart_Totals
	 *
	 * @return mixed
	 */
	public function calculate_taxes_for_original_price( $total_taxes, $item, $totals ) {
		if ( ! empty( $item->wdp_original_price ) ) {
			WC()->cart->cart_contents[ $item->key ]['wdp_original_price_tax'] = WC_Tax::calc_tax( $item->wdp_original_price, $item->tax_rates, $item->price_includes_tax );
		}

		return $total_taxes;
	}

	/**
	 * @param $item WC_Order_Item_Product
	 * @param $cart_item_key string
	 * @param $values array
	 * @param $order WC_Order
	 *
	 * @return WC_Order_Item_Product
	 */
	public function save_initial_price_to_order_item( $item, $cart_item_key, $values, $order ) {
		if ( ! empty( $values['wdp_original_price'] ) ) {
			$original_price = (float) $values['wdp_original_price'];
			$item->add_meta_data( '_wdp_initial_cost', $original_price );
			$original_taxes = WC_Tax::calc_tax( $original_price, WC_Tax::get_rates( $item->get_tax_class(), new WC_Customer( $order->get_customer_id() ) ), false );
			if ( count( $original_taxes ) ) {
				$item->add_meta_data( '_wdp_initial_cost_tax', reset( $original_taxes ) );
			}
		}

		return $item;
	}

	public function woocommerce_product_variation_get_sale_price( $value, $product ) {
		//cached?
		if ( ! empty( $this->last_onsale_product_id ) AND $product->get_id() == $this->last_onsale_product_id ) {
			return $this->last_onsale_product_price;
		}
		//calculate
		$prices = WDP_Frontend::get_initial_and_discounted_price( $product, 1 );
		if ( ! empty( $prices['price'] ) ) {
			$value = $prices['price'];
		}
		//cache
		$this->last_onsale_product_id    = $product->get_id();
		$this->last_onsale_product_price = $value;

		return $value;
	}

	public function woocommerce_product_variation_get_regular_price( $value, $product ) {
		//cached?
		if ( ! empty( $this->last_regular_product_id ) AND $product->get_id() == $this->last_regular_product_id ) {
			return $this->last_regular_product_price;
		}
		//calculate
		$prices = WDP_Frontend::get_initial_and_discounted_price( $product, 1 );
		if ( ! empty( $prices['initial_price'] ) ) {
			$value = $prices['initial_price'];
		}
		//cache
		$this->last_regular_product_id    = $product->get_id();
		$this->last_regular_product_price = $value;

		return $value;
	}

	public function wdp_woocommerce_discounted_price_html( $price_html, $initial_price, $price, $product ) {
		// option 'Show Striked Prices at Product page'
		if ( ! $this->is_show_striked_price_in_product_page() ) {
			$price_html = wc_price( $price ) . $product->get_price_suffix();
		}

		return $price_html;
	}

	public function wdp_modify_price_html( $modify, $price_html, $product, $qty ) {
		if ( WDP_Frontend::is_catalog_view() ) {
			$options = WDP_Helpers::get_settings();
			$modify = ! $options['dont_modify_prices_in_cat_tag_page'];
		}

		return $modify;
	}

	private function is_show_striked_price_in_product_page() {
		$options = WDP_Helpers::get_settings();
		$is_show = $options['show_striked_prices_product_page'] && ! $options['do_not_modify_price_at_product_page'] ;

		return is_product() || WDP_Frontend::is_nopriv_ajax_processing() ? $is_show : true;
	}

	public function load_frontend_assets() {
		$options    = WDP_Helpers::get_settings();

		if ( is_product() || woocommerce_product_loop() ) {
			wp_enqueue_script( 'wdp_deals_pro', WC_ADP_PRO_VERSION_URL . '/assets/js/frontend-pro.js', array(), WC_ADP_VERSION );
		}

		$script_data = array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'update_price_with_qty' => wc_string_to_bool( $options['update_price_with_qty'] ),
			'js_init_trigger'       => apply_filters( 'wdp_bulk_table_js_init_trigger', "" ),
		);

		wp_localize_script( 'wdp_deals_pro', 'script_data', $script_data );
	}

	public function woocommerce_add_to_cart_fragments( $fragments ) {
		/**
		 * Fix incorrect add-to-cart url in cross sells elements.
		 * We need to remove "wc-ajax" argument because WC_Product childs in method add_to_cart_url() use
		 * add_query_arg() with current url.
		 * Do not forget to set current url to cart_url.
		 */
		$_SERVER['REQUEST_URI'] = remove_query_arg( 'wc-ajax', wc_get_cart_url() );

		ob_start();
		woocommerce_cross_sell_display();
		$text = trim( ob_get_clean() );
		if( empty($text) )
			$text = '<div class="cross-sells"></div>';
		$fragments['div.cross-sells'] = $text;
		return $fragments;
	}

	/**
	 * @param $cart WDP_Cart
	 */
	public function apply_to_wc_cart_option_cart_item_sorting( $cart ) {
		$sort_callback = null;
		if ( 'free_product_at_bottom' === $cart->get_context()->get_cart_item_sorting() ) {
			$sort_callback = function ( $first, $second ) {
				$all_steps = array(
					array( ! empty( $first['wdp_gifted'] ), ! empty( $second['wdp_gifted'] ), 'boolean', )
				);

				return $this->recursive_compare_values( $all_steps );
			};
		} elseif ( 'group_by_product' === $cart->get_context()->get_cart_item_sorting() ) {
			$sort_callback = function ( $first, $second ) {
				$all_steps = array(
					array( (int) $first['product_id'], (int) $second['product_id'], 'numeric' ),
					array( ! empty( $first['wdp_rules'] ), ! empty( $second['wdp_rules'] ), 'boolean' ),
					array( ! empty( $first['wdp_gifted'] ), ! empty( $second['wdp_gifted'] ), 'boolean' ),
				);

				return $this->recursive_compare_values( $all_steps );
			};
		} elseif ( 'group_by_variation' === $cart->get_context()->get_cart_item_sorting() ) {
			$sort_callback = function ( $first, $second ) {
				$all_steps = array(
					array( (int) $first['product_id'], (int) $second['product_id'], 'numeric' ),
					array( (int) $first['variation_id'], (int) $second['variation_id'], 'numeric' ),
					array( ! empty( $first['wdp_rules'] ), ! empty( $second['wdp_rules'] ), 'boolean' ),
					array( ! empty( $first['wdp_gifted'] ), ! empty( $second['wdp_gifted'] ), 'boolean' ),
				);

				return $this->recursive_compare_values( $all_steps );
			};
		}

		if ( $sort_callback ) {
			$cart_contents = WC()->cart->get_cart_contents();
			usort( $cart_contents, $sort_callback );
			WC()->cart->set_cart_contents( $cart_contents );
		}
	}

	private function recursive_compare_values( $all_steps, $step = 0 ) {
		list( $first_value, $second_value, $type ) = $all_steps[ $step ];

		if ( 'boolean' === $type ) {
			if ( $first_value xor $second_value ) {
				return $first_value && ! $second_value ? 1 : - 1;
			}
		} elseif ( 'numeric' === $type ) {
			if ( $first_value !== $second_value ) {
				return $first_value > $second_value ? 1 : - 1;
			}
		} else {
			return 0;
		}

		return isset( $all_steps[ $step + 1 ] ) ? $this->recursive_compare_values( $all_steps, $step + 1 ) : 0;
	}
}