<?php
/**
 * Product Categories - Shortcode Options
 */

if (class_exists('WooCommerce')) {

  add_action( 'init', 'elsey_product_categories_vc_map' );

  if ( ! function_exists( 'elsey_product_categories_vc_map' ) ) {

    function elsey_product_categories_vc_map() {

      vc_map( array(
        'name'        => esc_html__('Categories', 'elsey-core'),
        'base'        => 'elsey_product_categories',
        'description' => esc_html__('WooCommerce Product Categories', 'elsey-core'),
        'icon'        => 'fa fa-th-large color-grey',
        'category'    => ElseyLib::elsey_cat_name(),
        'params'      => array(

          array(
            'type'             => 'notice',
            'heading'          => esc_html__('Layout', 'elsey-core'),
            'param_name'       => 'cat_lt_opt',
            'class'            => 'cs-info',
            'value'            => '',
  		    ),
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__('Style', 'elsey-core'),
            'param_name'       => 'cat_style',
            'value'            => array(
              esc_html__('Default', 'elsey-core') => 'cats_default',
              esc_html__('Masonry', 'elsey-core') => 'cats_masonry',
            ),
            'admin_label'      => true,
            'description'      => esc_html__('Select your style.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ),      		
          array(
      		  'type' 	           => 'textfield',
      		  'heading' 	       => esc_html__('Limit', 'elsey-core'),
      		  'param_name' 	     => 'cat_limit',
            'admin_label'      => true,
      		  'description'	     => esc_html__('Enter the number of product categories to display.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
      		),
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__('Columns', 'elsey-core'),
            'param_name'       => 'cat_columns',
            'description'      => esc_html__('Select number of columns.', 'elsey-core'),
            'value'            => array(
              esc_html__( 'Three', 'elsey-core' ) => 'col_3',
              esc_html__( 'Four',  'elsey-core' ) => 'col_4',
              esc_html__( 'Five',  'elsey-core' ) => 'col_5',
            ),
            'std'              => 'col_3',
            'dependency'       => array(
              'element'        => 'cat_style',
              'value'          => array('cats_default')
            ),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ),
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__('Spacing Style', 'elsey-core'),
            'param_name'       => 'cat_space',
            'description'      => esc_html__('Select masonry items spacing style.', 'elsey-core'),
            'value'            => array(
              esc_html__( 'Style One', 'elsey-core' )  => 'style_one',
              esc_html__( 'Style Two',  'elsey-core' ) => 'style_two',
            ),
            'dependency'       => array(
              'element'        => 'cat_style',
              'value'          => array('cats_masonry')
            ),
            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
          ),
          array(
            'type'             => 'dropdown',
            'heading'          => esc_html__('Height', 'elsey-core'),
            'param_name'       => 'cat_height',
            'value'            => array(
              esc_html__('Auto', 'elsey-core')   => 'cats_auto_height',
              esc_html__('Custom', 'elsey-core') => 'cats_min_height',
            ),
            'admin_label'      => true,
            'description'      => esc_html__('Select height for each category grid.', 'elsey-core'),
            'dependency'       => array(
              'element'        => 'cat_style',
              'value'          => array('cats_default')
            ),
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
          ),      
          array(
      		  'type' 	           => 'textfield',
      		  'heading' 	       => esc_html__('Custom Height', 'elsey-core'),
      		  'param_name' 	     => 'cat_min_height',
      		  'description'	     => esc_html__('Set custom height for each category grid in pixel.', 'elsey-core'),
            'dependency'       => array(
              'element'        => 'cat_height',
              'value'          => array('cats_min_height')
            ),
            'edit_field_class' => 'vc_col-md-12 vc_column els_field_space',
		      ),
          array(
            'type'             => 'notice',
            'heading'          => esc_html__('Enable/Disable', 'elsey-core'),
            'param_name'       => 'cat_ed_opt',
            'class'            => 'cs-info',
            'value'            => '',
  		    ),
          array(
            'type'             => 'switcher',
            'heading'          => esc_html__('Product Count', 'elsey-core'),
            'param_name'       => 'cat_pr_count',
            'value'            => '',
            'std'              => false,
            'description'	     => esc_html__('Show product count of categories.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          ),
          array(
            'type'             => 'switcher',
            'heading'          => esc_html__('Description', 'elsey-core'),
            'param_name'       => 'cat_des',
            'value'            => '',
            'std'              => false,
            'description'	     => esc_html__('Show description of categories.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          ),
          array(
            'type'             => 'switcher',
            'heading' 		     => esc_html__('Hide Empty', 'elsey-core'),
            'param_name'       => 'cat_hide_empty',
            'value'            => '',
            'std'              => true,
            'admin_label'      => true,
            'description'	     => esc_html__('Hide empty categories.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          ),
      		array(
            'type'             => 'switcher',
            'heading' 		     => esc_html__('Parent', 'elsey-core'),
            'param_name' 	     => 'cat_parent',
            'value'            => '',
            'std'              => true,
            'description'	     => esc_html__('Only display top level categories.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
      		),
          array(
            'type'             => 'notice',
            'heading'          => esc_html__('Styling', 'elsey-core'),
            'param_name'       => 'cat_stl_opt',
            'class'            => 'cs-info',
            'value'            => '',
  		    ),
          array(
            'type' 			       => 'colorpicker',
            'heading' 		     => esc_html__('Background Color', 'elsey-core'),
            'param_name' 	     => 'cat_bg_color',
            'description'	     => esc_html__('Set a background color.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
          ),
          array(
            'type'             => 'colorpicker',
            'heading'          => esc_html__('Title Color', 'elsey-core'),
            'param_name'       => 'cat_title_color',
            'value'            => '',
            'description'      => esc_html__('Set a category title text color.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
          ),
          array(
            'type'             => 'colorpicker',
            'heading'          => esc_html__('Description Text Color', 'elsey-core'),
            'param_name'       => 'cat_desc_color',
            'value'            => '',
            'description'      => esc_html__('Set a category description text color.', 'elsey-core'),
            'edit_field_class' => 'vc_col-md-4 vc_column els_field_space',
          ),
          array(
            'type'             => 'notice',
            'heading'          => esc_html__('Listing', 'elsey-core'),
            'param_name'       => 'cat_lst_opt',
            'class'            => 'cs-info',
            'value'            => '',
  		    ),
      		array(
      		  'type' 			       => 'dropdown',
      		  'heading' 		     => esc_html__('Order', 'elsey-core'),
      		  'param_name' 	     => 'cat_order',
      		  'description'	     => esc_html__('Select categories order.', 'elsey-core'),
      		  'value'			       => array(
              esc_html__('Select Order', 'elsey-core') => 'none',
              esc_html__('Asending',  'elsey-core')    => 'ASC',
              esc_html__('Desending', 'elsey-core')    => 'DESC',
      		  ),
      		  'std'			         => 'none',
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
      		),
          array(
      		  'type' 		         => 'dropdown',
      		  'heading'          => esc_html__('Order By', 'elsey-core'),
      		  'param_name' 	     => 'cat_orderby',
      		  'description'	     => esc_html__('Select categories order-by.', 'elsey-core'),
      		  'value'	           => array(
              esc_html__('Select Order By', 'elsey-core')  => 'none',
        			esc_html__('ID', 'elsey-core')	  => 'ID',
              esc_html__('Count', 'elsey-core') => 'count',
        			esc_html__('Name', 'elsey-core')	=> 'title',
        			esc_html__('Slug', 'elsey-core')	=> 'slug',
      		  ),
      		  'std'			         => 'none',
            'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
		      ),
      		array(
            'type' 			       => 'textfield',
            'heading' 		     => esc_html__('IDs', 'elsey-core'),
            'param_name' 	     => 'cat_ids',
            'description'	     => esc_html__('Filter categories by entering a comma separated list of IDs.', 'elsey-core'),
            'admin_label'      => true,
      		),

          ElseyLib::elsey_class_option(),
        )
      ) );
	  }
  }
}
