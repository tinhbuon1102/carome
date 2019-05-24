<?php


class WDP_Cron_Export {

	private $target_type_key = 'rules_target_type';
	private $target_type = null;
	private $target_types = array(
		'url'
	);

	private $target_key = 'rules_target';
	private $target = null;

	private $reset_key = 'reset';
	private $reset = false;


	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
	}

	public function wp_loaded() {
		global $pagenow;

		if ( 'wp-cron.php' !== $pagenow || ! defined( 'DOING_CRON' ) || ! DOING_CRON ) {
			return;
		}

		if ( ! $this->get_arguments() ) {
			return;
		}

		$this->store_rules();
	}

	private function get_arguments() {
		$this->target_type = $this->get_target_type();
		$this->target = $this->get_target();
		$this->reset = $this->get_reset_tables();

		return $this->target_type && $this->target;
	}

	private function get_reset_tables() {
		return ! empty( $_GET[ $this->reset_key ] ) && wc_string_to_bool( $_GET[ $this->reset_key ] );
	}

	private function get_target_type() {

		return ! empty( $_GET[ $this->target_type_key ] ) && in_array( $_GET[ $this->target_type_key ],
				$this->target_types ) ? $_GET[ $this->target_type_key ] : false;
	}

	private function get_target() {
		$target = ! empty( $_GET[ $this->target_key ] ) ? $_GET[ $this->target_key ] : false;

		return $target && $this->target_type && $this->is_target_valid( $target ) ? $target : false;
	}

	private function is_target_valid($target) {
		if ( 'url' == $this->target_type ) {
			return wc_is_valid_url( $target );
		}

		return true;
	}

	private function store_rules() {
		if ( 'url' == $this->target_type ) {
			$content = @file_get_contents( $this->target );

			if ( ! $content ) {
				die( 'empty content' );
			}

			$data = json_decode( $content, true );
			if ( empty( $data ) ) {
				$data = json_decode( str_replace( '\"', '"', $content ), 1 );
			}

			//array of rules ? each rule is array too!
			if ( is_array( $data ) AND is_array( $data[0] ) ) {
				WDP_Importer::import( $data, $this->reset );
				die( 'success!' );
			}

		}

		die('fail');
	}


}

new WDP_Cron_Export();