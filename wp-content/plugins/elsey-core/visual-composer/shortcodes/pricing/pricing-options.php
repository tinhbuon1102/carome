<?php
/**
 * Pricing Box - Shortcode Options
 */
add_action( 'init', 'elsey_pricing_box_vc_map' );
if ( ! function_exists( 'elsey_pricing_box_vc_map' ) ) {
  function elsey_pricing_box_vc_map() {
    vc_map( array(
      "name" => __( "Pricing Box", 'elsey-core'),
      "base" => "elsey_pricing_box",
      "description" => __( "Pricing Box", 'elsey-core'),
      "icon" => "fa fa-usd color-orange",
      "category" => ElseyLib::elsey_cat_name(),
      "params" => array(
       
        array(
          "type" => "textfield",
          "heading" => __( "Plan Title", 'elsey-core' ),
          "param_name" => "price_title",
          'value' => '',
          'admin_label' => true,
          "description" => __( "Enter your pricing_box title.", 'elsey-core'),
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Plan Sub Title", 'elsey-core' ),
          "param_name" => "price_subtitle",
          'value' => '',
          'admin_label' => true,
          "description" => __( "Enter your pricing_box title.", 'elsey-core'),
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Plan Cost", 'elsey-core' ),
          "param_name" => "price",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',          
          'value' => '',
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Currency", 'elsey-core' ),
          "param_name" => "price_currency",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          'value' => '',
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Duration", 'elsey-core' ),
          "param_name" => "price_duration",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          'value' => '',
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Plan Label", 'elsey-core' ),
          "param_name" => "price_label",
          'value' => '',
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Button Text", 'elsey-core' ),
          "param_name" => "btn_text",
          'value' => '',
        ),
        array(
          "type" => "textfield",
          "heading" => __( "Button Link", 'elsey-core' ),
          "param_name" => "btn_link",
          'value' => '',
        ),
        ElseyLib::elsey_class_option(),

        // List of features
        array(
          'type' => 'param_group',
          'value' => '',
          'heading' => __( 'Features', 'elsey-core' ),
          'param_name' => 'pricing_box_features',
          'params' => array(
            array(
              'type' => 'textfield',
              'value' => '',
              'heading' => __( 'Features', 'elsey-core' ),
              'param_name' => 'price_features',
              'admin_label' => true,
            ),            

          )
        ),

        // Style
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Background Color', 'elsey-core'),
          "param_name"  => "price_bg_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button Text Color', 'elsey-core'),
          "param_name"  => "btn_text_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button Text Hover Color', 'elsey-core'),
          "param_name"  => "btn_text_hover_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button Background Color', 'elsey-core'),
          "param_name"  => "btn_background_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button Background Hover Color', 'elsey-core'),
          "param_name"  => "btn_background_hover_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button Border Color', 'elsey-core'),
          "param_name"  => "btn_border_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),
        array(
          "type"        =>'colorpicker',
          "heading"     =>__('Button border Hover Color', 'elsey-core'),
          "param_name"  => "btn_border_hover_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 vc_column vt_field_space',
          "group"     =>__('Style', 'elsey-core'),
        ),

      )
    ) );
  }
}
