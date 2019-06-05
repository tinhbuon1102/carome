<?php
include dirname(__FILE__) . '/functions_epsilon.php';
include dirname(__FILE__) . '/functions_new_shipping.php';

define('CONTACT_EMAIL_ADMIN_WITH_FILE', 'return@carome.net');

if (strpos($_SERVER['SERVER_NAME'], 'carome.net') !== false){
	define('BOOKING_FORM_ID', 19672);
}

function elsey_change_cssjs_ver( $src ) {
	if( strpos( $src, '?ver=' ) )
		$src = remove_query_arg( 'ver', $src );
		$src = add_query_arg( array('ver' => '2.1'), $src );
		return $src;
}
add_filter( 'style_loader_src', 'elsey_change_cssjs_ver', 1000 );
add_filter( 'script_loader_src', 'elsey_change_cssjs_ver', 1000 );
/**
 * Dequence plugins file
 */
function dp_deregister_styles() {
  // 'contact' という投稿スラッグの固定ページでない場合
  if ( !is_page( 'contact' ) ) {
    // ハンドル名 'contact-form-7' のCSSの出力を無効化
    wp_dequeue_style( 'contact-form-7' );
  }
}
// アクションフック
add_action( 'wp_print_styles', 'dp_deregister_styles', 100 );
/**
 * Enqueues child theme stylesheet, loading first the parent theme stylesheet.
 */
function elsey_enqueue_child_theme_styles() {
	wp_enqueue_style( 'elsey-child-style', get_stylesheet_uri().'', array(), null );
	//wp_enqueue_style( 'elsey-child-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
}
add_action( 'wp_enqueue_scripts', 'elsey_enqueue_child_theme_styles', 11 );

// google fonts
function custom_add_google_fonts() {
	wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css?family=Lato:300,300i,400,700|Pathway+Gothic+One|Poppins:300,400,500,600|Playfair+Display:400,400i,700i', false );
}
add_action( 'wp_enqueue_scripts', 'custom_add_google_fonts' );
/*Add New CSS*/
function custom_styles () {
	wp_register_style('osum-style', get_stylesheet_directory_uri() . '/css/ordersummary.css?201902151312', array(), '');
	if (is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' )) {
		wp_enqueue_style('osum-style');
	}
}

add_action('admin_enqueue_scripts', 'load_custom_wp_admin_custom_script');

function load_custom_wp_admin_custom_script() {
	wp_enqueue_script('overlay', get_stylesheet_directory_uri() . '/js/loadingoverlay.js', array('jquery'));
	wp_enqueue_script('admin_js', get_stylesheet_directory_uri() . '/js/admin.js', array(
		'jquery'
	));
	wp_enqueue_style('admin_css', get_stylesheet_directory_uri() . '/css/admin.css');
}

add_action('wp_enqueue_scripts', 'custom_styles');
/*Jquery*/
function custom_scripts ()
{
	wp_register_script('autokana', get_stylesheet_directory_uri() . '/js/jquery.autoKana.js', array( 'jquery' ),'', true);
	wp_enqueue_script('autokana');
	
	wp_register_script('simple-ticker', get_stylesheet_directory_uri() . '/js/jquery.simpleTicker/jquery.simpleTicker.js', array( 'jquery' ),'', true);
	wp_enqueue_script('simple-ticker');
	
	wp_register_script('slick_js', get_stylesheet_directory_uri() . '/js/slick/slick.min.js', array( 'jquery' ),'', true);
	wp_enqueue_script('slick_js');
	
	wp_register_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js?v=' . time(), array( 'jquery' ),'', true);
	wp_enqueue_script('custom_js');
	
	wp_dequeue_script( 'sticky-header', ELSEY_SCRIPTS . '/sticky.min.js', array( 'jquery' ), '1.0.4', true );
	wp_enqueue_script('sticky-header', get_stylesheet_directory_uri() . '/js/sticky.min.js', array( 'jquery' ),'', true);
	
	wp_register_style('aos-style', 'https://unpkg.com/aos@2.3.1/dist/aos.css', "", '');
	wp_register_style('animate-style', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css', array(), '');
	wp_register_style('anim-style', get_stylesheet_directory_uri() . '/css/anim.css', array(), '');
	wp_register_style('eyeliner-style', get_stylesheet_directory_uri() . '/css/eyeliner.css?201904010816', array(), '');
	wp_register_script('picturefill-js', 'https://cdn.rawgit.com/scottjehl/picturefill/3.0.2/dist/picturefill.min.js', array(), '');
	wp_register_script('objfit-js', 'https://cdnjs.cloudflare.com/ajax/libs/object-fit-images/3.2.4/ofi.min.js', array(), '');
	wp_register_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '', true);
	wp_register_script('floslider_js', get_stylesheet_directory_uri() . '/js/floslider.js', array(), '', true);
	wp_register_script('eyeliner_js', get_stylesheet_directory_uri() . '/js/eyeliner.js?v=' . time(), array( 'custom_js' ),'', true);
	if ( is_page_template('page-eyeliner.php') ) {
		wp_enqueue_style('eyeliner-style');
		wp_enqueue_style('aos-style');
		wp_enqueue_style('anim-style');
		wp_enqueue_script('picturefill-js');
		wp_enqueue_script('objfit-js');
		wp_enqueue_script('aos-js');
		//wp_enqueue_script('floslider_js');
		wp_enqueue_script('eyeliner_js');
	}
	
	wp_register_script('event_js', get_stylesheet_directory_uri() . '/js/event.js?v=' . time(), array( 'custom_js' ),'', true);
	if ( is_page('enter') ) {
		wp_enqueue_script('event_js');
	}
}
add_action('wp_enqueue_scripts', 'custom_scripts');

function file_remove_scripts() {
	
	// Check for the page you want to target
	if ( is_page( 'insta-shop' ) ) {
		
		// Remove Styles
		wp_dequeue_style( 'slick_css' );
		wp_dequeue_style( 'slicktheme_css' );
		wp_deregister_style( 'slick_css' );
		wp_deregister_style( 'slicktheme_css' );
	}
}
add_action( 'wp_enqueue_scripts', 'file_remove_scripts' );

//shortcode url
add_shortcode('homeurl', 'shortcode_url');
function shortcode_url() {
	return get_bloginfo('url');
}

//add extra charge for conv payment order
function woocommerce_custom_fee( ) {
	
	if ( ( is_admin() && ! defined( 'DOING_AJAX' ) ) || ! is_checkout() )
		return;
		
		$chosen_gateway = WC()->session->chosen_payment_method;
		
		$fee = 300;
		// or calculate your $fee with all the php magic...
		// $fee = WC()->cart->cart_contents_total * .025; // sample computation for getting 2.5% of the cart total.
		
		if ( $chosen_gateway == 'epsilon_pro_cs' ) { //test with paypal method
			WC()->cart->add_fee( 'コンビニ支払い手数料', $fee, false );
		}
}
add_action( 'woocommerce_cart_calculate_fees','woocommerce_custom_fee' );


function remove_product_editor() {
	remove_post_type_support( 'product', 'editor' );
}
add_action( 'init', 'remove_product_editor' );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );
/*hide adminbar*/
add_filter('show_admin_bar', '__return_false');

/*meta tag force to clear cache?*/
function add_meta_tags() {
	echo '<meta http-equiv="cache-control" content="no-cache" />';
	echo '<meta http-equiv="expires" content="0" />';
	echo '<meta http-equiv="expires" content="Fri, 09 Nov 2018 19:00:00 GMT" />';
	echo '<meta http-equiv="pragma" content="no-cache" />';
}
add_action('wp_head', 'add_meta_tags');
/**
 * バージョンアップ通知を管理者のみ表示させるようにします。
 */
function update_nag_admin_only() {
	if ( ! current_user_can( 'administrator' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
add_action( 'admin_init', 'update_nag_admin_only' );


add_action('woocommerce_before_cart', 'show_check_category_in_cart');

function show_check_category_in_cart() {
	
	// Set $cat_in_cart to false
	$cat_in_cart = false;
	
	// Loop through all products in the Cart
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		
		// If Cart has category "download", set $cat_in_cart to true
		if ( has_term( 'twoset_price_jwl', 'product_cat', $cart_item['product_id'] ) ) {
			$cat_in_cart = true;
			break;
		}
	}
	
	function cart_update_script() {
		if (is_checkout()) :
		?>
    <script>
		jQuery( function( $ ) {
 
			// woocommerce_params is required to continue, ensure the object exists
			if ( typeof woocommerce_params === 'undefined' ) {
				return false;
			}
 
			$checkout_form = $( 'form.checkout' );
 
			$checkout_form.on( 'change', 'input[name="payment_method"]', function() {
					$checkout_form.trigger( 'update' );
			});
 
 
		});
    </script>
    <?php
    endif;
}
add_action( 'wp_footer', 'cart_update_script', 999 );
	
	
// Do something if category "download" is in the Cart      
if ( $cat_in_cart ) {
 
// For example, print a notice
//wc_print_notice( '対象アクセサリー2個同時注文特別価格は3個、5個などでは適用されませんのでご注意ください。2個、または4個のみで適用されます。', 'notice' );
 
// Or maybe run your own function...
// ..........
 
}
 
}

/*hide menu for other roles*/
add_action('admin_menu', 'remove_menus', 99999);
function remove_menus () {
	global $menu;
	$user = wp_get_current_user();
	$allowed_roles = array('administrator');
	if (is_admin() && !array_intersect($allowed_roles, $user->roles ) ) {
        remove_menu_page( 'wpcf7' );
		remove_menu_page( 'edit.php?post_type=acf-field-group' );
		remove_menu_page( 'elsey_options' ); //not working for Elsey Options
		remove_menu_page( 'productsize_chart' ); //not working for Size Chart
		remove_menu_page( 'edit.php?post_type=chart' ); //not working for Size Chart
		//remove_menu_page( 'wcst-shipping-tracking' );//not working for Shipping tracking
		remove_menu_page( 'yit_plugin_panel' );//not working for YITH Plugins
		remove_menu_page( 'yikes-inc-easy-mailchimp' );//not working for Easy Forms
		remove_menu_page( 'duplicator-tools' );//not working for Duplicator
		remove_menu_page( 'regenerate-thumbnails' );
		remove_menu_page( 'zoo-cw-settings' );
		remove_menu_page( 'mailchimp-for-wp' );
		remove_menu_page( 'wp_file_manager' );
		remove_menu_page( 'wpml_plugin_log' );
		remove_menu_page( 'siteguard' );
		remove_menu_page( 'duplicator' );
		remove_menu_page( 'vc-general' );
		remove_menu_page('tools.php');	// Tools
		remove_menu_page('options-general.php'); //setting
		remove_menu_page('upload.php'); //Media
		remove_submenu_page( 'index.php', 'ae_license' );
    }
}
add_action('admin_menu', 'remove_menus');
/*hide woocommerce submenu for other roles*/
function wooninja_remove_items() {
 $remove = array( 'wc-settings', 'wc-status', 'wc-addons', 'wc4jp-epsilon-output','checkout_form_designer', );
  foreach ( $remove as $submenu_slug ) {
   if ( ! current_user_can( 'update_core' ) ) {
    remove_submenu_page( 'woocommerce', $submenu_slug );
   }
  }
}

add_action( 'admin_menu', 'wooninja_remove_items', 99, 0 );
/*add class to body*/
add_filter( 'body_class', 'add_page_slug_class_name' );
function add_page_slug_class_name( $classes ) {
	if ( is_page() ) {
		$page = get_post( get_the_ID() );
		$classes[] = $page->post_name . '-template';

		$parent_id = $page->post_parent;
		if ( $parent_id ) {
			$classes[] = get_post($parent_id)->post_name . '-child-template';
		}
		
	}
	return $classes;
}
add_filter('body_class', 'add_preload_class_names');
function add_preload_class_names($classes) {
	if ( is_page_template('page-eyeliner.php') ) {
			$classes[] = 'preload';
	}
	return $classes;
}
//woo category sidebar widget
add_filter( 'woocommerce_product_categories_widget_args', 'rv_exclude_wc_widget_categories' );
function rv_exclude_wc_widget_categories( $cat_args ) {
	$cat_args['exclude'] = '77, 72, 1, 124'; // Insert the product category IDs you wish to exclude
	return $cat_args;
}
/*add custom logo menu for sticky*/
add_filter( 'wp_nav_menu_items', 'custom_menu_item_logo', 10, 2 );
function custom_menu_item_logo ( $items, $args ) {
	if ($args->theme_location == 'primary') {
		$items_array = array();
		while ( false !== ( $item_pos = strpos ( $items, '<li', 1 ) ) )
		{
			$items_array[] = substr($items, 0, $item_pos);
			$items = substr($items, $item_pos);
		}
		$items_array[] = $items;
		$elsey_brand_logo_default = cs_get_option('brand_logo_default');
		array_splice($items_array, 0, 0, '<li class="navLogo"><a href="'. esc_url(home_url( '/' )) .'"><img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="sticky-logo"></a></li>');
		$items = implode('', $items_array);
	}
	return $items;
}
/*add custom actions menu for sticky*/
add_filter( 'wp_nav_menu_items', 'custom_menu_item_actions', 10, 2 );
function custom_menu_item_actions ( $items2, $args ) {
	//$items2 = "";
	if ($args->theme_location == 'primary') {
		$items2 .= '<li class="navActions"><ul>';
		$elsey_myaccount_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
		$items2 .= '<li class="action-minibar els-user-icon"><a href="' . esc_url($elsey_myaccount_url) . '" class="link-actions"><i class="cmn-icon cmn-single-03-2"></i></a></li>';
		$elsey_menubar_wishlist    = cs_get_option('menubar_wishlist');
		if ( $elsey_menubar_wishlist && class_exists('WooCommerce') ) {
			if ( defined( 'YITH_WCWL' ) ) {
				$els_wishlist_count = YITH_WCWL()->count_products();
				$els_wishlist_url   = get_permalink(get_option('yith_wcwl_wishlist_page_id'));
				$elsey_icon_wishlist_black = ELSEY_IMAGES.'/wishlist-icon.png';
				$els_wishlist_class = ($els_wishlist_count) ? 'els-wishlist-filled' : 'els-wishlist-empty';
				$items2 .= '<li class="action-minibar els-wishlist-icon '. esc_attr($els_wishlist_class) .'"><a href="'. esc_url($els_wishlist_url) .'" class="link-actions"><i class="cmn-icon cmn-heart-2-3"></i></a></li>';
			}
		}
		$elsey_menubar_cart        = cs_get_option('menubar_cart');
		if ( $elsey_menubar_cart && class_exists('WooCommerce') ) {
			global $woocommerce;
			$items2 .= '<li id="els-shopping-cart-content-sticky" class="els-shopping-cart-content-sticky action-minibar"><a href="javascript:void(0);" id="els-cart-trigger-sticky" class="link-actions">';
			$items2 .= '<span class="action-icon-count">';
			if ( $woocommerce->cart->get_cart_contents_count() == '0' ) {
				$items2 .= '<span class="els-cart-count els-cart-zero">' . esc_attr($woocommerce->cart->get_cart_contents_count()) . '</span>';
			} else {
				$items2 .= '<span class="els-cart-count">'. esc_attr($woocommerce->cart->get_cart_contents_count()) .'</span>';
			}
			$items2 .= '<i class="cmn-icon cmn-bag-09-2"></i></span>';
			$items2 .= '</a></li>';
		}
		$items2 .= '</li></ul>';
	}
	return $items2;
}
/*override checkiout.min.js*/
add_action( 'wp_enqueue_scripts', 'custom_wp_enqueue_scripts_for_frontend', 99 );
function custom_wp_enqueue_scripts_for_frontend(){
	if( is_checkout() ){
		// Checkout Page
		wp_deregister_script('wc-checkout');
		wp_register_script('wc-checkout', get_stylesheet_directory_uri() . "/woocommerce/assets/js/frontend/checkout.js",
				array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), WC_VERSION, TRUE);
		wp_enqueue_script('wc-checkout');
	}

}
/*address form*/
add_filter( 'woocommerce_form_field_args', 'custom_wc_form_field_args', 10, 3 );
function custom_wc_form_field_args( $args, $key, $value ){
	// Only on My account > Edit Adresses
	if( is_wc_endpoint_url( 'edit-account' ) || is_checkout() ) return $args;

	$args['label_class'] = array('label');

	return $args;
}
/*change positon of payment section*/
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_payment', 20 );


add_action( 'user_register', 'elsey_register_new_user', 10, 1 );
function elsey_register_new_user($user_id){
	$user = get_user_by( 'id', $user_id );
	if( $user && isset($_POST['birth_year']) ) {
		update_user_meta($user_id, 'birth_year', $_POST['birth_year']);
		update_user_meta($user_id, 'billing_birth_year', $_POST['birth_year']);
		
		update_user_meta($user_id, 'birth_month', $_POST['birth_month']);
		update_user_meta($user_id, 'billing_birth_month', $_POST['birth_month']);
		
		update_user_meta($user_id, 'birth_day', $_POST['birth_day']);
		update_user_meta($user_id, 'billing_birth_day', $_POST['birth_day']);
	}
}

function getArrayYearMonthDay()
{
	$aTimes = array();
	
	$aTimes['years'][''] = __('Select Year Of Birth', 'elsey');
	$aTimes['months'][''] = __('Select Month Of Birth', 'elsey');
	$aTimes['days'][''] = __('Select Day Of Birth', 'elsey');
	
	for($i = date('Y') - 12; $i >= 1910; $i--)
	{
		$aTimes['years'][$i] = $i;
	}
	
	for($i = 1; $i <= 12; $i++)
	{
		$aTimes['months'][$i] = $i;
	}
	
	for($i = 1; $i <= 31; $i++)
	{
		$aTimes['days'][$i] = $i;
	}
	return $aTimes;
}

function getRetailYearMonthDay()
{
	$aTimes = array();

	$aTimes['years'][''] = __('年を選択', 'elsey');
	$aTimes['months'][''] = __('月を選択', 'elsey');
	$aTimes['days'][''] = __('日を選択', 'elsey');
	
	$max_year = date("Y", strtotime("+6 months"));
	$max_month = 12; //date("m", strtotime("+10 months"));
	
	$min_year = date('Y', strtotime("+7 days"));
	$min_month = date('m', strtotime("+7 days"));
	$min_month = date('m');
	$min_day = date('d', strtotime("+4 days"));
	
	$current_month = date('m');
	$current_day = date('d');
	$current_year = date('Y');
	
	// If has 1 year option, remove the title
	if ($current_year == $max_year) unset($aTimes['years']['']);
	
	for($i = $min_year; $i <= $max_year; $i++)
	{
		$aTimes['years'][$i] = $i;
	}

	for($i = $min_month; $i <= $max_month; $i++)
	{
		$i = strlen($i) == 1 ? '0' . $i : $i;
		$aTimes['months'][$i] = $i;
	}

	for($i = $min_day; $i <= 31; $i++)
	{
		$i = strlen($i) == 1 ? '0' . $i : $i;
		$aTimes['days'][$i] = $i;
	}
	$aTimes['min_year'] = $min_year;
	$aTimes['min_month'] = $min_month;
	$aTimes['min_day'] = $min_day;
	/*echo '<pre>';
	print_r($aTimes);
	exit;*/
	return $aTimes;
}
/*remove country field from checkout*/
function custom_override_checkout_fields( $fields )
{
// 	unset($fields['billing']['billing_country']);
// 	unset($fields['shipping']['shipping_country']);
	return $fields;
}
add_filter('woocommerce_checkout_fields','custom_override_checkout_fields');

function custom_override_billing_fields( $fields ) {
	//unset($fields['billing_country']);

	$current_user = wp_get_current_user();
	$fields['billing_last_name_kana'] = array(
		'label'     => __('姓(ふりがな)', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-first')
	);
	$fields['billing_first_name_kana'] = array(
		'label'     => __('名(ふりがな)', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-last'),
		'clear'     => true
	);
	
	$aTimes = getArrayYearMonthDay();
	
	if (is_checkout())
	{
		$fields['billing_birth_year'] = array(
			'label'     => __('Birth Year', 'elsey'),
			'required'  => true,
			'type'  => 'select',
			'options' => $aTimes['years'],
			'class'     => array('form-row-first-3 form-row-wide')
		);
		$fields['billing_birth_month'] = array(
			'label'     => __('Birth Month', 'elsey'),
			'required'  => true,
			'type'  => 'select',
			'options' => $aTimes['months'],
			'class'     => array('form-row-middle-3 form-row-wide')
		);
		$fields['billing_birth_day'] = array(
			'label'     => __('Birth Day', 'elsey'),
			'required'  => true,
			'type'  => 'select',
			'options' => $aTimes['days'],
			'class'     => array('form-row-last-3 form-row-wide')
		);
	}

	$fields['billing_last_name']['class'] = array('form-row-first');
	$fields['billing_first_name']['class'] = array('form-row-last');
	$fields['billing_postcode']['class'] = array('form-row-first', 'address-field');
	$fields['billing_country']['class'] = array('form-row-wide', 'address-field');
	$fields['billing_state']['class'] = array('form-row-last', 'address-field');
	$fields['billing_city']['class'] = array('form-row-wide', 'address-field');

	//change order
	$order = array(
		"billing_last_name",
		"billing_first_name",
		"billing_last_name_kana",
		"billing_first_name_kana",
		"billing_country",
		"billing_postcode",
		"billing_state",
		"billing_city",
		"billing_address_1",
		"billing_address_2",
		"billing_phone",
		"billing_email",
	);

	if ((!get_user_meta($current_user->ID, 'birth_year', true) && is_checkout()) || !is_checkout())
	{
		$order[] = 'billing_birth_year';
		$order[] = 'billing_birth_month';
		$order[] = 'billing_birth_day';
	}
	
	$ordered_fields = array();
	foreach($order as $indexField => $field)
	{
		if (isset($fields[$field]))
		{
			$fields[$field]['priority'] = ($indexField + 1) * 10;
			$ordered_fields[$field] = $fields[$field];
		}
	}

	$fields = $ordered_fields;
	return $fields;
}
add_filter( 'woocommerce_billing_fields' , 'custom_override_billing_fields', 100000 );

function custom_override_shipping_fields( $fields ) {
// 	unset($fields['shipping_country']);

	$fields['shipping_last_name_kana'] = array(
		'label'     => __('姓(ふりがな)', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-first')
	);
	$fields['shipping_first_name_kana'] = array(
		'label'     => __('名(ふりがな)', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-last'),
		'clear'     => true
	);

	$fields['shipping_last_name']['class'] = array('form-row-first');
	$fields['shipping_first_name']['class'] = array('form-row-last');
	$fields['shipping_postcode']['class'] = array('form-row-first', 'address-field');
	$fields['shipping_country']['class'] = array('form-row-wide', 'address-field');
	$fields['shipping_state']['class'] = array('form-row-last', 'address-field');
	$fields['shipping_city']['class'] = array('form-row-wide', 'address-field');

	//change order
	$order = array(
		"shipping_last_name",
		"shipping_first_name",
		"shipping_last_name_kana",
		"shipping_first_name_kana",
		"shipping_country",
		"shipping_postcode",
		"shipping_state",
		"shipping_city",
		"shipping_address_1",
		"shipping_address_2",
		"shipping_phone",
	);

	$ordered_fields = array();
	foreach($order as $field)
	{
		$fields[$field]['priority'] = ($indexField + 1) * 10;
		$ordered_fields[$field] = $fields[$field];
	}

	$fields = $ordered_fields;

	return $fields;
}
add_filter( 'woocommerce_shipping_fields' , 'custom_override_shipping_fields' );


/*remove postcode from shipping calculater*/
add_filter( 'woocommerce_shipping_calculator_enable_postcode', '__return_false' );

/*remove additional info*/
add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 98 );

function bbloomer_remove_product_tabs( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}
/*remove shortdescription*/
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
/*add custom tab in woo setting*/
add_filter( 'woocommerce_get_sections_products' , 'returnship_add_settings_tab' );
function returnship_add_settings_tab( $settings_tab ){
	$settings_tab['return_shipping_notices'] = __( 'Return&Shipping Notices' );
	return $settings_tab;
}
add_filter( 'woocommerce_get_settings_products' , 'returnship_get_settings' , 10, 2 );
function returnship_get_settings( $settings, $current_section ) {
	$custom_settings = array();
	if( 'return_shipping_notices' == $current_section ) {
		$custom_settings =  array(
			array(
				'name' => __( 'Return&Shipping Notices' ),
				'type' => 'title',
				'desc' => __( '全ての商品共通の配送返品について' ),
				'id'   => 'return_shipping'
			),
			array(
				'name' => __( 'この記載を表示する' ),
				'type' => 'checkbox',
				'desc' => __( '表記の有無'),
				'id'	=> 'enable'
			),
			array(
				'name' => __( '返品について' ),
				'type' => 'textarea',
				'desc' => __( '返品についての概要'),
				'desc_tip' => true,
				'id'	=> 'msg_threshold'
			),
			array(
				'name' => __( 'Position' ),
				'type' => 'select',
				'desc' => __( 'Position of the notice on the product page'),
				'desc_tip' => true,
				'id'	=> 'position',
				'options' => array(
					'top' => __( 'Top' ),
					'bottom' => __('Bottom')
				)
			),
			array( 'type' => 'sectionend', 'id' => 'return_shipping' ),
		);
		return $custom_settings;
	} else {
		return $settings;
	}
}
/*add custom tab in woo setting2*/
add_filter( 'woocommerce_get_sections_products' , 'notice_add_settings_tab' );
function notice_add_settings_tab( $settings_tab ){
	$settings_tab['common_notices'] = __( 'Notice' );
	return $settings_tab;
}
add_filter( 'woocommerce_get_settings_products' , 'notice_get_settings' , 10, 2 );
function notice_get_settings( $settings, $current_section ) {
	$custom_settings = array();
	if( 'common_notices' == $current_section ) {
		$custom_settings =  array(
			array(
				'name' => __( 'Return&Shipping Notices' ),
				'type' => 'title',
				'desc' => __( '全ての商品共通の注意事項について' ),
				'id'   => 'notice_desc'
			),
			array(
				'name' => __( 'この記載を表示する' ),
				'type' => 'checkbox',
				'desc' => __( '表記の有無'),
				'id'	=> 'enable_notice'
			),
			array(
				'name' => __( '注意事項について' ),
				'type' => 'textarea',
				'desc' => __( '注意事項についての文章'),
				'desc_tip' => true,
				'id'	=> 'msg_threshold_notice'
			),
			array(
				'name' => __( 'About notes In English' ),
				'type' => 'textarea',
				'desc' => __( 'Text on notes'),
				'desc_tip' => true,
				'id'	=> 'msg_threshold_notice_en'
			),
			array(
				'name' => __( 'Position' ),
				'type' => 'select',
				'desc' => __( 'Position of the notice on the product page'),
				'desc_tip' => true,
				'id'	=> 'position_notice',
				'options' => array(
					'top' => __( 'Top' ),
					'bottom' => __('Bottom')
				)
			),
			array( 'type' => 'sectionend', 'id' => 'notice_desc' ),
		);
		return $custom_settings;
	} else {
		return $settings;
	}
}
/*change products per a page*/

/*add custom fields to product edit*/
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');

// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');

//English Name
function woocommerce_product_custom_fields()
{
	global $woocommerce, $post;
	echo '<div class="product_custom_field">';
	// Custom Product Text Field
	woocommerce_wp_text_input(
			array(
				'id' => '_custom_product_text_field',
				'placeholder' => '英語商品名',
				'label' => __('English Name', 'woocommerce'),
				'desc_tip' => 'true'
			)
			);
	echo '</div>';
	
	echo '<div class="product_custom_field">';
	// Custom Product Text Field
	woocommerce_wp_checkbox(
		array(
		'id' => '_is_specific_cart_item',
		'label' => __('Is Specific Cart Item ?', 'elsey'),
		'description' => __('This is specific item, which must be purchased separeately with other', 'elsey'),
		'desc_tip' => 'true'
		)
	);
	echo '</div>';
	
	echo '<div class="product_custom_field">';
	// Custom Product Text Field
	woocommerce_wp_checkbox(
		array(
		'id' => '_is_no_free_shipping_item',
		'label' => __('Use default shipping method?', 'elsey'),
		'description' => __('Use default shipping method and no free shipping', 'elsey'),
		'desc_tip' => 'true'
		)
	);
	echo '</div>';
	/*

	echo '<div class="product_custom_field">';
	// Custom Product Text Field
	woocommerce_wp_text_input(
	array(
	'id' => '_custom_product_text_field_jan_code',
	'placeholder' => 'JAN Code',
	'label' => __('JAN Code', 'woocommerce'),
	'desc_tip' => 'true'
	)
	);
	echo '</div>';


	*/




}
function woocommerce_product_custom_fields_save($post_id)
{
	// Custom Product Text Field
	$woocommerce_custom_product_text_field = $_POST['_custom_product_text_field'];
	if (!empty($woocommerce_custom_product_text_field))
		update_post_meta($post_id, '_custom_product_text_field', esc_attr($woocommerce_custom_product_text_field));

	if ( isset($_POST['_is_specific_cart_item']) )
	{
		update_post_meta($post_id, '_is_specific_cart_item', esc_attr($_POST['_is_specific_cart_item']));
	}
	else
	{
		update_post_meta($post_id, '_is_specific_cart_item', '');
	}
	
	if ( isset($_POST['_is_no_free_shipping_item']) )
	{
		update_post_meta($post_id, '_is_no_free_shipping_item', esc_attr($_POST['_is_no_free_shipping_item']));
	}
	else
	{
		update_post_meta($post_id, '_is_no_free_shipping_item', '');
	}
		/*

		// Custom Product Text Field
		$woocommerce_custom_product_text_field = $_POST['_custom_product_text_field_jan_code'];
		if (!empty($woocommerce_custom_product_text_field))
			update_post_meta($post_id, '_custom_product_text_field_jan_code', esc_attr($woocommerce_custom_product_text_field));

			*/

		// Custom Product Number Field
		$woocommerce_custom_product_number_field = $_POST['_custom_product_number_field'];
		if (!empty($woocommerce_custom_product_number_field))
			update_post_meta($post_id, '_custom_product_number_field', esc_attr($woocommerce_custom_product_number_field));
			// Custom Product Textarea Field
			$woocommerce_custom_procut_textarea = $_POST['_custom_product_textarea'];
			if (!empty($woocommerce_custom_procut_textarea))
				update_post_meta($post_id, '_custom_product_textarea', esc_html($woocommerce_custom_procut_textarea));

}

//variation custom field JAN CODE
add_action('woocommerce_product_options_sku','add_jancode', 10, 0 );
function add_jancode(){

	global $woocommerce, $post;

	// getting the barcode value if exits
	$product_jancode = get_post_meta( $post->ID, '_jancode', true );
	if( ! $product_jancode ) $product_jancode = '';

	// Displaying the barcode custom field
	woocommerce_wp_text_input( array(
		'id'          => '_jancode',
		'label'       => __('JAN Code','woocommerce'),
		'placeholder' => 'JAN Code',
		'desc_tip'    => 'true',
		'description' => __('JAN Code.','woocommerce')
	), $product_jancode); // <== added "$product_jancode" here to get the value if exist

}

add_action( 'woocommerce_process_product_meta', 'save_jancode', 10, 1 );
function save_jancode( $post_id ){

	$product_jancode_field = $_POST['_jancode'];
	if( !empty( $product_jancode_field ) )
		update_post_meta( $post_id, '_jancode', esc_attr( $product_jancode_field ) );

}

add_action( 'woocommerce_product_after_variable_attributes','add_jancode_variations',10 , 3 );
function add_jancode_variations( $loop, $variation_data, $variation ){

	$variation_jancode = get_post_meta($variation->ID,"_jancode", true );
	if( ! $variation_jancode ) $variation_jancode = "";

	woocommerce_wp_text_input( array(
		'id'          => '_jancode_' . $loop,
		'label'       => __('JAN Code','woocommerce'),
		'placeholder' => 'JAN Code',
		'desc_tip'    => 'true',
		'description' => __('JAN Code.','woocommerce'),
		'value' => $variation_jancode,
	) );
}
//Save Variation JANCode
add_action( 'woocommerce_save_product_variation','save_jancode_variations', 10 ,2 );
function save_jancode_variations( $variation_id, $loop ){

	global $wpdb;
	$jancode = trim($_POST["_jancode_$loop"]);
	$products = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->postmeta." WHERE meta_key=%s AND meta_value=%s", '_jancode', $jancode ) );
	$newProducts = array();
	foreach ($products as $product)
	{
		if ($product->post_id != $variation_id)
		{
			$newProducts[] = $product;
		}
	}
	if (!empty($newProducts))
	{
		$errors = new WP_Error();
		$errors->add( 'DUPLICATE_JANCODE', __( 'Invalid or duplicated Jancode.', 'elsey' ) );
		WC_Admin_Meta_Boxes::add_error( $errors->get_error_message() );
	}
	elseif(!empty($jancode))
	{
		$jancode = (string) $jancode;
		update_post_meta( $variation_id, '_jancode', sanitize_text_field($jancode) );
	}
}

/*edit minicart buttons*/
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

function my_woocommerce_widget_shopping_cart_button_view_cart() {
	echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button button--primary button--full">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>';
}
function my_woocommerce_widget_shopping_cart_proceed_to_checkout() {
	echo '<div class="align--center order__actions__item"><a href="' . esc_url( wc_get_checkout_url() ) . '" class="button checkout button--link button--full">' . esc_html__( 'Checkout', 'woocommerce' ) . '</a></div>';
}
add_action( 'woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_button_view_cart', 10 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

/*show sku in cart/wishlist */
add_filter("woocommerce_in_cartproduct_obj_title", "wdm_test", 10 , 2);

function wdm_test($product_title, $product){
	if(is_a($product, "WC_Product_Variation")){
		$parent_id = $product->get_parent_id();
		$parent = get_product($parent_id);
		$product_test    = get_product($product->variation_id);
		$product_title = $parent->name;
		$attributes = $product->get_attributes();
		$html = '';
		
		if (elsey_is_ja_lang())
		{
			$html .= '<div class="mini-product__item mini-product__name-en small-text"><a href="'. esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $parent->id ) ) ) . '">' . get_post_meta($parent->id, '_custom_product_text_field', true) . '</a></div>';
		}
		$html .='<div class="mini-product__item mini-product__name-ja p6">
			<a href="'. esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $parent->id ) ) ) . '">
				'.$product_title . '
			</a>
         </div>';
		 
		foreach ($attributes as $attribute_key => $attribute_value)
		{
			$display_key   = wc_attribute_label( $attribute_key, $product );
			$display_value = $attribute_value;
			 
			if ( taxonomy_exists( $attribute_key ) ) {
				$term = get_term_by( 'slug', $attribute_value, $attribute_key );
				if ( ! is_wp_error( $term ) && is_object( $term ) && $term->name ) {
					$display_value = $term->name;
				}
			}
			$html .= '<div class="mini-product__item mini-product__attribute">
						<span class="label variation-color">'. $display_key .':</span>
						<span class="value variation-color">'. $display_value .'</span>
					</div>';
		}
		 
		$html .= '<p class="mini-product__item mini-product__id light-copy">商品番号 #' . $product_test->get_sku() . '</p>';
		return $html;
	}
	elseif( is_a($product, "WC_Product") ){
		$product_test    = new WC_Product($product->id);

		$html = '';
		if (elsey_is_ja_lang())
		{
			$html .= '<div class="mini-product__item mini-product__name-en small-text"><a href="'. esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $product->id ) ) ) . '">' . get_post_meta($product->id, '_custom_product_text_field', true) . '</a></div>';
		}
		$html .= '<div class="mini-product__item mini-product__name-ja p6">
			<a href="'. esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $product->id ) ) ) . '">
				'.$product_title . '
			</a>
         </div>' .
         '<p class="mini-product__item mini-product__id light-copy">商品番号 #' . $product_test->get_sku() . '</p>';
	}
	else{
		return $product_title ;
	}
}
//add action give it the name of our function to run
add_action( 'woocommerce_after_shop_loop_item_title', 'wcs_stock_text_shop_page', 25 );

