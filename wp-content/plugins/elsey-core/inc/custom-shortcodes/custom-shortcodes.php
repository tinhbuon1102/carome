<?php
/* Spacer */
function vt_spacer_function($atts, $content = true) {
  extract(shortcode_atts(array(
    "height" => '',
  ), $atts));

  $result = do_shortcode('[vc_empty_space height="'. $height .'"]');
  return $result;
}
add_shortcode("vt_spacer", "vt_spacer_function");

/* Contact Info */
function vt_contact_function($atts, $content = true) {

  extract(shortcode_atts(array(
    "custom_class"            => '',
    "info_contact_icon"       => '',
    "info_title_text"         => '',
    "info_address_text_one"   => '',
    "info_address_text_two"   => '',
    "info_phone_number"       => '',
    "info_email_address"      => '',
    "info_website_text"       => '',
    "info_website_link"       => '',
    "info_target_tab"         => '',
    // Colors
    "info_contact_icon_color" => '',
    "info_title_text_color"   => '',
    "info_address_text_color" => '',
    "info_website_text_color" => '',
  ), $atts));

  // Shortcode Style CSS
  $e_uniqid     = uniqid();
  $inline_style = '';
  $styled_class = 'els-contact-info-'. $e_uniqid;

  // Color
  $info_contact_icon_color = $info_contact_icon_color ? 'style="color:'. $info_contact_icon_color .';"' : '';
  $info_title_text_color   = $info_title_text_color ?   'style="color:'. $info_title_text_color .';"'   : '';
  $info_address_text_color = $info_address_text_color ? 'style="color:'. $info_address_text_color .';"' : '';
  $info_website_text_color = $info_website_text_color ? 'style="color:'. $info_website_text_color .';"' : '';

  $info_target_tab       = ($info_target_tab === '1') ? 'target="_blank"' : '';
  $info_contact_icon     = ($info_contact_icon) ? '<i class="'.$info_contact_icon.'" '.$info_contact_icon_color.'></i>' : '';
  $info_title_text       = (isset($info_title_text)) ? $info_title_text = '<h5 '.$info_title_text_color.'>'.$info_title_text.'</h5>' : '';

	$info_address_text_one = (isset($info_address_text_one)) ? '<p class="els-contact-info-address" '.$info_address_text_color.'>'. $info_address_text_one .'</p>' : '';
	$info_address_text_two = (isset($info_address_text_two)) ? '<p class="els-contact-info-address" '.$info_address_text_color.'>'. $info_address_text_two .'</p>' : '';

	$info_phone_number     = (isset($info_phone_number)) ? '<p class="els-contact-info-phone" '.$info_address_text_color.'>'.esc_html__('T:', 'elsey-core').' <a href="tel:'.preg_replace('/\s+/', '', $info_phone_number).'" '.$info_address_text_color.'>'.$info_phone_number.'</a></p>' : '';

	$info_email_address    = (isset($info_email_address)) ? '<p class="els-contact-info-email" '.$info_address_text_color.'>'.esc_html__('E:', 'elsey-core').' <a href="mailto:'.$info_email_address.'" '.$info_address_text_color.'>'. $info_email_address .'</a></p>' : '';

	if (isset($info_website_text) && isset($info_website_link)) {
    $info_website_text = '<span><a href="'. $info_website_link .'" '.$info_target_tab.' '.$info_website_text_color.'>'. $info_website_text .'</a></span>';
  } else {
    $info_website_text = '';
  }
	
  $result  = '<div class="els-contact-info '.$styled_class .' '. $custom_class.'">';
  $result .= '<div class="els-contact-title">'. $info_contact_icon . $info_title_text .'</div><div class="els-contact-content">';
  $result .= $info_address_text_one . $info_address_text_two . $info_phone_number . $info_email_address . $info_website_text;
  $result .= '</div></div>';
  
  return $result;
}
add_shortcode("vt_contact_info", "vt_contact_function");

/* Simple Images */
function vt_image_lists_function($atts, $content = true) {
  extract(shortcode_atts(array(
    "custom_class" => '',
  ), $atts));

  $result = '<ul class="els-simple-image-list '. $custom_class .'">'. do_shortcode($content) .'</ul>';
  return $result;
}
add_shortcode("vt_image_lists", "vt_image_lists_function");

/* Simple Image */
function vt_image_list_function($atts, $content = NULL) {

  extract(shortcode_atts(array(
    "get_image" => '',
    "link"      => '',
    "open_tab"  => ''
  ), $atts));

  if ($get_image) {
    $my_image = ($get_image) ? '<img src="'. $get_image .'" alt=""/>' : '';
  } else {
    $my_image = '';
  }

  if ($link) {
    $open_tab = $open_tab ? 'target="_blank"' : '';
    $link_o = '<a href="'. $link .'" '. $open_tab .'>';
    $link_c = '</a>';
  } else {
    $link_o = '';
    $link_c = '';
  }

  $result = '<li>'. $link_o . $my_image . $link_c .'</li>';
  return $result;
}
add_shortcode("vt_image_list", "vt_image_list_function");

