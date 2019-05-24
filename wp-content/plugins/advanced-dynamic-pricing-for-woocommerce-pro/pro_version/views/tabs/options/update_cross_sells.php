<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Update cross-sells in the cart', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Update cross-sells in the cart', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="update_cross_sells">
                <input <?php checked( $options['update_cross_sells'] ); ?>
                        name="update_cross_sells" id="update_cross_sells" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>