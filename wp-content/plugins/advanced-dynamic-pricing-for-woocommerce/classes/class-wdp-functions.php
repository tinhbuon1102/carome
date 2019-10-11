<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Functions {
	public static function get_gifted_cart_products() {
		$products = array();
		foreach ( WC()->cart->get_cart() as $wc_cart_item_key => $wc_cart_item ) {
			if ( ! empty( $wc_cart_item['wdp_gifted'] ) ) {
				$product_id = isset( $wc_cart_item['data'] ) ? $wc_cart_item['data']->get_id() : 0;
				if ( $product_id ) {
					if ( isset( $products[ $product_id ] ) ) {
						$products[ $product_id ] += $wc_cart_item['wdp_gifted'];
					} else {
						$products[ $product_id ] = $wc_cart_item['wdp_gifted'];
					}
				}
			}
		}

		return $products;
	}

	public static function get_active_rules_for_product( $product_id, $qty = 1, $use_empty_cart = false ) {
		$calc = WDP_Frontend::make_wdp_calc_from_wc();
		$cart = WDP_Frontend::make_wdp_cart_from_wc( $use_empty_cart );

		return $calc->get_active_rules_for_product( $cart, $product_id, $qty );
	}

	/**
	 *
	 * @param array   $array_of_products
	 * array[]['product_id']
	 * array[]['qty']
	 * @param boolean $plain Type of returning array. With False returns grouped by rules
	 *
	 * @return array
	 * @throws Exception
	 *
	 */
	public static function get_discounted_products_for_cart( $array_of_products, $plain = false ) {
		if ( ! did_action( 'wp_loaded' ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( __( '%1$s should not be called before the %2$s action.', 'woocommerce' ), 'get_discounted_products_for_cart', 'wp_loaded' ), WC_ADP_VERSION );

			return array();
		}

		// make calculator
		$rule_collection = WDP_Rules_Registry::get_instance()->get_active_rules();
		$calc            = new WDP_Cart_Calculator( $rule_collection );

		// make context
		if ( ! is_null( WC()->customer ) ) {
			$customer = new WDP_User_Impl( new WP_User( WC()->customer->get_id() ) );
			$customer->apply_wc_customer( WC()->customer );
			$customer->apply_wc_session( WC()->session );
		} else {
			$customer = new WDP_User_Impl( new WP_User() );
		}

		$environment = array(
			'timestamp'           => current_time( 'timestamp' ),
			'tax_enabled'         => wc_tax_enabled(),
			'prices_includes_tax' => wc_prices_include_tax(),
		);
		$settings    = WDP_Helpers::get_settings();
		$context     = new WDP_Cart_Context( $customer, $environment, $settings );

		// make and fill standalone wc cart
		include_once WC_ADP_PLUGIN_PATH . 'classes/class-wdp-standalone-cart.php';
		$wc_cart = new WDP_Standalone_Cart();
		foreach ( $array_of_products as $product_data ) {
			if ( ! isset( $product_data['product_id'], $product_data['qty'] ) ) {
				continue;
			}

			$product_id = (int) $product_data['product_id'];
			$qty        = (float) $product_data['qty'];

			$result = $wc_cart->add_to_cart( $product_id, $qty );
		}
		$cart = new WDP_Cart( $context, $wc_cart );

		return self::get_matched_products( $calc, $cart, $plain );
	}


	/**
	 * @param WDP_Cart_Calculator $calc
	 * @param WDP_Cart            $cart
	 * @param bool                $plain
	 *
	 * @return array
	 * @throws Exception
	 */
	private static function get_matched_products( $calc, $cart, $plain = false ) {
		$result = array();

		$initial_cart = $cart;

		foreach ( $calc->get_rule_array() as $rule ) {
			$rule_result = array();
			$cart        = clone $initial_cart;

			// we need conditions!
			if ( ! $rule->has_conditions() || ! $rule->is_rule_matched_cart( $cart ) ) {
				continue;
			}

			$dependencies = $rule->get_rule_filters();
			if ( ! count( $dependencies ) ) {
				continue;
			}

			$products_to_add = array();
			foreach ( $dependencies as $filter ) {
				if ( 'products' !== $filter['type'] || 'in_list' !== $filter['method'] ) {
					continue;
				}

				$products_to_add[] = array(
					'product_ids' => $filter['values'],
					'qty'         => $filter['qty'],
				);
			}

			if ( ! $products_to_add ) {
				continue;
			}

			$products = array();
			foreach ( $products_to_add as $product_to_add ) {
				$product_ids = $product_to_add['product_ids'];
				$qty         = $product_to_add['qty'];

				foreach ( $product_ids as $product_id ) {
					if ( ! isset( $products[ $product_id ] ) ) {
						$product_obj             = WDP_Object_Cache::get_instance()->get_wc_product( $product_id );
						$products[ $product_id ] = $product_obj;
					} else {
						$product_obj = $products[ $product_id ];
					}

					if ( ! $product_obj ) {
						continue;
					}

					$cart->add_product_to_calculate( $product_obj, $qty );
				}
			}

			if ( $plain ) {
				$cart = $calc->process_cart_new( $cart );
			} elseif ( ! $calc->process_cart_use_exact_rules( $cart, array( $rule ) ) ) {
				continue;
			}

			foreach ( $products as $product ) {
				$cart_items = $cart->get_temporary_items_by_product( $product );

				foreach ( $cart_items as $cart_item ) {
					/**
					 * @var $cart_item WDP_Cart_Item
					 */

					$rule_result[] = array_merge( array( 'product_id' => $product->get_id() ), array(
						'original_price'   => $cart_item->get_initial_price(),
						'discounted_price' => $cart_item->get_price(),
					) );
				}
			}

			if ( ! $rule_result ) {
				continue;
			}

			if ( $plain ) {
				if ( ! $result ) {
					$result = $rule_result;
				} else {
					foreach ( $result as &$result_item ) {
						foreach ( $rule_result as $k => $rule_item ) {
							if ( $rule_item['product_id'] == $result_item['product_id'] ) {
								$result_item['discounted_price'] = $result_item['discounted_price'] > $rule_item['discounted_price'] ? $rule_item['discounted_price'] : $result_item['discounted_price'];
								$result_item['original_price']   = $result_item['original_price'] < $rule_item['original_price'] ? $rule_item['original_price'] : $result_item['original_price'];
								unset( $rule_result[ $k ] );
								$rule_result = array_values( $rule_result );
								break;
							}
						}
					}

					$result = array_merge( $result, $rule_result );
				}
			} else {
				$result[] = $rule_result;
			}
		}

		return $result;
	}

	/**
	 * @param int|WC_product $the_product
	 * @param int            $qty
	 * @param bool           $use_empty_cart
	 *
	 * @return float|array|null
	 * float for simple product
	 * array is (min, max) range for variable
	 * null if product is incorrect
	 */
	public static function get_discounted_product_price( $the_product, $qty, $use_empty_cart = true ) {
		$price_display = new WDP_Price_Display();
		$price_display->apply_calc();

		if ( $use_empty_cart ) {
			$price_display->apply_empty_cart( 'nofilter' );
		} else {
			$price_display->apply_cart( 'nofilter' );
		}

		$wdp_product = $price_display->process_product( $the_product, $qty );
		if ( is_null( $wdp_product ) ) {
			return null;
		}

		return $wdp_product->is_variable() ? array(
			$wdp_product->get_min_price(),
			$wdp_product->get_max_price()
		) : $wdp_product->get_new_price();
	}

	public static function process_without_hooks( $callback, $hooks_list ) {
		if ( ! $hooks_list ) {
			return $callback();
		}

		global $wp_filter;
		$stored_actions = array();

		foreach ( $hooks_list as $key ) {
			if ( isset( $wp_filter[ $key ] ) ) {
				$stored_actions[ $key ] = $wp_filter[ $key ];
				unset( $wp_filter[ $key ] );
			}
		}

		$result = $callback();

		// restore hook
		foreach ( $hooks_list as $key ) {
			if ( isset( $stored_actions[ $key ] ) ) {
				$wp_filter[ $key ] = $stored_actions[ $key ];
			}
		}

		return $result;
	}

	public static function process_cart_manually() {
		$wc_customer = WC()->cart->get_customer();
		$wc_session = WC()->session;

		// store tax exempt value
		if ( ! isset( $wc_session->wdp_old_tax_exempt ) ) {
			$wc_session->set( 'wdp_old_tax_exempt', $wc_customer->get_is_vat_exempt() );
		} else {
			$wc_customer->set_is_vat_exempt( $wc_session->wdp_old_tax_exempt );
		}

		$calc = WDP_Frontend::make_wdp_calc_from_wc();
		$cart = WDP_Frontend::make_wdp_cart_from_wc();

		$newcart = $calc->process_cart_new( $cart );
		if( $newcart ) {
			$newcart->apply_to_wc_cart();
		} else {
			unset( $wc_session->wdp_old_tax_exempt );

			//try delete gifted products ?
			$wc_cart_items = WC()->cart->get_cart();
			$store_keys = apply_filters( 'wdp_save_cart_item_keys', array() );

			foreach ( $wc_cart_items as $wc_cart_item_key => $wc_cart_item ) {
				$changed = false;

				if ( isset( $wc_cart_item['wdp_gifted'] ) ) {
					$wdp_gifted = $wc_cart_item['wdp_gifted'];
					unset( $wc_cart_item['wdp_gifted'] );
					$changed = true;
					if ( $wdp_gifted ) {
						WC()->cart->remove_cart_item( $wc_cart_item_key );
						continue;
					}
				}

				if ( isset( $wc_cart_item['wdp_original_price'] ) ) {
					unset( $wc_cart_item['wdp_original_price'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_history'] ) ) {
					unset( $wc_cart_item['wdp_history'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_rules'] ) ) {
					unset( $wc_cart_item['wdp_rules'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['rules'] ) ) {
					unset( $wc_cart_item['rules'] );
					$changed = true;
				}

				if ( isset( $wc_cart_item['wdp_rules_for_singular'] ) ) {
					unset( $wc_cart_item['wdp_rules_for_singular'] );
					$changed = true;
				}

				$product_id   = $wc_cart_item['product_id'];
				$qty          = $wc_cart_item['quantity'];
				$variation_id = $wc_cart_item['variation_id'];
				$variation    = $wc_cart_item['variation'];

				$cart_item_data = array();
				foreach ( $store_keys as $key ) {
					if ( isset( $wc_cart_item[ $key ] ) ) {
						$cart_item_data[ $key ] = $wc_cart_item[ $key ];
					}
				}

				if ( $changed ) {
					WC()->cart->remove_cart_item( $wc_cart_item_key );

					$exclude_hooks = apply_filters('wdp_exclude_hooks_when_add_to_cart_after_disable_pricing', array(), $wc_cart_item);
					self::process_without_hooks( function () use ( $product_id, $qty, $variation_id, $variation, $cart_item_data ) {
						WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation, $cart_item_data );
					}, $exclude_hooks );
				}
			}

			$is_free_shipping_key = '_wdp_free_shipping';
			// clear shipping in session for triggering full calculate_shipping to replace '_wdp_free_shipping' when needed
			foreach ( WC()->session->get_session_data() as $key => $value ) {
				if ( preg_match( '/(shipping_for_package_).*/', $key, $matches ) === 1 ) {
					if ( ! isset( $matches[0] ) ) {
						continue;
					}
					$stored_rates = WC()->session->get( $matches[0] );

					if ( ! isset( $stored_rates['rates'] ) ) {
						continue;
					}
					if ( is_array( $stored_rates['rates'] ) ) {
						foreach ( $stored_rates['rates'] as $rate ) {
							if ( isset( $rate->get_meta_data()[$is_free_shipping_key] ) ) {
								unset( WC()->session->$key );
								break;
							}
						}
					}
				}
			}
		}// if no rules
	}

	public static function add_fee( $name, $amount, $taxable = false, $tax_class = '' ) {
		$default_args = array(
			'name'      => __( 'Fee', 'woocommerce' ),
			'amount'    => 0,
			'taxable'   => false,
			'tax_class' => '',
		);

		$args = array(
			'name'      => $name,
			'amount'    => $amount,
			'taxable'   => $taxable,
			'tax_class' => $tax_class,
		);

		$args = array_merge( $default_args, $args );

		add_action( 'woocommerce_cart_calculate_fees', function ( $wc_cart ) use ( $args ) {
			/**
			 * @var $wc_cart WC_Cart
			 */

			$name  = $args['name'];
			$count = 0;

			foreach ( $wc_cart->get_fees() as $fee ) {
				if ( self::starts_with( $fee->name, $name ) ) {
					$count ++;
				}
			}

			if ( $count ) {
				$args['name'] = sprintf( "%s # %d", $name, $count );
			}

			$wc_cart->fees_api()->add_fee( $args );
		}, 10, 1 );
	}

	private static function starts_with( $string, $query ) {
		return substr( $string, 0, strlen( $query ) ) === $query;
	}
}