<?php
// Metabox Options
global $post;
global $wp;
$request = explode( '/', $wp->request );
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_id   = ( ! is_tag() && ! is_archive() && ! is_search() && ! is_404() && ! is_singular('testimonial') ) ? $elsey_id : false;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_titlebar_options = $elsey_meta['titlebar_options'];
  if ($elsey_titlebar_options === 'custom') {
    $elsey_titlebar_config = 'custom';
  } else {
    $elsey_titlebar_config = 'default';
  }
} else {
  $elsey_titlebar_config = 'default';
}

if ($elsey_titlebar_config === 'custom') {
	// Layout
  $elsey_titlebar_layout       = $elsey_meta['titlebar_layout'];
  // Spacings
  $elsey_titlebar_spacings     = $elsey_meta['titlebar_spacings'];
	$elsey_titlebar_top_spacings = $elsey_meta['titlebar_top_spacings'];
	$elsey_titlebar_btm_spacings = $elsey_meta['titlebar_bottom_spacings'];
  // Background
  $elsey_titlebar_background   = $elsey_meta['titlebar_bg'];
  $elsey_titlebar_bg_overlay   = $elsey_meta['titlebar_bg_overlay'];
  // Show or Hide 
  $elsey_titlebar_title_type   = $elsey_meta['titlebar_text_type'];
  $elsey_titlebar_parallax     = $elsey_meta['titlebar_parallax'];
  $elsey_titlebar_breadcrumb   = $elsey_meta['titlebar_breadcrumb'];
  $elsey_titlebar_title_text   = ($elsey_titlebar_title_type !== 'hide-title-text') ? true : false;
} else {
	// Layout
  $elsey_titlebar_layout       = cs_get_option('titlebar_layout');
  // Spacings
  $elsey_titlebar_spacings     = cs_get_option('titlebar_spacings');
	$elsey_titlebar_top_spacings = cs_get_option('titlebar_top_spacings');
	$elsey_titlebar_btm_spacings = cs_get_option('titlebar_bottom_spacings');
  // Background
  $elsey_titlebar_background   = cs_get_option('titlebar_bg');
  $elsey_titlebar_bg_overlay   = cs_get_option('titlebar_bg_overlay');
  // Show or Hide
  $elsey_titlebar_title_text   = cs_get_option('titlebar_title_text');
  $elsey_titlebar_parallax     = false;
  $elsey_titlebar_breadcrumb   = cs_get_option('titlebar_breadcrumb');
} 

if ($elsey_titlebar_spacings && $elsey_titlebar_spacings !== 'els-padding-none') {
  if ($elsey_titlebar_spacings === 'els-padding-custom') {
	  $elsey_titlebar_top_spacings   = $elsey_titlebar_top_spacings ? 'padding-top:'. elsey_check_px($elsey_titlebar_top_spacings) .' !important;' : '';
	  $elsey_titlebar_btm_spacings   = $elsey_titlebar_btm_spacings ? 'padding-bottom:'. elsey_check_px($elsey_titlebar_btm_spacings) .' !important;' : '';
	  $elsey_titlebar_custom_padding = $elsey_titlebar_top_spacings . $elsey_titlebar_btm_spacings;
  } else {
	  $elsey_titlebar_custom_padding = '';
  }
} else {
  $elsey_titlebar_custom_padding = '';
}

if ($elsey_titlebar_title_text && $elsey_titlebar_breadcrumb) { 
	$elsey_titlebar_title_column_class = 'col-lg-5 col-md-5 col-sm-5 col-xs-12';
	$elsey_titlebar_bread_column_class = 'col-lg-7 col-md-7 col-sm-7 col-xs-12';
} else if ($elsey_titlebar_title_text || $elsey_titlebar_breadcrumb) {
	$elsey_titlebar_title_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	$elsey_titlebar_bread_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
}

