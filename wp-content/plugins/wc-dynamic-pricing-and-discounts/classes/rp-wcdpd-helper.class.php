<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin specific methods used by multiple classes
 *
 * @class RP_WCDPD_Helper
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Helper')) {

class RP_WCDPD_Helper
{

    /**
     * Filter out bundle cart items
     *
     * @access public
     * @param array $cart_items
     * @return array
     */
    public static function filter_out_bundle_cart_items($cart_items)
    {
        // Remove bundle items
        if (is_array($cart_items)) {
            foreach ($cart_items as $cart_item_key => $cart_item) {
                if (RP_WCDPD_Helper::cart_item_is_bundle($cart_item)) {
                    unset($cart_items[$cart_item_key]);
                    break;
                }
            }
        }

        // Return filtered items
        return $cart_items;
    }

    /**
     * Cart item is product bundle
     *
     * @access public
     * @param array $cart_item
     * @return bool
     */
    public static function cart_item_is_bundle($cart_item)
    {
        // Flags to use
        // Note: developers need to return array('bundled_by') to reverse the operation
        // for the "official" Product Bundles plugin, issues #359 and #437
        $flags = apply_filters('rp_wcdpd_bundle_cart_item_filter_flags', array('bundled_items'));

        // Check if cart item is product bundle
        foreach ($flags as $flag) {
            if (isset($cart_item[$flag])) {
                return true;
            }
        }

        // Not bundle
        return false;
    }

    /**
     * Multiple tax classes are set up
     *
     * @access public
     * @return bool
     */
    public static function wc_has_multiple_tax_classes()
    {
        $count = 0;

        // Get all tax classes
        $tax_classes = WC_Tax::get_tax_classes();

        // Add default tax class
        if (!in_array('', $tax_classes, true)) {
            $tax_classes[] = '';
        }

        // Count tax classes that have at least one tax rate added
        foreach ($tax_classes as $tax_class) {
            if (WC_Tax::get_rates_for_tax_class($tax_class)) {
                $count++;
            }
        }

        return $count > 1;
    }









}
}
