<?php
/**
 * Image Slider - Shortcode Options
 */
 
add_action( 'init', 'elsey_image_slider_vc_map' );

if ( ! function_exists( 'elsey_image_slider_vc_map' ) ) {
    
  function elsey_image_slider_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__( 'Image Slider', 'elsey-core'),
      'base'        => 'elsey_image_slider',
      'description' => esc_html__( 'Image Slider Styles', 'elsey-core'),
      'icon'        => 'fa fa-sliders color-pink',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(
      
        array(
          'type'             => 'notice',
          'heading'          => esc_html__('Layout', 'elsey-core'),
          'param_name'       => 'layout_opt',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Title', 'elsey-core'),
          'param_name'       => 'title',
          'value'            => '',
          'description'      => esc_html__( 'Enter the title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ), 
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Items', 'elsey-core'),
          'param_name'       => 'items_cnt',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter the number of items you want to see on the screen.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),    
        array(
          'type'             => 'attach_images',
          'heading'          => esc_html__( 'Images', 'elsey-core' ),
          'param_name'       => 'images',         
          'value'            => '',
          'description'      => esc_html__( 'Choose images for this slider.', 'elsey-core' ),
        ),
        array(
          'type'             => 'exploded_textarea',
          'heading'          => esc_html__( 'Images Links', 'elsey-core' ),
          'param_name'       => 'images_links',
          'description'      => esc_html__( 'Define custom links for slider images. Each new line will be a separate link. Leave empty line to skip an image.', 'elsey-core' ),
        ),         
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Style', 'elsey-core' ),
          'param_name'       => 'style_opt',
          'class'            => 'cs-info',
          'value'            => '',
        ),       
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Gap', 'elsey-core' ),
          'param_name'       => 'gap',
          'description'      => esc_html__( 'Enter gap between images(value is in px, enter numbers only).', 'elsey-core' ),
          'edit_field_class' => 'vc_col-sm-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Background Color', 'elsey-core' ),
          'param_name'       => 'bg_color',
          'value'            => '',
          'description'      => esc_html__( 'Select background color.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Loop', 'elsey-core'),
          'param_name'       => 'loop',
          'value'            => '',
          'std'              => true,
          'description'      => esc_html__( 'Inifnity loop.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),                    
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Navigation', 'elsey-core'),
          'param_name'       => 'nav',
          'value'            => '',
          'std'              => true,
          'description'      => esc_html__( 'Show next/prev buttons.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Dots', 'elsey-core'),
          'param_name'       => 'dots',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Show dots navigation.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),                       
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Autoplay', 'elsey-core'),
          'param_name'       => 'autoplay',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Start Autoplay.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),     
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Navigation Speed', 'elsey-core' ),
          'param_name'       => 'nav_speed',
          'description'      => esc_html__( 'Enter navigation speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'       => array(
                'element'        => 'nav',
                'value'          => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Dots Speed', 'elsey-core' ),
          'param_name'       => 'dots_speed',
          'description'      => esc_html__( 'Enter dots speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'       => array(
                'element'        => 'dots',
                'value'          => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
        ),        
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Autoplay Speed', 'elsey-core' ),
          'param_name'       => 'autoplay_speed',
          'description'      => esc_html__( 'Enter autoplay speed(value is in ms, enter numbers only).', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'autoplay',
            'value'          => 'true',
          ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
        ),
               
        ElseyLib::elsey_class_option(),
              
      )
    ) );
  }
}
