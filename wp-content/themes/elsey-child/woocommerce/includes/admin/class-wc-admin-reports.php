<?php
/**
 * Admin Reports
 *
 * Functions used for displaying sales and customer reports in admin.
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin/Reports
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Admin_Reports_New', false ) ) :

/**
 * WC_Admin_Reports Class.
 */
class WC_Admin_Reports_New extends WC_Admin_Reports  {

	/**
	 * Handles output of the reports page in admin.
	 */
	public static function output_new() {
		$reports        = parent::get_reports();
		$first_tab      = array_keys( $reports );
		$current_tab    = ! empty( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : $first_tab[0];
		$current_report = isset( $_GET['report'] ) ? sanitize_title( $_GET['report'] ) : current( array_keys( $reports[ $current_tab ]['reports'] ) );

		include_once( dirname( __FILE__ ) . '/reports/class-wc-admin-report.php' );
		include_once( dirname( __FILE__ ) . '/views/html-admin-page-reports.php' );
	}

	/**
	 * Returns the definitions for the reports to show in admin.
	 *
	 * @return array
	 */
	public static function get_reports() {}

	/**
	 * Get a report from our reports subfolder.
	 *
	 * @param string $name
	 */
	public static function get_report( $name ) {}
}

endif;
