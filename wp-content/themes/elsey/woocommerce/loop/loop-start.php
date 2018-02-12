<?php
/**
 * VictorTheme Custom Changes - Attribute added for lazy load disable
 */

/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

$data_attr_value = '';
$woo_lazy_load   = cs_get_option('woo_lazy_load');
$woo_dload_size  = cs_get_option('woo_dload_size'); 
$woo_lazy_url    = ELSEY_THEMEROOT_URI.'/inc/plugins/woocommerce/images/lazy-load.jpg';

if ( $woo_lazy_load === 'els-dload-small' ) {
	$data_attr_value.= 'data-dload=els-dload-small';
	$data_attr_value.= ( !empty($woo_dload_size) ) ? ' data-sload='.esc_attr($woo_dload_size).' ' : ' data-sload=767';
	$data_attr_value.= ' data-ldurl='.$woo_lazy_url;
} ?>

<ul class="products" <?php echo esc_attr($data_attr_value); ?>>
