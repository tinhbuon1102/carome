<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Method_Product_Pricing_Quantity')) {
    require_once('rp-wcdpd-method-product-pricing-quantity.class.php');
}

/**
 * Product Pricing Method: BOGO
 *
 * @class RP_WCDPD_Method_Product_Pricing_Quantity_BOGO
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Method_Product_Pricing_Quantity_BOGO')) {

abstract class RP_WCDPD_Method_Product_Pricing_Quantity_BOGO extends RP_WCDPD_Method_Product_Pricing_Quantity
{
    protected $group_key        = 'bogo';
    protected $group_position   = 40;

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->hook_group();
    }

    /**
     * Get group label
     *
     * @access public
     * @return string
     */
    public function get_group_label()
    {
        return __('Buy / Get', 'rp_wcdpd');
    }

    /**
     * Get price adjusted by rule pricing method
     *
     * @access public
     * @param float $price_to_adjust
     * @param array $rule
     * @return float
     */
    public function adjust_price_by_rule_pricing_method($price_to_adjust, $rule)
    {
        return RP_WCDPD_Pricing::adjust_amount($price_to_adjust, $rule['bogo_pricing_method'], $rule['bogo_pricing_value']);
    }


}
}
