<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Advanced_License_Page extends WDP_Admin_Abstract_Page {
	public $title;
	public $priority = 50;
	protected $tab = 'license';

	public function __construct() {
		$this->title = __( 'License', 'advanced-dynamic-pricing-for-woocommerce' );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_enqueue_scripts() {
		if ( isset( $_REQUEST['tab'] ) AND $_REQUEST['tab'] == $this->tab ) {
			wp_enqueue_style( 'wdp_license-tab', WC_ADP_PRO_VERSION_URL . '/assets/css/license-tab.css', array(), WC_ADP_VERSION);
		}
	}

	public function action() {}

	public function render() {
		$data = array(
			'pro_version' => true,
		);
		$this->render_template( 
			WC_ADP_PRO_VERSION_PATH . 'views/tabs/license.php',
			$data );
	}
}