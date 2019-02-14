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
	// Find not paid Convenience gateway
	$query_args = array(
		'post_type' => 'shop_order',
		'post_status' => wc_get_order_statuses(),
		'posts_per_page' => 2,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_payment_method',
				'value' => 'epsilon_pro_cs',
				'compare' => '='
			),
			array(
				'key' => '_custom_payment_status',
				'value' => '1',
				'compare' => '!='
			),
		),
	);
	$query = new WP_Query( $query_args );
	$unpaid_orders = $query->posts;
	
	$epsilon_response = get_post_meta($_GET['test_epsilon_cs'], 'epsilon_response_array', true);
	$epsilon_data = array();
	foreach ( $epsilon_response['result'] as $uns_v )
	{
		list ($result_atr_key, $result_atr_val) = each($uns_v);
		$epsilon_data[$result_atr_key] = $result_atr_val;
	}

	$order_id = mb_ereg_replace('[^0-9]', '', $epsilon_data['order_number']);
	$content_folder = dirname(dirname(dirname(__FILE__)));
// 	include_once ($content_folder . '/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/class-wc-gateway-epsilon-request.php');
// 	include_once ($content_folder . '/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/class-wc-gateway-epsilon-check.php');
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/http/Request.php";
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/xml/Unserializer.php";

// 	$epsilon_request = new WC_Gateway_Epsilon_Request('WC_Gateway_Epsilon_Pro_CS');
// 	$epsilon_check = new WC_Gateway_Epsilon_Check();
	$gateway_id = 'epsilon_pro_cs';

	// http_requset option Setting
	$option = array(
		"timeout" => "20" // Seconds
	);
	$epsilon_sc_settings = get_option('woocommerce_epsilon_pro_cs_settings');
	// HTTP_Request Initialization
	if($epsilon_sc_settings['testmode']=='yes'){
		$epsilon_pro_url = EPSILON_TESTMODE_URL_CHECK ;
	}else{
		$epsilon_pro_url = EPSILON_RUNMODE_URL_CHECK ;
	}
	$request = new HTTP_Request($epsilon_pro_url, $option);

	// set method
	$request->setMethod(HTTP_REQUEST_METHOD_POST);
	// set post data
	$request->addPostData('xml', '1');
	if ( $gateway_id == 'epsilon_pro_cs' )
	{
		$request->addPostData('st_code', $epsilon_data['st_code']);
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
		// xml unserializer
		$temp_xml_res = str_replace("x-sjis-cp932", "UTF8", $res_content);
		$unserializer = new XML_Unserializer();
		$unserializer->setOption('parseAttributes', TRUE);
		$unseriliz_st = $unserializer->unserialize($temp_xml_res);

		if ( $unseriliz_st === true )
		{
			$res_array = $unserializer->getUnserializedData();
			$epsilon_data_check = array();
			if (isset($res_array['result']))
			{
				foreach ( $res_array['result'] as $uns_v )
				{
					list ($result_atr_key, $result_atr_val) = each($uns_v);
					$epsilon_data_check[$result_atr_key] = $result_atr_val;
				}
			}
			
			pr($epsilon_data_check);die;
			if ($epsilon_data_check['paid'] == 1)
			{
				// Order are paid by customer => Set status to completed
				$order = wc_get_order( $order_id );
				$order->update_status( 'completed', __( 'Complete Convenience Store payment', 'elsey' ));
			}
			pr($epsilon_data_check);
			pr('---------------------------------');
			die();
		}
	}
}