//create our function
function wcs_stock_text_shop_page() {

	//returns an array with 2 items availability and class for CSS
	global $product;
	$availability = $product->get_availability();

	//check if availability in the array = string 'Out of Stock'
	//if so display on page.//if you want to display the 'in stock' messages as well just leave out this, == 'Out of stock'
	if ( $availability['availability'] == 'Out of stock') {
		echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>', $availability['availability'] );
	}
}
/****** NOT WORKING************/
/*hide stock not working*/
function my_wc_hide_in_stock_message( $html='', $text, $product='' ) {

	if($product !=''){
		$availability = $product->get_availability();
		if ( isset( $availability['class'] ) && 'in-stock' === $availability['class'] ) {
			return '';
		}
	}else{

		if ('in-stock' != $text ) {
			return '';
		}
	}

	return $html;
}
add_filter( 'woocommerce_stock_html', 'my_wc_hide_in_stock_message', 10, 3 );

//webfonts icons
function webfonts_scripts ()
{
	wp_enqueue_style('font_css', get_stylesheet_directory_uri() . '/fonts/fonts.css?v=' . time());
	wp_enqueue_style('icong_css', get_stylesheet_directory_uri() . '/icons/css/event-gicons.css?v=' . time());
}
add_action('wp_enqueue_scripts', 'webfonts_scripts');


//Validation jquery
function smoke_scripts ()
{
	wp_enqueue_style('smoke_css', get_stylesheet_directory_uri() . '/js/smoke/css/smoke.min.css');
	wp_enqueue_script('smoke_js', get_stylesheet_directory_uri() . '/js/smoke/js/smoke.min.js', array( 'jquery' ),'', true);
	wp_enqueue_script('smoke_lang', get_stylesheet_directory_uri() . '/js/smoke/lang/ja.js', array( 'jquery' ),'', true);
	wp_enqueue_script('remodal_js', get_stylesheet_directory_uri() . '/js/remodal/remodal.js', array( 'jquery' ),'', true);
	wp_enqueue_style('remodal_css', get_stylesheet_directory_uri() . '/js/remodal/remodal.css');
	wp_enqueue_style('remodaltheme_css', get_stylesheet_directory_uri() . '/js/remodal/remodal-default-theme.css');
	wp_enqueue_style( 'woo_css', get_stylesheet_directory_uri() . '/css/woo.css?v='. time() );
	wp_enqueue_style( 'overwrite_css', get_stylesheet_directory_uri() . '/overwrite.css?201812272312' );
	wp_enqueue_style('slick_css', get_stylesheet_directory_uri() . '/js/slick/slick.css');
	wp_enqueue_style('slicktheme_css', get_stylesheet_directory_uri() . '/js/slick/slick-theme.css');
	wp_enqueue_style('validation_engine_css', get_stylesheet_directory_uri() . '/css/validationEngine.jquery.css');
	wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine.js', array('jquery'));
	wp_enqueue_script('validation_engine_ja_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine-ja.js', array('jquery'));
	
	wp_enqueue_script('overlay', get_stylesheet_directory_uri() . '/js/loadingoverlay.js', array('jquery'));
	
	// Swiper
	wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/js/swiper/swiper.min.js', array('jquery'));
	wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/js/swiper/swiper.css');
}
add_action('wp_enqueue_scripts', 'smoke_scripts');

//unset specific categories
add_filter( 'get_terms', 'exclude_category', 10, 3 );
function exclude_category( $terms, $taxonomies, $args ) {
    $new_terms = array();
    if ( is_shop() ){
        foreach ( $terms as $key => $term ) {
            if( is_object ( $term ) ) {
                if ( 'twoset_price_jwl' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'threeset10poff' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'springfair2018mayacc' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'springfair2018may' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'springfair2018mayone' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'thespringsale18' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
				if ( 'finalsummersale' == $term->slug && $term->taxonomy = 'product_cat' ) {
                    unset($terms[$key]);
                }
            }
        }
    }
    return $terms;
}
//call remodal for enter page
/*function call_enter_remodal() {

if ( is_page('enter') ) {
ob_start();
get_template_part( 'template-parts/enter', 'modal' );
$content = ob_get_clean();
}
return $content;
}
add_action('wp_footer', 'call_enter_remodal');*/

function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'remove-admin-menus-by-role/remove-admin-menus-by-role.php',
		'productsize-chart-for-woocommerce/productsize-chart-for-woocommerce.php',
		'woocommerce-free-gift/woocommerce-free-gift.php',
		'woocommerce-pretty-emails/emailplus.php',
		'woocommerce-shipping-tracking/shipping-tracking.php'
	);
	$active_plugins = get_option('active_plugins');

	$myplugins = $wp_list_table->items;
	foreach ( $myplugins as $key => $val )
	{
		if ( in_array($key, $hidearr) && in_array($key, $active_plugins))
		{
			unset($wp_list_table->items[$key]);
		}
	}
}
add_action('pre_current_active_plugins', 'hide_plugin_order_by_product');


add_filter('woocommerce_cart_shipping_method_full_label', 'elsey_woocommerce_cart_shipping_method_full_label', 10, 3);
function elsey_woocommerce_cart_shipping_method_full_label ($label, $method)
{
	if ( $method->cost > 0 ) {
		$label = '<span class="small-text">' . $method->get_label() . '</span>';
		if ( WC()->cart->tax_display_cart == 'excl' ) {
			$label .= ': ' . wc_price( $method->cost );
			if ( $method->get_shipping_tax() > 0 && wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
			}
		} else {
			$label .= ': ' . wc_price( $method->cost + $method->get_shipping_tax() );
			if ( $method->get_shipping_tax() > 0 && ! wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
			}
		}
	}
	else {
		$label = '<span class="small-text free_shipping">' . __('Free Shipping', 'elsey') . '</span>';
	}
	return $label;
}

add_filter( 'the_title', 'woo_title_order_received', 10, 2 );
function woo_title_order_received( $title, $id ) {
	if ( function_exists( 'is_order_received_page' ) &&
			is_order_received_page() && get_the_ID() === $id ) {
				$title = "ご注文完了";
			}
			return $title;
}

function wc_cart_totals_order_total_html1() {

	$value = '<strong>'. WC()->cart->get_total(). '</strong>';
	echo $value;
}
remove_all_filters('woocommerce_cart_totals_order_total_html');
add_filter( 'woocommerce_cart_totals_order_total_html', 'wc_cart_totals_order_total_html1' );


add_filter( 'woocommerce_email_order_meta_fields', 'elsey_woocommerce_email_order_meta_fields', 1000, 3 );
add_filter( 'woocommerce_email_order_meta_keys', 'elsey_woocommerce_email_order_meta_fields', 1000, 3 );
function elsey_woocommerce_email_order_meta_fields($fields, $sent_to_admin = null, $order = null){
	$fields = array();
	return $fields;
}

add_filter( 'cs_framework_settings', 'elsey_cs_framework_settings', 100, 1 );
function elsey_cs_framework_settings ($settings)
{
	$settings['ajax_save'] = true;
	return $settings;
}

add_filter('woocommerce_cart_item_name', 'elsey_woocommerce_cart_item_name', 10, 3);
add_filter('woocommerce_order_item_name', 'elsey_woocommerce_cart_item_name', 10, 3);
function elsey_woocommerce_cart_item_name ($product_name, $cart_item, $cart_item_key)
{
	$product = get_product($cart_item['product_id']);

	if ($cart_item['variation_id'])
	{
		$variation = get_product($cart_item['variation_id']);
		$product_link = $variation->get_permalink( $cart_item );
		$product_name = $product->name;
	}
	else {
		$product_link = $product->get_permalink( $cart_item );
		$product_name = $product->name;
	}

	return sprintf( '<a href="%s">%s</a>', $product_link, $product_name );
}

function my_formatted_billing_adress($ord) {
	$address = apply_filters('woocommerce_order_formatted_billing_address', array(
		'last_name' => $ord->billing_last_name,
		'first_name' => $ord->billing_first_name,
		'kana_first_name' => $ord->billing_first_name_kana,
		'kana_last_name' => $ord->billing_last_name_kana,
		'company' => $ord->billing_company,
		'postcode' => $ord->billing_postcode,
		'state' => $ord->billing_state,
		'city' => $ord->billing_city,
		'address_1' => $ord->billing_address_1,
		'address_2' => $ord->billing_address_2,
		'country' => $ord->billing_country
	), $ord);

	$add = WC()->countries->get_formatted_address($address);
	return $add;
}

