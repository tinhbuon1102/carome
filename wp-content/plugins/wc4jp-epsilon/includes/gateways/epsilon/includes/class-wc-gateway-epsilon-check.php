<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Order Check to send to Epsilon
 */
class WC_Gateway_Epsilon_Check {

	/**
	 * Stores line items to send to Epsilon
	 * @var array
	 */
//	protected $line_items = array();

	/**
	 * Pointer to gateway making the request
	 * @var WC_Gateway_Epsilon_Pro
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from Epsilon
	 * @var string
	 */
//	protected $notify_url;

	/**
	 * Constructor
	 * @param WC_Gateway_Paypal $gateway
	 */
//	public function __construct( $gateway ) {
//		$this->gateway    = $gateway;
//		$this->notify_url = WC()->api_request_url( 'WC_Gateway_Epsilon' );
//	}

	/**
	 * Get the Epsilon request URL for an order
	 * @param  WC_Order  $order
	 * @param  boolean $sandbox
	 * @return string
	 */
	protected function get_check_url( $testmode = 'yes' ) {
		if ($_SERVER['REMOTE_ADDR'] == '14.248.158.112')
		{
// 			$testmode='yes';
		}
		
		if($testmode=='yes'){
			$epsilon_pro_url = EPSILON_TESTMODE_URL_CHECK;
		}else{
			$epsilon_pro_url = EPSILON_RUNMODE_URL_CHECK ;
		}
		return $epsilon_pro_url;
	}

	/**
	 * Get Epsilon Args for passing to PP
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	public function get_order_info_to_epsilon( $trans_code ,$testmode) {
		if ($_SERVER['REMOTE_ADDR'] == '14.248.158.112')
		{
// 			$testmode='yes';
		}
		
		require_once "http/Request.php";
		require_once "xml/Unserializer.php";
		$user_id =get_option('wc-epsilon-pro-cid');
		// HTTP_Request Initialization
		$request = new HTTP_Request($this->get_check_url( $testmode ));

		//set method
		$request->setMethod(HTTP_REQUEST_METHOD_POST);
		$request->addHeader("Content-Type","application/x-www-form-urlencoded");
		$request->setBody("trans_code=" . $trans_code . "&contract_code=" . $user_id);
//		$request->setBody("trans_code=526096" . "&contract_code=60862410");
		$response = $request->sendRequest();

		$response_array = array();

		if (PEAR::isError($response)) {
		// Interface CGI fail
			$response_array['error_msg'] = "データの送信に失敗しました<br><br>";
			$response_array['error_msg'] .= "<br />res_statusCd=" . $request->getResponseCode();
			$response_array['error_msg'] .= "<br />res_status=" . $request->getResponseHeader('Status');
		}else{
		// Interface CGI success
		$res_code = $request->getResponseCode();
		$res_content = $request->getResponseBody();

		//xml unserializer
		$temp_xml_res = str_replace("x-sjis-cp932", "EUC-JP", $res_content);
		$unserializer = new XML_Unserializer();
		$unserializer->setOption('parseAttributes', TRUE);
		$unseriliz_st = $unserializer->unserialize($temp_xml_res);
		if ($unseriliz_st === true) {
			//xmlを解析
			$res_array = $unserializer->getUnserializedData();
			//error check
			if(isset($res_array['result']['result']) and $res_array['result']['result'] == "0"){
				$response_array['error_msg'] = "処理に失敗しました<br><br>";
			}

			$res_param_array = array();
			//pram setting
			if(isset($res_array['result'])){
				foreach($res_array['result'] as $uns_k => $uns_v){
					list($result_atr_key, $result_atr_val) = each($uns_v);
					$res_param_array[$result_atr_key] = mb_convert_encoding(urldecode($result_atr_val), "UTF8" ,"auto");
				}
			}
		}else{
			//xml parser error
			$response_array['error_msg'] = "xml parser error<br><br>";
		}
		}
		$response_array['result'] = $res_code;

		return $response_array;
	}

}
