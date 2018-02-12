<?php
/**
 * Tab Function - Shortcode Options
 */
add_action( 'init', 'tab_mobile_vc_map' );
if ( ! function_exists( 'tab_mobile_vc_map' ) ) {
  function tab_mobile_vc_map() {
    vc_map( array(
      "name" => esc_html__( "Tab Event", 'elsey-core'),
      "base" => "elsy_tab_mobile",
      "description" => esc_html__( "Tab Event", 'elsey-core'),
      "icon" => "fa fa-shield color-green",
      "category" => ElseyLib::elsey_cat_name(),
      "params" => array(

         ElseyLib::elsey_open_link_tab(),

        array(
          'type' => 'textfield',
          'value' => '',
          'heading' => esc_html__( 'Tab Event Section Title', 'elsey-core' ),
          'param_name' => 'title',
        ),
        array(
          'type' => 'textfield',
          'value' => '',
          'heading' => esc_html__( 'Tab Section Content', 'elsey-core' ),
          'param_name' => 'sec_content',
        ),
        array(
          'type' => 'textfield',
          'value' => '',
          'heading' => esc_html__( 'Button Text', 'elsey-core' ),
          'param_name' => 'btn_txt',
        ),
        array(
          'type' => 'textfield',
          'value' => '',
          'heading' => esc_html__( 'Button Link', 'elsey-core' ),
          'param_name' => 'btn_link',
        ),

        // Tab Event 
        array(
          'type' => 'param_group',
          'value' => '',
          'heading' => esc_html__( 'Tab Event', 'elsey-core' ),
          'param_name' => 'tab_mobiles',
          // Note params is mapped inside param-group:
          'params' => array(
            array(
              'type' => 'textfield',
              'value' => '',
              'heading' => esc_html__( 'Tab Event Title', 'elsey-core' ),
              'param_name' => 'tab_title',
            ),
              array(
              'type' => 'textfield',
              'value' => '',
              'heading' => esc_html__( 'Tab Content', 'elsey-core' ),
              'param_name' => 'tab_content',
            ),
            array(
              'type' => 'attach_image',
              'value' => '',
              'heading' => esc_html__( 'Upload Image', 'elsey-core' ),
              'param_name' => 'tab_image',
            ),
          
          )
        ),
        ElseyLib::elsey_class_option(),
      )
    ) );
  }
}