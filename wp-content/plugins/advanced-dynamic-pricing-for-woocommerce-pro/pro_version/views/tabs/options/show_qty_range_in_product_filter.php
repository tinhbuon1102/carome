<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Show quantity range in "Product Filters"', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Show quantity range in "Product Filters"', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="show_qty_range_in_product_filter">
                <input <?php checked( $options['show_qty_range_in_product_filter'] ); ?>
                        name="show_qty_range_in_product_filter" id="show_qty_range_in_product_filter" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
