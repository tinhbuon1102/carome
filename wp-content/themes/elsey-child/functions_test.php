<?php
add_action('init', 'elsey_init_test', 1);
function elsey_init_test ()
{
	if ( isset($_GET['test_epsilon_cs']) )
	{
		test_epsilon_cs();
	}
}
function test_epsilon_cs ()
{
	$epsilon_response = get_post_meta($_GET['test_epsilon_cs'], 'epsilon_response_array', true);
	$epsilon_data = array();
	foreach ( $epsilon_response['result'] as $uns_k => $uns_v )
	{
		list ($result_atr_key, $result_atr_val) = each($uns_v);
		$epsilon_data[$result_atr_key] = $result_atr_val;
	}

	$order_id = mb_ereg_replace('[^0-9]', '', $epsilon_data['order_number']);
	$order = wc_get_order($order_id);
	$content_folder = dirname(dirname(dirname(__FILE__)));
	include_once ($content_folder . '/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/class-wc-gateway-epsilon-request.php');
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/http/Request.php";
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/xml/Unserializer.php";

	$epsilon_request = new WC_Gateway_Epsilon_Request('WC_Gateway_Epsilon_Pro_CS');
	$gateway_id = 'epsilon_pro_cs';

	// http_requset option Setting
	$option = array(
		"timeout" => "20" // Seconds
		                  // "allowRedirects" => true, // redirect Stting(true/false)
		                  // "maxRedirects" => 3, // max times of redirect
	);
	$epsilon_sc_settings = get_option('woocommerce_epsilon_pro_cs_settings');
	// HTTP_Request Initialization
	$request = new HTTP_Request($epsilon_request->get_request_url($epsilon_sc_settings['testmode']), $option);

	// set method
	$request->setMethod(HTTP_REQUEST_METHOD_POST);
	// set post data
	$request->addPostData('memo2', 'woocommerce');
// 	$request->addPostData('xml', '1');
	$request->addPostData('currency_id', get_woocommerce_currency());
	if ( $gateway_id == 'epsilon_pro_cs' )
	{
		$request->addPostData('st_code', $epsilon_data['contract_code']);
		$request->addPostData('user_id', $epsilon_data['user_id']);
		$request->addPostData('user_tel', $order->billing_phone);
		$request->addPostData('user_name_kana', mb_convert_encoding($order->billing_yomigana_last_name." ".$order->billing_yomigana_first_name, "EUC-JP", "auto"));
		$request->addPostData('contract_code', $epsilon_data['contract_code']);
		$request->addPostData('trans_code', $epsilon_data['trans_code']);
		
		$request->addPostData('conveni_code', $epsilon_data['conveni_code']);
		$request->addPostData('receipt_no', $epsilon_data['receipt_no']);
		$request->addPostData('kigyou_code', $epsilon_data['kigyou_code']);
		$request->addPostData('haraikomi_url', $epsilon_data['haraikomi_url']);
		$request->addPostData('paid', $epsilon_data['paid']);
		$request->addPostData('receipt_date', $epsilon_data['receipt_date']);
		$request->addPostData('conveni_limit', $epsilon_data['conveni_limit']);
		$request->addPostData('conveni_time', $epsilon_data['conveni_time']);
	}

	// HTTP REQUEST Action
	$response = $request->sendRequest();
	if ( ! PEAR::isError($response) )
	{
		$res_code = $request->getResponseCode();
		$res_content = $request->getResponseBody();
		pr($epsilon_data);
		pr($request);
		var_dump($res_content);
		// xml unserializer
		$temp_xml_res = str_replace("x-sjis-cp932", "UTF8", $res_content);
		$unserializer = new XML_Unserializer();
		$unserializer->setOption('parseAttributes', TRUE);
		$unseriliz_st = $unserializer->unserialize($temp_xml_res);

		if ( $unseriliz_st === true )
		{
			$res_array = $unserializer->getUnserializedData();
		}
		pr($res_array);
		pr('---------------------------------');
		die();
	}
}