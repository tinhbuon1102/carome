<?php
/**
 * Product List- Shortcode Options
 */
if (class_exists('WooCommerce')) {

  add_action( 'init', 'elsey_product_list_vc_map' );

  if ( ! function_exists( 'elsey_product_list_vc_map' ) ) {

  	function elsey_product_list_vc_map() {

	    vc_map( array(
	      'name'        => esc_html__( 'Product List', 'elsey-core' ),
	      'base'        => 'elsey_product_list',
	      'description' => esc_html__( 'WooCommerce Product List', 'elsey-core' ),
	      'icon'        => 'fa fa-list color-olive',
	      'category'    => ElseyLib::elsey_cat_name(),
	      'params'      => array(

	      	array(
					  'type'             => 'notice',
					  'heading'          => esc_html__( 'Layout', 'elsey-core' ),
					  'param_name'    	 => 'layout_opt',
					  'class'            => 'cs-info',
					  'value'            => '',
					),
	      	array(
	          'type'             => 'dropdown',
	          'heading'          => esc_html__( 'List Type', 'elsey-core' ),   
	          'param_name'       => 'pr_list_style',
	          'value'            => array(
	          	esc_html__( 'Best Selling Products', 'elsey-core' ) => 'pr-bestsell',
	            esc_html__( 'Featured Products', 'elsey-core' )     => 'pr-featured',
	            esc_html__( 'Random Products', 'elsey-core' )       => 'pr-random',
	            esc_html__( 'Recent Products', 'elsey-core' )       => 'pr-recent',
	            esc_html__( 'Sales Products', 'elsey-core' )        => 'pr-onsales',
	            esc_html__( 'Top Rated Products', 'elsey-core' )    => 'pr-toprated',
	          ),
	          'description'      => esc_html__('Select your product list type.', 'elsey-core'),
	        ),
	        array(
	          'type'             => 'textfield',
	          'heading'          => esc_html__('Limit', 'elsey-core'),
	          'param_name'       => 'pr_list_limit',
	          'value'            => '',
	          'admin_label'      => true,
	          'description'      => esc_html__('Enter the number of products to show.', 'elsey-core'),
        	),
	        array(
	          'type'             => 'dropdown',
	          'heading'          => esc_html__('Order', 'elsey-core'),
	          'value'            => array(
	            esc_html__('Select Product Order', 'elsey-core') => '',
	            esc_html__('Asending', 'elsey-core')  => 'ASC',
	            esc_html__('Desending', 'elsey-core') => 'DESC',
	          ),
	          'param_name'       => 'pr_list_order',
	          'dependency'       => array(
	            'element'        => 'pr_list_style',
	            'value'          => array( 'pr-featured', 'pr-random', 'pr-recent', 'pr-onsales', 'pr-toprated' ),
          	),
	        ),
	        array(
	          'type'             => 'dropdown',
	          'heading'          => esc_html__('Order By', 'elsey-core'),
	          'value'            => array(
	            esc_html__('None', 'elsey-core')     => 'none',
	            esc_html__('ID', 'elsey-core')       => 'ID',
	            esc_html__('Author', 'elsey-core')   => 'author',
	            esc_html__('Title', 'elsey-core')    => 'title',
	            esc_html__('Name', 'elsey-core')     => 'name',
	            esc_html__('Date', 'elsey-core')     => 'date',
	            esc_html__('Modified', 'elsey-core') => 'modified',
	            esc_html__('Random', 'elsey-core')   => 'rand',
	            esc_html__('Comment Count', 'elsey-core') => 'comment_count',
	          ),
	          'param_name'       => 'pr_list_orderby',
	          'dependency'       => array(
	            'element'        => 'pr_list_style',
	            'value'          => array( 'pr-featured', 'pr-onsales', 'pr-toprated' ),
          	),
	        ),
        	ElseyLib::elsey_class_option(),

	      )
	    ) );

		}
  }
}
