<?php
/*
Plugin Name: WooCommerce Free Gift
Plugin URI: http://codecanyon.net/item/woocommerce-free-gift/6144902
Description: Allows for rewarding customers a free gift when they spend at least a specified amount of money on purchase.
Author: Rene Puchinger
Version: 1.8.1
Author URI: http://codecanyon.net/user/renp

Copyright (C) 2013 Rene Puchinger

@package WooCommerce_Free_Gift
@since 1.0

*/

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return; // Check if WooCommerce is active

if ( !class_exists( 'WooCommerce_Free_Gift' ) ) {

	class WooCommerce_Free_Gift {

		var $last_msg = '';

		public function __construct() {

			load_plugin_textdomain( 'wc_free_gift', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

			$this->current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

			// Tab under WooCommerce settings
			$this->settings_tabs = array(
				'wc_free_gift' => __( 'Free Gift', 'wc_free_gift' )
			);

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

			add_action( 'woocommerce_settings_tabs', array( $this, 'add_tab' ), 10 );

			foreach ( $this->settings_tabs as $name => $label ) {
				add_action( 'woocommerce_settings_tabs_' . $name, array( $this, 'settings_tab_action' ), 10 );
				add_action( 'woocommerce_update_options_' . $name, array( $this, 'save_settings' ), 10 );
			}

			// enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dependencies_admin' ) );
			add_action( 'wp_head', array( $this, 'enqueue_dependencies' ) );

			add_action( 'woocommerce_loaded', array( $this, 'woocommerce_loaded' ) );

		}

		/**
		 * Register the main processing hooks.
		 */
		public function woocommerce_loaded() {

			add_action( 'woocommerce_calculate_totals', array( $this, 'cart_info' ), 10, 1 );
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'fix_messages' ) );
			add_action( 'woocommerce_review_order_after_cart_contents', array( $this, 'order_info' ) );
			// thangtqvn modified function "woocommerce_new_order" to "woocommerce_checkout_update_order_meta" - BEGIN
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'add_to_order' ), 10, 2 );

			if ( version_compare( WOOCOMMERCE_VERSION, "2.6.0" ) >= 0 ) {
				add_action( 'woocommerce_after_cart_table', array( $this, 'choose_gift' ) );
			} else {
				add_action( 'woocommerce_after_cart_table', array( $this, 'choose_gift_older_wc' ) );
			}

			add_action( 'wp_ajax_nopriv_wc_free_gift_chosen', array( $this, 'gift_chosen' ) );
			add_action( 'wp_ajax_wc_free_gift_chosen', array( $this, 'gift_chosen' ) );
			add_action( 'wp_ajax_wc_free_gift_get_products', array( $this, 'list_possible_products' ) );
			add_filter( 'woocommerce_product_categories_widget_args', array( $this, 'hide_gift_category' ) );
			add_filter( 'the_posts', array( $this, 'hide_gifts' ) );

			// add a useful shortcode
			add_shortcode( 'wc_free_gift_message', array( $this, 'shortcode_gift_message' ) );

			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				add_filter( 'woocommerce_order_item_name', array( $this, 'modify_title' ), 10, 2 );
			} else {
				add_filter( 'woocommerce_order_table_product_title', array( $this, 'modify_title' ), 10, 2 );
			}

		}

		function fix_messages() {

			global $woocommerce;

			if ( version_compare( WOOCOMMERCE_VERSION, "2.4.0" ) >= 0 ) {
				return;
			} else
				if ( version_compare( WOOCOMMERCE_VERSION, "2.3.0" ) >= 0 ) {
					$messages = wc_get_notices();
				} else {
					$messages = $woocommerce->get_messages();
				}

			if ( ( $i = array_search( $this->last_msg, $messages ) && is_order_received_page() ) !== false ) {

				unset( $messages[$i] );

			}

			if ( version_compare( WOOCOMMERCE_VERSION, "2.3.0" ) >= 0 ) {
				wc_add_notice( $messages, 'success' );
				wc_print_notices();
			} else {
				$woocommerce->set_messages( $messages );
				$woocommerce->show_messages();
			}

		}

		/**
		 * Add action links under WordPress > Plugins
		 *
		 * @param $links
		 * @return array
		 */
		public function action_links( $links ) {

			$settings_slug = 'woocommerce';

			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

				$settings_slug = 'wc-settings';

			}

			$plugin_links = array(

				'<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=wc_free_gift' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>',

			);

			return array_merge( $plugin_links, $links );
		}

		/**
		 * @access public
		 * @return void
		 */
		public function add_tab() {

			$settings_slug = 'woocommerce';

			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

				$settings_slug = 'wc-settings';

			}

			foreach ( $this->settings_tabs as $name => $label ) {
				$class = 'nav-tab';
				if ( $this->current_tab == $name )
					$class .= ' nav-tab-active';
				echo '<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=' . $name ) . '" class="' . $class . '">' . $label . '</a>';
			}

		}

		/**
		 * @access public
		 * @return void
		 */
		public function settings_tab_action() {

			global $woocommerce_settings;

			// Determine the current tab in effect.
			$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_settings_tabs_' );

			// Load the prepared form fields.
			$this->init_form_fields();

			if ( is_array( $this->fields ) )
				foreach ( $this->fields as $k => $v )
					$woocommerce_settings[$k] = $v;

			// Display settings for this tab (make sure to add the settings to the tab).
			woocommerce_admin_fields( $woocommerce_settings[$current_tab] );
		}

		/**
		 * Save settings in a single field in the database for each tab's fields (one field per tab).
		 */
		public function save_settings() {

			global $woocommerce_settings;

			// Make sure our settings fields are recognised.
			$this->add_settings_fields();

			$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_update_options_' );
			woocommerce_update_options( $woocommerce_settings[$current_tab] );

		}

		/**
		 * Get the tab current in view/processing.
		 */
		protected function get_tab_in_view( $current_filter, $filter_base ) {
			return str_replace( $filter_base, '', $current_filter );
		}

		/**
		 * Prepare form fields to be used in the various tabs.
		 */
		protected function init_form_fields() {

			global $woocommerce;

			// Define settings
			$this->fields['wc_free_gift'] = apply_filters( 'woocommerce_free_gift_settings_fields', array(

				array( 'name' => __( 'Free Gift', 'wc_free_gift' ), 'type' => 'title', 'desc' => __( 'The following options are specific to the Free Gift extension.', 'wc_free_gift' ), 'id' => 'wc_free_gift_options' ),

				array(
					'name' => __( 'Free Gift globally enabled', 'wc_free_gift' ),
					'id' => 'wc_free_gift_enabled',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no',
					'desc' => __( 'Free Gift is globally enabled.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'Allow free gifts only to logged in users', 'wc_free_gift' ),
					'id' => 'wc_free_gift_only_logged',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no',
					'desc' => __( 'Allow free gifts only to users which are registered and logged in.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'Allow each user to get the free gift ONLY once', 'wc_free_gift' ),
					'id' => 'wc_free_gift_only_once',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no',
				),

				array(
					'title' => __( 'Method of offering gifts', 'wc_free_gift' ),
					'id' => 'wc_free_gift_type',
					'desc' => __( 'Reward customers a fixed gift or let them choose from a category', 'wc_free_gift' ),
					'desc_tip' => true,
					'std' => 'yes',
					'type' => 'select',
					'css' => 'width:180px;',
					'class' => 'chosen_select',
					'options' => array( '' => 'Fixed gift', 'category' => 'Gift from a category' )
				),

				array(
					'title' => sprintf( __( 'Minimal cart subtotal (%s)', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'desc' => sprintf( __( 'Enter a minimal Cart Subtotal in %s so that the customer is eligible for a free gift.', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'id' => 'wc_free_gift_minimal_total',
					'css' => 'width:180px;',
					'type' => 'number',
					'custom_attributes' => array(
						'min' => 0,
						'step' => 1
					),
					'desc_tip' => true,
					'default' => '100'
				),

				array(
					'title' => __( 'The free gift product', 'wc_free_gift' ),
					'id' => 'wc_free_gift_product_id',
					'desc' => __( 'Select the product which will be given to customer as a free gift after they make a purchase for at least the minimal amount.', 'wc_free_gift' ),
					'desc_tip' => true,
					'std' => 'yes',
					'type' => 'select',
					'css' => 'width:300px;',
					'class' => 'chosen_select',
					'options' => array( '' => 'Choose a product ...' ) + $this->get_products()
				),

				array(
					'title' => sprintf( __( 'Minimal cart subtotal (%s) for a better gift (optional)', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'desc' => sprintf( __( 'If desirable, enter a higher minimal Cart Subtotal so that the customer is eligible for a better free gift (optional). Must be higher than the minimal cart subtotal above.', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'id' => 'wc_free_gift_minimal_total2',
					'css' => 'width:180px;',
					'type' => 'number',
					'custom_attributes' => array(
						'min' => 0,
						'step' => 1
					),
					'desc_tip' => true,
					'default' => ''
				),

				array(
					'title' => __( 'The better free gift product (optional)', 'wc_free_gift' ),
					'id' => 'wc_free_gift_product_id2',
					'desc' => __( 'If a minimal cart subtotal for a better gift is specified, select the product which will be given to customer if he is eligible.', 'wc_free_gift' ),
					'desc_tip' => true,
					'std' => 'yes',
					'type' => 'select',
					'css' => 'width:300px;',
					'class' => 'chosen_select',
					'options' => array( '' => 'Choose a product ...' ) + $this->get_products()
				),

				array(
					'title' => __( 'The free gift category', 'wc_free_gift' ),
					'id' => 'wc_free_gift_category_id',
					'desc' => __( 'Specify the category from which customers can select gifts', 'wc_free_gift' ),
					'std' => 'yes',
					'desc_tip' => true,
					'type' => 'select',
					'css' => 'width:300px;',
					'class' => 'chosen_select',
					'options' => array( '' => 'Choose a category ...' ) + $this->get_categories()
				),

				array(
					'title' => __( 'The better free gift category (optional)', 'wc_free_gift' ),
					'id' => 'wc_free_gift_category_id2',
					'desc' => __( 'If a minimal cart subtotal for a better gift is specified, specify the category from which a better gift can be selected.', 'wc_free_gift' ),
					'desc_tip' => true,
					'std' => 'yes',
					'type' => 'select',
					'css' => 'width:300px;',
					'class' => 'chosen_select',
					'options' => array( '' => 'Choose a product ...' ) + $this->get_categories()
				),

				array(
					'title' => __( 'Let customers choose not to receive a gift', 'wc_free_gift' ),
					'id' => 'wc_free_gift_let_choose',
					'desc' => __( 'Do you want customers choose not to receive the free gift?', 'wc_free_gift' ),
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no'
				),

				array(
					'title' => sprintf( __( 'Quantity of the free gift', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'desc' => sprintf( __( 'Enter the number of free gifts that the customer will receive if he has met the eligibility condition (i.e. sufficient subtotal). Important: If you set this value higher than 1 and you use stock management for the gift products, you need to adjust the "Out of Stock Threshold" on WooCommerce Settings page.', 'wc_free_gift' ), get_woocommerce_currency_symbol() ),
					'id' => 'wc_free_gift_quantity',
					'css' => 'width:100px;',
					'type' => 'number',
					'custom_attributes' => array(
						'min' => 0,
						'step' => 1
					),
					'desc_tip' => true,
					'default' => '1'
				),

				array(
					'name' => __( 'Hide the gift product / category', 'wc_free_gift' ),
					'id' => 'wc_free_gift_hide',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no',
					'desc' => __( 'If you want to hide the product or category you specified above from product search and product catalog, enable this option.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'If any coupon is applied, don\'t allow free gift to be added', 'wc_free_gift' ),
					'id' => 'wc_free_gift_coupon_no_gift',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no',
					'desc' => ''
				),

				array(
					'name' => __( 'Motivating message visible on the Cart page', 'wc_free_gift' ),
					'id' => 'wc_free_gift_motivating_message_enabled',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'yes',
					'desc' => __( 'Display a message on the Cart page motivating the customer to spend more money in order to get the free gift.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'The motivating message text', 'wc_free_gift' ),
					'id' => 'wc_free_gift_motivating_message',
					'type' => 'textarea',
					'css' => 'width:100%;',
					'default' => __( 'By spending at least %PRICE%, you will be eligible for a free gift after checkout.', 'wc_free_gift' ),
					'desc' => __( 'Optionally include any of the placeholders %PRICE% (the minimal cart subtotal for a gift), %PRODUCT% (the free gift product title), %REMAINING_AMOUNT% (the amount which remains to get the gift) which will be automatically substituted by the actual values. In case a better gift is specified, you can also use placeholders %PRICE_BETTER% and %REMAINING_AMOUNT_BETTER%.', 'wc_free_gift' )
				),

				array(
					'name' => __( '"Eligible for a free gift" message visible on the Cart page', 'wc_free_gift' ),
					'id' => 'wc_free_gift_eligible_message_enabled',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'yes',
					'desc' => __( 'Display a message on the Cart page after the customer is eligible for the free gift.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'The "Eligible for a free gift" message text', 'wc_free_gift' ),
					'id' => 'wc_free_gift_eligible_message',
					'type' => 'textarea',
					'css' => 'width:100%;',
					'default' => __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ),
					'desc' => __( 'Optionally use the placeholder %PRODUCT% which will be automatically substituted by the actual product name of the selected free gift.', 'wc_free_gift' )
				),

				array(
					'name' => __( 'The CSS style for the "Free" indicator of the free gift', 'wc_free_gift' ),
					'id' => 'wc_free_gift_price_css',
					'type' => 'textarea',
					'css' => 'width:100%;',
					'default' => 'color: #00aa00;'
				),
				array( 'type' => 'sectionend', 'id' => 'wc_free_gift_options' ),

			) ); // End settings

			$this->run_js( "

						jQuery('#wc_free_gift_motivating_message_enabled').change(function() {

							jQuery('#wc_free_gift_motivating_message').closest('tr').hide();
							if ( jQuery(this).attr('checked') ) {
								jQuery('#wc_free_gift_motivating_message').closest('tr').show();
							}

						}).change();

						jQuery('#wc_free_gift_eligible_message_enabled').change(function() {

							jQuery('#wc_free_gift_eligible_message').closest('tr').hide();
							if ( jQuery(this).attr('checked') ) {
								jQuery('#wc_free_gift_eligible_message').closest('tr').show();
							}

						}).change();

						jQuery('#wc_free_gift_only_logged').change(function() {

							jQuery('#wc_free_gift_only_once').closest('tr').hide();
							if ( jQuery(this).attr('checked') ) {
								jQuery('#wc_free_gift_only_once').closest('tr').show();
							}

						}).change();

						jQuery('#wc_free_gift_type').change(function() {

							jQuery('#wc_free_gift_product_id').closest('tr').hide();
							jQuery('#wc_free_gift_product_id2').closest('tr').hide();
							jQuery('#wc_free_gift_category_id').closest('tr').hide();
							jQuery('#wc_free_gift_category_id2').closest('tr').hide();
							jQuery('#wc_free_gift_let_choose').closest('tr').hide();

							if ( jQuery('#wc_free_gift_type').val() == '' ) {
								jQuery('#wc_free_gift_let_choose').closest('tr').show();
								jQuery('#wc_free_gift_product_id').closest('tr').show();
								jQuery('#wc_free_gift_product_id2').closest('tr').show();
							} else {
								jQuery('#wc_free_gift_category_id').closest('tr').show();
								jQuery('#wc_free_gift_category_id2').closest('tr').show();
							}

						}).change();

						jQuery('#wc_free_gift_category_id').change(function() {

							jQuery('.wc_free_gift_cat_info').remove();

							var data = {
								action: 'wc_free_gift_get_products',
								category: jQuery(this).val()
							};

							catProdDisplayed = false;

							jQuery.post('" . admin_url( 'admin-ajax.php' ) . "', data, function (response) {

								if ( response && !catProdDisplayed ) {
									jQuery('#wc_free_gift_category_id').closest('td').append('<div class=\'wc_free_gift_cat_info\'>'+response+'</div>');
									catProdDisplayed = true;
								}

							});

						}).change();

						jQuery('#wc_free_gift_category_id2').change(function() {

							jQuery('.wc_free_gift_cat_info2').remove();

							var data = {
								action: 'wc_free_gift_get_products',
								category: jQuery(this).val()
							};

							catProdDisplayed2 = false;

							jQuery.post('" . admin_url( 'admin-ajax.php' ) . "', data, function (response) {

								if ( response && !catProdDisplayed2 ) {
									jQuery('#wc_free_gift_category_id2').closest('td').append('<div class=\'wc_free_gift_cat_info2\'>'+response+'</div>');
									catProdDisplayed2 = true;
								}

							});

						}).change();

			" );

		}

		/**
		 * Add settings fields for each tab.
		 */
		public function add_settings_fields() {

			global $woocommerce_settings;

			// Load the prepared form fields.
			$this->init_form_fields();

			if ( is_array( $this->fields ) )
				foreach ( $this->fields as $k => $v )
					$woocommerce_settings[$k] = $v;

		}

		/**
		 * Enqueue frontend dependencies.
		 */
		public function enqueue_dependencies() {

			wp_enqueue_style( 'wc_free_gift_style', plugins_url( 'assets/css/style.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );

		}

		/**
		 * Enqueue backend dependencies.
		 */
		public function enqueue_dependencies_admin() {

			wp_enqueue_style( 'wc_free_gift_style_admin', plugins_url( 'assets/css/admin.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );

		}

		/**
		 * Hooks on woocommerce_calculate_totals action.
		 *
		 * @param WC_Cart $cart
		 */
		public function cart_info( WC_Cart $cart ) {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'no' ) {
				return;
			}

			if ( is_checkout() || ( get_option( 'wc_free_gift_coupon_no_gift', 'no' ) == 'yes' && $this->is_coupon_applied() ) ) {
				return;
			}

			if ( !$this->customer_gift_allowed() ) {
				return;
			}

			$the_amount = $woocommerce->cart->subtotal;

			$possible_products = $this->get_possible_products( false, false, $the_amount );

			if ( empty ( $possible_products ) ) {
				return;
			}

			if ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() ) {

				$motivating_msg = get_option( 'wc_free_gift_motivating_message', __( 'By spending at least %PRICE%, you will be eligible for a free gift after checkout.', 'wc_free_gift' ) );
				$motivating_msg = $this->str_replace_products( $possible_products, '%PRODUCT%', $motivating_msg );
				$motivating_msg = str_replace( '%PRICE%', $this->get_price( get_option( 'wc_free_gift_minimal_total' ) ), $motivating_msg );
				$motivating_msg = str_replace( '%PRICE_BETTER%', $this->get_price( get_option( 'wc_free_gift_minimal_total2' ) ), $motivating_msg );
				$motivating_msg = str_replace( '%REMAINING_AMOUNT%', $this->get_price( floatval( get_option( 'wc_free_gift_minimal_total' ) ) - floatval( $the_amount ) ), $motivating_msg );
				$motivating_msg = str_replace( '%REMAINING_AMOUNT_BETTER%', $this->get_price( floatval( get_option( 'wc_free_gift_minimal_total2' ) ) - floatval( $the_amount ) ), $motivating_msg );
				
				$eligible_msg_default = get_option( 'wc_free_gift_eligible_message', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
				$notices = WC()->session->get('wc_notices', array());
				if (!empty($notices['success']))
				{
					foreach ($notices['success'] as $key_notice => $notice)
					{
						if ($notice == $eligible_msg_default)
						{
							unset( $notices['success'][$key_notice] );
						}
						elseif ($notice == $motivating_msg)
						{
							unset( $notices['success'][$key_notice] );
						}
					}
					WC()->session->set( 'wc_notices', $notices );
				}
				
				if ( $this->is_customer_eligible( $the_amount ) ) {
					if ( get_option( 'wc_free_gift_eligible_message_enabled', 'yes' ) == 'yes' && ( $this->get_from_session() == '' || is_cart() ) ) {
						// thangtqvn modified - BEGIN
						if (isset($_REQUEST['wc-ajax']) && $_REQUEST['wc-ajax'] == 'checkout')
						{
							$eligible_msg = get_option( 'wc_free_gift_message_thanks_top', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
						}
						else {
							$eligible_msg = get_option( 'wc_free_gift_eligible_message', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
						}
						// thangtqvn modified - END
						$eligible_msg = $this->str_replace_products( $possible_products, '%PRODUCT%', $eligible_msg );
						$this->add_wc_message( apply_filters( 'woocommerce_free_gift_eligible_message', $eligible_msg ) );
						$this->last_msg = $eligible_msg;
					}
				} else {
					if ( get_option( 'wc_free_gift_motivating_message_enabled', 'yes' ) == 'yes' && ( $this->get_from_session() == '' || is_cart() ) ) {
						$this->add_wc_message( apply_filters( 'woocommerce_free_gift_motivating_message', $motivating_msg ) );
						$this->last_msg = $motivating_msg;
					}
				}
			}
		}

		/**
		 * Provides AJAX listing of products on backend.
		 */
		public function list_possible_products() {

			header( 'Content-Type: text/html; charset=utf-8' );

			if ( !empty( $_POST['category'] ) ) {
				echo __( 'Currently customers can choose one from the following products: ', 'wc_free_gift' )
					. '<strong>' . implode( ' ' . __( ', ', 'wc_free_gift' ) . ' ', array_values( $this->get_possible_products( true, $_POST['category'] ) ) ) .
					'</strong>';
			}

			die();

		}

		/**
		 * @param bool $with_id
		 * @param bool $category_id
		 * @return array
		 */
		protected function get_possible_products( $with_id = false, $category_id = false, $the_amount = false ) {

			$products = array();
			$products2 = array();
			$category_id2 = '';

			if ( get_option( 'wc_free_gift_type', '' ) == 'category' || $category_id ) {

				if ( !$category_id ) {
					$category_id = get_option( 'wc_free_gift_category_id', '' );
					$category_id2 = get_option( 'wc_free_gift_category_id2', '' );
				}

				if ( $category_id != '' ) {
					$products_from_cat = $this->get_products( $category_id );
					foreach ( $products_from_cat as $id => $name ) {
						array_push( $products, $id );
						unset( $name );
					}
					unset( $products_from_cat );
				} else {
					return array();
				}

				if ( $category_id2 != '' ) {
					$products_from_cat2 = $this->get_products( $category_id2 );
					foreach ( $products_from_cat2 as $id => $name ) {
						array_push( $products2, $id );
						unset( $name );
					}
					unset( $products_from_cat2 );
				}

				if ( $this->is_customer_eligible( $the_amount ) == 2 ) {
					$products = $products2;
				}

			} else {
				$product_id = get_option( 'wc_free_gift_product_id', '' );
				$product_id2 = get_option( 'wc_free_gift_product_id2', '' );
				$_product2 = $this->get_product( $product_id2 );
				if ( $product_id != '' && $product_id2 != '' && ( is_object( $_product2 ) && !$_product2->is_in_stock() ) ) {
					$product_id2 = '';
					$product_id = '';
				}
				if ( $this->is_customer_eligible( $the_amount ) == 2 ) {
					$product_id = $product_id2;
				}
				if ( $product_id != '' ) {
					array_push( $products, $product_id );
				}

			}

			$products_to_return = array();
			$products_to_return_with_id = array();
			foreach ( $products as $product_id ) {
				$_product = $this->get_product( $product_id );
				if ( !in_array( $this->get_product_post($_product)->post_title, array_values( $products_to_return ) ) && $_product->exists() && $_product->is_in_stock() ) {
					$products_to_return[$product_id] = $this->get_product_post($_product)->post_title;
					$products_to_return_with_id[$product_id] = $this->get_product_post($_product)->post_title . ' (' . ( $_product instanceof WC_Product_Variation ? 'variation id:' : 'id:' ) . $product_id . ')';
				}
				unset( $_product );
			}

			return ( $with_id ? $products_to_return_with_id : $products_to_return );
		}

		/**
		 * @param $possible_products
		 * @param $placeholder
		 * @param $message
		 * @return mixed
		 */
		protected function str_replace_products( $possible_products, $placeholder, $message ) {

			if ( empty ( $possible_products ) ) {
				return $message;
			}

			$products = implode( ' ' . __( 'or', 'wc_free_gift' ) . ' ', array_values( $possible_products ) );

			return str_replace( $placeholder, $products, $message );

		}

		/**
		 * Provides a combobox visible on cart page. Applies to category gift type.
		 */

		public function choose_gift_older_wc() {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_type', '' ) == '' && get_option( 'wc_free_gift_let_choose', 'no' ) == 'no' ) {
				return;
			}
			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'yes' && $this->customer_gift_allowed() && ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() ) ) {

				$the_amount = $woocommerce->cart->subtotal;

				if ( $this->is_customer_eligible( $the_amount ) ) { // eligible for a free gift?

					$products = $this->get_possible_products( false, false, $the_amount );
					if ( empty( $products ) ) {
						return;
					}

					echo '<select id="wc_free_gift_chosen_gift" name="wc_free_gift_chosen_gift">';
					echo '<option class="choose-gift-option" value="">' . __( 'Choose your free gift', 'wc_free_gift' ) . '&hellip;</option>';
					foreach ( $products as $id => $value ) {
						echo '<option value="' . $id . '" ' . ( ( $id == $this->get_from_session() ) ? 'selected' : '' ) . '>' . $value . '</option>';
					}
					echo '</select>';

					$this->run_js(
						'

						jQuery("#wc_free_gift_chosen_gift").click(function() {
						    jQuery("#wc_free_gift_chosen_gift option[value=\'\']").text("' . __( 'I don\'t want any gift', 'wc_free_gift' ) . '");
						});

						jQuery("#wc_free_gift_chosen_gift").change(function() {

							var $this = jQuery(this);

							var data = {
								action: "wc_free_gift_chosen",
								security: "' . wp_create_nonce( "wc_free_gift_chosen_nonce" ) . '",
								product_id: $this.val()
							};

							jQuery.post("' . admin_url( 'admin-ajax.php' ) . '", data, function (response) {

								if ( !response )
									return;

							});

						}).change();
					'
					);

				}
			}

		}

		/**
		 * newer version of choose_gift() - for WC >= 2.6
		 */
		public function choose_gift() {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_type', '' ) == '' && get_option( 'wc_free_gift_let_choose', 'no' ) == 'no' ) {
				return;
			}
			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'yes' && $this->customer_gift_allowed() && ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() ) ) {

				$the_amount = $woocommerce->cart->subtotal;

				if ( $this->is_customer_eligible( $the_amount ) ) { // eligible for a free gift?

					$products = $this->get_possible_products( false, false, $the_amount );
					if ( !empty( $products ) ) {
						echo '<select id="wc_free_gift_chosen_gift" name="wc_free_gift_chosen_gift">';
						echo '<option class="choose-gift-option" value="">' . __( 'Choose your free gift', 'wc_free_gift' ) . '&hellip;</option>';
						foreach ( $products as $id => $value ) {
							echo '<option value="' . $id . '" ' . ( ( $id == $this->get_from_session() ) ? 'selected' : '' ) . '>' . $value . '</option>';
						}
						echo '</select>';
					}

				}

				$this->run_js(
					'

					jQuery("#wc_free_gift_chosen_gift").click(function() {
						jQuery("#wc_free_gift_chosen_gift option[value=\'\']").text("' . __( 'I don\'t want any gift', 'wc_free_gift' ) . '");
						});

					jQuery(".woocommerce").on("change", "#wc_free_gift_chosen_gift", function() {

						var $this = jQuery("#wc_free_gift_chosen_gift");

						var data = {
							action: "wc_free_gift_chosen",
							security: "' . wp_create_nonce( "wc_free_gift_chosen_nonce" ) . '",
							product_id: $this.val()
						};

						jQuery.post("' . admin_url( 'admin-ajax.php' ) . '", data, function (response) {

							if ( !response )
								return;

						});

					});

					jQuery("#wc_free_gift_chosen_gift").change();

					jQuery(".woocommerce").on("click", "input[name=\'update_cart\']", function(){
						setTimeout(function() {
							jQuery("#wc_free_gift_chosen_gift").change();

							// now we get rid of possible mutliple messages
							var eligibleMsg = "";
							var firstEligible = false;
							var motivatingMsg = "";
							var firstMotivating = false;
							jQuery(".woocommerce-message").each(function() {
								if (jQuery(this).text().substring(0, 3) == "' . substr( get_option( 'wc_free_gift_eligible_message' ), 0, 3 ) . '") {
									eligibleMsg = this;
									if (!firstMotivating) {
										firstEligible = true;
									}
								}
								if (jQuery(this).text().substring(0, 3) == "' . substr( get_option( 'wc_free_gift_motivating_message' ), 0, 3 ) . '") {
									motivatingMsg = this;
									if (!firstEligible) {
										firstMotivating = true;
									}
								}
							});
							if (eligibleMsg != "" && motivatingMsg != "") {
								if (firstEligible) {
									jQuery(eligibleMsg).remove();
								} else if (firstMotivating) {
									jQuery(motivatingMsg).remove();
								}
							}
						}, 2000);
					});
				'
				);

			}

		}

		/**
		 * Triggers when customers select a gift.
		 */
		public function gift_chosen() {

			global $woocommerce;

			check_ajax_referer( 'wc_free_gift_chosen_nonce', 'security' );

			$selected_gift = isset( $_POST['product_id'] ) ? woocommerce_clean( $_POST['product_id'] ) : '';

			$this->save_to_session( $selected_gift );

		}

		/**
		 * Hooks on woocommerce_review_order_after_cart_contents action.
		 */
		public function order_info() {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'no' ) {
				return;
			}

			if ( ( get_option( 'wc_free_gift_coupon_no_gift', 'no' ) == 'yes' && $this->is_coupon_applied() ) ) {
				return;
			}

			if ( !$this->customer_gift_allowed() ) {
				return;
			}

			$prod_id = null;

			$the_amount = $woocommerce->cart->subtotal;

			if ( get_option( 'wc_free_gift_type', '' ) == 'category' || get_option( 'wc_free_gift_let_choose', 'no' ) == 'yes' ) {
				$prod_id = $this->get_from_session();
				if ( get_option( 'wc_free_gift_type', '' ) == 'category' && ( get_option( 'wc_free_gift_category_id', '' ) == '' || ( !in_array( $prod_id, array_keys( $this->get_products( get_option( 'wc_free_gift_category_id' ) ) ) ) && !in_array( $prod_id, array_keys( $this->get_products( get_option( 'wc_free_gift_category_id2' ) ) ) ) ) ) ) { // we double check here that the gift product id is really from the valid category
					return;
				}
			} else {
				$prods = array_keys( $this->get_possible_products( false, false, $the_amount ) );
				$prod_id = $prods[0];
			}

			if ( !$prod_id ) {
				return;
			}
			$_product = $this->get_product( $prod_id );

			if ( isset($_product) && $_product->exists() && $_product->is_in_stock()
				&& $this->is_customer_eligible( $the_amount )
				&& ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() )
			) { // eligible for a free item
				$price = __( 'Free!', 'woocommerce' );
				// thangtqvn added - BEGIN
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $prod_id ), 'thumbnail' );
				echo '
					<tr>
					<td class="product-name">' .
					'<div class="minicart__product mini-product--group cart_item">' .
						'<img style="width: 94px;" src="'. $image[0] .'" />' .
						'<div class="mini-product__info">' .
							'<div class="mini-product__item name">' .
								'<span class="product-gift-name">' . apply_filters( 'woocommerce_checkout_product_title', $_product->get_title(), $_product ) . ' </span>' .
							'</div>' .
							'<div class="mini-product__item mini-product__attribute">
								<span class="cart-label">数量: </span>
								<span class="value">'.get_option( 'wc_free_gift_quantity', 1 ).'
									<span class="product-gift-price" style="' . get_option( 'wc_free_gift_price_css', 'color: #00aa00;' ) . '">' . $price . '</span>
								</span>
							</div>' .
							'<div class="mini-product__item mini-free-message">
								<span class="cart-label">'. get_option('wc_free_gift_message_thanks') .'</span>
							</div>' .
						'</div>' .
					'</div>' .
					'</td>
					<td></td>
					</tr>';
				// thangtqvn added - END
			}

		}

		/**
		 * Hooks on woocommerce_new_order action.
		 *
		 * @param $order_id
		 */
		// thangtqvn modified function "woocommerce_new_order" to "woocommerce_checkout_update_order_meta" - END
		public function add_to_order( $order_id, $posted ) {

			global $woocommerce;

			if ( !$woocommerce->cart ) {
				return;
			}
            
			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'no' ) {
				return;
			}

			if ( ( get_option( 'wc_free_gift_coupon_no_gift', 'no' ) == 'yes' && $this->is_coupon_applied() ) ) {
				return;
			}

			if ( !$this->customer_gift_allowed() ) {
				return;
			}

            $the_amount = $woocommerce->cart->subtotal;

			if ( get_option( 'wc_free_gift_type', '' ) == 'category' || get_option( 'wc_free_gift_let_choose', 'no' ) == 'yes' ) {
				$prod_id = $this->get_from_session();
				if ( get_option( 'wc_free_gift_type', '' ) == 'category' && ( get_option( 'wc_free_gift_category_id', '' ) == '' || ( !in_array( $prod_id, array_keys( $this->get_products( get_option( 'wc_free_gift_category_id' ) ) ) ) && !in_array( $prod_id, array_keys( $this->get_products( get_option( 'wc_free_gift_category_id2' ) ) ) ) ) ) ) { // we double check here that the gift product id is really from the valid category
					return;
				}
			} else {
				$prods = array_keys( $this->get_possible_products( false, false, $the_amount ) );
				$prod_id = $prods[0];
			}

			if ( !$prod_id ) {
				return;
			}
			$_product = $this->get_product( $prod_id );

			$this->save_to_session( '' );

			if ( isset($_product) && $_product->exists() && $_product->is_in_stock()
				&& $this->is_customer_eligible( $the_amount )
				&& ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() )
			) {

				$item = array();

				$item['variation_id'] = $this->get_variation_id($_product);
				@$item['variation_data'] = $item['variation_id'] ? $this->get_variation_attributes($_product) : '';

				// Add line item
				$item_id = $this->woocommerce_add_order_item( $order_id, array(
					'order_item_name' => $_product->get_title(),
					'order_item_type' => 'line_item'
				) );

				// Add line item meta
				if ( $item_id ) {
					$this->woocommerce_add_order_item_meta( $item_id, '_qty', get_option( 'wc_free_gift_quantity', 1 ) );
					$this->woocommerce_add_order_item_meta( $item_id, '_tax_class', $_product->get_tax_class() );
					$this->woocommerce_add_order_item_meta( $item_id, '_product_id', $prod_id );
					$this->woocommerce_add_order_item_meta( $item_id, '_variation_id', $this->get_variation_id($_product) );
					$this->woocommerce_add_order_item_meta( $item_id, '_line_subtotal', $this->woocommerce_format_decimal( 0, 4 ) );
					$this->woocommerce_add_order_item_meta( $item_id, '_line_total', $this->woocommerce_format_decimal( 0, 4 ) );
					$this->woocommerce_add_order_item_meta( $item_id, '_line_tax', $this->woocommerce_format_decimal( 0, 4 ) );
					$this->woocommerce_add_order_item_meta( $item_id, '_line_subtotal_tax', $this->woocommerce_format_decimal( 0, 4 ) );
					$this->woocommerce_add_order_item_meta( $item_id, '_free_gift', 'yes' );

					if ( version_compare( WOOCOMMERCE_VERSION, "2.2.0" ) >= 0 ) {
						$this->woocommerce_add_order_item_meta( $item_id, '_line_tax_data', array( 'total' => array(), 'subtotal' => array() ) );
					}

					// Store variation data in meta so admin can view it
					if ( @$item['variation_data'] && is_array( $item['variation_data'] ) ) {
						foreach ( $item['variation_data'] as $key => $value ) {
							$this->woocommerce_add_order_item_meta( $item_id, esc_attr( str_replace( 'attribute_', '', $key ) ), $value );
						}
					}

					if ( get_option( 'wc_free_gift_only_once', 'no' ) == 'yes' ) {
						$user_id = get_current_user_id();
						if ( $user_id ) {
							update_user_meta( $user_id, '_wc_free_gift_already_given', "yes" );
						}
					}

				}

			}

		}

		/**
		 * Hooks on woocommerce_order_table_product_title filter.
		 *
		 * @param $title
		 * @param $item
		 * @return string
		 */
		public function modify_title( $title, $item ) {

			if ( @$item['item_meta']['_free_gift'][0] == 'yes' ) {
				return $title . ' <span style="' . get_option( 'wc_free_gift_price_css', 'color: #00aa00;' ) . '">(' . __( 'Free!', 'woocommerce' ) . ')</span>';
			}

			return $title;

		}

		/**
		 * Provides a shortocde for the message if the customer is eligible for a free gift or not yet.
		 *
		 * @param $atts
		 * @return string
		 */
		public function shortcode_gift_message( $atts ) {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'no' ) {
				return '';
			}

			if ( ( get_option( 'wc_free_gift_coupon_no_gift', 'no' ) == 'yes' && $this->is_coupon_applied() ) ) {
				return '';
			}

			if ( !$this->customer_gift_allowed() ) {
				return '';
			}

			// thangtqvn modified - BEGIN
			extract( shortcode_atts( array(
				'class' => 'wc_free_gift_message',
				'amount' => ''
			), $atts ) );

			$the_amount = $amount ? $amount : $woocommerce->cart->subtotal;

			$possible_products = $this->get_possible_products( false, false, $the_amount );

			if ( empty ( $possible_products ) ) {
				return '';
			}

			if ( get_option( 'wc_free_gift_only_logged', 'no' ) == 'no' || is_user_logged_in() ) {

				if ( $this->is_customer_eligible( $the_amount ) ) { // eligible for a free gift?
					if ( get_option( 'wc_free_gift_eligible_message_enabled', 'yes' ) == 'yes' ) {
						$eligible_msg = get_option( 'wc_free_gift_eligible_message', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
						
						// thangtqvn modified - BEGIN
						if ($amount)
						{
							$eligible_msg = get_option( 'wc_free_gift_message_thanks_top', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
						}
						else {
							$eligible_msg = get_option( 'wc_free_gift_eligible_message', __( 'You are eligible for a free gift after checkout.', 'wc_free_gift' ) );
						}
						// thangtqvn modified - END
						
						$eligible_msg = $this->str_replace_products( $possible_products, '%PRODUCT%', $eligible_msg );
						$this->last_msg = $eligible_msg;
						return '<div class="' . $class . '">' . apply_filters( 'woocommerce_free_gift_eligible_message', $eligible_msg ) . '</div>';
					}
				} else {
					if ( get_option( 'wc_free_gift_motivating_message_enabled', 'yes' ) == 'yes' ) {
						$motivating_msg = get_option( 'wc_free_gift_motivating_message', __( 'By spending at least %PRICE%, you will be eligible for a free gift after checkout.', 'wc_free_gift' ) );
						$motivating_msg = $this->str_replace_products( $possible_products, '%PRODUCT%', $motivating_msg );
						$motivating_msg = str_replace( '%PRICE%', $this->get_price( get_option( 'wc_free_gift_minimal_total' ) ), $motivating_msg );
						$motivating_msg = str_replace( '%PRICE_BETTER%', $this->get_price( get_option( 'wc_free_gift_minimal_total2' ) ), $motivating_msg );
						$motivating_msg = str_replace( '%REMAINING_AMOUNT%', $this->get_price( floatval( get_option( 'wc_free_gift_minimal_total' ) ) - floatval( $the_amount ) ), $motivating_msg );
						$motivating_msg = str_replace( '%REMAINING_AMOUNT_BETTER%', $this->get_price( floatval( get_option( 'wc_free_gift_minimal_total2' ) ) - floatval( $the_amount ) ), $motivating_msg );
						$this->last_msg = $motivating_msg;
						return '<div class="' . $class . '">' . apply_filters( 'woocommerce_free_gift_motivating_message', $motivating_msg ) . '</div>';
					}
				}
			}
			
			// thangtqvn modified - END

			return '';

		}

		/**
		 * @param $cat_args
		 * @return mixed
		 */
		public function hide_gift_category( $cat_args ) {
			if ( get_option( 'wc_free_gift_type', '' ) == 'category' && get_option( 'wc_free_gift_hide', 'no' ) == 'yes' ) {
				$cat_args['exclude'] = array( get_option( 'wc_free_gift_category_id' ) );
			}
			return $cat_args;
		}

		/**
		 * @param $posts
		 * @return array
		 */
		public function hide_gifts( $posts ) {

			$posts_to_return = array();

			if ( is_admin() ) return $posts;

			if ( get_option( 'wc_free_gift_enabled', 'no' ) == 'no' ) return $posts;

			if ( is_single() ) return $posts;

			if ( get_option( 'wc_free_gift_hide', 'no' ) == 'yes' ) {

				if ( !empty( $posts ) ) {
					foreach ( $posts as $post ) {

						if ( get_option( 'wc_free_gift_type', '' ) == 'category' ) {

							$cat_gifts = array_keys( $this->get_products( get_option( 'wc_free_gift_category_id' ) ) );
							if ( in_array( $post->ID, $cat_gifts ) ) continue; // hide the gift products
							unset( $cat_gifts );

						} else if ( $post->ID == get_option( 'wc_free_gift_product_id', '' ) || $post->ID == get_option( 'wc_free_gift_product_id2', '' ) ) {

							continue; // hide the gift product

						}

						array_push( $posts_to_return, $post );

					}
				}

			} else {

				return $posts;

			}

			return $posts_to_return;

		}

		/**
		 * Includes inline JavaScript.
		 *
		 * @param $js
		 */
		protected function run_js( $js ) {

			global $woocommerce;

			if ( function_exists( 'wc_enqueue_js' ) ) {
				wc_enqueue_js( $js );
			} else {
				$woocommerce->add_inline_js( $js );
			}

		}

		/**
		 * Return a list of WooCommerce products.
		 *
		 * @return array
		 */
		protected function get_products( $category_id = 0 ) {

			$products = array();

			for ( $offset = 0; ; $offset += 50 ) {
				$posts = get_posts( array( 'post_type' => 'product', 'status' => 'published', 'numberposts' => '50', 'offset' => $offset, 'orderby' => 'post_date' ) );

				if ( empty( $posts ) ) break;

				foreach ( $posts as $post ) {

					$_product = $this->get_product( $post->ID );

					if ( $category_id != 0 ) {
						$terms = get_the_terms( $post->ID, 'product_cat' );
						if ( !is_array( $terms ) ) continue;
						$found = false;
						foreach ( $terms as $term ) {
							if ( $term->term_id == $category_id ) {
								$found = true;
							}
						}
						if ( !$found ) {
							continue;
						}
					}

					if ( $this->get_product_type($_product) == 'simple' ) {
						$products[$post->ID] = $this->get_product_post($_product)->post_title . ' (id: ' . $post->ID . ')';
					} else if ( $this->get_product_type($_product) == 'variable' ) {
						$children = $_product->get_children();
						foreach ( $children as $child_id ) {
							$child_product = $this->get_product( $child_id );
							if ( $child_product instanceof WC_Product_Variation ) {
								$products[$this->get_variation_id($child_product)] = $this->get_product_post($child_product)->post_title . ' (variation id: ' . $this->get_variation_id($child_product) . ')';
							}
							unset( $child_product );
						}
						unset( $children );
					}
					unset( $_product );
					unset( $post );
				}

				unset( $posts );

			}

			return $products;

		}

		/**
		 * Return a list of WooCommerce categories.
		 *
		 * @return array
		 */
		protected function get_categories() {

			$args = array(
				'hide_empty' => true,
			);

			$cat_list = get_terms( 'product_cat', $args );

			$categories = array();
			foreach ( $cat_list as $category ) {
				$categories[$category->term_id] = $category->name . ' (category id: ' . $category->term_id . ')';
			}

			return $categories;

		}

		/**
		 * Check if a coupon code has been applied.
		 *
		 * @return bool
		 */
		protected function is_coupon_applied() {

			global $woocommerce;

			return !( empty( $woocommerce->cart->applied_coupons ) );

		}

		protected function customer_gift_allowed() {

			global $woocommerce;

			if ( get_option( 'wc_free_gift_coupon_no_gift', 'no' ) == 'yes' && $this->is_coupon_applied() ) {
				return false;
			}

			$user_id = get_current_user_id();
			if ( !$user_id ) {
				return true;
			}

			if ( get_option( 'wc_free_gift_only_once', 'no' ) == 'no' ) {
				return true;
			}

			$already_given = get_user_meta( $user_id, '_wc_free_gift_already_given', true );

			if ( !empty( $already_given ) && $already_given == "yes" ) {
				return false;
			}

			return true;

		}

		/**
		 * Adds a WooCommerce message.
		 *
		 * @param $message
		 */
		protected function add_wc_message( $message ) {

			global $woocommerce;

			if ( version_compare( WOOCOMMERCE_VERSION, "2.4.0" ) >= 0 ) {
				$messages = wc_get_notices( 'success' );
				if ( !in_array( $message, $messages ) ) { // we want to prevent adding the same message twice
					wc_add_notice( $message );
				}
			} else
				if ( version_compare( WOOCOMMERCE_VERSION, "2.3.0" ) >= 0 ) {
					$messages = wc_get_notices();
					if ( !in_array( $message, $messages ) ) { // we want to prevent adding the same message twice
						wc_add_notice( $message );
					}
				} else {
					$messages = $woocommerce->get_messages();
					if ( !in_array( $message, $messages ) ) { // we want to prevent adding the same message twice
						$woocommerce->add_message( $message );
					}
				}

		}

		protected function is_customer_eligible( $the_amount ) {

			if ( get_option( 'wc_free_gift_minimal_total', '' ) == '' ) {

				return false;

			}

			$result = false;

			if ( floatval( $the_amount ) >= floatval( get_option( 'wc_free_gift_minimal_total' ) ) ) {

				$result = 1;

			}

			if ( get_option( 'wc_free_gift_minimal_total2', '' ) != '' && floatval( $the_amount ) >= floatval( get_option( 'wc_free_gift_minimal_total2' ) ) ) {

				$result = 2;

			}

			return $result;

		}

		protected function get_product( $product_id ) {

			if ( version_compare( WOOCOMMERCE_VERSION, "2.4.0" ) >= 0 ) {
				$_product = wc_get_product( $product_id );
			} else {
				$_product = get_product( $product_id );
			}

			return $_product;

		}

		protected function woocommerce_add_order_item( $order_id, $arr ) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return wc_add_order_item( $order_id, $arr );
			} else {
				return woocommerce_add_order_item( $order_id, $arr );
			}
		}

		protected function woocommerce_add_order_item_meta( $item_id, $key, $value ) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				wc_add_order_item_meta( $item_id, $key, $value );
			} else {
				woocommerce_add_order_item_meta( $item_id, $key, $value );
			}
		}

		protected function woocommerce_format_decimal( $a, $b ) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return wc_format_decimal( $a, $b );
			} else {
				return woocommerce_format_decimal( $a, $b );
			}
		}

		protected function save_to_session( $value ) {
			global $woocommerce;
			if ( version_compare( WOOCOMMERCE_VERSION, "2.6.0" ) >= 0 ) {
				if (WC()->session) {
					WC()->session->set( 'wc_free_gift_chosen_gift', $value );
				}
			} else {
				if ($woocommerce->session) {
					$woocommerce->session->wc_free_gift_chosen_gift = $value;
				}
			}
		}

		protected function get_from_session() {
			global $woocommerce;
			if ( version_compare( WOOCOMMERCE_VERSION, "2.6.0" ) >= 0 ) {
				$result = WC()->session->get( 'wc_free_gift_chosen_gift' );
			} else {
				$result = $woocommerce->session->wc_free_gift_chosen_gift;
			}
			return $result;
		}

		protected function get_product_post($_product) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return get_post($_product->get_id());
			} else {
				return $_product->post;
			}
		}

		protected function get_product_type($_product) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return $_product->get_type();
			} else {
				return $_product->product_type;
			}
		}

		protected function get_variation_id($_product) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return $_product->get_id();
			} else {
				return $_product->variation_id;
			}
		}

		protected function get_price($price) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return wc_price($price);
			} else {
				return woocommerce_price($price);
			}
		}

		protected function get_variation_attributes($_product) {
			if ( version_compare( WOOCOMMERCE_VERSION, "2.7.0" ) >= 0 ) {
				return wc_get_product_variation_attributes( $_product->get_id() );
			} else {
				return $_product->get_variation_attributes();
			}
		}

	}

	new WooCommerce_Free_Gift();

}