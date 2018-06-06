<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}

$is_free_gift = isFreeGiftOrderProduct($order, $product);
?>
<div class="product-list__item line-item">
	<div class="mini-product--group">
		<?php
			$is_visible        = $product && $product->is_visible();

			if ($is_free_gift)
			{
				$product_permalink = '';
				echo apply_filters( 'woocommerce_order_item_thumbnail', '<img src="' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), 'shop_thumbnail' ) ) : wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Product image', 'woocommerce' ) . '" class="mini-product__img" />', $item );
			}
			else {
				$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
				echo apply_filters( 'woocommerce_order_item_thumbnail', '<a class="mini-product__link" href="'.$product_permalink.'"><img src="' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), 'shop_thumbnail' ) ) : wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Product image', 'woocommerce' ) . '" class="mini-product__img" /></a>', $item );
			}
		?>
	<div class="mini-product__info">
	<p class="mini-product__item mini-product__name heading heading--small">
		<?php 
		if ($is_free_gift)
		{
			echo $item->get_name();
		}
		else {
			echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s" class="link">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ); 
		}?>
	</p>

	<div class="line-item-quantity mini-product__attribute mini-product__item">
		<span class="label"><?php esc_html_e( 'Quantity', 'elsey' ); ?>: </span>
		<span class="value lato"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', '' . sprintf( '%s', $item->get_quantity() ) . '', $item ); ?></span>
	</div>
	<?php
	do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

	wc_display_item_meta( $item );

	do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
	?>

	<div class="mini-product__item txt--bold serif">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</div>
	<?php
	
	if ($is_free_gift)
	{
		echo get_option('wc_free_gift_message_thanks');
	}
	elseif ( !empty($product->get_sku()) ) {
									echo '<div class="mini-product__item mini-product__id light-copy">';
									echo '<span class="label">商品番号: </span><span class="value">' . $product->get_sku() . '</span>';
									echo '</div>';//sku
									}
		?>
	<?php if ( $show_purchase_note && $purchase_note ) : ?>
	<div class="mini-product__item product-purchase-note"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></div>
	<?php endif; ?>
	</div><!--/mini-product__info-->
	</div><!--/mini-product--group-->
</div>

