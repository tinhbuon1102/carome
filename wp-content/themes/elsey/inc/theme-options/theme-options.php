<?php
/*
 * All Theme Options for elsey theme.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

function elsey_vt_settings( $settings ) {
  $settings           = array(
    'menu_title'      => ELSEY_NAME . esc_html__(' Options', 'elsey'),
    'menu_slug'       => sanitize_title(ELSEY_NAME) . '_options',
    'menu_type'       => 'menu',
    'menu_icon'       => 'dashicons-awards',
    'menu_position'   => '4',
    'ajax_save'       => false,
    'show_reset_all'  => true,
    'framework_title' => ELSEY_NAME .' <small>V-'. ELSEY_VERSION .' by <a href="'. ELSEY_BRAND_URL .'" target="_blank">'. ELSEY_BRAND_NAME .'</a></small>',
  );
  return $settings;
}
add_filter( 'cs_framework_settings', 'elsey_vt_settings' );

// Theme Framework Options
function elsey_vt_options( $options ) {

  // Remove old options
  $options = array();

  // ------------------------------
  // Theme Brand
  // ------------------------------
  $options[]   = array(
    'name'     => 'theme_brand',
    'title'    => esc_html__('Logo', 'elsey'),
    'icon'     => 'fa fa-bookmark',
    'fields' => array(

      // Site Logo
      array(
        'type'        => 'notice',
        'class'       => 'info cs-vt-heading',
        'content'     => esc_html__('Site Logo', 'elsey')
      ),
      array(
        'id'          => 'brand_logo_default',
        'type'        => 'image',
        'add_title'   => esc_html__('Add Logo', 'elsey'),
        'title'       => esc_html__('Default Logo', 'elsey'),
        'info'        => esc_html__('Upload your default logo here. If you not upload, then site title will load in this logo location.', 'elsey'),
      ),
      array(
        'id'          => 'brand_logo_retina',
        'type'        => 'image',
        'add_title'   => esc_html__('Add Retina Logo', 'elsey'),
        'title'       => esc_html__('Retina Logo', 'elsey'),
        'info'        => esc_html__('Upload your retina logo here. Recommended size is 2x from default logo.', 'elsey'),
      ),
      array(
        'id'          => 'brand_logo_width',
        'type'        => 'text',
        'unit'        => 'px',
        'title'       => esc_html__('Retina & Normal Logo Width', 'elsey'),
      ),
      array(
        'id'          => 'brand_logo_top_space',
        'type'        => 'number',
        'title'       => esc_html__('Logo Top Space', 'elsey'),
        'attributes'  => array( 'placeholder' => 5 ),
        'unit'        => 'px',
      ),
      array(
        'id'          => 'brand_logo_bottom_space',
        'type'        => 'number',
        'title'       => esc_html__('Logo Bottom Space', 'elsey'),
        'attributes'  => array( 'placeholder' => 5 ),
        'unit'        => 'px',
      ),
      array(
        'type'        => 'notice',
        'class'       => 'info cs-vt-heading',
        'content'     => esc_html__('Transparent Logo', 'elsey')
      ),
      array(
        'id'          => 'transparent_logo_default',
        'type'        => 'image',
        'title'       => esc_html__('Transparent Logo', 'elsey'),
        'info'        => esc_html__('Upload your transparent header logo here. This logo is used in transparent header by enabled in each pages.', 'elsey'),
        'add_title'   => esc_html__('Add Transparent Logo', 'elsey'),
      ),
      array(
        'id'          => 'transparent_logo_retina',
        'type'        => 'image',
        'title'       => esc_html__('Transparent Retina Logo', 'elsey'),
        'info'        => esc_html__('Upload your transparent header retina logo here. This logo is used in transparent header by enabled in each pages.', 'elsey'),
        'add_title'   => esc_html__('Add Transparent Retina Logo', 'elsey'),
      ),

      // WordPress Admin Logo
      array(
        'type'        => 'notice',
        'class'       => 'info cs-vt-heading',
        'content'     => esc_html__('WordPress Admin Logo', 'elsey')
      ),
      array(
        'id'          => 'brand_logo_wp',
        'type'        => 'image',
        'title'       => esc_html__('Login logo', 'elsey'),
        'info'        => esc_html__('Upload your WordPress login page logo here.', 'elsey'),
        'add_title'   => esc_html__('Add Login Logo', 'elsey'),
      ),
    
        // -----------------------------
        // Begin: Fav
        // -----------------------------
       array(
        'type'        => 'notice',
        'class'       => 'info cs-vt-heading',
        'content'     => esc_html__('Favicon', 'elsey')
      ),
        array(
          'id'    => 'brand_fav_icon',
          'type'  => 'image',
          'title' => esc_html__('Fav Icon', 'elsey'),
          'info'  => esc_html__('Upload your site fav icon, size should be 16x16.', 'elsey'),
          'add_title' => esc_html__('Add Fav Icon', 'elsey'),
        ),

    ),

  );

  // ------------------------------
  // Layout
  // ------------------------------
  $options[] = array(
    'name'   => 'theme_layout',
    'title'  => esc_html__('Layout', 'elsey'),
    'icon'   => 'fa fa-file-text'
  );

  $options[] = array(
    'name'   => 'theme_general',
    'title'  => esc_html__('General', 'elsey'),
    'icon'   => 'fa fa-wrench',
    'fields' => array(
      // -----------------------------
      // Begin: Generel Options
      // -----------------------------
      array(
        'type'             => 'notice',
        'class'            => 'info cs-els-heading',
        'content'          => esc_html__('Preloader Options', 'elsey')
      ),
      array(
        'id'               => 'show_preloader',
        'type'             => 'switcher',
        'default'          => false,
        'title'            => esc_html__('Preloader', 'elsey'),
        'info'             => esc_html__('Turn off if you don\'t want to show preloader.', 'elsey'),
      ),
      array(
        'id'               => 'preloader_styles',
        'type'             => 'select',
        'options'          => array(
          'ball-beat'                  => esc_html__('Ball Beat', 'elsey'),
          'ball-clip-rotate'           => esc_html__('Ball Clip Rotate', 'elsey'),
          'ball-clip-rotate-pulse'     => esc_html__('Ball Clip Rotate Pulse', 'elsey'),
          'ball-clip-rotate-multiple'  => esc_html__('Ball Clip Rotate Multiple', 'elsey'),
          'ball-grid-beat'             => esc_html__('Ball Grid Beat', 'elsey'),
          'ball-grid-pulse'            => esc_html__('Ball Grid Pulse', 'elsey'),
          'ball-pulse'                 => esc_html__('Ball Pulse', 'elsey'),
          'ball-pulse-rise'            => esc_html__('Ball Pulse Rise', 'elsey'),
          'ball-pulse-sync'            => esc_html__('Ball Pulse Sync', 'elsey'),
          'ball-rotate'                => esc_html__('Ball Rotate', 'elsey'),
          'ball-scale'                 => esc_html__('Ball Scale', 'elsey'),
          'ball-scale-multiple'        => esc_html__('Ball Scale Multiple', 'elsey'),
          'ball-scale-ripple'          => esc_html__('Ball Scale Ripple', 'elsey'),
          'ball-scale-ripple-multiple' => esc_html__('Ball Scale Ripple Multiple', 'elsey'),
          'ball-spin-fade-loader'      => esc_html__('Ball Spin Fade Loader', 'elsey'),
          'ball-triangle-path'         => esc_html__('Ball Triangle Path', 'elsey'),
          'ball-zig-zag'               => esc_html__('Ball Zig Zag', 'elsey'),
          'ball-zig-zag-deflect'       => esc_html__('Ball Zig Zag Deflect', 'elsey'),
          'cube-transition'            => esc_html__('Cube Transition', 'elsey'),
          'line-scale'                 => esc_html__('Line Scale', 'elsey'),
          'line-scale-party'           => esc_html__('Line Scale Party', 'elsey'),
          'line-scale-pulse-out'       => esc_html__('Line Scale Pulse Out', 'elsey'),
          'line-scale-pulse-out-rapid' => esc_html__('Line Scale Pulse Out Rapid', 'elsey'),
          'line-spin-fade-loader'      => esc_html__('Line Spin Fade Loader', 'elsey'),
          'pacman'                     => esc_html__('Pacman', 'elsey'),
          'semi-circle-spin'           => esc_html__('Semi Circle Spin', 'elsey'),
          'square-spin'                => esc_html__('Square Spin', 'elsey'),
          'triangle-skew-spin'         => esc_html__('Triangle Skew Spin', 'elsey'),
        ),
        'default_option'   => esc_html__('Select preloader style', 'elsey'),
        'class'            => 'horizontal',
        'title'            => esc_html__('Preloader Style', 'elsey'),
        'dependency'       => array( 'show_preloader', '==', 'true' ),
      ),
      array(
        'type'             => 'notice',
        'class'            => 'info cs-els-heading',
        'content'          => esc_html__('Page Options', 'elsey')
      ),
      array(
        'type'             => 'notice',
        'class'            => 'info cs-els-heading',
        'content'          => esc_html__('Content Background', 'elsey')
      ),
      array(
        'id'               => 'content_layout_bg',
        'type'             => 'background',
        'title'            => esc_html__('Background', 'elsey'),
        'rgba'             => true,
        'info'             => esc_html__('Content area background.', 'elsey'),
      ),
      array(
        'id'               => 'content_layout_bg_overlay',
        'type'             => 'color_picker',
        'title'            => esc_html__('Overlay Color', 'elsey'),
        'rgba'             => true,
        'info'             => esc_html__('Content area background image overlay color.', 'elsey'),
      ),

    ), // End: Fields
  );

  // ------------------------------
  // Header Sections
  // ------------------------------
  $options[]   = array(
    'name'     => 'theme_header_tab',
    'title'    => esc_html__('Header', 'elsey'),
    'icon'     => 'fa fa-bars',
    'sections' => array(

      // Header Top Bar
      array(
        'name'     => 'header_topbar_tab',
        'title'    => esc_html__('Top Bar', 'elsey'),
        'icon'     => 'fa fa-minus',
        'fields'   => array(

          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Top Bar Options', 'elsey')
          ),
          array(
            'id'             => 'need_topbar',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Show Top Bar', 'elsey'),
            'off_text'       => esc_html__('No', 'elsey'),
            'on_text'        => esc_html__('Yes', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show top bar.', 'elsey'),
          ),
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Top Bar Left', 'elsey'),
            'dependency'     => array('need_topbar', '==', true),
          ),
          array(
            'id'             => 'topbar_left_content',
            'type'           => 'textarea',
            'shortcode'      => true,
            'title'          => esc_html__('Top Left Content', 'elsey'),
            'info'           => esc_html__('Top bar left block content.', 'elsey'),
            'dependency'     => array('need_topbar', '==', true),
          ),
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Top Bar Right', 'elsey'),
            'dependency'     => array('need_topbar', '==', true),
          ),
          array(
            'id'             => 'topbar_my_account',
            'type'           => 'switcher',
            'title'          => esc_html__('Top Right Login', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show login/register in top bar right position. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'default'        => true,
            'dependency'     => array('need_topbar', '==', true),
          ),
          array(
            'id'             => 'topbar_right_link',
            'type'           => 'switcher',
            'title'          => esc_html__('Featured Link', 'elsey'),
            'info'           => esc_html__('Turn off if you don\'t want to show featured link at topbar right.', 'elsey'),
            'default'        => false,
            'dependency'     => array('need_topbar', '==', true),
          ),
          array(
            'id'             => 'topbar_link_title',
            'type'           => 'text',
            'title'          => esc_html__('Featured Link Title', 'elsey'),
            'info'           => '',
            'dependency'     => array('need_topbar|topbar_right_link', '==|==', 'true|true'),
          ),
          array(
            'id'             => 'topbar_link_url',
            'type'           => 'text',
            'title'          => esc_html__('Featured Link URL', 'elsey'),
            'info'           => '',
            'dependency'     => array('need_topbar|topbar_right_link', '==|==', 'true|true'),
          ),
          array(
            'id'             => 'topbar_currency',
            'type'           => 'switcher',
            'title'          => esc_html__('Top Right Currency', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show currency selector in top bar right position. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'default'        => true,
            'dependency'     => array('need_topbar', '==', true),
          ),

        )
      ),

      // Header Menu Bar
      array(
        'name'     => 'header_menubar_tab',
        'title'    => esc_html__('Menu Bar', 'elsey'),
        'icon'     => 'fa fa-ellipsis-h',
        'fields'   => array(

          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Menu Bar Options', 'elsey'),
          ),
          array(
            'id'             => 'need_menubar',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Show Menu Bar', 'elsey'),
            'off_text'       => esc_html__('No', 'elsey'),
            'on_text'        => esc_html__('Yes', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show menu bar.', 'elsey'),
          ),
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Layout', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_menu_position',
            'type'           => 'select',
            'title'          => esc_html__('Menu Position', 'elsey'),
            'options'        => array(
              'center'       => esc_html__('Center', 'elsey'),
              'left'         => esc_html__('Left', 'elsey'),
              'right'        => esc_html__('Right', 'elsey'),
            ),
            'default'        => 'center',
            'dependency'     => array('need_menubar', '==', 'true'),
            'info'           => esc_html__('Choose your custom menu position for this page.', 'elsey'),
          ),
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Show / Hide', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_search',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Search', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show search in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_wishlist',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Wishlist', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show wishlist in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_cart',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Cart Widget', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show cart widget in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_rightmenu',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Right Menu', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show right menu in menu bar.', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
          array(
            'id'             => 'menubar_sticky',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Sticky Menu Bar', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want your menu bar on sticky.', 'elsey'),
            'dependency'     => array('need_menubar', '==', 'true'),
          ),
        )
      ),

      // Header Title Bar
      array(
        'name'     => 'header_titlebar_tab',
        'title'    => esc_html__('Title Bar', 'elsey'),
        'icon'     => 'fa fa-bullhorn',
        'fields'   => array(

          // Title Bar
          array(
            'type'             => 'notice',
            'class'            => 'info cs-vt-heading',
            'content'          => esc_html__('Title Bar Options', 'elsey')
          ),
          array(
            'id'               => 'need_titlebar',
            'type'             => 'switcher',
            'default'          => true,
            'title'            => esc_html__('Title Bar', 'elsey'),
            'label'            => esc_html__('Turn Off if you don\'t want to show title bar.', 'elsey'),
          ),
          array(
            'type'             => 'notice',
            'class'            => 'info cs-vt-heading',
            'content'          => esc_html__('Layout', 'elsey'),
            'dependency'       => array('need_titlebar', '==', 'true'),
          ),
          array(
            'id'               => 'titlebar_layout',
            'type'             => 'select',
            'title'            => esc_html__('Layout', 'elsey'),
            'options'          => array(
              'plain'          => esc_html__('Linear', 'elsey'),
              'vertical'       => esc_html__('Wider', 'elsey'),
            ),
            'default'          => 'plain',
            'info'             => esc_html__('Choose your title bar layout.', 'elsey'),
            'dependency'       => array('need_titlebar', '==', 'true'),
          ),
          array(
            'id'               => 'titlebar_spacings',
            'type'             => 'select',
            'title'            => esc_html__('Spacings', 'elsey'),
            'options'          => array(
              'els-padding-none'   => esc_html__('Default Spacing', 'elsey'),
              'els-padding-xs'     => esc_html__('Extra Small Padding', 'elsey'),
              'els-padding-sm'     => esc_html__('Small Padding', 'elsey'),
              'els-padding-md'     => esc_html__('Medium Padding', 'elsey'),
              'els-padding-lg'     => esc_html__('Large Padding', 'elsey'),
              'els-padding-xl'     => esc_html__('Extra Large Padding', 'elsey'),
              'els-padding-custom' => esc_html__('Custom Padding', 'elsey'),
            ),
            'info'             => esc_html__('Select title bar top and bottom spacings.', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),
          array(
            'id'               => 'titlebar_top_spacings',
            'type'             => 'text',
            'title'            => esc_html__('Top Spacing', 'elsey'),
            'attributes'       => array(
              'placeholder'    => '100px',
            ),
            'dependency'       => array('need_titlebar|titlebar_layout|titlebar_spacings', '==|any|==', 'true|plain,vertical|els-padding-custom'),
          ),
          array(
            'id'               => 'titlebar_bottom_spacings',
            'type'             => 'text',
            'title'            => esc_html__('Bottom Spacing', 'elsey'),
            'attributes'       => array(
              'placeholder'    => '100px',
            ),
            'dependency'       => array('need_titlebar|titlebar_layout|titlebar_spacings', '==|any|==', 'true|plain,vertical|els-padding-custom'),
          ),
          array(
            'id'               => 'titlebar_bg',
            'type'             => 'background',
            'rgba'             => true,
            'title'            => esc_html__('Background', 'elsey'),
            'info'             => esc_html__('Pick your title bar background image or color.', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),
          array(
            'id'               => 'titlebar_bg_overlay',
            'type'             => 'color_picker',
            'rgba'             => true,
            'title'            => esc_html__('Overlay Color', 'elsey'),
            'info'             => esc_html__('Pick your title bar background image overlay color.', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),
          array(
            'type'             => 'notice',
            'class'            => 'info cs-vt-heading',
            'content'          => esc_html__('Show / Hide', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),
          array(
            'id'               => 'titlebar_title_text',
            'type'             => 'switcher',
            'default'          => true,
            'title'            => esc_html__('Title Text', 'elsey'),
            'label'            => esc_html__('Turn Off if you don\'t want to show title on title bar.', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),
          array(
            'id'               => 'titlebar_breadcrumb',
            'type'             => 'switcher',
            'default'          => true,
            'title'            => esc_html__('Breadcrumbs', 'elsey'),
            'label'            => esc_html__('Turn Off if you don\'t want to show breadcrumbs on title bar.', 'elsey'),
            'dependency'       => array('need_titlebar|titlebar_layout', '==|any', 'true|plain,vertical'),
          ),

        )
      ),

    ),
  );

  // ------------------------------
  // Footer Section
  // ------------------------------
  $options[]   = array(
    'name'     => 'footer_section',
    'title'    => esc_html__('Footer', 'elsey'),
    'icon'     => 'fa fa-ellipsis-h',
    'sections' => array(

      // Footer Main Widgets
      array(
        'name'   => 'footer_widgets_tab',
        'title'  => esc_html__('Widget Area', 'elsey'),
        'icon'   => 'fa fa-th',
        'fields' => array(

          // Footer Widget Block
          array(
            'type'          => 'notice',
            'class'         => 'info cs-els-heading',
            'content'       => esc_html__('Widget Block', 'elsey')
          ),
          array(
            'id'            => 'footer_widget_block',
            'type'          => 'switcher',
            'title'         => esc_html__('Main Widget Block', 'elsey'),
            'default'       => true,
            'info'          => __('If you turn this ON, then Goto : Appearance > Widgets. There you can see <strong>Footer Widget 1,2,3 or 4</strong> Widget Area, add your widgets there.', 'elsey'),
          ),
          array(
            'id'            => 'footer_widget_layout',
            'type'          => 'image_select',
            'title'         => esc_html__('Widget Layouts', 'elsey'),
            'info'          => esc_html__('Choose your footer widget layouts.', 'elsey'),
            'default'       => 4,
            'options'       => array(
              1   => ELSEY_CS_IMAGES . '/footer/footer-1.png',
              2   => ELSEY_CS_IMAGES . '/footer/footer-2.png',
              3   => ELSEY_CS_IMAGES . '/footer/footer-3.png',
              4   => ELSEY_CS_IMAGES . '/footer/footer-4.png',
              5   => ELSEY_CS_IMAGES . '/footer/footer-5.png',
              6   => ELSEY_CS_IMAGES . '/footer/footer-6.png',
              7   => ELSEY_CS_IMAGES . '/footer/footer-7.png',
              8   => ELSEY_CS_IMAGES . '/footer/footer-8.png',
              9   => ELSEY_CS_IMAGES . '/footer/footer-9.png',
            ),
            'radio'         => true,
            'dependency'    => array('footer_widget_block', '==', true),
          ),
        )
      ),

      // footer copyright
      array(
        'name'     => 'footer_copyright_tab',
        'title'    => esc_html__('Copyright Area', 'elsey'),
        'icon'     => 'fa fa-copyright',
        'fields'   => array(

          // Copyright
          array(
            'type'          => 'notice',
            'class'         => 'info cs-els-heading',
            'content'       => esc_html__('Copyright Layout', 'elsey'),
          ),
          array(
            'id'            => 'need_copyright',
            'type'          => 'switcher',
            'default'       => true,
            'title'         => esc_html__('Enable Copyright Section', 'elsey'),
          ),
          array(
            'id'            => 'footer_copyright_layout',
            'type'          => 'image_select',
            'title'         => esc_html__('Select Copyright Layout', 'elsey'),
            'info'          => esc_html__('In above image, blue box is copyright text and yellow box is secondary text.', 'elsey'),
            'default'       => 'copyright-1',
            'options'       => array(
              'copyright-1' => ELSEY_CS_IMAGES .'/footer/copyright-layout-1.png',
              'copyright-2' => ELSEY_CS_IMAGES .'/footer/copyright-layout-2.png',
              'copyright-3' => ELSEY_CS_IMAGES .'/footer/copyright-layout-3.png',
            ),
            'radio'         => true,
            'dependency'    => array('need_copyright', '==', 'true'),
          ),
          array(
            'id'            => 'copyright_text',
            'type'          => 'textarea',
            'shortcode'     => true,
            'title'         => esc_html__('Copyright Text', 'elsey'),
            'after'         => 'Helpful shortcodes: [elsey_current_year] [elsey_home_url] or any shortcode.',
            'dependency'    => array('need_copyright', '==', 'true'),
          ),
          array(
            'type'          => 'notice',
            'class'         => 'info cs-els-heading',
            'content'       => esc_html__('Copyright Secondary Text', 'elsey'),
            'dependency'    => array('need_copyright', '==', 'true'),
          ),
          array(
            'id'            => 'secondary_text',
            'type'          => 'textarea',
            'title'         => esc_html__('Secondary Text', 'elsey'),
            'shortcode'     => true,
            'dependency'    => array('need_copyright', '==', 'true'),
          ),
        )
      ),

    ),
  );

  // ------------------------------
  // Design
  // ------------------------------
  $options[] = array(
    'name'   => 'theme_design',
    'title'  => esc_html__('Design', 'elsey'),
    'icon'   => 'fa fa-magic'
  );

  // ------------------------------
  // color section
  // ------------------------------
  $options[]   = array(
    'name'     => 'theme_color_section',
    'title'    => esc_html__('Colors', 'elsey'),
    'icon'     => 'fa fa-eyedropper',
    'fields'   => array(

      array(
        'type'       => 'heading',
        'content'    => esc_html__('Color Options', 'elsey'),
      ),
      array(
        'type'       => 'subheading',
        'wrap_class' => 'color-tab-content',
        'content'    => __('All color options are available in our theme customizer. The reason of we used customizer options for color section is because, you can choose each part of color from there and see the changes instantly using customizer.
          <br /><br />Highly customizable colors are in <strong>Appearance > Customize</strong>', 'elsey'),
      ),

    ),
  );

  // ------------------------------
  // Typography section
  // ------------------------------
  $options[] = array(
    'name'   => 'theme_typo_section',
    'title'  => esc_html__('Typography', 'elsey'),
    'icon'   => 'fa fa-header',
    'fields' => array(

      // Start fields
      array(
        'id'                  => 'typography',
        'type'                => 'group',
        'fields'              => array(
          array(
            'id'              => 'title',
            'type'            => 'text',
            'title'           => esc_html__('Title', 'elsey'),
          ),
          array(
            'id'              => 'selector',
            'type'            => 'textarea',
            'title'           => esc_html__('Selector', 'elsey'),
            'info'            => esc_html__('Enter css selectors like : <strong>body, .custom-class</strong>', 'elsey'),
          ),
          array(
            'id'              => 'font',
            'type'            => 'typography',
            'title'           => esc_html__('Font Family', 'elsey'),
          ),
          array(
            'id'              => 'size',
            'type'            => 'text',
            'title'           => esc_html__('Font Size', 'elsey'),
          ),
          array(
            'id'              => 'line_height',
            'type'            => 'text',
            'title'           => esc_html__('Line-Height', 'elsey'),
          ),
          array(
            'id'              => 'css',
            'type'            => 'textarea',
            'title'           => esc_html__('Custom CSS', 'elsey'),
          ),
        ),
        'button_title'        => esc_html__('Add New Typography', 'elsey'),
        'accordion_title'     => esc_html__('New Typography', 'elsey'),
        'default'             => array(
          array(
            'title'           => esc_html__('Body Typography', 'elsey'),
            'selector'        => 'body, .els-blog-cat-author li label, .els-sidebar .els-recent-blog-widget label, .woocommerce-Tabs-panel .shop_attributes th, .woocommerce-Tabs-panel .shop_attributes td, .els-product-summary-col .variations select, .woocommerce #review_form #respond input, .woocommerce #review_form #respond select, .woocommerce #review_form #respond textarea',
            'font'            => array(
              'family'        => 'Roboto',
              'variant'       => '400',
            ),
            'size'            => '15px',
            'line_height'     => 'normal',
          ),
          array(
            'title'           => esc_html__('Logo Typography', 'elsey'),
            'selector'        => '.els-logo a, .els-footerlogo a',
            'font'            => array(
              'family'        => 'Dosis',
              'variant'       => '600',
            ),
            'size'            => '35px',
            'line_height'     => 'normal',
          ),
          array(
            'title'           => esc_html__('Menu Typography', 'elsey'),
            'selector'        => '.els-main-menu li a, .slicknav_nav li a',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '400',
            ),
            'size'            => '15px',
          ),
          array(
            'title'           => esc_html__('Sub Menu Typography', 'elsey'),
            'selector'        => '.els-main-menu li ul li a',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '300',
            ),
            'size'            => '14px',
          ),
          array(
            'title'           => esc_html__('Primary Regular Typography', 'elsey'),
            'selector'        => '.els-dhav-dotted .els-prslr-text .els-prslr-desc, .els-product-summary-col .group_table .label a,.woocommerce ul.products .price,.els-footer,.els-recent-blog-footer h4 a,.els-recent-blog-footer h4,.els-recent-blog-footer label,.comment-main-area .els-comments-meta .comments-date,.els-cat-masonry .els-cat-masonry-text .els-cat-masonry-desc,.els-cat-default .els-catsc-text .els-catsc-desc,.els-service .els-service-content,.woocommerce-Tabs-panel .woocommerce-review__published-date,.els-blog-slider .els-blog-slider-details li,.els-blog-slider .els-blog-slider-details li a,.els-blog-slider-excerpt, .woocommerce div.product .woocommerce-product-rating, .product-type-grouped .els-product-summary-col td .els-pr-price',
            'font'            => array(
              'family'        => 'Roboto',
              'variant'       => '400',
            ),
          ),
          array(
            'title'           => esc_html__('Primary Medium Typography', 'elsey'),
            'selector'        => '.els-recent-blog .els-blog-publish .els-blog-date, .woocommerce input.button,.els-search-two input, .els-search-three input, .btn-fourth, .els-counter-two .counter-label, .els-list-icon h5, .els-testimonials-two .testi-client-info .testi-name, .els-testimonials-two .testi-client-info .testi-pro, .els-testimonials-three .testi-client-info .testi-name, .els-testimonials-three .testi-client-info .testi-pro, .els-testimonials-four .testi-client-info .testi-name, .els-testimonials-four .testi-client-info .testi-pro, .els-testimonials-five .testi-name, .els-list-icon h5, .els-comments-area .els-comments-meta .comments-reply, .footer-nav-links, .woocommerce a.button, .woocommerce button.button, .woocommerce .products li.product a.button, .woocommerce form .form-row .input-text, .woocommerce-page form .form-row .input-text, .tooltip',
            'font'            => array(
              'family'        => 'Roboto',
              'variant'       => '500',
            ),
          ),
          array(
            'title'           => esc_html__('Primary Bold Typography', 'elsey'),
            'selector'        => '.els-main-menu li.els-megamenu ul > li.els-megamenu-show-bgimg > a span, li.els-megamenu ul > li.els-megamenu-show-bgimg > a span',
            'font'            => array(
              'family'        => 'Roboto',
              'variant'       => '700',
            ),
          ),
          array(
            'title'           => esc_html__('Secondary Light Typography', 'elsey'),
            'selector'        => '.woocommerce form.track_order .form-row .input-text, .woocommerce-account form .form-row .input-text, .woocommerce-checkout .woocommerce-checkout-payment li label, .els-product-summary-col .els-product-stock-status .els-product-qty,.woocommerce-checkout .woocommerce-form-login p,.woocommerce .select2-container--default .select2-selection--single, select, textarea, input[type="text"], input[type="password"], input[type="email"], input[type="tel"], input[type="number"], input[type="url"], input[type="search"],.woocommerce-checkout .woocommerce-checkout-payment li .payment_box,.woocommerce-checkout .woocommerce-checkout-payment li .about_paypal,.track_order p,.wishlist_table .product-stock-status,.wp-link-pages a span, .woocommerce .checkout .form-row input, .woocommerce .checkout .form-row textarea',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '300',
            ),
          ),
          array(
            'title'           => esc_html__('Secondary Regular Typography', 'elsey'),
            'selector'        => '.tagcloud a, .woocommerce.els-prsc-products ul.products .price,.els-titlebar-bg .els-titlebar-breadcrumb li, .els-blog-readmore a,.els-blog-share .els-share,.wp-pagenavi a, .wp-pagenavi span,input[type="search"],.els-sidebar .els-widget li,.els-sidebar .els-widget li a, .els-sidebar .els-recent-blog-widget h4 a,.els-comment-form label, .els-product-summary-col .els-product-stock-status .els-in-stock label,.els-product-summary-col .yith-wcwl-add-to-wishlist a, .els-product-summary-col .product_meta span, .els-prsc-view-all a,.woocommerce td,.woocommerce-cart .coupon input[type="text"],.woocommerce-checkout .woocommerce-info,.woocommerce-checkout .woocommerce-form-login p label,.woocommerce-checkout .woocommerce-form-login .lost_password, .woocommerce-checkout .woocommerce-billing-fields label, .woocommerce-checkout .woocommerce-additional-fields label, .woocommerce-checkout .woocommerce-shipping-fields__field-wrapper label,.woocommerce .woocommerce-checkout-review-order-table td strong,.woocommerce .woocommerce-checkout-review-order-table .cart-subtotal th,.woocommerce .woocommerce-checkout-review-order-table .cart-subtotal td, .woocommerce .woocommerce-checkout-review-order-table .shipping th, .woocommerce-checkout .create-account label.checkbox label,.woocommerce-checkout .create-account label, .track_order p label,.woocommerce-account #customer_login label,.woocommerce-account #customer_login p.lost_password a, .els-pr-list-products .els-pr-list-name a,.els-pr-list-products .price,.els-sidebar .els-filter-column .price_slider_amount .price_label, .els-product-summary-col .variations .label label,.els-icon li .widget_shopping_cart_content a,.els-icon li .widget_shopping_cart_content .quantity',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '400',
            ),
          ),
          array(
            'title'           => esc_html__('Secondary Medium Typography', 'elsey'),
            'selector'        => '.els-dhav-dotted .els-prslr-text .els-prslr-offer, .woocommerce-Tabs-panel, .woocommerce-Tabs-panel td, .woocommerce-Tabs-panel .shop_attributes th, .woocommerce .woocommerce-Tabs-panel .shop_attributes td, .woocommerce .return-to-shop a, .woocommerce-wishlist .shop_table td.product-name a, .woocommerce-wishlist .shop_table td .els-pr-price, .els-sidebar .els-widget.els-recent-blog-widget .widget-title, .wc-tabs-wrapper .wc-tabs li a, .woocommerce .els-products-full-wrap.els-shop-masonry ul.products .els-product-title h3 a, .woocommerce ul.products .els-product-title h3 a, .woocommerce-checkout .woocommerce-form-login .form-row .input-text, .els-blog-masonry .els-blog-intro .els-blog-heading,.els-product-summary-col .els-product-stock-status .els-product-qty label,.els-blog-single-pagination a,.slicknav_nav li a.els-title-menu,.els-main-menu li a.els-title-menu,.els-copyright-bar,.els-tag-list a,.els-prev-next-pagination a,.els-commentbox h3.comment-reply-title,.els-commentbox h3.comments-title,.comment-main-area .els-comments-meta h4,.els-commentbox a.comment-reply-link,.els-service .els-service-heading h3,.els-service .els-service-heading h3 a,.els-service .els-service-read-more,.els-testi-name,.els-product-summary-col .product_title,.els-product-summary-col .els-product-stock-status .els-in-stock span,.woocommerce-page.single .quantity .qty,.woocommerce-Tabs-panel .comment_container .meta strong,.els-blog-readmore a,.return-to-shop a,.woocommerce .shop_table th, .wishlist_table th,.shop_table input.qty[type="number"],.woocommerce-cart .cart-collaterals .cart_totals .order-total td strong,.woocommerce-cart .cart-collaterals .cart_totals table .shipping .woocommerce-shipping-calculator p a,.woocommerce table.shop_table_responsive tr td::before, .woocommerce-page table.shop_table_responsive tr td::before,.woocommerce-checkout .checkout_coupon .form-row input[type="submit"],.woocommerce-checkout .checkout_coupon .form-row-first input,.woocommerce-MyAccount-content .woocommerce-EditAccountForm label,.wishlist_table .button,.woocommerce-ResetPassword label,.els-sidebar .els-filter-column .price_slider_amount button,.els-shop-filter .els-result-count,.els-order-filter .woocommerce-ordering select,.els-sidebar .widget_shopping_cart_content .mini_cart_item .quantity,.els-sidebar .widget_products li .amount,.els-sidebar .widget_recent_reviews .reviewer,.els-sidebar .widget_top_rated_products li .amount,.els-plxsec .els-plxsec-title-one,.els-icon li .widget_shopping_cart_content .woocommerce-mini-cart__total,.els-icon li .widget_shopping_cart_content .woocommerce-mini-cart__total strong,blockquote,blockquote cite,.els-icon li .widget_shopping_cart_content .woocommerce-mini-cart__buttons .button',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '500',
            ),
          ),
          array(
            'title'           => esc_html__('Secondary Semi Bold Typography', 'elsey'),
            'selector'        => '.els-dhav-dotted .els-prslr-text .els-prslr-title, .els-dhav-dotted .els-prslr-text .els-prslr-viewNow-title a, .els-dhav-dotted .els-prslr-text .els-prslr-shopNow-title a, .els-dhav-dotted .els-prslr-text .els-prslr-subtitle,.woocommerce-checkout .shop_table .order-total .woocommerce-Price-amount, .els-sidebar .els-widget .widget-title, .woocommerce .product-add-to-cart a.button, .woocommerce-account #customer_login input[type="submit"], .woocommerce-checkout input.button, .woocommerce .wc-proceed-to-checkout .checkout-button, .woocommerce-cart input[type="submit"],.woocommerce-cart input[disabled].els-update-cart, .woocommerce-cart .coupon input[type="submit"], .woocommerce #review_form #respond .form-submit input,.woocommerce .els-shop-fullgrid ul.products .els-product-atc a,.woocommerce .els-shop-fullgrid ul.products .price,h1,h2,h3,h4,h5,h6,.els-topbar-left,.els-topbar .els-topbar-right li,.els-topbar .els-topbar-right li a,.slicknav_btn,.els-btn,button,input[type="submit"],.els-team-member-details,.els-team-member-job,.els-product-summary-col .price,.wc-tabs-wrapper .wc-tabs li.active a,.woocommerce-Tabs-panel h2,#review_form_wrapper .comment-reply-title,.woocommerce ul.products .els-product-atc a,.cart-empty,.woocommerce .shop_table .order-total th,.wc-proceed-to-checkout .checkout-button,#ship-to-different-address label,.woocommerce-account .woocommerce-MyAccount-navigation li a,.woocommerce-MyAccount-content .woocommerce-EditAccountForm legend,.related.products h2,.els-pr-single .els-pr-single-price,.els-pr-single .els-pr-single-atc a,.woocommerce .els-products-full-wrap.els-shop-masonry ul.products .price,.els-sidebar .widget_shopping_cart_content .total strong,.els-sidebar .widget_shopping_cart_content .button,.els-product-summary-col .els-pr-price,.els-product-summary-col .variations .reset_variations,.els-product-summary-col .cart .button,.els-sidebar .calendar_wrap th,.els-sidebar .calendar_wrap caption,.els-sidebar strong,.els-plxsec .els-plxsec-title-three,.modal-dialog .searchform input[type="search"],.product-template-default .woocommerce-message .wc-forward,.single .els-content-col .els-blog-content table th,.comment .comment-area th,.comment .comment-area dl dt,.single .els-content-col dl dt',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '600',
            ),
          ),
          array(
            'title'           => esc_html__('Secondary Bold Typography', 'elsey'),
            'selector'        => '.els-plxsec .els-plxsec-btn .els-btn, .els-subs-one h5,.form-title,.vc_custom_heading, .els-contact-info span a,.els-cat-masonry .els-cat-masonry-name, .els-cat-default .els-catsc-text .els-catsc-name,.els-titlebar-vertical h1, .els-team .els-team-title,.els-team .els-team-sub-title, .els-testi-title,.els-team-member-name,body #yith-wcwl-popup-message #yith-wcwl-message,.els-product-onsale,.els-product-sold, .woocommerce-checkout .place-order input[type="submit"],.track_order input[type="submit"], .woocommerce-order-received .woocommerce-order .woocommerce-thankyou-order-details li strong, .woocommerce-message,.els-pr-single h3, .els-pr-single h3 a, .els-subs-two .els-single-title-one,.els-error-content h1, .els-plxsec .els-plxsec-title-two,.els-icon li .els-cart-count,strong, .wp-link-pages span',
            'font'            => array(
              'family'        => 'Hind',
              'variant'       => '700',
            ),
          ),
          array(
            'title'           => esc_html__('Example Usage', 'elsey'),
            'selector'        => '.your-custom-class',
            'font'            => array(
              'family'        => 'Roboto Slab',
              'variant'       => 'regular',
            ),
          ),
        ),
      ),

      // Subset
      array(
        'id'                  => 'subsets',
        'type'                => 'select',
        'title'               => esc_html__('Subsets', 'elsey'),
        'class'               => 'chosen',
        'options'             => array(
          'latin'             => 'latin',
          'latin-ext'         => 'latin-ext',
          'cyrillic'          => 'cyrillic',
          'cyrillic-ext'      => 'cyrillic-ext',
          'greek'             => 'greek',
          'greek-ext'         => 'greek-ext',
          'vietnamese'        => 'vietnamese',
          'devanagari'        => 'devanagari',
          'khmer'             => 'khmer',
        ),
        'attributes'          => array(
          'data-placeholder'  => 'Subsets',
          'multiple'          => 'multiple',
          'style'             => 'width: 200px;'
        ),
        'default'             => array( 'latin' ),
      ),

      array(
        'id'                  => 'font_weight',
        'type'                => 'select',
        'title'               => esc_html__('Font Weights', 'elsey'),
        'class'               => 'chosen',
        'options'             => array(
          '100'   => 'Thin 100',
          '100i'  => 'Thin 100 Italic',
          '200'   => 'Extra Light 200',
          '200i'  => 'Extra Light 200 Italic',
          '300'   => 'Light 300',
          '300i'  => 'Light 300 Italic',
          '400'   => 'Regular 400',
          '400i'  => 'Regular 400 Italic',
          '500'   => 'Medium 500',
          '500i'  => 'Medium 500 Italic',
          '600'   => 'Semi Bold 600',
          '600i'  => 'Semi Bold 600 Italic',
          '700'   => 'Bold 700',
          '700i'  => 'Bold 700 Italic',
          '800'   => 'Extra Bold 800',
          '800i'  => 'Extra Bold 800 Italic',
          '900'   => 'Black 900',
          '900i'  => 'Black 900 Italic',
        ),
        'attributes'         => array(
          'data-placeholder' => 'Font Weight',
          'multiple'         => 'multiple',
          'style'            => 'width: 200px;'
        ),
        'default'            => array( '400' ),
      ),

      // Custom Fonts Upload
      array(
        'id'                 => 'font_family',
        'type'               => 'group',
        'title'              => 'Upload Custom Fonts',
        'button_title'       => 'Add New Custom Font',
        'accordion_title'    => 'Adding New Font',
        'accordion'          => true,
        'desc'               => 'It is plain. Only add your custom fonts and click to save. After you can check "Font Family" selector. Do not forget to Save!',
        'fields'             => array(

          array(
            'id'             => 'name',
            'type'           => 'text',
            'title'          => 'Font-Family Name',
            'attributes'     => array(
              'placeholder'  => 'for eg. Arial'
            ),
          ),

          array(
            'id'             => 'ttf',
            'type'           => 'upload',
            'title'          => 'Upload .ttf <small><i>(optional)</i></small>',
            'settings'       => array(
              'upload_type'  => 'font',
              'insert_title' => 'Use this Font-Format',
              'button_title' => 'Upload <i>.ttf</i>',
            ),
          ),

          array(
            'id'             => 'eot',
            'type'           => 'upload',
            'title'          => 'Upload .eot <small><i>(optional)</i></small>',
            'settings'       => array(
              'upload_type'  => 'font',
              'insert_title' => 'Use this Font-Format',
              'button_title' => 'Upload <i>.eot</i>',
            ),
          ),

          array(
            'id'             => 'svg',
            'type'           => 'upload',
            'title'          => 'Upload .svg <small><i>(optional)</i></small>',
            'settings'       => array(
              'upload_type'  => 'font',
              'insert_title' => 'Use this Font-Format',
              'button_title' => 'Upload <i>.svg</i>',
            ),
          ),

          array(
            'id'             => 'otf',
            'type'           => 'upload',
            'title'          => 'Upload .otf <small><i>(optional)</i></small>',
            'settings'       => array(
              'upload_type'  => 'font',
              'insert_title' => 'Use this Font-Format',
              'button_title' => 'Upload <i>.otf</i>',
            ),
          ),

          array(
            'id'             => 'woff',
            'type'           => 'upload',
            'title'          => 'Upload .woff <small><i>(optional)</i></small>',
            'settings'       => array(
              'upload_type'  => 'font',
              'insert_title' => 'Use this Font-Format',
              'button_title' => 'Upload <i>.woff</i>',
            ),
          ),

          array(
            'id'             => 'css',
            'type'           => 'textarea',
            'title'          => 'Extra CSS Style <small><i>(optional)</i></small>',
            'attributes'     => array(
              'placeholder'  => 'for eg. font-weight: normal;'
            ),
          ),

        ),
      ),
      // End All field

    ),
  );


  // ------------------------------
  // Pages
  // ------------------------------
  $options[] = array(
    'name'   => 'theme_pages',
    'title'  => esc_html__('Pages', 'elsey'),
    'icon'   => 'fa fa-files-o'
  );

  // ------------------------------
  // Team Section
  // ------------------------------
  $options[]   = array(
    'name'     => 'team_section',
    'title'    => esc_html__('Team', 'elsey'),
    'icon'     => 'fa fa-users',
    'fields' => array(

      // Team Start
      array(
        'type'    => 'notice',
        'class'   => 'info cs-els-heading',
        'content' => esc_html__('Team Single', 'elsey')
      ),
      array(
        'id'               => 'team_page_layout',
        'type'             => 'image_select',
        'title'            => esc_html__('Page Layout', 'elsey'),
        'options'          => array(
          'less-width'     => ELSEY_CS_IMAGES . '/page-layout-2.png',
          'full-width'     => ELSEY_CS_IMAGES . '/page-layout-1.png',
        ),
        'attributes'       => array(
          'data-depend-id' => 'team_page_layout',
        ),
        'radio'            => true,
        'default'          => 'less-width',
      ),
      array(
        'id'               => 'team_spacings',
        'type'             => 'select',
        'title'            => esc_html__('Spacings', 'elsey'),
        'options'          => array(
          'els-padding-none'   => esc_html__('Default Spacing', 'elsey'),
          'els-padding-zero'   => esc_html__('No Padding', 'elsey'),
          'els-padding-xs'     => esc_html__('Extra Small Padding', 'elsey'),
          'els-padding-sm'     => esc_html__('Small Padding', 'elsey'),
          'els-padding-md'     => esc_html__('Medium Padding', 'elsey'),
          'els-padding-lg'     => esc_html__('Large Padding', 'elsey'),
          'els-padding-xl'     => esc_html__('Extra Large Padding', 'elsey'),
          'els-padding-custom' => esc_html__('Custom Padding', 'elsey'),
        ),
        'label'            => esc_html__('Title single page top and bottom spacings.', 'elsey'),
      ),
      array(
        'id'               => 'team_top_spacing',
        'type'             => 'text',
        'title'            => esc_html__('Top Spacing', 'elsey'),
        'info'             => esc_html__('Enter value in px, for team single pages top value.', 'elsey'),
        'attributes'       => array(
          'placeholder'    => '100px',
        ),
        'dependency'       => array('team_spacings', '==|==', 'els-padding-custom'),
      ),
      array(
        'id'               => 'team_bottom_spacing',
        'type'             => 'text',
        'title'            => esc_html__('Bottom Spacing', 'elsey'),
        'info'             => esc_html__('Enter value in px, for team single pages bottom value.', 'elsey'),
        'attributes'       => array(
          'placeholder'    => '100px',
        ),
        'dependency'       => array('team_spacings', '==|==', 'els-padding-custom'),
      ),
      // Team End

    ),
  );

  // ------------------------------
  // Blog Section
  // ------------------------------
  $options[]   = array(
    'name'     => 'blog_section',
    'title'    => esc_html__('Blog', 'elsey'),
    'icon'     => 'fa fa-edit',
    'sections' => array(

      // blog general section
      array(
        'name'   => 'blog_general_tab',
        'title'  => esc_html__('General', 'elsey'),
        'icon'   => 'fa fa-cog',
        'fields' => array(

          // Layout
          array(
            'type'            => 'notice',
            'class'           => 'info cs-els-heading',
            'content'         => esc_html__('Layout', 'elsey')
          ),
          array(
            'id'              => 'blog_page_layout',
            'type'            => 'image_select',
            'title'           => esc_html__('Page Layout', 'elsey'),
            'options'         => array(
              'less-width'    => ELSEY_CS_IMAGES . '/page-layout-2.png',
              'full-width'    => ELSEY_CS_IMAGES . '/page-layout-1.png',
            ),
            'attributes'      => array(
              'data-depend-id' => 'blog_page_layout',
            ),
            'radio'           => true,
            'default'         => 'less-width',
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author. If this settings will not apply your blog page, please set that page as a post page in Settings > Readings.', 'elsey'),
          ),
          array(
            'id'              => 'blog_listing_style',
            'type'            => 'select',
            'title'           => esc_html__('Blog Style', 'elsey'),
            'options'         => array(
              'els-blog-standard' => esc_html__('Standard', 'elsey'),
              'els-blog-masonry'  => esc_html__('Masonry', 'elsey'),
            ),
            'default_option'  => 'Select blog style',
            'info'            => esc_html__('Default option : Standard', 'elsey'),
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author. If this settings will not apply your blog page, please set that page as a post page in Settings > Readings.', 'elsey'),
          ),
          array(
            'id'              => 'blog_listing_columns',
            'type'            => 'select',
            'title'           => esc_html__('Blog Columns', 'elsey'),
            'options'         => array(
              'els-blog-col-1' => esc_html__('Column One', 'elsey'),
              'els-blog-col-2' => esc_html__('Column Two', 'elsey'),
              'els-blog-col-3' => esc_html__('Column Three', 'elsey')
            ),
            'default_option'  => 'Select blog column',
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author.', 'elsey'),
            'info'            => esc_html__('Default option : Column One', 'elsey'),
          ),
          array(
            'id'              => 'blog_sidebar_position',
            'type'            => 'select',
            'title'           => esc_html__('Sidebar Position', 'elsey'),
            'options'         => array(
              'sidebar-right' => esc_html__('Right', 'elsey'),
              'sidebar-left'  => esc_html__('Left', 'elsey'),
              'sidebar-hide'  => esc_html__('Hide', 'elsey'),
            ),
            'default_option'  => 'Select sidebar position',
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author.', 'elsey'),
            'info'            => esc_html__('Default option : Right', 'elsey'),
          ),
          array(
            'id'              => 'blog_sidebar_widget',
            'type'            => 'select',
            'title'           => esc_html__('Sidebar Widget', 'elsey'),
            'options'         => elsey_vt_registered_sidebars(),
            'default_option'  => esc_html__('Select Widget', 'elsey'),
            'dependency'      => array('blog_sidebar_position', '!=', 'sidebar-hide'),
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author.', 'elsey'),
            'info'            => esc_html__('Default option : Main Widget', 'elsey'),
          ),
          array(
            'id'              => 'blog_pagination_style',
            'type'            => 'select',
            'title'           => esc_html__('Pagination Style', 'elsey'),
            'options'         => array(
              'pagination_number'  => esc_html__('Page Numbers', 'elsey'),
              'pagination_nextprv' => esc_html__('Next/Previous', 'elsey'),
              'pagination_btn'     => esc_html__('Load More', 'elsey'),
            ),
            'default_option'  => 'Select pagination style',
            'help'            => esc_html__('This style will apply, default blog pages - Like : Archive, Category, Tags, Search & Author.', 'elsey'),
            'info'            => esc_html__('Default option : Page Numbers', 'elsey'),
          ),
          // Layout

          // Enable / Disable
          array(
            'type'            => 'notice',
            'class'           => 'info cs-els-heading',
            'content'         => esc_html__('Enable / Disable', 'elsey')
          ),
          array(
            'id'              => 'blog_excerpt_option',
            'type'            => 'switcher',
            'default'         => true,
            'title'           => esc_html__('Excerpt', 'elsey'),
            'info'            => esc_html__('If need to hide excerpt on blog page, please turn this OFF.', 'elsey'),
          ),
          array(
            'id'              => 'blog_popup_option',
            'type'            => 'switcher',
            'default'         => true,
            'title'           => esc_html__('Popup Option', 'elsey'),
            'info'            => esc_html__('If need to disable image popup on blog page, please turn this OFF.', 'elsey'),
          ),
          array(
            'id'              => 'blog_read_more_option',
            'type'            => 'switcher',
            'default'         => true,
            'title'           => esc_html__('Read More', 'elsey'),
            'info'            => esc_html__('If need to hide read more option on blog page, please turn this OFF.', 'elsey'),
          ),
          array(
            'id'              => 'blog_share_option',
            'type'            => 'switcher',
            'default'         => true,
            'title'           => esc_html__('Share Option', 'elsey'),
            'info'            => esc_html__('If need to hide share option on blog page, please turn this OFF.', 'elsey'),
          ),
          array(
            'id'              => 'blog_metas_hide',
            'type'            => 'checkbox',
            'title'           => esc_html__('Meta\'s to hide', 'elsey'),
            'info'            => esc_html__('Check items you want to hide from blog meta field.', 'elsey'),
            'class'           => 'horizontal',
            'options'         => array(
              'author'        => esc_html__('Author', 'elsey'),
              'category'      => esc_html__('Category', 'elsey'),
              'date'          => esc_html__('Date', 'elsey'),
            ),
          ),
          // Enable / Disable

          // Global Options
          array(
            'type'            => 'notice',
            'class'           => 'info cs-els-heading',
            'content'         => esc_html__('Global Options', 'elsey')
          ),
          array(
            'id'              => 'blog_exclude_categories',
            'type'            => 'checkbox',
            'title'           => esc_html__('Exclude Categories', 'elsey'),
            'info'            => esc_html__('Select categories you want to exclude from blog page.', 'elsey'),
            'options'         => 'categories',
          ),
          array(
            'id'              => 'blog_excerpt_length',
            'type'            => 'text',
            'title'           => esc_html__('Excerpt Length', 'elsey'),
            'info'            => esc_html__('Blog short content length, in blog listing pages.', 'elsey'),
            'default'         => '55',
          ),
          // End fields

        )
      ),

      // blog single section
      array(
        'name'     => 'blog_single_tab',
        'title'    => esc_html__('Single', 'elsey'),
        'icon'     => 'fa fa-sticky-note',
        'fields'   => array(

          // Start fields
          array(
            'type'    => 'notice',
            'class'   => 'info cs-els-heading',
            'content' => esc_html__('Layout', 'elsey')
          ),
          array(
            'id'              => 'single_page_layout',
            'type'            => 'image_select',
            'title'           => esc_html__('Page Layout', 'elsey'),
            'options'         => array(
              'less-width'    => ELSEY_CS_IMAGES . '/page-layout-2.png',
              'full-width'    => ELSEY_CS_IMAGES . '/page-layout-1.png',
            ),
            'attributes'      => array(
              'data-depend-id' => 'single_page_layout',
            ),
            'radio'           => true,
            'default'         => 'less-width',
          ),
          array(
            'id'              => 'single_sidebar_position',
            'type'            => 'select',
            'title'           => esc_html__('Sidebar Position', 'elsey'),
            'options'         => array(
              'sidebar-right' => esc_html__('Right', 'elsey'),
              'sidebar-left'  => esc_html__('Left', 'elsey'),
              'sidebar-hide'  => esc_html__('Hide', 'elsey'),
            ),
            'default_option'  => 'Select sidebar position',
            'info'            => esc_html__('Default option : Right', 'elsey'),
          ),
          array(
            'id'              => 'single_sidebar_widget',
            'type'            => 'select',
            'title'           => esc_html__('Sidebar Widget', 'elsey'),
            'options'         => elsey_vt_registered_sidebars(),
            'default_option'  => esc_html__('Select Widget', 'elsey'),
            'dependency'      => array('single_sidebar_position', '!=', 'sidebar-hide'),
            'info'            => esc_html__('Default option : Main Widget Area', 'elsey'),
          ),
          // End fields

          // Start fields
          array(
            'type'            => 'notice',
            'class'           => 'info cs-els-heading',
            'content'         => esc_html__('Enable / Disable', 'elsey')
          ),
          array(
            'id'              => 'single_featured_image',
            'type'            => 'switcher',
            'title'           => esc_html__('Featured Image/Gallery/Audio/Video', 'elsey'),
            'info'            => esc_html__('If need to hide featured image from single blog page, please turn this OFF.', 'elsey'),
            'default'         => true,
          ),
          array(
            'id'              => 'single_popup_option',
            'type'            => 'switcher',
            'title'           => esc_html__('Popup Option', 'elsey'),
            'info'            => esc_html__('If need to disable image popup on single blog page, please turn this OFF.', 'elsey'),
            'default'         => true,
            'dependency'      => array('single_featured_image', '==', 'true'),
          ),
          array(
            'id'              => 'single_share_option',
            'type'            => 'switcher',
            'title'           => esc_html__('Share Option', 'elsey'),
            'info'            => esc_html__('If need to hide share option on single blog page, please turn this OFF.', 'elsey'),
            'default'         => true,
          ),
          array(
            'id'              => 'single_comment_form',
            'type'            => 'switcher',
            'title'           => esc_html__('Comment Area/Form', 'elsey'),
            'info'            => esc_html__('If need to hide comment area and that form on single blog page, please turn this OFF.', 'elsey'),
            'default'         => true,
          ),
          array(
            'id'              => 'single_metas_hide',
            'type'            => 'checkbox',
            'title'           => esc_html__('Meta\'s to hide', 'elsey'),
            'info'            => esc_html__('Check items you want to hide from single blog page meta field.', 'elsey'),
            'class'           => 'horizontal',
            'options'         => array(
              'author'        => esc_html__('Author', 'elsey'),
              'category'      => esc_html__('Category', 'elsey'),
              'date'          => esc_html__('Date', 'elsey'),
              'tag'           => esc_html__('Tags', 'elsey'),
            ),
          )

        )
      ),

    ),
  );

  if (class_exists( 'WooCommerce' )) {

    // ------------------------------
    // WooCommerce Section
    // ------------------------------
    $options[]   = array(
      'name'     => 'woocommerce_section',
      'icon'     => 'fa fa-shopping-cart',
      'title'    => esc_html__('WooCommerce', 'elsey'),
      'sections' => array(

        //Shop Related Options
        array(
          'name'   => 'shop_tab',
          'icon'   => 'fa fa-shopping-cart',
          'title'  => esc_html__('Shop', 'elsey'),
          'fields' => array(

            // Start Fields
            // Layout
            array(
              'type'             => 'notice',
              'class'            => 'info cs-els-heading',
              'content'          => esc_html__('Layout', 'elsey')
            ),
            array(
              'id'               => 'woo_page_layout',
              'type'             => 'image_select',
              'radio'            => true,
              'options'          => array(
                'less-width'     => ELSEY_CS_IMAGES . '/page-layout-2.png',
                'full-width'     => ELSEY_CS_IMAGES . '/page-layout-1.png',
              ),
              'default'          => 'less-width',
              'attributes'       => array(
                'data-depend-id' => 'woo_page_layout',
              ),
              'title'            => esc_html__('Page Layout', 'elsey'),
              'help'             => esc_html__('This style will apply, default woocommerce listings pages. Like, shop and archive pages.', 'elsey'),
            ),
            array(
              'id'               => 'woo_product_columns',
              'type'             => 'select',
              'title'            => esc_html__('Product Column', 'elsey'),
              'options'          => array(
                3                => esc_html__('Three Column', 'elsey'),
                4                => esc_html__('Four Column', 'elsey'),
                5                => esc_html__('Five Column', 'elsey'),
                6                => esc_html__('Six Column', 'elsey'),
              ),
              'default_option'   => esc_html__('Select product columns', 'elsey'),
              'info'             => esc_html__('Default option : Four Column', 'elsey'),
              'help'             => esc_html__('This style will apply, default woocommerce listings pages. Like, shop and archive pages.', 'elsey'),
            ),
            array(
              'id'               => 'woo_sidebar_position',
              'type'             => 'select',
              'title'            => esc_html__('Sidebar Position', 'elsey'),
              'options'          => array(
                'sidebar-right'  => esc_html__('Right', 'elsey'),
                'sidebar-left'   => esc_html__('Left', 'elsey'),
                'sidebar-hide'   => esc_html__('Hide', 'elsey'),
              ),
              'default_option'   => esc_html__('Select sidebar position', 'elsey'),
              'info'             => esc_html__('Default option : Right', 'elsey'),
              'help'             => esc_html__('This style will apply, default woocommerce listings pages. Like, shop and archive pages.', 'elsey'),
            ),
            array(
              'id'               => 'woo_widget',
              'type'             => 'select',
              'options'          => elsey_vt_registered_sidebars(),
              'title'            => esc_html__('Sidebar Widget', 'elsey'),
              'default_option'   => esc_html__('Select widget', 'elsey'),
              'info'             => esc_html__('Default option : Shop Widget', 'elsey'),
              'help'             => esc_html__('This style will apply, default woocommerce listings pages. Like, shop and archive pages.', 'elsey'),
              'dependency'       => array('woo_sidebar_position', '!=', 'sidebar-hide'),
            ),
            array(
              'id'               => 'woo_load_style',
              'type'             => 'select',
              'title'            => esc_html__(' Style', 'elsey'),
              'options'          => array(
                'page_number'    => esc_html__('Page Number', 'elsey'),
                'prev_next'      => esc_html__('Next/Previous', 'elsey'),
                'page_btn'       => esc_html__('Load More', 'elsey'),
              ),
              'default_option'   => 'Select pagination style',
              'info'             => esc_html__('Default option : Page Number', 'elsey'),
              'help'             => esc_html__('This style will apply, default woocommerce listings pages. Like, shop and archive pages.', 'elsey'),
            ),
            // Listing
            array(
              'type'             => 'notice',
              'class'            => 'info cs-els-heading',
              'content'          => esc_html__('Listing', 'elsey')
            ),
            array(
              'id'               => 'theme_woo_limit',
              'type'             => 'text',
              'title'            => esc_html__('Product Limit', 'elsey'),
              'info'             => esc_html__('Enter the number value for per page products limit.', 'elsey'),
            ),
            // Enable / Disable
            array(
              'type'             => 'notice',
              'class'            => 'info cs-els-heading',
              'content'          => esc_html__('Enable / Disable', 'elsey')
            ),
            array(
              'id'               => 'woo_lazy_load',
              'type'             => 'select',
              'title'            => esc_html__('Image Lazy Load', 'elsey'),
              'options'          => array(
                'els-dload-none'  => esc_html__('Select Disable Type', 'elsey'),
                'els-dload-full'  => esc_html__('Disable On Full Site', 'elsey'),
                'els-dload-small' => esc_html__('Disable On Small Screen', 'elsey'),
              ),
              'help'             => esc_html__('Select product image lazy load option.', 'elsey'),
            ),
            array(
              'id'               => 'woo_dload_size',
              'type'             => 'text',
              'title'            => esc_html__( 'Lazy Load Starts Form?', 'elsey' ),
              'dependency'       => array('woo_lazy_load', '==', 'els-dload-small'),
              'info'             => esc_html__('Just put numeric value only. Don\'t use px or any other units. Default option : 767.', 'elsey'),
            ),
            array(
              'id'               => 'woo_hover_image',
              'type'             => 'switcher',
              'default'          => true,
              'title'            => esc_html__('Image Change Hover Effect', 'elsey'),
              'info'             => esc_html__('Turn Off if you don\'t want \'image change animation on hover\' on each product.', 'elsey'),
            ),
            array(
              'id'               => 'woo_sort_filter',
              'type'             => 'switcher',
              'default'          => true,
              'title'            => esc_html__('Sorting Dropdown', 'elsey'),
              'info'             => esc_html__('Turn Off if you don\'t want to show sorting dropdown filter.', 'elsey'),
            ),
            array(
              'id'               => 'woo_result_count',
              'type'             => 'switcher',
              'default'          => true,
              'title'            => esc_html__('Product Count', 'elsey'),
              'info'             => esc_html__('Turn Off if you don\'t want to show product result counting.', 'elsey'),
            ),
            // End Fields
          ),
        ),

        //Shop Single Product Options
        array(
          'name'   => 'shop_single_product_tab',
          'title'  => esc_html__('Single Product', 'elsey'),
          'icon'   => 'fa fa-shopping-cart',
          'fields' => array(

            // Start Fields
            array(
              'type'           => 'notice',
              'class'          => 'info cs-els-heading',
              'content'        => esc_html__('Enable / Disable', 'elsey')
            ),
            array(
              'id'             => 'woo_single_nav',
              'type'           => 'switcher',
              'title'          => esc_html__('Product Navigation', 'elsey'),
              'info'           => esc_html__('If you don\'t want \'Product Navigation\' in single product page, please turn this OFF.', 'elsey'),
              'default'        => true,
            ),
            array(
              'id'             => 'woo_single_modal',
              'type'           => 'switcher',
              'default'        => true,
              'title'          => esc_html__('Image Modal Gallery', 'elsey'),
              'info'           => esc_html__('If you don\'t want modal gallery for full-size product images in single product page, please turn this OFF.', 'elsey'),
            ),
            array(
              'id'             => 'woo_single_share',
              'type'           => 'switcher',
              'default'        => true,
              'title'          => esc_html__('Social Share Buttons', 'elsey'),
              'info'           => esc_html__('If you don\'t want \'Social Share Buttons\' in single product page, please turn this OFF.', 'elsey'),
            ),
            array(
              'id'             => 'woo_single_share_hide',
              'type'           => 'checkbox',
              'title'          => esc_html__('Share Buttons to Hide', 'elsey'),
              'info'           => esc_html__('Check buttons you want to hide from single product social share.', 'elsey'),
              'class'          => 'horizontal',
              'options'        => array(
                'facebook'     => esc_html__('Facebook', 'elsey'),
                'twitter'      => esc_html__('Twitter', 'elsey'),
                'googleplus'   => esc_html__('Google+', 'elsey'),
                'pinterest'    => esc_html__('Pinterest', 'elsey'),
                'linkedin'     => esc_html__('LinkedIn', 'elsey'),
              ),
              'dependency'     => array('woo_single_share', '==', 'true'),
            ),
            // Up-Sells Products
            array(
              'type'           => 'notice',
              'class'          => 'info cs-els-heading',
              'content'        => esc_html__('Up-Sells Products', 'elsey')
            ),
            array(
              'id'             => 'woo_single_upsell',
              'type'           => 'switcher',
              'default'        => false,
              'title'          => esc_html__('Up-Sells Products', 'elsey'),
              'info'           => esc_html__('If you don\'t want \'You May Also Like\' products in single product page, please turn this OFF.', 'elsey'),
            ),
            array(
              'id'             => 'woo_upsell_limit',
              'type'           => 'text',
              'title'          => esc_html__('Up-Sells Products Limit', 'elsey'),
              'dependency'     => array('woo_single_upsell', '==', 'true'),
            ),
            array(
              'id'             => 'woo_upsell_columns',
              'type'           => 'select',
              'title'          => esc_html__('Up-Sells Products Column', 'elsey'),
              'options'        => array(
                3              => esc_html__('Three Column', 'elsey'),
                4              => esc_html__('Four Column', 'elsey'),
                5              => esc_html__('Five Column', 'elsey'),
                6              => esc_html__('Six Column', 'elsey'),
              ),
              'info'           => esc_html__('Default option : Four Column', 'elsey'),
              'default_option' => esc_html__('Select product columns', 'elsey'),
              'dependency'     => array('woo_single_upsell', '==', 'true'),
            ),
            // Related Products
            array(
              'type'           => 'notice',
              'class'          => 'info cs-els-heading',
              'content'        => esc_html__('Related Products', 'elsey')
            ),
            array(
              'id'             => 'woo_single_related',
              'type'           => 'switcher',
              'default'        => true,
              'title'          => esc_html__('Related Products', 'elsey'),
              'info'           => esc_html__('If you don\'t want \'Related Products\' in single product page, please turn this OFF.', 'elsey'),
            ),
            array(
              'id'             => 'woo_related_limit',
              'type'           => 'text',
              'title'          => esc_html__('Related Products Limit', 'elsey'),
              'dependency'     => array('woo_single_related', '==', 'true'),
            ),
            array(
              'id'             => 'woo_related_load_style',
              'type'           => 'select',
              'title'          => esc_html__('Related Products Style', 'elsey'),
              'options'        => array(
                'default'      => esc_html__('Default', 'elsey'),
                'slider'       => esc_html__('Slider', 'elsey'),
              ),
              'info'           => esc_html__('Default option : Default', 'elsey'),
              'default_option' => 'Select product style',
              'dependency'     => array('woo_single_related', '==', 'true'),
            ),
            array(
              'id'             => 'woo_related_columns',
              'type'           => 'select',
              'title'          => esc_html__('Related Products Column', 'elsey'),
              'options'        => array(
                3              => esc_html__('Three Column', 'elsey'),
                4              => esc_html__('Four Column', 'elsey'),
                5              => esc_html__('Five Column', 'elsey'),
                6              => esc_html__('Six Column', 'elsey'),
              ),
              'info'           => esc_html__('Default option : Four Column', 'elsey'),
              'default_option' => esc_html__('Select product columns', 'elsey'),
              'dependency'     => array('woo_single_related', '==', 'true'),
            ),
            array(
              'id'             => 'woo_related_sl_loop',
              'type'           => 'switcher',
              'default'        => false,
              'title'          => esc_html__('Slider Loop', 'elsey'),
              'info'           => esc_html__('If you don\'t want loop for slider products, please turn this OFF.', 'elsey'),
              'dependency'     => array('woo_single_related|woo_related_load_style', '==|==', 'true|slider' ),
            ),
            array(
              'id'             => 'woo_related_slider_nav',
              'type'           => 'switcher',
              'default'        => true,
              'title'          => esc_html__('Slider Next/Prev Buttons', 'elsey'),
              'info'           => esc_html__('If you don\'t want to show slider next/prev buttons, please turn this OFF.', 'elsey'),
              'dependency'     => array('woo_single_related|woo_related_load_style', '==|==', 'true|slider' ),
            ),
            array(
              'id'             => 'woo_related_slider_dots',
              'type'           => 'switcher',
              'default'        => true,
              'title'          => esc_html__('Slider Dots Pagination', 'elsey'),
              'info'           => esc_html__('If you don\'t want to show slider dots, please turn this OFF.', 'elsey'),
              'dependency'     => array('woo_single_related|woo_related_load_style', '==|==', 'true|slider' ),
            ),
            array(
              'id'             => 'woo_related_slider_autoplay',
              'type'           => 'switcher',
              'default'        => false,
              'title'          => esc_html__('Slider Autoplay', 'elsey'),
              'info'           => esc_html__('If you don\'t want slider autoplay, please turn this OFF.', 'elsey'),
              'dependency'     => array('woo_single_related|woo_related_load_style', '==|==', 'true|slider' ),
            ),
            // End Fields
          ),
        ),

      ),
    );
  }

  // ------------------------------
  // Extra Pages
  // ------------------------------
  $options[]   = array(
    'name'     => 'theme_extra_pages',
    'title'    => esc_html__('Extra Pages', 'elsey'),
    'icon'     => 'fa fa-clone',
    'sections' => array(

      // Start 404 Page
      array(
        'name'     => 'error_page_section',
        'title'    => esc_html__('404 Page', 'elsey'),
        'icon'     => 'fa fa-exclamation-triangle',
        'fields'   => array(

          array(
            'id'        => 'error_page_heading',
            'type'      => 'text',
            'title'     => esc_html__('404 Page Heading', 'elsey'),
            'info'      => esc_html__('Enter 404 page heading.', 'elsey'),
          ),
          array(
            'id'        => 'error_page_content',
            'type'      => 'textarea',
            'shortcode' => true,
            'title'     => esc_html__('404 Page Content', 'elsey'),
            'info'      => esc_html__('Enter 404 page content.', 'elsey'),

          ),
          array(
            'id'        => 'error_page_bground',
            'type'      => 'image',
            'title'     => esc_html__('404 Page Background', 'elsey'),
            'info'      => esc_html__('Choose 404 page background styles.', 'elsey'),
            'add_title' => esc_html__('Add 404 Image', 'elsey'),
          ),
          array(
            'id'        => 'error_page_btntext',
            'type'      => 'text',
            'title'     => esc_html__('Button Text', 'elsey'),
            'info'      => esc_html__('Enter BACK TO HOME button text. If you want to change it.', 'elsey'),
          ),

        )
      ),
      // End 404 Page

      // Maintenance Mode Page
      array(
        'name'     => 'maintenance_mode_section',
        'title'    => esc_html__('Maintenance Mode', 'elsey'),
        'icon'     => 'fa fa-hourglass-half',
        'fields'   => array(

          // Start Maintenance Mode
          array(
            'type'    => 'notice',
            'class'   => 'info cs-vt-heading',
            'content' => esc_html__('If you turn this ON : Only Logged in users will see your pages. All other visiters will see, selected page of : <strong>Maintenance Mode Page</strong>', 'elsey')
          ),
          array(
            'id'             => 'enable_maintenance_mode',
            'type'           => 'switcher',
            'title'          => esc_html__('Maintenance Mode', 'elsey'),
            'default'        => false,
          ),
          array(
            'id'             => 'maintenance_mode_page',
            'type'           => 'select',
            'title'          => esc_html__('Maintenance Mode Page', 'elsey'),
            'options'        => 'pages',
            'default_option' => esc_html__('Select a page', 'elsey'),
            'dependency'     => array( 'enable_maintenance_mode', '==', 'true' ),
          ),
          array(
            'id'             => 'maintenance_mode_bg',
            'type'           => 'background',
            'title'          => esc_html__('Page Background', 'elsey'),
            'dependency'     => array( 'enable_maintenance_mode', '==', 'true' ),
          ),
          // End Maintenance Mode
        )
      ),
    )
  );

  // ------------------------------
  // Advanced
  // ------------------------------
  $options[] = array(
    'name'   => 'theme_advanced',
    'title'  => esc_html__('Advanced', 'elsey'),
    'icon'   => 'fa fa-cog'
  );

  // ------------------------------
  // Misc Section
  // ------------------------------
  $options[]   = array(
    'name'     => 'misc_section',
    'title'    => esc_html__('Misc', 'elsey'),
    'icon'     => 'fa fa-recycle',
    'sections' => array(

      // Custom Sidebar Section Start
      array(
        'name'     => 'custom_sidebar_section',
        'title'    => esc_html__('Custom Sidebar', 'elsey'),
        'icon'     => 'fa fa-reorder',
        'fields'   => array(

          array(
            'id'              => 'custom_sidebar',
            'title'           => esc_html__('Sidebars', 'elsey'),
            'desc'            => esc_html__('Go to Appearance -> Widgets after create sidebars', 'elsey'),
            'type'            => 'group',
            'fields'          => array(
              array(
                'id'          => 'sidebar_name',
                'type'        => 'text',
                'title'       => esc_html__('Sidebar Name', 'elsey'),
              ),
              array(
                'id'          => 'sidebar_desc',
                'type'        => 'text',
                'title'       => esc_html__('Custom Description', 'elsey'),
              )
            ),
            'accordion'       => true,
            'button_title'    => esc_html__('Add New Sidebar', 'elsey'),
            'accordion_title' => esc_html__('New Sidebar', 'elsey'),
            'default'             => array(
              array(
                'sidebar_name' => 'FAQ Sidebar',
                'sidebar_desc' => 'FAQ Sidebar',
              ),
            ),
          ),

        )
      ),
      // Custom Sidebar Section End

      // Custom CSS/JS
      array(
        'name'        => 'custom_css_js_section',
        'title'       => esc_html__('Custom Codes', 'elsey'),
        'icon'        => 'fa fa-code',
        'fields'      => array(

          // Start Custom CSS/JS
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Custom CSS', 'elsey')
          ),
          array(
            'id'             => 'theme_custom_css',
            'type'           => 'textarea',
            'attributes'     => array(
              'rows'         => 10,
              'placeholder'  => esc_html__('Enter your CSS code here...', 'elsey'),
            ),
          ),
          array(
            'type'           => 'notice',
            'class'          => 'info cs-vt-heading',
            'content'        => esc_html__('Custom JS', 'elsey')
          ),
          array(
            'id'             => 'theme_custom_js',
            'type'           => 'textarea',
            'attributes'     => array(
              'rows'         => 10,
              'placeholder'  => esc_html__('Enter your JS code here...', 'elsey'),
            ),
          ),
          // End Custom CSS/JS

        )
      ),

      // Translation
      array(
        'name'        => 'theme_translation_section',
        'title'       => esc_html__('Translation', 'elsey'),
        'icon'        => 'fa fa-language',

        // Begin: Fields
        'fields'      => array(
          // Blog Texts
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Blog Layouts', 'elsey')
          ),
          array(
            'id'          => 'read_more_text',
            'type'        => 'text',
            'title'       => esc_html__('Read More Text', 'elsey'),
          ),
          // Blog Pagination Texts
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Blog Pagination', 'elsey')
          ),
          array(
            'id'          => 'lmore_post_text',
            'type'        => 'text',
            'title'       => esc_html__('Load More Text', 'elsey'),
          ),
          array(
            'id'          => 'older_post_text',
            'type'        => 'text',
            'title'       => esc_html__('Older Posts Text', 'elsey'),
          ),
          array(
            'id'          => 'newer_post_text',
            'type'        => 'text',
            'title'       => esc_html__('Newer Posts Text', 'elsey'),
          ),
          // Single Post Texts
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Single Post', 'elsey')
          ),
           array(
            'id'          => 'share_text',
            'type'        => 'text',
            'title'       => esc_html__('Share Text', 'elsey'),
          ),
          array(
            'id'          => 'share_on_text',
            'type'        => 'text',
            'title'       => esc_html__('Share On Tooltip Text', 'elsey'),
          ),
          // Comment Area/Form
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Single Post Comment Area / Form', 'elsey')
          ),
          array(
            'id'          => 'comment_singular_text',
            'type'        => 'text',
            'title'       => esc_html__('Comment Singular Text', 'elsey'),
          ),
          array(
            'id'          => 'comment_plural_text',
            'type'        => 'text',
            'title'       => esc_html__('Comment Plural Text', 'elsey'),
          ),
          array(
            'id'          => 'comment_form_title_text',
            'type'        => 'text',
            'title'       => esc_html__('Title Reply Text', 'elsey'),
          ),
          array(
            'id'          => 'comment_form_reply_to_text',
            'type'        => 'text',
            'title'       => esc_html__('Title Reply To Text', 'elsey'),
          ),
          array(
            'id'          => 'comment_field_text',
            'type'        => 'text',
            'title'       => esc_html__('Comment Field Text', 'elsey'),
          ),
          array(
            'id'          => 'name_field_text',
            'type'        => 'text',
            'title'       => esc_html__('Name Field Text', 'elsey'),
          ),
          array(
            'id'          => 'email_field_text',
            'type'        => 'text',
            'title'       => esc_html__('Email Field Text', 'elsey'),
          ),
          array(
            'id'          => 'url_field_text',
            'type'        => 'text',
            'title'       => esc_html__('Website Field Text', 'elsey'),
          ),
          array(
            'id'          => 'reply_comment_text',
            'type'        => 'text',
            'title'       => esc_html__('Reply Text [Reply Button]', 'elsey'),
          ),
          array(
            'id'          => 'post_comment_text',
            'type'        => 'text',
            'title'       => esc_html__('Post Comment Text [Submit Button]', 'elsey'),
          ),
          // Single Post Pagination
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Single Post Pagination', 'elsey')
          ),
          array(
            'id'          => 'prev_post_text',
            'type'        => 'text',
            'title'       => esc_html__('Previous Post Text', 'elsey'),
          ),
          array(
            'id'          => 'next_post_text',
            'type'        => 'text',
            'title'       => esc_html__('Next Post Text', 'elsey'),
          ),
          // Comment Pagination
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Comment Pagination', 'elsey')
          ),
          array(
            'id'          => 'previous_comment_text',
            'type'        => 'text',
            'title'       => esc_html__('Previous Comment Text', 'elsey'),
          ),
          array(
            'id'          => 'next_comment_text',
            'type'        => 'text',
            'title'       => esc_html__('Next Comment Text', 'elsey'),
          ),
          // Single Shop Texts
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Single Product', 'elsey')
          ),
          array(
            'id'          => 'product_share_on_text',
            'type'        => 'text',
            'title'       => esc_html__('Product Share On Tooltip Text', 'elsey'),
          ),
          // Shop Pagination
          array(
            'type'        => 'notice',
            'class'       => 'info cs-els-heading',
            'content'     => esc_html__('Shop Pagination', 'elsey')
          ),
          array(
            'id'          => 'lmore_shop_text',
            'type'        => 'text',
            'title'       => esc_html__('Load More Products Text', 'elsey'),
          ),
          array(
            'id'          => 'older_shop_text',
            'type'        => 'text',
            'title'       => esc_html__('Older Products Text', 'elsey'),
          ),
          array(
            'id'          => 'newer_shop_text',
            'type'        => 'text',
            'title'       => esc_html__('Newer Products Text', 'elsey'),
          ),
          // End Translation
        ) // End: Fields
      ),

    ),
  );

  // ------------------------------
  // Envato Account
  // ------------------------------
  $options[]   = array(
    'name'     => 'envato_account_section',
    'title'    => esc_html__('Envato Account', 'elsey'),
    'icon'     => 'fa fa-link',
    'fields'   => array(

      array(
        'type'    => 'notice',
        'class'   => 'warning',
        'content' => esc_html__('Enter your Username and API key. You can get update our themes from WordPress admin itself.', 'elsey'),
      ),
      array(
        'id'      => 'themeforest_username',
        'type'    => 'text',
        'title'   => esc_html__('Envato Username', 'elsey'),
      ),
      array(
        'id'      => 'themeforest_api',
        'type'    => 'text',
        'title'   => esc_html__('Envato API Key', 'elsey'),
        'class'   => 'text-security',
        'after'   => __('<p>This is not a password field. Enter your Envato API key, which is located in : <strong>http://themeforest.net/user/[YOUR-USER-NAME]/api_keys/edit</strong></p>', 'elsey')
      ),

    )
  );

  // ------------------------------
  // Backup                       -
  // ------------------------------
  $options[]   = array(
    'name'     => 'backup_section',
    'title'    => 'Backup',
    'icon'     => 'fa fa-shield',
    'fields'   => array(

      array(
        'type'    => 'notice',
        'class'   => 'warning',
        'content' => 'You can save your current options. Download a Backup and Import.',
      ),
      array(
        'type'    => 'backup',
      ),

    )
  );

  return $options;

}
add_filter( 'cs_framework_options', 'elsey_vt_options' );