<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Admin_Advanced_Statistics_Page extends WDP_Admin_Abstract_Page {
	public $priority = 30;
	protected $tab = 'statistics';
	protected $reports;

	public function __construct() {
		$this->title = __( 'Statistics', 'advanced-dynamic-pricing-for-woocommerce' );

		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-amount.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-amount-extra.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-amount-shipping.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-amount-full.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-gifted-qty.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-rule-count.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-product-amount.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-product-count.php';
		include_once WC_ADP_PRO_VERSION_PATH . 'classes/admin/reports/class-wdp-report-product-gifted-qty.php';

		$reports       = array(
			'rule_amount'          => array(
				'handler' => new WDP_Admin_Report_Rule_Amount(),
				'label'   => __( 'Rule amount', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'rule_amount_extra'    => array(
				'handler' => new WDP_Admin_Report_Rule_Amount_Extra(),
				'label'   => __( 'Rule amount extra', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'rule_amount_shipping' => array(
				'handler' => new WDP_Admin_Report_Rule_Amount_Shipping(),
				'label'   => __( 'Rule amount shipping', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'rule_amount_full'     => array(
				'handler' => new WDP_Admin_Report_Rule_Amount_Full(),
				'label'   => __( 'Rule amount full', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'rule_gifted_qty'      => array(
				'handler' => new WDP_Admin_Report_Rule_Gifted_Qty(),
				'label'   => __( 'Rule gifts', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'rule_count'           => array(
				'handler' => new WDP_Admin_Report_Rule_Count(),
				'label'   => __( 'Rule count', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Rule', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'product_amount'       => array(
				'handler' => new WDP_Admin_Report_Product_Amount(),
				'label'   => __( 'Product amount', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Product', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'product_count'        => array(
				'handler' => new WDP_Admin_Report_Product_Count(),
				'label'   => __( 'Product count', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Product', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
			'product_gifted_qty'   => array(
				'handler' => new WDP_Admin_Report_Product_Gifted_Qty(),
				'label'   => __( 'Product gifts', 'advanced-dynamic-pricing-for-woocommerce' ),
				'group'   => __( 'Product', 'advanced-dynamic-pricing-for-woocommerce' ),
			),
		);
		$this->reports = apply_filters( 'wdp_admin_reports', $reports );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_wdp_admin_statistics', array( $this, 'ajax' ) );
	}

	public function action() {
	}

	public function render() {
		$charts = array();
		foreach ( $this->reports as $k => $item ) {
			$group = $item['group'];
			if ( ! isset( $charts[ $group ] ) ) {
				$charts[ $group ] = array();
			}
			$charts[ $group ][ $k ] = $item['label'];
		}

		$this->render_template(
			WC_ADP_PRO_VERSION_PATH . 'views/tabs/statistics.php',
			compact( 'charts' )
		);
	}

	protected function ajax_get_chart_data() {
		parse_str( $_POST['params'], $params );
		$type = $params['type'];

		if ( isset( $this->reports[ $type ] ) ) {
			/** @var WDP_Report $handler */
			$handler = $this->reports[ $type ]['handler'];

			$data = $handler->get_data( $params );
			wp_send_json_success( $data );
		} else {
			wp_send_json_error();
		}
	}

	public function admin_enqueue_scripts() {
		$screen           = get_current_screen();
		$is_settings_page = $screen && $screen->id === 'woocommerce_page_wdp_settings';

		// Load backend assets conditionally
		if ( ! $is_settings_page ) {
			return;
		}

		if ( isset( $_REQUEST['tab'] ) AND $_REQUEST['tab'] == $this->tab ) {
			wp_enqueue_script( 'google-charts-loader', 'https://www.gstatic.com/charts/loader.js' );

			wp_enqueue_script( 'wdp_settings-scripts-statistics',
				WC_ADP_PRO_VERSION_URL . '/assets/js/admin-statistics.js', array( 'jquery' ), WC_ADP_VERSION );
		}


	}
}