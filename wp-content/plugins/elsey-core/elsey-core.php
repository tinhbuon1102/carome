<?php
/*
Plugin Name: Elsey Core
Plugin URI: http://themeforest.net/user/VictorThemes
Description: Plugin to contain shortcodes and custom post types of the Elsey Theme.
Author: VictorThemes
Author URI: http://themeforest.net/user/VictorThemes/portfolio
Version: 1.2
Text Domain: elsey-core
*/

if( ! function_exists( 'elsey_block_direct_access' ) ) {
	function elsey_block_direct_access() {
		if( ! defined( 'ABSPATH' ) ) {
			exit( 'Forbidden' );
		}
	}
}

/* Plugin URL */
define( 'ELSEY_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/* Plugin PATH */
define( 'ELSEY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELSEY_PLUGIN_ASTS', ELSEY_PLUGIN_URL . 'assets' );
define( 'ELSEY_PLUGIN_IMGS', ELSEY_PLUGIN_ASTS . '/images' );
define( 'ELSEY_PLUGIN_INC', ELSEY_PLUGIN_PATH . 'inc' );

/* DIRECTORY SEPARATOR */
define ( 'DS' , DIRECTORY_SEPARATOR );

/* Elsey Shortcode Path */
define( 'ELSEY_SHORTCODE_BASE_PATH', ELSEY_PLUGIN_PATH . 'visual-composer/' );
define( 'ELSEY_SHORTCODE_PATH', ELSEY_SHORTCODE_BASE_PATH . 'shortcodes/' );

/* Check if Codestar Framework is Active or Not! */
function elsey_framework_active() {
  return ( defined( 'CS_VERSION' ) ) ? true : false;
}

/* ELSEY_NAME_P */
define('ELSEY_NAME_P', 'Elsey', true);

/* Initial File */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (is_plugin_active('elsey-core/elsey-core.php')) {

	// Custom Post Type
	require_once( ELSEY_PLUGIN_INC . '/custom-post-type.php' );

  // Shortcodes
  require_once( ELSEY_SHORTCODE_BASE_PATH . '/vc-setup.php' );
  require_once( ELSEY_PLUGIN_INC . '/custom-shortcodes/theme-shortcodes.php' );
  require_once( ELSEY_PLUGIN_INC . '/custom-shortcodes/custom-shortcodes.php' );

  // Widgets
  require_once( ELSEY_PLUGIN_INC . '/widgets/blog-recent-posts.php' );
  require_once( ELSEY_PLUGIN_INC . '/widgets/theme-instagram-widget.php' );
  require_once( ELSEY_PLUGIN_INC . '/widgets/theme-text-widget.php' );
  require_once( ELSEY_PLUGIN_INC . '/widgets/theme-widget-extra-fields.php' );

  require_once( ELSEY_PLUGIN_INC . '/widgets/product-price-filter.php' );
  require_once( ELSEY_PLUGIN_INC . '/widgets/product-attribute-filter.php' );

  // ElseyWP
  function elsey_dequeue_unnecessary_styles() {
    wp_dequeue_style( 'contact-form-7' );
    wp_deregister_style( 'contact-form-7' );

    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
    wp_dequeue_script( 'prettyPhoto' );
    wp_dequeue_script( 'prettyPhoto-init' );

    wp_dequeue_style( 'woocommerce-smallscreen' );

    wp_dequeue_script('prettyPhoto');
    wp_dequeue_script('prettyPhoto-init');

    wp_dequeue_script( 'contact-form-7' );
    wp_deregister_script( 'contact-form-7' );
  }
  // add_action( 'wp_enqueue_scripts', 'elsey_dequeue_unnecessary_styles', 20 );

}

/* Plugin language */
function elsey_plugin_language_setup() {
  load_plugin_textdomain( 'elsey-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'elsey_plugin_language_setup' );

/* WPAUTOP for shortcode output */
if( ! function_exists( 'elsey_set_wpautop' ) ) {
  function elsey_set_wpautop( $content, $force = true ) {
    if ( $force ) {
      $content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
    }
    return do_shortcode( shortcode_unautop( $content ) );
  }
}

/* Use shortcodes in text widgets */
add_filter('widget_text', 'do_shortcode');

/* Shortcodes enable in the_excerpt */
add_filter('the_excerpt', 'do_shortcode');

/* Remove p tag and add by our self in the_excerpt */
remove_filter('the_excerpt', 'wpautop');

/* Add Extra Social Fields in Admin User Profile */
function elsey_add_twitter_facebook( $contactmethods ) {
  $contactmethods['facebook']   = 'Facebook';
  $contactmethods['twitter']    = 'Twitter';
  $contactmethods['google_plus']  = 'Google Plus';
  $contactmethods['linkedin']   = 'Linkedin';
  return $contactmethods;
}
add_filter('user_contactmethods','elsey_add_twitter_facebook',10,1);

/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'cs_encode_string' ) ) {
  function cs_encode_string( $string ) {
    return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
  }
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'cs_decode_string' ) ) {
  function cs_decode_string( $string ) {
    return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
  }
}

