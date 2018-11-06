<?php

/* This snippet removes the action that inserts thumbnails to products in teh loop
 * and re-adds the function customized with our wrapper in it.
 * It applies to all archives with products.
 *
 * @original plugin: WooCommerce
 * @author of snippet: Brian Krogsard
 */

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

add_action( 'woocommerce_before_shop_loop_item_title', 'wooqv_template_loop_before_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'wooqv_template_loop_after_product_thumbnail', 10);

add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);


if ( ! function_exists( 'wooqv_template_loop_before_product_thumbnail' ) ) {

	function wooqv_template_loop_before_product_thumbnail() {
		
		echo '<div class="wooqv-image-wrapper">';
		do_action('wooqv_before_product_image');
	} 
}

if ( ! function_exists( 'wooqv_template_loop_after_product_thumbnail' ) ) {

	function wooqv_template_loop_after_product_thumbnail() {
		
		do_action('wooqv_after_product_image');
		echo '</div>';
	} 
}
