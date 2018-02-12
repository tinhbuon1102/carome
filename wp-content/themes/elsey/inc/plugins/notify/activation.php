<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 */
require_once ELSEY_FRAMEWORK . '/plugins/notify/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'elsey_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function elsey_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

    // Contact Form 7
    array(
	    'name'					=> 'Contact Form 7',
	    'slug'					=> 'contact-form-7',
	    'required'			=> true,
	    'external_url'	=> 'http://wordpress.org/plugins/contact-form-7',
    ),
    // Elsey Core
    array(
	    'name'					=> 'Elsey Core',
	    'slug'     			=> 'elsey-core',
	    'source'				=> 'http://victorthemes.com/plugins/elsey-core.zip',
	    'required'			=> true,
	    'external_url'	=> 'https://victorthemes.com/',
    ),
    // MailChimp for WordPress
    array(
	    'name'					=> 'MailChimp for WordPress',
	    'slug'					=> 'mailchimp-for-wp',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/mailchimp-for-wp/',
    ),
    // Slider Revolution
    array(
	    'name'					=> 'Slider Revolution',
	    'slug'     			=> 'revslider',
	    'source'				=> 'http://victorthemes.com/plugins/revslider.zip',
	    'required'			=> true,
	    'external_url'	=> 'https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380/',
    ),
    // One Click Demo Import
    array(
	    'name'					=> 'One Click Demo Import',
	    'slug'					=> 'one-click-demo-import',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/one-click-demo-import/',
    ),
    // WooCommerce
    array(
	    'name'					=> 'WooCommerce',
	    'slug'					=> 'woocommerce',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/woocommerce/',
    ),
    // WooCommerce Currency Switcher
    array(
	    'name'					=> 'WooCommerce Currency Switcher',
	    'slug'					=> 'woocommerce-currency-switcher',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/woocommerce-currency-switcher/',
    ),
    // WooCommerce Quantity Increment
    array(
	    'name'					=> 'WooCommerce Quantity Increment',
	    'slug'					=> 'woocommerce-quantity-increment',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/woocommerce-quantity-increment/',
    ),
    // YITH WooCommerce Wishlist
    array(
	    'name'					=> 'YITH WooCommerce Wishlist',
	    'slug'					=> 'yith-woocommerce-wishlist',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/yith-woocommerce-wishlist/',
    ),
    // WP-PageNavi
    array(
	    'name'					=> 'WP-PageNavi',
	    'slug'					=> 'wp-pagenavi',
	    'required'			=> true,
	    'external_url'	=> 'https://wordpress.org/plugins/wp-pagenavi/',
    ),
    // WPBakery Visual Composer
    array(
	    'name'					=> 'WPBakery Visual Composer',
	    'slug'     			=> 'js_composer',
	    'source'				=> 'http://victorthemes.com/plugins/js_composer.zip',
	    'required'			=> true,
	    'external_url'	=> 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431',
    ),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		/*
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'theme-slug' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'theme-slug' ),
			/* translators: %s: plugin name. * /
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'theme-slug' ),
			/* translators: %s: plugin name. * /
			'updating'                        => esc_html__( 'Updating Plugin: %s', 'theme-slug' ),
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'theme-slug' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'theme-slug'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'theme-slug'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'theme-slug'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). * /
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'theme-slug'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'theme-slug'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'theme-slug'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'theme-slug'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'theme-slug'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'theme-slug'
			),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'theme-slug' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'theme-slug' ),
			'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'theme-slug' ),
			/* translators: 1: plugin name. * /
			'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'theme-slug' ),
			/* translators: 1: plugin name. * /
			'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'theme-slug' ),
			/* translators: 1: dashboard link. * /
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'theme-slug' ),
			'dismiss'                         => esc_html__( 'Dismiss this notice', 'theme-slug' ),
			'notice_cannot_install_activate'  => esc_html__( 'There are one or more required or recommended plugins to install, update or activate.', 'theme-slug' ),
			'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'theme-slug' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		*/
	);

	tgmpa( $plugins, $config );
}
