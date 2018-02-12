<?php
/**
 * VictorTheme Custom Changes - Added slider for related products and added class for default related product layout
 */

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Custom Changes Starts//
global $woocommerce_loop;

if ( $woocommerce_loop['columns'] === 3 ) {
    $shop_col_class = 'woo-col-3';
} else if ( $woocommerce_loop['columns'] === 4 ) {
    $shop_col_class = 'woo-col-4';
} else if ( $woocommerce_loop['columns'] === 5 ) {
    $shop_col_class = 'woo-col-5';
} else if ( $woocommerce_loop['columns'] === 6 ) {
    $shop_col_class = 'woo-col-6';
} else {
    $shop_col_class = 'woo-col-4';
}
// Custom Changes Ends//


if ( $related_products ) :

    // Custom Changes Starts//
    $woo_related_load_style = cs_get_option('woo_related_load_style');  

    if($woo_related_load_style === 'slider') {

        $woo_related_col_number  = ($woocommerce_loop['columns']) ? $woocommerce_loop['columns'] : '4';
        $woo_related_sl_loop     = cs_get_option('woo_related_sl_loop');
        $woo_related_sl_nav      = cs_get_option('woo_related_slider_nav');
        $woo_related_sl_dots     = cs_get_option('woo_related_slider_dots');
        $woo_related_sl_autoplay = cs_get_option('woo_related_slider_autoplay');
         
        $woo_related_sl_loop     = ($woo_related_sl_loop)     ? 'true' : 'false';       
        $woo_related_sl_nav      = ($woo_related_sl_nav)      ? 'true' : 'false';
        $woo_related_sl_dots     = ($woo_related_sl_dots)     ? 'true' : 'false';      
        $woo_related_sl_autoplay = ($woo_related_sl_autoplay) ? 'true' : 'false';  
        
        $data_attr_value = 'data-loop='.$woo_related_sl_loop;
        $data_attr_value.= ' data-nav='.$woo_related_sl_nav;
        $data_attr_value.= ' data-dots='.$woo_related_sl_dots;
        $data_attr_value.= ' data-autoplay='.$woo_related_sl_autoplay;
        $data_attr_value.= ' data-items='.$woo_related_col_number;    ?>

        <section class="related products els-top-arrow">
            
            <h2><?php esc_html_e( 'Related Product', 'elsey' ); ?></h2>
        
            <ul class="products els-related-product-slider owl-carousel" <?php echo esc_attr($data_attr_value); ?>>             

                <?php foreach ( $related_products as $related_product ) : ?>

                    <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object );

                        wc_get_template_part( 'content', 'product' ); 
                    ?>

                <?php endforeach; ?>
                
            </ul>
            
        </section>    

    <?php } else {  // Custom Changes Ends ?>

        <section class="related products els-relateds <?php echo esc_attr($shop_col_class) ?>"><!-- custom class els-relateds and $shop_col_class added -->

            <h2><?php esc_html_e( 'Related Product', 'elsey' ); ?></h2>

            <?php woocommerce_product_loop_start(); ?>

                <?php foreach ( $related_products as $related_product ) : ?>

                    <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object );

                        wc_get_template_part( 'content', 'product' ); 
                    ?>

                <?php endforeach; ?>

            <?php woocommerce_product_loop_end(); ?>

        </section>

    <?php }  // Custom else close

endif;

wp_reset_postdata();
