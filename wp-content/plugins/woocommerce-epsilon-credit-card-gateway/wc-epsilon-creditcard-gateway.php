<?php

/**
 * Plugin Name: WooCommerce Epsilon Payment Gateway
 * Plugin URI: http://cmitexperts.com/
 * Description: Woocommerce Epsilon Credit cart payment gateway.
 * Version: 1111.0.0
 * Author: CMITEXPERT
 * Author URI: http://cmitexperts.com/
 * Requires at least: 3.8
 * Tested up to: 3.9 or higher
 * Text Domain: wc-epsilon
 * Domain Path: /i18n/
 *
 * @package WordPress
 * @author Artisan Workshop
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'plugins_loaded', 'woocommerce_gmo_epsilon_creditcard_init', 0 );

function woocommerce_gmo_epsilon_creditcard_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
    return;
  };

  DEFINE ('PLUGIN_DIR', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) . '/' );

	/**
	 * GMO Epsilon Gateway Class
	 */
		class WC_Epsilon extends WC_Payment_Gateway {

			function __construct() {

        // Register plugin information
	      $this->id			    = 'epsilon';
	      $this->has_fields = true;
	      $this->supports   = array(
               'products',
               'subscriptions',
               'subscription_cancellation',
               'subscription_suspension',
               'subscription_reactivation',
               'subscription_date_changes',
               );

        // Create plugin fields and settings
				$this->init_form_fields();
				$this->init_settings();
				$this->init();
		$this->method_title       = __( 'Epsilon Credit Card Payment Gateway', 'wc-epsilon' );
		$this->method_description = __( 'Allows payments by Epsilon Credit Card in Japan.', 'wc-epsilon' );

				// Get setting values
				foreach ( $this->settings as $key => $val ) $this->$key = $val;

        // Load plugin checkout icon
	      //$this->icon = PLUGIN_DIR . 'images/cards.png';

        // Add hooks
				add_action( 'admin_notices',                                            array( $this, 'epsilon_commerce_ssl_check' ) );
				add_action( 'woocommerce_receipt_epsilon',                              array( $this, 'receipt_page' ) );
				add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				add_action( 'wp_enqueue_scripts',                                       array( $this, 'add_epsilon_scripts' ) );
//				add_action( 'scheduled_subscription_payment_epsilon',                   array( $this, 'process_scheduled_subscription_payment'), 0, 3 );
		  }

	/**
	 * Init WooCommerce Payment Gateway Credit Card- GMO Epsilon when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/*
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wc-epsilon' );
		// Global + Frontend Locale
		load_plugin_textdomain( 'wc-epsilon', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n" );
	}

      /**
       * Check if SSL is enabled and notify the user.
       */
      function epsilon_commerce_ssl_check() {
        if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'no' && $this->enabled == 'yes' ) {
            echo '<div class="error"><p>' . sprintf( __('Epsilon Commerce is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'wc-epsilon' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
            }
      }

      /**
       * Initialize Gateway Settings Form Fields.
       */
	    function init_form_fields() {

	      $this->form_fields = array(
	      'enabled'     => array(
	        'title'       => __( 'Enable/Disable', 'wc-epsilon' ),
	        'label'       => __( 'Enable Epsilon Payment', 'wc-epsilon' ),
	        'type'        => 'checkbox',
	        'description' => '',
	        'default'     => 'no'
	        ),
	      'title'       => array(
	        'title'       => __( 'Title', 'wc-epsilon' ),
	        'type'        => 'text',
	        'description' => __( 'This controls the title which the user sees during checkout.', 'wc-epsilon' ),
	        'default'     => __( 'Credit Card (Epsilon)', 'wc-epsilon' )
	        ),
	      'description' => array(
	        'title'       => __( 'Description', 'wc-epsilon' ),
	        'type'        => 'textarea',
	        'description' => __( 'This controls the description which the user sees during checkout.', 'wc-epsilon' ),
	        'default'     => __( 'Pay with your credit card via Epsilon.', 'wc-epsilon' )
	        ),
	      'contract_code'    => array(
	        'title'       => __( 'Contract Code', 'wc-epsilon' ),
	        'type'        => 'text',
	        'description' => __( 'This is the API Contract Code generated within the epsilon payment gateway.', 'wc-epsilon' ),
	        'default'     => ''
	        ),
			'security_check' => array(
				'title'       => __( 'Security Check Code', 'wc-epsilon' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Security Check Code', 'wc-epsilon' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Require customer to enter credit card CVV code (Security Check Code).', 'wc-epsilon' )),
			),
			'testing' => array(
				'title'       => __( 'Gateway Testing', 'wc-epsilon' ),
				'type'        => 'title',
				'description' => '',
			),
			'testmode' => array(
				'title'       => __( 'Epsilon Test mode', 'wc-epsilon' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Epsilon Test mode', 'wc-epsilon' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Please check you want to use Epsilon Test mode.', 'wc-epsilon' )),
			)
			);
		  }


      /**
       * UI - Admin Panel Options
       */
			function admin_options() { ?>
				<h3><?php _e( 'Epsilon Credit Card Payment','wc-epsilon' ); ?></h3>
			  
			    <table class="form-table">
					<?php $this->generate_settings_html(); ?>
				</table>
			<?php } 
      /**
       * UI - Payment page fields for Epsilon Payment.
       */
			function payment_fields() { ?>
<div id="card-info">
								<?php
          		// Description of payment method from settings
          		if ( $this->description ) { ?>
            		<!--<p class="form__description p4"><?php //echo $this->description; ?></p>-->
      		<?php } ?>
			<fieldset>
		        <?php
		          $user = wp_get_current_user();
				  $customer_check = $this->user_has_stored_data( $user->ID );
		          if ( $customer_check['err_code']!=801) { ?>
						
							<div class="field-wrapper" style="display: none;">
								<input type="radio" name="epsilon-use-stored-payment-info" id="epsilon-use-stored-payment-info-yes" value="yes"  class="paymethod" onclick="entryChange1();" /><label for="epsilon-use-stored-payment-info-yes" class="form-row__inline-label control-label"><?php _e( '保存済みのクレジットカード情報を使う', 'wc-epsilon' ) ?></label>
				</div>
								<div id="epsilon-stored-info">
				                    <p><?php if($customer_check['result']==1):?>
											<?php _e( 'カード下四桁 ', 'wc-epsilon' ) ?><?php echo $customer_check['card_number_mask']; ?> (<?php echo $customer_check['card_bland']; ?>)
											<br /><?php elseif($method['result']==9):?>
											<?php echo $method['err_detail']; ?> (<?php echo $method['err_code']; ?>)
											<br /><?php elseif($method['result']==3):?>
											<?php echo _e('Not Found your infomation, Epsilon maybe delete your infomation.', 'wc-epsilon' ); ?>
											<br /><?php endif;?>
				                    </p>
							</div>
						
						
							<div class="field-wrapper">
								<input type="radio" name="epsilon-use-stored-payment-info" id="epsilon-use-stored-payment-info-no" value="no" checked="checked" class="paymethod" onclick="entryChange1();"/>
		                  		<label for="epsilon-use-stored-payment-info-no" class="form-row__inline-label control-label"><?php _e( 'Use a new payment method', 'wc-epsilon' ) ?></label>
		                	</div>
		                	
						
				<?php } ?>
              			
							
              				<!-- Show input boxes for new data -->
              				<div id="epsilon-new-info" <?php if ( $customer_check['err_code']!=801) { ?>style=""<?php } ?>>
								<div class="order--checkout--limit">
              					
								
								<!-- Credit card number -->
                    			<div class="form-row form-row-first">
									<label class="form-row__label light-copy" for="ccnum"><?php echo __( 'Credit Card number', 'wc-epsilon' ) ?> <span class="required">*</span></label>
									<input type="text" class="input-text" id="card_number" name="card_number" maxlength="16" />
									<input type="hidden" class="input-text" id="token_cc" name="token"/>
									<input type="hidden" class="input-text" id="maskedCardNo" name="maskedCardNo"/>
									<input type="hidden" class="input-text" id="holdername" name="holdername" value="EPSILON TAROU"/>
									<input type="hidden" class="input-text" id="contract_code" name="contract_code" value="<?php echo $this->contract_code?>"/>
									<ul class="credit-card-icons">
										<li class="payment-icon payment-icon--visa"></li>
										<li class="payment-icon payment-icon--master"></li>
										<li class="payment-icon payment-icon--jcb"></li>
										<li class="payment-icon payment-icon--diners"></li>
										<li class="payment-icon payment-icon--amex"></li>
									</ul>
                    			</div>
									
								<!-- Credit card type -->
								<!-- Credit card expiration -->
                    			<div class="form-row form-row-nomargin">
                      				<label class="form-row__label light-copy" for="cc-expire-month"><?php echo __( 'Expiration date', 'wc-epsilon') ?> <span class="required">*</span></label>
									<div class="input-list input-list--split">
									<div class="form-row month">
									<div class="dropdown">
                      				<select name="expire_m" id="expire_m" class="woocommerce-select woocommerce-cc-month">
                        				<option value=""><?php _e( 'Month', 'wc-epsilon' ) ?></option><?php
				                        $months = array();
				                        for ( $i = 1; $i <= 12; $i ++ ) {
				                          $timestamp = mktime( 0, 0, 0, $i, 1 );
				                          $months[ date( 'n', $timestamp ) ] = date( 'n', $timestamp );
				                        }
				                        foreach ( $months as $num => $name ) {
				                          printf( '<option value="%u">%s</option>', $num, $name );
				                        } ?>
                      				</select>
									</div><!--/dropdown-->
									</div>
									<div class="form-row year">
									<div class="dropdown">
                      				<select name="expire_y" id="expire_y" class="woocommerce-select woocommerce-cc-year">
                        				<option value=""><?php _e( 'Year', 'wc-epsilon' ) ?></option><?php
				                        $years = array();
				                        for ( $i = date( 'y' ); $i <= date( 'y' ) + 15; $i ++ ) {
				                          printf( '<option value="20%u">20%u</option>', $i, $i );
				                        } ?>
                      				</select>
									</div><!--/dropdown-->
									</div>
									</div>
                    			</div>
								<?php

				                    // Credit card security code
				                    if ( $this->security_check == 'yes' ) { ?>
				                      <div class="form-row">
				                        <label class="form-row__label light-copy" for="cvv"><?php _e( 'Card security code', 'wc-epsilon' ) ?> <span class="required">*</span></label>
				                        <input oninput="validate_cvv(this.value)" type="text" class="input-text" id="cvv" name="security_code" maxlength="4" />
				                        <div class="form-caption"><?php _e( '3 or 4 digits usually found on the signature strip.', 'wc-epsilon' ) ?></div>
				                      </div><?php
				                    }

			                    // Option to store credit card data
			                    if ( $this->saveinfo == 'yes' && ! ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) ) { ?>
			                      	
										<div class="form-row ">
											<div class="field-wrapper">
			                        		<label class="form-row__inline-label control-label checkbox icon--tick" for="saveinfo"><?php _e( 'Save this billing method?', 'wc-epsilon' ) ?></label>
			                        		<input type="checkbox" class="input-checkbox" id="saveinfo" name="saveinfo" />
			                        		<div class="form-caption"><?php _e( 'Select to store your billing information for future use.', 'wc-epsilon' ) ?></div>
											</div>
			                      		</div>
									<?php  } ?>
								</div><!--/order--checkout--limit-->
								</div><!--/#epsilon-new-info-->
			
							
            			
			</fieldset>
            </div>
