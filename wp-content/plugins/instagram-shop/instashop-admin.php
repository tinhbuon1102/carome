<?php
require_once( dirname( __FILE__ ).'/inc/admin-base.php' );
require_once( dirname( __FILE__ ).'/inc/admin-root.php' );
require_once( dirname( __FILE__ ).'/inc/admin-fonttheme.php' );

class Instashop_Admin extends Instashop_Admin_Base {
	private static $instance;
	private static $text_domain;

	private function __construct(){}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public function add_hook() {
		self::$text_domain = Instashop::text_domain();
		$root = Instashop_Admin_Root::get_instance();
		add_action( 'admin_menu',		array( $this, 'instashop_setting_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_theme_style' ) );
		
		add_action('wp_ajax_nopriv_get_insta_form', array( $this, 'get_insta_form'));
		add_action('wp_ajax_get_insta_form', array( $this, 'get_insta_form'));
		
		add_action('wp_ajax_nopriv_hide_insta_post', array( $this, 'hide_insta_post'));
		add_action('wp_ajax_hide_insta_post', array( $this, 'hide_insta_post'));
		
		add_action('wp_ajax_nopriv_get_search_product', array( $this, 'get_search_product'));
		add_action('wp_ajax_get_search_product', array( $this, 'get_search_product'));
		
		add_action('wp_ajax_nopriv_add_related_product', array( $this, 'add_related_product'));
		add_action('wp_ajax_add_related_product', array( $this, 'add_related_product'));
		
		add_action('wp_ajax_nopriv_remove_related_product', array( $this, 'remove_related_product'));
		add_action('wp_ajax_remove_related_product', array( $this, 'remove_related_product'));
		
		add_filter( 'woocommerce_json_search_found_products', array($this, 'insta_woocommerce_json_search_found_products'), 100, 1 );
	}

	public function buildRelatedProductList($insta_id){
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		$html = '';
		if (isset($insta_related_products[$insta_id]) && $insta_related_products[$insta_id]['products'] && count($insta_related_products[$insta_id]['products']))
		{
			foreach ($insta_related_products[$insta_id]['products'] as $product_id_index => $product_id)
			{
				$product = get_product($product_id);
				$html .= '<li data-id="'. $product_id .'" data-insta-id="'. $insta_id .'">
					<a target="_blank" href="'. site_url('/wp-admin/post.php?post='. $product->id .'&action=edit') .'"> 
						<span class="image">' . get_the_post_thumbnail( $product_id, 'thumbnail' ) . '</span>' .
						$product->name . ($product->sku ? '  (SKU: ' . $product->sku . ')' : '') .'</a>
					<a class="remove_related">X</a>
				</li>';
			}
		}
		return $html;
	}
	
	public function remove_related_product() {
		$insta_id = $_POST['insta_id'];
		$product_id = $_POST['product_id'];
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		
		if ((isset($insta_related_products[$insta_id]) && isset($insta_related_products[$insta_id]['products']) && in_array($product_id, $insta_related_products[$insta_id]['products'])))
		{
			unset($insta_related_products[$insta_id]['products'][$product_id]);
		}
		update_option('insta_related_products', $insta_related_products);
		echo json_encode(array('html' => $this->buildRelatedProductList($insta_id)));
		die;
	}
	
	public function add_related_product() {
		$insta_id = $_POST['insta_id'];
		$product_id = $_POST['product_id'];
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		
		if (!isset($insta_related_products[$insta_id]) || !isset($insta_related_products[$insta_id]['products']) || (isset($insta_related_products[$insta_id]) && isset($insta_related_products[$insta_id]['products']) && !in_array($product_id, $insta_related_products[$insta_id]['products'])))
		{
			$insta_related_products[$insta_id]['products'][$product_id] = $product_id;
		}
		update_option('insta_related_products', $insta_related_products);
		echo json_encode(array('html' => $this->buildRelatedProductList($insta_id)));
		die;
	}
	
