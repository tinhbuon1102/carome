<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'WC_ADP_PRO_VERSION_PATH', WC_ADP_PLUGIN_PATH . 'pro_version/' );
define( 'WC_ADP_PRO_VERSION_URL', WC_ADP_PLUGIN_URL . '/pro_version' );


// EDD
include 'classes/updater/class-wc-adp-updater.php';
include 'classes/updater/class-wc-adp-edd.php';

define( 'WC_ADP_MAIN_URL', WC_ADP_EDD::wc_adp_get_main_url() );
define( 'WC_ADP_STORE_URL', 'https://algolplus.com/plugins/' );
define( 'WC_ADP_ITEM_NAME', 'Advanced Dynamic Pricing For WooCommerce (Pro)' );
define( 'WC_ADP_AUTHOR', 'AlgolPlus' );

include 'classes/class-wdp-pro-loader.php';
