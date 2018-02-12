<?php
/*
 * Codestar Framework - Custom Style
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/* All Dynamic CSS Styles */
if ( ! function_exists( 'elsey_dynamic_styles' ) ) {

  function elsey_dynamic_styles() {

    $elsey_vt_get_typography = elsey_vt_get_typography();

    $all_element_color = cs_get_customize_option( 'all_element_colors' );

    ob_start();

    global $post;
    $elsey_id   = ( isset( $post ) ) ? $post->ID : false;
    $elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
    $elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
    $elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

    /* Title Area Outer Background */
		$titlebar_options = isset($elsey_meta['titlebar_options']) ? $elsey_meta['titlebar_options'] : '';

		if ($titlebar_options === 'custom') {
		  $titlebar_bg = isset($elsey_meta['titlebar_bg']) ? $elsey_meta['titlebar_bg'] : '';
		  $titlebar_bg_overlay = isset($elsey_meta['titlebar_bg_overlay']) ? $elsey_meta['titlebar_bg_overlay'] : '';
		} elseif ($titlebar_options === 'hide') {
		  $titlebar_bg = '';
		  $titlebar_bg_overlay = '';
		} else {
		  $titlebar_tp = cs_get_option('need_titlebar');
		  if($titlebar_tp){
		    $titlebar_bg = cs_get_option('titlebar_bg');
		    $titlebar_bg_overlay = cs_get_option('titlebar_bg_overlay');
		  } else {
		  	$titlebar_bg = '';
		    $titlebar_bg_overlay = '';
		  }
		}

		$titlebar_bg_url      = ( ! empty( $titlebar_bg['image'] ) ) ? 'background-image: url('. $titlebar_bg['image'] .');' : ' ';
		$titlebar_bg_repeat   = ( ! empty( $titlebar_bg['repeat'] ) ) ? 'background-repeat: '. $titlebar_bg['repeat'] .';' : 'background-repeat: no-repeat;';
		$titlebar_bg_position = ( ! empty( $titlebar_bg['position'] ) ) ? 'background-position: '. $titlebar_bg['position'] .';' : 'background-position: center top;';
		$titlebar_bg_attachment = ( ! empty( $titlebar_bg['attachment'] ) ) ? 'background-attachment: '. $titlebar_bg['attachment'] .';' : '';
		$titlebar_bg_size     = ( ! empty( $titlebar_bg['size'] ) ) ? 'background-size: '. $titlebar_bg['size'] .';' : 'background-size: cover;';
		$titlebar_bg_color    = ( ! empty( $titlebar_bg['color'] ) ) ? 'background-color: '. $titlebar_bg['color'] .';' : '';

if ( isset($titlebar_bg_url) || isset($titlebar_bg_color) ) {
echo <<<CSS
.no-class {}
.els-titlebar-bg {
  position: relative;
  {$titlebar_bg_url}
  {$titlebar_bg_repeat}
  {$titlebar_bg_position}
  {$titlebar_bg_attachment}
  {$titlebar_bg_size}
  {$titlebar_bg_color}
}
CSS;
}
if ( isset($titlebar_bg_url) || isset($titlebar_bg_color) ) {
echo <<<CSS
.no-class {}
.els-titlebar-bg::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: {$titlebar_bg_overlay};
}
CSS;
}

    /* Page Layout Content Background - Page Metabox/Theme Option - Background */
    $content_layout_bg_meta = (isset($elsey_meta['content_layout_bg'])) ? $elsey_meta['content_layout_bg'] : '';
    $content_layout_bg_pmb  = (isset($content_layout_bg_meta)) ? $content_layout_bg_meta : '';
    $content_layout_bg_pmb_image = isset($content_layout_bg_pmb['image']) ? $content_layout_bg_pmb['image'] : '';
    $content_layout_bg_pmb_color = isset($content_layout_bg_pmb['color']) ? $content_layout_bg_pmb['color'] : '';

    if ($content_layout_bg_pmb_image || $content_layout_bg_pmb_color) {
      $content_layout_bg = $content_layout_bg_pmb;
    } else {
      $content_layout_bg = cs_get_option('content_layout_bg');
    }

    $content_layout_bg_url        = ( ! empty( $content_layout_bg['image'] ) ) ? 'background-image: url('. $content_layout_bg['image'] .');' : ' ';
    $content_layout_bg_repeat     = ( ! empty( $content_layout_bg['repeat'] ) ) ? 'background-repeat: '. $content_layout_bg['repeat'] .';' : 'background-repeat: no-repeat;';
    $content_layout_bg_position   = ( ! empty( $content_layout_bg['position'] ) ) ? 'background-position: '. $content_layout_bg['position'] .';' : 'background-position: center top;';
    $content_layout_bg_attachment = ( ! empty( $content_layout_bg['attachment'] ) ) ? 'background-attachment: '. $content_layout_bg['attachment'] .';' : '';
    $content_layout_bg_size       = ( ! empty( $content_layout_bg['size'] ) ) ? 'background-size: '. $content_layout_bg['size'] .';' : 'background-size: cover;';
    $content_layout_bg_color      = ( ! empty( $content_layout_bg['color'] ) ) ? 'background-color: '. $content_layout_bg['color'] .';' : 'background-color: #ffffff;';

    $content_layout_bg_overlay   = isset($elsey_meta['content_layout_bg_overlay']) ? $elsey_meta['content_layout_bg_overlay'] : cs_get_option('content_layout_bg_overlay');

if ($content_layout_bg_url || $content_layout_bg_color) {
echo <<<CSS
.no-class {}
.els-content-background {
  position: relative;
  {$content_layout_bg_url}
  {$content_layout_bg_repeat}
  {$content_layout_bg_position}
  {$content_layout_bg_attachment}
  {$content_layout_bg_size}
  {$content_layout_bg_color}
}
CSS;
}
if ($content_layout_bg_overlay) {
echo <<<CSS
.no-class {}
.els-content-background::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: {$content_layout_bg_overlay};
}
CSS;
}

