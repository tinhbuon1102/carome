<?php
/**
 * Counter - Shortcode Options
 */
add_action( 'init', 'elsy_counter_vc_map' );
if ( ! function_exists( 'elsy_counter_vc_map' ) ) {
  function elsy_counter_vc_map() {
    vc_map( array(
      "name" => __( "Counter", 'elsey-core'),
      "base" => "elsy_counter",
      "description" => __( "Counter Styles", 'elsey-core'),
      "icon" => "fa fa-sort-numeric-asc color-blue",
      "category" => ElseyLib::elsey_cat_name(),
      "params" => array(

        array(
          'type'             => 'elsey_icon',
          'heading'          => __('Set Icon', 'elsey-core'),
          'param_name'       => 'counter_icon',
          'value'            => '',
          'description'      => __('Set your counter icon.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          "type"        =>'textfield',
          "heading"     =>__('Title', 'elsey-core'),
          "param_name"  => "counter_title",
          "value"       => "",
          "description" => __( "Enter your counter title.", 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          "type"        =>'textfield',
          "heading"     =>__('Counter Value', 'elsey-core'),
          "param_name"  => "counter_value",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-6 vc_column els_field_space',
          "description" => __( "Enter numeric value to count. Ex : 20", 'elsey-core')
        ),
        ElseyLib::elsey_class_option(),

        // Stylings
        array(
          "type"        => 'colorpicker',
          "heading"     => __('Title Color', 'elsey-core'),
          "param_name"  => "counter_title_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
        ),
        array(
          "type"        => 'colorpicker',
          "heading"     => __('Counter Color', 'elsey-core'),
          "param_name"  => "counter_value_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
        ),
        // Size
        array(
          "type"        => 'textfield',
          "heading"     => __('Title Size', 'elsey-core'),
          "param_name"  => "counter_title_size",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
          "description" => __( "Enter font size in px.", 'elsey-core')
        ),
        array(
          "type"        => 'textfield',
          "heading"     => __('Counter Size', 'elsey-core'),
          "param_name"  => "counter_value_size",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
          "description" => __( "Enter font size in px.", 'elsey-core')
        ),

      )
    ) );
  }
}
