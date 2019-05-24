<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Show Striked Prices at Product page', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Show Striked Prices at Product page', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="show_striked_prices_product_page">
                <input <?php checked( $options['show_striked_prices_product_page'] ); ?>
                        name="show_striked_prices_product_page" id="show_striked_prices_product_page" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