// Primary Colors
$all_element_color  = cs_get_customize_option( 'all_element_colors' );
if ($all_element_color) {
echo <<<CSS
	.no-class {}
    .product-template-default .woocommerce-message .button,
    .els-product-summary-col .cart .button,
    .els-sidebar .widget_shopping_cart_content .button,
    .woocommerce-checkout .woocommerce-form-login input[type='submit'],
    .wc-proceed-to-checkout .checkout-button,
    .return-to-shop a,
    .wpcf7 input[type='submit']:hover,
    .els-btn,
    button,
    input[type='submit'],
    .els-icon li .els-cart-count,
    .product-template-default .woocommerce-message .button,
    .line-scale-pulse-out>div,
    .els-product-summary-col .variations .reset_variations:hover,
    .els-sidebar .widget_shopping_cart_content .button:hover,
    .els-sidebar .els-filter-column .price_slider_amount button:hover,
    .woocommerce .els-products-full-wrap.els-shop-masonry .els-product-info .els-product-atc a:hover,
    .els-subs-two input[type='submit']:hover,
    .woocommerce-lost-password .woocommerce-Button:hover,
    .woocommerce-MyAccount-content .woocommerce-EditAccountForm input[type='submit']:hover,
    .woocommerce-account #customer_login input[type='submit']:hover,
    .track_order input[type='submit']:hover,
    .woocommerce-checkout .woocommerce-form-login input[type='submit']:hover,
    .woocommerce-checkout .checkout_coupon .form-row input[type='submit']:hover,
    .wc-proceed-to-checkout .checkout-button:hover,
    .woocommerce-cart .els-update-cart:hover,
    .woocommerce-cart .coupon input[type='submit']:hover,
    .els-product-onsale,
    #review_form .form-submit input[type='submit']:hover,
    .els-product-summary-col .cart .button:hover,
    .tagcloud a:hover,
    .tp-caption .els-custom-btn:hover,
	.tp-caption.rev-btn:hover,
    .els-icon li .els-cart-count,    
    .els-prsc-view-all a:hover .els-parent-dots .els-child-dots,
    .els-prslr .els-prslr-text .els-prslr-shopNow-title a,
    .els-prslr .els-prslr-text .els-prslr-viewNow-title a:hover {background: {$all_element_color};}

    .els-contact-info span a,
    .els-sidebar .widget_products li .product-title:hover,
    .woocommerce .els-products-full-wrap.els-shop-masonry ul.products .els-product-title h3 a:hover,
    .els-pr-single .els-pr-single-cats a:hover,
    .els-pr-single h3 a:hover,
    .woocommerce-NoticeGroup li,
    .woocommerce-account #customer_login p.lost_password a,
    .woocommerce-cart .cart-collaterals .cart_totals table .shipping .woocommerce-shipping-calculator p a,
    .els-blog-slider .els-blog-slider-details li a:hover,
    .woocommerce ul.products .els-product-cats a:hover,
    p.comment-form-notes span,
    .els-product-summary-col .yith-wcwl-add-to-wishlist a:hover,
    .woocommerce div.product .woocommerce-product-rating a:hover,
    .els-sidebar .els-widget li a:hover,
    .els-product-summary-col .product_meta span a:hover,
    a:hover,
    .els-recent-blog-footer h4 a,
    .els-footer .els-footer-widget-area a:hover,
    .els-social li a:hover,
    .els-blog-inner .els-blog-cat-author li a:hover,
    .els-blog-cat-author li a:hover,
    .els-topbar .els-topbar-right li a:hover,
    .els-titlebar-bg .els-titlebar-vertical .els-titlebar-breadcrumb li a:hover,
    .els-titlebar-bg .els-titlebar-breadcrumb li a:hover,
    .els-single-product-share li a:hover,
    .els-prslr .els-prslr-nav.prslr-prev:hover, 
    .els-prslr .els-prslr-nav.prslr-next:hover {color: {$all_element_color};}

    input[type="radio"]:checked + label::after,
    input[type="checkbox"]:checked + label::after,
    .els-sidebar .els-filter-column .price_slider_amount button:hover,
    .tagcloud a:hover,
    .els-prslr .els-prslr-text .els-prslr-shopNow-title a,
    .els-prslr .els-prslr-text .els-prslr-viewNow-title a:hover {border-color: {$all_element_color};}

CSS;
}

