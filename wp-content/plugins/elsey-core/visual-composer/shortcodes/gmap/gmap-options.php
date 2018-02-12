<?php
/**
 * Gmap - Shortcode Options
 */
add_action( 'init', 'elsey_gmap_vc_map' );

if ( ! function_exists( 'elsey_gmap_vc_map' ) ) {
    
  function elsey_gmap_vc_map() {
    
    vc_map( array(
      'name'        => esc_html__('Google Map', 'elsey-core'),
      'base'        => 'elsey_gmap',
      'description' => esc_html__('Google Map Styles', 'elsey-core'),
      'icon'        => 'fa fa-map color-cadetblue',
      'category'    => ElseyLib::elsey_cat_name(),
      'params'      => array(

        array(
          'type'             => 'notice',
          'heading'          => esc_html__('API KEY', 'elsey-core'),
          'param_name'       => 'api_key',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Enter Map ID', 'elsey-core'),
          'param_name'       => 'gmap_id',
          'value'            => '',
          'description'      => __( 'Enter google map ID. If you\'re using this in <strong>Map Tab</strong> shortcode, enter unique ID for each map tabs. Else leave it as blank. <strong>Note : This should same as Tab ID in Map Tabs shortcode.</strong>', 'elsey-core'),
          'admin_label'      => true,
        ),
        array(
          'type'             => 'textfield',
          'heading'          => esc_html__('Enter your Google Map API Key', 'elsey-core'),
          'param_name'       => 'gmap_api',
          'value'            => '',
          'description'      => __( 'New Google Maps usage policy dictates that everyone using the maps should register for a free API key. <br />Please create a key for "Google Static Maps API" and "Google Maps Embed API" using the <a href="https://console.developers.google.com/project" target="_blank">Google Developers Console</a>.<br /> Or follow this step links : <br /><a href="https://console.developers.google.com/flows/enableapi?apiid=maps_embed_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">1. Step One</a> <br /><a href="https://console.developers.google.com/flows/enableapi?apiid=static_maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">2. Step Two</a><br /> If you still receive errors, please check following link : <a href="https://churchthemes.com/2016/07/15/page-didnt-load-google-maps-correctly/" target="_blank">How to Fix?</a>', 'elsey-core'),
        ),

        array(
          'type'             => 'notice',
          'heading'          => esc_html__( 'Map Settings', 'elsey-core' ),
          'param_name'       => 'map_settings',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Google Map Type', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Select Type', 'elsey-core' ) => '',
            esc_html__( 'ROADMAP', 'elsey-core' )     => 'ROADMAP',
            esc_html__( 'SATELLITE', 'elsey-core' )   => 'SATELLITE',
            esc_html__( 'HYBRID', 'elsey-core' )      => 'HYBRID',
            esc_html__( 'TERRAIN', 'elsey-core' )     => 'TERRAIN',
          ),
          'admin_label'      => true,
          'param_name'       => 'gmap_type',
          'description'      => esc_html__( 'Select your google map type.', 'elsey-core' ),
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__( 'Google Map Style', 'elsey-core' ),
          'value'            => array(
            esc_html__( 'Select Style', 'elsey-core' )            => '',
            esc_html__( 'Gray Scale', 'elsey-core' )              => 'gray-scale',
            esc_html__( 'Mid Night', 'elsey-core' )               => 'mid-night',
            esc_html__( 'Blue Water', 'elsey-core' )              => 'blue-water',
            esc_html__( 'Light Dream', 'elsey-core' )             => 'light-dream',
            esc_html__( 'Pale Dawn', 'elsey-core' )               => 'pale-dawn',
            esc_html__( 'Apple Maps-esque', 'elsey-core' )        => 'apple-maps',
            esc_html__( 'Blue Essence', 'elsey-core' )            => 'blue-essence',
            esc_html__( 'Unsaturated Browns', 'elsey-core' )      => 'unsaturated-browns',
            esc_html__( 'Paper', 'elsey-core' )                   => 'paper',
            esc_html__( 'Midnight Commander', 'elsey-core' )      => 'midnight-commander',
            esc_html__( 'Light Monochrome', 'elsey-core' )        => 'light-monochrome',
            esc_html__( 'Flat Map', 'elsey-core' )                => 'flat-map',
            esc_html__( 'Retro', 'elsey-core' )                   => 'retro',
            esc_html__( 'becomeadinosaur', 'elsey-core' )         => 'becomeadinosaur',
            esc_html__( 'Neutral Blue', 'elsey-core' )            => 'neutral-blue',
            esc_html__( 'Subtle Grayscale', 'elsey-core' )        => 'subtle-grayscale',
            esc_html__( 'Ultra Light with Labels', 'elsey-core' ) => 'ultra-light-labels',
            esc_html__( 'Shades of Grey', 'elsey-core' )          => 'shades-grey',
          ),
          'admin_label'      => true,
          'param_name'       => 'gmap_style',
          'description'      => esc_html__( 'Select your google map style.', 'elsey-core' ),
          'dependency'       => array(
            'element'        => 'gmap_type',
            'value'          => 'ROADMAP',
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
        ),
        array(
          'type'             => 'dropdown',
          'heading'          => esc_html__('Zoom Level', 'elsey-core'),
          'param_name'       => 'gmap_zoom',
          'value'            => array(
            esc_html__( 'Select Zoom', 'elsey-core' )  => '',
            esc_html__( '1', 'elsey-core' )            => '1',
            esc_html__( '2', 'elsey-core' )            => '2',
            esc_html__( '3', 'elsey-core' )            => '3',
            esc_html__( '4', 'elsey-core' )            => '4',
            esc_html__( '5', 'elsey-core' )            => '5',
            esc_html__( '6', 'elsey-core' )            => '6',
            esc_html__( '7', 'elsey-core' )            => '7',
            esc_html__( '8', 'elsey-core' )            => '8',
            esc_html__( '9', 'elsey-core' )            => '9',
            esc_html__( '10', 'elsey-core' )           => '10',
            esc_html__( '11', 'elsey-core' )           => '11',
            esc_html__( '12', 'elsey-core' )           => '12',
            esc_html__( '13', 'elsey-core' )           => '13',
            esc_html__( '14', 'elsey-core' )           => '14',
            esc_html__( '15', 'elsey-core' )           => '15',
            esc_html__( '16', 'elsey-core' )           => '16',
            esc_html__( '17', 'elsey-core' )           => '17',
            esc_html__( '18', 'elsey-core' )           => '18',
          ),
          'description'      => esc_html__( 'Select your google map zoom level.', 'elsey-core'),
          'dependency'       => array(
            'element'        => 'gmap_type',
            'value'          => 'ROADMAP',
          ),
          'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
        ),
        array(
          'type'             =>'textfield',
          'heading'          =>esc_html__('Height', 'elsey-core'),
          'param_name'       => 'gmap_height',
          'value'            => '',
          'description'      => esc_html__( 'Enter the px value for map height. This will not work if you add this shortcode into the Map Tab shortcode.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
        ),    
        array(
          'type'             => 'attach_image',
          'heading'          => esc_html__('Common Marker', 'elsey-core'),
          'param_name'       => 'gmap_common_marker',
          'value'            => '',
          'description'      => esc_html__( 'Upload your custom marker.', 'elsey-core'),
          'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
        ),

        array(
          'type'             => 'notice',
          'heading'          => esc_html__('Enable & Disable', 'elsey-core'),
          'param_name'       => 'enb_disb',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Scroll Wheel', 'elsey-core'),
          'param_name'       => 'gmap_scroll_wheel',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Street View Control', 'elsey-core'),
          'param_name'       => 'gmap_street_view',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
        ),
        array(
          'type'             => 'switcher',
          'heading'          => esc_html__('Map Type Control', 'elsey-core'),
          'param_name'       => 'gmap_maptype_control',
          'value'            => '',
          'std'              => false,
          'edit_field_class' => 'vc_col-md-4 vc_column elsey_field_space',
        ),

        // Map Markers
        array(
          'type'             => 'notice',
          'heading'          => esc_html__('Map Pins', 'elsey-core'),
          'param_name'       => 'map_pins',
          'class'            => 'cs-info',
          'value'            => '',
        ),
        array(
          'type'             => 'param_group',
          'value'            => '',
          'heading'          => esc_html__('Map Locations', 'elsey-core'),
          'param_name'       => 'locations',
          'params'           => array(
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__('Latitude', 'elsey-core'),
              'param_name'       => 'latitude',           
              'admin_label'      => true,
              'description'      => __( 'Find Latitude : <a href="http://www.latlong.net/" target="_blank">latlong.net</a>', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__('Longitude', 'elsey-core'),
              'param_name'       => 'longitude',            
              'admin_label'      => true,
              'description'      => __( 'Find Longitude : <a href="http://www.latlong.net/" target="_blank">latlong.net</a>', 'elsey-core'),
              'edit_field_class' => 'vc_col-md-6 vc_column elsey_field_space',
            ),
            array(
              'type'             => 'attach_image',
              'value'            => '',
              'heading'          => esc_html__('Custom Marker', 'elsey-core'),
              'param_name'       => 'custom_marker',
              'description'      => esc_html__('Upload your unique map marker if your want to differentiate from others.', 'elsey-core'),
            ),
            array(
              'type'             => 'textfield',
              'value'            => '',
              'heading'          => esc_html__('Heading', 'elsey-core'),
              'param_name'       => 'location_heading',
            ),
            array(
              'type'             => 'textarea',
              'value'            => '',
              'heading'          => esc_html__('Content', 'elsey-core'),
              'param_name'       => 'location_text',
            ),

          )
        ),

        ElseyLib::elsey_class_option(),

        // Design Tab
        array(
          'type'                => 'css_editor',
          'heading'             => esc_html__( 'Text Size', 'elsey-core' ),
          'param_name'          => 'css',
          'group'               => esc_html__( 'Design', 'elsey-core'),
        ),

      )
    ) );
  }
}
