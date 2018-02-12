<?php
/* ===========================================================
    Pricing
=========================================================== */
if ( !function_exists('elsey_pricing_box_function')) {
  function elsey_pricing_box_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'price_title'    => '',
      'price_subtitle'    => '',
      'price'         => '',
      'price_currency'  => '',
      'price_duration'  => '',
      'price_label'      => '',
      'btn_text'      => '',
      'btn_link'      => '',
      'pricing_box_features' => '',
      // Style
      'price_bg_color' => '',
      'btn_text_color'  => '',
      'btn_text_hover_color'  => '',
      'btn_background_color'  => '',
      'btn_background_hover_color'  => '',
      'btn_border_color'  => '',
      'btn_border_hover_color'  => '',
    ), $atts));

    // Group Field
    $pricing_box_features = (array) vc_param_group_parse_atts( $pricing_box_features );

    if ($price_title) {
      $in_class = '-'.sanitize_title($price_title);
    } else {
      $in_class = '';
    }

     // Shortcode Style CSS
    $e_uniqid        = uniqid();
    $inline_style  = '';

    // Btn Normal
    if ( $price_bg_color ) {
      $inline_style .= '.elsey-pricing-'.$e_uniqid.$in_class.'.plan-item {';
      $inline_style .= ( $price_bg_color ) ? 'background-color:'. $price_bg_color .';' : '';
      $inline_style .= '}';
    }  
    // Btn Normal
    if ( $btn_text_color || $btn_background_color || $btn_border_color ) {
      $inline_style .= '.elsey-pricing-'.$e_uniqid.$in_class.' .plan-info a.elsey-btn {';
      $inline_style .= ( $btn_text_color ) ? 'color:'. $btn_text_color .';' : '';
      $inline_style .= ( $btn_background_color ) ? 'background-color:'. $btn_background_color .';' : '';
      $inline_style .= ( $btn_border_color ) ? 'border-color:'. $btn_border_color .';' : '';
      $inline_style .= '}';
    } 
    // Btn Hover
    if ( $btn_text_hover_color || $btn_background_hover_color || $btn_border_hover_color ) {
      $inline_style .= '.elsey-pricing-'.$e_uniqid.$in_class.'.plan-item:hover .plan-info a.elsey-btn {';
      $inline_style .= ( $btn_text_hover_color ) ? 'color:'. $btn_text_hover_color .';' : '';
      $inline_style .= ( $btn_background_hover_color ) ? 'background-color:'. $btn_background_hover_color .';' : '';
      $inline_style .= ( $btn_border_hover_color ) ? 'border-color:'. $btn_border_hover_color .';' : '';
      $inline_style .= '}';
    } 

    // add inline style
    add_inline_style( $inline_style );
    $styled_class  = ' elsey-pricing-'.$e_uniqid.$in_class;

    // Atts
    $uniqtab     = uniqid();
    $price_title = $price_title ? '<h4 class="plan-title">'.$price_title.'</h4>' : '';
    $price_subtitle = $price_subtitle ? '<h6 class="plan-subtitle">'.$price_subtitle.'</h6>' : '';
    $price = $price ? '<h2 class="plan-price"><sup>'.$price_currency.'</sup>'.$price.'<sub>/ '.$price_duration.'</sub></h2>' : '';
    $price_label = $price_label ? '<div class="plan-type">'.$price_label.'</div>' : '';
    $btn_text = $btn_text ? $btn_text : 'ORDER NOW';
    $btn_link = $btn_link ? '<a href="'.$btn_link.'" class="elsey-btn ">'.$btn_text.'</a>' : '';

    // Output
    $output = '<div class="plan-item '.$styled_class.'"><div class="plan-top-wrap">'. $price_title . $price_subtitle . $price . $price_label .'</div><div class="plan-info"><ul>';

    // Foreach features
    $i = 1;
    foreach ( $pricing_box_features as $list_item ) {
      
      $output .= '<li>'.$list_item['price_features'].'</li>';
    }
    // Foreach features

    $output .= '</ul>'.$btn_link.'</div></div>';

    return $output;

  }
}
add_shortcode( 'elsey_pricing_box', 'elsey_pricing_box_function' ); ?>
