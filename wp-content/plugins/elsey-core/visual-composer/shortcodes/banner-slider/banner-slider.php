<?php
/* ==========================================================
  Banner Slider
=========================================================== */

if ( class_exists( 'WooCommerce' ) ) {

  if ( !function_exists('elsey_banner_slider_function')) {

    function elsey_banner_slider_function( $atts, $content = NULL ) {

		 	extract( shortcode_atts( array(
				//Slider Options
				'adaptive_height'	  => '',
				'infinite_loop'		  => '',
				'arrows' 			      => '',
				'autoplay'			    => '',
				'pause_hover'			  => '',
				'autoplay_speed'	  => '',
				'background_color'	=> '',
				'animation'			    => 'slide',
				'anim_speed'		    => '',
				'prlists' 	        => '',
				'class'             => '',
			), $atts ) );

	    // Shortcode Style CSS
	    $e_uniqid         = uniqid();
	    $styled_class     = 'els-banner-slider-'. $e_uniqid;

	    // Slider Options
		  $adaptive_height  = ($adaptive_height)      ? 'true' : 'false';
	    $infinite_loop    = ($infinite_loop)        ? 'true' : 'false';
	    $arrows           = ($arrows)               ? 'true' : 'false';
	    $autoplay         = ($autoplay)             ? 'true' : 'false';
	    $pause_hover      = ($pause_hover)          ? 'true' : 'false';
	    $autoplay_speed   = ($autoplay_speed)       ? intval( $autoplay_speed ) : 3000;
	    $animation        = ($animation != 'slide') ? 'true' : 'false';
	    $anim_speed       = ($anim_speed)           ? intval( $anim_speed ) : 300;

		  $background_color_style = ( strlen( $background_color ) > 0 ) ? 'style="background-color: ' . esc_attr( $background_color ) . '"' : '';
	    $adaptive_height_class  = ($adaptive_height === 'true') ? 'els-prslr-height-adaptive' : 'els-prslr-height-fixed';

	    // Turn output buffer on
	    ob_start();

	    $output  = '';
	    $output .= '<div class="els-prslr '.$styled_class.' '.$adaptive_height_class.' '.$class.'">';

	    $slider_banners = (array) vc_param_group_parse_atts($prlists);

	    foreach ($slider_banners as $slider_banner) {
	      $pr_img              = isset($slider_banner['pr_img'])             ? $slider_banner['pr_img']             : '';
	      $pr_top_offer_text   = isset($slider_banner['pr_top_offer_text'])  ? $slider_banner['pr_top_offer_text']  : '';
	      $pr_title_text_one   = isset($slider_banner['pr_title_text_one'])  ? $slider_banner['pr_title_text_one']  : '';
	      $pr_title_text_two   = isset($slider_banner['pr_title_text_two'])  ? $slider_banner['pr_title_text_two']  : '';
	      $pr_sub_title_text   = isset($slider_banner['pr_sub_title_text'])  ? $slider_banner['pr_sub_title_text']  : '';
	      $pr_title_link       = isset($slider_banner['pr_title_link'])      ? $slider_banner['pr_title_link']      : '';
	      $pr_details_one      = isset($slider_banner['pr_details'])         ? $slider_banner['pr_details']         : '';
	      $pr_details_two      = isset($slider_banner['pr_details_two'])     ? $slider_banner['pr_details_two']     : '';      
	      $shop_now_title      = isset($slider_banner['shop_now_title'])     ? $slider_banner['shop_now_title']     : '';
	      $shop_now_link       = isset($slider_banner['shop_now_link'])      ? $slider_banner['shop_now_link']      : '';
	      $view_now_title      = isset($slider_banner['view_now_title'])     ? $slider_banner['view_now_title']     : '';
	      $view_now_link       = isset($slider_banner['view_now_link'])      ? $slider_banner['view_now_link']      : ''; 
	      $offer_text_color    = isset($slider_banner['offer_text_color'])   ? $slider_banner['offer_text_color']   : '';
	      $title_text_color    = isset($slider_banner['title_text_color'])   ? $slider_banner['title_text_color']   : '';
	      $subtitle_text_color = isset($slider_banner['subtitle_text_color'])? $slider_banner['subtitle_text_color']: '';
	      $details_text_color  = isset($slider_banner['details_text_color']) ? $slider_banner['details_text_color'] : '';
	      $shopnow_text_color  = isset($slider_banner['shopnow_text_color']) ? $slider_banner['shopnow_text_color'] : '';
	      $viewnow_text_color  = isset($slider_banner['viewnow_text_color']) ? $slider_banner['viewnow_text_color'] : '';
	      $text_position       = isset($slider_banner['text_position'])      ? $slider_banner['text_position']      : 'h_left-v_center';
	      $text_alignment      = isset($slider_banner['text_alignment'])     ? $slider_banner['text_alignment']     : 'align_left';
	      $text_padding        = isset($slider_banner['text_padding'])       ? $slider_banner['text_padding']       : '';
	      $text_animation      = isset($slider_banner['text_animation'])     ? $slider_banner['text_animation']     : '';

	      $e_uniqid_box    = uniqid();
	      $inline_style    = '';

	      if($offer_text_color) {     
	        $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-offer {';
	        $inline_style .= 'color:'.$offer_text_color.' !important;';
	        $inline_style .= '}';
	      }

	      if($title_text_color) {
	        if($pr_title_link) {
	            $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-title a, .els-prslr-box-'.$e_uniqid_box .' .els-prslr-intro span {';
	        } else {
	            $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-title, .els-prslr-box-'.$e_uniqid_box .' .els-prslr-intro span {';
	        }
	        $inline_style .= 'color:'.$title_text_color.' !important;';
	        $inline_style .= '}';
	      }

	      if($subtitle_text_color) {     
	        $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-subtitle {';
	        $inline_style .= 'color:'.$subtitle_text_color.' !important;';
	        $inline_style .= '}';
	      }
	      
	      if($details_text_color) {
	        $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-content .els-prslr-desc {';
	        $inline_style .= 'color:'.$details_text_color.' !important;';
	        $inline_style .= '}';
	      }

	      if($shopnow_text_color) {	     
	        $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-content .els-prslr-shopNow-title a {';     
	        $inline_style .= 'color:'.$shopnow_text_color.' !important;';
	        $inline_style .= '}';
	      }

	      if($viewnow_text_color) {      
	        $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-content .els-prslr-viewNow-title a {';   
	        $inline_style .= 'color:'.$viewnow_text_color.' !important;';
	        $inline_style .= 'border-color:'.$viewnow_text_color.' !important;';
	        $inline_style .= '}';
	      }

	      $inline_style .= '.els-prslr-box-'.$e_uniqid_box .' .els-prslr-content .animated {';
	      $inline_style .= 'opacity: 0;';
	      $inline_style .= '}';

	      // Banner Options //
	      $text_position  = explode( '-', $text_position );
	      $text_styles    = '';

	      if($text_padding) {
	        $padding        = intval( $text_padding ) . '% ';
	        $padding_top    = '0 ';
	        $padding_bottom = '0 ';

	        if ( $text_position[1] === 'v_top' ) {
	          $padding_top    = $padding;
	        } else if ( $text_position[1] === 'v_bottom' ) {
	          $padding_bottom = $padding;
	        }
	        $text_styles .= 'padding: ' . $padding_top . $padding . $padding_bottom . $padding . ';';
	      }

	      // Add Inline Style
	      add_inline_style( $inline_style );
	      $styled_box_class = 'els-prslr-box-'.$e_uniqid_box;

	      $banner_image  = '';
	      $banner_image  = wp_get_attachment_image_src( $pr_img, 'fullsize', false, '' );
	      $banner_image  = $banner_image[0];

	      if($banner_image) {

	        $output .= '<div class="els-prslr-box '.$styled_box_class.'" '.$background_color_style.'>';
	        $output .= '<div class="els-prslr-img">';
	        $output .= '<img src="'.esc_url($banner_image).'" class="els-prslr-image"></div>';
	        $output .= '<div class="els-prslr-content '.$text_position[0].' '.$text_position[1].' '.$text_alignment.'" style="'.$text_styles.'">';
	        $output .= '<div class="els-prslr-text">';

	        if($pr_top_offer_text) {
	        	$output .= '<div class="els-prslr-offer els-animate animated" data-animate="'.esc_attr($text_animation).'">'.esc_attr($pr_top_offer_text).'</div>';
	        }

	        if($pr_title_text_one || $pr_title_text_two) {	            
	          $output .= '<div class="els-prslr-intro">';
	        	$output .= '<div class="els-prslr-title els-animate animated" data-animate="'.esc_attr($text_animation).'">';
	        	$output .= ($pr_title_link) ? '<a href="'.esc_url($pr_title_link).'">' : '';
	        	$output .= ($pr_title_text_one) ? esc_attr($pr_title_text_one) : '';
	        	$output .= ($pr_title_text_one && $pr_title_text_two) ? '<br/>' : '';
	        	$output .= ($pr_title_text_two) ? esc_attr($pr_title_text_two) : '';
	        	$output .= ($pr_title_link) ? '</a>' : '';
	        	$output .= '</div>';
		        $output .= '</div>';
		      } 

		      if($pr_sub_title_text) {
	        	$output .= '<div class="els-prslr-subtitle els-animate animated" data-animate="'.esc_attr($text_animation).'">'.esc_attr($pr_sub_title_text).'</div>';
	        }

		      if($pr_details_one || $pr_details_two) {
	          $output .= '<div class="els-prslr-desc els-animate animated" data-animate="'.esc_attr($text_animation).'">';
	          $output .= ($pr_details_one) ? esc_attr($pr_details_one) : '';
	        	$output .= ($pr_details_one && $pr_details_two) ? '<br/>' : '';
	        	$output .= ($pr_details_two) ? esc_attr($pr_details_two) : '';
	        	$output .= '</div>';
	        }

	        if($shop_now_title || $view_now_title) {

	        	$output .= '<div class="els-prslr-btns">';

		        if($shop_now_title) {
		          if($shop_now_link) {
		            $output .= '<div class="els-prslr-shopNow-title els-animate animated" data-animate="'.esc_attr($text_animation).'"><a href="'.esc_url($shop_now_link).'">'.esc_attr($shop_now_title).'</a></div>';
		          } else {
		            $output .= '<div class="els-prslr-shopNow-title els-animate animated" data-animate="'.esc_attr($text_animation).'"><a href="#">'.esc_attr($shop_now_title).'</a></div>';
		          }
		        }

		        if($view_now_title) {
		          if($view_now_link) {
		            $output .= '<div class="els-prslr-viewNow-title els-animate animated" data-animate="'.esc_attr($text_animation).'"><a href="'.esc_url($view_now_link).'">'.esc_attr($view_now_title).'</a></div>';
		          } else {
		            $output .= '<div class="els-prslr-viewNow-title els-animate animated" data-animate="'.esc_attr($text_animation).'"><a href="#">'.esc_attr($view_now_title).'</a></div>';
		          }
		        }

		        $output .= '</div>';
		        
		      }

	        $output .= '</div></div></div>';

	      }
	    }

	    $output .= '</div>';

	    echo $output; ?>

	    <script type="text/javascript">

	    	jQuery(window).load(function() {

	    		var $sliderGrid = jQuery('.<?php echo esc_js($styled_class); ?>');

	    		$sliderGrid.on('init', function(event, slick) {
	    			jQuery(document).trigger('banner-slider-loaded');
    		    var $slideActive   = $sliderGrid.find('.slick-track .slick-active');
    		    var $bannerContent = $slideActive.find('.els-animate');
						if ($bannerContent.length) {
							$bannerContent.css('opacity', '1');
							$bannerAnimation = $bannerContent.data('animate');
							$bannerContent.addClass($bannerAnimation);
						}
			    });

      		$sliderGrid.slick({
		        rows:           1,
		        slidesPerRow:   1,
		        slidesToShow:   1,
		        adaptiveHeight: <?php echo esc_js($adaptive_height); ?>,
		        arrows:         <?php echo esc_js($arrows); ?>,
		        autoplay:       <?php echo esc_js($autoplay); ?>,
		        autoplaySpeed:  <?php echo esc_js($autoplay_speed); ?>,
		        dots:           false,
		        fade:           <?php echo esc_js($animation); ?>,
		        infinite:       <?php echo esc_js($infinite_loop); ?>,
		        pauseOnHover:   <?php echo esc_js($pause_hover); ?>,
		        prevArrow:      "<div class='els-prslr-nav prslr-prev'><i class='fa fa-angle-left' aria-hidden='true'></i></div>",
		        nextArrow:      "<div class='els-prslr-nav prslr-next'><i class='fa fa-angle-right' aria-hidden='true'></i></div>",
		        speed:          <?php echo esc_js($anim_speed); ?>,
	        });

	        $sliderGrid.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
	        	var $currentSlide  = jQuery(slick.$slides[slick.currentSlide]);
	        	var $bannerContent = $currentSlide.find('.els-animate');
	        	if ($bannerContent.length) {
	        		$bannerContent.css('opacity', '0');
							$bannerAnimation = $bannerContent.data('animate');
							$bannerContent.removeClass($bannerAnimation);
						}
					});

	        $sliderGrid.on('afterChange', function(event, slick, currentSlide, nextSlide) {
	        	var $currentSlide  = jQuery(slick.$slides[slick.currentSlide]);
	        	var $bannerContent = $currentSlide.find('.els-animate');
	        	if ($bannerContent.length) {
	        		$bannerContent.css('opacity', '1');
							$bannerAnimation = $bannerContent.data('animate');
							$bannerContent.addClass($bannerAnimation);
						}
	        });

	      });
	      
	    </script>

    <?php
	  // Return outbut buffer
    return ob_get_clean();

    }
  }

  add_shortcode( 'elsey_banner_slider', 'elsey_banner_slider_function' );

}