<?php if ( $customer_check['err_code']!=801) { ?>
<script type="text/javascript">
	function entryChange1(){
		radio = document.getElementsByName('epsilon-use-stored-payment-info') 
		if(radio[0].checked) {
			//フォーム
			document.getElementById('epsilon-stored-info').style.display = "";
			document.getElementById('epsilon-new-info').style.display = "none";
		}else if(radio[1].checked) {
			//フォーム
			document.getElementById('epsilon-stored-info').style.display = "none";
			document.getElementById('epsilon-new-info').style.display = "";
		}
	}
	window.onload = entryChange1;

</script>
<?php } ?>
      <!--      <script>
				jQuery(document).ready(function(){
					
					if(jQuery("#payment_method_epsilon:checked").length == 1){
						
						validate_checkout_button();
						
					}
					
					jQuery(document).on("click",".wc_payment_method",function(){
						validate_checkout_button();
					});
					
					jQuery(document).on("keyup","#card_number",function(){
						validate_checkout_button();
					});
					
					jQuery(document).on("change","#expire_y",function(){
						validate_checkout_button();
					});
					
					
					jQuery(document).on("change","#expire_m",function(){
						validate_checkout_button();
					});
					
					jQuery(document).on("change","#cvv",function(){
						validate_checkout_button();
					});
						  
				});
				
				function validate_checkout_button(){
					
					var ep_card = jQuery("#card_number").val();
					var ep_expire_y = jQuery("#expire_y").val();
					var ep_expire_m = jQuery("#expire_m").val();
					var ep_cvv = jQuery("#cvv").val();
					
					console.log(ep_card +"=="+ ep_expire_y + "==" + ep_expire_m +'=='+ ep_cvv);
					radio = document.getElementsByName('epsilon-use-stored-payment-info')
					if(radio[1].checked) {
					
					if(ep_card != '' && ep_expire_y != '' && ep_expire_m != '' && ep_cvv!=''){
						jQuery("#place_order").removeAttr("disabled");
					} else {
						jQuery("#place_order").attr("disabled","disabled");
					}
					} else {
						jQuery("#place_order").removeAttr("disabled");
					}
				}
			</script>-->
            
