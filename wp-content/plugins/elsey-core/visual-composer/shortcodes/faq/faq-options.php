<?php
/**
 * FAQ- Shortcode Options
 */
add_action( 'init', 'elsey_faq_vc_map' );
if ( ! function_exists( 'elsey_faq_vc_map' ) ) {
  function elsey_faq_vc_map() {
    vc_map( array(
      "name" => __( "FAQ", 'elsey-core'),
      "base" => "elsey_faq",
      "description" => __( "FAQ", 'elsey-core'),
      "icon" => "fa fa-newspaper-o color-red",
      "category" => ElseyLib::elsey_cat_name(),
      "params" => array(

        array(
          "type"        =>'textfield',
          "heading"     =>__('Limit', 'elsey-core'),
          "param_name"  => "faq_limit",
          "value"       => "",
          'admin_label' => true,
          "description" => __( "Enter the number of items to show.", 'elsey-core'),
        ),
        array(
    			"type"        => "notice",
    			"heading"     => __( "Listing", 'elsey-core' ),
    			"param_name"  => 'lsng_opt',
    			'class'       => 'cs-warning',
    			'value'       => '',
    		),
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order', 'elsey-core' ),
          'value' => array(
            __( 'Select faq Order', 'elsey-core' ) => '',
            __('Asending', 'elsey-core') => 'ASC',
            __('Desending', 'elsey-core') => 'DESC',
          ),
          'param_name' => 'faq_order',
          'edit_field_class'   => 'vc_col-md-6 vc_column vt_field_space',
        ),
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order By', 'elsey-core' ),
          'value' => array(
            __('None', 'elsey-core') => 'none',
            __('ID', 'elsey-core') => 'ID',
            __('Author', 'elsey-core') => 'author',
            __('Title', 'elsey-core') => 'title',
            __('Date', 'elsey-core') => 'date',
          ),
          'param_name' => 'faq_orderby',
          'edit_field_class'   => 'vc_col-md-6 vc_column vt_field_space',
        ),
        array(
          "type"        => 'textfield',
          "heading"     => __('Show Only Certain Faq?', 'elsey-core'),
          "param_name"  => "faq_show",
          "value"       => "",
          "description" => __( "Enter faq ids (comma separated) you want to display.", 'elsey-core')
        ),
        array(
          "type"        =>'switcher',
          "heading"     =>__('Pagination', 'elsey-core'),
          "param_name"  => "faq_pagination",
          "value"       => "",
          "std"         => true,
          'edit_field_class'   => 'vc_col-md-6 vc_column vt_field_space',
        ),
        ElseyLib::elsey_class_option(),

      )
    ) );
  }
}
