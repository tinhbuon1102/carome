<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WC_ADP_EDD {

	private static $instance;
	/**
	 * WC_Order_Export_EDD constructor.
	 */
	private function __construct() {
		// EDD license actions
		add_action( 'admin_init', array( $this, 'edd_wc_adp_plugin_updater' ), 0 );
		add_action( 'admin_init', array( $this, 'edd_wc_adp_register_option' ) );
		add_action( 'admin_init', array( $this, 'edd_wc_adp_activate_license' ) );
		add_action( 'admin_init', array( $this, 'edd_wc_adp_deactivate_license' ) );
	}

	//***********  EDD LICENSE FUNCTIONS BEGIN  *************************************************************************************************************************************************************************************************************************************************
	function edd_wc_adp_plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'edd_wc_adp_license_key' ) );

		// setup the updater
		$edd_updater = new WC_ADP_Updater( WC_ADP_STORE_URL, 'advanced-dynamic-pricing-for-woocommerce-pro/advanced-dynamic-pricing-for-woocommerce-pro.php', array(
				'version' 	=> WC_ADP_VERSION,   // current version number
				'license' 	=> $license_key,  // license key (used get_option above to retrieve from DB)
				'item_name' => WC_ADP_ITEM_NAME, // name of this plugin
				'author' 	=> WC_ADP_AUTHOR     // author of this plugin
			)
		);

	}

	function edd_wc_adp_license_page() {
		$this->error_messages = array(
			'missing' => __('not found', 'advanced-dynamic-pricing-for-woocommerce'),
			'license_not_activable' => __('is not activable', 'advanced-dynamic-pricing-for-woocommerce'),
			'revoked' => __('revoked', 'advanced-dynamic-pricing-for-woocommerce'),
			'no_activations_left' => __('no activations left', 'advanced-dynamic-pricing-for-woocommerce'),
			'expired' => __('expired', 'advanced-dynamic-pricing-for-woocommerce'),
			'key_mismatch' => __('key mismatch', 'advanced-dynamic-pricing-for-woocommerce'),
			'invalid_item_id' => __('invalid item ID', 'advanced-dynamic-pricing-for-woocommerce'),
			'item_name_mismatch' => __('item name mismatch', 'advanced-dynamic-pricing-for-woocommerce'),
		);

		$license 	= get_option( 'edd_wc_adp_license_key' );
		$status 	= get_option( 'edd_wc_adp_license_status' );
		$error 	    = get_option( 'edd_wc_adp_license_error' );
		if( isset($this->error_messages[$error]) ) {
			$error = $this->error_messages[$error];
		}

		$site_url = 'https://algolplus.com';
		$site_link_html = sprintf('<a target="_blank" href="%s">%s</a>', $site_url,$site_url);
		$account_url = 'https://algolplus.com/plugins/my-account';
		$account_link_html = sprintf('<a target="_blank" href="%s">%s</a>', $account_url,$account_url);
		$dashboard_link = sprintf('<a target="_blank" href="%s">%s</a>', admin_url( 'update-core.php' ), __(">Dashboard > Updates", 'advanced-dynamic-pricing-for-woocommerce' ) );
		
		?>
		<div class="wrap">
		<h2><?php _e('Plugin License', 'advanced-dynamic-pricing-for-woocommerce'); ?></h2>

        <div id="license_help_text">

            <h3><?php _e( 'Licenses', 'advanced-dynamic-pricing-for-woocommerce' ); ?></h3>

            <div class="license_paragraph"><?php printf ( __( 'The license key you received when completing your purchase from %s will grant you access to updates until it expires.', 'advanced-dynamic-pricing-for-woocommerce' ), $site_link_html );?><br>
				<?php _e( 'You do not need to enter the key below for the plugin to work, but you will need to enter it to get automatic updates.', 'advanced-dynamic-pricing-for-woocommerce' ); ?></div>
            <div class="license_paragraph"><?php printf( __( "If you're seeing a red message telling you that your key isn't valid or is out of installs, %s visit %s to manage your installs or renew / upgrade your license.", 'advanced-dynamic-pricing-for-woocommerce'),"<br>", $account_link_html );?></div>
            <div class="license_paragraph"><?php printf( __( 'Not seeing an update but expecting one? In WordPress, go to %s and click "Check Again".', 'advanced-dynamic-pricing-for-woocommerce'), $dashboard_link );?></div>

        </div>

		<form method="post" action="options.php">

			<?php settings_fields('edd_wc_adp_license'); ?>

			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e('License Key', 'advanced-dynamic-pricing-for-woocommerce'); ?>
					</th>
					<td>
						<input id="edd_wc_adp_license_key" name="edd_wc_adp_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" /><br>
						<label class="description" for="edd_wc_adp_license_key"><?php _e('look for it inside purchase receipt (email)', 'advanced-dynamic-pricing-for-woocommerce'); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
					</th>
					<td>
						<?php if( $status !== false && $status == 'valid' ) { ?>
							<span style="color:green;"><?php _e('License is active', 'advanced-dynamic-pricing-for-woocommerce'); ?></span><br><br>
							<?php wp_nonce_field( 'edd_wc_adp_nonce', 'edd_wc_adp_nonce' ); ?>
							<input type="submit" class="button-secondary" name="edd_wc_adp_license_deactivate" value="<?php _e('Deactivate License', 'advanced-dynamic-pricing-for-woocommerce'); ?>"/>
						<?php } else {
							if ( ! empty( $error ) ) { ?>
								<?php echo __('License is inactive:', 'advanced-dynamic-pricing-for-woocommerce'); ?>&nbsp;<span style="color:red;"><?php echo $error; ?></span><br><br>
							<?php }
							wp_nonce_field( 'edd_wc_adp_nonce', 'edd_wc_adp_nonce' ); ?>
							<input type="submit" class="button-secondary" name="edd_wc_adp_license_activate" value="<?php _e('Activate License', 'advanced-dynamic-pricing-for-woocommerce'); ?>"/>
						<?php } ?>
					</td>
				</tr>
				</tbody>
			</table>

		</form>
		<?php
	}

	function edd_wc_adp_register_option() {
		// creates our settings in the options table
		register_setting('edd_wc_adp_license', 'edd_wc_adp_license_key', array($this, 'edd_sanitize_license') );
	}

	function edd_sanitize_license( $new ) {
		$old = get_option( 'edd_wc_adp_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'edd_wc_adp_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}



	/************************************
	 * this illustrates how to activate
	 * a license key
	 *************************************/

	function edd_wc_adp_activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_wc_adp_license_activate'] ) ) {

			// run a quick security check
			if( ! check_admin_referer( 'edd_wc_adp_nonce', 'edd_wc_adp_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( $_POST['edd_wc_adp_license_key'] );
			update_option( 'edd_wc_adp_license_key', $license );


			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( WC_ADP_ITEM_NAME ), // the name of our product in EDD
				'url'       => WC_ADP_MAIN_URL
			);

			// Call the custom API.
			$response = wp_remote_post( WC_ADP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'edd_wc_adp_license_status', $license_data->license );
			update_option( 'edd_wc_adp_license_error', @$license_data->error );

		}
	}

	function edd_wc_adp_force_deactivate_license() {
		$this->_edd_wc_adp_deactivate_license();
	}

	function edd_wc_adp_deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_wc_adp_license_deactivate'] ) ) {

			// run a quick security check
			if( ! check_admin_referer( 'edd_wc_adp_nonce', 'edd_wc_adp_nonce' ) )
				return; // get out if we didn't click the Activate button

			$this->_edd_wc_adp_deactivate_license();
		}
	}

	private function _edd_wc_adp_deactivate_license() {
		// retrieve the license from the database
		$license = trim( get_option( 'edd_wc_adp_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( WC_ADP_ITEM_NAME ), // the name of our product in EDD
			'url'       => WC_ADP_MAIN_URL
		);

		// Call the custom API.
		$response = wp_remote_post( WC_ADP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'edd_wc_adp_license_status' );
		delete_option( 'edd_wc_adp_license_error' );
	}

	function edd_wc_adp_check_license() {

		global $wp_version;

		$license = trim( get_option( 'edd_wc_adp_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_name' => urlencode( WC_ADP_ITEM_NAME ),
			'url'       => WC_ADP_MAIN_URL
		);

		// Call the custom API.
		$response = wp_remote_post( WC_ADP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->license == 'valid' ) {
			echo 'valid'; exit;
			// this license is still valid
		} else {
			echo 'invalid'; exit;
			// this license is no longer valid
		}
	}

	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
//***********  EDD LICENSE FUNCTIONS END  *************************************************************************************************************************************************************************************************************************************************
	public static function wc_adp_get_main_url() {
		$home_url = home_url();
		$url_components = explode( '.', basename( $home_url ) );
		if ( count( $url_components ) > 2 ) {
			array_shift( $url_components );
		}
		$main_url = implode( '.', $url_components );
		if( strpos( $home_url, 'https://' ) !== 0 ) {
			$main_url = "https://{$main_url}";
		}
		else {
			$main_url = "http://{$main_url}";
		}
		return $main_url;
	}
}
WC_ADP_EDD::getInstance();