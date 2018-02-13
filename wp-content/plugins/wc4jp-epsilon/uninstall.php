<?php
if( ! defined ('WP_UNINSTALL_PLUGIN') )
exit();
function wc_epsilon_pro_delete_plugin(){
	global $wpdb;
	
	//delete option settings
	delete_option('woocommerce_epsilon_pro_cc');
	delete_option('woocommerce_epsilon_pro_cc_setting');
	delete_option('woocommerce_epsilon_pro_cc_settings');
	delete_option('woocommerce_epsilon_pro_cs');
	delete_option('woocommerce_epsilon_pro_cs_setting');
	delete_option('woocommerce_epsilon_pro_cs_settings');
	delete_option('woocommerce_epsilon_pro_nb');
	delete_option('woocommerce_epsilon_pro_nb_setting');
	delete_option('woocommerce_epsilon_pro_nb_settings');
	delete_option('woocommerce_epsilon_pro_wm');
	delete_option('woocommerce_epsilon_pro_wm_setting');
	delete_option('woocommerce_epsilon_pro_wm_settings');
	delete_option('woocommerce_epsilon_pro_bc');
	delete_option('woocommerce_epsilon_pro_bc_setting');
	delete_option('woocommerce_epsilon_pro_bc_settings');
	delete_option('woocommerce_epsilon_pro_em');
	delete_option('woocommerce_epsilon_pro_em_setting');
	delete_option('woocommerce_epsilon_pro_em_settings');
	delete_option('woocommerce_epsilon_pro_pe');
	delete_option('woocommerce_epsilon_pro_pe_setting');
	delete_option('woocommerce_epsilon_pro_pe_settings');
	delete_option('woocommerce_epsilon_pro_pp');
	delete_option('woocommerce_epsilon_pro_pp_setting');
	delete_option('woocommerce_epsilon_pro_pp_settings');
	delete_option('woocommerce_epsilon_pro_yw');
	delete_option('woocommerce_epsilon_pro_yw_setting');
	delete_option('woocommerce_epsilon_pro_yw_settings');
	delete_option('woocommerce_epsilon_pro_sc');
	delete_option('woocommerce_epsilon_pro_sc_setting');
	delete_option('woocommerce_epsilon_pro_sc_settings');
	delete_option('woocommerce_epsilon_pro_sn');
	delete_option('woocommerce_epsilon_pro_sn_setting');
	delete_option('woocommerce_epsilon_pro_sn_settings');
	delete_option('woocommerce_epsilon_pro_gp');
	delete_option('woocommerce_epsilon_pro_gp_setting');
	delete_option('woocommerce_epsilon_pro_gp_settings');
	delete_option('woocommerce_epsilon_pro_mccc');
	delete_option('woocommerce_epsilon_pro_mccc_setting');
	delete_option('woocommerce_epsilon_pro_mccc_settings');
	delete_option('woocommerce_epsilon_pro_jp');
	delete_option('woocommerce_epsilon_pro_jp_setting');
	delete_option('woocommerce_epsilon_pro_jp_settings');
	delete_option('wc-epsilon-pro-cid');
	delete_option('wc-epsilon-pro-cpass');
	delete_option('wc-epsilon-pro-cc');
	delete_option('wc-epsilon-prot-cs');
	delete_option('wc-epsilon-pro-mccc');

}

wc_epsilon_pro_delete_plugin();
?>