<?php
/**
 * Product - Shortcode Options
 */
if (class_exists('WooCommerce')) {

  add_action( 'init', 'elsey_product_single_vc_map' );

  if ( ! function_exists( 'elsey_product_single_vc_map' ) ) {

  	function elsey_product_single_vc_map() {

	    vc_map( array(
	      'name'        => esc_html__( 'Product Single', 'elsey-core' ),
	      'base'        => 'elsey_product_single',
	      'description' => esc_html__( 'WooCommerce Product Single', 'elsey-core' ),
	      'icon'        => 'fa fa-shopping-bag color-orange',
	      'category'    => ElseyLib::elsey_cat_name(),
	      'params'      => array(

	      	array(
					  'type'             => 'notice',
					  'heading'          => esc_html__('Layout', 'elsey-core'),
					  'param_name'    	 => 'layout_opt',
					  'class'            => 'cs-info',
					  'value'            => '',
					),
					array(
            'type'             => 'textfield',
            'heading'          => esc_html__( 'Product ID', 'elsey-core' ),
            'param_name'       => 'pr_single_id',
            'value'            => '',
            'admin_label'      => true,
            'description'      => esc_html__( 'Enter the single product id only.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ), 
	      	array(
	          'type'             => 'dropdown',
	          'heading'          => esc_html__('Image Position', 'elsey-core'),   
	          'param_name'       => 'pr_single_style',
	          'value'            => array(
	            esc_html__( 'Top',    'elsey-core' ) => 'img-top',
	            esc_html__( 'Bottom', 'elsey-core' ) => 'img-btm',
	            esc_html__( 'Left',   'elsey-core' ) => 'img-left',
	            esc_html__( 'Right',  'elsey-core' ) => 'img-right',
	          ),
	          'description'      => esc_html__('Select your product layout.', 'elsey-core'),
	          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
	        ),
	        array(
	          'type'             => 'attach_image',
	          'heading'          => __('Upload Product Image', 'elsey-core'),
	          'param_name'       => 'pr_single_upload',
	          'value'            => '',
	          'description'      => __('Set your product image.', 'elsey-core'),
	          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        	),
        	 array(
      		  'type' 	           => 'textfield',
      		  'heading' 	       => esc_html__('Custom Height', 'elsey-core'),
      		  'param_name' 	     => 'pr_single_height',
      		  'description'	     => esc_html__('Set custom height for product single box in pixel.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		      ),
	      	array(
					  'type'             => 'notice',
					  'heading'          => esc_html__('Enable/Disable', 'elsey-core'),
					  'param_name'       => 'enable_opt',
					  'class'            => 'cs-info',
					  'value'            => '',
					),
	        array(
	          'type'             => 'switcher',
	          'heading'          => esc_html__('Price', 'elsey-core'),
	          'param_name'       => 'pr_single_price',
	          'value'            => '',
	          'std'              => true,
	          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
	        ),
	        array(
	          'type'             => 'switcher',
	          'heading'          => esc_html__('Categories', 'elsey-core'),
	          'param_name'       => 'pr_single_cats',
	          'value'            => '',
	          'std'              => true,
	          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
	        ),
	        array(
	          'type'             => 'switcher',
	          'heading'          => esc_html__('Featured Image', 'elsey-core'),
	          'param_name'       => 'pr_single_image',
	          'value'            => '',
	          'std'              => true,
	          'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
	        ),
	        
        	ElseyLib::elsey_class_option(),
	      )
	    ) );

		}
  }
}
