<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Update price when user changes qty', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Update price when user changes qty', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="update_price_with_qty">
                <input <?php checked( $options['update_price_with_qty'] ); ?>
                        name="update_price_with_qty" id="update_price_with_qty" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
