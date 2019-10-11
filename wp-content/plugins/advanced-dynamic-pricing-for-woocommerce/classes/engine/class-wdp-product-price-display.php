<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Price_Display {
	private $options;

	/**
	 * @var WDP_Cart
	 */
	private $cart;

	/**
	 * @var WDP_Cart_Calculator
	 */
	private $calc;

	/**
	 * @var WDP_Product[]
	 */
	private $cached_products = array();

	/**
	 * WDP_Price_Display constructor.
	 *
	 */
	public function __construct() {
		$this->options = WDP_Helpers::get_settings();
	}

	public function is_enabled() {
		return ! ( ( is_admin() && ! wp_doing_ajax() ) || $this->is_request_to_rest_api() || defined( 'DOING_CRON' ) );
	}

	public function get_option( $option, $default = false ) {
		return isset( $this->options[ $option ] ) ? $this->options[ $option ] : $default;
	}

	public function init_hooks() {
		// for prices in catalog and single product mode
		add_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10, 2 );
//		add_filter( 'woocommerce_variable_price_html', array( $this, 'hook_get_price_html' ), 100, 2 );

		if ( $this->get_option('show_onsale_badge') && ! $this->get_option('do_not_modify_price_at_product_page') ) {
			add_filter( 'woocommerce_product_is_on_sale', array( $this, 'hook_product_is_on_sale' ), 10, 2 );
			add_filter( 'woocommerce_product_get_sale_price', array( $this, 'hook_product_get_sale_price' ), 100, 2 );
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'hook_product_get_regular_price' ), 100, 2 );
		}

		if ( $this->get_option( 'show_cross_out_subtotal_in_cart_totals' ) ) {
			add_filter( 'woocommerce_cart_subtotal', array( $this, 'hook_cart_subtotal' ), 10, 3 );
		}

		// strike prices for items
		if ( $this->get_option( 'show_striked_prices' ) ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'woocommerce_cart_item_price_and_price_subtotal' ), 10, 3 );
			add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'woocommerce_cart_item_price_and_price_subtotal' ), 10, 3 );
		}

		do_action( 'wdp_price_display_init_hooks', $this );
	}

	public function remove_price_hooks() {
		remove_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10 );
		remove_filter( 'woocommerce_product_is_on_sale', array( $this, 'hook_product_is_on_sale' ), 10 );
		remove_filter( 'woocommerce_product_get_sale_price', array( $this, 'hook_product_get_sale_price' ), 100 );
		remove_filter( 'woocommerce_product_get_regular_price', array( $this, 'hook_product_get_regular_price' ), 100 );

		do_action( 'wdp_price_display_remove_hooks', $this );
	}

	public function get_cached_products() {
		return $this->cached_products;
	}

	/**
	 * @param $cart_subtotal_html string
	 * @param $compound boolean
	 * @param $wc_cart WC_Cart
	 *
	 * @return string
	 */
	public function hook_cart_subtotal( $cart_subtotal_html, $compound, $wc_cart ) {
		if ( ! is_cart() ) {
			return $cart_subtotal_html;
		}

		if ( ! $compound ) {
			$cart_subtotal_suffix = '';

			$totals = $wc_cart->get_totals();

			if ( isset( $totals['wdp_initial_totals'] ) ) {
				$initial_cart_subtotal     = $totals['wdp_initial_totals']['subtotal'];
				$initial_cart_subtotal_tax = $totals['wdp_initial_totals']['subtotal_tax'];
			} else {
				return $cart_subtotal_html;
			}

			if ( $wc_cart->display_prices_including_tax() ) {
				$initial_cart_subtotal += $initial_cart_subtotal_tax;
				$cart_subtotal         = $wc_cart->get_subtotal() + $wc_cart->get_subtotal_tax();

				if ( $wc_cart->get_subtotal_tax() > 0 && ! wc_prices_include_tax() ) {
					$cart_subtotal_suffix = ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
				}
			} else {
				$cart_subtotal = $wc_cart->get_subtotal();

				if ( $wc_cart->get_subtotal_tax() > 0 && wc_prices_include_tax() ) {
					$cart_subtotal_suffix = ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
				}
			}

			$initial_cart_subtotal = apply_filters( 'wdp_initial_cart_subtotal', $initial_cart_subtotal, $wc_cart );
			$cart_subtotal         = apply_filters( 'wdp_cart_subtotal', $cart_subtotal, $wc_cart );

			if ( $cart_subtotal < $initial_cart_subtotal ) {
				$cart_subtotal_html = wc_format_sale_price( $initial_cart_subtotal, $cart_subtotal ) . $cart_subtotal_suffix;
			}
		}

		return $cart_subtotal_html;
	}

	/**
	 * Hook for create calculator and cart for frontend price calculation.
	 * We must do it as late as possible in wp_loaded hook for including (e.g.) items which added during POST.
	 */
	public function apply_cart_and_calc() {
		$this->apply_calc();
		$this->apply_cart();
	}

	public function apply_calc() {
		$rule_collection = WDP_Rules_Registry::get_instance()->get_active_rules();
		$this->calc      = new WDP_Cart_Calculator( $rule_collection );
	}

	public function apply_cart( $context = 'view' ) {
		if ( ! did_action( 'wp_loaded' ) ) {
			wc_doing_it_wrong( __FUNCTION__, __( 'Apply cart and calc should not be called before the wp_loaded action for including (e.g.) items which added during POST.', 'advanced-dynamic-pricing-for-woocommerce' ), '1.6.0' );
		}

		$cart_context = WDP_Frontend::make_wdp_cart_context_from_wc();
		$this->cart   = new WDP_Cart( $cart_context, WC()->cart );

		if ( 'view' === $context ) {
			$this->cart = apply_filters( 'wdp_apply_cart_to_price_display', $this->cart );
		}
	}

	public function apply_empty_cart( $context = 'view' ) {
		$cart_context = WDP_Frontend::make_wdp_cart_context_from_wc();
		$this->cart   = new WDP_Cart( $cart_context );
		if ( 'view' === $context ) {
			$this->cart = apply_filters( 'wdp_apply_empty_cart_to_price_display', $this->cart );
		}
	}

	/**
	 * @param $wdp_cart WDP_Cart
	 */
	public function attach_cart( $wdp_cart ) {
		$this->cart   = $wdp_cart;
	}

	/**
	 * @param $wdp_cart_calc WDP_Cart_Calculator
	 */
	public function attach_calc( $wdp_cart_calc ) {
		$this->calc   = $wdp_cart_calc;
	}

	/**
	 * @param $price_html string
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function hook_get_price_html( $price_html, $product ) {
		$price_html = $this->get_product_price_html_without_pricing( $product );

		$modify = ! ( is_product() && $this->get_option( 'do_not_modify_price_at_product_page' ) );
		if ( ! apply_filters( 'wdp_modify_price_html', $modify, $price_html, $product, 1 ) ) {
			return $price_html;
		}

		if ( ! ( $product instanceof WC_Product ) ) {
			return $price_html;
		}

		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $price_html;
		}

		$wdp_product = apply_filters( 'wdp_get_price_html_from_wdp_product', $wdp_product, $this );
		if ( ! ( $wdp_product instanceof WDP_Product ) ) {
			return $price_html;
		}

		if ( ! $wdp_product->are_rules_applied() ) {
			return apply_filters( 'wdp_price_display_html', $wdp_product->get_wc_price_html(), $wdp_product );
		}

		if ( count( $wdp_product->get_children() ) > 0 ) {
			// check if we apply bulk price replacements to the product with children if not all children changed by bulk
			$apply_not_fully_affected = (boolean) apply_filters( 'wdp_is_apply_bulk_price_replacements_to_not_fully_affected_product', true );
			$apply_not_fully_affected = ! $apply_not_fully_affected ? $wdp_product->are_all_children_affected_by_bulk() : $apply_not_fully_affected;
		} else {
			$apply_not_fully_affected = true;
		}

		if ( WDP_Frontend::is_catalog_view() && $this->get_option( 'replace_price_with_min_bulk_price' ) && $wdp_product->is_affected_by_bulk() && $apply_not_fully_affected ) {
			$price_html = $this->get_option( 'replace_price_with_min_bulk_price_template' );

			$min_bulk_price = $wdp_product->get_min_bulk_price();

			if ( count( $wdp_product->get_children() ) > 0 ) {
				$initial_price = $wdp_product->get_child_initial_price_for_min_bulk_price();
			} else {
				$initial_price = $wdp_product->get_price();
			}

			$replacements = array(
				'{{price}}'         => false !== $min_bulk_price ? wc_price( $min_bulk_price ) : "",
				'{{price_suffix}}'  => $product->get_price_suffix(),
				'{{price_striked}}' => false !== $initial_price ? '<del>' . wc_price( $initial_price ) . '</del>' : "",
			);

			foreach ( $replacements as $search => $replace ) {
				$price_html = str_replace( $search, $replace, $price_html );
			}

			return $price_html;
		}

		if ( $product_price_html = $wdp_product->get_price_html() ) {
			$price_html = $product_price_html;
		}

		return apply_filters( 'wdp_price_display_html', $price_html, $wdp_product );
	}

	/**
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public function get_product_price_html_without_pricing( $product ) {
		$this->remove_price_hooks();
		$html = $product->get_price_html();
		$this->init_hooks();

		return $html;
	}

	/**
	 * @param $the_product WC_Product|int|WDP_Product
	 * @param $qty int
	 *
	 * @return WDP_Product|null
	 */
	public function process_product( $the_product, $qty = 1 ) {
		if ( is_null( $this->calc ) ) {
			global $wp;
			$logger = wc_get_logger(); // >Woocommerce>Status>Logs , file "log-2019-06-24-xxxx"
			$logger->error( sprintf( 'Calling null calc at %s', home_url( $wp->request ) ) );

			return null;
		}

		if ( ! $this->calc->at_least_one_rule_active() ) {
			return null;
		}

		if ( is_numeric( $the_product ) ) {
			$product_id = $the_product;
		} elseif ( $the_product instanceof WC_Product || $the_product instanceof WDP_Product ) {
			$product_id = $the_product->get_id();
		} else {
			$product_id = null;
		}

		$wdp_product = WDP_Object_Cache::get_instance()->get_wdp_product( $product_id, $qty );
		if ( ! $wdp_product ) {
			if ( $the_product instanceof WDP_Product ) {
				$wdp_product = $the_product;
			} else {
				try {
					$wdp_product = new WDP_Product( $the_product );
				} catch ( Exception $e ) {
					return null;
				}
			}

			$wdp_product = $this->calculate_product( $wdp_product, $qty );
			$wdp_product = WDP_Object_Cache::get_instance()->get_wdp_product( $wdp_product, $qty );
		}

//		if ( ! isset( $this->cached_products[ $product_id ][ $qty ] ) ) {
//			if ( ! isset( $this->cached_products[ $product_id ] ) ) {
//				$this->cached_products[ $product_id ] = array();
//			}
//
//			if ( $the_product instanceof WDP_Product ) {
//				$wdp_product = $the_product;
//			} else {
//				try {
//					$wdp_product = new WDP_Product( $the_product );
//				} catch ( Exception $e ) {
//					return null;
//				}
//			}
//
//			$wdp_product                                  = $this->calculate_product( $wdp_product, $qty );
//			$this->cached_products[ $product_id ][ $qty ] = $wdp_product;
//		} else {
//			$wdp_product = $this->cached_products[ $product_id ][ $qty ];
//		}

		return $wdp_product;
	}

	/**
	 * @param $product WDP_Product
	 * @param $qty int
	 * @param $args array
	 *
	 * @return mixed|void
	 */
	public function get_product_price_html( $product, $qty, $args ) {
		$allow_striked = isset($args['allow_striked']) ? wc_string_to_bool($args['allow_striked']) : true;
		$allow_modify = isset($args['allow_modify']) ? wc_string_to_bool($args['allow_modify']) : true;

		if ( ! $allow_modify ) {
			remove_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10 );
			$price_html = $product->get_wc_product()->get_price_html();
			add_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10, 2 );

			return $price_html;
		}

		$product = $this->process_product( $product, $qty );
		if ( ! is_null( $product ) && $product->are_rules_applied() ) {
			if ( '' === $product->get_wc_product()->get_price() ) {
				$price_html = apply_filters( 'woocommerce_empty_price_html', '', $this );
			} elseif ( $product->is_on_wdp_sale() ) {
				if ( $allow_striked ) {
					$price_html = wc_format_sale_price( $product->get_price(), $product->get_new_price() ) . $product->get_price_suffix();
				} else {
					$price_html = wc_price( $product->get_new_price() ) . $product->get_price_suffix();
				}
			} else {
				$price_html = wc_price( $product->get_price() ) . $product->get_price_suffix();
			}

			$price_html = apply_filters( 'wdp_get_discounted_price_html', $price_html, $product->get_price(), $product->get_new_price(), $product );
		} else {
			remove_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10 );
			$price_html = $product->get_wc_product()->get_price_html();
			add_filter( 'woocommerce_get_price_html', array( $this, 'hook_get_price_html' ), 10, 2 );
		}

		return $price_html;
	}

	/**
	 * @param $product WDP_Product
	 * @param $qty int
	 * @param $page_data array
	 *
	 * @return string
	 */
	public function get_product_price_html_depends_on_page( $product, $qty, $page_data ) {
		$is_product = isset( $page_data['is_product'] ) ? wc_string_to_bool( $page_data['is_product'] ) : null;
		$args       = array(
			'allow_striked' => true,
			'allow_modify'  => true,
		);

		if ( $is_product === true ) {
			if ( ! $this->get_option( 'show_striked_prices_product_page' ) ) {
				$args['allow_striked'] = false;
			}

			if ( $this->get_option( 'do_not_modify_price_at_product_page' ) ) {
				$args['allow_modify'] = false;
			}
		}

		$args = apply_filters( 'wdp_get_product_price_html_depends_on_page_args', $args, $product, $qty, $page_data );

		return $this->get_product_price_html( $product, $qty, $args );
	}

	/**
	 * @param $product WDP_Product
	 * @param $qty int
	 *
	 * @return null|WDP_Product
	 */
	private function calculate_product( &$product, $qty = 1 ) {
		if ( ! $this->cart ) {
			$this->apply_cart();
		}

		$cart = clone $this->cart;
		
		$has_children = $product->is_variable() || $product->is_grouped() ;

		if ( $has_children && $product->get_children() ) {
			foreach ( $product->get_children() as $child_id ) {
				try {
					$child = new WDP_Product( $child_id, $product->get_wc_product() );
				} catch ( Exception $e ) {
					continue;
				}

				if ( ! $child->is_price_defined() ) {
					continue;
				}

				$child = $this->process_product( $child, $qty );
				if ( is_null( $child ) ) {
					return null;
				}

				$product->update_children_summary( $child );
			}
		} elseif ( ! $has_children  ) {
			// skip products without prices 
			if( $product->get_wc_product()->get_price() === "" )
				$new_cart = false;
			else 	
				$new_cart = $this->calc->process_cart_with_product( $cart, $product->get_wc_product(), $qty );
			if ( $new_cart ) {
				$product  = $this->calc->apply_changes_to_product( $new_cart, $product, $qty );
				$product->update_prices( $new_cart->get_context() );
				$product = $this->prepare_product_to_display( $product, $new_cart->get_context() );
			} else {
				$product = $this->prepare_product_to_display( $product, $cart->get_context() );
			}
		}

		return $product;
	}

	/**
	 * @param $product WDP_Product
	 * @param $context WDP_Cart_Context
	 *
	 * @return WDP_Product
	 */
	public function prepare_product_to_display($product, $context) {
		$initial_num_decimals = wc_get_price_decimals();
		$set_price_decimals = function ( $num_decimals ) use ( $initial_num_decimals ) {
			return $initial_num_decimals + 1;
		};
		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			add_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		$product->set_price( $this->get_price_to_display( $product->get_wc_product(), array( 'price' => $product->get_price() ) ) );
		$product->set_new_price( $this->get_price_to_display( $product->get_wc_product(), array( 'price' => $product->get_new_price() ) ) );

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			remove_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		return $product;
	}

	/**
	 * @param  WC_Product $product WC_Product object.
	 * @param  array      $args Optional arguments to pass product quantity and price.
	 *
	 * @return float
	 */
	private function get_price_to_display( $product, $args ) {
		return wc_get_price_to_display( $product, $args );
	}

	/**
	 * @param $on_sale boolean
	 * @param $product WC_Product
	 *
	 * @return boolean
	 */
	public function hook_product_is_on_sale( $on_sale, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $on_sale;
		}

		return $on_sale || $wdp_product->are_rules_applied();
	}

	/**
	 * @param $value string
	 * @param $product WC_Product
	 *
	 * @return string|float
	 */
	public function hook_product_get_sale_price( $value, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $value;
		}

		return $wdp_product->are_rules_applied() ? $wdp_product->get_new_price() : $value;
	}

	public function hook_product_get_regular_price( $value, $product ) {
		$wdp_product = $this->process_product( $product );
		if ( is_null( $wdp_product ) ) {
			return $value;
		}

		return $wdp_product->are_rules_applied() ? $wdp_product->get_price() : $value;
	}

	private function is_request_to_rest_api() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );

		// Check if our endpoint.
		$woocommerce = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix . 'wc/' ) ); // @codingStandardsIgnoreLine

		// Allow third party plugins use our authentication methods.
		$third_party = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix . 'wc-' ) ); // @codingStandardsIgnoreLine

		return apply_filters( 'woocommerce_rest_is_request_to_rest_api', $woocommerce || $third_party );
	}

	/**
	 * @param WC_Product $product
	 * @param array      $args
	 *
	 * @return string
	 */
	public function get_cart_item_price_to_display( $product, $args = array() ) {
		if ( ! $this->cart ) {
			$this->apply_cart();
		}

		$args = wp_parse_args( $args, array(
			'qty'   => 1,
			'price' => $product->get_price(),
		) );

		$price = $args['price'];
		$qty   = $args['qty'];

		$context = $this->cart->get_context();

		$initial_num_decimals = wc_get_price_decimals();
		$set_price_decimals = function ( $num_decimals ) use ( $initial_num_decimals ) {
			return $initial_num_decimals + 1;
		};

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			add_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		if ( 'incl' === get_option( 'woocommerce_tax_display_cart' ) ) {
			$new_price = wc_get_price_including_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
		} else {
			$new_price = wc_get_price_excluding_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
		}

		if ( ! $context->get_option( 'is_calculate_based_on_wc_precision' ) ) {
			remove_filter( 'wc_get_price_decimals', $set_price_decimals );
		}

		return $new_price;
	}

	/**
	 * @param string $price formatted price after wc_price()
	 * @param array $cart_item
	 * @param string $cart_item_key
	 *
	 * @return string
	 */
	public function woocommerce_cart_item_price_and_price_subtotal( $price, $cart_item, $cart_item_key ) {
		if ( ! isset( $cart_item['wdp_original_price'] ) ) {
			return $price;
		}

		$new_price_html = $price;
		$quantity       = $cart_item['quantity'];
		$product        = $cart_item['data'];
		/**
		 * @var $product WC_Product
		 */

		$new_price = $this->get_cart_item_price_to_display( $product, array( 'price' => (float) $product->get_price( 'edit' ) ) );
		$old_price = $this->get_cart_item_price_to_display( $product, array( 'price' => (float) $cart_item['wdp_original_price'] ) );

		$new_price = apply_filters( 'wdp_cart_item_subtotal', $new_price, $cart_item, $cart_item_key );
		$old_price = apply_filters( 'wdp_cart_item_initial_subtotal', $old_price, $cart_item, $cart_item_key );

		if ( 'woocommerce_cart_item_subtotal' == current_filter() ) {
			$new_price = $new_price * $quantity;
			$old_price = $old_price * $quantity;
		}

		if ( $new_price !== false && $old_price !== false ) {
			if ( $new_price < $old_price ) {
				$price_html = wc_format_sale_price( $old_price, $new_price );
			} else {
				$price_html = $new_price_html;
			}
		} else {
			$price_html = $new_price_html;
		}

		return $price_html;
	}

	public function get_calculator() {
		return $this->calc;
	}

	public function get_cart() {
		return $this->cart;
	}

}