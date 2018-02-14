<?php
/**
 * Plugin Name: WooCommerce Payment Status
 * Plugin URI: https://codecanyon.net/item/woocommerce-payment-status/6608142&ref=actualityextensions
 * Description: Allows WooCommerce admin users to see which orders are paid or not paid from the Orders page. This includes a date of when it's paid, or set to not paid. You can also configure which statuses automatically change the payment status.
 * Version: 1.5.2
 * Author: Actuality Extensions
 * Author URI: http://actualityextensions.com/
 * Tested up to: 4.6
 *
 * Copyright: (c) 2016 Actuality Extensions
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     WC-Payment-Status
 * @author      Actuality Extensions
 * @category    Plugin
 * @copyright   Copyright (c) 2016, Actuality Extensions
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require 'updater/updater.php';
global $aebaseapi;
$aebaseapi->add_product(__FILE__);

    /**
    * Plugin's Core Class
    */
    class WooCommerce_Payment_Status {
    	/**
    	 * list of statuses that should have their payment set as paid
    	 * @var array
    	 */
    	private $default_paid_statuses = array( 'processing', 'completed' );

    	/**
    	 * Default unpaid statuses
    	 * @var array
    	 */
    	private $default_unpaid_statuses = array( 'refunded' );

    	function __construct() {

    		global $woocommerce;

    		// Filter to add the payment status column to the orders listing table
    		add_filter( 'manage_edit-shop_order_columns', array( &$this,'add_payment_column_to_orders_page' ), 11 );

        add_filter('woocommerce_admin_order_actions',array(&$this,'action_column_manage'),12,2);

    		// Filter to output the HTML for the payment status column per order
        	add_filter( 'manage_shop_order_posts_custom_column', array( &$this,'payment_column_output' ), 11 ) ;

        	// When an order status is changed, trigger this
          add_action( 'woocommerce_order_status_changed', array( &$this, 'order_status_change_handler' ), 99, 3 );

          add_action("wp_ajax_woocommerce_mark_order_as_paid", array( &$this, 'ajax_mark_order_as_paid') );

        	// Add managing Payment Status options to the bulk actions on the orders' page
        	add_filter( 'admin_footer-edit.php', array( &$this, 'bulk_actions' ), 9999 );

        	// Handle the submission of the bulk actions
        	add_action( 'load-edit.php', array( &$this, 'bulk_actions_handler' ) );

        	// Display the success notice when bulk actions are performed
        	add_action( 'admin_notices', array( &$this, 'admin_notices' ) );

        	add_action( 'woocommerce_pre_payment_complete', array( &$this, 'payment_complete' ) );

        	// Displays the dropdown menu for the Payment Status in the View Order page
        	add_action( 'woocommerce_admin_order_data_after_order_details', array( &$this, 'display_payment_status_dropdown_on_view_order' ) );

        	// Handles the submission of the payment status from the view order page
        	add_action( 'woocommerce_process_shop_order_meta', array( &$this, 'handle_order_submission' ), 12, 2 );

        	// Enqueue assets
        	add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_assets' ) );

        	// Pay button on my orders page
        	add_filter( 'woocommerce_my_account_my_orders_actions', array( &$this, 'pay_button_controller' ), 99, 2 );

          add_filter( 'woocommerce_get_settings_pages', array( &$this, 'add_settings_class' ), 99 );

          add_filter( 'woocommerce_valid_order_statuses_for_payment', array( &$this, 'valid_order_statuses_for_payment' ), 50, 2 );

          add_filter( 'wc_order_rules_conditions_fields', array( &$this, 'order_rules_conditions_fields' ), 50, 2 );
          add_filter( 'wc_order_rules_check_order', array( &$this, 'order_rules_check_order' ), 50, 6 );

          
          
          add_action( 'admin_bar_init', array( $this, 'init'), 999, 4);

          add_action( 'added_post_meta', array( $this, 'xero_payment_complete'), 50, 4);    
          add_action( 'admin_notices', array( $this, 'xero_payment_admin_notice'), 999 );

          add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_orders' ), 6 );
          add_filter( 'pre_get_posts', array( $this, 'orders_by_order_type' ));
          

          include_once 'class-wc-payment-status-rules.php';
    	}

      public function init()
      {
         global $wp_filter;
        if ( class_exists( 'WC_Xero' )  ){
            $xero_payments = get_option('woocommerce_payment_status_xero_payments');
            if($xero_payments == 'not_allow'){
              if( !isset($wp_filter['woocommerce_order_action_xero_manual_payment_dl']) ){

                unset($wp_filter['woocommerce_order_action_xero_manual_payment']);
                add_action('woocommerce_order_action_xero_manual_payment', array( $this, 'xero_manual_payment'));

              }else{
                add_filter('current_user_can_xero_payment', array( $this, 'current_user_can_xero_payment'), 15, 3);
              }
            }
          }
      }

      public function current_user_can_xero_payment($user_can, $order, $status)
      {

        if( !$user_can )
          return $user_can;

        $payment_status = get_post_meta( $order->get_id(), '_custom_payment_status', true);

        if($payment_status == '1'){

          return true;

        }else{
          
          add_settings_error(
            'woocommerce_xero',
            '',
             __( "Order is not paid therefore payment cannot be sent to Xero. Please mark order as paid and try again.", 'woocommerce_payment_status' ),
            'error'
          );
          set_transient('settings_errors', get_settings_errors(), 30);

          return false;
        }

      }

      function restrict_manage_orders(){
        global $woocommerce, $typenow, $wp_query;
        if ( 'shop_order' != $typenow ) {
            return;
        }
        // Status
        ?>
        <select name='shop_order_payment_status_filter' id='shop_order_payment_status_filter'>
            <option value=""><?php _e( 'All orders', 'woocommerce_payment_status' ); ?></option>

            <?php 
            $selected = '';
            if(isset($wp_query->query['meta_key']) && $wp_query->query['meta_key'] == '_payment_status' && isset($wp_query->query['meta_value'])  ) {
              $selected = $wp_query->query['meta_value'];
            }
            $options = array(
              'paid'     => __( 'Paid', 'woocommerce_payment_status' ),
              'not_paid' => __( 'Not paid', 'woocommerce_payment_status' ),
            );
            foreach ($options as $key => $value) {
              ?>
              <option value="<?php echo $key; ?>" <?php echo (isset($_GET['shop_order_payment_status_filter']) && $_GET['shop_order_payment_status_filter'] == $key ? 'selected' : '' ) ?>><?php echo $value; ?></option>
              <?php
            }
            ?>
        </select>
        <?php
      }
      public function orders_by_order_type( $query ) {
        global $typenow, $wp_query;
        if ( $typenow == 'shop_order' && isset( $_GET['shop_order_payment_status_filter'] ) && $_GET['shop_order_payment_status_filter'] != '' ) {
                $meta_query = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_custom_payment_status',
                        'value' => $_GET['shop_order_payment_status_filter'] == 'paid' ? '1' : '0',
                        'compare' => '=',
                    ),
                );
            $query->set('meta_query', $meta_query);
        }
    }
      function xero_payment_admin_notice() {
          $xero_error = get_transient('settings_errors');
          if ($xero_error) {
            foreach ($xero_error as $error) {
              ?>
              <div class="<?php echo $error['type']; ?>">
                  <p><?php echo $error['message']; ?></p>
              </div>
              <?php
            }
            delete_transient( 'settings_errors' );
          }
      }
      function xero_manual_payment($order){

        $payment_status = get_post_meta( $order->get_id(), '_custom_payment_status', true);

        if($payment_status == '1'){
          // Payment Manager
          $payment_manager = new WC_XR_Payment_Manager();

          // Send Payment
          $payment_manager->send_payment( $order->get_id() );

          return true;

        }else{
          
          add_settings_error(
            'woocommerce_xero',
            '',
             __( "Order is not paid therefore payment cannot be sent to Xero. Please mark order as paid and try again.", 'woocommerce_payment_status' ),
            'error'
          );
          set_transient('settings_errors', get_settings_errors(), 30);

          return false;
        }

      }

       function xero_payment_complete( $mid, $object_id, $meta_key, $_meta_value ){
          $xero_payments = get_option('woocommerce_payment_status_xero_payments');
          if( $meta_key == '_xero_payment_id'){
            
            if($xero_payments == 'mark_as_paid' || !$xero_payments )
              $this->mark_order_as_paid( $object_id );
          }
       }

      function order_rules_conditions_fields($conditions_fields, $rules){ 
        $conditions_fields['payment_status'] = array(
          'name' => __( 'Payment Status', 'woocommerce_payment_status' ) ,
          'first_col' => array(
              array(
                'type'   => 'select',
                'values' => $rules->get_filed_values(array('is', 'is not'))
              )
          ),
          'second_col' => array(
              array(
                'type'   => 'select',
                'values' => $rules->get_filed_values(array('paid', 'not paid', 'partial'))
              )
          )
        );
        return $conditions_fields;
      }

      function order_rules_check_order($condition_eval, $order_var, $order_rule, $value, $post_id, $rules){
        if($order_var == 'payment_status'){
          
          $the_order = new WC_Order( $post_id );
          switch ($this->is_paid_order( $the_order )) {
            case '1':
              $payment_status = 'paid';
              break;

            case '2':
              $payment_status = 'partial';
              break;
            
            default:
              $payment_status = 'not paid';
              break;
          }
          $condition_eval = $rules->condition_rule_symbol($order_rule, $payment_status, $value);
        }
        return $condition_eval;
      }

    	/**
    	 * Enqueues the CSS and JS required by the plugin
    	 * @return void
    	 */
    	function enqueue_assets() {
    		wp_enqueue_style( 'woocommerce_payment_status', plugin_dir_url(__FILE__) . '/assets/css/payment_status.css' );

        $scripts = array('jquery', 'wc-enhanced-select', 'jquery-blockui', 'jquery-tiptip');
    		wp_enqueue_script( 'woocommerce_payment_status', plugin_dir_url(__FILE__) . '/assets/js/payment_status.js', $scripts );
    	}

    	/**
	     * Add the paid column to the orders table
	     * @param [array] $columns [added paid to columns]
	     */
	    function add_payment_column_to_orders_page($columns){
          $new_columns = array();
          foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if($key == 'order_date')
              $new_columns['payment_status'] = '<span class="payment_status_text tips status_head" data-tip="' . esc_attr__( 'Payment Status', 'woocommerce' ) . '" data-currency_symbol="'.get_woocommerce_currency_symbol().'">' . __( 'Payment Status', 'woocommerce_payment_status' ) . '</span>';
          }


	        return $new_columns;
	    }
       /**
     * Adds the action button of our custom status - per order
     * @param  [array] $actions   [actions for the order to be displayed]
     * @param  [object] $the_order [order object]
     * @return [array]            the actions array with our buttons added
     */
    function action_column_manage($actions, $the_order){
        global $woocommerce;

        $payment_status_action_icon = get_option('woocommerce_payment_status_action_icon_trigger', true);
        $_paid_date = get_post_meta( $the_order->get_id(), '_paid_date', true);
        if($payment_status_action_icon == 'yes' && empty($_paid_date)){
          $actions['mark_order_as_paid'] = array(
                  'url'       => wp_nonce_url( admin_url( "admin-ajax.php?action=woocommerce_mark_order_as_paid&order_id={$the_order->id}" ), "mark_order_as_paid" ),
                  'name'      => __( 'Paid', 'woocommerce' ),
                  'action'    => 'mark_order_as_paid payment_action'
              );
        }
        return $actions;
    }

    /**
   * Mark an order as paid
   */
    public function ajax_mark_order_as_paid() {
      if ( !current_user_can('edit_shop_orders') ) wp_die( __( 'You do not have sufficient permissions to access this page.', 'woocommerce' ) );
      if ( !check_admin_referer('mark_order_as_paid')) wp_die( __( 'You have taken too long. Please go back and retry.', 'woocommerce' ) );
      $order_id = isset($_GET['order_id']) && (int) $_GET['order_id'] ? (int) $_GET['order_id'] : '';
      if (!$order_id) die;

       $this->mark_order_as_paid( $order_id );

      wp_safe_redirect( wp_get_referer() );

      die();
    }

	    /**
	     * What data to view in the tables' columns
	     * @param  [string] $column [column name]
	     * @return void
	     */
	    function payment_column_output($column){
	        global $post, $the_order;

	        if ( empty( $the_order ) || $the_order->get_id() != $post->ID )
	            $the_order = new WC_Order( $post->ID );

	        if($column == 'payment_status'){
            switch ($this->is_paid_order( $the_order )) {
              case '1':
                $payment_date = get_post_meta($post->ID,'_paid_date',true);
                echo '<strong class="paid_label tips" alt="Paid" data-tip="' . __(  "Paid on {$payment_date}", 'woocommerce_payment_status' ) . '">' . __(  "paid", 'woocommerce_payment_status' ) . '</strong>';
                break;

              case '2':
                $payment_date = get_post_meta($post->ID,'_paid_date',true);
                echo '<strong class="partial_paid_label tips" alt="Partially Paid" data-tip="' . __(  "Paid on {$payment_date}", 'woocommerce_payment_status' ) . '">' . __(  "partial", 'woocommerce_payment_status' ) . '</strong>';
                break;
              
              default:
                echo '<strong class="not_paid_label tips" alt="Not Paid" data-tip="' . __( 'Not Paid', 'woocommerce_payment_status' ) . '">' . __(  "not paid", 'woocommerce_payment_status' ) . '</strong>';
                break;
            }

	        }
	    }

	    /**
	     * Gets the statuses that should have paid status by default
	     * @return [type] [description]
	     */
	    function get_paid_status_list() {
	    	return $this->default_paid_statuses;
	    }

	    /**
	     * Marks the order as paid
	     * @param  integer $order_id post id
	     * @return void
	     */
	    function mark_order_as_paid( $order_id ) {
	    	add_post_meta( $order_id, '_paid_date', current_time('mysql'), true );
        $st = get_post_meta( $order_id, '_custom_payment_status', true );
        update_post_meta( $order_id, '_custom_payment_status', '1' );
        if($st != '1'){
          $msg = '';
          switch ($st) {
            case '2':
              $msg = __("Payment status changed from Partially Paid to Paid", 'woocommerce_payment_status');
              break;
            case '0':
              $msg = __("Payment status changed from Not Paid to Paid", 'woocommerce_payment_status');
              break;
            default:
              $msg = __("Payment status changed from Not Paid to Paid", 'woocommerce_payment_status');
            break;
          }
          $order = new WC_Order($order_id);
          $order->add_order_note( $msg );
        }
	    }

	    /**
       * Marks the order as unpaid
       * @param  integer $order_id post id
       * @return void
       */
      function mark_order_as_unpaid( $order_id ) {
        delete_post_meta( $order_id, '_paid_date' );
        $st = get_post_meta( $order_id, '_custom_payment_status', true );
        update_post_meta( $order_id, '_custom_payment_status', '0' );

        if($st != '0'){
          $msg = '';
          switch ($st) {
            case '1':
              $msg = __("Payment status changed from Paid to Not Paid", 'woocommerce_payment_status');
              break;
            case '2':
              $msg = __("Payment status changed from Partially Paid to Not Paid", 'woocommerce_payment_status');
              break;
            default:
              $msg = __("Payment status changed from Paid to Not Paid", 'woocommerce_payment_status');
            break;
          }
          $order = new WC_Order($order_id);
          $order->add_order_note( $msg );
        }
      }

      /**
	     * Marks the order as partially paid
	     * @param  integer $order_id post id
	     * @return void
	     */
	    function mark_order_as_partiallypaid( $order_id ) {
	    	add_post_meta( $order_id, '_paid_date', current_time('mysql'), true );
        $st = get_post_meta( $order_id, '_custom_payment_status', true );
	    	update_post_meta( $order_id, '_custom_payment_status', '2' );

        if($st != '2'){
          $msg = '';
          switch ($st) {
            case '1':
              $msg = __("Payment status changed from Paid to Partially Paid", 'woocommerce_payment_status');
              break;
            case '0':
              $msg = __("Payment status changed from Not Paid to Partially Paid", 'woocommerce_payment_status');
              break;
            default:
              $msg = __("Payment status changed from Paid to Partially Paid", 'woocommerce_payment_status');
            break;
          }
          $order = new WC_Order($order_id);
          $order->add_order_note( $msg );
        }
	    }

	    /**
	     * Changes the payment status of the order to reflect the new status
	     * @param  [integer] $order_id   [order id]
	     * @param  [string] $old_status [old status slug]
	     * @param  [string] $new_status [new status slug]
	     * @return void
	     */
	    function order_status_change_handler( $order_id, $old_status, $new_status ) {

	    	if( isset( $_POST['order_status_changed'] ) ) {
	    		return;
	    	}

	    	if( !empty( $order_id ) ) {
	    		$order = new WC_Order( $order_id );
	    	}

	    	$old_payment_status = get_post_meta( $order_id, '_custom_payment_status', true );
	    	if( empty( $old_payment_status ) ) {
	    		$old_payment_status = 'Not Paid';
	    	} else {
	    		$old_payment_status = 'Paid';
	    	}
	    	$new_payment_status = $old_payment_status;

	    	// Get the associated payment status
    		$payment_status = get_option( 'woocommerce_payment_status_default_' . $new_status );        

        $status_rules   = get_option( 'wc_payment_status_rules_' . $new_status );
        if( $status_rules  && !empty($status_rules) && isset($status_rules['conditions']) && !empty($status_rules['conditions']) ){
          $ps_r = new WC_Payment_Status_Rules;
          if($ps_r->check_order($order_id, $status_rules)){
            $payment_status = $status_rules['payment_status'];
            switch ($payment_status) {
              case 'Paid':
                $payment_status = 'paid';
                break;
              case 'Partially Paid':
                $payment_status = 'partial';
                break;              
              default:
                $payment_status = 'notpaid';
                break;
            }
          }
        }

    		// Do we alter the payment status?
    		if( !empty( $payment_status ) && $payment_status != 'default' ) {
    			if( $payment_status == 'paid' ) {
            $this->mark_order_as_paid( $order_id );
          }elseif( $payment_status == 'partial' ) {
	    			$this->mark_order_as_partiallypaid( $order_id );
    			} else {
	    			$this->mark_order_as_unpaid( $order_id );
    			}
    		}
	    	return true;
	    }

	    /**
	     * Adds the Mark as Paid and Mark as Unpaid options to the bulk actions on the orders list page
	     * @param  array $actions array of actions as key => text to display
	     * @return array          array of actions including our custom options
	     */
	    function bulk_actions() {

	    	global $post_type;

	    	if( $post_type == 'shop_order' ) {
		    	?>
		    	<script type="text/javascript">
			    	jQuery('div.tablenav.top > .alignleft.actions > select[name="action"]').append('<option value="mark_order_as_paid">Mark paid</option>');
			    	jQuery('div.tablenav.top > .alignleft.actions > select[name="action"]').append('<option value="mark_order_as_unpaid">Mark not paid</option>');
			    	jQuery('div.tablenav.top > .alignleft.actions > select[name="action"]').append('<option value="mark_order_as_partiallypaid">Mark partially paid</option>');
		    	</script>
		    	<?php
	    	}
	    }

	    /**
	     * Performs the multiple marking as paid/unpaid - handles the bulk actions
	     * @return void
	     */
	    function bulk_actions_handler() {

  			if( empty( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-posts' ) || empty( $_GET['action'] ) ) {
  				return;
  			}

  			$action = $_GET['action'];

  			$fn = ($action == 'mark_order_as_paid' || $action == 'mark_order_as_unpaid' || $action == 'mark_order_as_partiallypaid') ? $action:'';

  			if( !empty( $fn ) && !empty( $_GET['post'] ) ) {

  				$post_ids = $_GET['post'];


  				foreach( $post_ids as $p ) {
  					call_user_func( array( &$this, $fn ), $p );
  				}
  			} else {
  				return;
  			}

  			$sendback = esc_url_raw( wp_get_referer() );

  			$sendback = add_query_arg( array( 'marked_as_payment_status' => true ), $sendback );

  			wp_redirect( $sendback );

  			exit();
	    }

	    /**
	     * Displays the success message when the payment status is changed on bulk actions
	     * @return void
	     */
	    function admin_notices() {
	    	if( isset( $_GET['marked_as_payment_status'] ) ) {
	    		echo '<div class="updated"><p>Payment status changed.</p></div>' . "\n";
	    	}
	    }

	    /**
	     * Marks the order as paid when a payment through a gateway is completed
	     * @param  integer $order_id order's post id
	     * @return void
	     */
	    function payment_complete( $order_id ) {
	    	$this->mark_order_as_paid( $order_id );
	    }

	    /**
	     * Displays the payment status select box in the view order page
	     * @param  object $order the order's object
	     * @return void
	     */
	    function display_payment_status_dropdown_on_view_order( $order ) {
	    	require_once( 'panels/payment_status.php' );
	    }

	    /**
	     * When an order is edited and submitted, this function changes the payment status according to the
	     * new order status (high priority) and the payment status select box (low priority)
	     * @param  integer $post_id order id
	     * @param  object $post    post object
	     * @return void
	     */
	    function handle_order_submission( $post_id, $post ) {

	    	$continue = true;

	    	// Has the order status changed? (js trick)
	    	if( !empty( $_POST['order_status_changed'] ) ) {
	    		// Order Status changed - check for payment status associated
	    		$order = new WC_Order( $post_id );
	    		// What is the new order status?
	    		$status = $order->status;
	    		// Get the associated payment status
	    		$payment_status = get_option( 'woocommerce_payment_status_default_' . $status );
	    		// Do we alter the payment status?
	    		if( empty( $payment_status ) || $payment_status == 'default' ) {
	    			return;
	    		}
	    		if( $payment_status == 'paid' ) {
	    			$this->mark_order_as_paid( $post_id );
	    		} else {
	    			$this->mark_order_as_unpaid( $post_id );
	    		}
	    		// Do not continue in this function
	    		$continue = false;
	    	}

	    	if( !$continue ) {
	    		return;
	    	}

	    	// By reaching this part, the order status did not change - we need to handle the payment status
	    	if( !empty( $_POST['payment_status'] ) ) {

          $payment_status = $_POST['payment_status'];
          if( $payment_status == 'paid' ) {
            $this->mark_order_as_paid( $post_id );
          }elseif( $payment_status == 'partiallypaid' ) {
            $this->mark_order_as_partiallypaid( $post_id );
          } else {
            $this->mark_order_as_unpaid( $post_id );
          }
	    		
	    	} # end of payment status submission
	    }

	    /**
	     * Checks whether an order has payment status as true
	     * @param  object  $order order's object
	     * @return boolean        whether an order is paid or not
	     */
	    function is_paid_order( $order ) {

	    	global $woocommerce;

	    	$field = get_post_meta( $order->get_id(), '_custom_payment_status', true );

    		if( $field === '' ) {
    			if(in_array($order->status, $this->get_paid_status_list()) ){
              add_post_meta($order->get_id(), '_custom_payment_status','1');
              $field = '1';
          } else {
              add_post_meta($order->get_id(), '_custom_payment_status','0');
              $field = '0';
          }
    		}

	    	return $field;


	    }

	    /**
	     * Adds the pay button to the orders row in the My Account page if the order is not paid
	     * @param  array $actions current actions for the order
	     * @param  object $order   Order's ibject
	     * @return array          new actions for the order
	     */
	    function pay_button_controller( $actions, $order ) {
          $valid_order_statuses = apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order );
          
  	    	if( $this->is_paid_order( $order ) == '0' && $order->has_status( $valid_order_statuses )) {

  	    		if( !isset( $actions['pay'] ) ) {

  	    			$actions2 = array();
  	    			$actions2['pay'] = array(
    						'url'  => $order->get_checkout_payment_url(),
    						'name' => __( 'Pay', 'woocommerce' )
              );
  					$actions = $actions2 + $actions;
  	    		}

  	    	} else {

  	    		if( isset( $actions['pay'] ) ) {
  	    			unset( $actions['pay'] );
  	    		}

  	    	}
	    	return $actions;
	    }

      function valid_order_statuses_for_payment($statuses, $order){
        $name =  'woocommerce_payment_status_action_pay_button_controller';
        $option_value = get_option( $name, array() );
        if(!is_array($option_value))
          $option_value = array();
        return $option_value;
      }
	    

	    function add_settings_class( $settings ) {
	    	$settings[] = include( 'class.settings.php' );
	    	return $settings;
	    }

	    /**
	     * For debugging purposes, not currently used
	     * @param  mixed $var any variable to var_dump
	     * @return void
	     */
	    function debug( $var ) {
			file_put_contents( plugin_dir_path(__FILE__) . '/debug.txt', '#######################################################' . PHP_EOL, FILE_APPEND );
			file_put_contents( plugin_dir_path(__FILE__) . '/debug.txt', var_export($var, true) . PHP_EOL, FILE_APPEND );
		}

    } # end of the class


/**
 * Initializes the plugin
 * @return void
 */
function woocommerce_payment_status_loader() {
	new WooCommerce_Payment_Status();
}

add_action( 'plugins_loaded', 'woocommerce_payment_status_loader', 99999 );

?>