<?php
/**
 * VictorTheme Custom Changes - TH TD Lines Rearranged
 */

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_notices' );
wc_print_notices();
//echo get_current_user_role();
do_action( 'woocommerce_before_cart' ); ?>
<div class="row flex-justify-between">
	<div class="order--cart__col col-md-7 col-xs-12">
		<div class="cart-table-wrapper">
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div id="cart-table" class="cart-table item-list cart woocommerce-cart-form__contents">
		<div class="cart-row-head">
				<div class="section-header product-name item-details"><?php esc_html_e( 'Product', 'elsey' ); ?></div>
				<!--<div class="section-header product-thumbnail">&nbsp;</div><!-- Custom Changes - colspan -->
				<!--<div class="section-header product-price"><?php //esc_html_e( 'Price', 'elsey' ); ?></div>-->
				<div class="section-header product-quantity"><?php esc_html_e( 'Quantity', 'elsey' ); ?></div>
				<div class="section-header product-subtotal"><?php esc_html_e( 'Total', 'elsey' ); ?></div>
		</div>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				global $product;
				$product = $_product;
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->get_permalink( $cart_item ), $cart_item, $cart_item_key );
					?>
					<div class="cart-row woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<!--<div class="cart-cell product-thumbnail">
							
						</div>-->

						<div class="cart-cell product-name item-details" data-title="<?php esc_attr_e( 'Product', 'elsey' ); ?>"> <!-- Custom Changes - colspan -->
							<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail;
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
								}
							?>
							<?php
								if ( ! $product_permalink ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo '<div class="mini-product__info">';
									if (elsey_is_ja_lang())
									{
										echo apply_filters( 'woocommerce_cart_item_name_en', sprintf( '<div class="mini-product__item en-name small-text"><a class="link" href="%s">%s</a></div>', esc_url( $product_permalink ), get_post_meta($cart_item['product_id'], '_custom_product_text_field', true) ), $cart_item_key );
									}
									echo apply_filters( 'woocommerce_cart_item_name_ja', sprintf( 
											'<div class="mini-product__item name"><a class="link" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item );
									echo '</div>';
									
									// Meta data
									echo WC()->cart->get_item_data( $cart_item );
									
									echo '<div class="mini-product__item item-price">';
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									echo showDiscountLabel($_product);
									echo '</div>';
									
									
									
									
									if ( !empty($_product->get_sku()) ) {
									echo '<div class="mini-product__item mini-product__id light-copy">';
									echo '<span class="label">商品番号: </span><span class="value">' . $_product->get_sku() . '</span>';
									echo '</div>';//sku
									}
									echo '</div>';
									
								}

								

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'elsey' ) . '</p>';
								}
							?>
						</div>

						<!--<div class="cart-cell product-price" data-title="<?php //esc_attr_e( 'Price', 'elsey' ); ?>">
							<?php
								//echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</div>-->

						<div class="cart-cell product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'elsey' ); ?>">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->get_max_purchase_quantity(),
										'min_value'   => '0',
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
							<div class="product-list__item__actions order__list__actions">
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove cta cta--underlined product-list__item__action" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;削除</a>',
									esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'elsey' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
							?>
						</div>
						</div>

						<div class="cart-cell product-subtotal" data-title="<?php esc_attr_e( 'Total', 'elsey' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
						</div>

						
						</div>

					<?php
				}
			}
			?>
</div>
			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<div class="actions els-cart-actions">
			    <!-- Custom Changes Start - colspan changed 6 to 8 -->
			
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'elsey' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'elsey' ); ?>" />
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>
					<!-- Custom Changes End -->

					<input type="submit" class="button els-update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'elsey' ); ?>" />

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				
			</div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		
	
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
</div><!--/cart-table-wrapper-->
	</div>
	<div class="order--cart__col col-lg-4 col-md-5 col-xs-12">
		<div class="cart-collaterals">
	<?php
		/**
		 * woocommerce_cart_collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
	 	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>
	</div>
</div><!--/flex-justify-between-->

<?php do_action( 'woocommerce_after_cart' ); ?>
