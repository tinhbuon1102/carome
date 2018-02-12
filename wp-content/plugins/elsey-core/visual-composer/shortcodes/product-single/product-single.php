<?php
/* ==========================================================
  Product
=========================================================== */

if ( class_exists( 'WooCommerce' ) ) {

  if ( !function_exists( 'elsey_product_single_function') ) {

    function elsey_product_single_function( $atts, $content = NULL ) {

	    extract(shortcode_atts(array(
        'pr_single_id'     => '',
	      'pr_single_style'  => 'img-top',
        'pr_single_upload' => '',
        'pr_single_height' => '',
	      'pr_single_price'  => '',
        'pr_single_cats'   => '',
        'pr_single_image'  => '',
	      'class'            => '',
	    ), $atts));

	    $e_uniqid = uniqid();
	    $pr_styled_class = 'els-pr-single-'. $e_uniqid;
      $inline_style = '';

      $html = '';
      $image_url = wp_get_attachment_url( $pr_single_upload );

      if ( ( $pr_single_style === 'img-left' ) || ( $pr_single_style === 'img-right' ) ) {
        $pr_single_parent_col  = 'row';
        $pr_single_image_col   = 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
        $pr_single_details_col = 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
      } else {
        $pr_single_image_col   = '';
        $pr_single_details_col = '';
        $pr_single_parent_col  = '';
      }

      if ( $pr_single_style === 'img-top' ) {
        $pr_single_position_class = 'els-pr-single-top';
      } else if ( $pr_single_style === 'img-btm' ) {
        $pr_single_position_class = 'els-pr-single-btm';
      } else if ( $pr_single_style === 'img-left' ) {
        $pr_single_position_class = 'els-pr-single-left';
      } else {
        $pr_single_position_class = 'els-pr-single-right';
      }
	   
	    $args = array(
        'p'         => $pr_single_id,
        'post_type' => 'product',
	    );

	    $elsey_single_products = new WP_Query($args);

	    // Turn output buffer on
	    ob_start();

      if ( $pr_single_image && ( ( $pr_single_style === 'img-top' ) || ( $pr_single_style === 'img-btm' ) ) ) {
        $pr_single_height = ($pr_single_height) ? elsey_check_px($pr_single_height) : '590px';
      } else if ( $pr_single_image && ( ($pr_single_style === 'img-left') || ($pr_single_style === 'img-right') ) ) {
        $pr_single_height = ($pr_single_height) ? elsey_check_px($pr_single_height) : '440px';
      }

      if( isset($pr_single_height) ) {
        $inline_style .= '.'.$pr_styled_class.' {';
        $inline_style .= 'height:'.$pr_single_height.';';
        $inline_style .= '}';
      }
      add_inline_style( $inline_style );

      echo '<div class="els-pr-single ' . esc_attr( $pr_single_position_class . ' ' . $pr_styled_class . $class ) . '">';
      echo '<div class="'. esc_attr( $pr_single_parent_col ) .'">';

      if ( $elsey_single_products->have_posts() ) : while ( $elsey_single_products->have_posts() ) : $elsey_single_products->the_post();

        global $product; 

        if ( $image_url ) {
          $html .= '<div class="els-pr-single-img '.esc_attr( $pr_single_image_col ).'">';
          $html .= '<a href="'. get_the_permalink( $product->get_id() ) .'"><img src="'.esc_url( $image_url ).'" alt="" /></a>';
          $html .= '</div>';
        }

        if ( $pr_single_image && ( ( $pr_single_style === 'img-top' ) || ( $pr_single_style === 'img-left' ) ) ) {
          echo $html;
        } 

        echo '<div class="els-pr-single-details '.esc_attr($pr_single_details_col).'">';

        $pr_single_price = ($pr_single_price) ? '<div class="els-pr-single-price">' . $product->get_price_html() . '</div>' : '';

        echo $pr_single_price;

        echo '<h3><a href="' . get_the_permalink($product->get_id()) . '">' . esc_attr(get_the_title($product->get_id())) . '</a></h3>';

        $pr_single_cats = ($pr_single_cats) ? get_the_term_list( $product->get_id(), 'product_cat', '<div class="els-pr-single-cats">', ', ', '</div>' ) : '';    

        echo $pr_single_cats;
        
        echo '<div class="els-pr-single-atc">';

        woocommerce_template_loop_add_to_cart( $elsey_single_products->post, $product );

        echo '</div></div>';

        if ( $pr_single_image && ( ($pr_single_style === 'img-btm') || ($pr_single_style === 'img-right') ) ) {
          echo $html;
        } 

      endwhile; endif;
      wp_reset_postdata();

      echo '</div></div>';

      // Return outbut buffer
      return ob_get_clean();

    }

  }

  add_shortcode( 'elsey_product_single', 'elsey_product_single_function' );

}
