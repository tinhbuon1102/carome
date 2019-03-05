<?php
/**
 * WC_Shipping_Necospos class.
 *
 * @class 		WC_Shipping_Necospos
 * @version		1.0.0
 * @package		Shipping-for-WooCommerce/Classes
 * @category	Class
 * @author 		thangtqvn
 */
class WC_Shipping_Necospos extends WC_Shipping_Method {
	
	/**
	 * Constructor. The instance ID is passed to this.
	 */
	public function __construct() {
		$this->id                    = 'necospos_method';
		$this->method_title          = __( 'Necospos Shipping' );
		$this->method_description    = __( 'Necospos Shipping' );
		$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable/Disable' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this shipping method' ),
				'default' 		=> 'yes',
			),
			'title' => array(
				'title' 		=> __( 'Method Title' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title which the user sees during checkout.' ),
				'default'		=> __( 'Necospos Shipping' ),
				'desc_tip'		=> true
			),
			'cost' => array(
				'title' 		=> __( 'Cost' ),
				'type' 			=> 'text',
				'default'		=> 100,
			)
		);
		$this->enabled              = $this->get_option( 'enabled' );
		$this->title                = $this->get_option( 'title' );
		$this->cost                = $this->get_option( 'cost' );
		
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}
	
	/**
	 * calculate_shipping function.
	 * @param array $package (default: array())
	 */
	public function calculate_shipping( $package = array() ) {
		$this->add_rate( array(
			'id'    => $this->id,
			'label' => $this->title,
			'cost'  => $this->cost ? $this->cost : 100,
		) );
	}
}

add_filter( 'woocommerce_shipping_methods', 'register_necospos_method' );

function register_necospos_method( $methods ) {
	
	// $method contains available shipping methods
	$methods[ 'necospos_method' ] = 'WC_Shipping_Necospos';
	return $methods;
}

add_filter('woocommerce_package_rates',     'show_necospos_in_cart_base_on_specific_product', 10, 2);
function show_necospos_in_cart_base_on_specific_product($available_shipping_methods, $package){
	$has_specific_product = false;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		if (elsey_is_specific_product($cart_item['product_id']))
		{
			$has_specific_product = true;
		}
	}
	
	if ($has_specific_product)
	{
		$specific_method = array();
		$specific_method['necospos_method'] = $available_shipping_methods['necospos_method'];
		return $specific_method;
	}
	else {
		unset($available_shipping_methods['necospos_method']);
	}
	return $available_shipping_methods;
}

