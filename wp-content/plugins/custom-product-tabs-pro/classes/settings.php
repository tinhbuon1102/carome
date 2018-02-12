<?php

	class YIKES_Custom_Product_Tabs_Pro_Settings {

		/**
		* Constructo >:^)
		*/
		public function __construct() {

			// Add our custom settings page
			add_action( 'admin_menu', array( $this, 'register_settings_subpage' ), 20 );

			// Enqueue scripts & styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10, 1 );

			// AJAX call to save settings
			add_action( 'wp_ajax_cptpro_save_settings', array( $this, 'save_settings' ) );

			// Hide our tab title depending on the value of our `hide_tab_title` settings value
			add_action( 'init', array( $this, 'maybe_hide_tab_title' ), 10 );
		}

		/**
		* Enqueue our scripts and styes
		*
		* @param string | $page | The slug of the page we're currently on
		*/
		public function enqueue_scripts( $page ) {

			if ( $page === 'custom-product-tabs-pro_page_' . YIKES_Custom_Product_Tabs_Pro_Settings_Page ) {
				wp_enqueue_style ( 'repeatable-custom-tabs-styles' , YIKES_Custom_Product_Tabs_URI . 'css/repeatable-custom-tabs.min.css', '', YIKES_Custom_Product_Tabs_Version, 'all' );
				wp_enqueue_script( 'icheck', YIKES_Custom_Product_Tabs_Pro_URI . 'js/icheck.min.js', array( 'jquery' ) );
				wp_enqueue_style ( 'icheck-flat-blue-styles', YIKES_Custom_Product_Tabs_Pro_URI . 'css/flat/blue.css' );
				wp_enqueue_style ( 'cptpro-settings-styles', YIKES_Custom_Product_Tabs_Pro_URI . 'css/settings.min.css' );
				wp_enqueue_script( 'cptpro-shared-scripts', YIKES_Custom_Product_Tabs_Pro_URI . 'js/shared.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'cptpro-settings-scripts', YIKES_Custom_Product_Tabs_Pro_URI . 'js/settings.min.js', array( 'icheck' ) );
				wp_localize_script( 'cptpro-settings-scripts', 'settings_data', array(
						'ajax_url'                  => admin_url( 'admin-ajax.php' ),
						'save_settings_nonce'       => wp_create_nonce( 'cptpro_save_settings' ),
						'save_settings_action'      => 'cptpro_save_settings',
						'activate_license_action'   => 'cptpro_activate_license',
						'activate_license_nonce'    => wp_create_nonce( 'cptpro_activate_license' ),
						'deactivate_license_action' => 'cptpro_deactivate_license',
						'deactivate_license_nonce'  => wp_create_nonce( 'cptpro_deactivate_license' ),
						'check_license_action'      => 'cptpro_check_license',
						'check_license_nonce'       => wp_create_nonce( 'cptpro_check_license' ),
					)
				);
			}
		}

		/**
		* Check our settings to decide whether we need to add our tab-title-hiding filter
		*/
		public function maybe_hide_tab_title() {

			// Get our option
			$settings = get_option( 'cptpro_settings', array() );

			// If `hide_tab_title` is true, add our filters
			if ( isset( $settings['hide_tab_title'] ) && $settings['hide_tab_title'] === true ) {

				// Hide our custom tab's title
				add_filter( 'yikes_woocommerce_custom_repeatable_product_tabs_heading', '__return_false', 99 );

				// Hide the description tab's title
				add_filter( 'woocommerce_product_description_heading', '__return_false', 99 );

				// Hide the additional information tab's title
				add_filter( 'woocommerce_product_additional_information_heading', '__return_false', 99 );
			}
		}

		/**
		* Save our settings [AJAX]
		*/
		public function save_settings() {

			// Verify the nonce
			if ( ! check_ajax_referer( 'cptpro_save_settings', 'nonce', false ) ) {
			 	wp_send_json_error();
			}

			// Get our option
			$settings = get_option( 'cptpro_settings', array() );

			// Handle hide tab title
			$hide_tab_title = isset( $_POST['hide_tab_title'] ) && $_POST['hide_tab_title'] === 'true' ? true : false;
			$settings['hide_tab_title'] = $hide_tab_title;

			// Handle Add Tabs to Search
			$search_wordpress = isset( $_POST['search_wordpress'] ) && $_POST['search_wordpress'] === 'true' ? true : false;
			$settings['search_wordpress'] = $search_wordpress;

			// Handle Restricting the Search to Products/WooCommerce
			$search_woo = isset( $_POST['search_woo'] ) && $_POST['search_woo'] === 'true' ? true : false;
			$settings['search_woo'] = $search_woo;

			update_option( 'cptpro_settings', $settings );

			wp_send_json_success();
		}

		/**
		* Register our settings page
		*/
		public function register_settings_subpage() {

			// Add our custom settings page
			add_submenu_page(
				YIKES_Custom_Product_Tabs_Settings_Page,                             // Parent menu item slug
				__( 'Settings', 'custom-product-tabs-pro' ),         // Tab title name (HTML title)
				__( 'Settings', 'custom-product-tabs-pro' ),         // Menu page name
				apply_filters( 'cptpro-pro-settings-capability', 'publish_products' ), // Capability required
				YIKES_Custom_Product_Tabs_Pro_Settings_Page,                         // Page slug (?page=slug-name)
				array( $this, 'settings_page' )                                      // Function to generate page
			);
		}

		/**
		* Include our settings page
		*/
		public function settings_page() {

			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'partials/page-settings.php';
		}

		
	}

	new YIKES_Custom_Product_Tabs_Pro_Settings();