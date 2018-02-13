<?php
/**
 * Plugin Name: WooCommerce For Epsilon payments link
 *
 * @package wc-epsilon-link-pro
 * @category Core
 * @author Artisan Workshop
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Admin_Screen_Epsilon_Pro {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wc_admin_epsilon_pro_menu' ),59 );
//		add_action( 'admin_notices', array( $this, 'wc_epsilon_pro_ssl_check' ) );
		add_action( 'admin_init', array( $this, 'wc_setting_epsilon_pro_init') );
	}
	/**
	 * Admin Menu
	 */
	public function wc_admin_epsilon_pro_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'Epsilon Setting', 'wc4jp-epsilon' ), __( 'Epsilon Setting', 'wc4jp-epsilon' ), 'manage_woocommerce', 'wc4jp-epsilon-output', array( $this, 'wc_epsilon_pro_output' ) );
	}

	/**
	 * Admin Screen output
	 */
	public function wc_epsilon_pro_output() {
		$tab = ! empty( $_GET['tab'] ) && $_GET['tab'] == 'info' ? 'info' : 'setting';
		include( 'views/html-admin-screen.php' );
	}

	/**
	 * Admin page for Setting
	 */
	public function admin_epsilon_pro_setting_page() {
		include( 'views/html-admin-setting-screen.php' );
	}

	/**
	 * Admin page for infomation
	 */
	public function admin_epsilon_pro_info_page() {
		include( 'views/html-admin-info-screen.php' );
	}
	
      /**
       * Check if SSL is enabled and notify the user.
       */
