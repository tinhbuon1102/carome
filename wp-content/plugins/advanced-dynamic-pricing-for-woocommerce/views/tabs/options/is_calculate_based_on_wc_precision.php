<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Calculate totals based on modified item price', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Calculate totals based on modified item price', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="is_calculate_based_on_wc_precision">
                <input <?php checked( $options['is_calculate_based_on_wc_precision'] ); ?>
                        name="is_calculate_based_on_wc_precision" id="is_calculate_based_on_wc_precision" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
