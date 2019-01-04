<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Customer_Value')) {
    require_once('rp-wcdpd-condition-customer-value.class.php');
}

/**
 * Condition: Customer Value - Last Order Amount
 *
 * @class RP_WCDPD_Condition_Customer_Value_Last_Order_Amount
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Customer_Value_Last_Order_Amount')) {

class RP_WCDPD_Condition_Customer_Value_Last_Order_Amount extends RP_WCDPD_Condition_Customer_Value
{
    protected $key      = 'last_order_amount';
    protected $contexts = array('product_pricing', 'cart_discounts', 'checkout_fees');
    protected $method   = 'numeric';
    protected $fields   = array(
        'after' => array('decimal'),
    );
    protected $position = 30;

    // Singleton instance
    protected static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->hook();
    }

    /**
     * Get label
     *
     * @access public
     * @return string
     */
    public function get_label()
    {
        return __('Spent - Last order', 'rp_wcdpd');
    }

    /**
     * Get value to compare against condition
     *
     * @access public
     * @param array $params
     * @return mixed
     */
    public function get_value($params)
    {
        // Get customer id
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : (is_user_logged_in() ? get_current_user_id() : null);

        // Get billing email
        if ($customer_id) {
            $customer = new WC_Customer($customer_id);
            $billing_email = $customer->get_billing_email();
        }
        else {
            $billing_email = RightPress_Conditions::get_checkout_billing_email();
        }

        // Get last order total
        if ($last_order_id = RightPress_Help::get_wc_last_user_paid_order_id($customer_id, $billing_email)) {
            if ($order = wc_get_order($last_order_id)) {
                return (float) RightPress_Conditions::order_get_total($order);
            }
        }

        return 0.0;
    }





}

RP_WCDPD_Condition_Customer_Value_Last_Order_Amount::get_instance();

}
