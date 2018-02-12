<?php
/* ==========================================================
    Contact
=========================================================== */

if ( is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {

  if ( !function_exists('elsey_newsletter_function')) { 

    function elsey_newsletter_function( $atts, $content = true ) {

      extract(shortcode_atts(array(
        'nl_title_one'     => '',
        'nl_title_one_tag' => '',
        'nl_title_two'     => '',
        'nl_title_two_tag' => '',
        'nl_desc'          => '',
        'nl_form_style'    => 'els-subs-one',
        'class'            => '',
      ), $atts));

      // Shortcode Style CSS
      $e_uniqid     = uniqid();
      $inline_style = ''; 
      $styled_class = 'els-newsltr-'. $e_uniqid;

      $form_style_class = ( $nl_form_style === 'els-subs-one' ) ? 'els-subs-one' : 'els-subs-two';
      $output = '';

      // Turn output buffer on
	    ob_start();

      $output .= '<div class="els-newsltr '. esc_attr( $styled_class .' '. $form_style_class .' '. $class ) .'">';

      if ( $nl_title_one_tag === 'h2' ) { $output .= isset($nl_title_one) ? '<h2 class="els-single-title-one">' . esc_attr($nl_title_one) . '</h2>' : ''; }
      else if ( $nl_title_one_tag === 'h3' ) { $output .= isset($nl_title_one) ? '<h3 class="els-single-title-one">' . esc_attr($nl_title_one) . '</h3>' : ''; }
      else if ( $nl_title_one_tag === 'h4' ) { $output .= isset($nl_title_one) ? '<h4 class="els-single-title-one">' . esc_attr($nl_title_one) . '</h4>' : ''; }
      else if ( $nl_title_one_tag === 'h5' ) { $output .= isset($nl_title_one) ? '<h5 class="els-single-title-one">' . esc_attr($nl_title_one) . '</h5>' : ''; }
      else if ( $nl_title_one_tag === 'h6' ) { $output .= isset($nl_title_one) ? '<h6 class="els-single-title-one">' . esc_attr($nl_title_one) . '</h6>' : ''; }

      if ( $nl_title_two_tag === 'h2' ) { $output .= isset($nl_title_two) ? '<h2 class="els-single-title-two">' . esc_attr($nl_title_two) . '</h2>' : ''; }
      else if ( $nl_title_two_tag === 'h3' ) { $output .= isset($nl_title_two) ? '<h3 class="els-single-title-two">' . esc_attr($nl_title_two) . '</h3>' : ''; }
      else if ( $nl_title_two_tag === 'h4' ) { $output .= isset($nl_title_two) ? '<h4 class="els-single-title-two">' . esc_attr($nl_title_two) . '</h4>' : ''; }
      else if ( $nl_title_two_tag === 'h5' ) { $output .= isset($nl_title_two) ? '<h5 class="els-single-title-two">' . esc_attr($nl_title_two) . '</h5>' : ''; }
      else if ( $nl_title_two_tag === 'h6' ) { $output .= isset($nl_title_two) ? '<h6 class="els-single-title-two">' . esc_attr($nl_title_two) . '</h6>' : ''; }
      
      $output .= ( !empty($nl_desc) ) ? '<div class="els-single-desc"><p>' . esc_attr($nl_desc) . '</p></div>' : '';

 			$output .= '<div class="els-mc-subsc-form">';
      $output .= do_shortcode('[mc4wp_form id="2799"]');
      $output .= '</div>';

      $output .= '</div>';

      echo $output;

      // Return outbut buffer
      return ob_get_clean();

    }

  }

  add_shortcode( 'elsey_newsletter', 'elsey_newsletter_function' );

}