if ( is_product() ) {
	$maincontent_class = 'max-width--large main-con';
} elseif( (!( end($request) == 'my-account') && is_account_page() ) ){
	$maincontent_class = 'max-width--site child-account-page';
}else if ( ( end($request) == 'my-account' && is_account_page() ) ) {
	$maincontent_class = 'max-width--site';
}else if ( is_page('kimono-rental') ) {
	$maincontent_class = 'max-width--large main-con';
} else {
	$maincontent_class = 'max-width--site';
}
if($elsey_titlebar_layout === 'vertical') { 

	if ($elsey_titlebar_title_text || $elsey_titlebar_breadcrumb) { 

		if ($elsey_titlebar_parallax) {
			echo '<div data-stellar-background-ratio="0.5" data-stellar-vertical-offset="" class="els-titlebar-bg" style="background-attachment: fixed;">';
		} else {
			echo '<div class="els-titlebar-bg">';
		}

			echo '<div class="els-titlebar els-titlebar-vertical '.esc_attr($elsey_titlebar_spacings).'" style="'.esc_attr($elsey_titlebar_custom_padding).'">';
			  echo '<div class="container"><div class="row">';

				  if($elsey_titlebar_title_text) { 
		 				if (function_exists('elsey_title_area')) {
				      echo '<div class="els-titlebar-title col-lg-12 col-md-12 col-sm-12 col-xs-12"><h1 class="page-title">';
				        echo elsey_title_area_custom();
				      echo '</h1></div>';
				    }     
				  }

				  if ($elsey_titlebar_breadcrumb) {
				    if (function_exists('breadcrumb_trail')) {
				    	echo '<div class="els-titlebar-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">';
				        echo breadcrumb_trail();
				      echo '</div>';
				    }
				  }

				echo '</div></div>';
			echo '</div>';
		echo '</div>';

  }

} else {
	
	if ($elsey_titlebar_title_text || $elsey_titlebar_breadcrumb) { 
		$titlebar_class   = ( is_page() ) ? 'spacing--medium' : '';
		          if ($elsey_titlebar_breadcrumb) {
				    if ( function_exists( 'breadcrumb_trail' ) ) {
				    	echo '<div class="els-titlebar-breadcrumb display--small-up max-width--site gutter-padding--full">';
						
							echo breadcrumb_trail();
				      echo '</div>';
				    }
				  }
		//$maincontent_class   = ( is_product() ) ? 'max-width--large' : 'max-width--site';
		echo '<div class="'. $maincontent_class . ' gutter-padding--full">';
		if ( is_product() ){
			echo '<div class="max-width--site">';
		} else if( (!( end($request) == 'my-account') && is_account_page() ) ){
			
		} else {
			
		
		echo '<div class="els-titlebar els-titlebar-plain '.$titlebar_class.'" style="'.esc_attr($elsey_titlebar_custom_padding).'">';
				  if($elsey_titlebar_title_text) { 
		 				if (function_exists('elsey_title_area') ) {
							
								if (is_page( 'my-account' )&&(!is_user_logged_in())) {
									echo '<div class="els-titlebar-title '.esc_attr($elsey_titlebar_spacings).'"><h1 class="page-title">';
				        echo 'Sign in or create an account';
				      echo '</h1></div>';
								} else if ( is_page('about-carome') ) {
									if (has_post_thumbnail( $post->ID ) ):
									$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
									echo '<div class="background-head" style="background-image: url(' . $image[0] .'); background-size:cover;"></div>';
									endif;
									echo '<div class="els-titlebar-title withhead '.esc_attr($elsey_titlebar_spacings).'"><h1 class="page-title">';
				        echo 'The Story of CAROME.';
				      echo '</h1></div>';
									
								} else {
									echo '<div class="els-titlebar-title '.esc_attr($elsey_titlebar_spacings).'"><h1 class="page-title">';
				        echo elsey_title_area_custom();
				      echo '</h1></div>';
								}
								
							
				      
				    }     
				  }

		echo '</div>';
	}

}
}