function my_formatted_shipping_adress($ord) {
	if ($ord->shipping_address_1 || $ord->shipping_address_2) {

		// Formatted Addresses
		$address = apply_filters('woocommerce_order_formatted_shipping_address', array(
			'first_name' => $ord->shipping_first_name,
			'last_name' => $ord->shipping_last_name,
			'kana_first_name' => $ord->shipping_first_name_kana,
			'kana_last_name' => $ord->shipping_last_name_kana,
			'company' => $ord->shipping_company,
			'postcode' => $ord->shipping_postcode,
			'state' => $ord->shipping_state,
			'city' => $ord->shipping_city,
			'address_1' => $ord->shipping_address_1,
			'address_2' => $ord->shipping_address_2,
			'country' => $ord->shipping_country
		), $ord);

		$add = WC()->countries->get_formatted_address($address);
	}
	return $add;
}

function insertAtSpecificIndex($array = [], $item = [], $position = 0) {
	$previous_items = array_slice($array, 0, $position, true);
	$next_items     = array_slice($array, $position, NULL, true);
	return $previous_items + $item + $next_items;
}

// Begin - customize order detail ADMIN
add_filter( 'woocommerce_admin_billing_fields', 'look_woocommerce_admin_extra_fields', 10, 1 );
add_filter( 'woocommerce_admin_shipping_fields', 'look_woocommerce_admin_extra_fields', 10, 1 );
function look_woocommerce_admin_extra_fields($fields){
	$fieldExtras['last_name_kana'] = array(
		'label' => __( '姓(ふりがな)', 'woocommerce' ),
		'show'  => false
	);

	$fieldExtras['first_name_kana'] = array(
		'label' => __( '名(ふりがな)', 'woocommerce' ),
		'show'  => false
	);


	$fields = insertAtSpecificIndex($fields, $fieldExtras, array_search('last_name', array_keys($fields)) + 1);

	$fields['phone'] = array(
		'label' => __( 'phone', 'woocommerce' ),
	);
	return $fields;
}

add_filter('woocommerce_localisation_address_formats', 'elsey_woocommerce_localisation_address_formats', 1000);
function elsey_woocommerce_localisation_address_formats($formats) {
	if(is_admin())
	{
		$format_string = "{last_name} {first_name}\n{kananame}\n{company}\n{country}\n〒{postcode}\n{state}\n{city}\n{address_1}\n{address_2}";

	}
	else {
		$format_string = "<span class='readonly-address__item'>{last_name} {first_name} ({kananame})</span><span class='readonly-address__item'>{company}</span><br><span class='readonly-address__item'>{country}</span><span class='readonly-address__item'>〒{postcode}</span><span class='readonly-address__item'>{state}{city}{address_1}</span><span class='readonly-address__item'>{address_2}</span>";
	}
	$formats['JP'] = $formats['default'] = $format_string;
	return $formats;
}

add_filter( 'woocommerce_formatted_address_replacements', 'look_woocommerce_formatted_address_replacements', 10000, 2);
function look_woocommerce_formatted_address_replacements ($fields, $args)
{
	$fields['{kananame}'] = $args['kananame'];
	return $fields;
}

add_filter( 'woocommerce_order_formatted_billing_address', 'look_woocommerce_order_formatted_billing_address', 10000, 2);
function look_woocommerce_order_formatted_billing_address ($args, $order)
{
	$args['kananame'] = $order->billing_last_name_kana . $order->billing_first_name_kana;
	$args['country'] = $args['country'] ? : 'JP';
	return $args;
}

add_filter( 'woe_fetch_order_row', 'elsey_woe_fetch_order_row', 10000, 2);
function elsey_woe_fetch_order_row ($row, $order_id)
{
	foreach($row as $key => $field)
	{
		if (strpos($key, '_country') !== false && ($field == 'JP'))
		{
			$row[$key] = WC()->countries->countries[ 'JP' ];
		}
		elseif (strpos($key, '_state') !== false && ($row['billing_country'] == 'JP' || $row['shipping_country'] == 'JP'))
		{
			$states = WC()->countries->get_states( 'JP' );
			$row[$key] = $states[$field] ? $states[$field] : $row[$key];
		}
	}
	
	if (isset($row['USER_billing_birth_year']) && $row['USER_billing_birth_year'])
	{
		$current_year = current_time('Y');
		$row['USER_billing_birth_year'] = $current_year - $row['USER_billing_birth_year'];
	}
	
	if (isset($row['USER_birth_year']) && $row['USER_birth_year'])
	{
		$current_year = current_time('Y');
		$row['USER_birth_year'] = $current_year - $row['USER_birth_year'];
	}
	
	return $row;
}

add_filter( 'woocommerce_order_formatted_shipping_address', 'look_woocommerce_order_formatted_shipping_address', 10000, 2);
function look_woocommerce_order_formatted_shipping_address ($args, $order)
{
	$args['kananame'] = $order->shipping_last_name_kana . $order->shipping_first_name_kana;
	$args['country'] = $args['country'] ? : 'JP';
	return $args;
}

add_filter('woocommerce_customer_meta_fields', 'look_woocommerce_customer_meta_fields', 1000, 1);
function look_woocommerce_customer_meta_fields ($fields) {
	$extraBilling['billing_last_name_kana']['label'] = __( '姓(ふりがな)', 'woocommerce' );
	$extraBilling['billing_first_name_kana']['label'] = __( '名(ふりがな)', 'woocommerce' );

	$extraShipping['shipping_last_name_kana']['label'] = __( '姓(ふりがな)', 'woocommerce' );
	$extraShipping['shipping_first_name_kana']['label'] = __( '名(ふりがな)', 'woocommerce' );

	$fields['billing']['fields'] = insertAtSpecificIndex($fields['billing']['fields'], $extraBilling, array_search('billing_last_name', array_keys($fields['billing']['fields'])) + 1);
	$fields['shipping']['fields'] = insertAtSpecificIndex($fields['shipping']['fields'], $extraShipping, array_search('shipping_last_name', array_keys($fields['shipping']['fields'])) + 1);
	
	return $fields;
}
// Rename My Account navigation
function wpb_woo_my_account_order() {
	$myorder = array(
		//'my-custom-endpoint' => __( 'My Stuff', 'woocommerce' ),
		'edit-account'       => __( 'Member Information', 'elsey' ),
		//'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'orders'             => __( 'Order history', 'elsey' ),
		'favorite-list'      => __( 'Favorite items', 'elsey' ),
		'waitlist'           => __( 'Waitlist items', 'elsey' ),
		'edit-address'       => __( 'Addresses', 'woocommerce' ),
		'payment-methods'    => __( 'Credit Card Information', 'elsey' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),
	);
	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );
// END - customize order detail ADMIN

add_filter( 'woocommerce_my_account_my_address_formatted_address', 'look_woocommerce_my_account_my_address_formatted_address', 10000, 3);
function look_woocommerce_my_account_my_address_formatted_address($fields, $customer_id, $name) {
	$last_name_kana = get_user_meta( $customer_id, $name . '_last_name_kana', true );
	$first_name_kana = get_user_meta( $customer_id, $name . '_first_name_kana', true );
	$fields['kananame'] = $last_name_kana . $first_name_kana;
	return $fields;
}



