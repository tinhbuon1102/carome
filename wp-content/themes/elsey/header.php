<?php
/*
 * The header for our theme.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */
?>
<!DOCTYPE html>
<!--[if IE 8]><html <?php language_attributes(); ?> class="ie8"><![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php
// if the `wp_site_icon` function does not exist (ie we're on < WP 4.3)
if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
  if (cs_get_option('brand_fav_icon')) {
    echo '<link rel="shortcut icon" href="'. esc_url(wp_get_attachment_url(cs_get_option('brand_fav_icon'))) .'" />';
  } else { ?>
    <link rel="shortcut icon" href="<?php echo esc_url(ELSEY_IMAGES); ?>/favicon.png" />
  <?php }
  if (cs_get_option('iphone_icon')) {
    echo '<link rel="apple-touch-icon" sizes="57x57" href="'. esc_url(wp_get_attachment_url(cs_get_option('iphone_icon'))) .'" >';
  }
  if (cs_get_option('iphone_retina_icon')) {
    echo '<link rel="apple-touch-icon" sizes="114x114" href="'. esc_url(wp_get_attachment_url(cs_get_option('iphone_retina_icon'))) .'" >';
    echo '<link name="msapplication-TileImage" href="'. esc_url(wp_get_attachment_url(cs_get_option('iphone_retina_icon'))) .'" >';
  }
  if (cs_get_option('ipad_icon')) {
    echo '<link rel="apple-touch-icon" sizes="72x72" href="'. esc_url(wp_get_attachment_url(cs_get_option('ipad_icon'))) .'" >';
  }
  if (cs_get_option('ipad_retina_icon')) {
    echo '<link rel="apple-touch-icon" sizes="144x144" href="'. esc_url(wp_get_attachment_url(cs_get_option('ipad_retina_icon'))) .'" >';
  }
} 
  $elsey_all_element_color = cs_get_customize_option( 'all_element_colors' ); ?>
  <meta name="msapplication-TileColor" content="<?php echo esc_attr($elsey_all_element_color); ?>">
  <meta name="theme-color" content="<?php echo esc_attr($elsey_all_element_color); ?>">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php
  // Metabox Options
  global $post;
  $elsey_id   = ( isset( $post ) ) ? $post->ID : false;
  $elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
  $elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
  $elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

  if ($elsey_meta) {
    $elsey_topbar_options   = $elsey_meta['topbar_options'];
    $elsey_menubar_options  = $elsey_meta['menubar_options'];
    $elsey_titlebar_options = $elsey_meta['titlebar_options'];
    $elsey_hide_header      = $elsey_meta['hide_header'];

    if ($elsey_topbar_options === 'custom') {
      $elsey_topbar_show = true;
    } elseif ($elsey_topbar_options === 'hide') {
      $elsey_topbar_show = false;
    } else {
      $elsey_topbar_show = cs_get_option('need_topbar');
    }

    if ($elsey_menubar_options === 'custom') {
      $elsey_menubar_show      = true;
      $elsey_menubar_search    = $elsey_meta['menubar_search'];
      $elsey_menubar_wishlist  = $elsey_meta['menubar_wishlist'];
      $elsey_menubar_cart      = $elsey_meta['menubar_cart'];
      $elsey_menubar_rightmenu = $elsey_meta['menubar_rightmenu'];
      $elsey_menubar_sticky    = $elsey_meta['menubar_sticky'];
      $elsey_menubar_trans     = $elsey_meta['menubar_transparent'];
      $elsey_menubar_border    = $elsey_meta['menubar_bottom_border_color'];
      $elsey_menubar_trans_color = $elsey_meta['menubar_trans_main_color'];
    } elseif ($elsey_menubar_options === 'hide') {
      $elsey_menubar_show      = false;
      $elsey_menubar_search    = false;
      $elsey_menubar_wishlist  = false;
      $elsey_menubar_cart      = false;
      $elsey_menubar_rightmenu = false;
      $elsey_menubar_sticky    = false;
      $elsey_menubar_trans     = false;
      $elsey_menubar_border    = '';
      $elsey_menubar_trans_color = '';
    } else {
      $elsey_menubar_show      = cs_get_option('need_menubar');
      $elsey_menubar_search    = cs_get_option('menubar_search');
      $elsey_menubar_wishlist  = cs_get_option('menubar_wishlist');
      $elsey_menubar_cart      = cs_get_option('menubar_cart');
      $elsey_menubar_rightmenu = cs_get_option('menubar_rightmenu');
      $elsey_menubar_sticky    = cs_get_option('menubar_sticky');
      $elsey_menubar_trans     = false;
      $elsey_menubar_border    = '';
      $elsey_menubar_trans_color = '';
    }

    if ($elsey_titlebar_options === 'custom') {
      $elsey_titlebar_show = true;
    } elseif ($elsey_titlebar_options === 'hide') {
      $elsey_titlebar_show = false;
    } else {
      $elsey_titlebar_show = cs_get_option('need_titlebar');
    }
  } else {
    $elsey_hide_header       = false;
    $elsey_topbar_show       = cs_get_option('need_topbar');
    $elsey_menubar_show      = cs_get_option('need_menubar');
    $elsey_titlebar_show     = cs_get_option('need_titlebar');
    $elsey_menubar_search    = cs_get_option('menubar_search');
    $elsey_menubar_wishlist  = cs_get_option('menubar_wishlist');
    $elsey_menubar_cart      = cs_get_option('menubar_cart');
    $elsey_menubar_rightmenu = cs_get_option('menubar_rightmenu');
    $elsey_menubar_sticky    = cs_get_option('menubar_sticky');
    $elsey_menubar_trans     = false;
    $elsey_menubar_border    = '';
    $elsey_menubar_trans_color  = '';
  }

  $elsey_sticky_header_cls = ($elsey_menubar_sticky) ? 'els-fixed-menubar' : 'els-normal-menubar';
  $elsey_transparent_class = ($elsey_menubar_trans) ? 'els-trans-menubar' : '';
  $elsey_transparent_class.= ($elsey_menubar_trans_color) ? ' els-trans-other-color' : '';
  $elsey_bottom_border     = ($elsey_menubar_border) ? '-webkit-box-shadow:0px 1px 1px '.$elsey_menubar_border.';-moz-box-shadow:0px 1px 1px '.$elsey_menubar_border.';box-shadow:0px 1px 1px '.$elsey_menubar_border.';' : '';

  wp_head(); ?>