// Top Bar Background Color
$topbar_bg_color  = cs_get_customize_option( 'topbar_bg_color' );
if ($topbar_bg_color) {
echo <<<CSS
    .no-class {}
    .els-topbar {background: {$topbar_bg_color};}
CSS;
}

// Top Bar Border Color
$topbar_border_color  = cs_get_customize_option( 'topbar_border_color' );
if ($topbar_border_color) {
echo <<<CSS
    .no-class {}
    .els-topbar .els-topbar-left-text,
    .els-topbar .els-topbar-right li {border-color: {$topbar_border_color};}
CSS;
}

// Top Bar Text Color
$topbar_text_color  = cs_get_customize_option( 'topbar_text_color' );
if ($topbar_text_color) {
echo <<<CSS
    .no-class {}
    .els-topbar .els-topbar-left-text {color: {$topbar_text_color};}
CSS;
}

// Top Link Color
$topbar_link_color  = cs_get_customize_option( 'topbar_link_color' );
if ($topbar_link_color) {
echo <<<CSS
    .no-class {}
    .els-topbar .els-topbar-right li a {color: {$topbar_link_color};}
CSS;
}

// Top link Hover Color
$topbar_link_hover_color  = cs_get_customize_option( 'topbar_link_hover_color' );
if ($topbar_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-topbar .els-topbar-right li a:hover {color: {$topbar_link_hover_color};}
CSS;
}

// Menu Bar Background Color
$menubar_bg_color  = cs_get_customize_option( 'menubar_bg_color' );
if ($menubar_bg_color) {
echo <<<CSS
    .no-class {}
    .els-header,
    .els-fixed-menubar .els-header .els-menubar,
    .els-fixed-menubar .els-header.scrolling .els-menubar {background: {$menubar_bg_color};}
CSS;
}

// Menu link Color
$menubar_mainmenu_link_color  = cs_get_customize_option( 'menubar_mainmenu_link_color' );
if ($menubar_mainmenu_link_color) {
echo <<<CSS
    .no-class {}
    .els-main-menu ul li a,
    .slicknav_nav li a {color: {$menubar_mainmenu_link_color};}
CSS;
}