/* Share Options */
if ( ! function_exists( 'elsey_wp_share_option' ) ) {
  function elsey_wp_share_option() {
    global $post;
    $page_url = get_permalink($post->ID );
    $title = $post->post_title;
    $share_text = cs_get_option('share_text');
    $share_text = $share_text ? $share_text : esc_html__( 'Share', 'elsey-core' );
    $share_on_text = cs_get_option('share_on_text');
    $share_on_text = $share_on_text ? $share_on_text : esc_html__( 'Share On', 'elsey-core' ); ?>

    <div class="els-share-box">
      <a class="els-share" href="javascript:void(0);">
        <img src="<?php echo ELSEY_IMAGES; ?>/share-icon.png" alt="share-icon" width="16" height="16"/><?php echo esc_attr($share_text); ?><span>:</span></a>
      <ul>
        <li>
          <a href="//twitter.com/home?status=<?php print(urlencode($title)); ?>+<?php print(urlencode($page_url)); ?>" class="icon-fa-twitter" data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr( $share_on_text .' '); echo esc_html__('Twitter', 'elsey-core'); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
        </li>
        <li>
          <a href="//www.facebook.com/sharer/sharer.php?u=<?php print(urlencode($page_url)); ?>&amp;t=<?php print(urlencode($title)); ?>" class="icon-fa-facebook" data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr( $share_on_text .' '); echo esc_html__('Facebook', 'elsey-core'); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
        </li>
        <li>
          <a href="//plus.google.com/share?url=<?php print(urlencode($page_url)); ?>" class="icon-fa-google-plus" data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr( $share_on_text .' '); echo esc_html__('Google+', 'elsey-core'); ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
        </li>
        <li>
          <a href="http://pinterest.com/pin/create/button/?url=<?php print(urlencode($page_url)); ?>&amp;description=<?php print(urlencode($title)); ?>" class="icon-fa-pinterest" data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr( $share_on_text .' '); echo esc_html__('Pinterest', 'elsey-core'); ?>"><i class="fa fa-pinterest"></i></a>
        </li>
      </ul>
    </div>
  <?php
  }
}

if ( ! function_exists( 'elsey_wp_single_product_share_option' ) ) {
  function elsey_wp_single_product_share_option() {
    global $post;
    $page_url      = get_permalink($post->ID);
    $title         = $post->post_title;
    $share_on_text = cs_get_option('product_share_on_text') ? cs_get_option('product_share_on_text') : esc_html__('Share On', 'elsey');
    $share_hide    = (array) cs_get_option( 'woo_single_share_hide' );

    $output = '';
    $output.= '<div class="els-single-product-share"><div class="container">';
    $output.= '<ul>';
    if ( !in_array( 'facebook', $share_hide ) ) :
      $output.= '<li><a href="http://www.facebook.com/sharer/sharer.php?u='.urlencode($page_url).'&amp;t='.urlencode($title).'" class="icon-fa-facebook" data-toggle="tooltip" data-placement="top" title="'.esc_attr($share_on_text).' '.esc_html__('Facebook', 'elsey').'" ><i class="fa fa-facebook"></i></a></li>';
    endif;
    if ( !in_array( 'twitter', $share_hide ) ) :
      $output.= '<li><a href="http://twitter.com/home?status='.urlencode($title).'+'.urlencode($page_url).'" class="icon-fa-twitter" data-toggle="tooltip" data-placement="top" title="'.esc_attr($share_on_text).' '.esc_html__('Twitter', 'elsey').'" ><i class="fa fa-twitter"></i></a></li>';
    endif;
    if ( !in_array( 'googleplus', $share_hide ) ) :
      $output.= '<li><a href="https://plus.google.com/share?url='.urlencode($page_url).'" class="icon-fa-google-plus" data-toggle="tooltip" data-placement="top" title="'.esc_attr($share_on_text).' '.esc_html__('Google+', 'elsey').'" ><i class="fa fa-google-plus" ></i></a></li>';
    endif;
    if ( !in_array( 'pinterest', $share_hide ) ) :
      $output.= '<li><a href="http://pinterest.com/pin/create/button/?url='.urlencode($page_url).'&amp;description='.urlencode($title).'" class="icon-fa-pinterest" data-toggle="tooltip" data-placement="top" title="'.esc_attr($share_on_text).' '.esc_html__('Pinterest', 'elsey').'" ><i class="fa fa-pinterest-p"></i></a></li>';
    endif;
    if ( !in_array( 'linkedin', $share_hide ) ) :
      $output.= '<li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($page_url).'&amp;title='.urlencode($title).'" class="icon-fa-linkedin" data-toggle="tooltip" data-placement="top" title="'.esc_attr($share_on_text).' '.esc_html__('Linkedin', 'elsey').'" ><i class="fa fa-linkedin"></i></a></li>';
    endif;
    $output.= '</ul></div></div>';
    echo $output;
  }
}

/**
 * One Click Install
 * @return Import Demos - Needed Import Demo's
 */
