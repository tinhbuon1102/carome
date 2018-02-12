<?php
/**
 * VictorTheme Custom Changes - H2 to H4
 */

/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
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
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $order = wc_get_order( $order_id ) ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
}

?>


	
		
	
<?php if (!is_wc_endpoint_url( 'order-received' )) { ?><div class="order--details__col row flex-justify-between">
		<div class="order--details__items product-list col-lg-7 col-xs-12">
			<?php
				foreach ( $order_items as $item_id => $item ) {
					$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

					wc_get_template( 'order/order-details-item.php', array(
						'order'			     => $order,
						'item_id'		     => $item_id,
						'item'			     => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'	     => $product ? $product->get_purchase_note() : '',
						'product'	         => $product,
					) );
				}
			?>
			<?php do_action( 'woocommerce_order_items_table', $order ); ?>
		</div>
		
		<div class="order--details__summary col-lg-5 col-xs-12">
			<div class="order__summary__contents order-totals-table">
				<p class="order__summary__row heading heading--small"><?php esc_html_e( 'CART TOTAL', 'elsey' ); ?></p>
				
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					// The condition to replace "Total:" text in english and german
                if( $total['label'] == 'Total:' || $total['label'] == '合計金額:') {
					$total_class = 'order__summary__totals';
					$total_wrap_start = '<div class="order__summary__row order-total">';
					$total_wrap_end = '</div>';
				} else {
					$total_class = 'order__summary__row';
					$total_wrap_start = '';
					$total_wrap_end = '';
				}
                // End of the condition
					?>
					<div class="<?php echo $total_class ?>">
						<?php echo $total_wrap_start ?>
						<span class="label"><?php echo $total['label']; ?></span>
						<span class="value"><?php echo $total['value']; ?></span>
						<?php echo $total_wrap_end ?>
					</div>
					<?php
				}
			?>
				
			</div>
			<?php if ( $order->get_customer_note() ) : ?>
				<div class="order-note">
					<p class="heading heading--small"><?php esc_html_e( 'Order note', 'elsey' ); ?></p>
					<span class="note-value"><?php echo wptexturize( $order->get_customer_note() ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ($other_details = get_post_meta( $order->id, 'Other Details', true ) ) : ?>
			<div class="order-others">
				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</div>
			<?php endif; ?>
			<?php
if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
?>
		</div>
		
	</div>
<?php } else { ?>
		
		<!--<div class="order--details__summary col-lg-5 col-xs-12">-->
			
			<?php if ( $order->get_customer_note() ) : ?>
				<div class="order--checkout__review__section order-note">
					<h3 class="heading heading--small"><?php esc_html_e( 'Order note', 'elsey' ); ?></h3>
					<span class="note-value"><?php echo wptexturize( $order->get_customer_note() ); ?></span>
				</div>
			<?php endif; ?>
<?php if ($other_details = get_post_meta( $order->id, 'Other Details', true ) ) : ?>
			<div class="order--checkout__review__section order-others">
				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</div>
<?php endif; ?>
			<?php
if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
?>
		</div>
<div class="order--details__items product-list col-lg-4 col-md-5 col-xs-12">
	<h2 class="heading heading--xlarge serif"><?php esc_html_e( 'ご注文情報', 'elsey' ); ?></h2>
	<div class="order__summary order--checkout__summary">
	<div class="checkout-mini-cart">
			<?php
				foreach ( $order_items as $item_id => $item ) {
					$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

					wc_get_template( 'order/order-details-item.php', array(
						'order'			     => $order,
						'item_id'		     => $item_id,
						'item'			     => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'	     => $product ? $product->get_purchase_note() : '',
						'product'	         => $product,
					) );
				}
			?>
			<?php do_action( 'woocommerce_order_items_table', $order ); ?>
		</div>
	<div class="order__summary__contents order-totals-table">
				<p class="order__summary__row heading heading--small"><?php esc_html_e( 'CART TOTAL', 'elsey' ); ?></p>
				
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					// The condition to replace "Total:" text in english and german
                if( $total['label'] == 'Total:' || $total['label'] == '合計金額:') {
					$total_class = 'order__summary__totals';
					$total_wrap_start = '<div class="order__summary__row order-total">';
					$total_wrap_end = '</div>';
				} else {
					$total_class = 'order__summary__row';
					$total_wrap_start = '';
					$total_wrap_end = '';
				}
                // End of the condition
					?>
					<div class="<?php echo $total_class ?>">
						<?php echo $total_wrap_start ?>
						<span class="label"><?php echo $total['label']; ?></span>
						<span class="value"><?php echo $total['value']; ?></span>
						<?php echo $total_wrap_end ?>
					</div>
					<?php
				}
			?>
				
			</div>
	</div>
</div>
		

<?php } ?>