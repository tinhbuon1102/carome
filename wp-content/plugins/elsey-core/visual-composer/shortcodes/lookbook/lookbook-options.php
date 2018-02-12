<?php
/**
 * Lookbook - Shortcode Options
 */
add_action( 'init', 'elsey_lb_vc_map' );

if ( ! function_exists( 'elsey_lb_vc_map' ) ) {
    
  function elsey_lb_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__( 'Lookbook', 'elsey-core'),
      'base'        => 'elsey_lb',
      'description' => esc_html__( 'Lookbook Styles', 'elsey-core'),
      'icon'        => 'fa fa-newspaper-o color-green',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(

        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Layout', 'elsey-core' ),
          'param_name'       => 'lt_opt',
          'class'            => 'cs-info',
          'value'            => '',
        ),     
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Lookbook Style', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Standard', 'elsey-core' ) => 'els-lb-one',
            esc_html__( 'Masonry', 'elsey-core' )  => 'els-lb-two',
            esc_html__( 'Slider', 'elsey-core' )   => 'els-lb-three',
          ),
          'admin_label'      => true,
          'param_name'       => 'lb_style',
          'description'      => esc_html__( 'Select your lookbook style.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Lookbook ID', 'elsey-core' ),
          'param_name'       => 'lb_single_id',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter the single lookbook id only.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ), 
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Columns', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Select Columns', 'elsey-core' ) => 'els-lb-col-none',
            esc_html__( 'Column Three', 'elsey-core' )   => 'els-lb-col-3',
            esc_html__( 'Column Four', 'elsey-core' )    => 'els-lb-col-4',
            esc_html__( 'Column Five', 'elsey-core' )    => 'els-lb-col-5',
          ),
          'param_name'       => 'lb_columns',
          'description'      => esc_html__( 'Select your lookbook column.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-one' ),
          ),
        ),  
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Items', 'elsey-core' ),
          'param_name'       => 'lb_slider_limit',
          'value'            => '',
          'description'      => esc_html__( 'Enter the number of items you want to see on the screen.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ), 
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Enable/Disable', 'elsey-core' ),
          'param_name'       => 'ed_opt',
          'class'            => 'cs-info',
          'value'            => '',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
		    ),  
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Loop', 'elsey-core'),
          'param_name'       => 'lb_slider_loop',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Inifnity loop.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),                    
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Navigation', 'elsey-core'),
          'param_name'       => 'lb_slider_nav',
          'value'            => '',
          'std'              => true,
          'description'      => esc_html__( 'Show next/prev buttons.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Dots', 'elsey-core'),
          'param_name'       => 'lb_slider_dots',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Show dots navigation.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),                       
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Autoplay', 'elsey-core'),
          'param_name'       => 'lb_slider_autoplay',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Start Autoplay.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),     
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Navigation Speed', 'elsey-core' ),
          'param_name'       => 'lb_slider_nav_speed',
          'description'      => esc_html__( 'Enter navigation speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'   => array(
                'element'    => 'nav',
                'value'      => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Dots Speed', 'elsey-core' ),
          'param_name'       => 'lb_slider_dots_speed',
          'description'      => esc_html__( 'Enter dots speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'   => array(
                'element'    => 'dots',
                'value'      => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ),        
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Autoplay Speed', 'elsey-core' ),
          'param_name'       => 'lb_slider_ap_speed',
          'description'      => esc_html__( 'Enter autoplay speed(value is in ms, enter numbers only).', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'autoplay',
            'value'          => 'true',
          ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'lb_style',
            'value'          => array( 'els-lb-three' ),
          ),
        ), 
          
        ElseyLib::elsey_class_option(),

      )
    ) );
  }
}