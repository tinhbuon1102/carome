<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Method')) {
    require_once('rp-wcdpd-method.class.php');
}

/**
 * Product Pricing Method
 *
 * @class RP_WCDPD_Method_Product_Pricing
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Method_Product_Pricing')) {

abstract class RP_WCDPD_Method_Product_Pricing extends RP_WCDPD_Method
{
    protected $context = 'product_pricing';

    /**
     * Group quantities of matching cart items
     *
     * @access public
     * @param array $cart_items
     * @param array $rule
     * @return array
     */
    public function group_quantities($cart_items, $rule)
    {
        $quantities = array();

        // Get Quantities Based On method
        $based_on = $rule['quantities_based_on'];

        // Filter out cart items that are not affected by this rule so we don't count them
        $cart_items = RP_WCDPD_Product_Pricing::filter_items_by_rules($cart_items, array($rule));

        // Iterate over cart items
        foreach ($cart_items as $cart_item_key => $cart_item) {

            // Get quantity
            $quantity = RP_WCDPD_Helper::cart_item_is_bundle($cart_item) ? 0 : $cart_item['quantity'];

            // Get absolute product id (i.e. parent product id for variations)
            $product_id = RightPress_Help::get_wc_product_absolute_id($cart_item['data']);

            // Individual Products - Each individual product
            // Individual Products - Each individual variation (variation not specified)
            if ($based_on === 'individual__product' || ($based_on === 'individual__variation' && empty($cart_item['variation_id']))) {
                $quantities[$product_id][$cart_item_key] = $quantity;
            }

            // Individual Products - Each individual variation (variation specified)
            else if ($based_on === 'individual__variation') {
                $quantities[$cart_item['variation_id']][$cart_item_key] = $quantity;
            }

            // Individual Products - Each individual cart line item
            else if ($based_on === 'individual__configuration') {
                $quantities[$cart_item_key][$cart_item_key] = $quantity;
            }

            // All Matched Products - Quantities added up by category
            else if ($based_on === 'cumulative__categories') {

                // Get category ids
                $categories = RightPress_Help::get_wc_product_category_ids_from_product_ids(array($product_id));

                // Iterate over categories and add quantities
                foreach ($categories as $category_id) {
                    $quantities[$category_id][$cart_item_key] = $quantity;
                }
            }

            // All Matched Products - All quantities added up
            else if ($based_on === 'cumulative__all') {
                $quantities['_all'][$cart_item_key] = $quantity;
            }
        }

        // Return quantities
        return $quantities;
    }

    /**
     * Get reference amount
     *
     * @access public
     * @param array $adjustment
     * @param float $base_amount
     * @param int $quantity
     * @param object $product
     * @param array $cart_item
     * @return mixed
     */
    public function get_reference_amount($adjustment, $base_amount = null, $quantity = 1, $product = null, $cart_item = null)
    {
        // Get rule selection method
        $selection_method = RP_WCDPD_Settings::get($this->context . '_rule_selection_method');

        // Calculate reference amount
        if (in_array($selection_method, array('smaller_price', 'bigger_price'), true)) {

            // Generate prices array
            $prices = RP_WCDPD_Controller_Methods_Product_Pricing::generate_prices_array($base_amount, $quantity);

            // Apply adjustment to prices
            // Note: $cart_item_key must NOT be set as a third param here as some methods treat real apply_adjustment_to_prices() calls and
            // calls from get_reference_amount() differently and this is determined by the presence of $cart_item_key param
            $prices = $this->apply_adjustment_to_prices($prices, $adjustment);

            // Get adjusted amount
            $adjusted_amount = RP_WCDPD_Controller_Methods_Product_Pricing::get_price_from_prices_array($prices, $base_amount, $product, $cart_item);

            // Calculate reference amount
            return (float) ($base_amount - $adjusted_amount);
        }
        // Reference amount is not needed
        else {
            return null;
        }
    }

    /**
     * Apply adjustment to prices
     *
     * Note: $cart_item_key is set for requests from get_change_set()
     * and not set for requests from get_reference_amount()
     *
     * @access public
     * @param array $prices
     * @param array $adjustment
     * @param string $cart_item_key
     * @return array
     */
    public function apply_adjustment_to_prices($prices, $adjustment, $cart_item_key = null)
    {
        // Reference rule
        $rule = $adjustment['rule'];

        // Get receive quantity
        $receive_quantity = !empty($adjustment['receive_quantity']) ? (int) $adjustment['receive_quantity'] : RP_WCDPD_Pricing::get_price_ranges_total_quantity($prices);

        // Track quantity left after each iteration
        $quantity_left = $receive_quantity;

        // Iterate over price ranges
        foreach ($prices as $price_range_index => $price_range) {

            // Get quantity to adjust
            $price_range_quantity = RP_WCDPD_Pricing::get_price_range_quantity($price_range);
            $price_range_adjust_quantity = $quantity_left < $price_range_quantity ? $quantity_left : $price_range_quantity;
            $quantity_left -= $price_range_adjust_quantity;

            // Get price adjusted by rule pricing method
            $adjusted_price = $this->adjust_price_by_rule_pricing_method($price_range['adjusted_price'], $rule);

            // Set adjusted price
            $this->prepare_and_set_adjusted_price($prices, $price_range_index, $price_range_adjust_quantity, $adjusted_price, $price_range['adjusted_price'], $adjustment, $cart_item_key, array('receive_quantity' => $receive_quantity));

            // No more units to adjust
            if ($quantity_left <= 0) {
                break;
            }

        }

        return $prices;
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
        return RP_WCDPD_Pricing::adjust_amount($price_to_adjust, $rule['pricing_method'], $rule['pricing_value']);
    }

    /**
     * Prepare and set adjusted price
     *
     * @access public
     * @param array $prices
     * @param int $price_range_index
     * @param int $quantity
     * @param float $adjusted_price
     * @param float $price_to_adjust
     * @param array $adjustment
     * @param string $cart_item_key
     * @param array $extra_filter_params
     * @param bool $skip_non_adjusted_quantity
     * @return void
     */
    public function prepare_and_set_adjusted_price(&$prices, $price_range_index, $quantity, $adjusted_price, $price_to_adjust, $adjustment, $cart_item_key = null, $extra_filter_params = array(), $skip_non_adjusted_quantity = false)
    {
        // Round adjusted price to get predictable results
        $adjusted_price = RP_WCDPD_Pricing::round($adjusted_price);

        // Allow developers to override
        $adjusted_price = (float) apply_filters('rp_wcdpd_product_pricing_adjusted_unit_price', $adjusted_price, $price_to_adjust, $adjustment, $quantity, $extra_filter_params);

        // Price that is being set does not differ from the current adjusted price
        if (!$skip_non_adjusted_quantity && (abs($prices[$price_range_index]['adjusted_price'] - $adjusted_price) < 0.000001)) {
            return;
        }

        // Apply any potential limits
        if (RP_WCDPD_Settings::get('product_pricing_total_limit') && $cart_item_key !== null && !RP_WCDPD_Controller_Methods_Product_Pricing::running_test()) {

            // Calculate discount amount
            $discount_amount = $price_to_adjust - $adjusted_price;

            // Check if adjustment is discount
            if ($discount_amount > 0.000001) {

                // Get potentially limited ranges
                $limited_ranges = RP_WCDPD_Limit_Product_Pricing::limit_discount($discount_amount, $prices[$price_range_index]['original_price'], $cart_item_key, $prices[$price_range_index]['from'], ($prices[$price_range_index]['from'] + $quantity - 1));

                // Iterate over limited ranges
                foreach ($limited_ranges as $limited_range) {

                    // Check if discount amount was limited for current range
                    if ((abs($discount_amount - $limited_range['discount']) > 0.000001)) {

                        // Recalculate price
                        $adjusted_price = $price_to_adjust - $limited_range['discount'];

                        // Sanity check
                        $adjusted_price = $adjusted_price < 0 ? 0 : $adjusted_price;
                    }

                    // Set adjusted price
                    $this->set_adjusted_price($prices, $price_range_index, $limited_range['quantity'], $adjusted_price, $adjustment, $cart_item_key);
                }

                // Do not proceed to the last line
                return;
            }
        }

        // Set adjusted price
        $this->set_adjusted_price($prices, $price_range_index, $quantity, $adjusted_price, $adjustment, $cart_item_key, $skip_non_adjusted_quantity);
    }

    /**
     * Set adjusted price
     *
     * Note: This method must not reset existing indexes of $prices values,
     * otherwise it may cause issues in apply_adjustment_to_prices() methods
     *
     * @access public
     * @param array $prices
     * @param int $price_range_index
     * @param int $quantity
     * @param float $adjusted_price
     * @param array $adjustment
     * @param string $cart_item_key
     * @param bool $skip_non_adjusted_quantity
     * @return void
     */
    public function set_adjusted_price(&$prices, $price_range_index, $quantity, $adjusted_price, $adjustment, $cart_item_key = null, $skip_non_adjusted_quantity = false)
    {

        // Price that is being set does not differ from the current adjusted price
        if (!$skip_non_adjusted_quantity && (abs($prices[$price_range_index]['adjusted_price'] - $adjusted_price) < 0.000001)) {
            return;
        }

        // Price is set for the whole range
        if (($prices[$price_range_index]['to'] - $prices[$price_range_index]['from'] + 1) === $quantity) {

            // Set adjusted price
            $prices[$price_range_index]['adjusted_price'] = $adjusted_price;

            // Set adjustment
            $prices[$price_range_index]['adjustments'][$adjustment['rule']['uid']] = $adjustment;
        }
        // Price is set for part of the range
        else {

            // Reference price range
            $price_range = $prices[$price_range_index];

            // Set to quantity on the new range
            $price_range['to'] = ($prices[$price_range_index]['from'] + $quantity - 1);

            // Set adjusted price
            $price_range['adjusted_price'] = $adjusted_price;

            // Set adjustment
            $price_range['adjustments'][$adjustment['rule']['uid']] = $adjustment;

            // Increase from quantity of the current range to "make space" for the new range
            $prices[$price_range_index]['from'] += $quantity;

            // Add new range to prices array before the current range
            $prices = RightPress_Help::insert_to_array_before_key($prices, $price_range_index, array(('to_' . $price_range['to']) => $price_range));
        }
    }

    /**
     * Get correcting price adjustment value when pricing value is set per multiple units
     *
     * Used to detect and correct wrong subtotals due to rounding errors (issues #491, #515)
     *
     * @access public
     * @param float $amount
     * @param float $raw_amount
     * @param int $quantity
     * @return float|null
     */
    public function get_correcting_adjustment_value($amount, $raw_amount, $quantity)
    {
        // Calculate potential subtotals
        $expected_subtotal = round(($raw_amount * $quantity), wc_get_price_decimals());
        $actual_subtotal = round(($amount * $quantity), wc_get_price_decimals());

        // Check for rounding error
        // Note: The fancy notation is for stable float comparison, it actually simply compares if floats are not equal
        if (abs($expected_subtotal - $actual_subtotal) > 0.000001) {
            return $amount + round(($expected_subtotal - $actual_subtotal), wc_get_price_decimals());
        }

        // No rounding error detected
        return null;
    }




}
}