function elsey_import_files() {
  return array(
    array(
      'import_file_name'           => 'Elsey',
      'import_file_url'            => trailingslashit( ELSEY_PLUGIN_URL ) . 'inc/import/content.xml',
      'import_widget_file_url'     => trailingslashit( ELSEY_PLUGIN_URL ) . 'inc/import/widget.wie',
      'local_import_csf'           => array(
        array(
          'file_path'   => trailingslashit( ELSEY_PLUGIN_URL ) . 'inc/import/theme-options.json',
          'option_name' => '_cs_options',
        ),
      ),
      'import_notice'              => __( 'Import process may take 3-5 minutes, please be patient. It\'s really based on your network speed.', 'elsey-core' ),
      'preview_url'                => 'http://victorthemes.com/themes/elsey',
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'elsey_import_files' );

/**
 * One Click Import Function for CodeStar Framework
 */
if ( ! function_exists( 'csf_after_content_import_execution' ) ) {
  function csf_after_content_import_execution( $selected_import_files, $import_files, $selected_index ) {

    $downloader = new OCDI\Downloader();

    if( ! empty( $import_files[$selected_index]['import_csf'] ) ) {

      foreach( $import_files[$selected_index]['import_csf'] as $index => $import ) {
        $file_path = $downloader->download_file( $import['file_url'], 'demo-csf-import-file-'. $index . '-'. date( 'Y-m-d__H-i-s' ) .'.json' );
        $file_raw  = OCDI\Helpers::data_from_file( $file_path );
        update_option( $import['option_name'], json_decode( $file_raw, true ) );
      }

    } else if( ! empty( $import_files[$selected_index]['local_import_csf'] ) ) {

      foreach( $import_files[$selected_index]['local_import_csf'] as $index => $import ) {
        $file_path = $import['file_path'];
        $file_raw  = OCDI\Helpers::data_from_file( $file_path );
        update_option( $import['option_name'], json_decode( $file_raw, true ) );
      }

    }

    // Put info to log file.
    $ocdi       = OCDI\OneClickDemoImport::get_instance();
    $log_path   = $ocdi->get_log_file_path();

    OCDI\Helpers::append_to_file( 'Codestar Framework files loaded.'. $logs, $log_path );

  }
  add_action('pt-ocdi/after_content_import_execution', 'csf_after_content_import_execution', 3, 99 );
}

/**
 * [elsey_after_import_setup]
 * @return Front Page, Post Page & Menu Set
 */
function elsey_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'primary' => $main_menu->term_id,
      )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Shop - Default' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'elsey_after_import_setup' );

// Install Demos Menu - Menu Edited
function elsey_core_one_click_page( $default_settings ) {
  $default_settings['parent_slug'] = 'themes.php';
  $default_settings['page_title']  = esc_html__( 'Install Demos', 'elsey-core' );
  $default_settings['menu_title']  = esc_html__( 'Install Demos', 'elsey-core' );
  $default_settings['capability']  = 'import';
  $default_settings['menu_slug']   = 'install_demos';

  return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'elsey_core_one_click_page' );

// Model Popup - Width Increased
function elsey_ocdi_confirmation_dialog_options ( $options ) {
  return array_merge( $options, array(
    'width'       => 600,
    'dialogClass' => 'wp-dialog',
    'resizable'   => false,
    'height'      => 'auto',
    'modal'       => true,
  ) );
}
add_filter( 'pt-ocdi/confirmation_dialog_options', 'elsey_ocdi_confirmation_dialog_options', 10, 1 );

// Disable the branding notice - ProteusThemes
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

function ocdi_plugin_intro_text( $default_text ) {
    $default_text .= '<h1>Install Demos</h1>
    <div class="elsey-core_intro-text vtdemo-one-click">
    <div id="poststuff">

      <div class="postbox important-notes">
        <h3><span>Important notes:</span></h3>
        <div class="inside">
          <ol>
            <li>Please note, this import process will take time. So, please be patient.</li>
            <li>Please make sure you\'ve installed recommended plugins before you import this content.</li>
            <li>All images are demo purposes only. So, images may repeat in your site content.</li>
          </ol>
        </div>
      </div>

      <div class="postbox vt-support-box vt-error-box">
        <h3><span>Don\'t Edit Parent Theme Files:</span></h3>
        <div class="inside">
          <p>Don\'t edit any files from parent theme! Use only a <strong>Child Theme</strong> files for your customizations!</p>
          <p>If you get future updates from our theme, you\'ll lose edited customization from your parent theme.</p>
        </div>
      </div>

      <div class="postbox vt-support-box">
        <h3><span>Need Support?</span> <a href="https://www.youtube.com/watch?v=tCmLh3UdkMA" target="_blank" class="cs-section-video"><i class="fa fa-youtube-play"></i> <span>How to?</span></a></h3>
        <div class="inside">
          <p>Have any doubts regarding this installation or any other issues? Please feel free to open a ticket in our support center.</p>
          <a href="http://victorthemes.com/docs/elsey" class="button-primary" target="_blank">Docs</a>
          <a href="https://victorthemes.com/my-account/support/" class="button-primary" target="_blank">Support</a>
          <a href="https://themeforest.net/item/elsey-responsive-ecommerce-theme/20352299?ref=VictorThemes" class="button-primary" target="_blank">Item Page</a>
        </div>
      </div>

    </div>
  </div>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );
