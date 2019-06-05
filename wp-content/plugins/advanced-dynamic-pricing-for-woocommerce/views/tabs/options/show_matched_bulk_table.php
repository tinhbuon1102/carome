<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
    <th scope="row" class="titledesc"><?php _e('Show Bulk Table inside Product page', 'advanced-dynamic-pricing-for-woocommerce') ?></th>
    <td class="forminp forminp-checkbox">
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Show Bulk Table inside Product page', 'advanced-dynamic-pricing-for-woocommerce') ?></span></legend>
            <label for="show_matched_bulk_table">
                <input <?php checked( $options['show_matched_bulk_table'] ); ?>
                        name="show_matched_bulk_table" id="show_matched_bulk_table" type="checkbox">
            </label>
			<?php if ($options['show_matched_bulk_table']): ?>
                <a href="<?php echo $product_bulk_table_customizer_url;?>" target="_blank">
					<?php _e( 'Customize', 'advanced-dynamic-pricing-for-woocommerce' ) ?>
                </a>
                  &nbsp; <?php _e( 'or add shortcode [adp_product_bulk_rules_table]', 'advanced-dynamic-pricing-for-woocommerce' ) ?>
			<?php endif; ?>
        </fieldset>
    </td>
</tr>