	public function get_search_product() {
		$search_products_nonce = wp_create_nonce( 'search-products' );
		$_GET['security'] = $_REQUEST['security'] = $search_products_nonce;
		return WC_AJAX::json_search_products( '', false);
	}
	
	public function insta_woocommerce_json_search_found_products ($products) 
	{
		if (isset($_GET['insta_id'])) {
			$new_products = $product = $relatedProducts = array();
			foreach ($products as $product_id => $product_name)
			{
				$wProduct = get_product($product_id);
				
				if ( has_post_thumbnail($product_id) )
				{
					$product['image'] = get_the_post_thumbnail( $product_id, 'thumbnail' );
				}
				else
				{
					$product['image'] = null;
				}
				
				$product['id'] = $product_id;
				$product['name'] = strip_tags($product_name);
				$product['group_name'] = __( 'Related Products', self::$text_domain );
				$relatedProducts[] = $product;
			}
			$new_products['data']['product'] = $relatedProducts;
			return $new_products;
		}
		else {
			return $products;
		}
	}
	
	public function get_insta_form() {
		$insta_id = $_REQUEST['insta_id'];
		echo json_encode(array('html' => $this->buildRelatedProductList($insta_id)));
		die;
	}
	
	public function hide_insta_post() {
		$insta_id = $_REQUEST['insta_id'];
		
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		
		if (!isset($insta_related_products[$insta_id]) || !isset($insta_related_products[$insta_id]['hide']) || $insta_related_products[$insta_id]['hide'] == 0)
		{
			$insta_related_products[$insta_id]['hide'] = 1;
		}
		else {
			$insta_related_products[$insta_id]['hide'] = 0;
		}
		update_option('insta_related_products', $insta_related_products);
		
		echo json_encode(array('hide' => $insta_related_products[$insta_id]['hide']));
		die;
	}
	
	public function admin_theme_style() {
		wp_enqueue_style( 'is-styles',  path_join( IS_PLUGIN_URL, 'inc/assets/css/admin.css' ) );
		
		if ($_GET['page'] == 'instashop-admin-menu')
		{
			wp_enqueue_style( 'is-remodal',  path_join( IS_PLUGIN_URL, 'inc/assets/css/remodal.css' ) );
			wp_enqueue_style( 'is-remodal-theme',  path_join( IS_PLUGIN_URL, 'inc/assets/css/remodal-default-theme.css' ) );
			wp_enqueue_style( 'typeahead',  path_join( IS_PLUGIN_URL, 'inc/assets/js/typehead/jquery.typeahead.css' ) );
		}
	}

	public function admin_theme_script( $position = false ) {
		wp_enqueue_script( 'is-admin', path_join( IS_PLUGIN_URL, 'inc/assets/js/admin.js' ), array( 'jquery' ), '1.0.0', $position );
		if ($_GET['page'] == 'instashop-admin-menu')
		{
			wp_enqueue_script( 'is-admin-remodal', path_join( IS_PLUGIN_URL, 'inc/assets/js/remodal.js' ), array( 'jquery' ), '1.0.0', $position );
			wp_enqueue_script( 'typahead', path_join( IS_PLUGIN_URL, 'inc/assets/js/typehead/jquery.typeahead.js' ), array( 'jquery' ), '1.0.0', $position );
			wp_enqueue_script( 'loadingoverlay', path_join( IS_PLUGIN_URL, 'inc/assets/js/loadingoverlay.js' ), array( 'jquery' ), '1.0.0', $position );
		}
	}

	public function instashop_setting_menu() {
		$root = Instashop_Admin_Root::get_instance();
		$theme = Instashop_Admin_Fonttheme::get_instance();
		$hooks = array(
			add_menu_page(
				__( 'Instashop Shop Settings', self::$text_domain ),
				__( 'Instashop Shop Settings', self::$text_domain ),
				'administrator',
				self::MENU_ID,
				array( $root, 'instashop_admin_menu' )
			),
		);

		foreach ( $hooks as $hook ) {
			add_action( $hook, array( $this, 'admin_theme_script' ) );
		}
	}
}