// Menu link Hover Color
$menubar_mainmenu_link_hover_color  = cs_get_customize_option( 'menubar_mainmenu_link_hover_color' );
if ($menubar_mainmenu_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-main-menu ul>li.current-menu-ancestor>a,
    .els-main-menu ul>li.current-menu-item>a,
    .els-main-menu ul>li.current_page_parent>a,
    .els-main-menu ul li.active a,
    .els-main-menu ul li a:hover,
    .navbar-toggle:hover .icon-bar,
    .slicknav_nav>li.current-menu-ancestor>a,
    .slicknav_nav>li.current-menu-ancestor>a>a,
    .slicknav_nav>li.current-menu-parent>a>a,
    .slicknav_nav>li.current-menu-parent>a,
    .slicknav_nav li.active > a,
    .slicknav_nav li.active > a a,
    .slicknav_nav ul li a:hover,
    .slicknav_nav li a:hover,
    .slicknav_nav li a:hover a,
    .slicknav_nav li li a:hover,
    .slicknav_nav li li.active a,
    .slicknav_nav li li.active li a:hover,
    .els-main-menu ul>li li.current_page_parent>a,
    .slicknav_nav li ul>li.current-menu-parent>a>a,
    .slicknav_nav li ul li li.current-menu-item>a,
    .slicknav_nav li ul li.current-menu-item>a,
    .slicknav_nav li li a:hover a,
    .slicknav_nav li ul>li.current-menu-parent>a {color: {$menubar_mainmenu_link_hover_color};}
CSS;
}

// Sub Menu Background Color
$menubar_submenu_bg_color  = cs_get_customize_option( 'menubar_submenu_bg_color' );
if ($menubar_submenu_bg_color) {
echo <<<CSS
    .no-class {}
    .els-main-menu li.els-megamenu .els-megamenu-wrap,
    .els-main-menu ul > li > ul,
    .els-main-menu ul li ul, .slicknav_nav {background: {$menubar_submenu_bg_color};}
CSS;
}

// Sub Menu link Color
$menubar_submenu_link_color  = cs_get_customize_option( 'menubar_submenu_link_color' );
if ($menubar_submenu_link_color) {
echo <<<CSS
    .no-class {}
    .els-main-menu ul li ul li a,
    .els-main-menu ul>li.current-menu-ancestor li a,
    .els-main-menu ul>li.current_page_parent li a,
    .slicknav_nav li li.active li a,
    .els-main-menu ul li ul li a:link,
    .els-main-menu ul li ul li a:active,
    .els-main-menu ul li ul li a:visited {color: {$menubar_submenu_link_color};}
CSS;
}

// Sub Menu hover Color
$menubar_submenu_link_hover_color  = cs_get_customize_option( 'menubar_submenu_link_hover_color' );
if ($menubar_submenu_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-main-menu ul li ul li.active>a:link,
    .els-main-menu ul li ul li.active>a:active,
    .els-main-menu ul li ul li.active>a:visited,
    .els-main-menu ul li ul li a:hover,
    .els-main-menu ul li ul li.current-menu-item li a:hover,
    .els-main-menu ul li ul li.current-menu-item>a:link,
    .els-main-menu ul li ul li.current-menu-item>a:active,
    .els-main-menu ul li ul li.current-menu-item>a:visited,
    .els-main-menu ul li ul li.current-menu-parent>a:link,
    .els-main-menu ul li ul li.current-menu-parent>a:active,
    .els-main-menu ul li ul li.current-menu-parent>a:visited {color: {$menubar_submenu_link_hover_color};}
CSS;
}

// Transparent Menu link Color and Menu Link Hover Color
if ($elsey_meta) {
  $transparent_menu_color = ($elsey_meta['menubar_trans_main_color']) ? $elsey_meta['menubar_trans_main_color'] : '';
  $transparent_menu_hover_color = ($elsey_meta['menubar_trans_hover_color']) ? $elsey_meta['menubar_trans_hover_color'] : '';
} else {
  $transparent_menu_color = '';
  $transparent_menu_hover_color = '';
}

$transparent_menu_color = ($transparent_menu_color) ? $transparent_menu_color : cs_get_customize_option( 'trans_menubar_mainmenu_link_color' );
$transparent_menu_hover_color = ($transparent_menu_hover_color) ? $transparent_menu_hover_color : cs_get_customize_option( 'trans_menubar_mainmenu_link_hover_color' );

