<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woo Quick View Kirki Options Class.
 */
class Woo_Quick_View_Customizer {

	public static $parent;
	public static $config_id = 'wooqv';
	public static $options = null;
	public static $path;
	
	/**
	 * Class constructor
	 */
	public function __construct($parent) {
		
		/**
		 * Exit early if Kirki does not exist or is not installed and activated.
		 */
		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}
		
		self::$parent = $parent;
		self::$path = dirname(__FILE__);
		
		self::add_config();
		self::add_panels();
		self::add_sections();
		self::add_fields();
		
		add_action( 'customize_controls_enqueue_scripts', array(__CLASS__, 'customizer_styles' ));
		add_action( 'customize_preview_init', array(__CLASS__, 'customizer_preview_script' ));	
		
		add_filter( 'wp_check_filetype_and_ext', array(__CLASS__, 'check_filetype_and_ext'), 10, 4 );
		add_filter( 'upload_mimes', array(__CLASS__, 'allow_myme_types'), 1, 1);
		
		if(!defined('WOOQV_LITE')) {
			add_filter( 'plugin_action_links_' . plugin_basename( self::$parent->plugin_file() ), array(__CLASS__, 'action_link' ));
			add_action( 'admin_menu', array(__CLASS__, 'customizer_menu' ));
		}	
	}

	public static function customizer_link() {
		
		return admin_url('customize.php?autofocus[panel]='.self::$config_id	);
	}
	
	public static function action_link( $links ) {
		
		$links[] = '<a href="'. esc_url( self::customizer_link() ) .'">'.esc_html__('Customize', 'woo-quick-view').'</a>';
		return $links;
	}

	public static function customizer_menu() {
		
		add_submenu_page( self::$parent->plugin_slug(), esc_html__('Customize', 'woo-quick-view'), esc_html__('Customize', 'woo-quick-view'), 'manage_options', self::$parent->plugin_slug('customize'), array(__CLASS__, 'customizer_redirect'));
	}
	
	public static function customizer_redirect() {
		
		wp_redirect(self::customizer_link());
		exit;
	}
	
	/**
	 * Kirki Config
	 */
	public static function add_config() {

		Kirki::add_config( self::$config_id, array(
		    'capability'    => 'edit_theme_options',
		    'option_type'   => 'option',
		    'option_name'	=> self::$config_id	    
		));	
	}

	/**
	 * Add panels to Kirki.
	 */
	public static function add_panels() {

		Kirki::add_panel( self::$config_id, array(
		    'priority'    => 130,
		    'title'       => esc_html__( 'Woo Quick View', 'woo-quick-view' ),
		    'icon' => 'dashicons-welcome-view-site'
		));
	}

	/**
	 * Add sections to Kirki.
	 */
	public static function add_sections() {

				
		Kirki::add_section( 'modal-trigger', array(
		    'title'          => esc_html__( 'Modal Trigger', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-share-alt'
		));
		
		Kirki::add_section( 'modal-overlay', array(
		    'title'          => esc_html__( 'Modal Overlay', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-external'
		));

		Kirki::add_section( 'modal-box', array(
		    'title'          => esc_html__( 'Modal Box', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-align-center'
		));
				
		Kirki::add_section( 'modal-product-slider', array(
		    'title'          => esc_html__( 'Modal Product Slider', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-slides'
		));

		Kirki::add_section( 'modal-product-info', array(
		    'title'          => esc_html__( 'Modal Product Info', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-info'
		));

		Kirki::add_section( 'modal-close-button', array(
		    'title'          => esc_html__( 'Modal Close Button', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-dismiss'
		));
		
		Kirki::add_section( 'modal-navigation', array(
		    'title'          => esc_html__( 'Modal Navigation Arrows', 'woo-quick-view'),
		    'panel'          => self::$config_id, 
		    'priority'       => 160,
		    'capability'     => 'edit_theme_options',
		    'icon' 			 => 'dashicons-dismiss'
		));

	}

	/**
	 * Add fields to Kirki.
	 */
	public static function add_fields() {
		
		// General Settings.
		
		require_once self::$path . '/fields/modal-trigger.php';
		require_once self::$path . '/fields/modal-overlay.php';
		require_once self::$path . '/fields/modal-box.php';
		require_once self::$path . '/fields/modal-product-slider.php';
		//require_once self::$path . '/fields/modal-product-info.php';
		require_once self::$path . '/fields/modal-close-button.php';
		require_once self::$path . '/fields/modal-navigation.php';
	}
	
	public static function field_id($id) {
		
		return $id;
	}

	public static function get_option($id) {

		return Kirki::get_option( self::$config_id, $id );

	}
	
	
	public static function customizer_styles() {
		
		wp_enqueue_style( 
			self::$config_id.'-customizer-styles', 
			self::$parent->plugin_url(). 'includes/customizer/assets/css/customizer.css', 
			array(), 
			self::$parent->plugin_version()
		);
	}
	
	public static function customizer_preview_script() {
		
		wp_enqueue_script( 
			self::$config_id.'-customizer-script', 
			self::$parent->plugin_url(). 'includes/customizer/assets/js/customizer-min.js', 
			array( 'jquery','customize-preview' ),
			self::$parent->plugin_version(),
			true	
		);
	}

	// Allow SVG
	public static function check_filetype_and_ext($data, $file, $filename, $mimes) {
	
	  global $wp_version;
	  if ( $wp_version <= '4.7.1' ) {
	     return $data;
	  }
	
	  $filetype = wp_check_filetype( $filename, $mimes );
	
	  return [
	      'ext'             => $filetype['ext'],
	      'type'            => $filetype['type'],
	      'proper_filename' => $data['proper_filename']
	  ];
	
	}

	public static function allow_myme_types($mime_types){

		$mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
		$mime_types['svgz'] = 'image/svg+xml';

		return $mime_types;

	}
				
} // End Class
	
