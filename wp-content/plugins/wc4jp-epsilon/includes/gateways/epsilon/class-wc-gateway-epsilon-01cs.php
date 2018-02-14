<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Epsilon Pro Payment Gateway
 *
 * Provides a Epsilon Convenience Store Payment Gateway (Link Type).
 *
 * @class 			WC_Epsilon
 * @extends		WC_Gateway_Epsilon_Pro_CS
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author			Artisan Workshop
 */
class WC_Gateway_Epsilon_Pro_CS extends WC_Payment_Gateway {


	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'epsilon_pro_cs';
		$this->has_fields        = false;
		$this->method_title      = __( 'Epsilon Convenience store', 'wc4jp-epsilon' );

        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'wc4jp-epsilon' ).$this->method_title;
		}

		include_once( 'includes/class-wc-cs-fee.php' );
		new WooCommerce_Epsilon_CS_Fee();

		//Epsilon Setting IDs
		$this->contract_code = get_option('wc-epsilon-pro-cid');
		$this->contract_password = get_option('wc-epsilon-pro-cpass');
		$this->st_code = '00100-0000-00000-00000-00000-00000-00000';

        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Epsilon Convenience store', 'wc4jp-epsilon' );
		$this->method_description = __( 'Allows payments by Epsilon Convenience store in Japan.', 'wc4jp-epsilon' );

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

        // Set Convenience Store
		$this->cs_stores = array();
		if(isset($this->setting_cs_se) and $this->setting_cs_se=='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Seven Eleven', 'wc4jp-epsilon' ) => '11' ));
		if(isset($this->setting_cs_fm) and $this->setting_cs_fm =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Family Mart', 'wc4jp-epsilon' ) => '21' ));
		if(isset($this->setting_cs_ls) and $this->setting_cs_ls =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Lawson', 'wc4jp-epsilon' ) => '31' ));
		if(isset($this->setting_cs_sm) and $this->setting_cs_sm =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Seicomart', 'wc4jp-epsilon' ) => '32'));
		if(isset($this->setting_cs_ms) and $this->setting_cs_ms =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Mini Stop', 'wc4jp-epsilon' ) => '33' ));
		if(isset($this->setting_cs_ck) and $this->setting_cs_ck =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Circle K', 'wc4jp-epsilon' ) => '35' ));
		if(isset($this->setting_cs_tk) and $this->setting_cs_tk =='yes') $this->cs_stores = array_merge($this->cs_stores, array( __( 'Thanks', 'wc4jp-epsilon' ) => '36' ));

		// Actions
//		add_action( 'woocommerce_receipt_epsilon_pro_cs',                              array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_epsilon_pro_cs', array( $this, 'thankyou_page' ) );
	}

	/**
	 * Initialize Gateway Settings Form Fields.
	 */
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'wc4jp-epsilon' ),
				'label'       => __( 'Enable Epsilon Convenience store Payment', 'wc4jp-epsilon' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'wc4jp-epsilon' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc4jp-epsilon' ),
				'default'     => __( 'Convenience store (Epsilon)', 'wc4jp-epsilon' )
			),
			'description' => array(
				'title'       => __( 'Description', 'wc4jp-epsilon' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc4jp-epsilon' ),
				'default'     => __( 'Pay with your Convenience store via Epsilon.', 'wc4jp-epsilon' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'wc4jp-epsilon' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc4jp-epsilon' ),
				'default'     => __( 'Proceed to Epsilon Convenience store', 'wc4jp-epsilon' )
			),
			'instructions' => array(
				'title'       => __( 'Instructions', 'wc4jp-epsilon' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc4jp-epsilon' ),
				'default'     => __( 'Thank you for your order. Please check the e-mail from Epsilon.', 'wc4jp-epsilon' ),
				'desc_tip'    => true,
			),
			'setting_cs_se' => array(
				'title'       => __( 'Set Convenience Store', 'wc4jp-epsilon' ),
				'id'              => 'wc-epsilon-cs-se',
				'type'        => 'checkbox',
				'label'       => __( 'Seven Eleven', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_fm' => array(
				'id'              => 'wc-epsilon-cs-fm',
				'type'        => 'checkbox',
				'label'       => __( 'Family Mart', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_ls' => array(
				'id'              => 'wc-epsilon-cs-ls',
				'type'        => 'checkbox',
				'label'       => __( 'Lawson', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_sm' => array(
				'id'              => 'wc-epsilon-cs-sm',
				'type'        => 'checkbox',
				'label'       => __( 'Seicomart', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_ms' => array(
				'id'              => 'wc-epsilon-cs-ms',
				'type'        => 'checkbox',
				'label'       => __( 'Mini Stop', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_ck' => array(
				'id'              => 'wc-epsilon-cs-ck',
				'type'        => 'checkbox',
				'label'       => __( 'Circle K', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'setting_cs_tk' => array(
				'id'              => 'wc-epsilon-cs-tk',
				'type'        => 'checkbox',
				'label'       => __( 'Thanks', 'wc4jp-epsilon' ),
				'default'     => 'yes',
			),
			'testmode' => array(
				'title'       => __( 'Test Mode', 'wc4jp-epsilon' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Epsilon Test mode For Convenience store', 'wc4jp-epsilon' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Please check you want to use Epsilon Test mode for Convenience store.', 'wc4jp-epsilon' )),
			),
		);
	}

	function cs_select() {
		?><select name="cvs_company_id">
		<?php 
		foreach($this->cs_stores as $num_id => $value){?>
			<option value="<?php echo $value; ?>"><?php echo $num_id;?></option>
		<?php }?>
		</select>
		<?php  
	}
	/**
	 * UI - Payment page fields for Epsilon Payment.
	*/
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
			<p><?php echo $this->description; ?></p>
      	<?php } ?>
			<fieldset  style="padding-left: 40px;">
            <p><?php _e( 'Please select Convenience Store where you want to pay', 'wc4jp-epsilon' );?></p>
        <?php $this->cs_select(); ?>
		</fieldset>
<?php
	}


		/**
		 * Process the payment and return the result.
		 */
		function process_payment( $order_id ) {
			include_once( 'includes/class-wc-gateway-epsilon-request.php' );

			$order          = wc_get_order( $order_id );
			$epsilon_request = new WC_Gateway_Epsilon_Request( $this );
			$response_array = $epsilon_request->get_order_to_epsilon( $order ,$this->contract_code, $this->st_code, $this->testmode, $this->id, $this->get_post( 'cvs_company_id' ));
			add_post_meta( $order_id, '_cvs_company_id', $this->get_post( 'cvs_company_id' ), true ) || update_post_meta( $order_id, '_cvs_company_id', $this->get_post( 'cvs_company_id' ));
			if($response_array['error_msg']){
				$order->add_order_note('Error : '.mb_convert_encoding($response_array['error_msg'],'UTF-8','SJIS'));
				if(is_checkout())wc_add_notice( mb_convert_encoding($response_array['error_msg'],'UTF-8','SJIS'), $notice_type = 'error' );
			}else{
				$cs_stores_ids = array(
					'11' => __('Seven Eleven', 'wc4jp-epsilon' ),
					'21' => __('Family Mart', 'wc4jp-epsilon' ),
					'31' => __( 'Lawson', 'wc4jp-epsilon' ),
					'32' => __( 'Seicomart', 'wc4jp-epsilon' ),
					'33' => __( 'Mini Stop', 'wc4jp-epsilon' ),
					'35' => __( 'Circle K', 'wc4jp-epsilon' ),
					'36' => __( 'Thanks', 'wc4jp-epsilon' )
				);

				// Mark as on-hold (we're awaiting the payment)
				$order->update_status( 'on-hold', __( 'Awaiting Convenience Store payment', 'wc4jp-epsilon' ).'('.$cs_stores_ids[$this->get_post( 'cvs_company_id' )].')' );

				// Reduce stock levels
				$order->reduce_order_stock();

				// Remove cart
				WC()->cart->empty_cart();
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order )
				);
			}
		}

		function receipt_page( $order ) {
			echo '<p>' . __( 'Thank you for your order. Please cehck e-mail from Epsilon', 'wc4jp-epsilon' ) . '</p>';
		}
	/**
	 * Output for the order received page.
	 */
	public function thankyou_page( $order_id ) {

		if ( $this->instructions ) {
			echo wpautop( wptexturize( wp_kses_post( $this->instructions ) ) );
		}
		$this->cs_details( $order_id );
	}

	/**
	 * Get Convenience Store details 
	 */
	private function cs_details( $order_id = '' ) {
		$cs_stores_ids = array(
		'11' => __('Seven Eleven', 'wc4jp-epsilon' ),
		'21' => __('Family Mart', 'wc4jp-epsilon' ),
		'31' => __( 'Lawson', 'wc4jp-epsilon' ),
		'32' => __( 'Seicomart', 'wc4jp-epsilon' ),
		'33' => __( 'Mini Stop', 'wc4jp-epsilon' ),
		'35' => __( 'Circle K', 'wc4jp-epsilon' ),
		'36' => __( 'Thanks', 'wc4jp-epsilon' )
		);
		echo '<p class="cv_detail">'.__('Your selected Convenience Store : ', 'wc4jp-epsilon' ).$cs_stores_ids[get_post_meta($order_id,'_cvs_company_id',true)].'</p><br />';
	}

	/*
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
function add_wc_epsilon_pro_cs_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Epsilon_Pro_CS';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_epsilon_pro_cs_gateway' );
