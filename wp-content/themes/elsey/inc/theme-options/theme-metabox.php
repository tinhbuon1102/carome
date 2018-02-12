<?php
/*
 * All Metabox related options for elsey theme.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

function elsey_metabox_options( $options ) {

  $options = array();

  // -----------------------------------------
  // Post Metabox Options                    -
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'post_type_metabox',
    'title'     => esc_html__('Post Options', 'elsey'),
    'post_type' => 'post',
    'context'   => 'normal',
    'priority'  => 'default',
    'sections'  => array(

      // All Post Formats
      array(
        'name'   => 'section_post_formats',
        'fields' => array(

          // Standard, Image
          array(
            'title'          => esc_html__('Standard/Image Format', 'elsey'),
            'type'           => 'subheading',
            'content'        => esc_html__('There is no Extra Option for this Post Format!', 'elsey'),
            'wrap_class'     => 'els-minimal-heading hide-title',
          ),
          // Standard, Image

          // Gallery
          array(
            'type'           => 'notice',
            'wrap_class'     => 'gallery-title',
            'class'          => 'info cs-els-heading',
            'content'        => esc_html__('Gallery Format', 'elsey')
          ),
          array(
            'id'             => 'gallery_display_type',
            'type'           => 'select',
            'title'          => esc_html__('Display Format', 'elsey'),
            'options'        => array(
              'img-slider'   => esc_html__('Slider', 'elsey'),
              'img-gallery'  => esc_html__('Tiles', 'elsey'),
            ),
            'default_option' => 'Select Display Format',
            'info'           => esc_html__('Default option : Slider', 'elsey'),
          ),
          array(
            'id'             => 'gallery_post_format',
            'type'           => 'gallery',
            'title'          => esc_html__('Add Gallery', 'elsey'),
            'add_title'      => esc_html__('Add Image(s)', 'elsey'),
            'edit_title'     => esc_html__('Edit Image(s)', 'elsey'),
            'clear_title'    => esc_html__('Clear Image(s)', 'elsey'),
          ),
          // Gallery

          // Audio
          array(
            'type'           => 'notice',
            'wrap_class'     => 'audio-title',
            'class'          => 'info cs-els-heading',
            'content'        => esc_html__('Audio Format', 'elsey')
          ),
          array(
            'id'             => 'audio_post_format',
            'type'           => 'textarea',
            'title'          => esc_html__('Add Audio', 'elsey'),
            'sanitize'       => false,
          ),
          // Audio

          // Video
          array(
            'type'           => 'notice',
            'wrap_class'     => 'video-title',
            'class'          => 'info cs-els-heading',
            'content'        => esc_html__('Video Format', 'elsey')
          ),
          array(
            'id'             => 'video_post_format',
            'type'           => 'textarea',
            'title'          => esc_html__('Add Video', 'elsey'),
            'sanitize'       => false,
          ),
          // Video
        ),
      ),

    ),
  );

  // -----------------------------------------
  // Product
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'post_featured_options',
    'title'     => esc_html__('Masonry Layout Featured Image', 'elsey'),
    'post_type' => 'post',
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'   => 'section_post_featured',
        'fields' => array(

            array(
              'id'        => 'masonry_featured_image',
              'type'      => 'image',
              'title'     => '',
              'info'      => esc_html__('Upload featured image for masonry layout.', 'elsey'),
              'add_title' => esc_html__('Add Image', 'elsey'),
            ),

        ),
      ),

    ),
  );

  // -----------------------------------------
  // Page Metabox Options                    -
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'page_type_metabox',
    'title'     => esc_html__('Page Custom Options', 'elsey'),
    'post_type' => array('post', 'page', 'product'),
    'context'   => 'normal',
    'priority'  => 'default',
    'sections'  => array(

      // Header - Top Bar Section
      array(
        'name'   => 'topbar_section',
        'title'  => esc_html__('Top Bar', 'elsey'),
        'icon'   => 'fa fa-minus',
        'fields' => array(

          array(
            'id'            => 'topbar_options',
            'type'          => 'image_select',
            'title'         => esc_html__('Top Bar', 'elsey'),
            'options'       => array(
              'default'     => ELSEY_CS_IMAGES .'/meta-default.png',
              'custom'      => ELSEY_CS_IMAGES .'/meta-custom.png',
              'hide'        => ELSEY_CS_IMAGES .'/meta-hide.png',
            ),
            'attributes'    => array(
              'data-depend-id' => 'topbar_options',
            ),
            'radio'         => true,
            'default'       => 'default',
          ),
          array(
            'id'            => 'topbar_left_content',
            'type'          => 'textarea',
            'title'         => esc_html__('Top Left Content', 'elsey'),
            'dependency'    => array('topbar_options', '==', 'custom'),
            'shortcode'     => true,
          ),
          array(
            'id'            => 'topbar_my_account',
            'type'          => 'switcher',
            'default'       => true,
            'title'         => esc_html__('Top Right Login', 'elsey'),
            'info'          => esc_html__('Turn Off if you don\'t want to show <strong>login/register</strong> in top bar right position. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'    => array('topbar_options', '==', 'custom'),
          ),
          array(
            'id'            => 'topbar_right_link',
            'type'          => 'switcher',
            'title'         => esc_html__('Featured Link', 'elsey'),
            'info'          => esc_html__('Turn off if you don\'t want to show featured link at topbar right.', 'elsey'),
            'default'       => false,
            'dependency'    => array('topbar_options', '==', 'custom'),
          ),
          array(
            'id'            => 'topbar_link_title',
            'type'          => 'text',
            'title'         => esc_html__('Featured Link Title', 'elsey'),
            'info'          => '',
            'dependency'     => array('topbar_options|topbar_right_link', '==|==', 'custom|true'),
          ),
          array(
            'id'            => 'topbar_link_url',
            'type'          => 'text',
            'title'         => esc_html__('Featured Link URL', 'elsey'),
            'info'          => '',
            'dependency'     => array('topbar_options|topbar_right_link', '==|==', 'custom|true'),
          ),
          array(
            'id'            => 'topbar_currency',
            'type'          => 'switcher',
            'default'       => true,
            'title'         => esc_html__('Top Right Currency', 'elsey'),
            'info'          => esc_html__('Turn Off if you don\'t want to show <strong>currency selector</strong> in top bar right position. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'    => array('topbar_options', '==', 'custom'),
          ),
          array(
            'id'            => 'topbar_background',
            'type'          => 'color_picker',
            'rgba'          => true,
            'title'         => esc_html__('Background Color', 'elsey'),
            'info'          => esc_html__('Pick your top bar background color.', 'elsey'),
            'dependency'    => array('topbar_options', '==', 'custom'),
          ),

        )
      ),
      // End : Top Bar Section

      // Header - Menu Bar Section
      array(
        'name'   => 'menubar_section',
        'title'  => esc_html__('Menu Bar', 'elsey'),
        'icon'   => 'fa fa-ellipsis-h',
        'fields' => array(

          array(
            'id'             => 'menubar_options',
            'type'           => 'image_select',
            'title'          => esc_html__('Menu Bar', 'elsey'),
            'options'        => array(
              'default'      => ELSEY_CS_IMAGES . '/meta-default.png',
              'custom'       => ELSEY_CS_IMAGES . '/meta-custom.png',
              'hide'         => ELSEY_CS_IMAGES . '/meta-hide.png',
            ),
            'attributes'     => array(
              'data-depend-id' => 'menubar_options',
            ),
            'radio'          => true,
            'default'        => 'default',
          ),
          array(
            'id'             => 'menubar_choose_menu',
            'type'           => 'select',
            'options'        => 'menus',
            'default_option' => esc_html__('Select your menu', 'elsey'),
            'title'          => esc_html__('Choose Menu', 'elsey'),
            'info'           => esc_html__('Choose your custom menu for this page.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
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
            'dependency'     => array('menubar_options', '==', 'custom'),
            'info'           => esc_html__('Choose your custom menu position for this page.', 'elsey'),
          ),
          array(
            'id'             => 'menubar_search',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Search', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show search in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_wishlist',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Wishlist', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show wishlist in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_cart',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Cart Widget', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show cart widget in menu bar. Make sure about installation/activation of WooCommerce plugin.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_rightmenu',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Right Menu', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want to show right menu in menu bar.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_sticky',
            'type'           => 'switcher',
            'default'        => true,
            'title'          => esc_html__('Sticky Menu Bar', 'elsey'),
            'info'           => esc_html__('Turn Off if you don\'t want your menu bar on sticky.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_transparent',
            'type'           => 'switcher',
            'default'        => false,
            'title'          => esc_html__('Transparent', 'elsey'),
            'info'           => esc_html__('Turn On if you want to show transparent menu bar.', 'elsey'),
            'dependency'     => array('menubar_options', '==', 'custom'),
          ),
          array(
            'id'             => 'menubar_trans_icon_color',
            'type'           => 'radio',
            'title'          => esc_html__('Select Icon Color', 'elsey'),
            'options'        => array(
              'icon-default'   => esc_html__('Default Color', 'elsey'),
              'icon-white'     => esc_html__('White Color', 'elsey'),
            ),
            'class'          => 'horizontal',
            'default'        => 'icon-default',
            'info'           => __('Select icon color to display at menu bar.', 'elsey'),
            'dependency'     => array('menubar_options|menubar_transparent', '==|==', 'custom|true'),
          ),
          array(
            'id'             => 'menubar_trans_main_color',
            'type'           => 'color_picker',
            'rgba'           => true,
            'title'          => esc_html__('Menu Text Color', 'elsey'),
            'info'           => esc_html__('Pick your menu text color. This color will only apply for non-sticky header mode.', 'elsey'),
            'dependency'     => array('menubar_options|menubar_transparent', '==|==', 'custom|true'),
          ),
          array(
            'id'             => 'menubar_trans_hover_color',
            'type'           => 'color_picker',
            'rgba'           => true,
            'title'          => esc_html__('Menu Text Hover Color', 'elsey'),
            'info'           => esc_html__('Pick your menu text hover color. This color will only apply for non-sticky header mode.', 'elsey'),
            'dependency'     => array('menubar_options|menubar_transparent', '==|==', 'custom|true'),
          ),
          array(
            'id'             => 'menubar_bg',
            'type'           => 'color_picker',
            'rgba'           => true,
            'title'          => esc_html__('Background Color', 'elsey'),
            'info'           => esc_html__('Pick your menu bar background color.', 'elsey'),
            'dependency'     => array('menubar_options|menubar_transparent', '==|==', 'custom|false'),
          ),
          array(
            'id'             => 'menubar_bottom_border_color',
            'type'           => 'color_picker',
            'title'          => esc_html__('Bottom Border Color', 'elsey'),
            'rgba'           => true,
            'dependency'     => array('menubar_options|menubar_transparent', '==|==', 'custom|false'),
          ),
        )
      ), // End : Menu Bar Section

      // Header - Banner & Title Bar
      array(
        'name'   => 'title_section',
        'title'  => esc_html__('Title Bar', 'elsey'),
        'icon'   => 'fa fa-bullhorn',
        'fields' => array(

          array(
            'id'               => 'titlebar_options',
            'type'             => 'image_select',
            'title'            => esc_html__('Title Bar', 'elsey'),
            'options'          => array(
              'default'        => ELSEY_CS_IMAGES . '/meta-default.png',
              'custom'         => ELSEY_CS_IMAGES . '/meta-custom.png',
              'hide'           => ELSEY_CS_IMAGES . '/meta-hide.png',
            ),
            'attributes'       => array(
              'data-depend-id' => 'titlebar_options',
            ),
            'radio'            => true,
            'default'          => 'default',
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
            'info'             => esc_html__('Select title bar layout.', 'elsey'),
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),
          array(
            'id'               => 'titlebar_text_type',
            'type'             => 'select',
            'title'            => esc_html__('Title Text', 'elsey'),
            'options'          => array(
              'default-title-text' => esc_html__('Default Title', 'elsey'),
              'custom-title-text'  => esc_html__('Custom Title', 'elsey'),
              'hide-title-text'    => esc_html__('Hide Title', 'elsey'),
            ),
            'default_option'   => 'Select title text',
            'info'             => esc_html__('Select title bar title text type. Default option : Default Title', 'elsey'),
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),
          array(
            'id'               => 'title_custom_text',
            'type'             => 'text',
            'title'            => esc_html__('Custom Title', 'elsey'),
            'attributes'       => array(
              'placeholder'    => esc_html__('Enter your custom title...', 'elsey'),
            ),
            'dependency'       => array('titlebar_options|titlebar_text_type', '==|==', 'custom|custom-title-text'),
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
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),
          array(
            'id'               => 'titlebar_top_spacings',
            'type'             => 'text',
            'title'            => esc_html__('Top Spacing', 'elsey'),
            'attributes'       => array('placeholder' => '100px'),
            'dependency'       => array('titlebar_options|titlebar_spacings', '==|==', 'custom|els-padding-custom'),
          ),
          array(
            'id'               => 'titlebar_bottom_spacings',
            'type'             => 'text',
            'title'            => esc_html__('Bottom Spacing', 'elsey'),
            'attributes'       => array('placeholder' => '100px'),
            'dependency'       => array('titlebar_options|titlebar_spacings', '==|==', 'custom|els-padding-custom'),
          ),
          array(
            'id'               => 'titlebar_bg',
            'type'             => 'background',
            'title'            => esc_html__('Background Image/Color', 'elsey'),
            'rgba'             => true,
            'info'             => esc_html__('Pick your title bar background image or color.', 'elsey'),
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),
          array(
            'id'               => 'titlebar_bg_overlay',
            'type'             => 'color_picker',
            'rgba'             => true,
            'title'            => esc_html__('Overlay Color', 'elsey'),
            'info'             => esc_html__('Pick your title bar background image overlay color.', 'elsey'),
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),
          array(
            'id'               => 'titlebar_parallax',
            'type'             => 'switcher',
            'default'          => false,
            'title'            => esc_html__('Parallax Effect', 'elsey'),
            'info'             => esc_html__('Turn On if you want to show parallax effect in title bar.', 'elsey'),
            'dependency'       => array('titlebar_options|titlebar_layout', '==|==', 'custom|vertical'),
          ),
          array(
            'id'               => 'titlebar_breadcrumb',
            'type'             => 'switcher',
            'default'          => true,
            'title'            => esc_html__('Breadcrumbs', 'elsey'),
            'info'             => esc_html__('Turn Off if you don\'t want to show breadcrumbs in title bar.', 'elsey'),
            'dependency'       => array('titlebar_options', '==', 'custom'),
          ),

        )
      ), // End : Title Bar

      // Content Section
      array(
        'name'   => 'page_content_options',
        'title'  => esc_html__('Content Options', 'elsey'),
        'icon'   => 'fa fa-file',
        'fields' => array(

          array(
            'id'          => 'content_spacings',
            'type'        => 'select',
            'title'       => esc_html__('Content Spacings', 'elsey'),
            'options'     => array(
              'els-padding-none'   => esc_html__('Default Padding', 'elsey'),
              'els-padding-zero'   => esc_html__('No Padding', 'elsey'),
              'els-padding-xs'     => esc_html__('Extra Small Padding', 'elsey'),
              'els-padding-sm'     => esc_html__('Small Padding', 'elsey'),
              'els-padding-md'     => esc_html__('Medium Padding', 'elsey'),
              'els-padding-lg'     => esc_html__('Large Padding', 'elsey'),
              'els-padding-xl'     => esc_html__('Extra Large Padding', 'elsey'),
              'els-padding-custom' => esc_html__('Custom Padding', 'elsey'),
            ),
            'info'        => esc_html__('Content area top and bottom spacings.', 'elsey'),
          ),
          array(
            'id'          => 'content_top_spacings',
            'type'        => 'text',
            'title'       => esc_html__('Top Spacing', 'elsey'),
            'attributes'  => array('placeholder' => '100px'),
            'dependency'  => array('content_spacings', '==', 'els-padding-custom'),
          ),
          array(
            'id'          => 'content_btm_spacings',
            'type'        => 'text',
            'title'       => esc_html__('Bottom Spacing', 'elsey'),
            'attributes'  => array('placeholder' => '100px' ),
            'dependency'  => array('content_spacings', '==', 'els-padding-custom'),
          ),
          array(
            'id'          => 'content_layout_bg',
            'type'        => 'background',
            'title'       => esc_html__('Content Background', 'elsey' ),
            'info'        => esc_html__('Content area background.', 'elsey'),
          ),
          array(
            'id'          => 'content_layout_bg_overlay',
            'type'        => 'color_picker',
            'title'       => esc_html__('Content Overlay', 'elsey'),
            'rgba'        => true,
            'info'        => esc_html__('Content area background image overlay color.', 'elsey')
          ),

        )
      ), // End : Content Section

      // Enable & Disable
      array(
        'name'   => 'hide_show_section',
        'title'  => esc_html__('Enable & Disable', 'elsey'),
        'icon'   => 'fa fa-toggle-on',
        'fields' => array(

  		  array(
            'id'      => 'hide_header',
            'type'    => 'switcher',
            'title'   => esc_html__('Hide Header', 'elsey'),
            'label'   => esc_html__('Yes, Please do it.', 'elsey'),
            'default' => false,
          ),
          array(
            'id'      => 'hide_footer',
            'type'    => 'switcher',
            'title'   => esc_html__('Hide Footer', 'elsey'),
            'label'   => esc_html__('Yes, Please do it.', 'elsey'),
            'default' => false,
          ),

        )
      ), // End : Enable & Disable

    ),
  );

  // -----------------------------------------
  // Page Layout
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'page_layout_options',
    'title'     => esc_html__('Page Layout', 'elsey'),
    'post_type' => 'page',
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'       => 'page_layout_section',
        'video_link' => 'http://yahoo.co.in',
        'fields'     => array(

          array(
            'id'               => 'page_layout',
            'type'             => 'image_select',
            'options'          => array(
              'less-width'     => ELSEY_CS_IMAGES . '/page-layout-2.png',
              'full-width'     => ELSEY_CS_IMAGES . '/page-layout-1.png',
              'strech-width'   => ELSEY_CS_IMAGES . '/page-layout-3.png',
            ),
            'attributes'       => array(
              'data-depend-id' => 'page_layout',
            ),
            'default'          => 'less-width',
            'radio'            => true,
            'wrap_class'       => 'text-center',
          ),
          array(
            'id'               => 'page_show_sidebar',
            'type'             => 'switcher',
            'title'            => esc_html__('Show Sidebar', 'elsey'),
            'default'          => false,
            'dependency'       => array('page_layout', 'any', 'less-width,full-width'),
          ),
          array(
            'id'               => 'page_sidebar_position',
            'type'             => 'image_select',
            'options'          => array(
              'sidebar-left'   => ELSEY_CS_IMAGES . '/page-sidebar-1.png',
              'sidebar-right'  => ELSEY_CS_IMAGES . '/page-sidebar-2.png',
            ),
            'attributes'       => array(
              'data-depend-id' => 'page_sidebar_position',
            ),
            'default'          => 'sidebar-left',
            'radio'            => true,
            'wrap_class'       => 'text-center',
            'dependency'       => array('page_show_sidebar', '==', 'true'),
          ),
          array(
            'id'               => 'page_sidebar_widget',
            'type'             => 'select',
            'title'            => esc_html__('Sidebar Widget', 'elsey'),
            'options'          => elsey_vt_registered_sidebars(),
            'default_option'   => esc_html__('Select widget', 'elsey'),
            'dependency'       => array('page_show_sidebar', '==', 'true'),
          ),

        ),
      ),

    ),
  );

  // -----------------------------------------
  // Post Layout
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'post_page_layout_options',
    'title'     => esc_html__('Page Layout', 'elsey'),
    'post_type' => array('post'),
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'       => 'post_page_layout_section',
        'video_link' => 'http://yahoo.co.in',
        'fields'     => array(

          array(
            'id'               => 'post_page_layout',
            'type'             => 'image_select',
            'options'          => array(
              'theme-default'  => ELSEY_CS_IMAGES . '/theme-default.png',
              'less-width'     => ELSEY_CS_IMAGES . '/page-layout-2.png',
              'full-width'     => ELSEY_CS_IMAGES . '/page-layout-1.png',
            ),
            'attributes'       => array(
              'data-depend-id' => 'post_page_layout',
            ),
            'default'          => 'theme-default',
            'radio'            => true,
            'wrap_class'       => 'text-center',
          ),
          array(
            'id'               => 'post_page_show_sidebar',
            'type'             => 'switcher',
            'default'          => false,
            'title'            => esc_html__('Show Sidebar', 'elsey'),
            'dependency'       => array('post_page_layout', 'any', 'full-width,less-width'),
          ),
          array(
            'id'               => 'post_page_sidebar_position',
            'type'             => 'image_select',
            'options'          => array(
              'sidebar-left'   => ELSEY_CS_IMAGES . '/page-sidebar-1.png',
              'sidebar-right'  => ELSEY_CS_IMAGES . '/page-sidebar-2.png',
            ),
            'attributes'       => array(
              'data-depend-id' => 'post_page_sidebar_position',
            ),
            'radio'            => true,
            'default'          => 'sidebar-left',
            'wrap_class'       => 'text-center',
            'dependency'       => array('post_page_layout|post_page_show_sidebar', 'any|==', 'full-width,less-width|true'),
          ),
          array(
            'id'               => 'post_page_sidebar_widget',
            'type'             => 'select',
            'title'            => esc_html__('Sidebar Widget', 'elsey'),
            'options'          => elsey_vt_registered_sidebars(),
            'default_option'   => esc_html__('Select widget', 'elsey'),
            'dependency'       => array('post_page_layout|post_page_show_sidebar', 'any|==', 'full-width,less-width|true'),
          ),

        ),
      ),

    ),
  );

  // -----------------------------------------
  // Product
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'product_options',
    'title'     => esc_html__('Product Options', 'elsey'),
    'post_type' => 'product',
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'   => 'product_option_section',
        'fields' => array(

          array(
            'id'          => 'product_masonry_size',
            'type'        => 'select',
            'default'     => 'pd-wh',
            'title'       => esc_html__('Masonry Layout Grid Size', 'elsey'),
            'options'     => array(
              'pd-wh'     => esc_html__('Default', 'elsey'),
              'pd-2wh'    => esc_html__('Double Width & Height', 'elsey'),
            ),
            'help'        => esc_html__('This style will apply for this product on masonry product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_masonry_image',
            'type'        => 'image',
            'title'       => 'Masonry Layout Image',
            'add_title'   => esc_html__('Upload/Add Image', 'elsey'),
            'help'        => esc_html__('This style will apply for this product on masonry product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_masonry_hover_image',
            'type'        => 'image',
            'title'       => 'Masonry Layout Hover Image',
            'add_title'   => esc_html__('Upload/Add Image', 'elsey'),
            'help'        => esc_html__('This style will apply for this product on masonry product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_fullgrid_image',
            'type'        => 'image',
            'title'       => 'Fullgrid Layout Image',
            'add_title'   => esc_html__('Upload/Add Image', 'elsey'),
            'help'        => esc_html__('This style will apply for this product on full grid product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_single_bg',
            'type'        => 'color_picker',
            'rgba'        => true,
            'title'       => esc_html__('Background Color', 'elsey'),
            'help'        => esc_html__('This style will apply for this product on product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_hover_image_change',
            'type'        => 'switcher',
            'title'       => esc_html__('Image Change On Hover', 'elsey'),
            'default'     => true,
            'help'        => esc_html__('This style will apply for this product on product catalog sortcodes and pages.', 'elsey'),
          ),
          array(
            'id'          => 'product_sticky_info',
            'type'        => 'switcher',
            'title'       => esc_html__('Sticky Info Product', 'elsey'),
            'default'     => false,
            'help'        => esc_html__('This style will apply for this product on product single page.', 'elsey'),
          ),
        ),
      ),

    ),
  );

  // -----------------------------------------
  // Testimonial
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'testimonial_options',
    'title'     => esc_html__('Testimonial Client', 'elsey'),
    'post_type' => 'testimonial',
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'   => 'testimonial_option_section',
        'fields' => array(

          array(
            'id'      => 'testi_name',
            'type'    => 'text',
            'title'   => esc_html__('Name', 'elsey'),
            'info'    => esc_html__('Enter client name', 'elsey'),
          ),
          array(
            'id'      => 'testi_name_link',
            'type'    => 'text',
            'title'   => esc_html__('Name Link', 'elsey'),
            'info'    => esc_html__('Enter client name link, if you want', 'elsey'),
          ),
          array(
            'id'      => 'testi_pro',
            'type'    => 'text',
            'title'   => esc_html__('Profession', 'elsey'),
            'info'    => esc_html__('Enter client profession', 'elsey'),
          ),
          array(
            'id'      => 'testi_pro_link',
            'type'    => 'text',
            'title'   => esc_html__('Profession Link', 'elsey'),
            'info'    => esc_html__('Enter client profession link', 'elsey'),
          ),

        ),
      ),

    ),
  );

  // -----------------------------------------
  // Team
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'team_options',
    'title'     => esc_html__('Member Details', 'elsey'),
    'post_type' => 'team',
    'context'   => 'side',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'   => 'team_option_section',
        'fields' => array(

          array(
            'id'            => 'team_member_job_position',
            'type'          => 'text',
            'attributes'    => array(
              'placeholder' => esc_html__('Eg : Financial Manager', 'elsey'),
            ),
            'info'          => esc_html__('Enter this employee job position, in your company.', 'elsey'),
          ),
          array(
            'id'            => 'team_member_custom_link',
            'type'          => 'text',
            'title'         => esc_html__('Custom Link', 'elsey'),
            'attributes'    => array(
              'placeholder' => esc_html__('http://', 'elsey'),
            ),
            'info'          => esc_html__('Enter this employee custom link.', 'elsey'),
          ),
          array(
            'id'            => 'team_member_facebook_link',
            'type'          => 'text',
            'title'         => esc_html__('Facebook Link', 'elsey'),
            'attributes'    => array(
              'placeholder' => esc_html__('http://', 'elsey'),
            ),
            'info'          => esc_html__('Enter this employee facebook link.', 'elsey'),
          ),
          array(
            'id'            => 'team_member_twitter_link',
            'type'          => 'text',
            'title'         => esc_html__('Twitter Link', 'elsey'),
            'attributes'    => array(
              'placeholder' => esc_html__('http://', 'elsey'),
            ),
            'info'          => esc_html__('Enter this employee twitter link.', 'elsey'),
          ),
          array(
            'id'            => 'team_member_instagram_link',
            'type'          => 'text',
            'title'         => esc_html__('Instagram Link', 'elsey'),
            'attributes'    => array(
              'placeholder' => esc_html__('http://', 'elsey'),
            ),
            'info'          => esc_html__('Enter this employee instagram link.', 'elsey'),
          ),
        ),
      ),

    ),
  );

  // -----------------------------------------
  // Lookbook Options
  // -----------------------------------------
  $options[]    = array(
    'id'        => 'lookbook_options',
    'title'     => esc_html__('Lookbook Options', 'elsey'),
    'post_type' => 'lookbook',
    'context'   => 'normal',
    'priority'  => 'default',
    'sections'  => array(

      array(
        'name'   => 'lookbook_option_section',
        'fields' => array(

          array(
            'id'             => 'lookbook_gallery',
            'type'           => 'gallery',
            'title'          => esc_html__('Add Lookbook Images', 'elsey'),
            'add_title'      => esc_html__('Add Image(s)', 'elsey'),
            'edit_title'     => esc_html__('Edit Image(s)', 'elsey'),
            'clear_title'    => esc_html__('Clear Image(s)', 'elsey'),
          ),

        ),
      ),

    ),
  );

  return $options;

}
add_filter( 'cs_metabox_options', 'elsey_metabox_options' );
