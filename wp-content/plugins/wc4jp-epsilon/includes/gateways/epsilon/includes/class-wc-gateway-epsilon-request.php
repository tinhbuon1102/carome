<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates requests to send to Epsilon
 */
class WC_Gateway_Epsilon_Request {

	/**
	 * Stores line items to send to PayPal
	 * @var array
	 */
//	public $line_items = array();

	/**
	 * Pointer to gateway making the request
	 * @var WC_Gateway_Epsilon_Pro
	 */
	public $gateway;

	/**
	 * Endpoint for requests from Epsilon
	 * @var string
	 */
//	protected $notify_url;

	/**
	 * Constructor
	 * @param WC_Gateway_Paypal $gateway
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
//		$this->notify_url = WC()->api_request_url( 'WC_Gateway_Epsilon' );
	}

	/**
	 * Get the Epsilon request URL for an order
	 * @param  WC_Order  $order
	 * @param  boolean $sandbox
	 * @return string
	 */
	public function get_request_url( $testmode = false ) {
		if ($_SERVER['REMOTE_ADDR'] == '14.248.158.112')
		{
// 			$testmode='yes';
		}
		
		if($testmode=='yes'){
			$epsilon_pro_url = EPSILON_TESTMODE_URL_REQUEST ;
		}else{
			$epsilon_pro_url = EPSILON_RUNMODE_URL_REQUEST ;
		}
		return $epsilon_pro_url;
	}

