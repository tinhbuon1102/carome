<?php
/* ===========================================================
    btn
=========================================================== */
if ( !function_exists('elsey_button_function')) {
  
  function elsey_button_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'btn_text'             => '',
      'btn_link'             => '',
      'btn_target'           => '',
      'btn_hover'            => '',
      
      // Styling
      'btn_text_size'        => '',
      'btn_align'            => '',
      'btn_text_color'       => '',
      'btn_text_hover_color' => '',
      'btn_bg_hover_color'   => '',
      'btn_br_hover_color'   => '',
      'class'                => '',
      'btn_css'              => ''
    ), $atts));

    // Design Tab
    $custom_css = ( function_exists( 'vc_shortcode_custom_css_class' ) ) ? vc_shortcode_custom_css_class( $btn_css, ' ' ) : '';

    $btn_class = 'els-btn-dt';
    $btn_style = ( !empty( $btn_align ) ) ? 'style="text-align:'. $btn_align .';"' : '';

    // Shortcode Style CSS
    $e_uniqid     = uniqid();
    $inline_style = '';

    // Text Size
    if ( $btn_text_size ) {
      $inline_style .= '.els-btn-'. $e_uniqid .' {';
      $inline_style .= ( $btn_text_size ) ? 'font-size:'. elsey_check_px($btn_text_size) .';' : '';
      $inline_style .= '}';
    }
    // btn Color
    if ( $btn_text_color ) {
      $inline_style .= '.els-btn-'. $e_uniqid .' .btn-text {';
      $inline_style .= ( $btn_text_color ) ? 'color:'. $btn_text_color .';' : '';
      $inline_style .= '}';
    }

    if ( $btn_text_hover_color ) {
      $inline_style .= '.els-btn-'. $e_uniqid .':hover .btn-text, .els-btn-'. $e_uniqid .':focus .btn-text, .els-btn-'. $e_uniqid .':active .btn-text {';
      $inline_style .= ( $btn_text_hover_color ) ? 'color:'. $btn_text_hover_color .' !important;' : '';
      $inline_style .= '}';
    }

    if ( $btn_bg_hover_color || $btn_br_hover_color ) {
      $inline_style .= '.els-btn-'. $e_uniqid .':hover, .els-btn-'. $e_uniqid .':focus, .els-btn-'. $e_uniqid .':active {';
      $inline_style .= ( $btn_bg_hover_color ) ? 'background: '. $btn_bg_hover_color .' !important;' : '';
      $inline_style .= ( $btn_br_hover_color ) ? 'border-color: '. $btn_br_hover_color .' !important;' : '';
      $inline_style .= '}';
    }

    // add inline style
    add_inline_style( $inline_style );
    $styled_class = ' els-btn-'. $e_uniqid;

    // Styling
    $btn_text   = ($btn_text) ? '<span class="btn-text">'. $btn_text .'</span>' : '';
    $btn_link   = ($btn_link) ? 'href="'. esc_url($btn_link) .'"' : '';
    $btn_target = ($btn_target) ? ' target="_blank"' : '';
    $btn_hover  = ($btn_hover) ? '' : ' btn-hover-one';
    
    $output = '<div class="'.$btn_class.'" '.$btn_style.'><a class="els-btn'. $custom_css . $styled_class . $btn_hover .' '. $class .'" '. $btn_link . $btn_target .'>'. $btn_text .'</a></div>';

    return $output;

  }
}

add_shortcode( 'elsey_button', 'elsey_button_function' );
