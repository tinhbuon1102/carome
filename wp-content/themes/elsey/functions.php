<?php
/*
 * Elsey Theme's Functions
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/**
 * Define - Folder Paths
 */
define( 'ELSEY_THEMEROOT_PATH', get_template_directory() );
define( 'ELSEY_THEMEROOT_URI', get_template_directory_uri() );
define( 'ELSEY_CSS', ELSEY_THEMEROOT_URI . '/assets/css' );
define( 'ELSEY_IMAGES', ELSEY_THEMEROOT_URI . '/assets/images' );
define( 'ELSEY_SCRIPTS', ELSEY_THEMEROOT_URI . '/assets/js' );
define( 'ELSEY_CS_IMAGES', ELSEY_THEMEROOT_URI . '/inc/theme-options/theme-extend/images' );
define( 'ELSEY_LAYOUT', get_template_directory() . '/layouts' );
define( 'ELSEY_FRAMEWORK', get_template_directory() . '/inc' );
define( 'ELSEY_CS_FRAMEWORK', get_template_directory() . '/inc/theme-options/theme-extend' ); // Called in Icons field *.json
define( 'ELSEY_ADMIN_PATH', get_template_directory() . '/inc/theme-options/cs-framework' ); // Called in Icons field *.json

/**
 * Define - Global Theme Info's
 */
if (is_child_theme()) { // If Child Theme Active
	$elsey_theme_child = wp_get_theme();
	$elsey_get_parent = $elsey_theme_child->Template;
	$elsey_theme = wp_get_theme($elsey_get_parent);
} else { // Parent Theme Active
	$elsey_theme = wp_get_theme();
}

define('ELSEY_NAME', $elsey_theme->get( 'Name' ), true);
define('ELSEY_VERSION', $elsey_theme->get( 'Version' ), true);
define('ELSEY_BRAND_URL', $elsey_theme->get( 'AuthorURI' ), true);
define('ELSEY_BRAND_NAME', $elsey_theme->get( 'Author' ), true);

/**
 * All Main Files Include
 */
require_once( ELSEY_FRAMEWORK . '/init.php' );
