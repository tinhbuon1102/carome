<?php
/*
 * Elsey Theme Widgets
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if ( ! function_exists( 'elsey_framework_widget_init' ) ) {
  
  function elsey_framework_widget_init() {
    
    if ( function_exists( 'register_sidebar' ) ) {

      // Main Widget Area Start
      register_sidebar(
       	array(
          'name'          => esc_html__('Main Widget', 'elsey'),
          'id'            => 'sidebar-main',
          'description'   => esc_html__('Appears on posts and pages.', 'elsey'),
          'before_widget' => '<div id="%1$s" class="els-widget sidebar-main-widget %2$s">',
          'after_widget'  => '</div> <!-- End Widget -->',
          'before_title'  => '<h2 class="widget-title">',
          'after_title'   => '</h2>',
   	    )
      );
      // Main Widget Area End

      // Right Menu Widget Area Start
      register_sidebar(
        array(
          'name'          => esc_html__( 'Menu Widget', 'elsey' ),
          'id'            => 'sidebar-right',
          'description'   => esc_html__( 'Appears on menu bar right.', 'elsey' ),
          'before_widget' => '<div id="%1$s" class="els-widget %2$s">',
          'after_widget'  => '</div> <!-- End Widget -->',
          'before_title'  => '<h2 class="widget-title">',
          'after_title'   => '</h2>',
        )
      );
      // Right Menu Widget Area End

      if (class_exists('WooCommerce')) {

        // Shop Widget Start
        register_sidebar(
          array(
            'name'          => esc_html__('Shop Widget', 'elsey'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Appears on Shop Pages.', 'elsey'),
            'before_widget' => '<div id="%1$s" class="els-widget sidebar-shop-widget %2$s">',
            'after_widget'  => '</div> <!-- End Widget -->',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
          )
        );
        // Shop Widget End
        
      }

      // Footer Widgets
      $footer_widget_block  = cs_get_option('footer_widget_block');
      $footer_widget_layout = cs_get_option('footer_widget_layout');

      if( $footer_widget_block && $footer_widget_layout ) {

        switch ( $footer_widget_layout ) {
          case 5:
          case 6:
          case 7:
            $length = 3;
            break;
          case 8:
          case 9:
            $length = 4;
            break;
          default:
            $length = $footer_widget_layout;
            break;
        }

        for( $i = 0; $i < $length; $i++ ) {
          $num = ( $i+1 );
          register_sidebar( array(
            'id'            => 'footer-' . $num,
            'name'          => esc_html__('Footer Widget ', 'elsey'). $num,
            'description'   => esc_html__('Appears on footer section.', 'elsey'),
            'before_widget' => '<div class="els-widget els-footer-'.$num.'-widget %2$s">',
            'after_widget'  => '<div class="clear"></div></div> <!-- End Widget -->',
            'before_title'  => '<h2 class="widget-title"><span>',
            'after_title'   => '</span></h2>'
          ) );
        }

      }
      // Footer Widgets End

      /* Custom Sidebar */
      $custom_sidebars = cs_get_option('custom_sidebar');

      if ($custom_sidebars) {

        foreach($custom_sidebars as $custom_sidebar) :

          $heading = $custom_sidebar['sidebar_name'];
          $own_id = preg_replace('/[^a-z]/', "-", strtolower($heading));
          $desc = $custom_sidebar['sidebar_desc'];

          register_sidebar( array(
            'id'            => $own_id,
            'name'          => esc_html($heading),
            'description'   => esc_html($desc),
            'before_widget' => '<div id="%1$s" class="els-widget '.$own_id.'-widget %2$s">',
            'after_widget'  => '</div> <!-- End Widget -->',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
          ) );

        endforeach;

      }
      /* Custom Sidebar End */

    }

  }

  add_action( 'widgets_init', 'elsey_framework_widget_init' );

}