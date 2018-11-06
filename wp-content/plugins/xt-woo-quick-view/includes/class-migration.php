<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Woo_Quick_View_Migration {

 	/**
	 * The single instance of Woo_Quick_View_Migration.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;
	public $version_key = null;

	public function __construct ( $parent ) {

		$this->parent = &$parent;
		$this->parent->migration = &$this;
		$this->version_key = $this->get_version_key();

		add_action('admin_init', array($this, 'upgrade'), 10);
	}	
			
	function get_version_key() {
		
		$key = $this->parent->plugin_slug('version');
		if(defined('WOOQV_LITE')) {
			$key .= '-lite';
		}
		
		return $key;
	}	
	
	function upgrade() {
		
		global $wpdb, $wp_filesystem; 
	
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		
		$old_version = get_option( $this->version_key );
		$new_version = $this->parent->plugin_version(); 

		if(empty($old_version)) {
			
			update_option($this->version_key, $new_version);
			return false;
		}

		if ( $new_version !== $old_version )
		{
	
			/*
			 * 1.1.2
			 */

			if ( $old_version < '1.1.2' )
			{
				$this->migrate($new_version);
			}

			// End Migrations	
					
			update_option($this->version_key, $new_version);
			
			$this->after_upgrade();
			
		}
	}
	
	function migrate($version) {
	
		$path = $this->parent->plugin_path() . 'includes/migrations/migration-'.$version.'.php';	
		
		if(file_exists($path)) {
			
			include_once $path;
		}
	
	}
	
	function after_upgrade() {
		
		delete_transient($this->parent->plugin_slug('changelog'));
		wp_redirect($this->parent->backend()->get_url());
		exit;
	}


	/**
	 * Woo_Quick_View_Migration Instance
	 *
	 * Ensures only one instance of Woo_Quick_View_Migration is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Slick_Menu()
	 * @return Woo_Quick_View_Migration instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->plugin_version() );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->plugin_version() );
	} // End __wakeup()	 	
}
