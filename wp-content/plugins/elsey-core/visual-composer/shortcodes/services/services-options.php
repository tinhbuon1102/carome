<?php
/**
 * Services - Shortcode Options
 */
add_action( 'init', 'els_service_vc_map' );

if ( ! function_exists( 'els_service_vc_map' ) ) {

  function els_service_vc_map() {

    vc_map( array(
      'name'        => __('Service', 'elsey-core'),
      'base'        => 'elsey_service',
      'description' => __('Service Shortcodes', 'elsey-core'),
      'icon'        => 'fa fa-cog color-cadetblue',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(


        array(
        'type'             => 'dropdown',
        'heading'          => esc_html__( 'Service Styles', 'elsey-core' ),
        'value'            => array(
          esc_html__( 'Service Style One', 'elsey-core' ) => 'els-style-one',
          esc_html__( 'Service Style Two', 'elsey-core' )  => 'els-style-two',
          esc_html__( 'Service Style Three', 'elsey-core' )  => 'els-style-three',
    
        ),
        'admin_label'      => true,
        'param_name'       => 'service_style_types',
        'description'      => esc_html__( 'Select your service style.', 'elsey-core' ),
        'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),

        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Service Icon Type', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Image', 'elsey-core' ) => 'els-service-one',
            esc_html__( 'Icon', 'elsey-core' )  => 'els-service-two',
            esc_html__( 'Number', 'elsey-core' )  => 'els-service-three',
          ),
          'admin_label'      => true,
          'param_name'       => 'service_style',
          'description'      => esc_html__( 'Select your service icon style.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'attach_image',
          'heading'          => __('Upload Service Image', 'elsey-core'),
          'param_name'       => 'service_image',
          'value'            => '',
          'description'      => __('Set your service image.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'service_style',
            'value'          => 'els-service-one',
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'elsey_icon',
          'heading'          => __('Set Icon', 'elsey-core'),
          'param_name'       => 'service_icon',
          'value'            => '',
          'description'      => __('Set your service icon.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-two'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Enter Number', 'elsey-core'),
          'param_name'       => 'service_number',
          'value'            => '',
          'description'      => __('Enter your service number.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-three'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),

        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Service Alignment', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Service Center', 'elsey-core' ) => 'els-center',
            esc_html__( 'Service Left', 'elsey-core' )  => 'els-left',
            esc_html__( 'Service Right', 'elsey-core' )  => 'els-right',
      
          ),
          'admin_label'      => true,
          'param_name'       => 'service_style_align',
          'description'      => esc_html__( 'Select your service alignment.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'service_style_types',
            'value'          => array('els-style-two'),
          ), 
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Service Alignment', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Service Right', 'elsey-core' )  => 'els-right',
            esc_html__( 'Service Left', 'elsey-core' )  => 'els-left',
      
          ),
          'admin_label'      => true,
          'param_name'       => 'service_style_three_align',
          'description'      => esc_html__( 'Select your service alignment.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'service_style_types',
            'value'          => array('els-style-three'),
          ), 
        ),

        ElseyLib::elsey_open_link_tab(),

        ElseyLib::elsey_notice_field(__('Content Area', 'elsey-core'), 'cntara_opt', 'cs-info', ''), // Notice

        array(
          'type'             => 'vc_link',
          'heading'          => __('Service Title', 'elsey-core'),
          'param_name'       => 'service_title',
          'value'            => '',
          'description'      => __( 'Enter your service title and link.', 'elsey-core')
        ),
        array(
          'type'             => 'textarea_html',
          'heading'          => __('Content', 'elsey-core'),
          'param_name'       => 'content',
          'value'            => '',
          'description'      => __( 'Enter your service content here.', 'elsey-core')
        ),

        // Read More
        array(
    			'type'             => 'notice',
    			'heading'          => __('Read More Link', 'elsey-core'),
    			'param_name'       => 'rml_opt',
    			'class'            => 'cs-info',
    			'value'            => '',
    		),
        array(
          'type'             => 'href',
          'heading'          => __('Link', 'elsey-core'),
          'param_name'       => 'read_more_link',
          'value'            => '',
          'description'      => __('Set your link for read more.', 'elsey-core'),  
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Title', 'elsey-core'),
          'param_name'       => 'read_more_title',
          'value'            => '',
          'description'      => __('Enter your read more title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
   
      
        ElseyLib::elsey_class_option(),

        // Style
       array(
        "type"        => "notice",
        "heading"     => esc_html__( "Icon Styling", 'elsey-core' ),
        "param_name"  => 'lsng_opt',
        'group'            => __('Style', 'elsey-core'),
        'class'       => 'cs-info',
        'value'       => '',
          'dependency'       => array(
          'element'        => 'service_style',
          'value'          => array('els-service-two'),
        ), 
      ),
        
        array(
          'type'             => 'colorpicker',
          'heading'          => __('Icon Color', 'elsey-core'),
          'param_name'       => 'icon_color',
          'value'            => '',
          'description'      => __('Pick your icon color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
             'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-two'),
          ), 
           
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Icon Size', 'elsey-core'),
          'param_name'       => 'icon_size',
          'value'            => '',
          'description'      => __('Enter the numeric value for icon size in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
             'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-two'),
          ), 
        ),

        array(
          "type"        => "notice",
          "heading"     => esc_html__( "Number Styling", 'elsey-core' ),
          "param_name"  => 'lsng_opt',
          'group'            => __('Style', 'elsey-core'),
          'class'       => 'cs-info',
          'value'       => '',
            'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-three'),
          ), 
        ),
        
        array(
          'type'             => 'colorpicker',
          'heading'          => __('Number Color', 'elsey-core'),
          'param_name'       => 'number_color',
          'value'            => '',
          'description'      => __('Pick your number color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-three'),
          ), 
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Number Size', 'elsey-core'),
          'param_name'       => 'number_size',
          'value'            => '',
          'description'      => __('Enter the numeric value for number size in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
           'dependency'       => array(
            'element'        => 'service_style',
            'value'          => array('els-service-three'),
          ), 
        ),

        ElseyLib::elsey_notice_field(__('Title Styling', 'elsey-core'), 'tle_opt', 'cs-info', 'Style'), 

        array(
          'type'             => 'colorpicker',
          'heading'          => __('Title Color', 'elsey-core'),
          'param_name'       => 'title_color',
          'value'            => '',
          'description'      => __('Pick your heading color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Title Size', 'elsey-core'),
          'param_name'       => 'title_size',
          'value'            => '',
          'description'      => __('Enter the numeric value for title size in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Title Font Weight', 'elsey-core'),
          'param_name'       => 'title_font_weight',
          'value'            => '',
          'description'      => __('Enter the numeric value for title font weight.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Title Top Space', 'elsey-core'),
          'param_name'       => 'title_top_space',
          'value'            => '',
          'description'      => __('Enter the value for title top space in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Title Bottom Space', 'elsey-core'),
          'param_name'       => 'title_bottom_space',
          'value'            => '',
          'description'      => __('Enter the value for title bottom space in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        ElseyLib::elsey_notice_field(__('Content Styling', 'elsey-core'), 'tle_opt', 'cs-info', 'Style'), 
          array(
          'type'             => 'colorpicker',
          'heading'          => __('Content Color', 'elsey-core'),
          'param_name'       => 'content_color',
          'value'            => '',
          'description'      => __('Pick your content color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Content Size', 'elsey-core'),
          'param_name'       => 'content_size',
          'value'            => '',
          'description'      => __('Enter the numeric value for content size in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
         ElseyLib::elsey_notice_field(__('Read More Styling', 'elsey-core'), 'tle_opt', 'cs-info', 'Style'), 
        array(
          'type'             => 'colorpicker',
          'heading'          => __('Read More Color', 'elsey-core'),
          'param_name'       => 'read_more_color',
          'value'            => '',
          'description'      => __('Pick your Read More color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => __('Read More Size', 'elsey-core'),
          'param_name'       => 'read_more_size',
          'value'            => '',
          'description'      => __('Enter the numeric value for read more size in px.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
         array(
          'type'             => 'colorpicker',
          'heading'          => __('Read More Hover Color', 'elsey-core'),
          'param_name'       => 'read_more_hover_color',
          'value'            => '',
          'description'      => __('Pick your Read More Hover color.', 'elsey-core'),
          'group'            => __('Style', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        // Design Tab
        array(
          "type" => "css_editor",
          "heading" => __( "Spacings", 'elsey-core' ),
          "param_name" => "css",
          "group" => __( "Design", 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
         'dependency'       => array(
          'element'        => 'service_style_types',
          'value'          => array('els-style-two'),
          ), 
        ),

      )
    ) );

  }
}
