<?php
/**
 * Visual Composer Library
 * Common Fields
 */
class ElseyLib {

	// Get Theme Name
	public static function 

elsey_cat_name() {
		return __( "by VictorThemes", 'elsey-core' );
	}

	// Notice
	public static function elsey_notice_field($heading, $param, $class, $group) {
		return array(
			"type"        => "notice",
			"heading"     => $heading,
			"param_name"  => $param,
			'class'       => $class,
			'value'       => '',
			"group"       => $group,
		);
	}

	// Extra Class
	public static function elsey_class_option() {
		return array(
		  "type"        => "textfield",
		  "heading"     => __( "Extra class name", 'elsey-core' ),
		  "param_name"  => "class",
		  'value'       => '',
		  "description" => __( "Custom styled class name.", 'elsey-core')
		);
	}

	// ID
	public static function elsey_id_option() {
		return array(
		  "type"        => "textfield",
		  "heading"     => __( "Element ID", 'elsey-core' ),
		  "param_name"  => "id",
		  'value'       => '',
		  "description" => __( "Enter your ID for this element. If you want.", 'elsey-core')
		);
	}

	// Open Link in New Tab
	public static function elsey_open_link_tab() {
		return array(
			"type"        => "switcher",
			"heading"     => __( "Open New Tab? (Links)", 'elsey-core' ),
			"param_name"  => "open_link",
			"std"         => true,
			'value'       => '',
			"on_text"     => __( "Yes", 'elsey-core' ),
			"off_text"    => __( "No", 'elsey-core' ),
		);
	}

	/**
	 * Carousel Default Options
	 */

	// Loop
	public static function elsey_carousel_loop() {
		return array(
			"type"        => "switcher",
			"param_name"  => "carousel_loop",
			"value"       => '',
			"heading"     => __( "Disable Loop?", 'elsey-core' ),
			"group"       => __( "Carousel", 'elsey-core' ),
			"on_text"     => __( "Yes", 'elsey-core' ),
			"off_text"    => __( "No", 'elsey-core' ),
			"description" => __( "Continuously moving carousel, if enabled.", 'elsey-core')
		);
	}
	// Items
	public static function elsey_carousel_items() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Items", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_items",
		  'value'            => '',
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "Enter the numeric value of how many items you want in per slide.", 'elsey-core')
		);
	}
	// Margin
	public static function elsey_carousel_margin() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Margin", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_margin",
		  'value'            => '',
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "Enter the numeric value of how much space you want between each carousel item.", 'elsey-core')
		);
	}
	// Dots
	public static function elsey_carousel_dots() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Dots", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_dots",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "If you want Carousel Dots, enable it.", 'elsey-core')
		);
	}
	// Nav
	public static function elsey_carousel_nav() {
		return array(
		  "type"             => "switcher",
		  "param_name"       => "carousel_nav",
		  "value"            => '',
			"heading"          => __( "Navigation", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "If you want Carousel Navigation, enable it.", 'elsey-core')
		);
	}
	// Autoplay Timeout
	public static function elsey_carousel_autoplay_timeout() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Autoplay Timeout", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_autoplay_timeout",
		  'value'            => '',
		  "description"      => __( "Change carousel Autoplay timing value. Default : 5000. Means 5 seconds.", 'elsey-core')
		);
	}
	// Autoplay
	public static function elsey_carousel_autoplay() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Autoplay", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_autoplay",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "If you want to start Carousel automatically, enable it.", 'elsey-core')
		);
	}
	// Animate Out
	public static function elsey_carousel_animateout() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Animate Out", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_animate_out",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "CSS3 animation out.", 'elsey-core')
		);
	}
	// Mouse Drag
	public static function elsey_carousel_mousedrag() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Disable Mouse Drag?", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_mousedrag",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "If you want to disable Mouse Drag, check it.", 'elsey-core')
		);
	}
	// Auto Width
	public static function elsey_carousel_autowidth() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Auto Width", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_autowidth",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "Adjust Auto Width automatically for each carousel items.", 'elsey-core')
		);
	}
	// Auto Height
	public static function elsey_carousel_autoheight() {
		return array(
		  "type"             => "switcher",
			"heading"          => __( "Auto Height", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_autoheight",
			"on_text"          => __( "Yes", 'elsey-core' ),
			"off_text"         => __( "No", 'elsey-core' ),
			"value"            => '',
			'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
		  "description"      => __( "Adjust Auto Height automatically for each carousel items.", 'elsey-core')
		);
	}
	// Tablet
	public static function elsey_carousel_tablet() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Tablet", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_tablet",
		  'value'            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "Enter number of items to show in tablet.", 'elsey-core')
		);
	}
	// Mobile
	public static function elsey_carousel_mobile() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Mobile", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_mobile",
		  'value'            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "Enter number of items to show in mobile.", 'elsey-core')
		);
	}
	// Small Mobile
	public static function elsey_carousel_small_mobile() {
		return array(
		  "type"             => "textfield",
			"heading"          => __( "Small Mobile", 'elsey-core' ),
		  "group"            => __( "Carousel", 'elsey-core' ),
		  "param_name"       => "carousel_small_mobile",
		  'value'            => '',
			'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
		  "description"      => __( "Enter number of items to show in small mobile.", 'elsey-core')
		);
	}

}

