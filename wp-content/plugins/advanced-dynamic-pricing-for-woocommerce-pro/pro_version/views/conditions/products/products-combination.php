<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wdp-column wdp-condition-subfield wdp-condition-field-combination-method">
    <select name="rule[conditions][{c}][options][0]">
        <option value="combine"><?php _e( 'Combine', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="any"><?php _e( 'Any', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="each"><?php _e( 'Each', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
    </select>
</div>


<div class="wdp-column wdp-condition-subfield wdp-condition-field-value">
    <div>
        <select multiple
                data-list="products"
                data-field="autocomplete"
                data-placeholder="Select values"
                name="rule[conditions][{c}][options][1][]">
        </select>
    </div>
</div>

<div class="wdp-column wdp-condition-subfield wdp-condition-field-method">
    <select name="rule[conditions][{c}][options][2]">
        <option value="<"><?php _e( '&lt;', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="<="><?php _e( '&lt;=', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value=">="><?php _e( '&gt;=', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value=">"><?php _e( '&gt;', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="="><?php _e( '=', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="!="><?php _e( '!=', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
        <option value="in_range"><?php _e( 'in range', 'advanced-dynamic-pricing-for-woocommerce' ) ?></option>
    </select>
</div>

<div class="wdp-column wdp-condition-subfield wdp-condition-field-value">
    <input name="rule[conditions][{c}][options][3]" type="number" placeholder="qty" min="0">
</div>

<div class="wdp-column wdp-condition-subfield wdp-condition-field-value-last" style="display: none">
    <input name="rule[conditions][{c}][options][4]" type="number" placeholder="qty" min="0">
</div>