//add fav content to my account
function carome_add_list_endpoint() {
	add_rewrite_endpoint( 'favorite-list', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'waitlist', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'carome_add_list_endpoint' );

// ------------------
// 2. Add new query var

function carome_list_query_vars( $vars ) {
	if (!in_array('favorite-list', $vars))
	{
		$vars[] = 'favorite-list';
	}
	if (!in_array('waitlist', $vars))
	{
		$vars[] = 'waitlist';
	}

	return $vars;
}
add_filter( 'query_vars', 'carome_list_query_vars', 0 );

// ------------------
// 3. Insert the new endpoint into the My Account menu

/*function carome_add_fav_list_link_my_account( $items ) {
 $items['favorite-list'] = __('お気に入りアイテム', 'elsey');
 $items['waitlist'] = __('再入荷待ちアイテム', 'elsey');
 return $items;
 }
 add_filter( 'woocommerce_account_menu_items', 'carome_add_fav_list_link_my_account' );*/


// ------------------
// 4. Add content to the new endpoint

function carome_fav_list_content() {
	echo '<h1 class="account__heading heading heading--xlarge serif">お気に入りアイテム</h1>';
	echo do_shortcode( ' [yith_wcwl_wishlist] ' );
}
add_action( 'woocommerce_account_favorite-list_endpoint', 'carome_fav_list_content' );
function carome_wait_list_content() {
	echo '<h1 class="account__heading heading heading--xlarge serif">再入荷待ちアイテム</h1>';
	echo do_shortcode( ' [woocommerce_my_waitlist] ' );
}
add_action( 'woocommerce_account_waitlist_endpoint', 'carome_wait_list_content' );

add_action( 'woocommerce_save_account_details', 'woocommerce_save_account_details_custom' );
function woocommerce_save_account_details_custom ($userID)
{
	update_user_meta($userID, 'first_name_kana', $_POST['account_first_name_kana']);
	update_user_meta($userID, 'last_name_kana', $_POST['account_last_name_kana']);
	
	update_user_meta($userID, 'birth_year', $_POST['birth_year']);
	update_user_meta($userID, 'birth_month', $_POST['birth_month']);
	update_user_meta($userID, 'birth_day', $_POST['birth_day']);
	
	update_user_meta($userID, 'billing_birth_year', $_POST['birth_year']);
	update_user_meta($userID, 'billing_birth_month', $_POST['birth_month']);
	update_user_meta($userID, 'billing_birth_day', $_POST['birth_day']);
}

add_action( 'woocommerce_save_account_details_required_fields', 'carome_woocommerce_save_account_details_required_fields' );
function carome_woocommerce_save_account_details_required_fields ($required_fields)
{
	$required_fields['account_first_name_kana'] = __( 'First Name Kana', 'woocommerce' );
	$required_fields['account_last_name_kana'] = __( 'Last Name Kana', 'woocommerce' );
	unset($required_fields['account_display_name']);
	return $required_fields;
}

/*Customer Date of birth*/
add_action( 'woocommerce_checkout_update_order_meta', 'elsey_custom_checkout_field_update_order_meta' );
function elsey_custom_checkout_field_update_order_meta( $order_id )
{
	$userID = get_current_user_id();
	if (!get_user_meta($userID, 'first_name_kana', true))
	{
		update_user_meta($userID, 'first_name_kana', $_POST['billing_first_name_kana']);
	}
	if (!get_user_meta($userID, 'last_name_kana', true))
	{
		update_user_meta($userID, 'last_name_kana', $_POST['billing_last_name_kana']);
	}
	
	if (!get_user_meta($userID, 'birth_year', true))
	{
		update_user_meta($userID, 'birth_year', $_POST['billing_birth_year']);
		update_user_meta($userID, 'billing_birth_year', $_POST['billing_birth_year']);
	}
	if (!get_user_meta($userID, 'birth_month', true))
	{
		update_user_meta($userID, 'birth_month', $_POST['billing_birth_month']);
		update_user_meta($userID, 'billing_birth_month', $_POST['billing_birth_month']);
	}
	if (!get_user_meta($userID, 'birth_day', true))
	{
		update_user_meta($userID, 'birth_day', $_POST['billing_birth_day']);
		update_user_meta($userID, 'billing_birth_day', $_POST['billing_birth_day']);
	}
	
	// Remove user group Offline payment
	global $wpdb;
	$wpdb->delete( $wpdb->prefix . 'groups_user_group', array( 'user_id' => $userID, 'group_id' => 3 ) );
	
	// Remove email added by admin for event
	zoa_remove_email_event();
}

add_filter('woocommerce_countries_base_state', 'elsey_woocommerce_countries_base_state');
function elsey_woocommerce_countries_base_state()
{
	return '';
}

add_action( 'wcwl_after_remove_user_from_waitlist', 'elsey_wcwl_after_remove_user_from_waitlist', 10, 2);
function elsey_wcwl_after_remove_user_from_waitlist ($product_id, $user )
{
	$user_id = $user->ID;
	$waitlist_user = get_user_meta($user_id, woocommerce_waitlist_user, true);
	$waitlist_user = $waitlist_user ? $waitlist_user : array();

	if (isset($waitlist_user) && isset($waitlist_user[$product_id])){
		unset($waitlist_user[$product_id]);
	}
	update_user_meta($user_id, 'woocommerce_waitlist_user', $waitlist_user);
}

add_action( 'wcwl_after_add_user_to_waitlist', 'elsey_wcwl_after_add_user_to_waitlist', 10, 2);
function elsey_wcwl_after_add_user_to_waitlist ($product_id, $user )
{
	$user_id = $user->ID;
	$waitlist_user = get_user_meta($user_id, woocommerce_waitlist_user, true);
	$waitlist_user = $waitlist_user ? $waitlist_user : array();
	if (!isset($waitlist_user) || !isset($waitlist_user[$product_id])){
		$waitlist_user[$product_id] = $product_id;
	}
	update_user_meta($user_id, 'woocommerce_waitlist_user', $waitlist_user);
}

function elsey_waitlist_user( $atts ) {
	ob_start();
	include( locate_template( 'waitlist-user.php' ) );
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode( 'waitlist_user', 'elsey_waitlist_user' );

add_filter('woocommerce_waitlist_supported_products', 'elsey_woocommerce_waitlist_supported_products', 1000, 1);
function elsey_woocommerce_waitlist_supported_products ($classes) {
	if ($_REQUEST['waitlist'] == 1)
	{
		global $post;
		$product_id = woocommerce_waitlist;
		$post = get_product($product_id);
	}
	return $classes;
}

add_filter('woocommerce_is_attribute_in_product_name', 'elsey_woocommerce_is_attribute_in_product_name', 1000, 3);
function elsey_woocommerce_is_attribute_in_product_name ( $is_in_name, $attribute, $name )
{
	if ($is_in_name && $attribute)
	{
		return false;
	}
	return $is_in_name;
}

add_filter('woocommerce_cart_item_product', 'elsey_woocommerce_cart_item_product', 1000, 3);
add_filter('woocommerce_order_item_product', 'elsey_woocommerce_cart_item_product', 1000, 3);
function elsey_woocommerce_cart_item_product ( $product, $cart_item = '', $cart_item_key = '')
{
	if (is_a($product, 'WC_Product_Variation'))
	{
		$parent = $product->get_parent_data();
		$product->set_name($parent['title']);
		$product->apply_changes();
	}
	return $product;
}

add_filter('woocommerce_display_item_meta', 'elsey_woocommerce_display_item_meta', 1000, 3);
function elsey_woocommerce_display_item_meta ( $html, $item, $args)
{
	$html = '';
	$strings = array();
	foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
		$strings[] = '<div class="mini-product__item mini-product__attribute">
						<span class="label variation-color">' . wp_kses_post( $meta->display_key ) . ':</span>
					 	<span class="value variation-color">'. strip_tags($meta->display_value) .'</span>
					</div>';
	}

	$html = implode( '', $strings );
	return $html;
}

function pr ($data)
{
	echo '<pre>'; print_r($data); echo '</pre>';
}

function elsey_title_area_custom(){
	if (is_shop() && isCustomerInPrivateEvent())
	{
		echo __( 'CAROME EVENT SHOP', 'elsey' );
	} elseif(is_shop()) {
		echo __( 'CAROME SHOP', 'elsey' );
	}
	else {
		echo elsey_title_area();
	}
}
// change BACS fields
//original fields from plugins/woocommerce/includes/gateways/bacs/class-wc-gateway-bacs.php
add_filter('woocommerce_bacs_account_fields','custom_bacs_fields');

function custom_bacs_fields() {
	global $wpdb;
	$account_details = get_option( 'woocommerce_bacs_accounts',
			array(
				array(
					'account_name'   => get_option( 'account_name' ),
					'account_number' => get_option( 'account_number' ),
					'sort_code'      => get_option( 'sort_code' ),
					'bank_name'      => get_option( 'bank_name' ),
					'iban'           => get_option( 'iban' ),
					'bic'            => get_option( 'bic' )
				)
			)

			);
	$account_fields = array(
		'bank_name'      => array(
			'label' => '金融機関',
			'value' => $account_details[0]['bank_name']
		),
		'account_number' => array(
			'label' => __( '口座番号', 'woocommerce' ),
			'value' => $account_details[0]['sort_code'].' '.$account_details[0]['account_number']
		),
		'bic'            => array(
			'label' => __( '支店', 'woocommerce' ),
			'value' => $account_details[0]['iban'].'('.$account_details[0]['bic'].')'
		),
		'account_name'   => array(
			'label' => '口座名義',
			'value' => $account_details[0]['account_name']
		)
	);

	return $account_fields;
}

function show_epsilon_method() {
	$html = '';
	if (class_exists('WC_Epsilon'))
	{
		ob_start();
		$user = wp_get_current_user();
		$removed_epsilon = get_user_meta($user->ID, 'epsilon_cc_removed', true);

		// Stop showing if the method removed
		if ($removed_epsilon) return '';

		$epsilon = new WC_Epsilon();
		$customer_check = $epsilon->user_has_stored_data( $user->ID );
		if ( $customer_check['err_code']!=801 && $customer_check['result']==1) {
			?>
			<!--Start saved credit card-->
			<div class="payment-list row">
				<div class="col-xs-12 col-lg-6 first Visa">
					<div class="box box--rounded">
<!-- 						<span class="ccdetails__section cc-owner">Holder Name</span> -->
						<div class="ccdetails__section cc-info">
							<span class="cc-type"><?php echo $customer_check['card_bland']; ?></span>
							<span class="cc-number">************<?php echo $customer_check['card_number_mask'];?></span>
							<br>
							<div class="cc-exp"><?php esc_html_e( 'Expiry date', 'elsey' ); ?>: <?php echo $customer_check['card_expire']; ?></div>
						</div>
						<a class="cta cta--underlined txt--upper delete" id="remove_cc_card" data-message="<?php echo __('Are you sure you want delete ?', 'elsey') ?>" href="javascript:void(0)"><?php esc_html_e( 'Delete Card', 'elsey' ); ?></a>
					</div>
				</div>
			</div>
			<!--End saved credit card-->
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
	}
	return $html;
}

add_action( 'wp_ajax_nopriv_removed_epsilon_method', 'removed_epsilon_method' );
add_action( 'wp_ajax_removed_epsilon_method', 'removed_epsilon_method' );

function removed_epsilon_method() {
	$response = array('success' => 1);
	$user = wp_get_current_user();
	update_user_meta($user->ID, 'epsilon_cc_removed', 1);
	echo json_encode($response); die;
}

add_filter( 'document_title_parts', 'elsey_document_title_parts', 10000, 1 );
function elsey_document_title_parts( $title ) {
	if (is_wc_endpoint_url( 'order-received' ))
	{
		$title['title'] = 'ご注文完了';
	}
	return $title;
}


add_filter( 'bulk_actions-edit-shop_order', 'elsey_shop_order_bulk_actions', 1000, 1 );
function elsey_shop_order_bulk_actions($actions)
{
	$actions['mark_cancelled'] = __('Mark cancelled', 'elsey');
	return $actions;
}

add_filter('woocommerce_create_account_default_checked', 'elsey_woocommerce_create_account_default_checked', 1000);
function elsey_woocommerce_create_account_default_checked(){
	return true;
}

add_action( 'restrict_manage_posts', 'elsey_restrict_manage_posts', 50  );
// Display dropdown
function elsey_restrict_manage_posts(){
	global $typenow;
	if ( 'shop_order' != $typenow ) {
		return;
	}
	?>
		<style>
			.select2-container {margin-top: 0 !important;}
		</style>
	    <span id="woe_order_exported_wrap">
		    <select name="woe_order_exported" id="woe_order_exported">
		    	<option value=""><?php _e('Choose Order Export', 'elsey'); ?></option>
		    	<option value="0" <?php echo (isset($_REQUEST['woe_order_exported']) && $_REQUEST['woe_order_exported'] !== "") ? 'selected' : '';?>><?php _e('Orders Not Exported', 'elsey'); ?></option>
		    	<option value="<?php echo 1?>" <?php echo (isset($_REQUEST['woe_order_exported']) && $_REQUEST['woe_order_exported'] == 1) ? 'selected' : '';?>><?php _e('Orders Exported', 'elsey'); ?></option>
		    </select>
		</span>
		
		<span id="region_filter_wrap">
		    <select name="region_filter" id="region_filter">
		    	<option value=""><?php _e('Choose Region', 'elsey'); ?></option>
		    	<option value="JP" <?php echo (isset($_REQUEST['region_filter']) && $_REQUEST['region_filter'] == 'JP') ? 'selected' : '';?>><?php _e('Domestic Orders Only', 'elsey'); ?></option>
		    	<option value="notJP" <?php echo (isset($_REQUEST['region_filter']) && $_REQUEST['region_filter'] == 'notJP') ? 'selected' : '';?>><?php _e('International Orders Only', 'elsey'); ?></option>
		    </select>
		</span>
		
	    <span id="event_coupon_wraper">
		    <select name="event_coupon" id="event_coupon">
		    	<option value=""><?php _e('Filter Event Coupon', 'elsey'); ?></option>
		    	<?php 
		    	$coupons_fields = get_coupons_fields();
		    	
		    	foreach ($coupons_fields as $coupon_field)
		    	{
		    		if ($coupon_field['name'] == 'coupons_code')
		    		{
		    			foreach ($coupon_field['sub_fields'] as $coupon)
		    			{
		    			?>
		    				<option value="<?php echo $coupon['name']?>" <?php echo (isset($_REQUEST['event_coupon']) && $_REQUEST['event_coupon'] == $coupon['name']) ? 'selected' : '';?>><?php echo $coupon['label']; ?></option>
		    			<?php	
		    			}
		    			break;
		    		}
		    	}
		    	?>
		    </select>
		</span>
		
		<?php if (false) { ?> 
		<span id="epsilon_type_wrap">
		    <select name="epsilon_type" id="epsilon_type">
		    	<option value=""><?php _e('Epsilon Payment Type', 'elsey'); ?></option>
		    	<option value="creditcard" <?php echo $_REQUEST['epsilon_type'] == "creditcard" ? 'selected' : '';?>><?php _e('Credit Card', 'elsey'); ?></option>
		    	<option value="smartphone" <?php echo $_REQUEST['epsilon_type'] == "smartphone" ? 'selected' : '';?>><?php _e('Smart Phone', 'elsey'); ?></option>
		    </select>
		</span>
		<?php }?>
		
		<span id="kana_name_search_wraper">
		    <input name="kana_name" placeholder="<?php echo __('Search Kana name', 'elsey')?>" value="<?php echo $_REQUEST['kana_name'] ? $_REQUEST['kana_name'] : ''?>"/>
		</span>
	    <?php
}

add_filter( 'parse_query', 'else_parse_query' ); 
function else_parse_query ( $query )
{
	global $pagenow, $wpdb;
	if ( 'shop_order' == $query->query['post_type'] && is_admin() && $pagenow == 'edit.php' && isset($_GET['woe_order_exported']) && $_GET['woe_order_exported'] !== '' )
	{
		$query->query_vars['meta_query'] = isset($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
		$query->query_vars['meta_query'][] = array(
			'key' => 'woe_order_exported',
			'value' => 1,
			'compare' => $_GET['woe_order_exported'] ? '=' : 'NOT EXISTS'
		);
	}
	
	if ( 'shop_order' == $query->query['post_type'] && is_admin() && $pagenow == 'edit.php' && isset($_GET['event_coupon']) && $_GET['event_coupon'] !== '' )
	{
		$query->query_vars['meta_query'] = isset($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
		$query->query_vars['meta_query'][] = array(
			'key' => 'use_event_store_coupon',
			'value' => $_GET['event_coupon'],
			'compare' => $_GET['event_coupon'] ? '=' : 'NOT EXISTS'
		);
	}
	
	if ( 'shop_order' == $query->query['post_type'] && is_admin() && $pagenow == 'edit.php' && isset($_GET['epsilon_type']) && $_GET['epsilon_type'] !== '' )
	{
		$query->query_vars['meta_query'] = isset($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
		$query->query_vars['meta_query'][] = array(
			'key' => 'epsilon_type',
			'value' => $_GET['epsilon_type'],
			'compare' => $_GET['epsilon_type'] ? '=' : 'NOT EXISTS'
		);
	}
	
	if ( 'shop_order' == $query->query['post_type'] && is_admin() && $pagenow == 'edit.php' && $_GET['kana_name'])
	{
		$products = $wpdb->get_results( "
				SELECT post_id 
				FROM $wpdb->postmeta 
				WHERE 
					(meta_key = '_billing_last_name_kana' AND meta_value LIKE '%". $_GET['kana_name'] ."%') OR 
					(meta_key = '_billing_first_name_kana' AND meta_value LIKE '%". $_GET['kana_name'] ."%') 
				GROUP BY post_id
				");
		if (count($products))
		{
			foreach ($products as $product)
			{
				$query->query_vars['post__in'][] = $product->post_id;
			}
		}
	}
	
	if ( 'shop_order' == $query->query['post_type'] && is_admin() && $pagenow == 'edit.php' && isset($_GET['region_filter']) && $_GET['region_filter'] !== '' )
	{
		$query->query_vars['meta_query'] = isset($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
		$query->query_vars['meta_query'][] = array(
			'key' => '_billing_country',
			'value' => 'JP',
			'compare' => $_GET['region_filter'] == 'JP' ? '=' : '!='
		);
	}
	return $query;
}

/*Order Bacs Email*/
add_action('woocommerce_thankyou_bacs', 'elsey_woocommerce_thankyou_bacs', 1);
function elsey_woocommerce_thankyou_bacs() 
{
	echo '<div class="before_bacs">' . __('ご注文の確定はご入金確認後となり、<strong>ご注文日から3営業日以内にご入金が確認できない場合はキャンセルとなります</strong>のであらかじめご了承ください。', 'elsey') . '</div>';
}

/*Order Export Function*/
add_action( 'woe_order_exported', 'elsey_woe_order_exported', 1000, 2 );
function elsey_woe_order_exported($order_id){
	if (class_exists('WC_Order_Export_Manage'))
	{
		$order = new WC_Order( $order_id );
		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );
		if ( $settings[ 'mark_exported_orders' ] && $order->status == 'processing') {
			// Set new status
			$order->update_status('wc-process-deliver', 'mark exported for processing order'); 
		}
	}
}

/*Insta Shop*/
add_action( 'wp_loaded', 'change_orders_detail_name' );
function change_orders_detail_name(){
	if (isset($_GET['update_insta_shop']) && $_GET['update_insta_shop'])
	{
		$insta_related_products = get_option('insta_related_products');
		$insta_related_products = $insta_related_products ? $insta_related_products : array();
		
		$insta_related_products_new = array();
		foreach($insta_related_products as $insta_id => $insta_related_product)
		{
			if ($insta_related_product && count($insta_related_product))
			{
				foreach ($insta_related_product as $product_id_index => $product_id)
				{
					if (in_array($product_id_index, array('hide', 'products')))
					{
						break;
					}
					if (is_numeric($product_id_index))
					{
						$insta_related_products_new[$insta_id]['products'][$product_id_index] = $product_id;
					}
					else {
						$insta_related_products_new[$insta_id][$product_id_index] = $product_id;
					}
				}
			}
		}
		if (count($insta_related_products_new))
		{
			update_option('insta_related_products', $insta_related_products_new);
		}
		die('done');
	}
	
	if (!isset($_GET['change_old_order_name']))
	{
		return;
	}

	$post_status = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');
	$order_statuses = array_keys(wc_get_order_statuses());
	foreach ($post_status as $post_status)
	{
		$order_statuses[] = $post_status;
	}
	$orders = get_posts(array(
		'post_type'   => 'shop_order',
		'posts_per_page' => '-1',
		'post_status' => $order_statuses,
		'posts_per_page' => 500, 'offset' => (int)$_GET['change_old_order_name']
	));
	
	foreach ($orders as $order)
	{
		if ($order->ID > 6075) continue;
		
		$order = new WC_Order($order->ID);
		$order_items = $order->get_items();
		if (count($order_items))
		{
			foreach ($order_items as $order_item)
			{
				$order_name_orig = $order_item->get_name();
				$product_id = $order_item->get_product_id();
				$product = get_product($product_id);
				
				$english_name = get_post_meta($product_id, '_custom_product_text_field', true);
				$japanese_name = $product->name;

				$order_names = explode(' - ', $order_name_orig);
				$order_name_old = '';
				$order_name_attr = '';
				foreach ($order_names as $order_name_loop_index => $order_name_ex)
				{
					if ($order_name_loop_index < count($order_names) - 1)
					{
						$order_name_old .= $order_name_ex;
					}
					else {
						$order_name_attr .= $order_name_ex;
					}
				}
					
				if ($order_names[0] == $order_names[1])
				{
					$new_name = $japanese_name;
				}
				else {
					$new_name = $japanese_name . ' - ' . $order_name_attr;
				}
				$order_item->set_name($new_name);
				$order_item->save();
			}
		}
	}
	die('done');
}

add_action( 'woocommerce_email_before_order_table', 'add_order_email_instructions', 10, 2 );
 
function add_order_email_instructions( $order, $sent_to_admin ) {
  
  if ( ! $sent_to_admin ) {
 
    if ( ('bacs' == $order->payment_method) && ($order->status == 'processing') ) {
      // cash on delivery method
      echo '<p>お客様のご注文のご入金を確認いたしましたので、お知らせ致します。<br/>お忙しいなか、お手続きをありがとうございました。</p><p>発送手続き完了後、「商品発送のご案内」メールを再度配信いたしますので、<br/>発送完了までもうしばらくお待ちください。</p><p>お客様のご注文内容は以下となりますので、ご確認ください。</p>';
    } elseif (('epsilon' == $order->payment_method) && ($order->status == 'processing')) {
      echo '<p>お客様のご注文を下記の内容で承りましたので、ご確認ください。</p><p>発送手続き完了後、「商品発送のご案内」メールを再度配信いたしますので、<br/>発送完了までもうしばらくお待ちください。</p>';
    } elseif (('epsilon_pro_sc' == $order->payment_method) && ($order->status == 'processing')) {
      echo '<p>お客様のご注文を下記の内容で承りましたので、ご確認ください。</p><p>発送手続き完了後、「商品発送のご案内」メールを再度配信いたしますので、<br/>発送完了までもうしばらくお待ちください。</p>';
    } else {
      // other methods (ie credit card)
      echo '';
    }
    
    $products = $order->get_items();
    
    $pre_order_notice = '';
    foreach ( $products as $product ) {
    	if ($pre_order_notice)
    	{
    		break;
    	}
    	$pre_order_notice = trim(get_field( 'notice_pre-order', $product->get_product_id()));
    }
    if (in_array($order->payment_method, array('epsilon', 'epsilon_pro_sc')) && in_array($order->status, array('on-hold', 'processing')) && $pre_order_notice)
    {
    	echo '<p>お届け日が異なる商品がこの注文にあるため、それぞれの商品は別日に発送されます。</p>';
    }
  }
  
      
    //Process Tracking for Necospos Shipping
    if($order->status == 'completed'&&$order->get_shipping_method()=='ネコポス'){
        if(get_field('tracking_url',$order->get_id())!=''){
            echo '<p>'.__('Tracking Url', 'elsey').':</p>';
            echo '<p><a target="_blank" href="http://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id='.get_field('tracking_url',$order->get_id()).'">http://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id='.get_field('tracking_url',$order->get_id()).'</a></p>';
        }
    }
    //end
  
  $order_subtotal = $order->get_subtotal();
  echo '<div style="margin-top: 20px; margin-bottom: 30px;" class="gift_message">' . do_shortcode('[wc_free_gift_message amount="'. $order_subtotal .'"]') . '</div>';
}

add_action('woocommerce_review_order_before_submit','wpdreamer_woocommerce_proceed_to_checkout',9999);

add_action('woocommerce_proceed_to_checkout','wpdreamer_woocommerce_proceed_to_checkout');
function wpdreamer_woocommerce_proceed_to_checkout(){
	$show_text = array();
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$pre_order_notice = get_field( 'notice_pre-order',$cart_item['product_id']);
			if(!empty($pre_order_notice))$show_text['has_text']=true;
			if(empty($pre_order_notice))$show_text['has_no_text']=true;
	}

	if(count($show_text) == 2 && count(array_unique($show_text)) === 1)
		echo '<p class="prenotice-message">お届け日が異なる商品がこの注文にあるため、それぞれの商品は別日に発送されます。</p>';
}

add_filter('woocommerce_thankyou_order_received_text','wpdreamer_woocommerce_thankyou_order_received_text',10,2);
function wpdreamer_woocommerce_thankyou_order_received_text($text, $order){
	if (!$order) return $text;
	
	$items = $order->get_items();
	$show_text = array();

	foreach ( $items as $item ) {
		$pre_order_notice = get_field( 'notice_pre-order',$item->get_product_id());
		if(!empty($pre_order_notice))$show_text['has_text']=true;
		if(empty($pre_order_notice))$show_text['has_no_text']=true;
	}
	if(count($show_text) == 2 && count(array_unique($show_text)) === 1)
		$text .= '<p class="prenotice-message">お届け日が異なる商品がこの注文にあるため、それぞれの商品は別日に発送されます。</p>';


		return $text;
}

/**
 * Add a widget to the dashboard.
 */
function product_report_dashboard_widget() {
	wp_add_dashboard_widget(
			'product_report_dashboard_widget',
			__('Product Quantity Report By Time', 'elsey'),
			'grand_product_report_dashboard_widget_function'
			);
	
	wp_add_dashboard_widget(
			'user_age_report_dashboard_widget',
			__('Member Age Report', 'elsey'),
			'elsey_user_age_report_dashboard_widget_function'
			);
	
	wp_add_dashboard_widget(
			'pre_order_report_dashboard_widget',
			__('Product Preorder Report', 'elsey'),
			'elsey_pre_order_report_dashboard_widget_function'
			);
	
	wp_add_dashboard_widget(
			'pre_variation_order_report_dashboard_widget',
			__('Variations Preorder Report', 'elsey'),
			'elsey_pre_variation_order_report_dashboard_widget_function'
			);
}
add_action( 'wp_dashboard_setup', 'product_report_dashboard_widget' );

function elsey_user_age_report_dashboard_widget_function() {
	global $wpdb;
	$ageRanges = array('1-13', '14-19', '20-24', '25-29', '30-34', '35-39', '40-50', '51-60', '61-70', '71-100');
	$aRangesCount = array();
	foreach ($ageRanges as $range)
	{
		$ageRange = explode('-', $range);
		$sql = "SELECT COUNT(*) as count FROM {$wpdb->usermeta} 
			WHERE 
				meta_key = 'birth_year' 
				AND meta_value <= YEAR(CURDATE()) - {$ageRange[0]} 
				AND meta_value >= YEAR(CURDATE()) - {$ageRange[1]}
				";
		$result = $wpdb->get_row($sql);
		$aRangesCount[$range] = $result ? $result->count : 0;
	}
	$sqlAllAge = "SELECT COUNT(*) as count FROM {$wpdb->usermeta} WHERE meta_key = 'birth_year'";
	$result = $wpdb->get_row($sqlAllAge);
	$allCount = $result ? $result->count : 0;
	arsort($aRangesCount);
	
	foreach ($aRangesCount as $range => $rangeCount)
	{
		if (!$rangeCount) continue;
		
		$percent = round(($rangeCount / $allCount) * 100);
		echo '<div class="age-record">';
		echo '<span class="range">';
		echo sprintf(__('%1$s : %2$s members, %3$s%% in total', 'elsey'), $range, $rangeCount, $percent);
		echo '</span>';
		echo '</div>';
		
	}
}
/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function grand_product_report_dashboard_widget_function() {
	require_once get_stylesheet_directory() . '/classes/class-product-report-list-table.php';
	$product_list = new Product_Quantity_Report_List();
	$product_list->prepare_items();
	return $product_list->display();
}

function elsey_pre_order_report_dashboard_widget_function()
{
	require_once get_stylesheet_directory() . '/classes/class-product-pre-order-report-list-table.php';
	$product_list = new Product_Pre_Order_Report_List();
	$product_list->prepare_items();
	return $product_list->display();
}

function elsey_pre_variation_order_report_dashboard_widget_function()
{
	require_once get_stylesheet_directory() . '/classes/class-product-pre-order-report-list-table.php';
	$product_list = new Product_Pre_Order_Report_List();
	$product_list->product_type = '_variation_id';
	$product_list->prepare_items();
	return $product_list->display();
}

add_action( 'wp_ajax_load_table_list_widget_dashboard', 'elsey_wp_ajax_load_table_list_widget_dashboard', 1, 2 );
add_action( 'wp_ajax_nopriv_load_table_list_widget_dashboard', 'elsey_wp_ajax_load_table_list_widget_dashboard', 1, 2 );
function elsey_wp_ajax_load_table_list_widget_dashboard()
{
	$link_url = $_REQUEST['url'];
	$aWidget = [
		'#product_report_dashboard_widget' => 'grand_product_report_dashboard_widget_function',
		'#user_age_report_dashboard_widget' => 'elsey_user_age_report_dashboard_widget_function',
		'#pre_order_report_dashboard_widget' => 'elsey_pre_order_report_dashboard_widget_function',
		'#pre_variation_order_report_dashboard_widget' => 'elsey_pre_variation_order_report_dashboard_widget_function',
	];
	$url_parts = parse_url($link_url);
	$url_query = array();
	parse_str($url_parts['query'], $url_query);
	
	$widget_function = $aWidget[$_REQUEST['wg']];
	$url_query['paged'] = isset($url_query['paged']) ? $url_query['paged'] : 1;
	
	if (isset($url_query['paged']))
	{
		foreach ($url_query as $param_key => $param_value)
		{
			$_GET[$param_key] = $_REQUEST[$param_key] = $param_value;
		}
		$table_list = $widget_function();
		echo $table_list;die;
	}
}

add_filter( 'woocommerce_payment_gateways', 'elsey_woocommerce_payment_gateways', 1000, 1 );
function elsey_woocommerce_payment_gateways($load_gateways) 
{
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	{
		return $load_gateways;
	}
	
	global $wpdb;
	$current_user = wp_get_current_user();
	
	//show WC_Gateway_BACS or not
	$sql = "SELECT g.group_id, g.name
	FROM {$wpdb->prefix}groups_user_group ug 
	INNER JOIN {$wpdb->prefix}groups_group g ON ug.group_id = g.group_id
	WHERE ug.user_id=" . (int)$current_user->ID . ' AND g.group_id = 2';
	
	$group = $wpdb->get_row($sql);
	if (!$group)
	{
		if (($bacs_index = array_search('WC_Gateway_BACS', $load_gateways)) !== false)
		{
			unset($load_gateways[$bacs_index]);
		}
	}
	
	//show WC_Gateway_Offline or not
	$sql = "SELECT g.group_id, g.name
	FROM {$wpdb->prefix}groups_user_group ug
	INNER JOIN {$wpdb->prefix}groups_group g ON ug.group_id = g.group_id
	WHERE ug.user_id=" . (int)$current_user->ID . ' AND g.group_id = 3';
	
	$group = $wpdb->get_row($sql);
	// Get event time
	$today = current_time('mysql');
	$event_start_end = get_event_time_start_end();
	$is_event_time = !empty($event_start_end) && $today >= $event_start_end['start'] && $today <= $event_start_end['end'];
	
	if (!$group || !$is_event_time || !isCustomerInPrivateEvent())
	{
		if (($bacs_index = array_search('WC_Gateway_Offline', $load_gateways)) !== false)
		{
			unset($load_gateways[$bacs_index]);
		}
	}
	
	$load_gateways = array_values($load_gateways);
	return $load_gateways;
}

add_action( 'wp_loaded', 'restoreUserWailist' );
function restoreUserWailist()
{
	if (isset($_GET['restore_waitlist']) && $_GET['restore_waitlist'])
	{
		$aRestore = array(
			'4696' => 'a:42:{i:507;i:1518684954;i:520;i:1518686760;i:375;i:1518687460;i:491;i:1518693988;i:126;i:1518705446;i:603;i:1518708337;i:607;i:1518723825;i:528;i:1518742447;i:670;i:1518791770;i:215;i:1518827079;i:689;i:1518841801;i:94;i:1518871036;i:298;i:1518917790;i:730;i:1518946140;i:735;i:1518953435;i:279;i:1518961236;i:198;i:1518963751;i:751;i:1518964438;i:758;i:1518969203;i:7;i:1518969753;i:44;i:1518993623;i:775;i:1519007182;i:34;i:1519025065;i:788;i:1519027564;i:194;i:1519033084;i:943;i:1519033598;i:513;i:1519034252;i:539;i:1519034874;i:1062;i:1519041612;i:241;i:1519044929;i:594;i:1519059964;i:1205;i:1519110630;i:1234;i:1519137039;i:969;i:1519139195;i:448;i:1519171648;i:1247;i:1519191414;i:1250;i:1519198254;i:740;i:1519202869;i:1254;i:1519205406;i:1107;i:1519211304;i:317;i:1519211541;i:508;i:1519263128;}',
			'4697' => 'a:34:{i:42;i:1518514226;i:93;i:1518515231;i:89;i:1518515718;i:72;i:1518515733;i:26;i:1518519443;i:172;i:1518519817;i:107;i:1518520076;i:221;i:1518521280;i:207;i:1518521323;i:229;i:1518522152;i:242;i:1518524134;i:44;i:1518524870;i:260;i:1518525742;i:298;i:1518530666;i:302;i:1518531168;i:317;i:1518534396;i:319;i:1518534897;i:326;i:1518536487;i:327;i:1518537289;i:338;i:1518540091;i:346;i:1518549003;i:353;i:1518559983;i:392;i:1518586064;i:98;i:1518594378;i:447;i:1518643721;i:565;i:1518699181;i:581;i:1518702552;i:588;i:1518704581;i:604;i:1518713366;i:614;i:1518766289;i:636;i:1518770391;i:638;i:1518772455;i:686;i:1518831208;i:696;i:1518854331;}',
			'4699' => 'a:1:{i:658;i:1518784987;}',
			'4700' => 'a:7:{i:363;i:1518716156;i:528;i:1518742459;i:584;i:1518778902;i:647;i:1518782796;i:332;i:1518792633;i:706;i:1518867954;i:518;i:1518878996;}',
			'4701' => 'a:16:{i:535;i:1518696455;i:376;i:1518698032;i:383;i:1518699528;i:578;i:1518702332;i:340;i:1518704172;i:592;i:1518705375;i:599;i:1518707137;i:65;i:1518712189;i:338;i:1518716891;i:609;i:1518733829;i:622;i:1518749158;i:632;i:1518778769;i:648;i:1518783505;i:679;i:1518795561;i:685;i:1518818467;i:696;i:1518854342;}',
		);
		foreach ($aRestore as $product_id => $restore)
		{
			$product = get_product($product_id);
			$waitListClass = new Pie_WCWL_Waitlist($product);
			$waitListUser = unserialize($restore);
			foreach ($waitListUser as $user_id => $datetime)
			{
				$user = get_user_by('id', $user_id);
				$waitListClass->register_user( $user );
			}
		}
	}
}

// remove user waitlist after order placed
add_filter( 'woocommerce_payment_successful_result', 'elsey_woocommerce_payment_successful_result', 1000, 2 );
function elsey_woocommerce_payment_successful_result($result, $order_id)
{
	$order = new WC_Order($order_id);
	$order_items = $order->get_items();
	$current_user = wp_get_current_user();
	if (count($order_items))
	{
		foreach ($order_items as $order_item)
		{
			$product_id = $order_item->get_product_id();
			$variation_id = $order_item->get_variation_id();
			$product = get_product($variation_id ? $variation_id : $product_id);
			$waitListClass = new Pie_WCWL_Waitlist($product);
			// remove current waitist user this product;
			$waitListClass->unregister_user( $current_user );
		}
	}
	
	// Modify epilon sc redirect url
	$payment_method = get_post_meta( $order_id, '_payment_method', true );
	if ($payment_method == 'epsilon_pro_sc' && $result['result'] == 'success')
	{
		$result['redirect'] = str_replace('order/method_select3.cgi', 'carrier/carrier3.cgi', $result['redirect']);
		$result['redirect'] .= '&payment_code=15';
	}
	
	return $result;
}

add_action( 'woocommerce_order_status_on-hold', 'elsey_woocommerce_order_status_on_hold', 1000, 4);
function elsey_woocommerce_order_status_on_hold($order_id, $order){
	$is_pre_order = get_post_meta( $order_id, '_wc_pre_orders_is_pre_order', true );
	if ($is_pre_order)
	{
		remove_action('woocommerce_order_status_pending_to_on-hold', array('WC_Emails', 'send_transactional_email'), 10);
	}
	return $order_id;
}
/*Return Policy Setting*/
add_filter( 'wpcf7_mail_components', 'elsey_wpcf7_mail_components', 100, 3);
function elsey_wpcf7_mail_components ($components, $contactForm, $mailer)
{
	if ($_POST['contact-type'] == '不良品の返品・交換について' && strpos($components['recipient'], '@carome.net') !== false)
	{
		$components['recipient'] = CONTACT_EMAIL_ADMIN_WITH_FILE;
	}
	return $components;
}

add_filter( 'wpcf7_validate_file', 'else_wpcf7_file_validation_filter', 20, 2 );
function else_wpcf7_file_validation_filter ($result, $tag)
{
	$name = $tag->name;
	$file = isset( $_FILES[$name] ) ? $_FILES[$name] : null;

	if ( empty( $file['tmp_name'] ) && $_POST['contact-type'] == '不良品の返品・交換について' ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	}
	return $result;
}

add_action( 'wp_ajax_woocommerce_save_variations', 'elsey_wp_ajax_woocommerce_save_variations', 1, 2 );
add_action( 'wp_ajax_nopriv_woocommerce_save_variations', 'elsey_wp_ajax_woocommerce_save_variations', 1, 2 );
function elsey_wp_ajax_woocommerce_save_variations()
{
	global $before_save_variations;
	if ( isset( $_POST['variable_post_id'] ) ) {
		$max_loop   = max( array_keys( $_POST['variable_post_id'] ) );
		for ( $i = 0; $i <= $max_loop; $i ++ ) {
			if ( ! isset( $_POST['variable_post_id'][ $i ] ) ) {
				continue;
			}
			$variation_id = absint( $_POST['variable_post_id'][ $i ] );
			$variation    = new WC_Product_Variation( $variation_id );
			$before_save_variations[$variation_id] = $variation;
		}
	}
}

add_action( 'woocommerce_save_product_variation', 'elsey_woocommerce_save_product_variation', 1000, 2 );
function elsey_woocommerce_save_product_variation( $variation_id, $variation_index, $product_id = 0 ) {
	global $before_save_variations;
	$product_id = absint( $product_id ? $product_id : $_POST['product_id'] );
	$product_stock_log = get_post_meta($product_id, 'product_stock_log', true);
	$product_stock_log = $product_stock_log ? $product_stock_log : array();
	$current_user = wp_get_current_user();
	
	$current_time = date('Y-m-d H:i:s', current_time( 'timestamp', 0 ));
	$current_time_mix = $current_time . '__' . $variation_id;
	
	if ($before_save_variations && count($before_save_variations))
	{
		$old_variation = $before_save_variations[$variation_id];
		$new_variation    = wc_get_product($variation_id);
		
		if ($old_variation->stock_quantity != $new_variation->stock_quantity || $old_variation->stock_status != $new_variation->stock_status)
		{
			$product_stock_log[$current_time_mix]['old_stock'] = (int)$old_variation->stock_quantity;
			$product_stock_log[$current_time_mix]['new_stock'] = (int)$new_variation->stock_quantity;
			
			$product_stock_log[$current_time_mix]['old_stock_status'] = $old_variation->stock_status;
			$product_stock_log[$current_time_mix]['new_stock_status'] = $new_variation->stock_status;
			
			$product_stock_log[$current_time_mix]['user_id'] = $current_user->ID;
			$product_stock_log[$current_time_mix]['create_time'] = $current_time;
			$product_stock_log[$current_time_mix]['variation_id'] = $variation_id;
			
			update_post_meta($product_id, 'product_stock_log', $product_stock_log);
		}
	}
	
	// Save stock schedule
	if ($_POST['variable_stock_schedule'])
	{
		$stock_schedule = get_option('restock_schedule');
		$stock_schedule[$variation_id]['schedule'] = trim($_POST['variable_stock_schedule'][$variation_index]);
		$stock_schedule[$variation_id]['quantity'] = trim($_POST['restock_quantity_schedule'][$variation_index]);
		update_option('restock_schedule', $stock_schedule);
	}
	
	return $variation_id;
}

add_action( 'admin_action_editpost', 'elsey_admin_action_editpost', 1000);
function elsey_admin_action_editpost()
{
	if (isset($_POST) && !empty($_POST) && $_POST['post_type'] == 'product' && $_POST['product-type'] == 'simple')
	{
		global $before_save_variations;
		$product_id = $_POST['post_ID'];
		$before_save_variations[$product_id] = wc_get_product($product_id);
	}
}

add_action( 'wp_insert_post', 'elsey_wp_insert_post', 999999, 3);
function elsey_wp_insert_post($post_ID, $post, $update )
{
	if (isset($_POST) && !empty($_POST) && $_POST['post_type'] == 'product' && $_POST['product-type'] == 'simple')
	{
		elsey_woocommerce_save_product_variation( $post_ID, 0, $post_ID );
	}
}

add_action( 'add_meta_boxes', 'else_add_meta_boxes_product_stock_record' );
function else_add_meta_boxes_product_stock_record()
{
	add_meta_box( 'else_product_stock_record', __('Stock records','elsey'), 'else_show_product_stock_record', 'product', 'side', 'core' );
}
/*Stock Records*/
function else_show_product_stock_record()
{
	global $post;
	
	$product_stock_log = get_post_meta($post->ID, 'product_stock_log', true);
	$product_stock_log = $product_stock_log ? $product_stock_log : array();
	krsort($product_stock_log);
	echo '<ul class="order_notes">';
	foreach ($product_stock_log as $stock_log)
	{
		$user = get_user_by('id', $stock_log['user_id']);
		$product = wc_get_product($stock_log['variation_id']);
		if (!$product) continue;
		
		$product_name = $product->get_name();
		$stock_change = $stock_log['new_stock'] - $stock_log['old_stock'];
		$stock_change_texts = array();
		$statuses = array('instock' => __('In Stock', 'elsey'), 'outofstock' => __('Out Of Stock', 'elsey'));
		if ($stock_change != 0)
		{
			$stock_change_texts[] = $stock_change > 0 ? sprintf(__('Stock increase from %1$s to %2$s', 'elsey'), $stock_log['old_stock'], $stock_log['new_stock']) : sprintf(__('Stock decrease from %1s to %2s', 'elsey'), $stock_log['old_stock'], $stock_log['new_stock']);
		}
		
		if ($stock_log['old_stock_status'] != $stock_log['new_stock_status'])
		{
			$stock_change_texts[] = sprintf(__('Stock status change from %1$s to %2$s', 'elsey'), $statuses[$stock_log['old_stock_status']], $statuses[$stock_log['new_stock_status']]);
		}
		
		echo '<li class="note system-note">
        	<div class="note_content"><p>' .  
			sprintf(__('Product = %1$s, %2$s, Modified by : %3$s', 'elsey'), 
				$stock_log['variation_id'], 
				implode(', ', $stock_change_texts),
				'ID' . $user->ID . '-' . $user->display_name
			) . '
        	</p></div>
        	<p class="meta">
				<abbr class="exact-date" title="'. $stock_log['create_time'] .'">'. date('Y年m月d日 h:i A', strtotime($stock_log['create_time'])) .'に追加</abbr>
			</p>	
        </li>';
	}
	echo '</ul>';
}

add_filter('posts_clauses', 'elsey_order_by_stock_status', 200, 1);
function elsey_order_by_stock_status($posts_clauses)
{
	if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag()) && !is_admin())
	{
		global $wpdb;
		$posts_clauses['fields'] = $posts_clauses['fields'] . ", IF(st.meta_value > 0, 1, 0) as stock ";
		$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta st ON ($wpdb->posts.ID = st.post_id) ";
		$posts_clauses['orderby'] = "stock DESC, st.meta_value ASC, menu_order, post_title ASC, " . $posts_clauses['orderby'];
		$posts_clauses['where'] = " AND st.meta_key = '_stock_status' AND st.meta_value <> '' " . $posts_clauses['where'];
	}
	return $posts_clauses;
}

add_action( 'woocommerce_process_product_meta_simple', 'else_woocommerce_process_product_meta_simple', 100, 1);
function else_woocommerce_process_product_meta_simple($post_id)
{
	if ($_POST['restock_schedule'])
	{
		$stock_schedule = get_option('restock_schedule');
		$stock_schedule[$post_id]['schedule'] = trim($_POST['restock_schedule']);
		$stock_schedule[$post_id]['quantity'] = trim($_POST['restock_quantity_schedule']);
		update_option('restock_schedule', $stock_schedule);
	}
	return $post_id;
}

add_action('woocommerce_product_options_stock_fields', 'else_woocommerce_product_options_stock_fields', 1000);
function else_woocommerce_product_options_stock_fields()
{
	global $product_object;
	$stock_schedule = get_option('restock_schedule');
	$product_id = $product_object->get_id();
	$stock_schedule_value = $stock_schedule && isset($stock_schedule[$product_id]) ? $stock_schedule[$product_id] : array('schedule' => '', 'quantity' => '');
	woocommerce_wp_text_input( array(
		'id'                => 'restock_schedule',
		'value'             => $stock_schedule_value['schedule'],
		'label'             => __( 'ReStock Schedule', 'elsey' ),
		'desc_tip'          => true,
		'description'       => __( 'ReStock schedule', 'elsey' ),
		'type'              => 'text',
		'placeholder'       => 'YYYY-MM-DD HH:MM',
	) );
	
	woocommerce_wp_text_input( array(
		'id'                => 'restock_quantity_schedule',
		'value'             => $stock_schedule_value['quantity'],
		'label'             => __( 'ReStock Schedule Quantity', 'elsey' ),
		'desc_tip'          => true,
		'description'       => __( 'ReStock quantity with schedule', 'elsey' ),
		'type'              => 'text',
		'placeholder'       => '0',
	) );
	
	echo '<script type="text/javascript">jQuery("#restock_schedule").datetimepicker({minDate: new Date()});</script>';
}

add_action( 'woocommerce_variation_options_inventory', 'else_woocommerce_variation_options_inventory', 100, 3);
function else_woocommerce_variation_options_inventory($loop, $variation_data, $variation)
{
	$stock_schedule = get_option('restock_schedule');
	$product_id = $variation->ID;
	$stock_schedule_value = $stock_schedule && isset($stock_schedule[$product_id]) ? $stock_schedule[$product_id] : array('schedule' => '', 'quantity' => '');
	
	woocommerce_wp_text_input( array(
	'id'                => "variable_stock_schedule{$loop}",
	'name'              => "variable_stock_schedule[{$loop}]",
	'value'             => $stock_schedule_value['schedule'],
	'label'             => __( 'ReStock Schedule', 'elsey' ),
	'desc_tip'          => true,
	'description'       => __( 'ReStock schedule', 'elsey' ),
	'type'              => 'text',
	'placeholder'       => 'YYYY-MM-DD HH:MM',
	'class'       		=> 'schedule_date_picker',
	'wrapper_class'     => 'form-row form-row-first',
	) );
	
	woocommerce_wp_text_input( array(
		'id'                => "restock_quantity_schedule{$loop}",
		'name'                => "restock_quantity_schedule[{$loop}]",
		'value'             => $stock_schedule_value['quantity'],
		'label'             => __( 'ReStock Schedule Quantity', 'elsey' ),
		'desc_tip'          => true,
		'description'       => __( 'ReStock quantity with schedule', 'elsey' ),
		'type'              => 'text',
		'placeholder'       => '0',
		'wrapper_class'     => 'form-row form-row-last',
	) );
	
	echo '<script type="text/javascript">jQuery(".schedule_date_picker").datetimepicker({minDate: new Date()});</script>';
}
/*schedule stock*/
add_action( 'wp_loaded', 'elsey_process_stock_schedule' );
function elsey_process_stock_schedule() {
	if (!isset($_GET['process_stock_schedule']) || !$_GET['process_stock_schedule'])
		return ;
	
	$current_time = date('Y-m-d H:i', current_time( 'timestamp', 0 ));

	$stock_schedules = get_option('restock_schedule');
	$new_stock_schedules = array();
	
	if ($stock_schedules && !empty($stock_schedules))
	{
		foreach($stock_schedules as $product_id => $stock_schedule)
		{
			// Don't process if missing data schedule date
			if (!isset($stock_schedule['schedule']) && !isset($stock_schedule['quantity'])) continue;
			if (!$stock_schedule['schedule']) continue;
			
			$schedule = $stock_schedule['schedule'];
			$quantity = $stock_schedule['quantity'];
			$schedule_date = str_replace(array('年', '月', '日'), array('-', '-', ''), $schedule);
			$schedule_date = date('Y-m-d H:i', strtotime($schedule_date));
			
			if ($current_time >= $schedule_date)
			{				
				// Restock when ontime
				wc_update_product_stock($product_id, $quantity);
			}
			elseif ($current_time < $schedule_date)
			{
				// Store the future schedules only
				$new_stock_schedules[$product_id]['schedule'] = $schedule;
				$new_stock_schedules[$product_id]['quantity'] = $quantity;
			}
		}
	}
	// Store new schedule
	update_option('restock_schedule', $new_stock_schedules);
	if (isset($_REQUEST['return']))
	{
		pr($new_stock_schedules);
	}
	die('done');
}

add_action( 'wp_loaded', 'elsey_schedule_cancelled_not_paid' );
function elsey_schedule_cancelled_not_paid() {
	if (isset($_GET['cancelled_not_paid']) && $_GET['cancelled_not_paid'])
	{
		$held_duration = 5; 

	    if ( $held_duration < 1 || 'yes' !== get_option( 'woocommerce_manage_stock' ) ) { 
	        return; 
	    } 
	
	global $wpdb;
	$date = strtotime( '-' . absint( $held_duration ) . ' MINUTES', current_time( 'timestamp' ) );
		    $unpaid_orders = $wpdb->get_col(
		      $wpdb->prepare(
		        // @codingStandardsIgnoreStart
		        "SELECT posts.ID
						FROM {$wpdb->posts} AS posts
						WHERE   posts.post_type   IN ('" . implode( "','", wc_get_order_types() ) . "')
						AND     posts.post_status IN ('wc-pending', 'wc-on-hold')
						AND     posts.post_modified < %s",
		        // @codingStandardsIgnoreEnd
		        date( 'Y-m-d H:i:s', absint( $date ) )
		      )
		    );

		$time_now = current_time('timestamp');
	    if ( $unpaid_orders ) { 
	        foreach ( $unpaid_orders as $unpaid_order ) { 
	            $order = wc_get_order( $unpaid_order ); 
	            if (strpos($order->get_payment_method(), 'epsilon_pro_cs') !== false)
	            {
	            	// Already cancel in function_epsilon.php file
	            	continue;
	            	
	            	// If passed 3 days -> cancel
	            	if (($time_now - (60*60*24*4)) >= strtotime($order->get_date_created()->format('Y-m-d')))
	            	{
	            		$order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'woocommerce' ) );
	            	}
	            }
	            else {
	            	$order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'woocommerce' ) );
	            }
	        } 
	    } 
		die('done');
	}
}
/*Restock when order is cancelled*/
add_action( 'wp_loaded', 'elsey_restock_cancelled' );
function elsey_restock_cancelled ()
{
	$enable_restock = get_option('woocommerce_enable_restock');
	if (!class_exists('WC_Auto_Stock_Restore') && $enable_restock == 'yes')
	{
		add_action( 'woocommerce_order_status_processing_to_cancelled', 'elsey_restore_order_stock', 10, 1 );
		add_action( 'woocommerce_order_status_completed_to_cancelled', 'elsey_restore_order_stock', 10, 1 );
		add_action( 'woocommerce_order_status_on-hold_to_cancelled', 'elsey_restore_order_stock', 10, 1 );
		add_action( 'woocommerce_order_status_processing_to_refunded', 'elsey_restore_order_stock', 10, 1 );
		add_action( 'woocommerce_order_status_completed_to_refunded', 'elsey_restore_order_stock', 10, 1 );
		add_action( 'woocommerce_order_status_on-hold_to_refunded', 'elsey_restore_order_stock', 10, 1 );
	}
}

