<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="cart_item_sorting"><?php _e('Cart items sorting', 'advanced-dynamic-pricing-for-woocommerce') ?></label>
    </th>
    <td class="forminp">
        <select name="cart_item_sorting">
            <option <?php selected( $options['cart_item_sorting'], 'no' ); ?> value="no"><?php _e('Do not sort', 'advanced-dynamic-pricing-for-woocommerce') ?></option>
            <option <?php selected( $options['cart_item_sorting'], 'free_product_at_bottom' ); ?> value="free_product_at_bottom"><?php _e('Free products at bottom', 'advanced-dynamic-pricing-for-woocommerce')?></option>
            <option <?php selected( $options['cart_item_sorting'], 'group_by_product' ); ?> value="group_by_product"><?php _e('Group by product', 'advanced-dynamic-pricing-for-woocommerce')?></option>
            <option <?php selected( $options['cart_item_sorting'], 'group_by_variation' ); ?> value="group_by_variation"><?php _e('Group by variation', 'advanced-dynamic-pricing-for-woocommerce')?></option>
        </select>
    </td>
</tr>
