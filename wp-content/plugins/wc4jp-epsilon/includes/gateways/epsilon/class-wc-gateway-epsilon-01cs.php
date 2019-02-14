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
		$epsilon_response = get_post_meta($order_id, 'epsilon_response_array', true);
		$epsilon_data = array();
		foreach ( $epsilon_response['result'] as $uns_k => $uns_v )
		{
			list ($result_atr_key, $result_atr_val) = each($uns_v);
			$epsilon_data[$result_atr_key] = $result_atr_val;
		}
		if ($epsilon_data['conveni_code'] == 11) {
			$receiptLabel = '払込票番号';
			$instruction = '詳しいお支払い手順は下記マニュアルにてご確認いただけます。';
			$instUrl = 'http://www.epsilon.jp/mb/conv/seven/index.html?pay';
		} elseif ($epsilon_data['conveni_code'] == 31 || $epsilon_data['conveni_code'] == 32 || $epsilon_data['conveni_code'] == 33) {
			$receiptLabel = '受付番号';
			$phoneLabel = '電話番号/取扱番号';
			$instruction = 'お客様がご選択いただいたコンビニブランドはローソンその他ですが、以下のいずれのコンビニブランドでもお支払いただけます。<br/>(ローソン、ミニストップ、セイコーマート)<br/>コンビニ店頭の設置端末を操作し、受付番号を入力ください。';
			$instUrl01 = 'http://www.epsilon.jp/mb/conv/lawson/index.html?pay';
			$instUrl02 = 'http://www.epsilon.jp/mb/conv/seico/index.html?pay';
			$instUrl03 = 'http://www.epsilon.jp/mb/conv/ministop/index.html?pay';
		} elseif ($epsilon_data['conveni_code'] == 21) {
			$receiptLabel = '注文番号';
			$instruction = '詳しいお支払い手順は下記マニュアルにてご確認いただけます。';
			$instUrl = 'http://www.epsilon.jp/mb/conv/famima/index.html?pay';
		} elseif ($epsilon_data['conveni_code'] == 35 || $epsilon_data['conveni_code'] == 36) {
			$receiptLabel = 'お支払受付番号';
			$phoneLabel = '電話番号';
			$instruction = 'お近くのサークルＫサンクスの店頭にあるKステーション(情報端末)の画面で「各種支払い」を選択し、次の画面で「6ケタの番号をお持ちの方」を選択します。<br/>画面の表示に従い、「お支払受付番号」とお申込時の「電話番号」を入力してください。<br/>誤りがなければ受付票が発券されますので、レジに提示して、代金を現金にてお支払いください。';
		} else {
			$receiptLabel = '受付番号';
		}
		$order = wc_get_order( $order_id );
		$phone = get_post_meta($order_id, '_billing_phone', true);
		echo '<p class="cv_detail">';
		/**********start show total amount************************/
		echo '<span class="pay_amount"><span class="label">お支払い金額：</span><span class="value">' . $order->get_total() . '円</span></span>';
		/**********end show total amount************************/
		echo '<span class="cv_store"><span class="label">'.__('Your selected Convenience Store : ', 'wc4jp-epsilon' ).'</span><span class="value">'.$cs_stores_ids[get_post_meta($order_id,'_cvs_company_id',true)].'</span></span>';
		if ($epsilon_data['conveni_code'] == 21) {
			echo '<span class="kigyo_num"><span class="label">企業コード：</span><span class="value">' . $epsilon_data['kigyou_code'] . '</span></span>';
		}
		if ($epsilon_data['conveni_code'] == 11) {
			$hurl = urldecode($epsilon_data['haraikomi_url']);
			echo '<span class="harai_url"><span class="label">'. __('Harai url : ', 'wc4jp-epsilon' ).'</span><span class="value"><a href="'. $hurl .'" target="_blank">'. $hurl .'</a></span></span>';
		}
		echo '<span class="receipt_no"><span class="label">'. $receiptLabel .'：</span><span class="value">' .$epsilon_data['receipt_no'] . '</span></span>';
		/**********start show phone number of billing info************************/
		if ($epsilon_data['conveni_code'] == 31 || $epsilon_data['conveni_code'] == 32 || $epsilon_data['conveni_code'] == 33 || $epsilon_data['conveni_code'] == 35 || $epsilon_data['conveni_code'] == 36) {
			echo '<span class="tori_num"><span class="label">'. $phoneLabel .'：</span><span class="value">' . $phone . '</span></span>';
		}
		/**********end show phone number of billing info************************/
		echo '<span class="receipt_expire"><span class="label">'. __('Expire date : ', 'wc4jp-epsilon' ).'</span><span class="value">' . $epsilon_data['conveni_limit'] . '</span></span>';
		echo '</p>';
		echo '<p class="inst_pay">';
		if ($epsilon_data['conveni_code'] == 31 || $epsilon_data['conveni_code'] == 32 || $epsilon_data['conveni_code'] == 33) {
			echo '';
		} else {
			echo '<strong>&#9660'.$cs_stores_ids[get_post_meta($order_id,'_cvs_company_id',true)].'でのお支払い方法</strong>';
		}
		echo '<span class="inst_txt">'. $instruction .'</span>';
		if ($epsilon_data['conveni_code'] == 31 || $epsilon_data['conveni_code'] == 32 || $epsilon_data['conveni_code'] == 33) {
			echo '<strong>&#9660ローソンでのお支払い方法</strong><br/>';
			echo '<a href="'. $instUrl01 .'" target="_blank">'. $instUrl01 .'</a><br/><br/>';
			echo '<strong>&#9660セイコーマートでのお支払い方法</strong><br/>';
			echo '<a href="'. $instUrl02 .'" target="_blank">'. $instUrl02 .'</a><br/><br/>';
			echo '<strong>&#9660ミニストップでのお支払い方法</strong><br/>';
			echo '<a href="'. $instUrl03 .'" target="_blank">'. $instUrl03 .'</a>';
		} elseif ($epsilon_data['conveni_code'] == 35 || $epsilon_data['conveni_code'] == 36) {
			echo '';
		} else {
			echo '<a href="'. $instUrl .'" target="_blank">'. $instUrl .'</a>';
		}
		echo '</p>';
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
