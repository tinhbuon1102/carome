<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Advanced_Exclusive_Page extends WDP_Admin_Abstract_Page {
	public $title;
	public $priority = 20;
	protected $tab = 'exclusive';

	public function __construct() {
		$this->title = __( 'Exclusive Rules', 'advanced-dynamic-pricing-for-woocommerce' );
	}

	public function action() {

	}

	public function render() {
		$condition_registry   = WDP_Condition_Registry::get_instance();
		$conditions_templates = $condition_registry->get_templates_content();
		$conditions_titles    = $condition_registry->get_titles();

		$limit_registry   = WDP_Limit_Registry::get_instance();
		$limits_templates = $limit_registry->get_templates_content();
		$limits_titles    = $limit_registry->get_titles();

		$cart_registry  = WDP_Cart_Adj_Registry::get_instance();
		$cart_templates = $cart_registry->get_templates_content();
		$cart_titles    = $cart_registry->get_titles();

		$this->render_template(
			WC_ADP_PRO_VERSION_PATH . 'views/tabs/exclusive.php',
			compact(
				'conditions_templates',
				'conditions_titles',
				'limits_templates',
				'limits_titles',
				'cart_templates',
				'cart_titles'
			)
		);
	}
}