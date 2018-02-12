<?php
/* ==========================================================
  Client Carousel
=========================================================== */
if ( !function_exists('elsy_tab_mobile_func')) {
  function elsy_tab_mobile_func( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'title'  => '',
      'sec_content'  => '',
      'btn_txt'  => '',
      'btn_link'  => '',
      'open_link'  => '',
      'tab_mobiles'  => '',
      'class'  => '',
    ), $atts));

    // Link Target
    $open_link = $open_link ? 'target="_blank"' : '';
    $sec_title = $title ? '<h2>'.$title.'</h2>' : '';
    $sec_content = $sec_content ? '<p>'.$sec_content.'</p>' : '';
    $btn_link = $btn_link ? '<a href="'.$btn_link.'" class="elsy-btn elsy-btn-dwnld">'.$btn_txt.'</a>' : '<span class="elsy-btn elsy-btn-dwnld">'.$btn_txt.'</span>' ;
    $btn_txt = $btn_txt ? $btn_link : '';


    // Group Field
    $tab_mobiles = (array) vc_param_group_parse_atts( $tab_mobiles );
    $get_tab_mobiles = array();
    foreach ( $tab_mobiles as $tab_mobile ) {
      $each_tab = $tab_mobile;
      $each_tab['tab_title'] = isset( $tab_mobile['tab_title'] ) ? $tab_mobile['tab_title'] : '';
      $each_tab['tab_content'] = isset( $tab_mobile['tab_content'] ) ? $tab_mobile['tab_content'] : '';
      $each_tab['tab_image'] = isset( $tab_mobile['tab_image'] ) ? $tab_mobile['tab_image'] : '';
      $get_tab_mobiles[] = $each_tab;
    }

    $output = '<div class="col-md-6 elsey-event-tab"><div class="elsy-slider-title pull-left">'.$sec_title.$sec_content.$btn_txt.'</div>';
    $output .= '<div class="pull-left"><ul class="nav nav-tabs nav-tabs-left nav-centered" role="tablist">';
    // Group Param Output
    $s = 0;
    foreach ( $get_tab_mobiles as $each_tab ) {
      $output .= '<li role="presentation"><a href="#'.sanitize_title_with_dashes($each_tab['tab_title']).'-'.$s.'" data-toggle="tab" role="tab">';
       if ($each_tab['tab_title']) {
        $output .= '<h2>'. $each_tab['tab_title'] .'</h2>';
      } 
      if ($each_tab['tab_content']) {
        $output .= '<p>'. $each_tab['tab_content'] .'</p>';
      } 
      $s++;
    }

    $output .= '</li></a></ul></div></div>';

    $output .= '<div class="col-md-6 elsey-event-tab"><div id="my_side_tabs" class="tab-content event-tabs side-tabs side-tabs-left">';

    $s = 0;
    foreach ( $get_tab_mobiles as $each_tab ) {
      $image_url = wp_get_attachment_url( $each_tab['tab_image'] );

       if ($image_url) {
        $output .= '<div class="tab-pane fade in" id="'.sanitize_title_with_dashes($each_tab['tab_title']).'-'.$s.'" role="tabpanel"><div class="elsy-tab-image"><img src="'.$image_url.'" alt=""></div></div>';
      } 
      $s++;
    }

    $output .= '</div></div>';

    return $output;
  }
}
add_shortcode( 'elsy_tab_mobile', 'elsy_tab_mobile_func' );
           