	/**
	 * Get Epsilon Args for passing to PP
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	public function get_order_to_epsilon( $order ,$contract_code, $st_code, $testmode, $gateway_id, $conveni_code) {
		if ($_SERVER['REMOTE_ADDR'] == '14.248.158.112')
		{
// 			$testmode='yes';
		}
		require_once "http/Request.php";
		require_once "xml/Unserializer.php";
		// http_requset option Setting
		$option = array(
			"timeout" => "20", // Seconds
//			"allowRedirects" => true, // redirect Stting(true/false)
//			"maxRedirects" => 3, // max times of redirect
		);
		// HTTP_Request Initialization
		if ($gateway_id == 'epsilon_pro_sc')
		{
			$request = new HTTP_Request($this->get_request_url( $testmode ) , $option);
// 			if ($testmode == 'no')
// 			{
// 				$request_url = 'https://secure.epsilon.jp/cgi-bin/carrier/carrier3.cgi?payment_code=15'; 
// 				$request = new HTTP_Request($request_url , $option);
// 			}
// 			else {
// 				$request = new HTTP_Request($this->get_request_url( $testmode ) , $option);
// 			}
		}
		else {
			$request = new HTTP_Request($this->get_request_url( $testmode ) , $option);
		}

		$mission_code =1;//Payment times
		$process_code =1;//First time payment

		//set method
		$request->setMethod(HTTP_REQUEST_METHOD_POST);
		//set post data
		$request->addPostData('contract_code', $contract_code);
		$request->addPostData('user_id', $this->get_customer_id( $order ));
		$request->addPostData('user_name', mb_convert_encoding($order->billing_last_name." ".$order->billing_first_name, "EUC-JP", "auto"));
		$request->addPostData('user_mail_add', $order->billing_email);
		$request->addPostData('item_code', $this->get_item_code( $order ));
		$request->addPostData('item_name', mb_convert_encoding($this->get_item_name( $order ), "EUC-JP", "auto"));
		$request->addPostData('order_number', 'wc'.$order->id);
		$request->addPostData('st_code', $st_code);
		$request->addPostData('currency_id', get_woocommerce_currency());

		$request->addPostData('mission_code', $mission_code);
		if(get_woocommerce_currency() =='JPY' or get_woocommerce_currency() =='KRW' or get_woocommerce_currency() =='VND'){
			$order_total_price = $order->order_total;
		}else{
			$order_total_price = $order->order_total*100;
		}
		$request->addPostData('item_price', $order_total_price);
		$request->addPostData('process_code', $process_code);
		$request->addPostData('memo2', 'woocommerce');
		$request->addPostData('xml', '1');

		//
		$states = WC()->countries->get_allowed_country_states();
		if($gateway_id == 'epsilon_pro_cs'){
			$request->addPostData('conveni_code', $conveni_code);
			$request->addPostData('user_tel', $order->billing_phone);
			$request->addPostData('user_name_kana', mb_convert_encoding($order->billing_yomigana_last_name." ".$order->billing_yomigana_first_name, "EUC-JP", "auto"));
		}elseif($gateway_id == 'epsilon_pro_gp'){
			$request->addPostData('delivery_code', 99);
			$request->addPostData('consignee_postal', $order->shipping_postcode);
			$request->addPostData('consignee_name', mb_convert_encoding($order->shipping_last_name." ".$order->shipping_first_name, "EUC-JP", "auto"));
			$request->addPostData('consignee_name_kana', mb_convert_encoding($order->shipping_yomigana_last_name." ".$order->shipping_yomigana_first_name, "EUC-JP", "auto"));
			$request->addPostData('consignee_address', mb_convert_encoding($states['JP'][$order->shipping_state].$order->shipping_city.$order->shipping_address_1.$order->shipping_address_2, "EUC-JP", "auto"));
			$request->addPostData('consignee_tel', $order->shipping_phone);
			$request->addPostData('orderer_postal', $order->billing_postcode);
			$request->addPostData('orderer_name', mb_convert_encoding($order->billing_last_name." ".$order->billing_first_name, "EUC-JP", "auto"));
			$request->addPostData('orderer_name_kana', mb_convert_encoding($order->billing_yomigana_last_name." ".$order->billing_yomigana_first_name, "EUC-JP", "auto"));
			$request->addPostData('orderer_address', mb_convert_encoding($states['JP'][$order->billing_state].$order->billing_city.$order->billing_address_1.$order->billing_address_2, "EUC-JP", "auto"));
			$request->addPostData('orderer_tel', $order->billing_phone);
		}

		// HTTP REQUEST Action
		$response = $request->sendRequest();
		if (!PEAR::isError($response)) {
			// Request (XML)
			$res_code = $request->getResponseCode();
			$res_content = $request->getResponseBody();

			//xml unserializer
			$temp_xml_res = str_replace("x-sjis-cp932", "UTF8", $res_content);
			$unserializer = new XML_Unserializer();
			$unserializer->setOption('parseAttributes', TRUE);
			$unseriliz_st = $unserializer->unserialize($temp_xml_res);

			update_post_meta($order->id, 'epsilon_response', $temp_xml_res);
			if ($unseriliz_st === true) {
				//xmlを解析
				$res_array = $unserializer->getUnserializedData();
				update_post_meta($order->id, 'epsilon_response_array', $res_array);
				
				$is_xml_error = false;
				$response_array = array();
				foreach($res_array['result'] as $uns_k => $uns_v){
					//$debug_printj .=  "<br />k=" . $uns_k;
	    				list($result_atr_key, $result_atr_val) = each($uns_v);
					//$debug_printj .=  "<br />result_atr_key=" . $result_atr_key;
					//$debug_printj .=  "<br />result_atr_val=" . $result_atr_val;

					switch ($result_atr_key) {
						case 'redirect':
							$response_array['redirect_url'] = rawurldecode($result_atr_val);
							break;
						case 'err_code':
							$is_xml_error = true;
							$response_array['error_msg'] = $result_atr_val.'1';
							break;
						case 'err_detail':
							$response_array['error_msg'] = urldecode($result_atr_val);
							break;
/*						case 'memo1':
							$response_array['error_msg'] = mb_convert_encoding(urldecode($result_atr_val.'3'), "UTF8" ,"auto");
							break;
						case 'memo2':
							$response_array['error_msg'] = mb_convert_encoding(urldecode($result_atr_val.'4'), "UTF8" ,"auto");
							break;*/
						default:
							break;
					}
				}
			}else{
			//xml parser error
		  	$response_array['error_msg'] = "xml parser error";
			}
		}else{ //http error
			//$debug_printj .=  "http error";
			$response_array['error_msg'] = "データの送信に失敗しました<br />";
			$response_array['error_msg'] .= "<br />res_statusCd=" . $request->getResponseCode();
			$response_array['error_msg'] .= "<br />res_status=" . $request->getResponseHeader('Status');
		}
		return $response_array;
	}

	public function get_customer_id( $order ) {
		$user = new WP_User( $order->user_id );
		if($order->user_id){
			$customer_id   = $order->user_id;
		}else{
			$customer_id   = $order->id.'-user';
		}		
		return $customer_id;
	}

	public function get_item_code( $order ) {
		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $item['qty'] ) {
					if ($item === end($order->get_items())) {
					// last
						$item_codes .= $item['product_id'];
					}else{
						$item_codes .= $item['product_id'].'-';
					}
				}
			}
		}
		$item_codes = substr($item_codes, 0, 64);
		if(!$item_codes)$item_codes='no-item-codes';
		return $item_codes;
	}
	public function get_item_name( $order ) {
		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $item['qty'] ) {
					if ($item === end($order->get_items())) {
					// last
						$item_names .= $item['name'];
					}else{
						$item_names .= $item['name'].' ';
					}
				}
			}
		}
		$item_names = substr($item_names, 0, 64);
		if(!$item_names)$item_names='no-items';
		return $item_names;
	}

}
