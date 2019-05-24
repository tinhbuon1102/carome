<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Show Striked Prices in the Order details and Emails', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Show Striked Prices in the Order details and Emails', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="show_striked_prices_in_order">
                <input <?php checked( $options['show_striked_prices_in_order'] ); ?>
                        name="show_striked_prices_in_order" id="show_striked_prices_in_order" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
