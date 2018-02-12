<?php
/**
 * Contact - Shortcode Options
 */

if ( is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {

  add_action( 'init', 'newsletter_vc_map' );

  if ( ! function_exists( 'newsletter_vc_map' ) ) {

    function newsletter_vc_map() {

      vc_map( array(
        'name'        => __('Newsletter', 'elsey-core'),
        'base'        => 'elsey_newsletter',
        'description' => __('Newsletter Shortcodes', 'elsey-core'),
        'icon'        => 'fa fa-envelope-o color-black',
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
            'type'             => 'textfield',
            'heading'          => esc_html__( 'Title One', 'elsey-core' ),
            'param_name'       => 'nl_title_one',
            'value'            => '',
            'description'      => esc_html__( 'Enter the title one text.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ), 
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__( 'Title One Heading', 'elsey-core' ),
            'param_name'       => 'nl_title_one_tag',
            'value'            => array(
              esc_html__( 'Select Title One Tag', 'elsey-core' ) => '',
              esc_html__( 'H2', 'elsey-core' ) => 'h2',
              esc_html__( 'H3', 'elsey-core' ) => 'h3',
              esc_html__( 'H4', 'elsey-core' ) => 'h4',
              esc_html__( 'H5', 'elsey-core' ) => 'h5',
              esc_html__( 'H6', 'elsey-core' ) => 'h6',
            ),
            'description'      => esc_html__( 'Select your title one heading tag.', 'elsey-core' ),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ), 
          array(
            'type'             => 'textfield',
            'heading'          => esc_html__( 'Title Two', 'elsey-core' ),
            'param_name'       => 'nl_title_two',
            'value'            => '',
            'description'      => esc_html__( 'Enter the title two text.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ),  
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__( 'Title Two Heading', 'elsey-core' ),
            'value'            => array(
              esc_html__( 'Select Title Two Tag', 'elsey-core' ) => '',
              esc_html__( 'H2', 'elsey-core' ) => 'h2',
              esc_html__( 'H3', 'elsey-core' ) => 'h3',
              esc_html__( 'H4', 'elsey-core' ) => 'h4',
              esc_html__( 'H5', 'elsey-core' ) => 'h5',
              esc_html__( 'H6', 'elsey-core' ) => 'h6',
            ),
            'param_name'       => 'nl_title_two_tag',
            'description'      => esc_html__( 'Select your title two heading tag.', 'elsey-core' ),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ), 
          array(
            'type'             => 'textarea',
            'heading'          => esc_html__( 'Description', 'elsey-core' ),
            'param_name'       => 'nl_desc',
            'value'            => '',
            'description'      => esc_html__( 'Enter the description text.', 'elsey-core'),
          ),  
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__( 'MailChimp Subscription Form Style', 'elsey-core' ),
            'param_name'       => 'nl_form_style',
            'value'            => array(
              esc_html__( 'Style One', 'elsey-core' ) => 'els-subs-one',
              esc_html__( 'Style Two', 'elsey-core' ) => 'els-subs-two',
            ),
            'description'      => esc_html__( 'Select your subscription form style.', 'elsey-core' ),
          ),

          ElseyLib::elsey_class_option(),

        )
      ) );
    }
  }
}