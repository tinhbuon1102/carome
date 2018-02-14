<?php
/*
  Plugin Name: Woo - Send Email on Cancelled or Failed Order 
  Plugin URI: https://tickera.com/
  Description: Sends an email to the customer when order is cancelled or failed
  Author: Tickera.com
  Author URI: https://tickera.com/
  Version: 1.0.0
  TextDomain: tc
  Domain Path: /languages/

  Copyright 2017 Tickera (https://tickera.com/)
 */

function wc_cancelled_order_add_customer_email( $recipient, $order ){
     return $recipient . ',' . $order->billing_email;
 }
 add_filter( 'woocommerce_email_recipient_cancelled_order', 'wc_cancelled_order_add_customer_email', 10, 2 );
 add_filter( 'woocommerce_email_recipient_failed_order', 'wc_cancelled_order_add_customer_email', 10, 2 );

