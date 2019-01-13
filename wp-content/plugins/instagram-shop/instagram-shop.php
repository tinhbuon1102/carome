<?php
/*
 * Plugin Name: Instagram Shop
 * Version: 1
 * Description: Instagram shop connected with Woocommerce Products
 * Author: thangtqvn
 * Text Domain: instagram-shop
 * Domain Path: /languages
 */
load_plugin_textdomain('instagram-shop', false, dirname(plugin_basename(__FILE__)) . '/languages');
if ( isset($_POST['insta_shop_id']) && $_POST['insta_shop_id'] )
{
	update_option('insta_shop_id', $_POST['insta_shop_id']);
}

require_once (dirname(__FILE__) . '/instashop-admin.php');
require_once (dirname(__FILE__) . '/inc/class/class.font.data.php');
require_once (dirname(__FILE__) . '/inc/class/class.auth.php');

$shop_id = get_option('insta_shop_id');
define('IS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('IS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LIMIT_INSTA_IMAGE', 100);
define('INSTA_ID', $shop_id ? $shop_id : 'kitt.official');

$ts = Instashop::get_instance();
$ts->add_hook();

if ( is_admin() )
{
	$admin = Instashop_Admin::get_instance();
	$admin->add_hook();
}

class Instashop
{
	private static $instance;
	private $styles = false;
	const OPTION_NAME = 'is_settings';
	private function __construct ()
	{}
	public static function get_instance ()
	{
		if ( ! isset(self::$instance) )
		{
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	public function add_hook ()
	{
		add_shortcode('instagram_shop', array(
			$this,
			'insta_shop_shortcode'
		));
		add_action('wp_enqueue_scripts', array(
			$this,
			'load_scripts'
		), 0, 3);
		
		add_action('wp_ajax_nopriv_instagram_related_products', array(
			$this,
			'instagram_related_products'
		));
		add_action('wp_ajax_instagram_related_products', array(
			$this,
			'instagram_related_products'
		));
	}
	public function instagram_related_products ()
	{
		$prdtID = str_replace('/', '', basename($_POST['data']));
		
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		$insta_related_products[$prdtID]['products'] = isset($insta_related_products[$prdtID]) && isset($insta_related_products[$prdtID]['products']) ? array_values($insta_related_products[$prdtID]['products']) : null;
		
		$products_array = array();
		if ( $insta_related_products[$prdtID]['products'] )
		{
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => - 1,
				'fields' => 'ids',
				'post__in' => $insta_related_products[$prdtID]['products']
			);
			$related_query = new WP_Query($args);
			
			if ( $related_query->have_posts() )
			{
				$count_loop = 0;
				while ( $related_query->have_posts() )
				{
					$related_query->the_post();
					$product_id = get_the_ID();
					$_product = wc_get_product($product_id);
					
					// The secondary loop
					if ( has_post_thumbnail($product_id) )
					{
						$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), "medium");
					}
					else
					{
						$product_image = null;
					}
					
					$products_array[$count_loop]['post_id'] = $product_id;
					$products_array[$count_loop]['post_title'] = get_the_title($product_id);
					$products_array[$count_loop]['post_image'] = $product_image[0];
					$products_array[$count_loop]['link'] = get_permalink($product_id);
					$products_array[$count_loop]['price'] = $_product->get_price_html();
					$count_loop ++;
				}
			}
			wp_reset_postdata();
		}
		
		die(json_encode($products_array));
	}
	public static function version ()
	{
		static $version;
		
		if ( ! $version )
		{
			$data = get_file_data(__FILE__, array(
				'version' => 'Version'
			));
			$version = $data['version'];
		}
		return $version;
	}
	public static function text_domain ()
	{
		static $text_domain;
		
		if ( ! $text_domain )
		{
			$data = get_file_data(__FILE__, array(
				'text_domain' => 'Text Domain'
			));
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}
	public function load_scripts ()
	{
		$query = '';
		$version = $this->version();
		
		wp_enqueue_script('instashop', path_join(IS_PLUGIN_URL, 'inc/assets/js/insta.js'), array(
			'jquery'
		), '1.0.0', $position);
		
		wp_enqueue_style('instashop', path_join(IS_PLUGIN_URL, 'inc/assets/css/insta.css'));
	}
	function insta_shop_shortcode ( $atts, $content = null, $code = '' )
	{
		$atts = shortcode_atts(array(
			'count' => 0
		), $atts, 'instagram_shop');
		
		$insta_count = (int) $atts['count'];
		
		ob_start();
		include (dirname(__FILE__)) . '/inc/instagram-products.php';
		$response = ob_get_contents();
		ob_end_clean();
		return $response;
	}
}

register_uninstall_hook(__FILE__, 'instashop_uninstall');
function instashop_uninstall ()
{
	delete_option('instashop_auth');
	delete_option('instashop_fonttheme');
	delete_option('instashop_custom_theme');
	return;
}