if ($transparent_menu_color) {
echo <<<CSS
  .no-class {}
  .els-trans-menubar .els-main-menu ul li a {color: {$transparent_menu_color};}
CSS;
}

if ($transparent_menu_hover_color) {
echo <<<CSS
  .no-class {}
  .els-trans-menubar .els-main-menu ul>li.current-menu-ancestor>a,
  .els-trans-menubar .els-main-menu ul>li.current-menu-item>a,
  .els-trans-menubar .els-main-menu ul>li.current_page_parent>a,
  .els-trans-menubar .els-main-menu ul li.active a,
  .els-trans-menubar .els-main-menu ul li a:hover,
  .els-trans-menubar .navbar-toggle:hover .icon-bar {color: {$transparent_menu_hover_color};}
CSS;
}

// Transparent Sub Menu Background Color
$trans_menubar_submenu_bg_color  = cs_get_customize_option( 'trans_menubar_submenu_bg_color' );
if ($trans_menubar_submenu_bg_color) {
echo <<<CSS
    .no-class {}
    .els-trans-menubar .els-main-menu li.els-megamenu .els-megamenu-wrap,
    .els-trans-menubar .els-main-menu ul > li > ul {background: {$trans_menubar_submenu_bg_color};}
CSS;
}

// Transparent Sub Menu link Color
$trans_menubar_submenu_link_color  = cs_get_customize_option( 'trans_menubar_submenu_link_color' );
if ($trans_menubar_submenu_link_color) {
echo <<<CSS
    .no-class {}
    .els-trans-menubar .els-main-menu ul li ul li a,
    .els-trans-menubar .els-main-menu ul>li.current-menu-ancestor li a,
    .els-trans-menubar .els-main-menu ul>li.current_page_parent li a,
    .els-trans-menubar .els-main-menu ul li ul li a:link,
    .els-trans-menubar .els-main-menu ul li ul li a:active,
    .els-trans-menubar .els-main-menu ul li ul li a:visited {color: {$trans_menubar_submenu_link_color};}
CSS;
}

// Transparent Sub Menu hover Color
$trans_menubar_submenu_link_hover_color  = cs_get_customize_option( 'trans_menubar_submenu_link_hover_color' );
if ($trans_menubar_submenu_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-trans-menubar .els-main-menu ul li ul li.active>a:link,
    .els-trans-menubar .els-main-menu ul li ul li.active>a:active,
    .els-trans-menubar .els-main-menu ul li ul li.active>a:visited,
    .els-trans-menubar .els-main-menu ul li ul li a:hover,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-item li a:hover,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-item>a:link,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-item>a:active,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-item>a:visited,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-parent>a:link,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-parent>a:active,
    .els-trans-menubar .els-main-menu ul li ul li.current-menu-parent>a:visited {color: {$trans_menubar_submenu_link_hover_color};}
CSS;
}

// Title Bar Background Color
$titlebar_bg_color  = cs_get_customize_option( 'titlebar_bg_color' );
if ($titlebar_bg_color) {
echo <<<CSS
    .no-class {}
    .els-titlebar-bg,
    .els-titlebar-plain {background: {$titlebar_bg_color};}
CSS;
}

// Title text Color
$titlebar_title_color  = cs_get_customize_option( 'titlebar_title_color' );
if ($titlebar_title_color) {
echo <<<CSS
    .no-class {}
    .els-titlebar-plain .page-title,
    .els-titlebar-vertical .page-title {color: {$titlebar_title_color};}
CSS;
}

// Breadcrumbs Text Color
$titlebar_breadcrumbs_color  = cs_get_customize_option( 'titlebar_breadcrumbs_color' );
if ($titlebar_breadcrumbs_color) {
echo <<<CSS
    .no-class {}
    .els-titlebar-bg .els-titlebar-vertical .els-titlebar-breadcrumb li, 
    .els-titlebar-bg .els-titlebar-vertical .els-titlebar-breadcrumb li a,
    .els-titlebar .els-titlebar-breadcrumb li a {color: {$titlebar_breadcrumbs_color};}
CSS;
}

