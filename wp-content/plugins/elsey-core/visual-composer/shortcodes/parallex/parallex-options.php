<?php
/**
 * Parallex - Shortcode Options
 */
add_action( 'init', 'elsey_parallex_vc_map' );

if ( ! function_exists( 'elsey_parallex_vc_map' ) ) {
    
  function elsey_parallex_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__('Parallex Sections', 'elsey-core'),
      'base'        => 'elsey_parallex',
      'description' => esc_html__('Parallex Sections Styles', 'elsey-core'),
      'icon'        => 'fa fa-ellipsis-v color-byzantine',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(

        array(
          'type'                 => 'notice',
          'heading'              => esc_html__( 'Parallex Settings', 'elsey-core' ),
          'param_name'           => 'plx_settings',
          'class'                => 'cs-info',
          'value'                => '',
        ),
        array(
          'type'                 => 'textfield',
          'heading'              => esc_html__( 'Parallex Scroll Speed', 'elsey-core' ),
          'param_name'           => 'plx_scroll_speed',
          'value'                => '',
          'description'          => esc_html__( 'Enter parallex section navigation scrolling speed.', 'elsey-core'),
          'edit_field_class'     => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'                 => 'dropdown',
          'heading'              => esc_html__('Scrolling Type', 'elsey-core'),
          'param_name'           => 'plx_scroll_type',
          'value'                => array(
            esc_html__( 'Full Page Scrolling', 'elsey-core' ) => 'flp_scroll',
            esc_html__( 'Parallex Scrolling', 'elsey-core' )  => 'plx_scroll',
          ),
          'description'          => 'Select sections scrolling type.',
          'edit_field_class'     => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'                 => 'switcher',
          'heading'              => esc_html__('Side Navigation', 'elsey-core'),
          'param_name'           => 'plx_side_nav',
          'value'                => '',
          'std'                  => false,
          'description'          => esc_html__('Show parallex sections side navigation.', 'elsey-core'),
          'edit_field_class'     => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'                 => 'param_group',
          'value'                => '',
          'heading'              => esc_html__( 'Parallex Sections', 'elsey-core' ),
          'param_name'           => 'plx_sections',
          'params'               => array(

            array(
              'type'             => 'attach_image',
              'value'            => '',
              'heading'          => esc_html__( 'Section Image', 'elsey-core' ),
              'param_name'       => 'plx_img',
              'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
            ),      
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__( 'Section Minimum Height', 'elsey-core' ),
              'param_name'       => 'plx_min_height',           
              'description'      => esc_html__( 'Enter each parallex section minimum height.', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__( 'Parallex speed', 'elsey-core' ),
              'param_name'       => 'plx_speed',           
              'description'      => __( 'Enter parallex speed to move relative to vertical scroll (Default: 0.1)', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__( 'Heading One', 'elsey-core' ),
              'param_name'       => 'plx_text_one',           
              'description'      => __( 'Enter Title One Text.', 'elsey-core' ),
              'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',       
              'heading'          => esc_html__( 'Heading Two Line One', 'elsey-core' ),
              'param_name'       => 'plx_text2_part_one',     
              'description'      => __( 'Enter Title Two Line One Text.', 'elsey-core' ),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',       
              'heading'          => esc_html__( 'Heading Two Line Two', 'elsey-core' ),
              'param_name'       => 'plx_text2_part_two',     
              'description'      => __( 'Enter Title Two Line Two Text.', 'elsey-core' ),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',       
              'heading'          => esc_html__( 'Heading Three', 'elsey-core' ),
              'param_name'       => 'plx_text_three',     
              'description'      => __( 'Enter Title Three Text.', 'elsey-core' ),
              'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
            ),
            array(
              'type'             => 'textarea',
              'value'            => '',
              'heading'          => esc_html__( 'Description', 'elsey-core' ),
              'param_name'       => 'plx_desc',
              'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
            ),
            array(
              'type'             => 'textfield',
              'heading'          => esc_html__( 'Button Text', 'elsey-core' ),
              'param_name'       => 'plx_btn_text',
              'value'            => '',
              'description'      => esc_html__( 'Enter your button text.', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'href',
              'heading'          => esc_html__( 'Button Link', 'elsey-core' ),
              'param_name'       => 'plx_btn_link',
              'value'            => '',
              'description'      => esc_html__( 'Enter your button link.', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'dropdown',
              'heading'          => esc_html__('Content Alignment', 'elsey-core'),
              'param_name'       => 'plx_content_align',
              'value'            => array(
                esc_html__( 'Select Alignment', 'elsey-core' )                 => '',
                esc_html__( 'Left Space Vertical Center',   'elsey-core' )     => 'left-ns',
                esc_html__( 'Right Space Vertical Center',  'elsey-core' )     => 'right-ns',
                esc_html__( 'Left More Space Vertical Center', 'elsey-core' )  => 'left-ms',
                esc_html__( 'Right More Space Vertical Center', 'elsey-core' ) => 'right-ms',
                esc_html__( 'Center', 'elsey-core' )                           => 'center',
              ),
              'description'      => 'Select content text alignment (Default: right)',
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
            ),
            array(
              'type'             => 'colorpicker',
              'heading'          => esc_html__( 'Active Color', 'elsey-core' ),
              'param_name'       => 'plx_active_color',
              'value'            => '',
              'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
              'description'      => 'Select active section bullet color.',
            ),
          )
        ),

        ElseyLib::elsey_class_option(),
        
      )

    ) );

  }

}
