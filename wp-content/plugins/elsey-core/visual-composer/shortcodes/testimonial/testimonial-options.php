<?php
/**
 * Testimonial - Shortcode Options
 */
 
add_action( 'init', 'elsey_testi_vc_map' );

if ( ! function_exists( 'elsey_testi_vc_map' ) ) {
    
  function elsey_testi_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__( 'Testimonial Slider', 'elsey-core'),
      'base'        => 'elsey_testimonial',
      'description' => esc_html__( 'Testimonial Slider', 'elsey-core'),
      'icon'        => 'fa fa-bookmark-o color-purple',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(
        
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Layout', 'elsey-core' ),
          'param_name'       => 'layout_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),  
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Title', 'elsey-core'),
          'param_name'       => 'testi_title',
          'value'            => '',
          'description'      => esc_html__( 'Enter the title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),   
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Limit', 'elsey-core'),
          'param_name'       => 'testi_limit',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter the number of testimonials to show.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),         
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Show/Hide', 'elsey-core' ),
          'param_name'       => 'sh_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),  
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Image', 'elsey-core'),
          'param_name'       => 'testi_image',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Name', 'elsey-core'),
          'param_name'       => 'testi_name',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ), 
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Profession', 'elsey-core'),
          'param_name'       => 'testi_profession',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),    
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Navigation', 'elsey-core'),
          'param_name'       => 'testi_nav',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Show next/prev buttons.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Dots', 'elsey-core'),
          'param_name'       => 'testi_dots',
          'value'            => '',
          'std'              => true,
          'description'      => esc_html__( 'Show dots navigation.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ), 
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Autoplay', 'elsey-core'),
          'param_name'       => 'testi_autoplay',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Start Autoplay.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),                    
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Listing', 'elsey-core' ),
          'param_name'       => 'lsng_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Order', 'elsey-core' ),
          'value'            => array(
            esc_html__('Select Testimonial Order', 'elsey-core') => '',
            esc_html__('Asending', 'elsey-core')  => 'ASC',
            esc_html__('Desending', 'elsey-core') => 'DESC',
          ),
          'param_name'       => 'testi_order',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Order By', 'elsey-core' ),
          'value'            => array(
            esc_html__('None', 'elsey-core')  => 'none',
            esc_html__('ID', 'elsey-core')    => 'ID',
            esc_html__('Title', 'elsey-core') => 'title',
            esc_html__('Date', 'elsey-core')  => 'date',
          ),
          'param_name'       => 'testi_orderby',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
              
        ElseyLib::elsey_class_option(),

      )
    ) );
  }
}