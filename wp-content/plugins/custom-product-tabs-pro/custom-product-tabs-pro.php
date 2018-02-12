<?php
/**
 * Plugin Name: Custom Product Tabs Pro
 * Plugin URI: https://www.yikesplugins.com
 * Description: Extend Custom Product Tabs for WooCommerce with enhanced features like global tabs, category-based tabs and more!
 * Author: YIKES, Inc.
 * Author URI: https://www.yikesplugins.com
 * Version: 1.0.3
 * Text Domain: custom-product-tabs-pro
 * Domain Path: languages/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.3
 *
 * Copyright: (c) 2017-2018 YIKES Inc.
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Must include plugin.php to use is_plugin_active()
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Check if our base plugin is installed
	if ( is_plugin_active( 'yikes-inc-easy-custom-woocommerce-product-tabs/yikes-inc-easy-custom-woocommerce-product-tabs.php' ) ) {

		// Instantiate the add-on!
		new YIKES_Custom_Product_Tabs_Pro();

	} else {

		// Deactivate the plugin, and display our error notification 
		deactivate_plugins( '/custom-product-tabs-pro/custom-product-tabs-pro.php' );
		add_action( 'admin_notices' , 'cptpro_display_admin_notice_error' );
	}
	
	/**
	* Display our error admin notice if the base plugin Custom Product Tabs is not installed & active
	*/
	function cptpro_display_admin_notice_error() {
		?>	
			<!-- hide the 'Plugin Activated' default message -->
			<style> #message.updated { display: none; } </style>

			<!-- display our error message -->
			<div class="error">
				<p><?php _e( 'Custom Product Tabs Pro could not be activated because Custom Product Tabs is not installed and active.', 'custom-product-tabs-pro' ); ?></p>
				<p><?php echo sprintf( __( 'Please install and activate %1s before activating the plugin.', 'custom-product-tabs-pro' ),
					'<a href="https://wordpress.org/plugins/yikes-inc-easy-custom-woocommerce-product-tabs/" title="Custom Product Tabs" target="_blank">Custom Product Tabs</a>' ); ?></p>
			</div>
		<?php
	}
	
	/**
	* Initialize the Custom Product Tab Class
	*/
	class YIKES_Custom_Product_Tabs_Pro {

		/**
		* Construct :)
		*/
		public function __construct() {

			$this->define_constants();

			// Require our classes
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/admin.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/saved-tabs-list.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/saved-tabs-single.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/saved-tabs.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/settings.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/licensing.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/search.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/support.php';
			require_once YIKES_Custom_Product_Tabs_Pro_Path . 'classes/EDD_SL_Plugin_Updater.php';

			add_action( 'admin_init', array( $this, 'init' ) );

			// i18n
			add_action( 'plugins_loaded', array( $this, 'cptpro_load_plugin_textdomain' ) );
		}

		/**
		* Define our constants
		*/
		private function define_constants() {

			/**
			* Define the text domain
			*
			* Currently not used
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_Text_Domain' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_Text_Domain', 'custom-product-tabs-pro' );
			}

			/**
			* Define the plugin's version
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_Version' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_Version', '1.0.3' );
			}

			/**
			* Define the plugin's URI
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_URI' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_URI', plugin_dir_url( __FILE__ ) );
			}

			/**
			* Define the plugin's path
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_Path' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_Path', plugin_dir_path( __FILE__ ) );
			}

			/**
			* Set a constant so we know if this plugin is enabled
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_Enabled' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_Enabled', true );
			}

			/**
			* Define the plugin's settings page slug
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_Settings_Page' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_Settings_Page', 'cptpro-settings' );
			}

			/**
			* Define the plugin's license URL
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_License_URL' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_License_URL', 'https://yikesplugins.com' );				
			}

			/**
			* Define the plugin's license item ID
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_License_Item_ID' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_License_Item_ID', 8314 );
			}

			/**
			* Define the plugin's update path
			*/
			if ( ! defined( 'YIKES_Custom_Product_Tabs_Pro_License_Path' ) ) {
				define( 'YIKES_Custom_Product_Tabs_Pro_License_Path', __FILE__ );
			}
		}

		/**
		* Run our basic plugin setup
		*/
		public function init() {

			// Add settings link to plugin on plugins page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_plugin_action_links' ), 10, 1 );
		}

		
		/* i18n */

		/**
		* Register the textdomain for proper i18n / l10n
		*/
		public function cptpro_load_plugin_textdomain() {
			load_plugin_textdomain(
				'custom-product-tabs-pro',
				false,
				YIKES_Custom_Product_Tabs_Pro_Path . 'languages/'
			);
		}

		/* End i18n */

		/**
		* Add a link to the settings page from the plugin's action links
		*
		* @param array | $links | array of links passed from the plugin_action_links_{plugin_name} filter
		*/
		public function add_plugin_action_links( $links ) {
			$settings_href  = add_query_arg( array( 'page' => YIKES_Custom_Product_Tabs_Pro_Settings_Page ), admin_url( 'admin.php' ) );
			$support_href   = add_query_arg( array( 'page' => YIKES_Custom_Product_Tabs_Support_Page ), admin_url( 'admin.php' ) );
			$knowledge_base = 'https://yikesplugins.com/support/knowledge-base/product/easy-custom-product-tabs-for-woocommerce/';
			$links[] = '<a href="' . esc_url_raw( $settings_href ) . '">Settings</a>';
			$links[] = '<a href="' . esc_url_raw( $support_href ) . '">Support</a>';
			$links[] = '<a href="' . esc_url_raw( $knowledge_base ) . '" target="_blank">Knowledge Base</a>';
			return $links;
		}

	}