</head>
<body <?php body_class(); ?>>
  <?php
  if (!$elsey_hide_header && $elsey_menubar_show && $elsey_menubar_search) {
    if (function_exists('elsey_search_modal')) { echo elsey_search_modal(); } else { echo ''; }
  }
  if (!$elsey_hide_header && $elsey_menubar_show && $elsey_menubar_rightmenu) {
   if (function_exists('elsey_right_slide_menu')) { echo elsey_right_slide_menu(); } else { echo ''; }
  } ?>
  <!-- Elsey Wrap Start -->
  <div id="els-wrap" class="<?php echo esc_attr($elsey_sticky_header_cls.' '.$elsey_transparent_class); ?>">

    <?php if (!$elsey_hide_header) { ?>
    <header class="els-header" style="<?php echo esc_attr($elsey_bottom_border); ?>">
      <?php if ($elsey_topbar_show) { /*Top Bar*/ get_template_part( 'layouts/header/top', 'bar' ); }
      if ($elsey_menubar_show) { /*Menu Bar*/ get_template_part( 'layouts/header/menu', 'bar' ); } ?>
    </header>
    <?php } ?>

    <!-- Elsey Wrapper Start -->
    <div class="els-wrapper">
      <?php if ($elsey_titlebar_show) { /*Title Bar*/ get_template_part( 'layouts/header/title', 'bar' ); } ?>
      <div class="els-content-background">
