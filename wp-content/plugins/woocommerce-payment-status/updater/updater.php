<?php
if(!defined('ABSPATH')) return;

if( !class_exists('AEBaseApi')) {

	/**
	 * Set the system path to the plugin's directory
	 */
	define('EUP_PLUGIN_DIR', realpath(dirname(__FILE__)).'/');

	//#!-- Load dependencies
	require('lib/plugin-update-checker.php');
	require('lib/AEBaseApi.php');
	$AEApi = new AEBaseApi();
	$GLOBALS['aebaseapi'] = $AEApi;


	add_action('admin_init', array($AEApi, 'onInit'));

	//#!-- Add sidebar menu
	if(function_exists('is_multisite') && is_multisite()){
		add_action('network_admin_menu', array($AEApi,'addPluginPages'));
	}
	else {
		add_action('admin_menu', array($AEApi,'addPluginPages'));
	}

}