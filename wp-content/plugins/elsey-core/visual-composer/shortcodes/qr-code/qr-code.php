<?php
/* ==========================================================
 QR Code
=========================================================== */
if ( !function_exists('elsy_qrcode_function')) {
  function elsy_qrcode_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'qrcode_title'   =>'',
      'qrcode_phno'  => '',
      'qrcode_scan'  => '',
      'qrcode_image'  => '',

      // Style
      'qrcode_title_color'  => '',
      'phone_no_color'  => '',
      'scan_title_color'  => '',
      // Size
      'qrcode_title_size'  => '',
      'phone_no_size'  => '',
      'qr_title_size'  => '',
    ), $atts));

    // Style
    $qrcode_title_color = $qrcode_title_color ? 'color:'. $qrcode_title_color .';' : '';
    $phone_no_color = $phone_no_color ? 'color:'. $phone_no_color .';' : '';
    $scan_title_color = $scan_title_color ? 'color:'. $scan_title_color .';' : '';
   
    // Size
    $qrcode_title_size = $qrcode_title_size ? 'font-size:'. $qrcode_title_size .';' : '';
    $phone_no_size = $phone_no_size ? 'font-size:'. $phone_no_size .';' : '';
    $qr_title_size = $qr_title_size ? 'font-size:'. $qr_title_size .';' : '';
    
    // Top Title
    $qrcode_title = $qrcode_title ? '<p style="'. $qrcode_title_color . $qrcode_title_size .'">'. $qrcode_title .'</p>' : '';

    // Phone Number
    $qrcode_phno = $qrcode_phno ? '<h2 style="'. $phone_no_color . $phone_no_size .'">'. $qrcode_phno .'</h2>' : '';

     // Scan Title
    $qrcode_scan = $qrcode_scan ? '<p style="'. $scan_title_color . $qr_title_size .'">'. $qrcode_scan .'</p>' : '';
    
    $image_url     = wp_get_attachment_url( $qrcode_image );
    $qrcode_image = ($image_url) ? '<div class="scan-code-pos"><img src="'.esc_url($image_url).'" alt="Scan Code"></div>' : '';

    // QR Code
    $output = '<div class="elsy-app-scan">'. $qrcode_title . $qrcode_phno . $qrcode_scan . $qrcode_image .'</div>';

    // Output
    return $output;


  }
}
add_shortcode( 'elsy_qrcode', 'elsy_qrcode_function' );
