<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WDP_Rule_Product_Package implements WDP_Rule {
	use WDP_Price_Calc;
	use WDP_Product_Filtering;

	protected $data;

	/** @var WDP_Cart_Adjustment[] */
	protected $cart_adjustments = array();
	/** @var WDP_Condition[] */
	protected $conditions = array();
	/** @var WDP_Limit[] */
	protected $limits = array();

	protected $options = array();

	private $roles_applied = false;

	function __construct( $rule ) {
		$this->data = $rule;

		if ( ! empty( $this->data['cart_adjustments'] ) ) {
			foreach ( $this->data['cart_adjustments'] as $cart_adj_data ) {
				try {
					$cart_adj                 = WDP_Cart_Adj_Registry::get_instance()->create_adj( $cart_adj_data );
					$this->cart_adjustments[] = $cart_adj;
				} catch ( Exception $exception ) {
				}
			}
		}

		if ( ! empty( $this->data['conditions'] ) ) {
			foreach ( $this->data['conditions'] as $condition_data ) {
				try {
					$condition          = WDP_Condition_Registry::get_instance()->create_condition( $condition_data );
					$this->conditions[] = $condition;
				} catch ( Exception $exception ) {
				}
			}
		}

		if ( ! empty( $this->data['limits'] ) ) {
			foreach ( $this->data['limits'] as $limit_data ) {
				try {
					$limit          = WDP_Limit_Registry::get_instance()->create_limit( $limit_data );
					$this->limits[] = $limit;
				} catch ( Exception $exception ) {
				}
			}
		}

		$this->options = WDP_Helpers::get_settings();
	}

	public function get_id() {
		return (int) $this->data['id'];
	}
	
	public function get_ad_title() {
		return $this->data['title'];
	}

	public function is_exclusive() {
		return (bool) $this->data['exclusive'];
	}

	public function get_priority() {
		return (int) $this->data['priority'];
	}

	protected function has_filters() {
		$filters = $this->get_filters();

		return ! empty( $filters );
	}

	protected function get_filters() {
		$filters = isset( $this->data['filters'] ) ? $this->data['filters'] : array();

		if ( empty( $filters ) ) {
			$filters[] = array(
				'qty'    => 1,
				'qty_end'    => 1,
				'type'   => 'products',
				'method' => 'any',
				'value'  => array(),
			);
		}

		foreach ( $filters as &$filter ) {
			if ( ! isset( $filter['qty_end'] ) ) {
				$filter['qty_end'] = $filter['qty'];
			}
		}

		return $filters;
	}

	public function get_count_of_product_dependencies() {
		$filters_count = count( $this->get_filters() );

		$conditions_count = 0;
		foreach ( $this->conditions as $condition ) {
			if ( $condition->has_product_dependency() ) {
				$conditions_count ++;
			}
		}

		$total = $filters_count + $conditions_count;

		return $total;
	}

	public function get_product_dependencies() {
		$dependencies = array();
		foreach ( $this->get_filters() as $filter ) {
			$dependencies[] = array(
				'qty'     => $filter['qty'],
				'qty_end' => $filter['qty_end'],
				'type'    => $filter['type'],
				'method'  => $filter['method'],
				'values'  => $filter['value'],
			);
		}

		//TODO: not now
//		foreach ( $this->conditions as $condition ) {
//			if ( $condition->has_product_dependency() ) {
//				$dependencies[] = $condition->get_product_dependency();
//			}
//		}

		return $dependencies;
	}

	public function has_bulk() {
		return ! empty( $this->data['bulk_adjustments']['ranges'] );
	}

	public function has_roles_discount() {
		return ! empty( $this->data['role_discounts']['rows'] );
	}

	public function get_bulk_details() {
		if ( ! $this->has_bulk() ) {
			return false;
		}

		$bulk   = $this->maybe_update_bulk_prices();
		$ranges = $bulk['ranges'];

		$ret = array(
			'type'          => $bulk['type'],
			'discount'      => $bulk['discount_type'],
			'ranges'        => array(),
			'table_message' => $bulk['table_message'],
		);
		foreach ( $ranges as $range ) {
			$ret['ranges'][] = array(
				'from'  => $range['from'],
				'to'    => $range['to'],
				'value' => $range['value'],
			);
		}

		return $ret;
	}


	/**
	 * Change bulk prices according ONLY roles rules.
	 * Apply only if roles has larger priority and bulk adjustments type is fixed price.
	 * Affected only in bulk table.
	 * We need not to apply other rule sections because fixed price from bulk overrides them.
	 * Be advised, you must rework this method if order of submethods in "apply_to_cart" has been changed.
	 *
	 * @return array
	 */
	private function maybe_update_bulk_prices() {
		$bulk = $this->data['bulk_adjustments'];
		if ( empty( $bulk['discount_type'] ) || "price__fixed" !== $bulk['discount_type'] ) {
			return $bulk;
		}

		$update = false;
		if ( ! empty( $this->data['sortable_blocks_priority'] ) && is_array( $this->data['sortable_blocks_priority'] ) ) {
			$role_priority = - 1;
			$bulk_priority = - 1;

			foreach ( $this->data['sortable_blocks_priority'] as $key => $priority_label ) {
				if ( "roles" == $priority_label ) {
					$role_priority = $key;
				} elseif ( "bulk-adjustments" == $priority_label ) {
					$bulk_priority = $key;
				}
			}

			if ( $role_priority !== - 1 && $bulk_priority !== - 1 && ( $role_priority > $bulk_priority ) ) {
				$update = true;
			}
		}


		if ( $update && ! empty( $bulk['ranges'] ) ) {
			$roles_rule = array();

			$roles_rules = $this->data['role_discounts']['rows'];

			if ( ! ( $current_user_roles = $this->cart->get_context()->get_customer_roles() ) ) {
				return $bulk;
			}

			foreach ( $roles_rules as $tmp_roles_rule ) {
				if ( empty( $tmp_roles_rule['roles'] ) ) {
					continue;
				}
				$roles = $tmp_roles_rule['roles'];
				if ( ! count( array_intersect( $roles, $current_user_roles ) ) ) {
					continue;
				}

				$roles_rule = $tmp_roles_rule;
			}

			foreach ( $bulk['ranges'] as &$range ) {
				if ( empty( $range['value'] ) ) {
					continue;
				}
				$prices = array( $range['value'] );

				$prices = $this->calculate_prices(
					$prices,
					$roles_rule['discount_type'],
					$roles_rule['discount_value']
				);

				$range['value'] = reset( $prices );
			}

		}

		return $bulk;
	}

	public function is_product_matched( $cart, $product_id ) {
		$this->cart = $cart;

		$limits_verified = $this->check_limits();
		if ( ! $limits_verified ) {
			return false;
		}

		$conditions_verified = $this->check_conditions_for_a_match();
		if ( ! $conditions_verified ) {
			return false;
		}

		$matched = $this->match_product_with_filters( $product_id );

		return $matched;
	}

	public function get_matched_products( $cart ) {
		// we need conditions!
		if ( empty( $this->conditions ) ) {
			return false;
		}
	
		$this->cart = $cart;

		$limits_verified = $this->check_limits();
		if ( ! $limits_verified ) {
			return false;
		}

		$conditions_verified = $this->check_conditions_for_a_match();
		if ( ! $conditions_verified ) {
			return false;
		}

		$filters = $this->get_filters();
		if ( ! count( $filters ) ) {
			return false;
		}

		$product_ids = array();
		foreach ( $this->get_filters() as $filter_key => $filter ) {
			if ( 'products' !== $filter['type'] || 'in_list' !== $filter['method'] ) {
				return false;
			}
			$product_ids = array_merge( $product_ids, $filter['value'] );
		}

		return $product_ids;
	}

	/** @var  WDP_Cart */
	private $cart;
	private $items;
	private $add_products;
	private $used_items_all;
	private $used_items_by_attempt;
	private $used_items_by_filters;
	private $attempt;

	protected function init_applying_to_cart( $cart ) {
		$this->cart                  = $cart;
		$this->cart->sort_cart_items( $this->data['options']['apply_to'] );
		$this->items                 = $this->cart->get_cart_items();
		$this->add_products          = array();
		$this->used_items_all        = array();
		$this->used_items_all_KEYS   = array();
		$this->used_items_by_attempt = array();
		$this->used_items_by_filters = array();
		$this->attempt               = 0;

		foreach ( $this->items as &$item ) {
			$item['initial_price'] = $item['price'];
		}

	}

	public function apply_to_cart( $cart ) {
		global $wp_filter;
		$current_wp_filter = $wp_filter;
		$this->data = apply_filters( 'wdp_before_apply_rule', $this->data, $cart );
		do_action('wdp_before_apply_rule_' . $this->get_id(), $this);
		$this->init_applying_to_cart( $cart );
		//TODO: check limits +
		$limits_verified = $this->check_limits();
		if ( ! $limits_verified ) {
			return false;
		}

		//TODO: check conditions +
		$conditions_verified = $this->check_conditions();
		if ( ! $conditions_verified ) {
			return false;
		}
		$is_valid = false;
		while ( $this->check_attempt() ) {
			//TODO: check product filters +
			$is_checked = $this->check_product_filters();
			if ( ! $is_checked ) {
				break;
			}

			//TODO: apply product adjustments +
			$this->apply_product_adjustment();

			if ( $this->check_deal_attempt() ) {
				//TODO: apply get products +
				$this->apply_deal_adjustment();
			}

			$is_valid = true;
			$this->attempt ++;
		}

		if ( ! $is_valid ) {
			return false;
		}

		//TODO: check max discount sum for product adjustments +
		$this->check_max_discount_sum();

		if ( ! empty( $this->data['sortable_blocks_priority'] ) && is_array( $this->data['sortable_blocks_priority'] ) ) {
			foreach ( $this->data['sortable_blocks_priority'] as $block_name ) {
				if ( 'roles' == $block_name ) {
					$this->apply_roles_discount();
				} elseif ( 'bulk-adjustments' == $block_name && ( empty( $this->data['role_discounts']['dont_apply_bulk_if_roles_matched'] ) || ! $this->roles_applied ) ) {
					//TODO: apply bulk adjustment +
					$this->apply_bulk_adjustment();
				}
			}
		} else {
			$this->apply_roles_discount();

			//TODO: apply bulk adjustment +
			$this->apply_bulk_adjustment();
		}

		//TODO: apply cart adjustment +
		$this->apply_cart_adjustment();

		//TODO: apply changes to cart +
		$this->apply_changes_to_cart();

//		echo json_encode( $this->items, JSON_PRETTY_PRINT );
		$wp_filter = $current_wp_filter;
		return true;
	}

	protected function check_limits() {
		foreach ( $this->limits as $limit ) {
			if ( ! $limit->check( $this->cart, $this->get_id() ) ) {
				return false;
			}
		}

		return true;
	}

	protected function check_conditions() {
		if ( empty( $this->conditions ) ) {
			return true;
		}
			
		$relationship = ! empty( $this->data['additional']['conditions_relationship'] ) ? $this->data['additional']['conditions_relationship'] : 'and';
		$result = false;
		foreach ( $this->conditions as $condition ) {
			if ( ! $condition->check( $this->cart ) ) {
				if ( 'and' == $relationship ) {
					return false;
				}
				continue;
			}

//          Why do we put used in condition cart items to used_items_all?
//
//			$involved_items = $condition->get_involved_cart_items();
//			if ( ! empty( $involved_items ) ) {
//				foreach ( $involved_items as $item_key ) {
//					$this->used_items_all[] = $item_key;
//				}
//			}

			// check_conditions always true if relationship not 'and' and at least one condition checked
			$result = true;
		}

		return $result;
	}

	protected function check_conditions_for_a_match() {
		if ( empty( $this->conditions ) ) {
			return true;
		}
		$relationship = ! empty( $this->data['additional']['conditions_relationship'] ) ? $this->data['additional']['conditions_relationship'] : 'and';
		$result = false;
		foreach ( $this->conditions as $condition ) {
			if ( !$condition->match( $this->cart ) ) {
				if( 'and' == $relationship ) 
					return false;
			} else {
				if ( 'or' == $relationship ) 
					return true;
				else	
					$result = true;
			}
		}

		return $result;
	}

	protected function check_max_discount_sum() {
		$limit = $this->data['product_adjustments']['max_discount_sum'];

		if ( empty( $limit ) ) {
			return true;
		}

		$changed_items = array();

		$discount_sum = 0;
		foreach ( $this->get_keys_of_changeable_prices() as $item_key ) {
			$item = $this->items[ $item_key ];

			$item_discount = 0;
			if ( isset( $item['initial_price'] ) ) {
				$item_discount = $item['initial_price'] - $item['price'];
			}

			if ( ! empty( $item_discount ) ) {
				$discount_sum    += $item_discount;
				$changed_items[] = $item_key;
			}
		}

		if ( $discount_sum <= $limit ) {
			return true;
		}

		//TODO: apply max discount sum option +
		//TODO: split sum between last attempts +
		$left_to_increase = $discount_sum - $limit;

		$changed_items = array_reverse( $changed_items );
		foreach ( $changed_items as $item_key ) {
			$item = $this->items[ $item_key ];

			$increases_sum = min( $item['initial_price'] - $item['price'], $left_to_increase );
			$new_price     = $item['price'] + $increases_sum;

			$new_price = $this->maybe_maximize_discount( $item, $new_price );

			$item['adjusted_price'] = $new_price;
			$item['price']          = $new_price;

			$this->items[ $item_key ] = $item;

			$left_to_increase -= $increases_sum;
			if ( $left_to_increase <= 0 ) {
				break;
			}
		}

		return true;
	}

	protected function check_attempt() {
		$attempts_limit = (int) $this->data['options']['repeat'];

		return $attempts_limit < 0 || $this->attempt < $attempts_limit;
	}

	protected function check_deal_attempt() {
		$attempts_limit = (int) $this->data['get_products']['repeat'];

		return $attempts_limit < 0 || $this->attempt < $attempts_limit;
	}

	protected function apply_changes_to_cart() {
		$is_override_cents = $this->options['is_override_cents'];
		$cached_discount = array();

		foreach ( $this->used_items_all as $item_key ) {
			$item     = $this->items[ $item_key ];
			$key = md5( json_encode( $item ) );

			if ( isset( $cached_discount[ $key ] ) ) {
				$discount = $cached_discount[ $key ];
			} else {
				$product       = wc_get_product( $item['product_id'] );
				$initial_price = wc_get_price_to_display( $product, array( 'price' => $item['initial_price'] ) );
				$price         = wc_get_price_to_display( $product, array( 'price' => $item['price'] ) );

				$discount = $initial_price - $price;

				if ( abs( $discount ) > 0 && $is_override_cents && $item['price'] != 0) {
					$item['price'] = $this->override_cents( $item['price'] );
					$price         = wc_get_price_to_display( $product, array( 'price' => $item['price'] ) );
					$discount = $initial_price - $price;
				}

				$cached_discount[ $key ] = $discount;
			}

			if ( abs( $discount ) > 0 ) {
				$item['price'] = $is_override_cents && $item['price'] != 0 ? $this->override_cents( $item['price'] ) : $item['price'];
				$this->cart->modify_cart_item( $item_key, $item );
				$this->cart->add_rule_discount_to_cart_item( $item_key, $this->get_id(), $discount );
			}
		}
		if ( $this->is_exclusive() ) {
			$this->cart->fix_cart_items( $this->used_items_all );
		}

		$added_item_keys = array();
		foreach ( $this->add_products as $product_id => $qty ) {
			$added_item_keys = array_merge(
				$added_item_keys,
				$this->cart->add_free( $product_id, $qty )
			);

			foreach ( $added_item_keys as $added_item_key ) {
				$this->cart->add_rule_discount_to_cart_item( $added_item_key, $this->get_id(), $this->cart->get_original_price( $product_id ) );
			}
		}
		if ( $this->is_exclusive() ) {
			$this->cart->fix_cart_items( $added_item_keys );
		}
	}

	protected function override_cents( $price ){
		$prices_ends_with  = $this->options['prices_ends_with'];

		$price_fraction = $price - intval( $price );
		$new_price_fraction = $prices_ends_with / 100;

		$round_new_price_fraction = round($new_price_fraction);


		if ( 0 == intval( $price ) AND 0 < $new_price_fraction ){
			$price = $new_price_fraction;
			return $price;
		}


		if ( $round_new_price_fraction ) {

			if ( $price_fraction <= $new_price_fraction - round(1/2, 2) ){
				$price = intval( $price ) - 1 + $new_price_fraction;
			} else {
				$price = intval( $price ) + $new_price_fraction;
			}

		} else {

			if ( $price_fraction >= $new_price_fraction + round(1/2, 2) ){
				$price = intval( $price ) + 1 + $new_price_fraction;
			} else {
				$price = intval( $price ) + $new_price_fraction;
			}

		}

		return $price;
	}

	protected function match_product_with_filters( $product_id ) {
		$matched = true;
		foreach ( $this->get_filters() as $filter_key => $filter ) {
			$matched = $this->check_product_suitability(
				$product_id,
				$filter['type'],
				$filter['value'],
				$filter['method']
			);

			if ( $matched ) {
				break;
			}
		}

		return $matched;
	}

	/**
	 * @return bool
	 */
	protected function check_product_filters() {
		$used_cart_items_by_attempt = array();
		$used_cart_items_by_filter  = array();
		static $last_iteration_product = null;
		static $last_iteration_result = null;
		static $cached_parent_id = array();

		foreach ( $this->get_filters() as $filter_key => $filter ) {
			$filter_qty = '' !== $filter['qty'] ? (int) $filter['qty'] : 1;
			$filter_qty_end = '' !== $filter['qty_end'] ? (int) $filter['qty_end'] : null;
			$found_qty  = 0;

			foreach ( $this->items as $cart_item_key => $cart_item ) {
				$is_used_item = isset( $this->used_items_all_KEYS[$cart_item_key] );

				if ( ! $is_used_item ) {
					$filter['value'] = isset( $filter['value'] ) ? $filter['value'] : array();
					$hash = md5( json_encode( array($cart_item, $filter['type'], $filter['value'], $filter['method']) ) );
					if ( $hash == $last_iteration_product ) {
						$is_product_valid  = $last_iteration_result;
					} else {
						$is_product_valid      = $this->check_product_suitability(
							$cart_item['product_id'],
							$filter['type'],
							$filter['value'],
							$filter['method'],
							$cart_item
						);
						$last_iteration_product = $hash;
						$last_iteration_result = $is_product_valid;
					}

					if ( $is_product_valid ) {
						$used_cart_items_by_filter[ $filter_key ][] = $cart_item_key;
						$used_cart_items_by_attempt[]               = $cart_item_key;
						$this->used_items_all_KEYS[$cart_item_key] = 1;
						
 						if( !isset($cached_parent_id[$cart_item['product_id']]) ) 
 							$cached_parent_id[$cart_item['product_id']] = wp_get_post_parent_id( $cart_item['product_id'] );
 						$parent_product_id = $cached_parent_id[$cart_item['product_id']];
						
						$product_id        = $parent_product_id ? $parent_product_id : $cart_item['product_id'];
						if ( ! isset( $this->used_categories_by_product_id[ $product_id ] ) ) {
							$terms                                              = get_the_terms( $product_id, 'product_cat' );
							$this->used_categories_by_product_id[ $product_id ] = wp_list_pluck( $terms, 'term_id' );
						}

						$found_qty ++;
					}
				}

				if ( $filter_qty_end !== null && $found_qty === $filter_qty_end ) {
					break;
				}
			}

			if ( $found_qty < $filter_qty || ( $filter_qty_end !== null && $found_qty > $filter_qty_end ) ) {
				return false;
			}
		}

		$this->used_items_by_filters[ $this->attempt ] = $used_cart_items_by_filter;
		$this->used_items_by_attempt[ $this->attempt ] = $used_cart_items_by_attempt;

		$this->used_items_all = array_merge( $this->used_items_all, $used_cart_items_by_attempt );

		return true;
	}

	/**
	 * @return bool
	 */
	protected function apply_product_adjustment() {
		$ret = true;

		$adj_type = isset( $this->data['product_adjustments']['type'] ) ? $this->data['product_adjustments']['type'] : false;
		if ( 'total' === $adj_type ) {
			//TODO: implement product adjustment total mode +
			$ret = $this->apply_product_adjustment_total();
		} elseif ( 'split' === $adj_type ) {
			$ret = $this->apply_product_adjustment_split();
		}

		return $ret;
	}

	/**
	 * @return bool
	 */
	protected function apply_product_adjustment_total() {
		$used_item_keys = $this->get_keys_of_changeable_prices( $this->attempt );

		$adjustment = $this->data['product_adjustments']['total'];
		if ( empty( $adjustment ) || empty( $adjustment['type'] ) ) {
			return true;
		}

		$prices = array();
		foreach ( $used_item_keys as $cart_item_key ) {
			$prices[ $cart_item_key ] = $this->items[ $cart_item_key ]['price'];
		}
		$prices = $this->calculate_prices(
			$prices,
			$adjustment['type'],
			$adjustment['value']
		);

		foreach ( $used_item_keys as $cart_item_key ) {
			$cart_item = $this->items[ $cart_item_key ];

			$new_price = $prices[ $cart_item_key ];

			$new_price = $this->maybe_maximize_discount( $cart_item, $new_price );

			$cart_item['discounted_price'] = $new_price;
			$cart_item['price']            = $new_price;

			$this->items[ $cart_item_key ] = $cart_item;
		}

		return true;
	}

	protected function get_keys_of_changeable_prices( $attempt = null ) {
		if ( is_null( $attempt ) ) {
			$used_item_keys = $this->used_items_all;
		} else {
			$used_item_keys = $this->used_items_by_attempt[ $attempt ];
		}

		$ret = array();
		foreach ( $used_item_keys as $cart_item_key ) {
			if ( $this->is_changeable_price( $cart_item_key ) ) {
				$ret[] = $cart_item_key;
			}
		}

		return $ret;
	}

	protected function get_keys_of_changeable_prices_by_filter( $filter_id, $attempt ) {
		$ret = array();

		$by_filter = $this->used_items_by_filters[ $attempt ][ $filter_id ];
		foreach ( $by_filter as $cart_item_key ) {
			if ( $this->is_changeable_price( $cart_item_key ) ) {
				$ret[] = $cart_item_key;
			}
		}

		return $ret;
	}

	protected function is_changeable_price( $cart_item_key ) {
		return empty( $this->items[ $cart_item_key ]['price_readonly'] );
	}

	/**
	 * @return bool
	 */
	protected function apply_product_adjustment_split() {
		$adjustments = $this->data['product_adjustments']['split'];
		if ( empty( $adjustments ) ) {
			return true;
		}
		
		foreach ( $adjustments as $adj_key => $adjustment ) {
			if( empty($adjustment['type']) )
				continue;
			$filter_key = $adj_key;

			$used_cart_items = $this->get_keys_of_changeable_prices_by_filter( $filter_key, $this->attempt );
			foreach ( $used_cart_items as $cart_item_key ) {
				$cart_item = $this->items[ $cart_item_key ];

				$new_price = $this->calculate_single_price(
					$cart_item['price'],
					$adjustment['type'],
					$adjustment['value']
				);

				$new_price = $this->maybe_maximize_discount( $cart_item, $new_price );

				$cart_item['discounted_price'] = $new_price;
				$cart_item['price']            = $new_price;

				$this->items[ $cart_item_key ] = $cart_item;
			}
		}

		return true;
	}

	private $cached_free_prods = array();

	protected function apply_deal_adjustment() {
		$adjustments = isset( $this->data['get_products']['value'] ) ? $this->data['get_products']['value'] : false;
		if ( empty( $adjustments ) ) {
			return true;
		}

		foreach ( $adjustments as $deal_adjustment ) {
			$qty = (int) $deal_adjustment['qty'];
			if ( empty( $deal_adjustment['value'] ) ) {
				continue;
			}

			foreach ( $deal_adjustment['value'] as $deal_adjustment_product_id ) {
				if ( $qty <= 0 ) {
					break;
				}
				if ( isset( $this->cached_prods[ $deal_adjustment_product_id ] ) ) {
					$product = $this->cached_free_prods[ $deal_adjustment_product_id ];
				} else {
					$product = wc_get_product( $deal_adjustment_product_id );
					if ( ! $product ) {
						continue;
					}
					$this->cached_free_prods[ $deal_adjustment_product_id ] = $product;
				}

				$current_free_qty = isset( $this->add_products[ $deal_adjustment_product_id ] ) ? $this->add_products[ $deal_adjustment_product_id ] : 0;
				$qty_to_add       = 0;

				if ( $product->managing_stock() ) {
					if ( $product->backorders_allowed() ) {
						$qty_to_add = $qty;
					} else {
						$available_for_now = $product->get_stock_quantity() - $current_free_qty;
						$qty_to_add        = $available_for_now >= $qty ? $qty : $available_for_now;
					}
				} else {
					$qty_to_add = $qty;
				}

				if ( $qty_to_add > 0 ) {
					$this->add_products[ $deal_adjustment_product_id ] = isset( $this->add_products[ $deal_adjustment_product_id ] ) ? $this->add_products[ $deal_adjustment_product_id ] + $qty_to_add : $qty_to_add;
					$qty                                               -= $qty_to_add;
				}
			}
		}

		return true;
	}

	private $used_categories_by_product_id = array();

	protected function apply_bulk_adjustment() {
		if ( ! $this->has_bulk() ) {
			return true;
		}
		$attempt_count                            = $this->attempt;
		$qty_based                                = isset( $this->data['bulk_adjustments']['qty_based'] ) ? $this->data['bulk_adjustments']['qty_based'] : 'all';
		$predefined_array_used_item_keys_with_qty = array();

		$ranges   = $this->data['bulk_adjustments']['ranges'];
		$adj_type = $this->data['bulk_adjustments']['type'];
		$discount_type = $this->data['bulk_adjustments']['discount_type'];


		if ( has_filter( 'wdp_replace_apply_bulk_adjustment' ) ) {
			$ranges_objs = WDP_Rule_Discount_Range_Calculation::make_ranges( $ranges );
			$bulk_calc   = new WDP_Rule_Discount_Range_Calculation( $adj_type, $discount_type, $ranges_objs );
			$this->items = apply_filters( 'wdp_replace_apply_bulk_adjustment', $this->items, $this->get_keys_of_changeable_prices(), $bulk_calc );

			return true;
		}

		if ( 'total_qty_in_cart' === $qty_based ) {
			$predefined_array_used_item_keys_with_qty [] = array(
				'qty'            => array_sum( wp_list_pluck( WC()->cart->get_cart(), 'quantity' ) ),
				'used_item_keys' => $this->get_keys_of_changeable_prices(),
			);
		} elseif ( 'product' === $qty_based ) {
			$qty_based_param = array();
			foreach ( $this->get_keys_of_changeable_prices() as $cart_item_key ) {
				$item              = $this->items[ $cart_item_key ];
				$parent_product_id = wp_get_post_parent_id( $item['product_id'] );
				$key               = $parent_product_id ? $parent_product_id : $item['product_id'];
				if ( ! isset( $qty_based_param[ $key ] ) ) {
					$qty_based_param[ $key ] = array();
				}

				$qty_based_param[ $key ][] = $cart_item_key;
			}

			foreach ( $qty_based_param as $cart_item_keys ) {
				$predefined_array_used_item_keys_with_qty [] = array(
					'qty'            => count( $cart_item_keys ),
					'used_item_keys' => $cart_item_keys,
				);
			}
		} elseif ( 'variation' === $qty_based ) {
			$qty_based_param = array();
			foreach ( $this->get_keys_of_changeable_prices() as $cart_item_key ) {
				$item = $this->items[ $cart_item_key ];
				$key  = $item['product_id'];

				if ( ! isset( $qty_based_param[ $key ] ) ) {
					$qty_based_param[ $key ] = array();
				}

				$qty_based_param[ $key ][] = $cart_item_key;
			}

			foreach ( $qty_based_param as $cart_item_keys ) {
				$predefined_array_used_item_keys_with_qty [] = array(
					'qty'            => count( $cart_item_keys ),
					'used_item_keys' => $cart_item_keys,
				);
			}
		} elseif ( 'all' === $qty_based ) {
			$items                                       = $this->get_keys_of_changeable_prices();
			$predefined_array_used_item_keys_with_qty [] = array(
				'qty'            => count( $items ),
				'used_item_keys' => $items,
			);
		} elseif ( 'sets' === $qty_based ) {
			$used_item_keys = array();
			for ( $i = 0; $i < $attempt_count; $i ++ ) {
				$used_item_keys[] = $this->get_keys_of_changeable_prices( $i );
			}

			$predefined_array_used_item_keys_with_qty [] = array(
				'qty'            => $attempt_count,
				'used_item_keys' => $used_item_keys,
			);
		} elseif ( 'product_selected_categories' === $qty_based ) {
			$predefined_array_used_item_keys_with_qty [] = array(
				'qty'            => $this->cart->get_original_items_qty_with_categories( $this->data['bulk_adjustments']['selected_categories'] ),
				'used_item_keys' => $this->get_keys_of_changeable_prices(),
			);
		} elseif ( 'product_categories' === $qty_based ) {
			$used_categories = array_unique( call_user_func_array( 'array_merge', $this->used_categories_by_product_id ) );

			$predefined_array_used_item_keys_with_qty [] = array(
				'qty'            => $this->cart->get_original_items_qty_with_categories( $used_categories ),
				'used_item_keys' => $this->get_keys_of_changeable_prices(),
			);
		}

		foreach ( $predefined_array_used_item_keys_with_qty as $item ) {
			$item = apply_filters( 'wdp_used_item_keys_with_qty_before_bulk_range', $item, $this->items );

			$comparison_qty = $item['qty'];
			$keys_to_change = $item['used_item_keys'];

			if ( 'tier' == $adj_type ) {
				foreach ( $ranges as $range ) {
					$from = ! empty( $range['from'] ) ? $range['from'] : 1;
					$to   = ! empty( $range['to'] ) ? $range['to'] : '';

					$in_range = $comparison_qty >= $from && ( $comparison_qty <= $to || $to == '' );

					if ( $in_range ) {
						foreach ( array_slice( $keys_to_change, $from - 1, $comparison_qty - $from + 1 ) as $key_to_change ) {
							if ( is_numeric($key_to_change) ) {
								$this->apply_price( array( $key_to_change ), $discount_type, $range['value'] );
							} else {
								$this->apply_price( $key_to_change, $discount_type, $range['value'] );
							}
						}
						break;
					} elseif ( $to !== '' && $to < $comparison_qty ) {
						foreach ( array_slice( $keys_to_change, $from - 1, $to - $from + 1 ) as $key_to_change ) {
							if ( is_numeric($key_to_change) ) {
								$this->apply_price( array( $key_to_change ), $discount_type, $range['value'] );
//								break;
							} else {
								$this->apply_price( $key_to_change, $discount_type, $range['value'] );
							}
						}
					}
				}
			} elseif ( 'bulk' == $adj_type ) {
				foreach ( $ranges as $range ) {
					$more_than_from = empty( $range['from'] ) || $comparison_qty >= $range['from'];
					$less_than_to   = empty( $range['to'] ) || $comparison_qty <= $range['to'];
					if ( $more_than_from && $less_than_to ) {
						foreach ( $keys_to_change as $key_to_change ) {
							if ( is_numeric($key_to_change) ) {
								$this->apply_price( array( $key_to_change ), $discount_type, $range['value'] );
							} else {
								$this->apply_price( $key_to_change, $discount_type, $range['value'] );
							}
						}
						break;
					}
				}
			}
		}

		return true;
	}

	protected function apply_price( $used_item_keys, $discount_type, $value ) {
		$prices = array();
		foreach ( $used_item_keys as $cart_item_key ) {
			$prices[ $cart_item_key ] = $this->items[ $cart_item_key ]['price'];
		}
		$prices = $this->calculate_prices(
			$prices,
			$discount_type,
			$value
		);

		foreach ( $used_item_keys as $cart_item_key ) {
			$cart_item = $this->items[ $cart_item_key ];

			$new_price = $prices[ $cart_item_key ];

			$new_price = $this->maybe_maximize_discount( $cart_item, $new_price );

			$cart_item['bulk_price']       = $new_price;
			$cart_item['price']            = $new_price;
			$this->items[ $cart_item_key ] = $cart_item;
		}
	}

	protected function apply_roles_discount() {
		if ( ! $this->has_roles_discount() ) {
			return true;
		}

		$this->roles_applied = false;

		$roles_rules = $this->data['role_discounts']['rows'];

		$attempt_count = $this->attempt;

		if ( ! ( $current_user_roles = $this->cart->get_context()->get_customer_roles() ) ) {
			return true;
		}

		for ( $i = 0; $i < $attempt_count; $i ++ ) {
			$used_item_keys  = $this->get_keys_of_changeable_prices( $i );

			foreach ( $roles_rules as $roles_rule ) {
				$roles = ! empty( $roles_rule['roles'] ) ? $roles_rule['roles'] : array();
				if ( ! count( array_intersect( $roles, $current_user_roles ) ) ) {
					continue;
				}
				$this->roles_applied = true;
				$type = $roles_rule['discount_type'];
				$value = $roles_rule['discount_value'];

				$prices = array();
				foreach ( $used_item_keys as $cart_item_key ) {
					$prices[ $cart_item_key ] = $this->items[ $cart_item_key ]['price'];
				}
				$prices = $this->calculate_prices(
					$prices,
					$type,
					$value
				);

				foreach ( $used_item_keys as $cart_item_key ) {
					$cart_item = $this->items[ $cart_item_key ];

					$new_price = $prices[ $cart_item_key ];

					// bulk will maximize discount if enable in rule
					if ( ! $this->has_bulk() ) {
						$new_price = $this->maybe_maximize_discount( $cart_item, $new_price );
					}

					$cart_item['role_price'] = $new_price;
					$cart_item['price']      = $new_price;

					$this->items[ $cart_item_key ] = $cart_item;
				}
			}
		}


		return true;
	}

	public function maybe_maximize_discount( $cart_item, $new_price ) {
		if ( ! empty( $cart_item['price_mode'] ) && 'compare_discounted_and_sale' == $cart_item['price_mode'] ) {
			if ( ! empty( $cart_item['woo_in_on_sale'] ) && isset( $cart_item['woo_sale_price'] ) ) {
				$new_price = (float) $new_price > (float) $cart_item['woo_sale_price'] ? (float) $cart_item['woo_sale_price'] : (float) $new_price;
			}
		}

		return $new_price;
	}

	protected function apply_cart_adjustment() {
		foreach ( $this->cart_adjustments as $cart_adjustment ) {
			$cart_adjustment->apply_to_cart( $this->cart, $this->get_id() );
		}

		return true;
	}

	public function get_items() {
		return $this->items;
	}

	/**
	 * Convert items to sets before bulk calculation
	 * @see WDP_Rule_Discount_Range_Calculation::convert_sets_to_items()
	 *
	 * @return array
	 */
	private function prepate_sets() {
		$sets = array();
		for ( $i = 0; $i < $this->attempt; $i ++ ) {
			$items = array();
			foreach ( $this->get_keys_of_changeable_prices( $i ) as $key ) {
				$items[] = $this->items[ $key ];
			};

			// needs unique hash for every item
			$hash = md5( json_encode( $this->get_keys_of_changeable_prices( $i ) ) );

			$sets[ $hash ] = $items;
		}

		return $sets;
	}
}