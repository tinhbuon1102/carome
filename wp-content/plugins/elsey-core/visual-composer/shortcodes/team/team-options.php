<?php
/**
 * Team - Shortcode Options
 */
add_action( 'init', 'elsey_team_vc_map' );

if ( ! function_exists( 'elsey_team_vc_map' ) ) {
    
  function elsey_team_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__('Team', 'elsey-core'),
      'base'        => 'elsey_team',
      'description' => esc_html__('Team Styles', 'elsey-core'),
      'icon'        => 'fa fa-users color-fungreen',
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
          'heading'          => esc_html__('Title', 'elsey-core'),
          'param_name'       => 'team_title',
          'value'            => '',
          'description'      => esc_html__( 'Enter the title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),      
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Sub Title', 'elsey-core'),
          'param_name'       => 'team_sub_title',
          'value'            => '',
          'description'      => esc_html__( 'Enter the sub-title.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),      
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Columns', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Column One', 'elsey-core' )   => 'els-team-col-1',
            esc_html__( 'Column Two', 'elsey-core' )   => 'els-team-col-2',
            esc_html__( 'Column Three', 'elsey-core' ) => 'els-team-col-3',
          ),
          'admin_label'      => true,
          'param_name'       => 'team_columns',
          'description'      => esc_html__( 'Select your team column.', 'elsey-core' ),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Limit', 'elsey-core'),
          'param_name'       => 'team_limit',
          'value'            => '',
          'admin_label'      => true,
          'description'      => esc_html__( 'Enter the number of members to show.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
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
          'heading'          => esc_html__('Member Details', 'elsey-core'),
          'param_name'       => 'team_member_details',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Member Job Position', 'elsey-core'),
          'param_name'       => 'team_member_job',
          'value'            => '',
          'std'              => true,
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
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
            esc_html__('Select Team Order', 'elsey-core')   => '',
            esc_html__('Asending', 'elsey-core')            => 'ASC',
            esc_html__('Desending', 'elsey-core')           => 'DESC',
          ),
          'param_name'       => 'team_order',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Order By', 'elsey-core' ),
          'value'            => array(
            esc_html__('None',  'elsey-core')  => 'none',
            esc_html__('ID',    'elsey-core')  => 'ID',
            esc_html__('Title', 'elsey-core')  => 'title',
            esc_html__('Date',  'elsey-core')  => 'date',
          ),
          'param_name'       => 'team_orderby',
          'edit_field_class' => 'vc_col-md-6 vc_column els_field_space',
        ),      
              
        ElseyLib::elsey_class_option(),
        
      )
    ) );
    
  }
}