function elsey_restore_order_stock($order_id){

	$order = new WC_Order( $order_id );
	
	if ( ! get_option('woocommerce_manage_stock') == 'yes' && ! sizeof( $order->get_items() ) > 0 ) {
		return;
	}
	
	foreach ( $order->get_items() as $item ) {
	
		if ( $item['product_id'] > 0 ) {
			$_product = $order->get_product_from_item( $item );
	
			if ( $_product && $_product->exists() && $_product->managing_stock() ) {
	
				$old_stock = $_product->stock;
	
				$qty = apply_filters( 'woocommerce_order_item_quantity', $item['qty'], $order, $item );
	
				$new_quantity = $_product->increase_stock( $qty );
	
				do_action( 'woocommerce_auto_stock_restored', $_product, $item );
	
				$order->add_order_note( sprintf( __( 'Item #%s stock incremented from %s to %s.', 'woocommerce' ), $item['product_id'], $old_stock, $new_quantity) );
	
				$order->send_stock_notifications( $_product, $new_quantity, $item['qty'] );
			}
		}
	}
	
}

add_filter( 'woocommerce_get_settings_general', 'else_woocommerce_get_settings_general', 1, 1);
function else_woocommerce_get_settings_general($settings) {
	$settings[] = array( 'title' => __( 'Restock When Order Cancel', 'elsey' ), 'type' => 'title', 'desc' => '', 'id' => 'enable_restock_title' );
	
	$settings[] = array(
		'title'    => __( 'Enable Cancelled Restock', 'elsey' ),
		'desc'    => __( 'Restock when order status is cancelled', 'woocommerce' ),
		'default' => 'no',
		'type'    => 'checkbox',
		'id'       => 'woocommerce_enable_restock',
	);
	$settings[] = array( 'type' => 'sectionend', 'id' => 'enable_restock' );
	return $settings;
}

add_action( 'admin_menu', 'elsey_menu_report_removing', 99 );
function elsey_menu_report_removing() {
	if ( current_user_can( 'manage_woocommerce' ) ) {
		global $submenu, $wp_filter;
		$tag = 'woocommerce_page_wc-reports';
		if (!class_exists('WC_Admin_Reports_New'))
		{
			$adminMenu = new WC_Admin_Menus;
			remove_submenu_page( 'woocommerce', 'wc-reports' );
			remove_action( $tag, array($adminMenu, 'reports_page') );
			
			if ( isset( $wp_filter[ $tag ] ) ) {
				unset( $wp_filter[ $tag ] );
			}
			
			require_once  get_stylesheet_directory() .'/woocommerce/includes/admin/class-wc-admin-reports.php';
			add_submenu_page( 'woocommerce', __( 'Reports', 'woocommerce' ),  __( 'Reports', 'woocommerce' ) , 'view_woocommerce_reports', 'wc-reports', array( 'WC_Admin_Reports_New', 'output_new' ) );
		}
	}
}

add_filter( 'wc_admin_reports_path',  'elsey_wc_admin_reports_path', 100, 3 );
function elsey_wc_admin_reports_path($file_name, $name, $class)
{
	$template_file = get_stylesheet_directory() . '/woocommerce/includes/admin/' . $file_name;
	if (file_exists($template_file))
	{
		return $template_file;
	}
	return $file_name;
}

add_filter( 'woocommerce_admin_reports', 'else_woocommerce_admin_reports', 1000, 1);
function else_woocommerce_admin_reports($reports)
{
	$most_stocked = $reports['stock']['reports']['most_stocked'];
	$reports['stock']['reports']['out_of_stock_new'] = $reports['stock']['reports']['out_of_stock'];
	$reports['stock']['reports']['most_stocked_new'] = $reports['stock']['reports']['most_stocked'];
	unset($reports['stock']['reports']['out_of_stock']);
	unset($reports['stock']['reports']['most_stocked']);
	return $reports;
}

