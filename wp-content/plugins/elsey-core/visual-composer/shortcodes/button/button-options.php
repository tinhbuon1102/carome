<?php
/**
 * Button - Shortcode Options
 */

add_action( 'init', 'elsey_button_vc_map' );

if ( ! function_exists( 'elsey_button_vc_map' ) ) {

  function elsey_button_vc_map() {

    vc_map( array(
      'name'        => esc_html__('Button', 'elsey-core'),
      'base'        => 'elsey_button',
      'description' => esc_html__('Button Styles', 'elsey-core'),
      'icon'        => 'fa fa-mouse-pointer color-brown',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(

        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Layout', 'elsey-core' ),
          'param_name'       => 'bl_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Button Text', 'elsey-core' ),
          'param_name'       => 'btn_text',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter your button text.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'href',
          'heading'          => esc_html__( 'Button Link', 'elsey-core' ),
          'param_name'       => 'btn_link',
          'value'            => '',
          'description'      => esc_html__( 'Enter your button link.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Open New Tab?', 'elsey-core' ),
          'param_name'       => 'btn_target',
          'std'              => false,
          'value'            => '',
          'on_text'          => esc_html__( 'Yes', 'elsey-core' ),
          'off_text'         => esc_html__( 'No', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Custom Hover', 'elsey-core' ),
          'param_name'       => 'btn_hover',
          'std'              => false,
          'value'            => '',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        // Styling
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Styling', 'elsey-core' ),
          'param_name'       => 'bs_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Text Size', 'elsey-core' ),
          'param_name'       => 'btn_text_size',
          'value'            => '',
          'description'      => esc_html__( 'Enter button text font size. [Eg: 14px]', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
    		  'type'             => 'dropdown',
    		  'heading'          => esc_html__('Alignment', 'elsey-core'),
    		  'param_name'       => 'btn_align',
          'value'            => array(
            esc_html__( 'Select Button Align', 'elsey-core' ) => '',
            esc_html__( 'Center', 'elsey-core' )              => 'center',
            esc_html__( 'Left', 'elsey-core' )                => 'left',
            esc_html__( 'Right', 'elsey-core' )               => 'right',
          ),
          'description'      => 'Select button alignment.',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
    		),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Text Color', 'elsey-core' ),
          'param_name'       => 'btn_text_color',
          'value'            => '',
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Text Hover Color', 'elsey-core' ),
          'param_name'       => 'btn_text_hover_color',
          'value'            => '',
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'btn_hover',
            'value'          => 'true',
          ),
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Background Hover Color', 'elsey-core' ),
          'param_name'       => 'btn_bg_hover_color',
          'value'            => '',
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'btn_hover',
            'value'          => 'true',
          ),
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Border Hover Color', 'elsey-core' ),
          'param_name'       => 'btn_br_hover_color',
          'value'            => '',
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'btn_hover',
            'value'          => 'true',
          ),
        ),

        ElseyLib::elsey_class_option(),

        // Design Tab
        array(
          'type'             => 'css_editor',
          'heading'          => esc_html__( 'Text Size', 'elsey-core' ),
          'param_name'       => 'btn_css',
          'group'            => esc_html__( 'Design', 'elsey-core'),
        ),

      )
    ) );
  }
}
