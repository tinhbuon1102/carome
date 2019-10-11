<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WDP_Calculation_Profiler {
	/**
	 * @var WDP_Price_Display $price_display
	 */
	private $price_display;

	/**
	 * @var WDP_Report_Output
	 */
	private $report_display;

	/**
	 * @var string
	 */
	private $import_key = null;

	/**
	 * @var WDP_Cart_Calculator_Listener
	 */
	private $calc_listener;

	private $expiration_time_is_seconds = 1200;

	public function __construct( $price_display ) {
		$this->price_display = $price_display;

		if ( $this->is_active() ) {
			$this->calc_listener = new WDP_Cart_Calculator_Listener();
			$this->init_hooks();
		}
	}

	public function init_hooks() {
		add_action( 'wp_footer', array( $this, 'print_report' ) );
		add_action( 'wp_footer', array( $this, 'collect_report' ), PHP_INT_MAX ); // do not use shutdown hook
		add_action( 'wp_ajax_get_user_report_data', array( $this, 'get_user_report_data' ) );
		add_action( 'wp_ajax_download_report', array( $this, 'handle_download_report' ) );

		add_action( 'wp_loaded', function () {
			$this->import_key     = $this->create_import_key();
			$this->report_display = new WDP_Report_Output( $this->import_key );

			if ( wp_doing_ajax() ) {
				add_action( 'wdp_after_apply_to_wc_cart', array( $this, 'collect_report' ) );
			}
		} );
	}

	public function collect_report() {
		$active_rules = $this->price_display->get_calculator()->get_rule_array();

		$active_rules_as_dict = array();
		foreach ( $active_rules as $wdp_rule ) {
			/**
			 * @var $wdp_rule WDP_Rule
			 */
			$new_data                  = $wdp_rule->get_rule_data();
			$new_data['edit_page_url'] = $wdp_rule->get_edit_page_url();

			$active_rules_as_dict[ $wdp_rule->get_id() ] = $new_data;
		}

		if ( wp_doing_ajax() ) {
			$prev_processed_products_report = get_transient( $this->get_report_transient_key( 'processed_products' ) );
			foreach ( $prev_processed_products_report as $report ) {
				if ( isset( $report['data']['id'] ) ) {
					$this->price_display->process_product( (int) $report['data']['id'] );
				}
			}
		}

		$reports = array(
			'initial_cart'       => null,
			'processed_cart'     => ( new WDP_Reporter_WC_Cart_Collector( $this->calc_listener ) )->collect(),
			'processed_products' => ( new WDP_Reporter_Products_Collector( $this->price_display, $this->calc_listener ) )->collect(),
			'rules_timing'       => ( new WDP_Reporter_Rules_Timing_Collector( $this->calc_listener, $active_rules ) )->collect(),
			'options'            => ( new WDP_Reporter_Options_Collector() )->collect(),
			'additions'          => ( new WDP_Reporter_Plugins_And_Theme_Collector() )->collect(),
			'active_hooks'       => ( new WDP_Reporter_Active_Hooks_Collector() )->collect(),

			'rules' => $active_rules_as_dict,
		);

		foreach ( $reports as $report_key => $report ) {
			set_transient( $this->get_report_transient_key( $report_key ), $report, $this->expiration_time_is_seconds );
		}
	}

	public function is_active() {
		return is_super_admin( get_current_user_id() );
	}

	public function print_report() {
		if ( is_super_admin( get_current_user_id() ) ) {
			$this->report_display->output();
		}
	}

	private function get_report_transient_key( $report_key ) {
		return sprintf( "wdp_profiler_%s_%s", $report_key, $this->import_key );
	}

	private function create_import_key() {
		if ( ! did_action( 'wp_loaded' ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( __( '%1$s should not be called before the %2$s action.', 'woocommerce' ), 'create_import_key', 'wp_loaded' ), WC_ADP_VERSION );

			return null;
		}

		global $wp;

		return substr( md5( $wp->request . '|' . (string) get_current_user_id() ), 0, 8 );
	}

	/**
	 * @param $calc WDP_Cart_Calculator
	 */
	public function attach_listener( $calc ) {
		$calc->add_subscriber( $this->calc_listener );
	}

	public function get_user_report_data() {
		$import_key = isset( $_REQUEST['import_key'] ) ? $_REQUEST['import_key'] : false;

		if ( $import_key === $this->import_key ) {
			$data = $this->make_response_data();
			if ( $data ) {
				wp_send_json_success( $data );
			} else {
				wp_send_json_error( __( 'Import key not found', 'advanced-dynamic-pricing-for-woocommerce' ) );
			}
		} else {
			wp_send_json_error( __( 'Wrong import key', 'advanced-dynamic-pricing-for-woocommerce' ) );
		}
	}

	private function make_response_data() {
		$required_keys = array(
			'processed_cart',
			'processed_products',
			'rules_timing',
			'rules'
		);
		$data          = array();

		foreach ( $required_keys as $key ) {
			$data[ $key ] = get_transient( $this->get_report_transient_key( $key ) );
		}

		$rules_data = array();
		foreach ( $data['rules'] as $rule ) {
			/**
			 * @var $rule WDP_Rule
			 */
			$rules_data[ $rule['id'] ] = array(
				'title'         => $rule['title'],
				'edit_page_url' => $rule['edit_page_url'],
			);
		}
		$data['rules'] = $rules_data;

		return $data;
	}


	public function handle_download_report() {
		$import_key = isset( $_REQUEST['import_key'] ) ? $_REQUEST['import_key'] : false;

		if ( $import_key !== $this->import_key ) {
			wp_send_json_error( __( 'Wrong import key', 'advanced-dynamic-pricing-for-woocommerce' ) );
		}

		if ( empty( $_REQUEST['reports'] ) ) {
			wp_send_json_error( __( 'Wrong "reports" key values', 'advanced-dynamic-pricing-for-woocommerce' ) );
		}
		$reports = explode(',', $_REQUEST['reports']);
		$keys = array(
			'initial_cart',
			'processed_cart',
			'processed_products',
			'rules_timing',
			'options',
			'additions',
			'active_hooks',
			'rules',
		);

		if ( ! in_array( 'all', $reports ) ) {
			$keys = array_intersect( $keys, $reports );
		}

		$data = array();
		foreach ( $keys as $key ) {
			$data[ $key ] = get_transient( $this->get_report_transient_key( $key ) );
		}

		$tmp_dir  = ini_get( 'upload_tmp_dir' ) ? ini_get( 'upload_tmp_dir' ) : sys_get_temp_dir();
		$filepath = @tempnam( $tmp_dir, 'wdp' );
		$handler  = fopen( $filepath, 'a' );
		fwrite( $handler, json_encode( $data, JSON_PRETTY_PRINT ) );
		fclose( $handler );

		$this->kill_buffers();
		header( 'Content-type: application/json' );
		header( 'Content-Disposition: attachment; filename="' . basename( $filepath ) . '.json' . '"' );
		$this->send_contents_delete_file( $filepath );

		wp_die();
	}

	private function kill_buffers() {
		while ( ob_get_level() ) {
			ob_end_clean();
		}
	}

	private function send_contents_delete_file( $filename ) {
		if ( ! empty( $filename ) ) {
			if ( ! $this->function_disabled( 'readfile' ) ) {
				readfile( $filename );
			} else {
				// fallback, emulate readfile
				$file = fopen( $filename, 'rb' );
				if ( $file !== false ) {
					while ( ! feof( $file ) ) {
						echo fread( $file, 4096 );
					}
					fclose( $file );
				}
			}
			unlink( $filename );
		}
	}

	private function function_disabled( $function ) {
		$disabled_functions = explode( ',', ini_get( 'disable_functions' ) );

		return in_array( $function, $disabled_functions );
	}

}