/*Separete Shipping for pre and normal products*/
add_filter('woocommerce_cart_shipping_packages', 'elsey_woocommerce_cart_shipping_packages', 10000, 1);
function elsey_woocommerce_cart_shipping_packages($packages){
	global $elsey_order_type;
	if ($elsey_order_type == 'normal' || $elsey_order_type == 'preorder')
	{
		$aOrderBothType = isBothOrderTypeShipping($packages[0]);
		return array($elsey_order_type == 'normal' ? $aOrderBothType['aNormalProducts'] : $aOrderBothType['aPreOrderProducts']);
	}
	return $packages;
}
function isBothOrderTypeShipping($package)
{
	$aNormalProducts = $aPreOrderProducts = $package;
	$aNormalProducts['contents'] = $aPreOrderProducts['contents'] = array();
	$aNormalProducts['contents_cost'] = $aNormalProducts['cart_subtotal'] = 0;
	$aPreOrderProducts['contents_cost'] = $aPreOrderProducts['cart_subtotal'] = 0;
	
	$hasPreOrder = $hasNormal = false;
	foreach ($package['contents'] as $item_id => $values) {
		$is_pre_order = get_post_meta($values['product_id'], '_wc_pre_orders_enabled', true);
		if( 'yes' !== $is_pre_order )
		{
			$aNormalProducts['contents'][$item_id] = $values;
			$aNormalProducts['contents_cost'] += $values['line_total'];
			$aNormalProducts['cart_subtotal'] += $values['line_total'] + $values['line_tax'];
			$hasNormal = true;
		}
		else {
			$aPreOrderProducts['contents'][$item_id] = $values;
			$aPreOrderProducts['contents_cost'] += $values['line_total'];
			$aPreOrderProducts['cart_subtotal'] += $values['line_total'] + $values['line_tax'];
			$hasPreOrder = true;
		}
	}
	if (!$hasNormal) $aNormalProducts = null;
	if (!$hasPreOrder) $aPreOrderProducts = null;
	
	if (!empty($aPreOrderProducts) && !empty($aNormalProducts))
	{
		return array('aPreOrderProducts' => $aPreOrderProducts, 'aNormalProducts' => $aNormalProducts);
	}
	return null;
}
/*Shipping Calculate Function*/
function elsey_calculate_shipping($order_id) {
	global $wpdb;
	WC()->cart->calculate_shipping();
	$shipping_cost = WC()->cart->get_shipping_total();
	$shipping_total_tax = WC()->cart->get_shipping_tax();
	$shipping_taxes = array('total' => WC()->cart->get_shipping_taxes());
	
	// Save shipping cost
	$wpdb->query( $wpdb->prepare( "
		UPDATE {$wpdb->prefix}woocommerce_order_itemmeta itemmeta 
		INNER JOIN {$wpdb->prefix}woocommerce_order_items item ON itemmeta.order_item_id = item.order_item_id
		SET itemmeta.meta_value = %s 
		WHERE itemmeta.meta_key = %s AND item.order_item_type = %s AND item.order_id = %s", 
		$shipping_cost, 'cost', 'shipping', $order_id ) );
	
	$wpdb->query( $wpdb->prepare( "
		UPDATE {$wpdb->prefix}woocommerce_order_itemmeta itemmeta
		INNER JOIN {$wpdb->prefix}woocommerce_order_items item ON itemmeta.order_item_id = item.order_item_id
		SET itemmeta.meta_value = %s
		WHERE itemmeta.meta_key = %s AND item.order_item_type = %s AND item.order_id = %s",
		$shipping_total_tax, 'total_tax', 'shipping', $order_id ) );
	
	$wpdb->query( $wpdb->prepare( "
		UPDATE {$wpdb->prefix}woocommerce_order_itemmeta itemmeta
		INNER JOIN {$wpdb->prefix}woocommerce_order_items item ON itemmeta.order_item_id = item.order_item_id
		SET itemmeta.meta_value = %s
		WHERE itemmeta.meta_key = %s AND item.order_item_type = %s AND item.order_id = %s",
		serialize($shipping_taxes), 'taxes', 'shipping', $order_id ) );
	
	// Save shipping cost
	$order = wc_get_order( $order_id );
	$order->set_shipping_total($shipping_cost);
	$order->set_shipping_tax($shipping_total_tax);
	$order->save();
	
	// Save shipping cost
	$shipping_items = $order->get_items('shipping');
	foreach ($shipping_items as $shipping_item)
	{
		$shipping_item->set_total($shipping_cost);
		$shipping_item->set_taxes($shipping_taxes);
		$shipping_item->save();
	}
}

// add_filter( 'woocommerce_payment_successful_result', 'elsey_woocommerce_payment_successful_result_duplicate_order', 1000, 2 );
// add_action( 'woocommerce_order_status_changed', 'elsey_woocommerce_order_status_changed', 1000, 4 );

//pre order function
add_action( 'woocommerce_order_status_pending', 'elsey_woocommerce_order_status_changed', 1, 2 );
add_action( 'woocommerce_order_status_on-hold', 'elsey_woocommerce_order_status_changed', 1, 2 );
add_action( 'woocommerce_order_status_processing', 'elsey_woocommerce_order_status_changed', 1, 2 );
function elsey_woocommerce_order_status_changed ($order_id, $order)
{
	if (is_admin())
	{
		return $order_id;
	}
	
	global $wpdb;
	require_once  get_stylesheet_directory() .'/classes/class-clone-order.php';
	
	// Check order items
	$order = new WC_Order($order_id);
	$shipping_total = $order->get_shipping_total();
	
	$items = $order->get_items();
	$aNormalProducts = $aPreOrderProducts = array();
	foreach ($items as $item_key => $order_item)
	{
		$product_id = $order_item->get_product_id();
		$product = get_product($product_id);
		$is_pre_order = get_post_meta($product_id, '_wc_pre_orders_enabled', true);
		if( 'yes' === $is_pre_order )
			$aPreOrderProducts[$item_key] = $product;
		else
			$aNormalProducts[$item_key] = $product;
	}
	
	// If order contain both normal + preorder, so separate the order to 2 orders
	if (!empty($aPreOrderProducts) && !empty($aNormalProducts))
	{
		global $elsey_order_type, $global_shipping_preorder_total;
		
		// Store total price to use for showing message
		update_post_meta($order_id, '_both_order_total_price', $order->get_total());
		update_post_meta($order_id, '_both_order_shipping_total_price', $order->get_shipping_total());
		update_post_meta($order_id, '_both_order_tax_total_price', $order->get_tax_totals());
		
		// Clone order
		$cloneOrder = new CloneOrder();
		$clone_order_id = $cloneOrder->clone_order($order_id);
		
		// Remove pre order from normal order
		foreach ($aPreOrderProducts as $item_key => $product)
		{
			wc_delete_order_item( absint( $item_key ) );
		}
		
		// Calculate price
		$elsey_order_type = 'normal';
		elsey_calculate_shipping($order_id);
		$elsey_order_type = '';
		WC()->cart->calculate_shipping();
		
		wp_cache_delete( 'order-items-' . $order_id, 'orders' );
		$order = wc_get_order( $order_id );
		$order->calculate_shipping();
		$order->calculate_totals( true );
			
		delete_post_meta( $order_id, '_wc_pre_orders_is_pre_order');
		delete_post_meta( $order_id, '_wc_pre_orders_when_charged');
		update_post_meta( $order_id, '_wc_pre_orders_with_normal', $clone_order_id);
		
		if ( is_wp_error( $clone_order_id ) ) {
			// Clone error
		}
		else {
			// Get items from new clone order
			$order_clone = wc_get_order( $clone_order_id );
			$items = $order_clone->get_items();
			$aNormalCloneProducts = array();
			foreach ($items as $item_key => $order_item)
			{
				$product_id = $order_item->get_product_id();
				$product = get_product($product_id);
				$is_pre_order = get_post_meta($product_id, '_wc_pre_orders_enabled', true);
				if( 'yes' !== $is_pre_order )
				{
					$aNormalCloneProducts[$item_key] = $product;
				}
			}
			
			// Remove normal products from pre order  
			foreach ($aNormalCloneProducts as $item_key => $product)
			{
				wc_delete_order_item( absint( $item_key ) );
			}
			
			// Calculate price for pre order
			$elsey_order_type = 'preorder';
			$global_shipping_preorder_total = $shipping_total;
			elsey_calculate_shipping($clone_order_id);
			$elsey_order_type = '';
			WC()->cart->calculate_shipping();
			
			$order_clone = wc_get_order( $clone_order_id );
			wp_cache_delete( 'order-items-' . $clone_order_id, 'orders' );
			$order_clone->calculate_shipping();
			$order_clone->calculate_totals( true );
			
			$preOrderCheckout = new WC_Pre_Orders_Checkout();
			$preOrderCheckout->add_order_meta( $clone_order_id );
			update_post_meta( $clone_order_id, '_wc_pre_orders_with_normal', $order_id);
			$global_shipping_preorder_total = '';
			
			// Send email notification
			$updated = $wpdb->update( $wpdb->postmeta, array('post_status' => 'wc-pending'), array('ID' => $clone_order_id) );
			$order_clone->update_status( 'pre-ordered');
		}
	}
	return $order_id;
}

//related product display number
add_filter ('woocommerce_output_related_products_args', 'elsey_woocommerce_output_related_products_args', 1000, 1);
function elsey_woocommerce_output_related_products_args ($args)
{
	$args['posts_per_page'] = 4;
	return $args;
}

//user profile function
add_action( 'show_user_profile', 'elsey_edit_user_profile', 1, 1 );
add_action( 'edit_user_profile', 'elsey_edit_user_profile', 1, 1 );
function elsey_edit_user_profile($profileuser){
	$aTimes = getArrayYearMonthDay();
	$birth_year = get_user_meta($profileuser->ID, 'birth_year', true);
	$birth_month = get_user_meta($profileuser->ID, 'birth_month', true);
	$birth_day = get_user_meta($profileuser->ID, 'birth_day', true);
?>
	<h2><?php echo __('Customer Birth', 'elsey')?></h2>
	<table class="form-table" id="fieldset-customer-birth">
		<tbody>
			<tr>
				<th>
					<label for="birth_year"><?php echo __('Birth Year', 'elsey')?></label>
				</th>
				<td>
					<select name="birth_year" id="birth_year" class="woocommerce-Select form-control" required>
					<?php foreach ($aTimes['years'] as $timeKey => $timeValue) {?>
						<option value="<?php echo $timeKey?>" <?php echo $birth_year == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
					<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label for="birth_month"><?php echo __('Birth Month', 'elsey')?></label>
				</th>
				<td>
					<select name="birth_month" id="birth_month" class="woocommerce-Select form-control" required>
					<?php foreach ($aTimes['months'] as $timeKey => $timeValue) {?>
						<option value="<?php echo $timeKey?>" <?php echo $birth_month == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
					<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label for="birth_day"><?php echo __('Birth Day', 'elsey')?></label>
				</th>
				<td>
					<select name="birth_day" id="birth_day" class="woocommerce-Select form-control" required>
					<?php foreach ($aTimes['days'] as $timeKey => $timeValue) {?>
						<option value="<?php echo $timeKey?>" <?php echo $birth_day == $timeKey ? 'selected' : ''?> ><?php echo $timeValue?></option>
					<?php }?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

add_action('profile_update', 'elsey_update_extra_profile_fields', 10, 1);
add_action('edit_user_profile_update', 'elsey_update_extra_profile_fields', 10, 1);
function elsey_update_extra_profile_fields($user_id) {
	if ( current_user_can('edit_user',$user_id) && $_POST['birth_year'])
		update_user_meta($user_id, 'birth_year', $_POST['birth_year']);
		update_user_meta($user_id, 'birth_month', $_POST['birth_month']);
		update_user_meta($user_id, 'birth_day', $_POST['birth_day']);
}


//woo order email function
add_action( 'woocommerce_email_before_order_table', 'elsey_woocommerce_email_before_order_table_show_both_order_total', 1, 4 );
function elsey_woocommerce_email_before_order_table_show_both_order_total ($order, $sent_to_admin, $plain_text = '', $email = '')
{
	$second_order_id = get_post_meta($order->get_id(), '_wc_pre_orders_with_normal', true);
	if ($second_order_id)
	{
		$both_total_price = get_post_meta( $order->id, '_both_order_total_price', true );
		
		if ($sent_to_admin)
		{
			$message = sprintf(__('Ordered #%s and #%s has total amount is %s', 'elsey'), $order->get_id(), $second_order_id, wc_price($both_total_price));
		}
		else {
			$message = sprintf(__('Your ordered #%s and #%s has total amount is %s', 'elsey'), $order->get_id(), $second_order_id, wc_price($both_total_price));
		}
		
		echo '<div class="order__summary__row shipping_fee_message">
 				<span class="big-text both_order_total"  style="font-size: 20px;">'. $message . '</span>
 			</div><hr />';
	}
}

//woo report function
add_filter( 'woocommerce_reports_get_order_report_data_args', 'elsey_woocommerce_reports_get_order_report_data_args_add_pre_order', 1000, 1);
function elsey_woocommerce_reports_get_order_report_data_args_add_pre_order($args)
{
	if (isset($args['order_status']) && is_array($args['order_status']) && in_array('processing', $args['order_status']))
	{
		$args['order_status'][] = 'pre-ordered';
	}
	if (isset($args['parent_order_status']) && is_array($args['parent_order_status']) && in_array('processing', $args['parent_order_status']))
	{
		$args['parent_order_status'][] = 'pre-ordered';
	}
	return $args;
}

//add_filter( 'woocommerce_reports_order_statuses', 'elsey_woocommerce_reports_order_statuses_add_pre_order', 1000, 1);
function elsey_woocommerce_reports_order_statuses_add_pre_order($statuses)
{
	if($_REQUEST['page'] == 'wc-reports' && is_array($statuses) && in_array('processing', $statuses) && !in_array('pre-ordered', $statuses))
	{
		$statuses[] = 'pre-ordered';
	}
	
	return $statuses;
}

add_filter( "option_woocommerce_email_footer_text", 'elsey_option_woocommerce_email_footer_text', 10000, 2);
function elsey_option_woocommerce_email_footer_text ($value, $option )
{
	global $wpdb;
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );
	return $row->option_value;
}

//show scheduled product only for admin
add_filter( 'posts_request', 'else_add_schedule_for_admin' );
function else_add_schedule_for_admin( $input ) {
	$user = wp_get_current_user();
	$allowed_roles = array('administrator', 'shop_manager');
	$is_admin_page = is_admin() && !defined( 'DOING_AJAX' );
	// Check if on frontend and main query is modified
	if( array_intersect($allowed_roles, $user->roles ) && !$is_admin_page) {
		if (strpos($input, "post_type = 'product'") !== false)
		{
			$input = str_replace("wp_posts.post_status = 'publish'", "wp_posts.post_status = 'publish' OR wp_posts.post_status = 'future'", $input);
		}
	}
	return $input;
}

add_filter( 'woocommerce_shortcode_products_query', 'else_woocommerce_shortcode_products_query', 10, 3 );
function else_woocommerce_shortcode_products_query ($query_args, $attributes, $type)
{
	$user = wp_get_current_user();
	$allowed_roles = array('administrator', 'shop_manager');
	$is_admin_page = is_admin() && !defined( 'DOING_AJAX' );
	// Check if on frontend and main query is modified
	if( array_intersect($allowed_roles, $user->roles ) && !$is_admin_page) {
		$query_args['post_status'] = array('publish', 'future');
	}
	return $query_args;
}

add_filter( 'woocommerce_ajax_get_customer_details', 'zoa_woocommerce_ajax_get_customer_details', 1000, 3 );
function zoa_woocommerce_ajax_get_customer_details($data, $customer, $user_id)
{
	$data['billing']['first_name_kana'] = get_user_meta($user_id, 'billing_first_name_kana', true);
	$data['billing']['last_name_kana'] = get_user_meta($user_id, 'billing_last_name_kana', true);
	
	$data['shipping']['first_name_kana'] = get_user_meta($user_id, 'shipping_first_name_kana', true);
	$data['shipping']['last_name_kana'] = get_user_meta($user_id, 'shipping_last_name_kana', true);
	
	return $data;
}

add_action( 'user_register', 'elsey_registration_save', 10, 1 );
function elsey_registration_save( $user_id ) {
	$user = wp_get_current_user();
	$allowed_roles = array('administrator', 'shop_manager');
	if( !empty($user->roles) && array_intersect($allowed_roles, $user->roles ) ) {
		update_user_meta($user_id, 'created_by_admin', 1);
	}
}

add_action( 'pre_user_query', 'elsey_search_user_with_id', 10000 );
function elsey_search_user_with_id( $wp ) {
	global $pagenow;
	$search = str_replace('*', '', $wp->query_vars['search']);
	// If it's not the post listing return
	if ( 'users.php' != $pagenow ) return;
	
	// If it's not a search return
	if ( ! isset($wp->query_vars['search']) ) return;
	
	// If it's a search but there's no prefix, return
	if ( '#' != substr($search, 0, 1) ) return;
	
	// Validate the numeric value
	$id = absint(substr($search, 1));
	if ( ! $id ) return; // Return if no ID, absint returns 0 for invalid values
	
	// If we reach here, all criteria is fulfilled, unset search and select by ID instead
	unset($wp->query_vars['search']);
	$wp->query_where = ' WHERE wp_users.ID = ' . $id;
	
}

add_filter('manage_users_columns', 'elsey_add_user_id_column');
function elsey_add_user_id_column($columns) {
	$new_column['user_id'] = 'User ID';
	$columns = insertAtSpecificIndex($columns, $new_column, array_search('username', array_keys($columns)) + 1);
	return $columns;
}

add_action('manage_users_custom_column',  'else_show_user_id_column_content', 10, 3);
function else_show_user_id_column_content($value, $column_name, $user_id) {
	if ( 'user_id' == $column_name )
		return $user_id;
}

add_filter( 'loop_shop_per_page', 'elsey_loop_shop_per_page', 2000 );
function elsey_loop_shop_per_page( $cols ) {
	return 30;
}

function elsey_is_ja_lang()
{
	$current_lang = apply_filters( 'wpml_current_language', NULL );
	return $current_lang == 'ja';
}


//Kimono Rental Function
function get_retal_contact_email_template($is_admin, $has_html = true)
{
	if ($is_admin)
	{
		$html = 'CAROME.サイトにて、着物レンタルの申し込みがありました。
申し込み詳細は以下となります。

申し込み詳細_______________________________________________
●レンタル情報
レンタル第１希望日：{year1}年 {month1}月 {date1}日
レンタル第２希望日：{year2}年 {month2}月 {date2}日
レンタル第３希望日：{year3}年 {month3}月 {date3}日

●身長 {height} cm
		
●お客様情報
お名前：{last_name} {first_name}
ふりがな：{last_name_kana} {first_name_kana}
電話番号：{tel}
メールアドレス：{email}
お届け先住所：〒{postcode}  {state}{city}{address1}{address2}';
	}
	else {
		$html = '{last_name} {first_name} 様
		
この度はCAROME.にて、着物レンタルのお申し込みをいただき、
まことにありがとうございます。

この時点で、レンタルのお申し込みは確定しておりません。

お申し込み内容から、レンタル日の決定、
レンタル受け付け完了までは、メールにて担当者から
連絡させていただきます。

担当者から、折り返し連絡させていただきますので、
いましばらくお待ちください。

お申し込み内容は以下となります。

お申し込み詳細_______________________________________________
●レンタル商品
着物セット 50,000円(税抜)

●レンタル情報
レンタル第１希望日：{year1}年 {month1}月 {date1}日
レンタル第２希望日：{year2}年 {month2}月 {date2}日
レンタル第３希望日：{year3}年 {month3}月 {date3}日

●身長 {height} cm
		
●お客様情報
お名前：{last_name} {first_name} 様
ふりがな：{last_name_kana} {first_name_kana}
電話番号：{tel}
メールアドレス：{email}
お届け先住所：〒{postcode}  {state}{city}{address1}{address2}
		
━━━━━━━━━━━━━━━━━━━━━━━━━━
　CAROME. ONLINE STORE
  レンタル着物カスタマーセンター
　お問い合わせ時間：平日 9:00〜18:00
  E-mail: rental@carome.net
　URL: https://www.carome.net/
━━━━━━━━━━━━━━━━━━━━━━━━━━';
	}
	$states = WC()->countries->get_states('JP');
	foreach ($_POST['contact'] as $field_name => $field)
	{
		if ($field_name == 'state')
		{
			$field = $states[$field];
		}
		$html = str_replace('{'.$field_name.'}', $field, $html);
	}
	return $html;
}
add_action( 'wp_ajax_nopriv_retal_submition', 'retal_submition' );

add_action( 'wp_ajax_retal_submition', 'retal_submition' );
function retal_submition() {
	$user_email = $_POST['contact']['email'];
	$admin_email = get_option( 'admin_email' );
	
	$site_title = get_bloginfo( 'name' );
	$headers = 'Content-type: text/plain;charset=utf-8' . "\r\n";
	$headers .= 'From: '.$site_title.' <info@carome.net>' . "\r\n";
	$bcc_email = 'kyoooko1122@gmail.com,rental@carome.net,yagi@libera-japan.com';
	if ($bcc_email)
	{
		$headers .= 'Bcc: '.$bcc_email."\r\n";
	}

	// Send to user
	$email_content = get_retal_contact_email_template(false, false);
	$subject = __(' 着物レンタルの申し込みを受け付けました | CAROME.', 'elsey') . "\r\n";
	$success = wp_mail($user_email, $subject, $email_content, $headers );
	if ($success)
	{
		// Now send to admin
		$email_content = get_retal_contact_email_template(true, false);
		$subject = __(' 着物レンタルの申し込みがありました | CAROME.', 'elsey') . "\r\n";
		$success = wp_mail($admin_email, $subject, $email_content, $headers );
	}
	echo json_encode(array('success' => $success, 'redirect' => get_site_url() . '/kimono-rental-thanks'));
	die;
}

//Free Gift Function
function isFreeGiftOrderProduct($order, $product)
{
	$order_items = $order->get_items();
	$is_free_gift = '';
	foreach ($order_items as $order_item_id => $order_item)
	{
		$free_gift_id = wc_get_order_item_meta( $order_item_id, '_product_id', true );
		$free_gift_id = $free_gift_id ? $free_gift_id : wc_get_order_item_meta( $order_item_id, '_variation_id', true );
		$item_price = $order->get_line_subtotal( $item );
	
		if ($product->get_id() == $free_gift_id && !$item_price)
		{
			$is_free_gift = wc_get_order_item_meta( $order_item_id, '_free_gift', true );
			if ($is_free_gift == 'yes')
			{
				break;
			}
		}
	}
	return $is_free_gift;
}

add_filter('woocommerce_free_gift_settings_fields', 'kitt_add_free_gift_settings_fields', 10, 1);
function kitt_add_free_gift_settings_fields($fields)
{
	$new_fields = array();
	foreach ($fields as $index => $field)
	{
		if ($index == count($fields) - 1)
		{
			$new_fields[] = array(
				'name' => __( 'Message text In thanks page 1', 'wc_free_gift' ),
				'id' => 'wc_free_gift_message_thanks',
				'type' => 'textarea',
				'css' => 'width:100%;',
				'default' => __( '', 'wc_free_gift' ),
				'desc' => __( 'Message text showing under free gift product in thanks page', 'wc_free_gift' )
			);
			
			$new_fields[] = array(
				'name' => __( 'Message text In thanks page 2', 'wc_free_gift' ),
				'id' => 'wc_free_gift_message_thanks_top',
				'type' => 'textarea',
				'css' => 'width:100%;',
				'default' => __( '', 'wc_free_gift' ),
				'desc' => __( 'Message text showing on top in thanks page', 'wc_free_gift' )
			);
		}
		$new_fields[] = $field;
	}
	
	return $new_fields;
}

/*Event Function*/

function event_styles() {
	wp_register_style( 'new-style', get_stylesheet_directory_uri() . '/css/new-woo-archive.css?v=' . time(), array('elsey-child-style') );
	wp_register_style( 'quick-style', get_stylesheet_directory_uri() . '/css/quick-view.css', array('elsey-child-style') );
	wp_register_style( 'event-style', get_stylesheet_directory_uri() . '/css/event-page.css?v='. time(), array('quick-style') );
	if (isCustomerInPrivateEvent()) {
		wp_enqueue_style('new-style');
	}
	wp_enqueue_style('quick-style');
	wp_enqueue_style('event-style');
}
add_action('wp_enqueue_scripts', 'event_styles');
function get_coupons_fields()
{
	$coupons_fields = acf_get_fields(BOOKING_FORM_ID);
	$coupons_fields = $coupons_fields && !empty($coupons_fields) ? $coupons_fields : array();
	return $coupons_fields;
}

function get_event_coupon_by($field_name)
{
	$coupons_fields = get_coupons_fields();
	foreach ($coupons_fields as $coupon_field)
	{
		if ($coupon_field['name'] == $field_name)
		{
			return $coupon_field['default_value'] ? $coupon_field['default_value'] : $coupon_field['instructions'];
			break;
		}
	}
}

function get_current_event_coupon()
{
	return get_event_coupon_by('current_event_coupon');
}

function get_distance_event_coupon()
{
	return get_event_coupon_by('distance_get_coupon');
}

function get_emails_event_coupon()
{
	$private_emails = get_event_coupon_by('email_of_users_for_event_coupon');
	$private_emails = str_replace(PHP_EOL, ';', $private_emails);
	$private_emails = str_replace(',', ';', $private_emails);
	$private_emails = str_replace(' ', ';', $private_emails);
	$private_emails = explode(';', $private_emails);
	$private_emails = array_map('trim', $private_emails);
	return $private_emails;
}

function get_event_time_start_end()
{
	$coupons_fields = get_coupons_fields();
	$current_coupon = get_current_event_coupon();
	$coupon_start_end = array();
	foreach ($coupons_fields as $coupon_field)
	{
		if ($coupon_field['name'] == 'coupons_code')
		{
			foreach ($coupon_field['sub_fields'] as $coupon)
			{
				if ($coupon['name'] == $current_coupon)
				{
					foreach ($coupon['sub_fields'] as $coupon_time)
					{
						if ($coupon_time['name'] == 'event_start')
						{
							$coupon_start_end['start'] = date('Y-m-d H:i:s', strtotime($coupon_time['default_value']));
						}
						elseif ($coupon_time['name'] == 'event_end')
						{
							$coupon_start_end['end'] = date('Y-m-d H:i:s', strtotime($coupon_time['default_value']));
						}
					}
				}
			}
		}
	}
	return $coupon_start_end;
}

function zoa_remove_email_event()
{
	$user = wp_get_current_user();
	$private_emails = get_emails_event_coupon();
	
	$user_email = $user->get('user_email');
	if (!empty($private_emails) && $user_email)
	{
		foreach ($private_emails as $key_email => $private_email)
		{
			if (trim($private_email) == trim($user_email))
			{
				// Load post and resave with email list
				$posts = get_posts(array(
					'posts_per_page'			=> -1,
					'post_type'					=> 'acf-field',
					'orderby'					=> 'menu_order',
					'order'						=> 'ASC',
					'suppress_filters'			=> true, // DO NOT allow WPML to modify the query
					'post_parent'				=> BOOKING_FORM_ID,
					'post_status'				=> 'publish, trash', // 'any' won't get trashed fields
					'update_post_meta_cache'	=> false
				));
				foreach ($posts as $post)
				{
					if ($post->post_excerpt == 'email_of_users_for_event_coupon')
					{
						$email_field = maybe_unserialize($post->post_content);
						unset($private_emails[$key_email]);
						$email_field['instructions'] = implode(PHP_EOL, $private_emails);
						$post->post_content = serialize($email_field);
						wp_update_post( $post );
						break;
					}
				}
				break;
			}
		}
	}
}

function else_ip_country($ip = NULL) {
	$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
		return $ipdat->geoplugin_countryCode;
	}
	return '';
}

add_action('init', 'elsey_init', 1);
function elsey_init() {
	if (!session_id())
	{
		session_start();
	}
	
	$ip = $_SERVER['REMOTE_ADDR'];
	
// 	$_SESSION['ip_country_code'] = '';
	
	if (!isset($_SESSION['ip_country_code']) || !$_SESSION['ip_country_code'])
	{
		$country_code = else_ip_country($ip);
		if ($country_code)
		{
			$_SESSION['ip_country_code'] = $country_code;
		}
	}
	remove_shortcode('elsey_product');
	require_once get_stylesheet_directory() . '/override/plugins/elsey-core/visual-composer/shortcodes/product/product.php';
}

add_action('wp', 'elsey_wp_loaded');
function elsey_wp_loaded()
{
	if (!is_user_logged_in() && isCustomerInPrivateEvent() && is_front_page() && !defined( 'DOING_AJAX' ))
	{
		wp_redirect(site_url('/enter/'));
	} elseif (is_user_logged_in() && isCustomerInPrivateEvent() && is_front_page() && !defined( 'DOING_AJAX' )) {
		wp_redirect(site_url('/shop/'));
	} elseif (is_user_logged_in() && isCustomerInPrivateEvent() && is_page('enter') && $_SESSION['user_agree_to_check_location'] && !defined( 'DOING_AJAX' )) {
		wp_redirect(site_url('/shop/'));
	}
}

function isCustomerInPrivateEvent()
{
	$user = wp_get_current_user();
	$private_emails = get_emails_event_coupon();
	
	$today = current_time('mysql');
	$event_start_end = get_event_time_start_end();
	$is_time_event = !empty($event_start_end) && ($today >= $event_start_end['start'] && $today <= $event_start_end['end']);
	$user_email = $user->get('user_email');
	if ($user_email && in_array(trim($user->get('user_email')), $private_emails) && $is_time_event)
	{
		$_SESSION['user_store_distance'] = 1;
		$_SESSION['user_at_store'] = 1;
		return 1;
	}
	elseif(isset($_SESSION['allow_private_coupon']) && $is_time_event) {
		return $_SESSION['allow_private_coupon'] == 1;
	}
	else {
		$_SESSION['user_store_distance'] = null;
		$_SESSION['allow_private_coupon'] = null;
		$_SESSION['user_at_store'] = null;
		return false;
	}
}

function elsey_woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id = 0, $variation = array(), $cart_item_data = array())
{
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	{
		return '';
	}
	
	$current_active_coupon = get_current_event_coupon();
	
	if (!isCustomerInPrivateEvent())
	{
		return '';
	}

	//geolocation is valid
	$coupon = new WC_Coupon($current_active_coupon);
	if ($coupon->get_date_created())
	{
		$packages = WC()->cart->get_shipping_packages();
		// CHeck coupon is added or not
		$isAddedCoupon = false;
		foreach ($packages as $package)
		{
			foreach ($package['applied_coupons'] as $applied_coupon)
			{
				if ($applied_coupon == $current_active_coupon)
				{
					$isAddedCoupon = true;
				}
			}
		}
		
		$todayTimeStamp = current_time('timestamp');
		$coupon_expire = $coupon->get_date_expires();
		if ($coupon_expire->getTimestamp() >= $todayTimeStamp && !$isAddedCoupon)
		{
			WC()->cart->add_discount( $current_active_coupon );
			wc_clear_notices();
		}
	}
}

