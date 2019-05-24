<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Calculate "On Sale" badge for variable products', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Calculate "On Sale" badge for variable products', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="show_onsale_badge_for_variable">
                <input <?php checked( $options['show_onsale_badge_for_variable'] ); ?>
                        name="show_onsale_badge_for_variable" id="show_onsale_badge_for_variable" type="checkbox">
            </label>
        </fieldset>
    </td>
</tr>
