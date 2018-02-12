<?php
/**
 * Product - Shortcode Options
 */
if (class_exists('WooCommerce')) {
  add_action( 'vc_after_mapping', 'elsey_product_vc_map' );
}

if ( ! function_exists( 'elsey_product_vc_map' ) ) {
  function elsey_product_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__('Products', 'elsey-core'),
      'base'        => 'elsey_product',
      'description' => esc_html__('WooCommerce Products', 'elsey-core'),
      'icon'        => 'fa fa-shopping-cart color-slate-blue',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(
        
        array(
				  'type'             => 'notice',
				  'heading'          => esc_html__('Layout', 'elsey-core'),
				  'param_name'       => 'layout_opt',
				  'class'            => 'cs-info',
				  'value'            => '',
				),		
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Product Style', 'elsey-core'),
          'value'            => array(
            esc_html__( 'Default', 'elsey-core' )   => 'shop-default',
            esc_html__( 'Masonry', 'elsey-core' )   => 'shop-masonry',
            esc_html__( 'Full Grid', 'elsey-core' ) => 'shop-fullgrid',
          ),
          'admin_label'      => true,
          'param_name'       => 'pr_style',
          'description'      => esc_html__('Select your product layout.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Title', 'elsey-core'),
          'param_name'       => 'pr_title',
          'value'            => '',
          'description'      => esc_html__('Enter the title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ), 
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Limit', 'elsey-core'),
          'param_name'       => 'pr_limit',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__('Enter the number of products to show.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Product Column', 'elsey-core'),
          'value'            => array(
            esc_html__( 'Three', 'elsey-core' ) => 'shop-col-3',
            esc_html__( 'Four', 'elsey-core' ) 	=> 'shop-col-4',
            esc_html__( 'Five', 'elsey-core' ) 	=> 'shop-col-5',
            esc_html__( 'Six', 'elsey-core' ) 	=> 'shop-col-6',
          ),
          'param_name'       => 'pr_column',
          'dependency'       => array(
            'element'        => 'pr_style',
            'value'          => array('shop-default', 'shop-fullgrid'),
          ),
          'description'      => esc_html__('Select your product column.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Masonry Column Style', 'elsey-core'),
          'value'            => array(
            esc_html__( 'One', 'elsey-core' ) => 'mscol-one',
            esc_html__( 'Two', 'elsey-core' ) => 'mscol-two',
          ),
          'param_name'       => 'pr_mscol',
          'description'      => esc_html__('Select your masonry column style.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'pr_style',
            'value'          => array('shop-masonry'),
          ),
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
          'heading'          => esc_html__('Result Count', 'elsey-core'),
          'param_name'       => 'pr_result',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Sorting Filter', 'elsey-core'),
          'param_name'       => 'pr_sort_by',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('View All Products Icon', 'elsey-core'),
          'param_name'       => 'pr_view_icon',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Pagination', 'elsey-core'),
          'param_name'       => 'pr_nav',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Pagination Style', 'elsey-core'),
          'value'            => array(
            esc_html__( 'Page Numbers', 'elsey-core' )  => 'els-pagination-one',
            esc_html__( 'Next/Previous', 'elsey-core' ) => 'els-pagination-two',
            esc_html__( 'Load More', 'elsey-core' )     => 'els-pagination-three',
          ),
          'param_name'       => 'pr_nav_style',
          'dependency'       => array(
            'element'        => 'pr_nav',
            'value'          => array('true'),
          ),
          'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
        ),
        array(
				  'type'             => 'notice',
				  'heading'          => esc_html__('Listing', 'elsey-core'),
				  'param_name'       => 'lsng_opt',
				  'class'            => 'cs-info',
				  'value'            => '',
				),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Order', 'elsey-core'),
          'value'            => array(
            esc_html__('Select Product Order', 'elsey-core') => '',
            esc_html__('Asending',             'elsey-core') => 'ASC',
            esc_html__('Desending',            'elsey-core') => 'DESC',
          ),
          'param_name'       => 'pr_order',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
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
          'param_name'       => 'pr_orderby',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Filter By', 'elsey-core'),
          'value'            => array(
          	esc_html__( 'Select Filter By', 'elsey-core' ) 	 => 'pr-filter-none',
          	esc_html__( 'Product IDs', 'elsey-core' ) 	     => 'pr-filter-ids',
            esc_html__( 'Product Categories', 'elsey-core' ) => 'pr-filter-cats',
          ),
          'param_name'       => 'pr_filter',
        ),
        array(
					'type'             => 'textfield',
					'heading'          => __( 'Show only products of certain ids?', 'js_composer' ),
					'param_name'       => 'pr_ids',
					'value'            => '',
					'description'      => esc_html__('Enter product IDs (comma separated).', 'elsey-core'),
					'dependency'       => array(
            'element'        => 'pr_filter',
            'value'          => array( 'pr-filter-ids' ),
          ),
				),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Show only products of certain categories?', 'elsey-core'),
          'param_name'       => 'pr_cats',
          'value'            => '',
          'description'      => esc_html__('Enter category SLUGS (comma separated).', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'pr_filter',
            'value'          => array( 'pr-filter-cats' ),
          ),
        ),
    		ElseyLib::elsey_class_option(),
    	)
  	) );
	
  }
}