function checkGeoLocationNearStore()
{
	$today = current_time('mysql');
	$event_start_end = get_event_time_start_end();
	
	if (!empty($event_start_end) && ($today <= $event_start_end['start'] || $today >= $event_start_end['end']))
	{
		unset($_SESSION['allow_private_coupon']);
		return '';
	}
	$shopLocation = array('lat' => get_event_coupon_by('store_latitude'), 'long' => get_event_coupon_by('store_longtitude'));
	?>
	<?php 
	$google_keys = array('AIzaSyCMbwOMB5I-AbwMAbRDHvdDaqlnC62KGxk', 'AIzaSyC0zkZJ3sHFHJgzUsAteOnObvf3ouAbc68');
	$google_key_index = array_rand($google_keys);
	?>
	<script src="https://maps.google.com/maps/api/js?libraries=geometry&key=<?php echo $google_keys[$google_key_index]?>" type="text/javascript"></script>
	<script type="text/javascript">
		function getMyPlace(is_button_click)
		{
			$ = jQuery;
			var id, options;
			if (!navigator.geolocation){//Geolocation apiがサポートされていない場合
				alert ('ご使用のブラウザはgeolocationがサポートされていません。ChromeかSafariのブラウザをご利用ください。');
			    return;
			  }
			
		
			function calcDistance (fromLat, fromLng, toLat, toLng) {
			      return google.maps.geometry.spherical.computeDistanceBetween(
			        new google.maps.LatLng(fromLat, fromLng), new google.maps.LatLng(toLat, toLng));
			}

			function showPopUpPermissionLocationDevice()
			{
				$('#error_message_access').html('<?php echo _e('<p><i class="evg-icon evg-icon-alert-circle-exc"></i> 位置情報が取得できませんでした。再度以下の手順にて設定ができているかご確認ください。</p>', 'elsey')?>');
				var inst = $.remodal.lookup[$('[data-remodal-id=beforeenter]').data('remodal')];
				inst.open();
			}
			
			function checking_allow_free_shipping_coupon(distance, is_blocked, err)
			{
				  $.ajax({
				        type: "post",
				        url: woocommerce_params.ajax_url,
				        crossDomain: false,
				        dataType : "json",
				        scriptCharset: 'utf-8',
				        data: {action: 'allow_use_free_shipping_coupon', distance: distance, verify: is_button_click, is_blocked: is_blocked}
				    }).done(function(data){
				    	if (data.in_store)
				    	{
					    	if (is_button_click)
					    	{
						    	alert('<?php echo __('アクセスポイントのマッチに成功しました！イベントオンラインショッピングをお楽しみください。', 'elsey')?>');
					    	}
					    }
				    	else if (is_button_click){
					    	if (err && err.code)
					    	{
					    		alert('エラーコード(' + err.code + '): ' + err.message + '. お使いの端末にて位置情報が許可されていないか、ブラウザのアプリ設定にて位置情報許可がされていません。');
					    		// Add failed text in popup and show it again
								showPopUpPermissionLocationDevice();
					    	}
					    	else {
				    			alert('<?php echo __('アクセスポイントがマッチしませんでした。イベント会場にいるのにマッチしない場合はスタッフまでお声がけください。', 'elsey')?>');
					    	}
				    		//alert('Distance setting is : <?php echo get_distance_event_coupon()?> meters. Distance between you vs Store is : ' + distance.toFixed(0) + ' meters');//remove this for event day
					    }
					    
					    <?php if (!isCustomerInPrivateEvent() && $_SERVER['REQUEST_METHOD'] !== 'POST') {?>
						    	if (data.in_store)
						    	{
						    		location.reload();
							    }
						<?php }?>
						if (data.message && data.redirect)
						{
							alert(data.message);
							location.href = data.redirect;
						}

						if (is_button_click)
						{
							$('body').LoadingOverlay('hide');
						}
				    });
				    
				    //navigator.geolocation.clearWatch(id);
			}
			function success(position) {
				var crd = position.coords;
				var distance = calcDistance (crd.latitude, crd.longitude, <?php echo $shopLocation['lat'] ? $shopLocation['lat'] : 0?>, <?php echo $shopLocation['long'] ? $shopLocation['long'] : 0?>);
				checking_allow_free_shipping_coupon(distance);
			}
		
			function error(err) {
				//make very large distance if location is blocked
				distance = 9999999999;
				checking_allow_free_shipping_coupon(distance, true, err);
			}
		
			options = {
			  enableHighAccuracy: false,
			  timeout: 20000,
			  maximumAge: 0
			};

			if (is_button_click)
			{
				$('body').LoadingOverlay('show');
				setTimeout(function(){
					if ($('.loadingoverlay').length)
					{
						$('body').LoadingOverlay('hide');
						// Add failed text in popup and show it again
						showPopUpPermissionLocationDevice();
					}
				}, 21000);
			}
			id = navigator.geolocation.getCurrentPosition(success, error, options);
		}
	jQuery(function($){
		<?php if (isset($_SESSION['user_agree_to_check_location']) && $_SESSION['user_agree_to_check_location'] == 1) {?>
		getMyPlace();
		<?php }?>
	});
	</script>
<?php
}
add_action( 'init', 'elsey_check_private_coupon' );
function elsey_check_private_coupon()
{
	isCustomerInPrivateEvent();
	
	$today = current_time('mysql');
	$event_start_end = get_event_time_start_end();
	
	if (WC()->cart && !is_admin() && ($today <= $event_start_end['start'] || $today >= $event_start_end['end']) || (isset($_SESSION['user_store_distance']) && $_SESSION['user_store_distance'] > get_distance_event_coupon()))
	{
		zoa_remove_private_coupon ();
	}
	else {
		elsey_woocommerce_add_to_cart(false, false, false);
	}
}

function zoa_remove_private_coupon ()
{
	if (!WC()->cart) return ;
	
	// Remove cart coupon
	$packages = WC()->cart->get_shipping_packages();
	$current_active_coupon = get_current_event_coupon();
	foreach ($packages as $package)
	{
		foreach ($package['applied_coupons'] as $applied_coupon)
		{
			if ($applied_coupon == $current_active_coupon)
			{
				WC()->cart->remove_coupon( $current_active_coupon );
			}
		}
	}
	
	$_SESSION['allow_private_coupon'] = null;
	unset($_SESSION['allow_private_coupon']);
}

add_action( 'wp_ajax_nopriv_allow_use_free_shipping_coupon', 'elsey_allow_use_free_shipping_coupon' );
add_action( 'wp_ajax_allow_use_free_shipping_coupon', 'elsey_allow_use_free_shipping_coupon' );
function elsey_allow_use_free_shipping_coupon()
{
	if(defined( 'DOING_AJAX' ))
	{
		$is_remove = false;
		$today = current_time('mysql');
		$event_start_end = get_event_time_start_end();
		$_SESSION['user_store_distance'] = $_POST['distance'];
		//Test with developer distance = 1
		if ($_SERVER['REMOTE_ADDR'] == '14.248.158.112' && !isset($_REQUEST['is_blocked']))
		{
			$_SESSION['user_store_distance'] = $_POST['distance'] = 1;
		}
		
		if (isset($_REQUEST['is_blocked']) && $_REQUEST['is_blocked'])
		{
			$_SESSION['user_agree_to_check_location'] = null;
			$_SESSION['allow_private_coupon'] = null;
		}
		
		if ($_POST['distance'] <= get_distance_event_coupon() && ($today >= $event_start_end['start'] || $today <= $event_start_end['end']))
		{
			if (isset($_REQUEST['verify']) && $_REQUEST['verify'])
			{
				$_SESSION['user_agree_to_check_location'] = 1;
			}
			
			$_SESSION['allow_private_coupon'] = 1;
			$_SESSION['user_at_store'] = 1;
		}else {
			if ($_SESSION['user_at_store'] == 1)
			{
				$is_remove = true;
				unset($_SESSION['user_at_store']);
			}
			zoa_remove_private_coupon();
		}
	}
	if ($is_remove)
	{
		print_r(json_encode(array('in_store' => (int)$_SESSION['allow_private_coupon'], 'message' => __('イベント会場からでたので通常ページへ戻ります', 'elsey'), 'redirect' => site_url())));die;
	}
	else {
		print_r(json_encode(array('in_store' => (int)$_SESSION['allow_private_coupon'], 'message' => '')));die;
	}
}

add_action( 'woocommerce_checkout_update_order_meta', 'elsey_woocommerce_checkout_update_order_meta' );
function elsey_woocommerce_checkout_update_order_meta( $order_id )
{
	$post = get_post($order_id);
	if ($post->post_type !== 'shop_order')
	{
		return '';
	}
	
	$order = new WC_Order($order_id);
	$coupons = $order->get_used_coupons();
	if (!empty($coupons))
	{
		foreach ($coupons as $coupon)
		{
			if ($coupon == get_current_event_coupon())
			{
				update_post_meta($order_id, 'use_event_store_coupon', $coupon);
				break;
			}
		}
	}
	
	// Add specific option by customer
	if ($order->get_status() != 'cancelled')
	{
		$items = $order->get_items();
		$purchased_products = array();
		foreach ( $items as $item ) {
			if (elsey_is_specific_product($item['product_id']))
			{
				if (isset($item [ 'variation_id' ]) && $item [ 'variation_id' ])
				{
					$purchased_products[$item [ 'variation_id' ]]['id'] = $item [ 'variation_id' ];
					$purchased_products[$item [ 'variation_id' ]]['is_variation'] = true;
				}
				else {
					$purchased_products[$item [ 'product_id' ]]['id'] = $item [ 'product_id' ];
					$purchased_products[$item [ 'product_id' ]]['is_variation'] = false;
				}
			}
		}
		if (!empty($purchased_products))
		{
			$user_id = get_current_user_id();
			$user_email = $order->get_billing_email();
			$user_phone = $order->get_billing_phone();
			
			$old_purchased_products = get_option('specific_user_' . $user_id);
			$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_email);
			$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_phone);
			
			if ($old_purchased_products)
			{
				$purchased_products = $purchased_products + $old_purchased_products;
			}
			
			if ($user_id)
			{
				update_option('specific_user_' . $user_id, $purchased_products);
			}
			update_option('specific_user_' . $user_email, $purchased_products);
			update_option('specific_user_' . $user_phone, $purchased_products);
		}
	}
}

add_action( 'save_post', 'elsey_store_event_coupon_meta' );
function elsey_store_event_coupon_meta( $post_id ) {
	elsey_woocommerce_checkout_update_order_meta( $post_id );
}

function register_user_in_event_menu() {
	register_nav_menus(
		array(
			'event_menu' => __( 'Event Menu' )
		)
	);
}
add_action( 'init', 'register_user_in_event_menu' );

function isOrderEvent($order)
{
	$event_coupon = get_post_meta($order->get_id(), 'use_event_store_coupon', true);
	return $event_coupon ? true : false;
}

function showOrderEventLabel($order){
	if (isOrderEvent($order))
	{
		echo '<span class="event-order-label" style="font-family: brandon-grotesque,sans-serif;
    font-weight: 600;
    letter-spacing: 2px;
    background: #800080;
    color: #FFF;
    display: inline-block;
	margin-left: 10px;
    padding: 3px 6px;
    margin-left: 5px;
    font-size: 12px;
    border-radius: 5px;line-height: 20px;
    font-size: 14px;">'. __('Event', 'elsey') .'</span>';
	}
}

add_filter( 'wp_nav_menu_args', 'elsey_event_nav_menu_args', 9999 );
function elsey_event_nav_menu_args( $args ) {
	if (isCustomerInPrivateEvent())
	{
		$args['menu_class'] .= ' event-navigation';
		$args['theme_location'] = 'event_menu';
	}
	
	return $args;
}

add_action('woocommerce_before_checkout_form', 'elsey_woocommerce_after_shipping_price_custom');
add_action('woocommerce_before_cart', 'elsey_woocommerce_after_shipping_price_custom');
add_action('woocommerce_after_shipping_price_custom', 'elsey_woocommerce_after_shipping_price_custom');
function elsey_woocommerce_after_shipping_price_custom()
{
	$current_filter = current_filter();
	$packages = WC()->cart->get_shipping_packages();
	$aOrderBothType = isBothOrderTypeShipping($packages[0]);
	if ($aOrderBothType && count($aOrderBothType) > 1)
	{
		if (in_array($current_filter, array('woocommerce_before_cart', 'woocommerce_before_checkout_form'))  )
		{
			echo '<div class="order__summary__row shipping_fee_message"><span class="big-text">' . __('This order will be separated as 2 orders for normal and pre-order. But payment will be total of both orders.', 'elsey') . '<span></div>';
		}
		else {
			echo '<div class="order__summary__row shipping_fee_message"><span class="small-text">' . __('This shipping fee is for 2 orders of normal order and pre-order', 'elsey') . '<span></div>';
			if (isCustomerInPrivateEvent())
			{
				echo '<div class="order__summary__row shipping_fee_message event_message"><span class="small-text">' . __('送料が無料となるのは通常商品のご注文のみとなります。', 'elsey') . '<span></div>';
			}
		}
	}
}

add_filter('woocommerce_package_rates', 'elsey_shipping_class_null_shipping_costs', 10, 2);
function elsey_shipping_class_null_shipping_costs( $rates, $package ){
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return $rates;
		
		$free_shipping = false;
		if (!empty($package['applied_coupons']))
		{
			foreach ($package['applied_coupons'] as $applied_coupon)
			{
				if ($applied_coupon == get_current_event_coupon())
				{
					$free_shipping = true;
				}
			}
		}
		
		// Set shipping costs to zero if use coupon freeshipping
		if( $free_shipping ){
			global $global_shipping_preorder_total;
			foreach ( $rates as $rate_key => $rate ){
				$has_taxes = false;
				$taxes = array();
				// Targetting "flat rate" and local pickup
				if (isset($rates[$rate_key]->rate_normal) && isset($rates[$rate_key]->rate_preorder))
				{
					if ($rates[$rate_key]->rate_normal == $rates[$rate_key]->rate_preorder && $rates[$rate_key]->rate_normal > 0)
					{
						$rates[$rate_key]->cost = $rates[$rate_key]->rate_preorder;
					}
					else{
						$rates[$rate_key]->cost = 0;
					}
				}
				else {
					if ($global_shipping_preorder_total)
					{
						$rates[$rate_key]->cost = $global_shipping_preorder_total;
					}
					else 
					{
						$rates[$rate_key]->cost = 0;
					}
				}
				
				// Taxes rate cost (if enabled)
				foreach ($rates[$rate_key]->taxes as $key => $tax){
					if( $rates[$rate_key]->taxes[$key] > 0 ){
						$has_taxes = true;
						// Set taxes cost to zero
						$taxes[$key] = 0;
					}
				}
				if( $has_taxes )
					$rates[$rate_key]->taxes = $taxes;
			}
		}
		return $rates;
}

//end event function

