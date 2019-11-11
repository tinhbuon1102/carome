<?php
add_action('init', 'elsey_init_test', 100);
function elsey_init_test ()
{
	if ( isset($_REQUEST['epsilon_cs_checking']) )
	{
		check_epsilon_paid_cs_orders();
		die('done');
	}
}

function check_epsilon_paid_cs_orders ()
{
	global $wpdb;
	// Find not paid Convenience gateway
	$sql = "SELECT wp_posts.ID, wp_posts.post_status, wp_posts.menu_order, wp_posts.post_date FROM wp_posts
			INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
			INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) WHERE 1=1  AND (
		  ( wp_postmeta.meta_key = '_payment_method' AND wp_postmeta.meta_value = 'epsilon_pro_cs' )
		  AND
		  ( (mt1.meta_key = '_custom_payment_status' AND mt1.meta_value != '1' AND (wp_posts.post_status = 'wc-on-hold' OR wp_posts.post_status = 'wc-processing' OR wp_posts.post_status = 'wc-pending'))
		)) AND wp_posts.post_type = 'shop_order'  
		
		GROUP BY wp_posts.ID
		ORDER BY wp_posts.menu_order ASC, wp_posts.post_date DESC LIMIT 0, 20";
	
	//AND wp_posts.post_date > '" . date('Y-m-d', strtotime('-5 days')) . "' 
		
	$orders = $wpdb->get_results($sql);
	
	if (is_array($orders) && !empty($orders))
	{
		foreach ($orders as $order)
		{
			if (in_array($order->post_status, array('wc-on-hold', 'wc-processing', 'wc-pending')))
			{
				$wpdb->query('UPDATE wp_posts SET menu_order = ' . ($order->menu_order + 1) . ' WHERE ID = ' . $order->ID);
				epsilon_get_paid_cs_order($order->ID);
			}
		}
	}
}

function get_epsilon_response_array($order_id)
{
	$epsilon_response = get_post_meta($order_id, 'epsilon_response_array', true);
	$epsilon_data = array();
	foreach ( $epsilon_response['result'] as $uns_v )
	{
		list ($result_atr_key, $result_atr_val) = each($uns_v);
		$epsilon_data[$result_atr_key] = $result_atr_val;
	}
	return $epsilon_data;
}

function epsilon_get_paid_cs_order($order_id)
{
	$epsilon_data = get_epsilon_response_array($order_id);
	
	$content_folder = dirname(dirname(dirname(__FILE__)));
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/http/Request.php";
	require_once $content_folder . "/plugins/wc4jp-epsilon/includes/gateways/epsilon/includes/xml/Unserializer.php";
	
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
// 			$epsilon_data['conveni_limit'] = '2019-02-11';
// 			$epsilon_data_check['paid'] = 0;
			
			if (!empty($epsilon_data_check) && $epsilon_data_check['paid'] == 1)
			{
				// Order are paid by customer => Set status to completed
				epsilon_complete_cs_payment($order_id);
			}
			elseif ($epsilon_data['conveni_limit'] < current_time('Y-m-d') && $epsilon_data_check['paid'] == 0)
			{
				epsilon_cancel_cs_payment($order_id);
			}
		}
	}
}

function epsilon_complete_cs_payment($order_id)
{
	$order = wc_get_order( $order_id );
	update_post_meta($order_id, '_custom_payment_status', 1);
	$order->update_status( 'processing', __( 'Complete Convenience Store payment', 'elsey' ));
	var_dump($order_id);
	
}

function epsilon_cancel_cs_payment($order_id)
{
	$order = wc_get_order( $order_id );
	update_post_meta($order_id, '_custom_payment_status', 0);
	$order->update_status( 'cancelled', __( 'Cancel Convenience Store payment, Expired deadline date', 'elsey' ));
// 	var_dump($order_id);
	
}

function epsilon_cs_checking_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'order_number' => '',
		'paid' => '',
		'conveni_limit' => '',
	), $atts );
	
	$order_number = isset($_REQUEST['order_number']) ? $_REQUEST['order_number'] : $atts['order_number']; 
	$paid = isset($_REQUEST['paid']) ? $_REQUEST['paid'] : $atts['paid']; 
	
	if (site_url() == 'https://www.carome.net/')
	{
		wp_mail('quocthang.2001@gmail.com', 'Epsilon response', var_export($_REQUEST, true));
	}
	
	if ($order_number)
	{
		$order_id = mb_ereg_replace('[^0-9]', '', $order_number);
		if ($paid == 1)
		{
			epsilon_complete_cs_payment($order_id);
		}
		else {
			// Paid = 0 & expired deadline date
			$epsilon_data = get_epsilon_response_array($order_id);
			$conveni_limit = $atts['conveni_limit'] ? $atts['conveni_limit'] : $epsilon_data['conveni_limit'];
			
			if ($conveni_limit < current_time('Y-m-d'))
			{
				epsilon_cancel_cs_payment($order_id);
			}
		}
	}
}
add_shortcode( 'epsilon_cs_checking', 'epsilon_cs_checking_shortcode' );
