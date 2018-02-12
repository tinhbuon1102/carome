<?php
/**
 * Counter - Shortcode Options
 */
add_action( 'init', 'elsy_qrcode_vc_map' );
if ( ! function_exists( 'elsy_qrcode_vc_map' ) ) {
  function elsy_qrcode_vc_map() {
    vc_map( array(
      "name" => __( "QR Code", 'elsey-core'),
      "base" => "elsy_qrcode",
      "description" => __( "QR Code Styles", 'elsey-core'),
      "icon" => "fa fa fa-barcode color-red",
      "category" => ElseyLib::elsey_cat_name(),
      "params" => array(

        array(
          "type"        =>'textfield',
          "heading"     =>__('Top Title', 'elsey-core'),
          "param_name"  => "qrcode_title",
          "value"       => "",
          "description" => __( "Enter your qrcode top title.", 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          "type"        =>'textfield',
          "heading"     =>__('Phone Number', 'elsey-core'),
          "param_name"  => "qrcode_phno",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-6 vc_column els_field_space',
          "description" => __( "Enter value like. Ex : +1 (800) 433 544", 'elsey-core')
        ),
        array(
          "type"        =>'textfield',
          "heading"     =>__('Scan Title', 'elsey-core'),
          "param_name"  => "qrcode_scan",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-6 vc_column els_field_space',
          "description" => __( "Enter your scan title.", 'elsey-core'),
          
        ),
        array(
          'type'             => 'attach_image',
          'heading'          => __('Upload QR code Image', 'elsey-core'),
          'param_name'       => 'qrcode_image',
          'value'            => '',
          'description'      => __('Set QR code image.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ),
        ElseyLib::elsey_class_option(),
         ElseyLib::elsey_notice_field(__('Text Styling', 'elsey-core'), 'tle_opt', 'cs-info', 'Style'),

        // Stylings
        array(
          "type"        => 'colorpicker',
          "heading"     => __('Top Title Color', 'elsey-core'),
          "param_name"  => "qrcode_title_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
        ),
        array(
          "type"        => 'colorpicker',
          "heading"     => __('Phone Number Color', 'elsey-core'),
          "param_name"  => "phone_no_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
        ),
        array(
          "type"        => 'colorpicker',
          "heading"     => __('Scan title Color', 'elsey-core'),
          "param_name"  => "scan_title_color",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
        ),

        ElseyLib::elsey_notice_field(__('Text Size', 'elsey-core'), 'tle_opt', 'cs-info', 'Style'),
        // Size
        array(
          "type"        => 'textfield',
          "heading"     => __('Top Title Size', 'elsey-core'),
          "param_name"  => "qrcode_title_size",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
          "description" => __( "Enter font size in px.", 'elsey-core')
        ),
        array(
          "type"        => 'textfield',
          "heading"     => __('Phone Number Size', 'elsey-core'),
          "param_name"  => "phone_no_size",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
          "description" => __( "Enter font size in px.", 'elsey-core')
        ),
          array(
          "type"        => 'textfield',
          "heading"     => __('QR Code Tile Size', 'elsey-core'),
          "param_name"  => "qr_title_size",
          "value"       => "",
          'edit_field_class'   => 'vc_col-md-4 els_field_space',
          "group"       => __('Style', 'elsey-core'),
          "description" => __( "Enter font size in px.", 'elsey-core')
        ),


      )
    ) );
  }
}
