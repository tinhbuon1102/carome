<?php
/*
 * All CSS and JS files are enqueued from this file
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/**
 * Enqueue Files for FrontEnd
 */
if ( ! function_exists( 'elsey_vt_scripts_styles' ) ) {
  function elsey_vt_scripts_styles() {

		// Styles
		wp_enqueue_style( 'font-awesome', ELSEY_THEMEROOT_URI . '/inc/theme-options/cs-framework/assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'bootstrap', ELSEY_CSS .'/bootstrap.min.css', array(), '3.3.6', 'all' );
		wp_enqueue_style( 'loaders', ELSEY_CSS .'/loaders.css', array(), '0.9.9', 'all' );
		wp_enqueue_style( 'owl-carousel', ELSEY_CSS .'/owl.carousel.css', array(), '2.4', 'all' );
		wp_enqueue_style( 'slick-slider', ELSEY_CSS .'/slick-slider.min.css', array(), '1.6', 'all' );
		wp_enqueue_style( 'magnific-popup', ELSEY_CSS .'/magnific-popup.min.css', array(), '0.9.9', 'all' );
		wp_enqueue_style( 'animate', ELSEY_CSS . '/animate.css', array(), '1.0.0', 'all');
		wp_enqueue_style( 'simple-line-icons', ELSEY_CSS .'/simple-line-icons.css', array(), '2.4.0', 'all' );
		wp_enqueue_style( 'lightgallery', ELSEY_CSS . '/lightgallery.css', array(), '1.0.2', 'all');
		wp_enqueue_style( 'elsey-main-style', ELSEY_CSS .'/styles.css', array(), ELSEY_VERSION, 'all' );

		// Scripts
		wp_enqueue_script( 'bootstrap', ELSEY_SCRIPTS . '/bootstrap.min.js', array( 'jquery' ), '3.3.6', true );
		wp_enqueue_script( 'html5shiv', ELSEY_SCRIPTS . '/html5shiv.min.js', array( 'jquery' ), '3.7.3', true );
		wp_enqueue_script( 'respond', ELSEY_SCRIPTS . '/respond.min.js', array( 'jquery' ), '1.4.2', true );
		wp_enqueue_script( 'slicknav', ELSEY_SCRIPTS . '/slicknav.min.js', array( 'jquery' ), '1.0.10', true );
		wp_enqueue_script( 'fitvids', ELSEY_SCRIPTS . '/fitvids.js', array( 'jquery' ), '1.1.0', true );
		wp_enqueue_script( 'nice-select', ELSEY_SCRIPTS . '/nice-select.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'mcustom-scrollbar', ELSEY_SCRIPTS . '/mcustom-scrollbar.min.js', array( 'jquery' ), '3.1.5', true );
		wp_enqueue_script( 'matchheight', ELSEY_SCRIPTS . '/matchheight.min.js', array( 'jquery' ), '0.7.2', true );
		wp_enqueue_script( 'magnific-popup', ELSEY_SCRIPTS . '/magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
    wp_enqueue_script( 'lightgallery', ELSEY_SCRIPTS . '/lightgallery-all.min.js', array( 'jquery' ), '1.6.4', true );
    wp_enqueue_script( 'mousewheel', ELSEY_SCRIPTS . '/mousewheel.min.js', array( 'jquery' ), '3.1.13', true );
    wp_enqueue_script( 'owl-carousel', ELSEY_SCRIPTS . '/owlcarousel.min.js', array( 'jquery' ), '2.2.0', true );
    wp_enqueue_script( 'slick-slider', ELSEY_SCRIPTS . '/slick-slider.min.js', array( 'jquery' ), '1.6.0', true );
    wp_enqueue_script( 'imagesloaded', ELSEY_SCRIPTS . '/imagesloaded.min.js', array( 'jquery' ), '4.1.1', true );
    wp_enqueue_script( 'isotope', ELSEY_SCRIPTS . '/isotope.min.js', array( 'jquery' ), '3.0.4', true );
    wp_enqueue_script( 'packery', ELSEY_SCRIPTS . '/packery.min.js', array( 'jquery' ), '2.0.0', true );
    wp_enqueue_script( 'sticky-header', ELSEY_SCRIPTS . '/sticky.min.js', array( 'jquery' ), '1.0.4', true );
    wp_enqueue_script( 'sticky-sidebar', ELSEY_SCRIPTS . '/theia-sticky-sidebar.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'onepage-nav', ELSEY_SCRIPTS . '/onepage-nav.min.js', array( 'jquery' ), '3.0.0', true );
    wp_enqueue_script( 'sticky-header', ELSEY_SCRIPTS . '/parallax.min.js', array( 'jquery' ), '1.1.3', true );
    wp_enqueue_script( 'stellar', ELSEY_SCRIPTS . '/stellar.min.js', array( 'jquery' ), '0.6.2', true );
    wp_enqueue_script( 'waypoints', ELSEY_SCRIPTS . '/waypoints.min.js', array( 'jquery' ), '4.0.1', true );
    wp_enqueue_script( 'counterup', ELSEY_SCRIPTS . '/counterup.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'lazyload', ELSEY_SCRIPTS . '/unveil-lazyload.js', array( 'jquery' ), '1.0.0', false );
		wp_enqueue_script( 'instafeed', ELSEY_SCRIPTS . '/instafeed.min.js', array( 'jquery' ), '1.9.3', false );
		wp_enqueue_script( 'elsey-scripts', ELSEY_SCRIPTS . '/scripts.js', array( 'jquery' ), ELSEY_VERSION, true );

		// Comments
		wp_enqueue_script( 'validate', ELSEY_SCRIPTS . '/jquery.validate.min.js', array( 'jquery' ), '1.9.0', true );
		wp_add_inline_script( 'validate', 'jQuery(document).ready(function($) {$("#commentform").validate({rules: {author: {required: true,minlength: 2},email: {required: true,email: true},comment: {required: true,minlength: 10}}});});' );

		// WooCommerce
		if (class_exists( 'WooCommerce' )) {
		  wp_enqueue_style( 'woocommerce', ELSEY_CSS . '/woocommerce.css', null, 1.0, 'all' );
		  wp_enqueue_style( 'woocommerce-responsive', ELSEY_CSS . '/woocommerce-responsive.css', null, 1.0, 'all' );
		  // Styles
		  //wp_enqueue_style( 'woocommerce', ELSEY_THEMEROOT_URI . '/inc/plugins/woocommerce/woocommerce.css', null, 1.0, 'all' );
		  //wp_enqueue_style( 'woocommerce-responsive', ELSEY_THEMEROOT_URI . '/inc/plugins/woocommerce/woocommerce-responsive.css', null, 1.0, 'all' );
		  // Scripts
		  wp_enqueue_script( 'woocommerce-scripts', ELSEY_SCRIPTS . '/wc-scripts.js', array( 'jquery' ), 1.0, true );
		}

		// Responsive Active
		wp_enqueue_style( 'els-responsive', ELSEY_CSS .'/responsive.css', array(), ELSEY_VERSION, 'all' );

		// Adds support for pages with threaded comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		  wp_enqueue_script( 'comment-reply' );
		}

  }
  add_action( 'wp_enqueue_scripts', 'elsey_vt_scripts_styles' );
}

/**
 * Apply theme's stylesheet to the visual editor.
 *
 * @uses add_editor_style() Links a stylesheet to visual editor
 * @uses get_stylesheet_uri() Returns URI of theme stylesheet
 */
function elsey_add_editor_styles() {
  add_editor_style( get_stylesheet_uri() );
}
add_action( 'init', 'elsey_add_editor_styles' );

/** Enqueue Files for BackEnd **/
if ( ! function_exists( 'elsey_vt_admin_scripts_styles' ) ) {
  function elsey_vt_admin_scripts_styles() {

	wp_enqueue_style( 'els-admin-css', ELSEY_CSS . '/admin-styles.css', true );
	wp_enqueue_script( 'els-admin-scripts', ELSEY_SCRIPTS . '/admin-scripts.js', true );
	wp_enqueue_style( 'simple-line-icons', ELSEY_CSS .'/simple-line-icons.css', array(), '2.4.0', 'all' );

  }
  add_action( 'admin_enqueue_scripts', 'elsey_vt_admin_scripts_styles' );
}

/** Enqueue All Styles **/
if ( ! function_exists( 'elsey_vt_wp_enqueue_styles' ) ) {
  function elsey_vt_wp_enqueue_styles() {

		elsey_vt_google_fonts();
		add_action( 'wp_head', 'elsey_vt_custom_css', 99 );
		add_action( 'wp_head', 'elsey_vt_custom_js', 99 );
		if ( is_child_theme() ){
		  wp_enqueue_style( 'elsey_framework_child', get_stylesheet_uri() );
		}

  }
  add_action( 'wp_enqueue_scripts', 'elsey_vt_wp_enqueue_styles' );
}
