<?php
/**
 * Coupon Code - Shortcode Options
 */

add_action( 'init', 'elsey_coupon_vc_map' );

if ( ! function_exists( 'elsey_coupon_vc_map' ) ) {

  function elsey_coupon_vc_map() {

    vc_map( array(
      'name'        => esc_html__('Coupon Code', 'elsey-core'),
      'base'        => 'elsey_coupon',
      'description' => esc_html__('Coupon Code Styles', 'elsey-core'),
      'icon'        => 'fa fa-mouse-pointer color-brown',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(

        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Layout', 'elsey-core' ),
          'param_name'       => 'cl_opt',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Heading Text', 'elsey-core' ),
          'param_name'       => 'heading_text',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter your heading text.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
        ),
        array(
          'type'             => 'textarea',
          'heading'          => esc_html__( 'Coupon Description', 'elsey-core' ),
          'param_name'       => 'coupon_desc',
          'value'            => '',
          'description'      => esc_html__( 'Enter your coupon description.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Code', 'elsey-core' ),
          'param_name'       => 'coupon_code',
          'value'            => '',
          'description'      => esc_html__( 'Enter coupon code', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Link Text', 'elsey-core' ),
          'param_name'       => 'coupon_link_text',
          'value'            => '',
          'description'      => esc_html__( 'Enter coupon link text', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),


        array(
          'type'             => 'href',
          'heading'          => esc_html__( 'Coupon Link', 'elsey-core' ),
          'param_name'       => 'coupon_link',
          'value'            => '',
          'description'      => esc_html__( 'Enter your coupon link.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Coupon Link Open New Tab?', 'elsey-core' ),
          'param_name'       => 'coupon_link_target',
          'std'              => false,
          'value'            => '',
          'on_text'          => esc_html__( 'Yes', 'elsey-core' ),
          'off_text'         => esc_html__( 'No', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Button Text', 'elsey-core' ),
          'param_name'       => 'cpn_btn_text',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter your Coupon button text.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
      

        // Styling
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Styling', 'elsey-core' ),
          'param_name'       => 'bs_opt',
          'class'            => 'cs-info',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Heading Text Size', 'elsey-core' ),
          'param_name'       => 'heading_text_size',
          'value'            => '',
          'group'            => __('Style', 'elsey-core'),
          'description'      => esc_html__( 'Enter heading text font size.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Heading Text Color', 'elsey-core' ),
          'param_name'       => 'heading_text_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Description Text Size', 'elsey-core' ),
          'param_name'       => 'desc_text_size',
          'value'            => '',
          'group'            => __('Style', 'elsey-core'),
          'description'      => esc_html__( 'Enter description text font size.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Description Text Color', 'elsey-core' ),
          'param_name'       => 'desc_text_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Code Text Size', 'elsey-core' ),
          'param_name'       => 'cpn_code_size',
          'value'            => '',
          'group'            => __('Style', 'elsey-core'),
          'description'      => esc_html__( 'Enter Coupon code font size.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Code Color', 'elsey-core' ),
          'param_name'       => 'cpn_text_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Link Text Size', 'elsey-core' ),
          'param_name'       => 'cpn_link_size',
          'value'            => '',
          'group'            => __('Style', 'elsey-core'),
          'description'      => esc_html__( 'Enter coupon link font size.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Link Color', 'elsey-core' ),
          'param_name'       => 'cpn_link_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Link Hover Color', 'elsey-core' ),
          'param_name'       => 'cpn_link_hover_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),

        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Coupon Button Text Size', 'elsey-core' ),
          'param_name'       => 'cpn_btn_text_size',
          'value'            => '',
          'group'            => __('Style', 'elsey-core'),
          'description'      => esc_html__( 'Enter coupon button font size.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Text Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_text_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Border Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_border_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Background Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_bg_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Text Hover Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_text_hover_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Border Hover Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_border_hover_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
        ),
        array(
          'type'             => 'colorpicker',
          'heading'          => esc_html__( 'Coupon Button Background Hover Color', 'elsey-core' ),
          'param_name'       => 'cpn_btn_bg_hover_color',
          'group'            => __('Style', 'elsey-core'),
          'value'            => '',
          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
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
