<?php
/* ==========================================================
  Accordion
=========================================================== */
if( ! function_exists( 'elsey_vt_accordion_function' ) ) {
  function elsey_vt_accordion_function( $atts, $content = '', $key = '' ) {

    global $vt_accordion_tabs;
    $vt_accordion_tabs = array();

    extract( shortcode_atts( array(
      'accordion_style' => '',
      'id'            => '',
      'class'         => '',
      'one_active'    => '',
      'icon_color'    => '',
      'border_color'  => '',
      'active_tab'    => 0,
    ), $atts ) );

    do_shortcode( $content );

    // is not empty clients
    if( empty( $vt_accordion_tabs ) ) { return; }

    $id          = ( $id ) ? ' id="'. $id .'"' : '';
    $class       = ( $class ) ? ' '. $class : '';
    $one_active  = ( $one_active ) ? ' collapse-others' : '';
    $uniqtab     = uniqid();

    // Style
    if ($accordion_style === 'style-two') {
      $accordion_class = ' elsy-panel-two';
    } elseif($accordion_style === 'style-three') {
      $accordion_class = ' elsy-panel-three';
    } else {
      $accordion_class = ' elsy-panel-one';
    }

    $el_style    = ( $border_color ) ? ' style="border-color:'. $border_color .';"' : '';
    $icon_style  = ( $icon_color ) ? ' style="color:'. $icon_color .';"' : '';

    // begin output
    $output      = '<div class="accordion ' . $one_active . '" '. $id .' role="tablist" aria-multiselectable="true">';

    foreach ( $vt_accordion_tabs as $key => $tab ) {

      $selected  = ( ( $key + 1 ) == $active_tab ) ? ' in' : '';
      $opened    = ( ( $key + 1 ) == $active_tab ) ? ' in' : '';
      $icon      = ( isset( $tab['atts']['icon'] ) ) ? '<i class="'. $tab['atts']['icon'] .'"'. $icon_style .'></i>' : '';
        $title     = '<h4 class="panel-title"><a href="#'. $uniqtab .'-'. $key .'" aria-controls="'. $uniqtab .'-'. $key .'" class="collapsed " data-toggle="collapse">'. $icon . $tab['atts']['title'] .'</a></h4>';


      $output   .= '<div class="panel panel-default"'. $el_style .'>';
      $output   .= '<div class="panel-heading'. $selected .'" role="tab">'. $title .'</div>';
      $output   .= '<div id="'. $uniqtab .'-'. $key .'" class="panel-collapse collapse'. $opened .'" role="tabpanel"><div class="panel-content">'. do_shortcode( $tab['content'] ) . '</div></div>';
      $output   .= '</div>';

    }

    $output     .= '</div>';
    // end output

    return $output;
  }
  add_shortcode( 'vc_accordion', 'elsey_vt_accordion_function' );
}

/**
 *
 * Accordion Shortcode
 * @since 1.0.0
 * @version 1.1.0
 *
 */
if( ! function_exists( 'elsey_vt_accordion_tab' ) ) {
  function elsey_vt_accordion_tab( $atts, $content = '', $key = '' ) {
    global $vt_accordion_tabs;
    $vt_accordion_tabs[]  = array( 'atts' => $atts, 'content' => $content );
    return;
  }
  add_shortcode( 'vc_accordion_tab', 'elsey_vt_accordion_tab' );
}