/* Shortcode Extends */
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
  class WPBakeryShortCode_Trnr_Histories extends WPBakeryShortCodesContainer {
  }
  class WPBakeryShortCode_Trnr_Map_Tabs extends WPBakeryShortCodesContainer {
  }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
  class WPBakeryShortCode_Trnr_History extends WPBakeryShortCode {
  }
  class WPBakeryShortCode_Trnr_Map_Tab extends WPBakeryShortCode {
  }
}

// Call to Action
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
  class WPBakeryShortCode_Trnr_Ctas extends WPBakeryShortCodesContainer {
  }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
  class WPBakeryShortCode_Trnr_Cta extends WPBakeryShortCode {
  }
}

/*
 * Load All Shortcodes within a directory of visual-composer/shortcodes
 */
function elsey_all_shortcodes() {
	$dirs = glob( ELSEY_SHORTCODE_PATH . '*', GLOB_ONLYDIR );
	if ( !$dirs ) return;
	foreach ($dirs as $dir) {
		$dirname = basename( $dir );

		/* Include all shortcodes backend options file */
		$options_file = $dir . DS . $dirname . '-options.php';
		$options = array();
		if ( file_exists( $options_file ) ) {
			include_once( $options_file );
		} else {
			continue;
		}

		/* Include all shortcodes frondend options file */
		$shortcode_class_file = $dir . DS . $dirname .'.php';
		if ( file_exists( $shortcode_class_file ) ) {
			include_once( $shortcode_class_file );
		}
	}
}
elsey_all_shortcodes();

if( ! function_exists( 'vc_add_shortcode_param' ) && function_exists( 'add_shortcode_param' ) ) {
  function vc_add_shortcode_param( $name, $form_field_callback, $script_url = null ) {
    return add_shortcode_param( $name, $form_field_callback, $script_url );
  }
}

/* Inline Style */
global $all_inline_styles;
$all_inline_styles = array();
if( ! function_exists( 'add_inline_style' ) ) {
  function add_inline_style( $style ) {
    global $all_inline_styles;
    array_push( $all_inline_styles, $style );
  }
}

/* Enqueue Inline Styles */
if ( ! function_exists( 'elsey_enqueue_inline_styles' ) ) {
  function elsey_enqueue_inline_styles() {

    global $all_inline_styles;

    if ( ! empty( $all_inline_styles ) ) {
      echo '<style id="els-inline-style" type="text/css">'. elsey_compress_css_lines( join( '', $all_inline_styles ) ) .'</style>';
    }

  }
  add_action( 'wp_footer', 'elsey_enqueue_inline_styles' );
}

/* Validate px entered in field */
if( ! function_exists( 'elsey_core_check_px' ) ) {
  function elsey_core_check_px( $num ) {
    return ( is_numeric( $num ) ) ? $num . 'px' : $num;
  }
}

/* Tabs Added Via elsey_tabs_function */
if( function_exists( 'elsey_tabs_function' ) ) {
  add_shortcode( 'vc_tabs', 'elsey_tabs_function' );
  add_shortcode( 'vc_tab', 'elsey_tab_function' );
}