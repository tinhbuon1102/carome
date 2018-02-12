<?php 
class WCST_Shortcodes
{
	public function __construct()
	{
		add_shortcode( 'wcst_show_estimated_date', array(&$this, 'display_estimated_date' ));
		add_shortcode( 'wcst_tracking_form', array(&$this, 'display_tracking_form' ));
	}
	
	public function display_estimated_date($atts)
	{
		$parameters = shortcode_atts( array(
        'product_id' => get_the_ID(),
			), $atts );
			
		if(!isset($parameters['product_id']))
			return "";
		
		global $wcst_product_model,$wcst_time_model;
		
		//$product = get_post_type($parameters['product_id']) == 'product'  ? new WC_Product_Simple($parameters['product_id']) : new  WC_Product_Variable($parameters['product_id']);
		$product = wc_get_product($parameters['product_id']); //new WC_Product($parameters['product_id']);
		
		$stock_quantity = $product->get_stock_quantity( );
		
		$options_controller = new WCST_Option();
		$wpml_helper = new WCST_Wpml();
		$options = $options_controller->get_general_options();
		$estimated_shipping_info_out_of_stock = isset($options['estimated_shipping_info_out_of_stock']) && isset($options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()]) ? $options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()] : __('Out of stock, date unavailable', 'woocommerce-shipping-tracking');
		
		if(!$product->is_in_stock( ) || (WCST_Order::get_manage_stock($product) != 'no' && isset($stock_quantity) && $stock_quantity < 1))
		{
			ob_start();
			echo $estimated_shipping_info_out_of_stock;
			return ob_get_clean();
		}
		
		$estimated_date = $wcst_time_model->get_available_date($wcst_product_model->get_estimation_shippment_rule($parameters['product_id']));	
		
		ob_start();
			echo '<span class="wcst_estimated_text">';
			echo $estimated_date;
			echo '</span>';
		return ob_get_clean();
	}
	public function display_tracking_form($atts)
	{
		global $wcst_shipping_company_model;
		$options = new WCST_Option();
		$redirection_method = $options->get_option('wcst_general_options','tracking_form_redirect_method', 'same_page');
		$shipping_companies = $wcst_shipping_company_model->get_all_selected_comanies();
		$company_id = isset($atts['company_id']) && $atts['company_id'] != "" ? $atts['company_id'] : 'none';
		$button_classes = isset($atts['button_classes']) && $atts['button_classes'] != "" ? $atts['button_classes'] : "";
		
		wp_enqueue_script('wcst-shortcode-tracking-input', WCST_PLUGIN_PATH.'/js/wcst-shortcode-tracking-input.js', array( 'jquery' ));
		wp_enqueue_style('wcst-shortcode-style', WCST_PLUGIN_PATH.'/css/wcst-shortcode.css');
		ob_start();
		include WCST_PLUGIN_ABS_PATH."template/shortcode_tracking_input.php";
		return ob_get_clean();
	}
	
	public static function check_if_conditional_no_tracking_url_text_has_to_be_removed($text, $remove_conditional_text = true)
	{
		$start_string = "[if_has_tracking_url]";
		$end_string = "[/if_has_tracking_url]";
		
		$start = strpos($text, $start_string);
		$end = strpos($text, $end_string);
		if($start == true && $end == true)
		{
			if($remove_conditional_text)
			{
				//$offset = strlen($start_string);
				$offset = strlen($end_string);
				//$conditional_string = substr ( $text , $start+$offset, $end - ($start + $offset));
				//return substr ( $text , $start + $offset, $end - ($start + $offset));
				$offset_second = substr ( $text , ($end + $offset) + 1, 2) == "br" ? 6 : 0; //<br /> = 6
				$result = substr_replace ($text, "", $start, ($end + $offset) - $start + $offset_second);
				
				return substr($result,0,1) == "\n" ? "a" : $result;
			}
			else
				return str_replace(array($start_string, $end_string), "", $text);
		}
		return $text;
	}
}
?>