<?php
    }

		/**
		 * Process the payment and return the result.
		 */
		function process_payment( $order_id ) {

			global $woocommerce;

			$order = new WC_Order( $order_id );
      $user = new WP_User( $order->user_id );
	  // required request information
	$base_request = array (
		'user_name'   => $order->billing_first_name." ".$order->billing_last_name,
		'user_mail_add'       => $order->billing_email,
		'order_number' 	=> $order->id,
		'item_price' 	    => $order->order_total,
	);
	if($order->user_id){
		$base_request['user_id']   = $order->user_id;
	}else{
		$base_request['user_id']   = $order->id.'-user';
	}
			

		//
			if ( sizeof( $order->get_items() ) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					if ( $item['qty'] ) {
						if ($item === end($order->get_items())) {
						// last
						$item_names .= $item['name'];
						$item_codes .= $item['product_id'];
						}else{
						$item_names .= $item['name'].' ';
						$item_codes .= $item['product_id'].'-';
						}
					}
				}
			}
			$item_names = substr($item_names, 0, 64);
			$item_codes = substr($item_codes, 0, 64);
			if(!$item_names)$item_names='no-items';
			if(!$item_codes)$item_codes='no-item-codes';
		$base_request['item_name'] = $item_names;
		$base_request['item_code'] = $item_codes;

      // Create server request using stored or new payment details
		if ( $this->get_post( 'epsilon-use-stored-payment-info' ) == 'yes' ) {
			$base_request['process_code'] 		= 2;//Using stored payment

		} else {

		//Credit Card Infomation
//         $base_request['card_number'] 	= $this->get_post( 'card_number' );
//         $base_request['expire_m'] 	= $this->get_post( 'expire_m' );
//         $base_request['expire_y'] 	= $this->get_post( 'expire_y' );

		$base_request['process_code'] 	= 1;//First time payment
		$base_request['token'] 	= $this->get_post( 'token' );
		$base_request['card_st_code'] 	= 10;

        // Using Security Check
        if ( $this->security_check == 'yes' ) {
			$base_request['security_code'] 	= $this->get_post( 'security_code' );
			$base_request['security_check'] 	= 1;
		}

      }

      // Add transaction-specific details to the request
      $transaction_details = array (
        'contract_code' => $this->contract_code,
        'st_code' 	=> '10000-0000-00000',
        'user_agent' 	    => $_SERVER['HTTP_USER_AGENT'],
		'mission_code' => 1,
		'memo2' 	=> 'woocommerce',
        );

		// Send request and get response from server
		$response = $this->post_and_get_response( array_merge( $transaction_details,$base_request ) );
		$respsnse['err_detail'] = $respsnse['err_detail'];

      // Check response
      if ( $response['result'] == 1 ) {
        // Success
        $order->add_order_note( __( 'Epsilon Payment payment completed. Transaction ID: ' , 'wc-epsilon' ) . $response['trans_code'] );
        $order->payment_complete();
        update_user_meta($user->ID, 'epsilon_cc_removed', 0);

        // Return thank you redirect
        return array (
          'result'   => 'success',
          'redirect' => $this->get_return_url( $order ),
        );

      } else if ( $response['result'] == 3 ) {//3DS
		$term_url = $this->get_return_url( $order );
		session_start();
		$_SESSION['acsurl'] = urldecode($response['acsurl']);
		$_SESSION['PaReq'] = urldecode($response['pareq']);
		$_SESSION['TermUrl'] = urldecode($term_url);
		$_SESSION['MD'] = $order->id;
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( '3DSecure Payment Processing.', 'woocommerce-4jp' ) );

			// Reduce stock levels
			$order->reduce_order_stock();

			// Remove cart
			WC()->cart->empty_cart();
			return array(
				'result' 	=> 'success',
				'redirect'	=> plugins_url('/woocommerce-for-gmo-epsilon-credit-card/3ds.php')
			);
			
      } else if ( $response['result'] == 9 ) {//System Error
        // Other transaction error
        $order->add_order_note( __( 'Epsilon Payment failed. Sysmte Error: ', 'wc-epsilon' ) . $response['err_code'] .':'. $response['err_detail'] .':'.$response['trans_code']);
        wc_add_notice( __( 'Sorry, there was an error: ', 'wc-epsilon' ) . $response['err_code'], $notice_type = 'error' );
      } else {
        // No response or unexpected response
        $order->add_order_note( __( "Epsilon Payment failed. Some trouble happened.", 'wc-epsilon' ). $response['err_code'] .':'. $response['err_detail'].':'.$response['trans_code'] );
        wc_add_notice( __( 'No response from payment gateway server. Try again later or contact the site administrator.', 'wc-epsilon' ). $response['err_code'], $notice_type = 'error' );

      }

	}

		/**
		 * Process a payment for an ongoing subscription.
		 */
    function process_scheduled_subscription_payment( $amount_to_charge, $order, $product_id ) {

      $user = new WP_User( $order->user_id );
      $customer_vault_ids = get_user_meta( $user->ID, 'customer_vault_ids', true );
      $payment_method_number = get_post_meta( $order->id, 'payment_method_number', true );

      $inspire_request = array (
				'username' 		      => $this->username,
				'password' 	      	=> $this->password,
				'amount' 		      	=> $amount_to_charge,
				'type' 			        => $this->salemethod,
				'billing_method'    => 'recurring',
        );

      $id = $customer_vault_ids[ $payment_method_number ];
      if( substr( $id, 0, 1 ) !== '_' ) $inspire_request['customer_vault_id'] = $id;
      else {
        $inspire_request['customer_vault_id'] = $user->user_login;
        $inspire_request['billing_id']        = substr( $id , 1 );
        $inspire_request['ver']               = 2;
      }

      $response = $this->post_and_get_response( $inspire_request );

      if ( $response['response'] == 1 ) {
        // Success
        $order->add_order_note( __( 'Epsilon Payment scheduled subscription payment completed. Transaction ID: ' , 'wc-epsilon' ) . $response['transactionid'] );
        WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );

			} else if ( $response['response'] == 2 ) {
        // Decline
        $order->add_order_note( __( 'Epsilon Payment scheduled subscription payment failed. Payment declined.', 'wc-epsilon') );
        WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );

      } else if ( $response['response'] == 3 ) {
        // Other transaction error
        $order->add_order_note( __( 'Epsilon Payment scheduled subscription payment failed. Error: ', 'wc-epsilon') . $response['responsetext'] );
        WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );

      } else {
        // No response or unexpected response
        $order->add_order_note( __('Epsilon Payment scheduled subscription payment failed. Couldn\'t connect to gateway server.', 'wc-epsilon') );

      }
    }

    /**
     * Check if the user has any billing records in the Customer Vault
     */
    function user_has_stored_data( $user_id ) {
    	$removed_epsilon = get_user_meta($user_id, 'epsilon_cc_removed', true);
    	if ($removed_epsilon)
    	{
    		return array('err_code' => 801);
    	}
    	
		if( $this->testmode == 'no' ){
		$get_user_info_url = "https://secure.epsilon.jp/cgi-bin/order/get_user_info.cgi";
		}else{
		$get_user_info_url = "https://beta.epsilon.jp/cgi-bin/order/get_user_info.cgi";
		}

		$post_data = array(
			"contract_code" => $this->contract_code ,
			"user_id"=>$user_id
		);
		// make new cURL resource
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		$array = explode("\n", $output);
		foreach($array as $value){
			$title = substr($value,10,5);
			switch($title){
				case 'card_':
				if(substr($value,10,6)=='card_n'){
				$result['card_number_mask'] = substr(substr($value,65),0,-4);
				}elseif(substr($value,10,6)=='card_b'){
				$result['card_bland'] = substr(substr($value,22),0,-4);
				}elseif(substr($value,10,6)=='card_e'){
					$result['card_expire'] = substr(substr($value,23),0,-4);
					$expire_year = substr($result['card_expire'], 0, 4);
					$expire_date = substr($result['card_expire'], 4);
					$result['card_expire'] = $expire_date . '/' . $expire_year;
				}
				break;
				case 'err_c':
				$result['err_code'] = substr(substr($value,20),0,-4);
				break;
				case 'err_d':
				$result['err_detail'] = substr(substr($value,22),0,-4);
				break;
				case 'resul':
				if(substr($value,10,7)!="result>"){
					$result['result'] = substr(substr($value,18),0,-4);
				}
				break;
			}
		}
      return $result;
    }

    /**
     * Check payment details for valid format
     */
		function validate_fields() {

      if ( $this->get_post( 'epsilon-use-stored-payment-info' ) == 'yes' ) return true;

			global $woocommerce;

			// Check for saving payment info without having or creating an account
			if ( $this->get_post( 'saveinfo' )  && ! is_user_logged_in() && ! $this->get_post( 'createaccount' ) ) {
        wc_add_notice( __( 'Sorry, you need to create an account in order for us to save your payment information.', 'wc-epsilon'), $notice_type = 'error' );
        return false;
      }

			$cardNumber          = $this->get_post( 'card_number' );
			$cardCSC             = $this->get_post( 'security_code' );
			$cardExpirationMonth = $this->get_post( 'expire_m' );
			$cardExpirationYear  = $this->get_post( 'expire_y' );

			// Check card number
			if ( empty( $cardNumber ) || ! ctype_digit( $cardNumber ) ) {
				wc_add_notice( __( 'Card number is invalid.', 'wc-epsilon' ), $notice_type = 'error' );
				return false;
			}

			if ( $this->security_check == 'yes' ){
				// Check security code
				if ( ! ctype_digit( $cardCSC ) ) {
					wc_add_notice( __( 'Card security code is invalid (only digits are allowed).', 'wc-epsilon' ) , $notice_type = 'error');
					return false;
				}
				if ( ( strlen( $cardCSC ) >4 ) ) {
					wc_add_notice( __( 'Card security code is invalid (wrong length).', 'wc-epsilon' ), $notice_type = 'error' );
					return false;
				}
			}

			// Check expiration data
			$currentYear = date( 'Y' );

			if ( ! ctype_digit( $cardExpirationMonth ) || ! ctype_digit( $cardExpirationYear ) ||
				 $cardExpirationMonth > 12 ||
				 $cardExpirationMonth < 1 ||
				 $cardExpirationYear < $currentYear ||
				 $cardExpirationYear > $currentYear + 20
			) {
				wc_add_notice(__( 'Card expiration date is invalid', 'wc-epsilon' ), $notice_type = 'error' );
				return false;
			}

			// Strip spaces and dashes
			$cardNumber = str_replace( array( ' ', '-' ), '', $cardNumber );

			return true;

		}

		/**
     * Send the payment data to the gateway server and return the response.
     */
    private function post_and_get_response( $request ) {

		if($this->testmode=='no'){
		$direct_card_url = "https://secure.epsilon.jp/cgi-bin/order/direct_card_payment.cgi";
		}else{
		$direct_card_url = "https://beta.epsilon.jp/cgi-bin/order/direct_card_payment.cgi";
		}

// 		var_dump($direct_card_url);
// 		pr($request);die;
		// make new cURL resource
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
		curl_setopt($ch, CURLOPT_URL, $direct_card_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);

		if (false)
		{
			if (file_exists(dirname(dirname(__FILE__)) . '/wc4jp-epsilon/includes/gateways/epsilon/includes/xml/Unserializer.php'))
			{
				require_once dirname(dirname(__FILE__)) . '/wc4jp-epsilon/includes/gateways/epsilon/includes/xml/Unserializer.php';
			}
			else {
				require_once (plugin_dir_path(__FILE__) . 'Unserializer.php');
			}
			$temp_xml_res = str_replace("x-sjis-cp932", "UTF-8", $output);
			$unserializer =new XML_Unserializer();
			$unserializer->setOption('parseAttributes', TRUE);
			$unseriliz_st = $unserializer->unserialize($temp_xml_res);
			if ($unseriliz_st === true) {
				//xmlを解析
				$res_array = $unserializer->getUnserializedData();
				$is_xml_error = false;
				$xml_redirect_url = "";
				$xml_error_cd = "";
				$xml_error_msg = "";
				$xml_memo1_msg = "";
				$xml_memo2_msg = "";
				$result = "";
				$trans_code = "";
				foreach($res_array['result'] as $uns_k => $uns_v){
					list($result_atr_key, $result_atr_val) = each($uns_v);
			
					switch ($result_atr_key) {
						case 'redirect':
							$xml_redirect_url = rawurldecode($result_atr_val);
							break;
						case 'err_code':
							$is_xml_error = true;
							$xml_error_cd = $result_atr_val;
							break;
						case 'err_detail':
							$xml_error_msg = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
							break;
						case 'memo1':
							$xml_memo1_msg = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
							break;
						case 'memo2':
							$xml_memo2_msg = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
							break;
						case 'result':
							$result = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
							break;
						case 'trans_code':
							$trans_code = mb_convert_encoding(urldecode($result_atr_val), "UTF-8" ,"auto");
							break;
						default:
							break;
					}
				}
			
			}
		}
		$array = explode("\n", $output);
		foreach($array as $value){
			$title = substr($value,10,5);
			switch($title){
				case 'acsur':
				$result['acsurl'] = substr(substr($value,18),0,-4);
				break;
				case 'err_c':
				$result['err_code'] = substr(substr($value,20),0,-4);
				break;
				case 'err_d':
				$result['err_detail'] = substr(substr($value,22),0,-4);
				break;
				case 'pareq':
				$result['pareq'] = substr(substr($value,17),0,-4);
				break;
				case 'resul':
				if(substr($value,10,7)!="result>"){
					$result['result'] = substr(substr($value,18),0,-4);
				}
				break;
				case 'trans':
				$result['trans_code'] = substr(substr($value,22),0,-4);
				break;
				case 'kari_':
				$result['kari_flag'] = substr(substr($value,21),0,-4);
				break;
			}
		}

      // Return response array
      return $result;
    }


		function receipt_page( $order ) {
			echo '<p>' . __( 'Thank you for your order.', 'wc-epsilon' ) . '</p>';
		}

    /**
     * Include jQuery and our scripts
     */
    function add_epsilon_scripts() {

    	if( $this->testmode == 'no' ){
    		wp_enqueue_script( 'token', PLUGIN_DIR . 'js/token_live.js', array( 'jquery' ), 1.0 );
    	}
    	else{
    		wp_enqueue_script( 'token', PLUGIN_DIR . 'js/token_test.js', array( 'jquery' ), 1.0 );
    	}
    	wp_enqueue_script( 'get_token', PLUGIN_DIR . 'js/get_token.js', array( 'jquery' ), 1.0 );
    	
      if ( ! $this->user_has_stored_data( wp_get_current_user()->ID ) ) return;

      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'edit_billing_details', PLUGIN_DIR . 'js/edit_billing_details.js', array( 'jquery' ), 1.0 );

      if ( $this->security_check == 'yes' ) wp_enqueue_script( 'check_cvv', PLUGIN_DIR . 'js/check_cvv.js', array( 'jquery' ), 1.0 );

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

		/**
     * Check whether an order is a subscription
     */
		private function is_subscription( $order ) {
      return class_exists( 'WC_Subscriptions_Order' ) && WC_Subscriptions_Order::order_contains_subscription( $order );
		}

	}

	/**
	 * Add the gateway to woocommerce
	 */
	function add_epsilon_commerce_gateway( $methods ) {
		$methods[] = 'WC_Epsilon';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_epsilon_commerce_gateway' );

}