//      function wc_epsilon_pro_ssl_check() {
//        if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'no' && $this->enabled == 'yes' ) {
//            echo '<div class="error"><p>' . sprintf( __('Epsilon Commerce is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'wc4jp-epsilon' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
//            }
//	  }

	function wc_setting_epsilon_pro_init(){
		if( isset( $_POST['wc-epsilon-pro-setting'] ) && $_POST['wc-epsilon-pro-setting'] ){
			if( check_admin_referer( 'my-nonce-key', 'wc-epsilon-pro-setting')){
				//Contract ID Setting
				if(isset($_POST['epsilon_pro_cid']) && $_POST['epsilon_pro_cid']){
					update_option( 'wc-epsilon-pro-cid', $_POST['epsilon_pro_cid']);
				}else{
					update_option( 'wc-epsilon-pro-cid', '');
				}
				//Contract Password Setting
				if(isset($_POST['epsilon_pro_cpass']) && $_POST['epsilon_pro_cpass']){
					update_option( 'wc-epsilon-pro-cpass', $_POST['epsilon_pro_cpass']);
				}else{
					update_option( 'wc-epsilon-pro-cpass', '');
				}
				//Credit Card payment method
				$woocommerce_epsilon_pro_cc = get_option('woocommerce_epsilon_pro_cc_settings');
				if(isset($_POST['epsilon_pro_cc']) && $_POST['epsilon_pro_cc']){
					update_option( 'wc-epsilon-pro-cc', $_POST['epsilon_pro_cc']);
					if(isset($woocommerce_epsilon_pro_cc)){
						$woocommerce_epsilon_pro_cc['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_cc_settings', $woocommerce_epsilon_pro_cc);
					}
				}else{
					update_option( 'wc-epsilon-pro-cc', '');
					if(isset($woocommerce_epsilon_pro_cc)){
						$woocommerce_epsilon_pro_cc['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_cc_settings', $woocommerce_epsilon_pro_cc);
					}
				}
				//Convenient payment method
				$woocommerce_epsilon_pro_cs = get_option('woocommerce_epsilon_pro_cs_settings');
				if(isset($_POST['epsilon_pro_cs']) && $_POST['epsilon_pro_cs']){
					update_option( 'wc-epsilon-pro-cs', $_POST['epsilon_pro_cs']);
					if(isset($woocommerce_epsilon_pro_cs)){
						$woocommerce_epsilon_pro_cs['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_cs_settings', $woocommerce_epsilon_pro_cs);
					}
				}else{
					update_option( 'wc-epsilon-pro-cs', '');
					if(isset($woocommerce_epsilon_pro_cs)){
						$woocommerce_epsilon_pro_cs['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_cs_settings', $woocommerce_epsilon_pro_cs);
					}
				}
				//Net Bank payment method
					$woocommerce_epsilon_pro_nb = get_option('woocommerce_epsilon_pro_nb_settings');
				if(isset($_POST['epsilon_pro_nb']) && $_POST['epsilon_pro_nb']){
					update_option( 'wc-epsilon-pro-nb', $_POST['epsilon_pro_nb']);
					if(isset($woocommerce_epsilon_pro_nb)){
						$woocommerce_epsilon_pro_nb['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_nb_settings', $woocommerce_epsilon_pro_nb);
					}
				}else{
					update_option( 'wc-epsilon-pro-nb', '');
					if(isset($woocommerce_epsilon_pro_nb)){
						$woocommerce_epsilon_pro_nb['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_nb_settings', $woocommerce_epsilon_pro_nb);
					}
				}
				//Web Money payment method
					$woocommerce_epsilon_pro_wm = get_option('woocommerce_epsilon_pro_wm_settings');
				if(isset($_POST['epsilon_pro_wm']) && $_POST['epsilon_pro_wm']){
					update_option( 'wc-epsilon-pro-wm', $_POST['epsilon_pro_wm']);
					if(isset($woocommerce_epsilon_pro_wm)){
						$woocommerce_epsilon_pro_wm['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_wm_settings', $woocommerce_epsilon_pro_wm);
					}
				}else{
					update_option( 'wc-epsilon-pro-wm', '');
					if(isset($woocommerce_epsilon_pro_wm)){
						$woocommerce_epsilon_pro_wm['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_wm_settings', $woocommerce_epsilon_pro_wm);
					}
				}
				//BitCash payment method
				$woocommerce_epsilon_pro_bc = get_option('woocommerce_epsilon_pro_bc_settings');
				if(isset($_POST['epsilon_pro_bc']) && $_POST['epsilon_pro_bc']){
					update_option( 'wc-epsilon-pro-bc', $_POST['epsilon_pro_bc']);
					if(isset($woocommerce_epsilon_pro_bc)){
						$woocommerce_epsilon_pro_bc['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_bc_settings', $woocommerce_epsilon_pro_bc);
					}
				}else{
					update_option( 'wc-epsilon-pro-bc', '');
					if(isset($woocommerce_epsilon_pro_bc)){
						$woocommerce_epsilon_pro_bc['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_bc_settings', $woocommerce_epsilon_pro_bc);
					}
				}
				//Electric Money payment method
				$woocommerce_epsilon_pro_em = get_option('woocommerce_epsilon_pro_em_settings');
				if(isset($_POST['epsilon_pro_em']) && $_POST['epsilon_pro_em']){
					update_option( 'wc-epsilon-pro-em', $_POST['epsilon_pro_em']);
					if(isset($woocommerce_epsilon_pro_em)){
						$woocommerce_epsilon_pro_em['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_em_settings', $woocommerce_epsilon_pro_em);
					}
				}else{
					update_option( 'wc-epsilon-pro-em', '');
					if(isset($woocommerce_epsilon_pro_em)){
						$woocommerce_epsilon_pro_em['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_em_settings', $woocommerce_epsilon_pro_em);
					}
				}
				//Pay-easy payment method
				$woocommerce_epsilon_pro_pe = get_option('woocommerce_epsilon_pro_cc_settings');
				if(isset($_POST['epsilon_pro_pe']) && $_POST['epsilon_pro_pe']){
					update_option( 'wc-epsilon-pro-pe', $_POST['epsilon_pro_pe']);
					if(isset($woocommerce_epsilon_pro_pe)){
						$woocommerce_epsilon_pro_pe['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_pe_settings', $woocommerce_epsilon_pro_pe);
					}
				}else{
					update_option( 'wc-epsilon-pro-pe', '');
					if(isset($woocommerce_epsilon_pro_pe)){
						$woocommerce_epsilon_pro_pe['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_pe_settings', $woocommerce_epsilon_pro_pe);
					}
				}
				//Pay Pal payment method
				$woocommerce_epsilon_pro_pp = get_option('woocommerce_epsilon_pro_pp_settings');
				if(isset($_POST['epsilon_pro_pp']) && $_POST['epsilon_pro_pp']){
					update_option( 'wc-epsilon-pro-pp', $_POST['epsilon_pro_pp']);
					if(isset($woocommerce_epsilon_pro_pp)){
						$woocommerce_epsilon_pro_pp['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_pp_settings', $woocommerce_epsilon_pro_pp);
					}
				}else{
					update_option( 'wc-epsilon-pro-pp', '');
					if(isset($woocommerce_epsilon_pro_pp)){
						$woocommerce_epsilon_pro_pp['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_pp_settings', $woocommerce_epsilon_pro_pp);
					}
				}
				//Yahoo! Walet payment method
				$woocommerce_epsilon_pro_yw = get_option('woocommerce_epsilon_pro_yw_settings');
				if(isset($_POST['epsilon_pro_yw']) && $_POST['epsilon_pro_yw']){
					update_option( 'wc-epsilon-pro-yw', $_POST['epsilon_pro_yw']);
					if(isset($woocommerce_epsilon_pro_yw)){
						$woocommerce_epsilon_pro_yw['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_yw_settings', $woocommerce_epsilon_pro_yw);
					}
				}else{
					update_option( 'wc-epsilon-pro-yw', '');
					if(isset($woocommerce_epsilon_pro_yw)){
						$woocommerce_epsilon_pro_yw['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_yw_settings', $woocommerce_epsilon_pro_yw);
					}
				}
				//SmartPhone Carrier payment method
				$woocommerce_epsilon_pro_sc = get_option('woocommerce_epsilon_pro_sc_settings');
				if(isset($_POST['epsilon_pro_sc']) && $_POST['epsilon_pro_sc']){
					update_option( 'wc-epsilon-pro-sc', $_POST['epsilon_pro_sc']);
					if(isset($woocommerce_epsilon_pro_sc)){
						$woocommerce_epsilon_pro_sc['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_sc_settings', $woocommerce_epsilon_pro_sc);
					}
				}else{
					update_option( 'wc-epsilon-pro-sc', '');
					if(isset($woocommerce_epsilon_pro_sc)){
						$woocommerce_epsilon_pro_sc['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_sc_settings', $woocommerce_epsilon_pro_sc);
					}
				}
				//SBI Net Bank payment method
				$woocommerce_epsilon_pro_sn = get_option('woocommerce_epsilon_pro_sn_settings');
				if(isset($_POST['epsilon_pro_sn']) && $_POST['epsilon_pro_sn']){
					update_option( 'wc-epsilon-pro-sn', $_POST['epsilon_pro_sn']);
					if(isset($woocommerce_epsilon_pro_sn)){
						$woocommerce_epsilon_pro_sn['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_sn_settings', $woocommerce_epsilon_pro_sn);
					}
				}else{
					update_option( 'wc-epsilon-pro-sn', '');
					if(isset($woocommerce_epsilon_pro_sn)){
						$woocommerce_epsilon_pro_sn['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_sn_settings', $woocommerce_epsilon_pro_sn);
					}
				}
				//GMO Postpay payment method
				$woocommerce_epsilon_pro_gp = get_option('woocommerce_epsilon_pro_gp_settings');
				if(isset($_POST['epsilon_pro_gp']) && $_POST['epsilon_pro_gp']){
					update_option( 'wc-epsilon-pro-gp', $_POST['epsilon_pro_gp']);
					if(isset($woocommerce_epsilon_pro_gp)){
						$woocommerce_epsilon_pro_gp['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_gp_settings', $woocommerce_epsilon_pro_gp);
					}
				}else{
					update_option( 'wc-epsilon-pro-gp', '');
					if(isset($woocommerce_epsilon_pro_gp)){
						$woocommerce_epsilon_pro_gp['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_gp_settings', $woocommerce_epsilon_pro_gp);
					}
				}
				//Multi-currency Credit Card payment method
					$woocommerce_epsilon_pro_mccc = get_option('woocommerce_epsilon_pro_mccc_settings');
				if(isset($_POST['epsilon_pro_mccc']) && $_POST['epsilon_pro_mccc']){
					update_option( 'wc-epsilon-pro-mccc', $_POST['epsilon_pro_mccc']);
					if(isset($woocommerce_epsilon_pro_mccc)){
						$woocommerce_epsilon_pro_mccc['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_mccc_settings', $woocommerce_epsilon_pro_mccc);
					}
				}else{
					update_option( 'wc-epsilon-pro-mccc', '');
					if(isset($woocommerce_epsilon_pro_mccc)){
						$woocommerce_epsilon_pro_mccc['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_mccc_settings', $woocommerce_epsilon_pro_mccc);
					}
				}
				// JCB PREMO payment method
				$woocommerce_epsilon_pro_jp = get_option('woocommerce_epsilon_pro_jp_settings');
				if(isset($_POST['epsilon_pro_jp']) && $_POST['epsilon_pro_jp']){
					update_option( 'wc-epsilon-pro-jp', $_POST['epsilon_pro_jp']);
					if(isset($woocommerce_epsilon_pro_jp)){
						$woocommerce_epsilon_pro_jp['enabled'] = 'yes';
						update_option( 'woocommerce_epsilon_pro_jp_settings', $woocommerce_epsilon_pro_jp);
					}
				}else{
					update_option( 'wc-epsilon-pro-jp', '');
					if(isset($woocommerce_epsilon_pro_jp)){
						$woocommerce_epsilon_pro_jp['enabled'] = 'no';
						update_option( 'woocommerce_epsilon_pro_jp_settings', $woocommerce_epsilon_pro_jp);
					}
				}
			}
		}
	}
}

new WC_Admin_Screen_Epsilon_Pro();