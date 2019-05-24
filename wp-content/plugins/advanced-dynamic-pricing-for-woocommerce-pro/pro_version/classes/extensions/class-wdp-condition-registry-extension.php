<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Condition_Registry_Extension {

	private $conditions;

	public function __construct() {
		add_filter( 'wdp_conditions', array( $this, 'add_conditions' ), 10, 1 );
		add_filter( 'wdp_ids_for_filter_titles', array( $this, 'get_ids_for_filter_titles' ), 10, 2 );
	}

	public function add_conditions( $conditions ) {

		$this->init_product_conditions();
		$this->init_cart_conditions();
		$this->init_customer_conditions();
		$this->init_shipping_conditions();
		$this->init_datetime_conditions();

		return array_merge($conditions, $this->conditions);
	}

	protected function init_product_conditions() {
		$this->conditions['amount_products'] = array(
			'class'    => 'WDP_Condition_Products_Amount',
			'label'    => __( 'Amount for products *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-products.php',
		);
		$this->conditions['amount_product_categories'] = array(
			'class'    => 'WDP_Condition_Product_Categories_Amount',
			'label'    => __( 'Amount for categories *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-product-categories.php',
		);
		$this->conditions['amount_product_tags'] = array(
			'class'    => 'WDP_Condition_Product_Tags_Amount',
			'label'    => __( 'Amount for tags *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-product-tags.php',
		);

		foreach ( WDP_Helpers::get_custom_product_taxonomies() as $taxonomy ) {
			$this->conditions[ 'amount_' . $taxonomy->name ] = array(
				'class'         => 'WDP_Condition_Product_Taxonomies_Amount',
				'label'         => __( 'Amount for ' . $taxonomy->labels->menu_name . ' *', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'         => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
				'template'      => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-product-taxonomies.php',
				'template_data' => array(
					'taxonomy' => $taxonomy,
				),
			);
		}

		$this->conditions['amount_product_attributes'] = array(
			'class'    => 'WDP_Condition_Product_Attributes_Amount',
			'label'    => __( 'Amount for attributes *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-product-attributes.php',
		);
		$this->conditions['amount_product_custom_fields'] = array(
			'class'    => 'WDP_Condition_Product_Custom_Fields_Amount',
			'label'    => __( 'Amount for custom fields *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/amount-product-custom-fields.php',
		);
		$this->conditions['products_combination'] = array(
			'class'    => 'WDP_Condition_Products_Combination',
			'label'    => __( 'Products combination *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart items', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/products/products-combination.php',
		);
	}

	protected function init_cart_conditions() {
		$this->conditions['cart_items_count'] = array(
			'class'    => 'WDP_Condition_Cart_Items_Count',
			'label'    => __( 'Items count *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/cart/items-count.php',
		);
		$this->conditions['cart_weight'] = array(
			'class'    => 'WDP_Condition_Cart_Weight',
			'label'    => __( 'Total weight *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/cart/weight.php',
		);
		$this->conditions['cart_coupons'] = array(
			'class'    => 'WDP_Condition_Cart_Coupons',
			'label'    => __( 'Coupons applied *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/cart/coupons.php',
		);
		$this->conditions['customer_payment_method'] = array(
			'class'    => 'WDP_Condition_Cart_Payment_Method',
			'label'    => __( 'Cart payment method *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Cart', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/cart/payment-method.php',
		);

	}

	protected function init_customer_conditions() {
		$this->conditions['customer_users'] = array(
			'class'    => 'WDP_Condition_Customer_Users',
			'label'    => __( 'User *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/users.php',
		);
		$this->conditions['customer_spent_within'] = array(
			'class'    => 'WDP_Condition_CustomerValue_Spent',
			'label'    => __( 'Spent within *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer Value', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/customer-value-spent.php',
		);
		$this->conditions['customer_last_order_amount'] = array(
			'class'    => 'WDP_Condition_CustomerValue_Last_Order_Amount',
			'label'    => __( 'Last order amount *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer Value', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/customer-last-order-amount.php',
		);
		$this->conditions['customer_last_order'] = array(
			'class'    => 'WDP_Condition_CustomerValue_Last_Order',
			'label'    => __( 'Last order *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer Value', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/customer-last-order.php',
		);
		$this->conditions['customer_capability'] = array(
			'class'    => 'WDP_Condition_Customer_Capability',
			'label'    => __( 'Customer capability *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/customer-capability.php',
		);
		$this->conditions['customer_is_first_order'] = array(
			'class'    => 'WDP_Condition_Customer_Is_First_Order',
			'label'    => __( 'Customer first order *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/customer-is-first-order.php',
		);
		$this->conditions['customer_geolocation_country'] = array(
			'class'    => 'WDP_Condition_Customer_Geolocation_Country',
			'label'    => __( 'Customer geolocation country *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Customer', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/customer/geolocation-country.php',
		);
	}

	protected function init_shipping_conditions() {
		$this->conditions['shipping_state'] = array(
			'class'    => 'WDP_Condition_Shipping_State',
			'label'    => __( 'State *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/shipping/state.php',
		);
		$this->conditions['customer_shipping_method'] = array(
			'class'    => 'WDP_Condition_Cart_Shipping_Method',
			'label'    => __( 'Shipping method *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/shipping/shipping-method.php',
		);
	}

	protected function init_datetime_conditions() {
		$this->conditions['date_time'] = array(
			'class'    => 'WDP_Condition_Date_Time',
			'label'    => __( 'Date and Time *', 'advanced-dynamic-pricing-for-woocommerce' ),
			'group'    => __( 'Date & time', 'advanced-dynamic-pricing-for-woocommerce' ),
			'template' => WC_ADP_PRO_VERSION_PATH . 'views/conditions/datetime/date-time.php',
		);
	}

	function get_ids_for_filter_titles( $filters_by_type, $rules ) {
		foreach ( $rules as $rule ) {
				foreach ($rule['conditions'] as $condition) {
					if ( $condition['type'] === 'amount_products' && isset( $condition['options'][0] ) ) {
						$value = $condition['options'][0];
						$filters_by_type[ 'products' ] = array_merge( $filters_by_type[ 'products' ], (array) $value );
					}
					if ( $condition['type'] === 'amount_product_categories' && isset( $condition['options'][0] ) ) {
						$value = $condition['options'][0];
						$filters_by_type[ 'product_categories' ] = array_merge( $filters_by_type[ 'product_categories' ], (array) $value );
					}
					if ( $condition['type'] === 'amount_product_tags' && isset( $condition['options'][0] ) ) {
						$value = $condition['options'][0];
						$filters_by_type[ 'product_tags' ] = array_merge( $filters_by_type[ 'product_tags' ], (array) $value );
					}
					if ( $condition['type'] === 'amount_product_attributes' && isset( $condition['options'][0] ) ) {
						$value = $condition['options'][0];
						$filters_by_type[ 'product_attributes' ] = array_merge( $filters_by_type[ 'product_attributes' ], (array) $value );
					}
					if ( $condition['type'] === 'amount_product_custom_fields' && isset( $condition['options'][0] ) ) {
						$value = $condition['options'][0];
						$filters_by_type[ 'product_custom_fields' ] = array_merge( $filters_by_type[ 'product_custom_fields' ], (array) $value );
					}
					if ( $condition['type'] === 'products_combination' && isset( $condition['options'][1] ) ) {
						$value                       = $condition['options'][1];
						$filters_by_type['products'] = array_merge( $filters_by_type['products'], (array) $value );
					}
				}
		}
		return $filters_by_type;
	}
}

new WDP_Condition_Registry_Extension();