// Body & Content Color
$body_color  = cs_get_customize_option( 'body_color' );
if ($body_color) {
echo <<<CSS
    .no-class {}
    .els-blog-slider .els-blog-slider-details li a,
    .els-blog-slider .els-blog-slider-details li,
    .woocommerce ul.products .els-product-title h3 a:hover,
    .els-blog-share .els-share,
    .els-blog-cat-author li,
    .els-titlebar-bg .els-titlebar-breadcrumb li a,
    .els-titlebar-bg .els-titlebar-breadcrumb li,
    body,
    .els-product-summary-col .product_meta span span,
    .woocommerce div.product .woocommerce-product-rating a,
    .woocommerce-page .quantity .plus:hover,.woocommerce-page .quantity .minus:hover,
    .els-blog-cat-author li a,
    .els-sidebar .els-widget li,
    .els-sidebar .els-widget li a,
    .els-product-summary-col .product_meta span a,
    .els-contact-info a,
    .woocommerce-Tabs-panel .shop_attributes td,
    .woocommerce-cart .cart-collaterals .cart_totals table td,
    .woocommerce .woocommerce-checkout-review-order-table td,
    .wishlist_table .button,
    .els-pr-single .els-pr-single-cats a {color: {$body_color};}
CSS;
}

// Body link Color
$body_links_color  = cs_get_customize_option( 'body_links_color' );
if ($body_links_color) {
echo <<<CSS
    .no-class {}
    a {color: {$body_links_color};}
CSS;
}

// Body link hover Color
$body_link_hover_color  = cs_get_customize_option( 'body_link_hover_color' );
if ($body_link_hover_color) {
echo <<<CSS
    .no-class {}
    a:hover {color: {$body_link_hover_color};}
CSS;
}

// Body link Color
$sidebar_content_color  = cs_get_customize_option( 'sidebar_content_color' );
if ($sidebar_content_color) {
echo <<<CSS
    .no-class {}
    .els-sidebar .els-widget {color: {$sidebar_content_color};}
CSS;
}

// Content Heading Color
$content_heading_color  = cs_get_customize_option( 'content_heading_color' );
if ($content_heading_color) {
echo <<<CSS
    .no-class {}
    h1, h2, h3, h4, h5, h6,
    .els-blog-intro .els-blog-heading,
    .comment .comment-area dl dt, .single .els-content-col dl dt, .comment .comment-area th, .single .els-content-col .els-blog-content table th,
    .comment .comment-area dl dt, .single .els-content-col dl dt, .comment .comment-area th, .single .els-content-col .els-blog-content table th,
    .els-content-col strong {color: {$content_heading_color};}
CSS;
}

// Sidebar Heading Color
$sidebar_heading_color  = cs_get_customize_option( 'sidebar_heading_color' );
if ($sidebar_heading_color) {
echo <<<CSS
    .no-class {}
    .els-sidebar .els-widget .widget-title {color: {$sidebar_heading_color};}
CSS;
}







// Footer Widget Heading Color
$footer_heading_color  = cs_get_customize_option( 'footer_heading_color' );
if ($footer_heading_color) {
echo <<<CSS
    .no-class {}
    .els-footer-widget-area .widget-title {color: {$footer_heading_color};}
CSS;
}

// Footer Widget Text Color
$footer_text_color  = cs_get_customize_option( 'footer_text_color' );
if ($footer_text_color) {
echo <<<CSS
    .no-class {}
    .els-footer,
    .els-recent-blog-footer label {color: {$footer_text_color};}
CSS;
}

// Footer Widget Link Color
$footer_link_color  = cs_get_customize_option( 'footer_link_color' );
if ($footer_link_color) {
echo <<<CSS
    .no-class {}
    .els-footer .els-footer-widget-area a {color: {$footer_link_color};}
CSS;
}

// Footer Widget Link Hover Color
$footer_link_hover_color  = cs_get_customize_option( 'footer_link_hover_color' );
if ($footer_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-footer .els-footer-widget-area a:hover {color: {$footer_link_hover_color};}
CSS;
}

// Copyright Background Color
$copyright_bg_color  = cs_get_customize_option( 'copyright_bg_color' );
if ($copyright_bg_color) {
echo <<<CSS
    .no-class {}
    .els-copyright-bar {background: {$copyright_bg_color};}
CSS;
}

