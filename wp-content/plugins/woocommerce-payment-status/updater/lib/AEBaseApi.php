<?php

class AEBaseApi
{

	public $products = array();

	/**
	 * Class constructor. Sets error messages if any. Registers the 'pre_set_site_transient_update_plugins' filter.
	 *
	 * @param string $user_name The buyer's Username
	 * @param string $api_key   The buyer's API Key can be accessed on the marketplaces via My Account -> My Settings -> API Key
	 */
	public function __construct()
	{
	}

	/**
	 * Set up the filter for plugins in order to include Envato plugins
	 *
	 * @private
	 */
	public function onInit()
	{
		$products = $this->get_products();
		if(empty($products)){
			return $plugins; // No plugins from Envato Marketplace found
		}
		$purchase_codes = get_option(AEBaseApi::PURCHASE_CODES_OPTION_KEY, array());

		// Setup parent class with the correct credentials, if we have them
		foreach ($products as $file) {
			$plugin_slug = basename($file, '.php');
			if( !isset($purchase_codes[$plugin_slug]) || empty($purchase_codes[$plugin_slug]) ){
				continue;
			}
			$code = $purchase_codes[$plugin_slug];
			PucFactory::buildUpdateChecker( 'http://actualityextensions.com/updates/server/?action=get_metadata&slug=' . $plugin_slug . '&purchase_code=' . $code, $file , $plugin_slug );
		}
	}

	/**
	 * Holds the name of the user meta key that will store the Envato api key
	 *
	 * @type string
	 */
	const PURCHASE_CODES_OPTION_KEY = 'ae_purchase_codes';

	/**
	 * Creates the sidebar menu
	 */
	public function addPluginPages()
	{
		add_dashboard_page('Actuality Extensions', 'Actuality Extensions', 'manage_options', 'ae_license', array($this,'pageDashboard'));
		/*add_dashboard_page('ae_license', __('Settings', 'envato-update-plugins'), __('Settings', 'envato-update-plugins'),
			'manage_options', 'ae_license', array($this,'pageDashboard'));*/
	}

	public function add_product($file = '')
	{
		if( !empty($file) && !in_array($file, $this->products)){
			$this->products[] = $file;
		}
	}

	public function get_products()
	{
		 return $this->products;
	}

	public function pageDashboard(){ include(EUP_PLUGIN_DIR.'pages/index.php'); }
}