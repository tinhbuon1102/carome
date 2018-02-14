<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<p class="form-field form-field-wide">
	<label for="customer_user"><?php _e( 'Payment Status:', 'woocommerce' ) ?></label>
	<select id="payment_status" name="payment_status" class="wc-enhanced-select">
		<option value="paid" <?php selected($this->is_paid_order( $order ), '1', true); ?>><?php _e( 'Paid', 'woocommerce' ) ?></option>
		<option value="unpaid" <?php selected($this->is_paid_order( $order ), '0', true); ?>><?php _e( 'Not Paid', 'woocommerce' ) ?></option>
		<option value="partiallypaid" <?php selected($this->is_paid_order( $order ), '2', true); ?>><?php _e( 'Partially Paid', 'woocommerce' ) ?></option>
	</select>
	<input type="hidden" name="order_status_changed" value="0" data-original="<?php echo $order->status ?>" />
</p>