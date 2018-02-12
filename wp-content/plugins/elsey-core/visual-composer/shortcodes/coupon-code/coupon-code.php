<?php
/* ===========================================================
  Coupon Code
=========================================================== */
if ( !function_exists('elsey_coupon_function')) {
  
  function elsey_coupon_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'heading_text'            => '',
      'coupon_desc'             => '',
      'coupon_code'             =>'',
      'coupon_link_text'        => '',
      'coupon_link'             => '',
      'coupon_link_target'      => '',
      'cpn_btn_text'            => '',
        
      // Styling
      'heading_text_size'        => '',
      'heading_text_color'       =>'',
      'desc_text_size'           =>'',
      'desc_text_color'          =>'',
      'cpn_code_size'            =>'',
      'cpn_text_color'           =>'',
      'cpn_link_size'            =>'',
      'cpn_link_color'           =>'',
      'cpn_link_hover_color'     =>'',
      'cpn_btn_text_size'        =>'',
      'cpn_btn_text_color'       =>'',
      'cpn_btn_border_color'     =>'',
      'cpn_btn_bg_color'         =>'',
      'cpn_btn_text_hover_color' =>'',
      'cpn_btn_border_hover_color' =>'',
      'cpn_btn_bg_hover_color'   =>'',
      'btn_css' =>'',

   
    ), $atts));

    // Design Tab
    $custom_css = ( function_exists( 'vc_shortcode_custom_css_class' ) ) ? vc_shortcode_custom_css_class( $btn_css, ' ' ) : '';

  

    // Shortcode Style CSS
    $e_uniqid     = uniqid();
    $inline_style = '';

    //  Heading Text Size
    if ( $heading_text_size || $heading_text_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .'.elsy-coupon-section h3 {';
      $inline_style .= ( $heading_text_size ) ? 'font-size:'. elsey_check_px($heading_text_size) .';' : '';
      $inline_style .= ( $heading_text_color ) ? 'color:'. $heading_text_color .';' : '';
      $inline_style .= '}';
    }
   // Description Text Size
    if ( $desc_text_size || $desc_text_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .'.elsy-coupon-section p {';
      $inline_style .= ( $desc_text_size ) ? 'font-size:'. elsey_check_px($desc_text_size) .';' : '';
      $inline_style .= ( $desc_text_color ) ? 'color:'. $desc_text_color .';' : '';
      $inline_style .= '}';
    }

    // Coupon Text Size
    if ( $cpn_code_size || $cpn_text_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .' .coupon-input .coupon-code {';
      $inline_style .= ( $cpn_code_size ) ? 'font-size:'. elsey_check_px($cpn_code_size) .';' : '';
      $inline_style .= ( $cpn_text_color ) ? 'color:'. $cpn_text_color .';' : '';
      $inline_style .= '}';
    }

     // Coupon Link Text Size
    if ( $cpn_link_size || $cpn_link_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .' .coupon-input h4 {';
      $inline_style .= ( $cpn_link_size ) ? 'font-size:'. elsey_check_px($cpn_link_size) .';' : '';
      $inline_style .= ( $cpn_link_color ) ? 'color:'. $cpn_link_color .';' : '';
      $inline_style .= '}';
    }

       // Coupon Link Text Size
    if ( $cpn_link_hover_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .' .coupon-input h4:hover{';
      $inline_style .= ( $cpn_link_hover_color ) ? 'color:'. $cpn_link_hover_color .';' : '';
    }

    // Coupon Button Styles
    if ( $cpn_btn_text_size || $cpn_btn_text_color || $cpn_btn_border_color || $cpn_btn_bg_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .' .elsy-coupon-code .get-coupon a {';
      $inline_style .= ( $cpn_btn_text_size ) ? 'font-size:'. elsey_check_px($cpn_btn_text_size) .';' : '';
      $inline_style .= ( $cpn_btn_text_color ) ? 'color:'. $cpn_btn_text_color .';' : '';
      $inline_style .= ( $cpn_btn_border_color ) ? 'border-color:'. $cpn_btn_border_color .';' : '';
      $inline_style .= ( $cpn_btn_bg_color ) ? 'background-color:'. $cpn_btn_bg_color .';' : '';
      $inline_style .= '}';
    }

    // Coupon Button Hover Styles
    if ( $cpn_btn_text_hover_color || $cpn_btn_border_hover_color || $cpn_btn_bg_hover_color) {
      $inline_style .= '.els-cpn-'. $e_uniqid .' .elsy-coupon-code .get-coupon a:hover {';
      $inline_style .= ( $cpn_btn_text_hover_color ) ? 'color:'. $cpn_btn_text_hover_color .';' : '';
      $inline_style .= ( $cpn_btn_border_hover_color ) ? 'border-color:'. $cpn_btn_border_hover_color .';' : '';
      $inline_style .= ( $cpn_btn_bg_hover_color ) ? 'background-color:'. $cpn_btn_bg_hover_color .';' : '';
      $inline_style .= '}';
    }

    // add inline style
    add_inline_style( $inline_style );
    $styled_class = ' els-cpn-'. $e_uniqid;

    $coupon_link   = ($coupon_link) ? 'href="'. esc_url($coupon_link) .'"' : '';
    $coupon_link_target = ($coupon_link_target) ? ' target="_blank"' : '';
    
    $output = '<div class="elsy-coupon-section ' .$styled_class . $custom_css .'">
            <h3> '. $heading_text .'</h3>
            <p>'. $coupon_desc .'</p>
            <div class="elsy-coupon-code">
              <div class="coupon-input">
                <div class="pull-left">
                  <input type="text" id="website" readonly="true" class="coupon-code elsy-coupon-copied" value="'. $coupon_code .'"  />
                  <a href="'. $coupon_link . $coupon_link_target .'"><h4>'. $coupon_link_text .'</h4></a>
                </div>
                <div class="get-coupon">
                  <div class="pull-right">
                    <a href="#0" class="elsy-cpd" data-copytarget="#website">'. $cpn_btn_text .'</a>
                  </div>
                </div>
              </div>
            </div>
          </div>';


    return $output;

  }
}

add_shortcode( 'elsey_coupon', 'elsey_coupon_function' );
