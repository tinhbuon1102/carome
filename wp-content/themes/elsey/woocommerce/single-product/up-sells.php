<?php
/**
 * VictorTheme Custom Changes - Added class for default upsells product layout
 */

/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Custom Changes Starts //
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
// Custom Changes Ends //

if ( $upsells ) : ?>

	<section class="up-sells upsells products els-upsells <?php echo esc_attr($shop_col_class) ?>">  <!-- custom class els-upsells and $shop_col_class added -->

		<h2><?php esc_html_e( 'You May Also Like&hellip;', 'elsey' ) ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
				 	$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();
