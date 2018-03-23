<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php 
$orders = array($order);
if ($order)
{
	$both_order_preorder_id = get_post_meta( $order->id, '_wc_pre_orders_with_normal', true );
	if ($both_order_preorder_id)
	{
		$both_total_price = get_post_meta( $order->id, '_both_order_total_price', true );
		$pre_order = new WC_Order($both_order_preorder_id);
		$orders = array($order, $pre_order);
	}
}
if (count($orders) > 1) {
	echo '<div class="order__summary__row shipping_fee_message">
 			<span class="big-text both_order_total">'. 
 				sprintf(__('Total payment amount is %s for below orders', 'elsey'), wc_price($both_total_price)) .
 			'</span>
 			</div><hr />';
}
foreach ($orders as $order_index => $order) {
?>
<?php if (count($orders) > 1 && $order_index > 0) {?>
<br /><br />
<hr />
<?php }?>
<div class="woocommerce-order">
<div class="order--details__col row flex-justify-between">
<div class="order--details__summary col-lg-7 col-xs-12">
	<?php if ( $order ) : ?>
	<?php if ( $order->has_status( 'failed' ) ) : ?>
	<div class="section__header os-header">
		<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>
	</div>

	<?php else : ?>
	<div class="order--checkout__form__section">
	<div class="section__header os-header">
		<h2 class="heading heading--xlarge serif">ご注文#<?php echo $order->get_order_number(); ?>を受け付けました。</h2>
			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>
			<?php if (get_post_meta( $order->id, '_wc_pre_orders_is_pre_order', true )) {
				$orderProducts = $order->get_items('line_item');
				$orderProduct = end($orderProducts);
				$product = wc_get_product($orderProduct->get_product_id());
			?>
			<p class="notice-preorder"><?php  printf(__('This order will be delivered on <strong>%s</strong>', 'elsey'), WC_Pre_Orders_Product::get_localized_availability_date_approx( $product ))?></p>
			<?php }?>
		</div>
	</div>

		<div class="order--checkout__review__section woocommerce-order-overview thankyou-order-details order_details">
			<h3 class="heading heading--small"><?php _e( 'ご注文概要', 'woocommerce' ); ?></h3>
			<div class="mini-product__attribute woocommerce-order-overview__order order">
				<span class="label"><?php _e( 'Order number:', 'woocommerce' ); ?></span>
				<span class="value"><?php echo $order->get_order_number(); ?></strong>
			</div>

			<div class="mini-product__attribute woocommerce-order-overview__date date">
				<span class="label"><?php _e( 'Date:', 'woocommerce' ); ?></span>
				<span class="value"><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
			</div>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
				<div class="mini-product__attribute woocommerce-order-overview__email email">
					<span class="label"><?php _e( 'Email:', 'woocommerce' ); ?></span>
					<span class="value"><?php echo $order->get_billing_email(); ?></span>
				</div>
			<?php endif; ?>

			<div class="mini-product__attribute woocommerce-order-overview__total total">
				<span class="label"><?php _e( 'Total:', 'woocommerce' ); ?></span>
				<span class="value"><?php echo $order->get_formatted_order_total(); ?></span>
			</div>

			<?php if ( $order->get_payment_method_title() ) : ?></span>
				<div class="mini-product__attribute woocommerce-order-overview__payment-method method">
					<span class="label"><?php _e( 'Payment method:', 'woocommerce' ); ?>
					<span class="value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
				</div>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php 
	ob_start();
	do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
	$payment_info = ob_get_contents();
	ob_end_clean();
	?>
	
	<?php if (trim($payment_info)) {?>
	<div class="payment-info order--checkout__review__section"><?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?></div>
	<?php }?>
	
	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
	<?php else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
	<?php endif; ?>
</div>
</div>
<?php }?>