//test to check user role depend on rule
function get_current_user_role() {
	global $wad_discounts;
	if (!$wad_discounts || !isset($wad_discounts['product'])) return '';
	
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	//$role = array_shift($roles);
	if( is_user_logged_in() ) {
		$role = array_shift($roles);
	} else {
		$role = 'not-logged-in';
	}
	$customer_discount_rule = '';
	$first_condition = array_shift($wad_discounts['product']);
	if (isset($first_condition->settings['rules'][0]))
	{
		foreach( $first_condition->settings['rules'][0] as $rule)
		{
			if ($rule['condition'] == 'customer-role')
			{
				$customer_discount_rule = $rule['value'][0];
			}
		}
	}
	if ($customer_discount_rule && $first_condition->title == '2点セット価格') {
		return '<p class="check_user">'.$role.' should be <strong>'.$customer_discount_rule.'</strong></p>';
	}
	
}

//woocommerce all discount plugin
add_action('woocommerce_before_notices', 'my_custom_message');
function my_custom_message() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if (is_plugin_active('advanced-dynamic-pricing-for-woocommerce-pro/advanced-dynamic-pricing-for-woocommerce-pro.php')) {
        //プラグインが有効の場合
        if (is_admin() && !defined('DOING_AJAX'))
            return;
        //to display notice only on cart page
        if (!is_cart() && !is_checkout()) {
            return;
        }
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        $customer_discount_rule = '';
        $rules = WDP_Database::get_rules();
        if (!empty($rules)) {
            foreach ($rules as $key => $value) {
                if ($value['title'] == '2 sets jewelry discount' && $value['enabled'] == 'on') {
                    $customer_discount_rule = $value['title'];
                    break;
                }
            }

            if ($customer_discount_rule != '') {
                $count_twoset_price_jwl_cart = 0;
                foreach ($items as $item => $values) {
                    if (has_term('twoset_price_jwl', 'product_cat', $values['product_id'])) {
                        $res=WDP_Frontend::get_active_rules_for_product($values['product_id']);
                        if(!empty($res)){
                            $count_twoset_price_jwl_cart += $values['quantity'];
                        }
                    }
                }
                //get current user role
                $current_user = wp_get_current_user();
                $roles = $current_user->roles;
//check current role slug
                if (is_user_logged_in()) {
                    $role = array_shift($roles);
                } else {
                    $role = 'not-logged-in';
                }
                if ($count_twoset_price_jwl_cart == 1) {
                    wc_clear_notices();
                    wc_add_notice(__("2点セットプライス対象アクセサリーをもう1点ご購入で5,000円のセットプライスになります"), 'notice');
                } elseif ($count_twoset_price_jwl_cart == 3) {
                    wc_add_notice(__("2点セットプライス対象アクセサリーのセット価格は2点、4点、6点のご注文のみに適用されます"), 'notice');
                } elseif ($count_twoset_price_jwl_cart == 5) {
                    wc_add_notice(__("2点セットプライス対象アクセサリーのセット価格は2点、4点、6点のご注文のみに適用されます"), 'notice');
                } elseif ($count_twoset_price_jwl_cart == 7) {
                    wc_add_notice(__("2点セットプライス対象アクセサリーのセット価格は6点までのみのご注文に適用されます"), 'notice');
                }
            }
        }
    }
}

function showDiscountLabel($product)
{
	global $wad_discounts;
	global $new_extraction_algorithm;
	if ( $wad_discounts && ! empty($wad_discounts["product"]) )
	{
		global $wad_ignore_product_prices_calculations;
		$previous_value=$wad_ignore_product_prices_calculations;
		$wad_ignore_product_prices_calculations=TRUE;
		$regular_price = $product->get_regular_price();
		$sale_price= $regular_price;
		$wad_ignore_product_prices_calculations=$previous_value;
		
		$pid = wad_get_product_id_to_use($product);
		
		foreach ( $wad_discounts["product"] as $discount_id => $discount_obj )
		{
			if ( $new_extraction_algorithm ) $list_products = $discount_obj->products_list->get_products(true);
			else $list_products = $discount_obj->products_list->get_products();
			$disable_on_products_pages = get_proper_value($discount_obj->settings, "disable-on-product-pages", "no");
			// Even If the discount is disabled on the shop pages, we force it to be enabled in the minicart even if this minicart is on the shop pages
			if ( $disable_on_products_pages && did_action('woocommerce_before_mini_cart_contents') && ! did_action('woocommerce_after_mini_cart') ) $disable_on_products_pages = false;
			// if ($disable_on_products_pages == "yes" && (is_singular("product") || is_shop() || is_product_category() || is_front_page()))
			if ( $disable_on_products_pages == "yes" && (! is_cart() && ! is_checkout()) ) continue;
			if ( $discount_obj->is_applicable($pid) && is_array($list_products) && in_array($pid, $list_products) )
			{
				$sale_price = floatval($sale_price) - $discount_obj->get_discount_amount(floatval($sale_price));
				echo '<span class="discount_title">'.$discount_obj->title . esc_html__( '適用中', 'elsey' ) . '</span>';
			}
		}
	}
}
function my_custom_item_label() {
	global $product;
	global $wad_discounts;
	global $new_extraction_algorithm;
	if ( !$product->is_in_stock() ) {
		echo '<span class="els-product-sold">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>';
	} else if ( $wad_discounts && ! empty($wad_discounts["product"]) ) {
		echo '';
		//echo '<span class="els-product-onsale">' . esc_html__( 'Campaign Price!', 'elsey' ) . '</span>';
	} else if ( $product->is_on_sale() ) {
		echo '<span class="els-product-onsale">' . esc_html__( 'Sale!!', 'elsey' ) . '</span>';
	}
}
add_action('woocommerce_shop_loop_label', 'my_custom_item_label');

add_action( 'woocommerce_before_shop_loop_item_title', 'elsey_show_product_badge', 10);
  if ( ! function_exists('elsey_show_product_badge') ) {
	  function elsey_show_product_badge() {
	  	global $product;
		global $wad_discounts;
		global $new_extraction_algorithm;
		  if ( !$product->is_in_stock() ) {
				echo '<span class="els-product-sold">' . esc_html__( 'Sold', 'elsey' ) . '</span>';
			} else if ( $wad_discounts && ! empty($wad_discounts["product"]) ) {
			  global $wad_ignore_product_prices_calculations;
		$previous_value=$wad_ignore_product_prices_calculations;
		$wad_ignore_product_prices_calculations=TRUE;
		$regular_price = $product->get_regular_price();
		$sale_price= $regular_price;
		$wad_ignore_product_prices_calculations=$previous_value;
		
		$pid = wad_get_product_id_to_use($product);
			  foreach ( $wad_discounts["product"] as $discount_id => $discount_obj )
		{
			if ( $new_extraction_algorithm ) $list_products = $discount_obj->products_list->get_products(true);
			else $list_products = $discount_obj->products_list->get_products();
			$disable_on_products_pages = get_proper_value($discount_obj->settings, "disable-on-product-pages", "no");
			// Even If the discount is disabled on the shop pages, we force it to be enabled in the minicart even if this minicart is on the shop pages
			if ( $disable_on_products_pages && did_action('woocommerce_before_mini_cart_contents') && ! did_action('woocommerce_after_mini_cart') ) $disable_on_products_pages = false;
			// if ($disable_on_products_pages == "yes" && (is_singular("product") || is_shop() || is_product_category() || is_front_page()))
			if ( $disable_on_products_pages == "yes" && (! is_cart() && ! is_checkout()) ) continue;
			if ( $discount_obj->is_applicable($pid) && is_array($list_products) && in_array($pid, $list_products) )
			{
				$sale_price = floatval($sale_price) - $discount_obj->get_discount_amount(floatval($sale_price));
				echo '';
				//echo '<span class="els-product-onsale">' . esc_html__( 'Campaign Price!', 'elsey' ) . '</span>';
			}
		}
		  } else if ( $product->is_on_sale() ) {
				echo '<span class="els-product-onsale">' . esc_html__( 'Sale!!', 'elsey' ) . '</span>';
			}
	  }
	}
function ch_custom_price_message($price, $product = null) {

	if (!$product) return $price;
	
	$product_cat_slug = 'twoset_price_jwl';
	$product_cat = get_term_by( 'slug', $product_cat_slug, 'product_cat' );
	
	if (!$product_cat) return $price;
	
	$product_cat_id = $product_cat->term_id;
    $product_cats_ids = wc_get_product_term_ids($product->get_id(), 'product_cat');
	//date_default_timezone_set('Asia/Tokyo');
    $from = "2019-01-08 12:00:00";
    $to = "2019-01-14 23:59:00";
    
    if (in_array($product_cat_id, $product_cats_ids) && (current_time('timestamp') >= strtotime($from) && current_time('timestamp') <= strtotime($to))) {
    	return $price . '<span class="discount_title">2点セット価格</span>';
    } else {
        return $price;
    }
}

add_filter('woocommerce_get_price_html', 'ch_custom_price_message',99999, 2);

add_action('woocommerce_no_products_found', 'ch_woocommerce_no_products_found', 9);
function ch_woocommerce_no_products_found() {
    remove_action('woocommerce_no_products_found', 'wc_no_products_found', 10);
    echo '<div class="no_result_msg">' . __('Item is not found by your keyword.', 'elsey') . '</div> ';
}

function show_epsilon_cs_order_success_text($order)
{
	if ($order->get_payment_method() == 'epsilon_pro_cs')
	{
		$order_id = $order->get_id();
		$phone = get_post_meta($order_id, '_billing_phone', true);
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
		echo '<p class="order--details__date serif">
			<span class="label">決済方法</span>
			<span class="value">'. $cs_stores_ids[get_post_meta($order_id,'_cvs_company_id',true)] .'(コンビニ決済)</span>
		</p>';
		echo '<div class="conv_info">';
		if ($epsilon_data['conveni_code'] == 21) {
			echo '<p class="order--details__kigyo conv_list serif"><span class="label">企業コード：</span><span class="value">' . $epsilon_data['kigyou_code'] . '</span></p>';
		}
		if ($epsilon_data['conveni_code'] == 11) {
			$hurl = urldecode($epsilon_data['haraikomi_url']);
			echo '<p class="order--details__haraiurl conv_list serif"><span class="label">'. __('Harai url : ', 'wc4jp-epsilon' ).'</span><span class="value"><a href="'. $hurl .'" target="_blank">'. $hurl .'</a></span></p>';
		}
		echo '<p class="order--details__date conv_list serif">
			<span class="label">'. $receiptLabel .'：</span>
			<span class="value">'. $epsilon_data['receipt_no'] .'</span>
		</p>';
		if ($epsilon_data['conveni_code'] == 31 || $epsilon_data['conveni_code'] == 32 || $epsilon_data['conveni_code'] == 33 || $epsilon_data['conveni_code'] == 35 || $epsilon_data['conveni_code'] == 36) {
			echo '<p class="order--details__torinum conv_list serif"><span class="label">'. $phoneLabel .'：</span><span class="value">' . $phone . '</span></p>';
		}
		echo '<p class="order--details__date conv_list serif">
			<span class="label">'. __('Expire date : ', 'wc4jp-epsilon' ) .'</span>
			<span class="value">'. $epsilon_data['conveni_limit'] .'</span>
		</p>';
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
		echo '</div>';
	}
	
}

//Delete cache of shop page and product category page when product is published
add_action( 'save_post', 'ch_published_product_scheduled', 10, 3);

function ch_published_product_scheduled($post_id, $post, $update){
    if ($post->post_status != 'publish' || $post->post_type != 'product') {
        return;
    }

    if (!$product = wc_get_product( $post )) {
        return;
    }

    $path_cache=ABSPATH.'wp-content/cache/wp-rocket/'.$_SERVER['HTTP_HOST'];
    $shop=$path_cache.'/shop';
    ch_rmdir__files_recurse($shop);
    
    $product_category=$path_cache.'/product-category';
    ch_rmdir__files_recurse($product_category);
}

function ch_rmdir__files_recurse($path) {
  $path = rtrim($path, '/') . '/';
  $handle = opendir($path);  
  
  if (!is_dir($path))
  	return ;
  
  while (false !== ($file = readdir($handle))) {
    if($file != '.' and $file != '..' ) {
      $fullpath = $path.$file;
      if (is_dir($fullpath)) ch_rmdir__files_recurse($fullpath);
      else unlink($fullpath);
    }
  }
  closedir($handle);
  rmdir($path);
}
//end

function elsey_cart_contains_specific_product( ) {
	
	global $woocommerce;
	
	$contains_specific_product = false;
	
	if ( ! empty($woocommerce->cart->cart_contents) )
	{
		
		foreach ( $woocommerce->cart->cart_contents as $cart_item )
		{
			if ( elsey_is_specific_product($cart_item['product_id']) )
			{
				$contains_specific_product = true;
				break;
			}
		}
	}
	
	return $contains_specific_product;
	
}

function elsey_is_specific_product($product_id)
{
	$is_specific_product = get_post_meta( $product_id, '_is_specific_cart_item', true );
	return 'yes' === $is_specific_product ? true : false;
}

function elsey_is_no_free_shipping_product($product_id)
{
	$_is_no_free_shipping_item = get_post_meta( $product_id, '_is_no_free_shipping_item', true );
	return 'yes' === $_is_no_free_shipping_item ? true : false;
}

add_filter('woocommerce_add_to_cart_validation', 'elsey_validate_specific_product_in_cart', 1, 5);
function elsey_validate_specific_product_in_cart ( $valid, $product_id, $quantity = 0, $variation_id = 0, $variations = null )
{
	global $woocommerce;
	if ( elsey_is_specific_product($product_id) )
	{
		// Check this product id is ordered by this customer before or not
		$user_id = get_current_user_id();
		$is_purchased_by_customer = false;
		if ($user_id)
		{
			$user_email = get_user_meta($user_id, 'billing_email', true);
			$user_phone = get_user_meta($user_id, 'billing_phone', true);
			
			$old_purchased_products = get_option('specific_user_' . $user_id);
			$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_email);
			$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_phone);
			
			if (!empty($old_purchased_products))
			{
				$old_purchased_product_ids = array_keys($old_purchased_products);
				if (in_array($variation_id, $old_purchased_product_ids) || in_array($product_id, $old_purchased_product_ids))
				{
					$is_purchased_by_customer = true;
				}
					
			}
		}
		if ($is_purchased_by_customer)
		{
			wc_add_notice(__('Sorry, You already ordered this product before.', 'elsey'));
			$valid = false;
		}
		// Check this product is purchased before or not.
		elseif ( $woocommerce->cart->get_cart_contents_count() >= 1 )
		{
			foreach ( WC()->cart->get_cart() as $cart_item )
			{
				if ( ! elsey_is_specific_product($cart_item['product_id']) )
				{
					$valid = false;
					wc_add_notice(__('This product cannot be added because this product is specific product, which must be purchased separately.', 'elsey'));
					return $valid;
					break;
				}
				else {
					// If it is specific item, prevent same id
					if (($variation_id && $variation_id == $cart_item['variation_id']) || (!$variation_id && $product_id == $cart_item['product_id']))
					{
						$valid = false;
						wc_add_notice(__('This product cannot be added to your cart because it already added in cart', 'elsey'));
					}
				}
			}
		}
		else
		{
			return $valid;
		}
	}
	else
	{
		// if there's a specific product in the cart already, prevent anything else from being added
		if ( elsey_cart_contains_specific_product() )
		{
			wc_add_notice(__('This product cannot be added to your cart because it already contains a specific product, which must be purchased separately.', 'elsey'));
			$valid = false;
		}
	}
	return $valid;
}

function elsey_woocommerce_after_checkout_validation($data, $errors) {
	global $woocommerce;
	$user_id = get_current_user_id();
	$old_purchased_products = null;
	if ($user_id )
	{
		$user_email = get_user_meta($user_id, 'billing_email', true);
		$user_phone = get_user_meta($user_id, 'billing_phone', true);
		$old_purchased_products = get_option('specific_user_' . $user_id);
	}
	else
	{
		$user_email = $data['billing_email'];
		$user_phone = $data['billing_phone'];
	}
	$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_email);
	$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_phone);
	
	if ( $woocommerce->cart->get_cart_contents_count() >= 1 && !empty($old_purchased_products))
	{
		$old_purchased_product_ids = array_keys($old_purchased_products);
		foreach ( WC()->cart->get_cart() as $cart_item )
		{
			if ( elsey_is_specific_product($cart_item['product_id']) )
			{
				if (in_array($cart_item['variation_id'], $old_purchased_product_ids) || in_array($cart_item['product_id'], $old_purchased_product_ids))
				{
					$errors->add( 'validation', sprintf(__( 'You already ordered Product %s before, please go to cart and remove it.', 'elsey' ), $cart_item['data']->name));
				}
			}
		}
	}
	return $errors;
}
add_action('woocommerce_after_checkout_validation', 'elsey_woocommerce_after_checkout_validation',10,2);

add_action( 'woocommerce_order_status_changed', 'elsey_woocommerce_order_status_changed_remove_specific_product', 1000, 4 );
function elsey_woocommerce_order_status_changed_remove_specific_product($order_id, $from_status, $to_status, $order)
{
	if ($to_status == 'cancelled')
	{
		$user_email = $order->get_billing_email();
		$user_phone = $order->get_billing_phone();
		$user_id	= $order->get_customer_id();;
		$old_purchased_products = null;
		
		if ($user_id)
		{
			$old_purchased_products = get_option('specific_user_' . $user_id);
		}
		$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_email);
		$old_purchased_products = $old_purchased_products ? $old_purchased_products : get_option('specific_user_' . $user_phone);
		if (!empty($old_purchased_products))
		{
			$items = $order ->get_items();
			foreach ( $items as $item ) {
				if (isset($old_purchased_products[$item['product_id']]))
				{
					unset($old_purchased_products[$item['product_id']]);
				}
				if (isset($old_purchased_products[$item['variation_id']]))
				{
					unset($old_purchased_products[$item['variation_id']]);
				}
			}
			
			if ($user_id)
			{
				update_option('specific_user_' . $user_id, $old_purchased_products);
			}
			update_option('specific_user_' . $user_email, $old_purchased_products);
			update_option('specific_user_' . $user_phone, $old_purchased_products);
// 			pr($old_purchased_products);die;
		}
	}
}



//For set cron job on server
//Url to set cron job
//domain/wp-admin/admin-ajax.php?action=ch_run_products_scheduled&key=538e0A01f737g1Ae518331D2d920Fbd1
add_action('wp_ajax_ch_run_products_scheduled', 'ch_run_products_scheduled');
add_action('wp_ajax_nopriv_ch_run_products_scheduled', 'ch_run_products_scheduled');

function ch_run_products_scheduled() {
    if (isset($_REQUEST['key']) && $_REQUEST['key'] == '538e0A01f737g1Ae518331D2d920Fbd1') {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'future',
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $post_id = '';
            while ($query->have_posts()) {
                $query->the_post();
                $post = get_post(get_the_ID());
                $date = date_create($post->post_date);
                $post_date = date_format($date, "Y-m-d H:i");
                if (current_time('timestamp') >= strtotime($post_date)) {
                    wp_publish_post(get_the_ID());
                    $post_id .= get_the_ID() . ",";
                }
            }
            if (!empty($post_id)) {
                //for check log
                wp_mail("chien.lexuan@gmail.com", get_home_url().' .Published product id: ' . $post_id, 'Published product id: ' . $post_id);
                wp_mail("kyoko@heart-hunger.com", get_home_url().' .Published product id: ' . $post_id, 'Published product id: ' . $post_id);
                //end
                echo get_home_url().' .Not found products to publish';
            } else {
                echo get_home_url().' .Not found products to publish';
            }
        } else {
            echo get_home_url().' .Not found products to publish';
        }
    } else {
        echo 'No permission';
    }
    exit();
}

//End


////////////////////////////////// Serhii Kniaziuk Changes 20190423 ////////////////////////////////////////////////

add_filter( 'woocommerce_reports_order_statuses', 'tcstope_order_status_for_reports_on_hold_remove', 10000, 1 ); 

function tcstope_order_status_for_reports_on_hold_remove($order_statuses){
    
    $current_screen_obj = get_current_screen();
    
    //echo "asdf1: "; print_r($order_statuses);
    
    if ( $current_screen_obj->base == 'woocommerce_page_wc-reports' || is_admin() ){
        
        if ( is_array($order_statuses) ){
        
            if ( in_array("on-hold", $order_statuses) || in_array("auto-cancel", $order_statuses) || in_array("test-orders", $order_statuses) || in_array("sp-onhold", $order_statuses) || in_array("refunded", $order_statuses) ){
                
                foreach ($order_statuses as $order_status_key => $order_status){
                    
                    if ( $order_status == "on-hold" ){
                        unset( $order_statuses[$order_status_key] );
                    }
                    
                    if ( $order_status == "auto-cancel" ){
                        unset( $order_statuses[$order_status_key] );
                    }
                    
                    if ( $order_status == "test-orders" ){
                        unset( $order_statuses[$order_status_key] );
                    }
                    
                    if ( $order_status == "sp-onhold" ){
                        unset( $order_statuses[$order_status_key] );
                    }
                    
                    if ( $order_status == "refunded" ){
                        unset( $order_statuses[$order_status_key] );
                    }
                    
                }
            
            }
            
        }
        
    }

    return $order_statuses;
}


add_filter( 'woocommerce_reports_order_statuses', 'tcstope_order_status_for_reports_check_pre_ordered', 10000, 1 ); 

function tcstope_order_status_for_reports_check_pre_ordered($order_statuses){
    
    $current_screen_obj = get_current_screen();
    
    if ( is_admin() || $current_screen_obj->base == 'woocommerce_page_wc-reports' ){
        
        if ( is_array($order_statuses) && !empty($order_statuses) ){
        
            if ( !in_array("pre-ordered", $order_statuses) ){
                
                $order_statuses[] = "pre-ordered";
            
            }
            
            if ( !in_array("completed", $order_statuses) ){
                
                $order_statuses[] = "completed";
            
            }
            
            if ( !in_array("processing", $order_statuses) ){
                
                $order_statuses[] = "processing";
            
            }
            
        }
        
    }

    return $order_statuses;
}

////////////////////////////////// END Serhii Kniaziuk Changes 20190423 ////////////////////////////////////////////////