/* Social Icons */
function vt_socials_function($atts, $content = true) {

  extract(shortcode_atts(array(
    "custom_class"     => '',
    "icon_color"       => '',
    "icon_hover_color" => '',
    "icon_size"        => '',
  ), $atts));

  // Atts
  $social_style = 'els-social ';
  
  // Div for topbar social icons
  $social_open  = '<div class="els-social-box">';
  $social_close = '</div>';
  
  // Shortcode Style CSS
  $e_uniqid     = uniqid();
  $inline_style = '';

  // Colors & Size
  if ( $icon_color || $icon_size ) {
    $inline_style .= '.els-socials-'. $e_uniqid .'.els-social a {';
    $inline_style .= ( $icon_color ) ? 'color:'. $icon_color .';' : '';
    $inline_style .= ( $icon_size ) ? 'font-size:'. elsey_check_px($icon_size) .';' : '';
    $inline_style .= '}';
  }

  if ( $icon_hover_color ) {
    $inline_style .= '.els-socials-'. $e_uniqid .'.els-social li a:hover {';
    $inline_style .= ( $icon_hover_color ) ? 'color:'. $icon_hover_color .';' : '';
    $inline_style .= '}';
  }

  // add inline style
  add_inline_style( $inline_style );
  $styled_class  = ' els-socials-'. $e_uniqid;

  $result = $social_open .'<ul class="clearfix '. $social_style . $custom_class . $styled_class .'">'. do_shortcode($content) .'</ul>'. $social_close;
  return $result;

}
add_shortcode("vt_socials", "vt_socials_function");

/* Social Icon */
function vt_social_function($atts, $content = NULL) {

   extract(shortcode_atts(array(
      "social_link" => '',
      "social_icon" => '',
      "target_tab"  => ''
   ), $atts));

   $social_link = ( isset( $social_link ) ) ? 'href="'. $social_link . '"' : '';
   $target_tab  = ( $target_tab === '1' ) ? ' target="_blank"' : '';

   $result = '<li><a '. $social_link . $target_tab .' class="'. str_replace('fa ', 'icon-', $social_icon) .'"><i class="'. $social_icon .'"></i></a></li>';
   return $result;

}
add_shortcode("vt_social", "vt_social_function");

/* Quick Link */
function vt_quicklink_function($atts, $content = true) {

  extract(shortcode_atts(array(
    "custom_class"            => '',
    "link_text"         => '',
    "text_link"       => '',
    "link_target_tab"         => '',
    "text_align"    =>'',
    "text_size" => '',
    // Colors
    "text_color" => '',
    "text_hover_color"   => '',
    "border_color" => '',
    "border_hover_color" => '',

  ), $atts));



  // Shortcode Style CSS
  $e_uniqid     = uniqid();
  $inline_style = '';

  if ( $text_size || $text_color || $border_color  ) {
    $inline_style .= '.els-qlink-'. $e_uniqid .'.service-left-link a {';
    $inline_style .= ( $text_size ) ? 'font-size:'. elsey_check_px($text_size) .';' : '';
    $inline_style .= ( $text_color ) ? 'color:'. $text_color .';' : '';
    $inline_style .= ( $border_color ) ? 'border-color:'. $border_color .';' : '';
    $inline_style .= '}';
  }


  // Colors & Size
  if ( $text_hover_color || $border_hover_color  ) {
    $inline_style .= '.els-qlink-'. $e_uniqid .'.service-left-link a:hover {';
    $inline_style .= ( $text_hover_color ) ? 'color:'. $text_hover_color .';' : '';
    $inline_style .= ( $border_hover_color ) ? 'border-color:'. $border_hover_color .';' : '';
    $inline_style .= '}';
  }

  // add inline style
  add_inline_style( $inline_style );
  $styled_class  = ' els-qlink-'. $e_uniqid;
  $link_target_tab       = ($link_target_tab === '1') ? 'target="_blank"' : '';
 
  if (isset($link_text) && isset($text_link)) {
    $link_text = '<a href="'. $text_link .'" '.$link_target_tab.'>'. $link_text .'</a>';
  } else {
    $link_text = '';
  }
  

 if ($text_align === 'text-right') {
      $align_text_class = 'service-align-right';
    } elseif ($text_align === 'text-center') {
      $align_text_class = 'service-align-center';
    } else {
      $align_text_class = '';
    }


  $result  = '<div class="service-left-link '. $styled_class .' '. $align_text_class .' '. $custom_class.'">'. $link_text .'</div>';

  return $result;
}
add_shortcode("vt_quicklink", "vt_quicklink_function");



/* FAQ navigation */
function vt_faq_navigation_function($atts, $content = true) {
  extract(shortcode_atts(array(
    'sidenav_title' =>'',
    'faq_limit'  => '',
    'faq_order'  => '',
    'faq_orderby'  => '',
    'faq_show'  => '',
    'custom_class'  => '',
  ), $atts));

  $faq_specific_arr=array();
  //specific faq
    if($faq_show){
      $faq_specific_arr=explode(",",$faq_show);
    }
  $args = array(
    // other query params here,
    'post_type' => 'faq',
    'posts_per_page' => (int)$faq_limit,
    /*'category_name' => esc_attr($faq_show_category),*/
    'post__in' => $faq_specific_arr,
    'orderby' => $faq_orderby,
    'order' => $faq_order
  );

  $elsy_post = new WP_Query( $args );

  if ($elsy_post->have_posts()) :
    $output = '<div class="elsy-secondary"><div class="secondary-wrap"><div class="sidebar-nav"><h4>'.$sidenav_title.'</h4><ul>';
    while ($elsy_post->have_posts()) :
    $elsy_post->the_post();
    $post_type = get_post_meta( get_the_ID(), 'post_type_metabox', true );
    $output .= '<li><a href="#" data-scroll="p-' . get_the_ID() . '">' . get_the_title() . '</a></li>';
    endwhile;
    $output .= '</ul></div></div></div>';
  endif;  
  wp_reset_postdata();

  $result = $output;
  return $result;

}
add_shortcode("vt_faq_navigation", "vt_faq_navigation_function");
/* FAQ navigation*/

