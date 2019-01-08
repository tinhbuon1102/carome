<?php
/**
 * VictorTheme Custom Changes - Some Label Change
 */

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div id="order_review" class="order__summary order--checkout__summary woocommerce-checkout-review-order-table">
	<div class="checkout-mini-cart">
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<div class="minicart__product mini-product--group <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<?php 
							$is_visible        = $_product && $_product->is_visible();
							$product_permalink = get_permalink($_product->id);
							?>
						
						<?php //echo apply_filters( 'woocommerce_order_item_thumbnail', '<a class="image-wraper" href="'.$product_permalink.'"><img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), 'thumbnail' ) ) : wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Product image', 'woocommerce' ) . '" height="' . esc_attr( $image_size[1] ) . '" width="' . esc_attr( $image_size[0] ) . '" style="vertical-align:middle; margin-' . ( is_rtl() ? 'left' : 'right' ) . ': 10px;" /></a>', $cart_item ); ?>
						<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail;
								} else {
									printf( '<a class="mini-product__link" href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
								}
							?>
						
						<div class="mini-product__info">
							<?php 
							if (elsey_is_ja_lang())
							{
								echo apply_filters( 'woocommerce_cart_item_name_en', sprintf( '<div class="mini-product__item en-name small-text"><a class="link" href="%s">%s</a></div>', esc_url( $product_permalink ), get_post_meta($cart_item['product_id'], '_custom_product_text_field', true) ), $cart_item_key );
							}
							?>
							<div class="mini-product__item name"><?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?></div>
							<div class="mini-product__item mini-product__attribute"><?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', '<span class="cart-label">数量: </span><span class="value">' . sprintf( '%s', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); ?></div>
							<div class="mini-product__item getitemdata"><?php echo WC()->cart->get_item_data( $cart_item ); ?></div>
							<div class="mini-product__item item-totalprice"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></div>
						</div>
					</div>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div>
	<div class="checkout-order-totals">
	<div class="order__summary__contents order-totals-table">
		<p class="order__summary__row heading heading--small"><?php esc_html_e( 'CART TOTAL', 'elsey' ); ?></p>
		<div class="order__summary__row order-subtotal">
			<span class="label-cart"><?php esc_html_e( 'Cart Subtotal', 'elsey' ); ?></span>
			<span class="value lato"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div><!--/order-subtotal-->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="order__summary__row order-discount">
			<span class="label-cart"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
			<span class="value lato"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div><!--/order-discount-->
		<?php endforeach; ?>
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="order__summary__row order-fee">
			<span class="label-cart"><?php echo esc_html( $fee->name ); ?></span>
			<span class="value lato"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div><!--/order-fee-->
		<?php endforeach; ?>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

					<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
		
					<?php wc_cart_totals_shipping_html(); ?>
		
					<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		
				<?php endif; ?>
		<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
		            <div class="order__summary__row tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<span class="label-cart"><?php echo esc_html( $tax->label ); ?></span>
						<span class="value lato"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="order__summary__row tax-total">
					<span class="label-cart"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="value lato"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		<div class="order__summary__totals">
			<div class="order__summary__row order-total">
				<span class="label-cart"><?php esc_html_e( 'Order Total', 'elsey' ); ?></span>
				<span class="value lato bigger order-value"><?php wc_cart_totals_order_total_html(); ?></span>
			</div>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</div>
</div><!--/checkout-order-totals-->
</div>
