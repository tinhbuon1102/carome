<?php 
if (!$waitlist_products)
{
	$user_id = get_current_user_id();
	$waitlist_products = get_user_meta($user_id, woocommerce_waitlist_user, true);
	$waitlist_products = $waitlist_products ? $waitlist_products : array();
}
$show_price = $show_stock_status = $is_user_owner = true;

?>

<?php
if( count( $waitlist_products ) > 0 ) {
$count_row = 0;
	?>
<div id="wishlistList" class="product-list wishlist__list item-list">
<?php
foreach( $waitlist_products as $product_id ) {
$count_row++;
$product = is_numeric($product_id) ? wc_get_product( $product_id ) : $product_id;
$product_id = $product->get_id();
$parent_id = $product->get_parent_id();
$parent_id = $parent_id ? $parent_id : $product_id;

$availability = $product->get_availability();
$stock_status = $availability['class'];

if( $product && $product->exists() ) : ?>
                    <div class="product-list__item wishlist__item item" data-row-id="<?php echo esc_attr($product_id); ?>">
						<div class="wishlist__item__details mini-product--group">
							<a class="mini-product__link" href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $product_id ) ) ) ?>">
								<?php echo $product->get_image() ?>
							</a>
							<div class="mini-product__info">
								<?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?>
								 <?php if( $show_stock_status ) : ?>
								<div class="mini-product__item mini-product__stock display--small-only">
								<span class="label">Stock: </span><?php echo $stock_status == 'out-of-stock' ? '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>' : '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'elsey' ) . '</span>'; ?>
								</div>
								<?php endif ?>
								<?php if( $show_price ) : ?>
								<div class="mini-product__item mini-product__price display--small-only">
									<span class="label">Price: </span><?php echo $product->get_price_html(); ?>
								</div>
							<?php endif ?>
							</div><!--/mini-product__info-->
						</div><!--/mini-product--group-->
						<div class="wishlist__item__extras item-dashboard">
							<?php if( $show_stock_status ) : ?>
                            <div class="wishlist__item__extras__col display--small-up" title="Stock: ">
                                <?php echo $stock_status == 'out-of-stock' ? '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>' : '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'elsey' ) . '</span>'; ?>
                            </div>
							<?php endif ?>
							<?php if( $show_price ) : ?>
							<div class="wishlist__item__extras__col display--small-up" title="Price: ">
								<?php echo $product->get_price_html(); ?>
							</div>
							<?php endif ?>
						
						<div class="wishlist__item__extras__actions align--right">
							<?php if( $is_user_owner ): ?>
                            <div class="product-list__item__actions">
                                    <a href="<?php echo esc_url( add_query_arg( array('woocommerce_waitlist' => $product_id, 'woocommerce_waitlist_action' => 'leave', 'waitlist' => 1, 'wc-ajax' => 'get_variation', 'product_id' => $parent_id) ) ) ?>" class="remove waitlist_remove_product product-list__item__action cta cta--underlined" title="<?php esc_html_e( 'Remove this product', 'elsey' ) ?>"><?php esc_html_e( '再入荷通知をキャンセル', 'elsey' ) ?></a>
                            </div>
                        <?php endif; ?>
						</div><!--/wishlist__item__extras__actions-->
						</div><!--/wishlist__item__extras-->
						
					</div>
                <?php endif; }?>
	</div>
        <?php } else{ ?>
            <div class="wishlist-empty align--center">
                <h2 class="ja heading--xlarge spacing--normal"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', esc_html__( '再入荷待ちリクエストの商品はありません。', 'elsey' ) ) ?></h2>
				<p class="wishlist_desc"><?php esc_html_e( '売り切れ商品の再入荷通知をご希望のお客様は商品ページにて「再入荷通知登録」ボタンからご登録されますと、再入荷した際にメールにて通知が受け取れます。', 'elsey' ) ?></p>
				<p class="return-to-shop">
					<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Return to shop', 'elsey' ) ?></a>
				</p>
            </div>
        <?php
}
        ?>
		
