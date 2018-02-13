<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Epsilon Pro Payment Gateway
 *
 * Provides a Epsilon Multi-currency Credit Card Payment Gateway (Link Type).
 *
 * @class 			WC_Epsilon
 * @extends		WC_Gateway_Epsilon_Pro_MCCC
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author			Artisan Workshop
 */
class WC_Gateway_Epsilon_Pro_MCCC extends WC_Payment_Gateway {


	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'epsilon_pro_mccc';
		$this->has_fields        = false;
		$this->method_title      = __( 'Epsilon Multi-currency Credit Card', 'wc4jp-epsilon' );
		
        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'wc4jp-epsilon' ).$this->method_title;
		}

		//Epsilon Setting IDs
		$this->contract_code = get_option('wc-epsilon-pro-cid');
		$this->contract_password = get_option('wc-epsilon-pro-cpass');
		$this->st_code = '00000-0000-00000-00001-00000-00000-00000';

        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Epsilon Multi-currency Credit Card', 'wc4jp-epsilon' );
		$this->method_description = __( 'Allows payments by Epsilon Multi-currency Credit Card in Japan.', 'wc4jp-epsilon' );

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

		// Actions
		add_action( 'woocommerce_receipt_epsilon_pro_mccc',                              array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

      /**
       * Initialize Gateway Settings Form Fields.
       */
	    function init_form_fields() {

	      $this->form_fields = array(
	      'enabled'     => array(
	        'title'       => __( 'Enable/Disable', 'wc4jp-epsilon' ),
	        'label'       => __( 'Enable Epsilon Multi-currency Credit Card Payment', 'wc4jp-epsilon' ),
	        'type'        => 'checkbox',
	        'description' => '',
	        'default'     => 'no'
	        ),
	      'title'       => array(
	        'title'       => __( 'Title', 'wc4jp-epsilon' ),
	        'type'        => 'text',
	        'description' => __( 'This controls the title which the user sees during checkout.', 'wc4jp-epsilon' ),
	        'default'     => __( 'Multi-currency Credit Card (Epsilon)', 'wc4jp-epsilon' )
	        ),
	      'description' => array(
	        'title'       => __( 'Description', 'wc4jp-epsilon' ),
	        'type'        => 'textarea',
	        'description' => __( 'This controls the description which the user sees during checkout.', 'wc4jp-epsilon' ),
	        'default'     => __( 'Pay with your Multi-currency Credit Card via Epsilon.', 'wc4jp-epsilon' )
	        ),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'wc4jp-epsilon' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc4jp-epsilon' ),
				'default'     => __( 'Proceed to Epsilon Multi-currency Credit Card', 'wc4jp-epsilon' )
			),
			'setting_mccc_krw' => array(
				'title'       => __( 'Set Use Currency', 'wc4jp-epsilon' ),
				'id'              => 'wc-epsilon-mccc-krw',
				'type'        => 'checkbox',
				'label'       => __( 'South Korean Won', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_cny' => array(
				'id'              => 'wc-epsilon-mccc-cny',
				'type'        => 'checkbox',
				'label'       => __( 'Chinese Yuan', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_twd' => array(
				'id'              => 'wc-epsilon-mccc-twd',
				'type'        => 'checkbox',
				'label'       => __( 'Taiwan New Dollars', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_usd' => array(
				'id'              => 'wc-epsilon-mccc-usd',
				'type'        => 'checkbox',
				'label'       => __( 'US Dollars', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_hkd' => array(
				'id'              => 'wc-epsilon-mccc-hkd',
				'type'        => 'checkbox',
				'label'       => __( 'Hong Kong Dollar', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_aud' => array(
				'id'              => 'wc-epsilon-mccc-aud',
				'type'        => 'checkbox',
				'label'       => __( 'Australian Dollars', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_thb' => array(
				'id'              => 'wc-epsilon-mccc-thb',
				'type'        => 'checkbox',
				'label'       => __( 'Thai Baht', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_gbp' => array(
				'id'              => 'wc-epsilon-mccc-gbp',
				'type'        => 'checkbox',
				'label'       => __( 'Pounds Sterling', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_sgd' => array(
				'id'              => 'wc-epsilon-mccc-sgd',
				'type'        => 'checkbox',
				'label'       => __( 'Singapore Dollar', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_cad' => array(
				'id'              => 'wc-epsilon-mccc-cad',
				'type'        => 'checkbox',
				'label'       => __( 'Canadian Dollars', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_eur' => array(
				'id'              => 'wc-epsilon-mccc-eur',
				'type'        => 'checkbox',
				'label'       => __( 'Euros', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_myr' => array(
				'id'              => 'wc-epsilon-mccc-myr',
				'type'        => 'checkbox',
				'label'       => __( 'Malaysian Ringgits', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_php' => array(
				'id'              => 'wc-epsilon-mccc-php',
				'type'        => 'checkbox',
				'label'       => __( 'Philippine Pesos', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_inr' => array(
				'id'              => 'wc-epsilon-mccc-inr',
				'type'        => 'checkbox',
				'label'       => __( 'Indian Rupee', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_rub' => array(
				'id'              => 'wc-epsilon-mccc-rub',
				'type'        => 'checkbox',
				'label'       => __( 'Russian Ruble', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_vnd' => array(
				'id'              => 'wc-epsilon-mccc-vnd',
				'type'        => 'checkbox',
				'label'       => __( 'Vietnamese Dong', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_nok' => array(
				'id'              => 'wc-epsilon-mccc-nok',
				'type'        => 'checkbox',
				'label'       => __( 'Norwegian Krone', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_sek' => array(
				'id'              => 'wc-epsilon-mccc-sek',
				'type'        => 'checkbox',
				'label'       => __( 'Swedish Krona', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_chf' => array(
				'id'              => 'wc-epsilon-mccc-chf',
				'type'        => 'checkbox',
				'label'       => __( 'Swiss Franc', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_dkk' => array(
				'id'              => 'wc-epsilon-mccc-dkk',
				'type'        => 'checkbox',
				'label'       => __( 'Danish Krone', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_mccc_brl' => array(
				'id'              => 'wc-epsilon-mccc-brl',
				'type'        => 'checkbox',
				'label'       => __( 'Brazilian Real', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
	      'testmode' => array(
	        'title'       => __( 'Test Mode', 'wc4jp-epsilon' ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable Epsilon Test mode For Multi-currency Credit Card', 'wc4jp-epsilon' ),
			'default'     => 'yes',
			'description' => sprintf( __( 'Please check you want to use Epsilon Test mode for Multi-currency Credit Card.', 'wc4jp-epsilon' )),
	        ),
		);
		}

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-epsilon-request.php' );

		$order          = wc_get_order( $order_id );
		$epsilon_request = new WC_Gateway_Epsilon_Request( $this );
		$response_array = $epsilon_request->get_order_to_epsilon( $order ,$this->contract_code, $this->st_code, $this->testmode, $this->id);

		if($response_array['error_msg']){
			$order->add_order_note('Error : '.mb_convert_encoding($response_array['error_msg'],'UTF-8','SJIS'));
			if(is_checkout())wc_add_notice( mb_convert_encoding($response_array['error_msg'],'UTF-8','SJIS'), $notice_type = 'error' );
		}else{
			return array(
				'result'   => 'success',
				'redirect' => $response_array['redirect_url']
			);
		}
	}

	function receipt_page( $order ) {
		echo '<p>' . __( 'Thank you for your order.', 'wc4jp-epsilon' ) . '</p>';
	}


	/**
	 * Get post data if set
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $_POST[ $name ];
		}
		return null;
	}
}
	/**
	 * Add the gateway to woocommerce
	 */
	function add_wc_epsilon_pro_mccc_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Epsilon_Pro_MCCC';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_wc_epsilon_pro_mccc_gateway' );
