<?php
/**
 * Single variation cart button
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$available_variations = $product->get_available_variations();
$iOutStock = 0;
foreach ($available_variations as $indexVariation => $available_variation)
{
	$iOutStock = $iOutStock + ($available_variation['is_in_stock'] ? 1 : 0);
}
?>

<div class="woocommerce-variation-add-to-cart variations_button <?php if ( $iOutStock == 0 ) : ?>soldout_disabled<?php endif; ?>">
	<?php
		/**
		 * @since 3.0.0.
		 */
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
		) );

		/**
		 * @since 3.0.0.
		 */
		do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>
	
	<div class="pdp__actions product-add-to-cart">
	<div class="input-list">
	<button type="submit" class="input-list--item single_add_to_cart_button button--primary button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
	<?php echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?>
	</div>
	</div>
</div>

<div class="wcwl_control" id="woocommerce_waitlist_wraper" style="display: none;">
	<a href="javascript:void(0)" class="button alt woocommerce_waitlist_new join"><?php echo __('Join waitlist', 'elsey')?></a>
</div>