// Copyright text Color
$copyright_text_color  = cs_get_customize_option( 'copyright_text_color' );
if ($copyright_text_color) {
echo <<<CSS
    .no-class {}
     .els-copyright-bar {color: {$copyright_text_color};}
CSS;
}

// Copyright Link Color
$copyright_link_color  = cs_get_customize_option( 'copyright_link_color' );
if ($copyright_link_color) {
echo <<<CSS
    .no-class {}
    .els-copyright-bar a {color: {$copyright_link_color};}
CSS;
}

// Copyright Widget Link Hover Color
$copyright_link_hover_color  = cs_get_customize_option( 'copyright_link_hover_color' );
if ($copyright_link_hover_color) {
echo <<<CSS
    .no-class {}
    .els-copyright-bar a:hover {color: {$copyright_link_hover_color};}
CSS;
}




    // Preloader Color
    $preloader_color    = cs_get_customize_option( 'preloader_color');
    $preloader_bg_color = cs_get_customize_option( 'preloader_bg_color');
    $preloader_options  = cs_get_option('preloader_styles');

    $preloader_color    = isset($preloader_color) ? $preloader_color : '#222222';
    $preloader_bg_color = isset($preloader_bg_color) ? $preloader_bg_color : '#FFFFFF';

echo <<<CSS
.no-class {}
.els-preloader-mask {
    background-color: {$preloader_bg_color};
    height: 100%;
    position: fixed;
    width: 100%;
    z-index: 100000;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    line-height: 0px;
}
#els-preloader-wrap {
    display: table;
    margin: 0 auto;
    top: 50%;
    transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    position: relative;
    line-height: 0px;
}
.els-preloader-html.{$preloader_options} > div {
  background-color: {$preloader_color};
  color: {$preloader_color};
}
CSS;

    echo $elsey_vt_get_typography;
    $output = ob_get_clean();
    return $output;
  }
}

/**
 * Custom Font Family
 */
if ( ! function_exists( 'elsey_custom_font_load' ) ) {
  function elsey_custom_font_load() {
    $font_family       = cs_get_option( 'font_family' );
    ob_start();
    if( ! empty( $font_family ) ) {
      foreach ( $font_family as $font ) {
        echo '@font-face{';
        echo 'font-family: "'. $font['name'] .'";';
        if( empty( $font['css'] ) ) {
          echo 'font-style: normal;';
          echo 'font-weight: normal;';
        } else {
          echo $font['css'];
        }
        echo ( ! empty( $font['ttf']  ) ) ? 'src: url('. esc_url($font['ttf']) .');' : '';
        echo ( ! empty( $font['eot']  ) ) ? 'src: url('. esc_url($font['eot']) .');' : '';
        echo ( ! empty( $font['svg']  ) ) ? 'src: url('. esc_url($font['svg']) .');' : '';
        echo ( ! empty( $font['woff'] ) ) ? 'src: url('. esc_url($font['woff']) .');' : '';
        echo ( ! empty( $font['otf']  ) ) ? 'src: url('. esc_url($font['otf']) .');' : '';
        echo '}';
      }
    }
    // Typography
    $output = ob_get_clean();
    return $output;
  }
}

/* Custom Styles */
if( ! function_exists( 'elsey_vt_custom_css' ) ) {
  function elsey_vt_custom_css() {
    wp_enqueue_style('els-default-style', get_template_directory_uri() . '/style.css');
    $output  = elsey_custom_font_load();
    $output .= elsey_dynamic_styles();
    $output .= cs_get_option( 'theme_custom_css' );
    $custom_css = elsey_compress_css_lines( $output );
    wp_add_inline_style( 'els-default-style', $custom_css );
  }
}

/* Custom JS */
if( ! function_exists( 'elsey_vt_custom_js' ) ) {
  function elsey_vt_custom_js() {
    if ( ! wp_script_is( 'jquery', 'done' ) ) {
      wp_enqueue_script( 'jquery' );
    }
    $output = cs_get_option( 'theme_custom_js' );
    wp_add_inline_script( 'jquery-migrate', $output );
  }
  add_action( 'wp_enqueue_scripts', 'elsey_vt_custom_js' );
}