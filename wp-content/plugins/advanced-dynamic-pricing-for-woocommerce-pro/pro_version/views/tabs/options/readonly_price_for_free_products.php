<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Readonly quantity for free products', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Readonly quantity for free products', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="readonly_price_for_free_products">
                <input <?php checked( $options['readonly_price_for_free_products'] ); ?>
                        name="readonly_price_for_free_products" id="readonly_price_for_free_products" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
