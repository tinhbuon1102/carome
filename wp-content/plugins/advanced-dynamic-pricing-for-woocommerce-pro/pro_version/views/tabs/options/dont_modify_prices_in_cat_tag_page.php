<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php _e( 'Don\'t modify product prices at category/tag pages', 'advanced-dynamic-pricing-for-woocommerce' ) ?></th>
	<td class="forminp forminp-checkbox">
		<fieldset>
			<legend class="screen-reader-text">
				<span><?php _e( 'Don\'t modify product prices at category/tag pages', 'advanced-dynamic-pricing-for-woocommerce' ) ?></span></legend>
			<label for="dont_modify_prices_in_cat_tag_page">
				<input <?php checked( $options['dont_modify_prices_in_cat_tag_page'] ) ?>
					name="dont_modify_prices_in_cat_tag_page" id="dont_modify_prices_in_cat_tag_page" type="checkbox">
			</label>
		</fieldset>
	</td>
</tr>