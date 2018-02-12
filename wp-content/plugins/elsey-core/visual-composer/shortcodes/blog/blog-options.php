<?php
/**
 * Blog - Shortcode Options
 */
add_action( 'init', 'elsey_blog_vc_map' );

if ( ! function_exists( 'elsey_blog_vc_map' ) ) {
    
  function elsey_blog_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__( 'Blog', 'elsey-core'),
      'base'        => 'elsey_blog',
      'description' => esc_html__( 'Blog Styles', 'elsey-core'),
      'icon'        => 'fa fa-th color-red',
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
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Blog Style', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Standard', 'elsey-core' ) => 'els-blog-one',
            esc_html__( 'Masonry', 'elsey-core' )  => 'els-blog-two',
            esc_html__( 'Slider', 'elsey-core' )   => 'els-blog-three',
          ),
          'admin_label'      => true,
          'param_name'       => 'blog_style',
          'description'      => esc_html__( 'Select your blog style.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Limit', 'elsey-core'),
          'param_name'       => 'blog_limit',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter the number of items to show.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ), 
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Columns', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Column One', 'elsey-core' )   => 'els-blog-col-1',
            esc_html__( 'Column Two', 'elsey-core' )   => 'els-blog-col-2',
            esc_html__( 'Column Three', 'elsey-core' ) => 'els-blog-col-3',
          ),
          'admin_label'      => true,
          'param_name'       => 'blog_columns',
          'description'      => esc_html__( 'Select your blog column.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-one', 'els-blog-two' ),
          ),
        ),  
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Title', 'elsey-core' ),
          'param_name'       => 'blog_title',
          'value'            => '',
          'description'      => esc_html__( 'Enter the title.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),      
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Enable/Disable', 'elsey-core' ),
          'param_name'       => 'ed_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),  
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Category', 'elsey-core' ),
          'param_name'       => 'blog_category',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Date', 'elsey-core' ),
          'param_name'       => 'blog_date',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),            
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Excerpt', 'elsey-core' ), 
          'param_name'       => 'blog_excerpt',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Read More', 'elsey-core' ),
          'param_name'       => 'blog_read_more',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Author', 'elsey-core'),
          'param_name'       => 'blog_author',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-one', 'els-blog-two' ),
          ),
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Share', 'elsey-core' ),
          'param_name'       => 'blog_share',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-one', 'els-blog-two' ),
          ),
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Image Popup', 'elsey-core' ),
          'param_name'       => 'blog_popup',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-one', 'els-blog-two' ),
          ),
        ),   
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__( 'Pagination', 'elsey-core' ),
          'param_name'       => 'blog_pagination',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-one', 'els-blog-two' ),
          ),
        ), 
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Excerpt Length', 'elsey-core' ),
          'param_name'       => 'blog_excerpt_length',
          'value'            => '',
          'description'      => esc_html__( 'Enter blog short content length.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'blog_excerpt',
            'value'          => array('true'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),               
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Read More Button Text', 'elsey-core' ),
          'param_name'       => 'blog_read_more_text',
          'value'            => '',
          'description'      => esc_html__( 'Enter read more button text.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'blog_read_more',
            'value'          => array('true'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),      
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Pagination Style', 'elsey-core'),
          'value'            => array(
            esc_html__( 'Page Numbers', 'elsey-core' )  => 'els-pagination-one',
            esc_html__( 'Previous/Next', 'elsey-core' ) => 'els-pagination-two',
            esc_html__( 'Load More', 'elsey-core' )     => 'els-pagination-three',
          ),
          'param_name'       => 'blog_pagination_style',
          'description'      => esc_html__( 'Select blog pagination style.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'blog_pagination', 
            'value'          => array('true'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),    
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Pagination Alignment', 'elsey-core'),
          'value'            => array(
            esc_html__( 'Select Alignment', 'elsey-core' )   => '',
            esc_html__( 'Left', 'elsey-core' )   => 'els-pagenavi-left',
            esc_html__( 'Right', 'elsey-core' )  => 'els-pagenavi-right',
            esc_html__( 'Center', 'elsey-core' ) => 'els-pagenavi-center',
          ),
          'param_name'       => 'blog_pagination_align',
          'description'      => esc_html__( 'Select blog pagination alignment style.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'blog_pagination_style', 
            'value'          => array('els-pagination-one'),
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),     
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Loop', 'elsey-core'),
          'param_name'       => 'blog_slider_loop',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Inifnity loop.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),                    
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Navigation', 'elsey-core'),
          'param_name'       => 'blog_slider_nav',
          'value'            => '',
          'std'              => true,
          'description'      => esc_html__( 'Show next/prev buttons.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Dots', 'elsey-core'),
          'param_name'       => 'blog_slider_dots',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Show dots navigation.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),                       
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Autoplay', 'elsey-core'),
          'param_name'       => 'blog_slider_autoplay',
          'value'            => '',
          'std'              => false,
          'description'      => esc_html__( 'Start Autoplay.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-3 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),     
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Navigation Speed', 'elsey-core' ),
          'param_name'       => 'blog_slider_nav_speed',
          'description'      => esc_html__( 'Enter navigation speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'       => array(
                'element'        => 'nav',
                'value'          => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Dots Speed', 'elsey-core' ),
          'param_name'       => 'blog_slider_dots_speed',
          'description'      => esc_html__( 'Enter dots speed(value is in ms, enter numbers only).', 'elsey-core' ),
              'dependency'       => array(
                'element'        => 'dots',
                'value'          => 'true',
              ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ),        
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__( 'Autoplay Speed', 'elsey-core' ),
          'param_name'       => 'blog_slider_ap_speed',
          'description'      => esc_html__( 'Enter autoplay speed(value is in ms, enter numbers only).', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'autoplay',
            'value'          => 'true',
          ),
          'edit_field_class' => 'vc_col-sm-4 vc_column els_field_space',
          'dependency'       => array(
            'element'        => 'blog_style',
            'value'          => array( 'els-blog-three' ),
          ),
        ), 
        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Listing', 'elsey-core' ),
          'param_name'       => 'lsng_opt',
          'class'            => 'cs-info',
          'value'            => '',
		    ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Order', 'elsey-core' ),
          'value'            => array(
            esc_html__('Select Blog Order', 'elsey-core') => '',
            esc_html__('Asending', 'elsey-core')          => 'ASC',
            esc_html__('Desending', 'elsey-core')         => 'DESC',
          ),
          'param_name'       => 'blog_order',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Order By', 'elsey-core' ),
          'value'            => array(
            esc_html__('None', 'elsey-core')   => 'none',
            esc_html__('ID', 'elsey-core')     => 'ID',
            esc_html__('Author', 'elsey-core') => 'author',
            esc_html__('Title', 'elsey-core')  => 'title',
            esc_html__('Date', 'elsey-core')   => 'date',
          ),
          'param_name'       => 'blog_orderby',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Show only certain categories?', 'elsey-core'),
          'param_name'       => 'blog_category_slugs',
          'value'            => '',
          'description'      => esc_html__( 'Enter category SLUGS (comma separated) you want to display.', 'elsey-core')
        ),
          
        ElseyLib::elsey_class_option(),

      )
    ) );
  }
}