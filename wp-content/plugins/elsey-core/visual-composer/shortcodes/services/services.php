<?php
/* ==========================================================
  Services
=========================================================== */
if ( !function_exists('elsey_service_function')) {

  function elsey_service_function( $atts, $content = true ) {

    extract(shortcode_atts(array(
      'service_style'       => '',
      'service_image'       => '',
      'service_icon'        => '',
      'service_number'      => '',
      'service_title'       => '',
      'read_more_link'      => '',
      'read_more_title'     => '',
      'open_link'           => '',
      'class'               => '',
      'service_style_types'          => '',
      'service_style_align'  =>'',
      'service_style_three_align' => '',
      // Style
      'title_color'         => '',
      'title_size'          => '',
      'title_font_weight'   => '',
      'title_top_space'     => '',
      'title_bottom_space'  => '',
      'content_color'  => '',
      'content_size'  => '',
      'icon_color' => '',
      'icon_size' => '',
      'number_color'  =>'',
      'number_size' =>'',
      'read_more_color' => '',
      'read_more_size' => '',
      'read_more_hover_color' => '',
      'css' => '',
    ), $atts));

    // Design Tab
    $custom_css = ( function_exists( 'vc_shortcode_custom_css_class' ) ) ? vc_shortcode_custom_css_class( $css, ' ' ) : '';

    // Fix unclosed/unwanted paragraph tags in $content
    $content = wpb_js_remove_wpautop($content, true);

    // Style
    $title_color     = $title_color ? 'color:' . $title_color . ';' : '';
    $title_size      = $title_size ? 'font-size:' . elsey_check_px($title_size) . ';' : '';
    $title_top_space = $title_top_space ? 'margin-top:' . elsey_check_px($title_top_space) . ';' : '';
    $title_btm_space = $title_bottom_space ? 'margin-bottom:' . elsey_check_px($title_bottom_space) . ';' : '';


        // Shortcode Style CSS
    $e_uniqid     = uniqid();
    $inline_style = '';

    // Font Weight
    if ( $title_font_weight ) {
      $inline_style .= '.els-service-'. $e_uniqid .' .els-service-content .els-service-heading h3 {';
      $inline_style .= ( $title_font_weight ) ? 'font-weight:'. $title_font_weight .';' : '';
      $inline_style .= '}';
    }
      // Content Style
    if ( $content_color || $content_size ) {
      $inline_style .= '.els-service-'. $e_uniqid .' .quality-section-right p, .els-service-'. $e_uniqid .'.els-service .els-service-content p, .els-service-'. $e_uniqid .'.elsy-service .elsy-service-benefits p {';
      $inline_style .= ( $content_color ) ? 'color:'. $content_color .';' : '';
      $inline_style .= ( $content_size ) ? 'font-size:'. elsey_check_px($content_size) .';' : '';
      $inline_style .= '}';
    }
      // Icon color
    if ( $icon_color || $icon_size ) {
      $inline_style .= '.els-service-'. $e_uniqid .' .els-service-view i, .els-service-'. $e_uniqid .' .els-service-icon i {';
      $inline_style .= ( $icon_color ) ? 'color:'. $icon_color .';' : '';
      $inline_style .= ( $icon_size ) ? 'font-size:'. elsey_check_px($icon_size) .';' : '';
      $inline_style .= '}';
    }

    // Number color
    if ( $number_color || $number_size ) {
      $inline_style .= '.els-service-'. $e_uniqid .'.els-service .elsy-num {';
      $inline_style .= ( $number_color ) ? 'color:'. $number_color .';' : '';
      $inline_style .= ( $number_color ) ? 'border-color:'. $number_color .';' : '';
      $inline_style .= ( $number_size ) ? 'font-size:'. elsey_check_px($number_size) .';' : '';
      $inline_style .= '}';
    }

      // Read More Style
    if ( $read_more_color || $read_more_size ) {
      $inline_style .= '.els-service-'. $e_uniqid .'.els-service .els-service-read-more {';
      $inline_style .= ( $read_more_color ) ? 'color:'. $read_more_color .';' : '';
      $inline_style .= ( $read_more_size ) ? 'font-size:'. elsey_check_px($read_more_size) .';' : '';
      $inline_style .= '}';
    }
       // Read More Hover Style
    if ( $read_more_hover_color ) {
      $inline_style .= '.els-service-'. $e_uniqid .'.els-service .els-service-read-more:hover {';
      $inline_style .= ( $read_more_hover_color ) ? 'color:'. $read_more_hover_color .';' : '';
      
      $inline_style .= '}';
    }
    
    

    // add inline style
    add_inline_style( $inline_style );
    $styled_class = ' els-service-'. $e_uniqid;



    // Link Target
    $open_link      = ($open_link) ? 'target="_blank"' : '';
    $read_more_link = ($read_more_link) ? '<a href="'.esc_url($read_more_link).'" class="els-service-read-more" '.$open_link.'>'.esc_attr($read_more_title).'</a>' : '';

    // Alignment
    if ($service_style_types === 'els-style-two') {
      if($service_style_align === 'els-left') {
         $align_class = ' service-align-left';
      } elseif ($service_style_align === 'els-right') {
         $align_class = ' service-align-right';
      } else {$align_class = '';} 
    } 

    // Service Three Alignment
    if ($service_style_types === 'els-style-three') {
      if($service_style_three_align === 'els-left') {
         $align_class = ' service-align-left';
      } else {
         $align_class = ' service-align-right';
      } 
    } 

     // Service Icon
    if ($service_style === 'els-service-two') {
      $service_image = ($service_icon) ? '<div class="els-service-icon els-service-view"><i class="'. $service_icon .'"></i></div>' : '';
    } elseif ($service_style === 'els-service-three'){
      $service_image = ($service_number) ? ' <span class="elsy-num">'. $service_number .'</span>' : '';
    
    } else {
        $image_url     = wp_get_attachment_url( $service_image );
      $service_image = ($image_url) ? '<div class="els-service-img els-service-view"><img src="'.esc_url($image_url).'" alt=""></div>' : '';
    }


    // Service Title
    if ( function_exists( 'vc_parse_multi_attribute' ) ) {
      $parse_args = vc_parse_multi_attribute( $service_title );
      $url        = ( isset( $parse_args['url'] ) ) ? $parse_args['url'] : '';
      $title      = ( isset( $parse_args['title'] ) ) ? $parse_args['title'] : '';
      $target     = ( isset( $parse_args['target'] ) ) ? trim( $parse_args['target'] ) : '_self';
    }
    
    $service_title = '';

    if ($url) {
      $service_title = '<div class="els-service-heading" style="'. $title_top_space . $title_btm_space .'">';
      $service_title.= '<h3><a href="'.$url.'" target="'.$target.'" style="'. $title_color . $title_size .'">'. esc_attr($title) .'</a></h3></div>';
    } elseif ($title) {
      $service_title = '<div class="els-service-heading" style="'. $title_top_space . $title_btm_space .'">';
      $service_title.= '<h3 style="'. $title_color . $title_size .'">'. esc_attr($title) .'</h3></div>';
    } else {
      $service_title = '';
    }
if ($service_style_types === 'els-style-two') {
    $output = '<div class="quality-section-right '.$styled_class. $align_class .' " >
                <div class="elsy-quality-column '. $custom_css .'">
                  <div class="elsy-quality-icon">
                    '. $service_image .'
                  </div>
                  <div class="quality-right-title">
                    '.$service_title.'
                  </div>
                  <div class="quality-content-right">
                    '. $content . $read_more_link .'
                  </div>    
                </div>
              </div>';
  } elseif ($service_style_types === 'els-style-three') {
      $output = '<div class="elsy-app-benefites elsy-service '. $styled_class . $align_class .'">
            <div class="elsy-app-icon">
              '. $service_image .'
            </div>
            <div class="elsy-service-benefits">
              <div class="elsy-service-name">'.$service_title.'</div>
              '. $content .'
            </div>
          </div>';
  }
  else {
    $output = '<div class="els-service '. $styled_class .'">'. $service_image .'<div class="els-service-content">'. $service_title . $content . $read_more_link .'</div></div>';
   }
    return $output;
  }
}
add_shortcode( 'elsey_service', 'elsey_service_function' );
