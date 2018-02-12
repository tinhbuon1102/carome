<?php
/* ==========================================================
  Counter
=========================================================== */
if ( !function_exists('elsy_counter_function')) {
  function elsy_counter_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'counter_icon'   =>'',
      'counter_title'  => '',
      'counter_value'  => '',
      'class'  => '',

      // Style
      'counter_title_color'  => '',
      'counter_value_color'  => '',
      'counter_value_in_color'  => '',
      'counter_title_size'  => '',
      'counter_value_size'  => '',
      'counter_value_in_size'  => '',
    ), $atts));

    // Style
    $counter_title_color = $counter_title_color ? 'color:'. $counter_title_color .';' : '';
    $counter_value_color = $counter_value_color ? 'color:'. $counter_value_color .';' : '';
   
    // Size
    $counter_title_size = $counter_title_size ? 'font-size:'. $counter_title_size .';' : '';
    $counter_value_size = $counter_value_size ? 'font-size:'. $counter_value_size .';' : '';
    
    // Icon
    $counter_icon = $counter_icon ? '<i class="'. $counter_icon .'"></i>' : '';
    // Value
    $counter_value = $counter_value ? '<h2 class="elsy-counter" style="'. $counter_value_color . $counter_value_size .'">'. $counter_value .'</h2>' : '';

    // Counter Title
    $counter_title = $counter_title ? '<p class="status-title" style="'. $counter_title_color . $counter_title_size .'">'. $counter_title .'</p>' : '';

    // Counters
    $output = '<div class="status-item">'. $counter_icon . $counter_value . $counter_title .'</div>';

    // Output
    return $output;

  }
}
add_shortcode( 'elsy_counter', 'elsy_counter_function' );
