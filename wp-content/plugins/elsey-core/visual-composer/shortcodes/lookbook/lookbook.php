<?php
/* ==========================================================
  Lookbook
=========================================================== */
if (!function_exists('elsey_lb_function')) {

  function elsey_lb_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'lb_style'             => 'els-lb-one',
      'lb_single_id'         => '',
      'lb_columns'           => 'els-lb-col-5',
      'lb_slider_limit'      => '',
      // Enable & Disable
      'lb_slider_loop'       => '',
      'lb_slider_nav'        => '',
      'lb_slider_dots'       => '',
      'lb_slider_autoplay'   => '',
      'lb_slider_nav_speed'  => '',
      'lb_slider_dots_speed' => '',
      'lb_slider_ap_speed' 	 => '',
      // Custom Class
      'class'                => '',
    ), $atts));

    $output = '';
    $e_uniqid = uniqid();
    $lb_styled_class = 'els-lb-'. $e_uniqid;
    
    // Lookbook Slider Style Values
    $lb_slider_loop       = $lb_slider_loop ? 'true' : 'false';
    $lb_slider_nav        = $lb_slider_nav ? 'true' : 'false';
    $lb_slider_dots       = $lb_slider_dots ? 'true' : 'false';
    $lb_slider_autoplay   = $lb_slider_autoplay ? 'true' : 'false';
    $lb_slider_nav_speed  = $lb_slider_nav_speed ? (int)$lb_slider_nav_speed : '500';
    $lb_slider_dots_speed = $lb_slider_dots_speed ? (int)$lb_slider_dots_speed : '500';    
    $lb_slider_ap_speed   = $lb_slider_ap_speed ? (int)$lb_slider_ap_speed : '500';
    $lb_slider_limit      = $lb_slider_limit ? $lb_slider_limit : '4';
   
    // Lookbook Style
    if ( $lb_style === 'els-lb-three' ) {
      $lb_style_class  = 'els-lb-slider';
      $lb_parent_class = 'els-lb-slider-wrap owl-carousel';
      $lb_item_class   = 'els-lb-slider-item';
      $lb_column_class = '';
    } else if ( $lb_style === 'els-lb-two' ) {
      $lb_style_class  = 'els-lb-masonry';
      $lb_parent_class = 'els-lb-masonry-wrap';
      $lb_item_class   = 'els-lb-masonry-item';
      $lb_column_class = '';
    } else {
      $lb_style_class  = 'els-lb-standard';
      $lb_parent_class = 'els-lb-grid-wrap';
      $lb_item_class   = 'els-lb-grid-item';
      // Column Style
	    if ($lb_columns === 'els-lb-col-3') {
	      $lb_column_class = ' els-lb-grid-col-3';
	    } else if ($lb_columns === 'els-lb-col-4') {
	      $lb_column_class = ' els-lb-grid-col-4';
	    } else {
	      $lb_column_class = ' els-lb-grid-col-5';
	    }
    }

    // Turn output buffer on
    ob_start();

    // Other query params here
    $args = array(
		  'p'         => $lb_single_id,
		  'post_type' => 'lookbook'
		);

    $elsey_lookbook = new WP_Query( $args ); ?>

    <!-- Lookbook Start -->
    <div class="els-lb-wrapper <?php echo esc_attr($lb_style_class.' '.$lb_styled_class.' '.$class); ?>">
      <div class="els-lb-inner <?php echo esc_attr($lb_parent_class . $lb_column_class); ?> els-lb-gallery">

	  	 	<?php if ($elsey_lookbook->have_posts()) : while ($elsey_lookbook->have_posts()) : $elsey_lookbook->the_post();
          $post_type = get_post_meta( get_the_ID(), 'lookbook_options', true );
          
          $images = []; 
          $ids = explode( ',', $post_type['lookbook_gallery'] );     

          foreach ( $ids as $id ) {
            $attachment = wp_get_attachment_image_src( $id, 'full' );
            if ( isset($attachment[0]) ) {
              array_push($images, $attachment[0]);
            }
          }

          if ( count($images) > 0 ) {  	
          	$count = 0;
          	$count_all = count($images);

          	foreach ( $ids as $id ) {
              $count++;
              $attachment = wp_get_attachment_image_src($id, 'full');
              $alt        = get_post_meta($id, '_wp_attachment_image_alt', true);
              $alt        = $alt ? esc_attr($alt) : esc_attr(get_the_title());
              $g_img      = $attachment[0]; 

              if ( ( $count === 1 ) && ( $lb_style === 'els-lb-two' ) ) {
                $output .= '<div class="els-lb-masonry-sizer"></div>';
              }

              if ( $lb_style === 'els-lb-two' ) {
                $lb_msclass = get_post_meta( $id, 'msclass', true );
                $lb_msclass = !empty($lb_msclass) ? ' ' . esc_attr($lb_msclass) : ' lb-df';
              } else {
                $lb_msclass = '';
              }

              $lb_popup = get_post_meta( $id, '_image_media_link', true );
              $lb_popup_url = !empty($lb_popup) ? $lb_popup : $g_img;

              $output .= '<div class="'.esc_attr($lb_item_class . $lb_msclass).'" data-rel="gallery" data-src="'.esc_url($lb_popup_url).'">';
              $output .= '<div class="els-lb-img">';
              $output .= '<img src="'.esc_url($g_img).'" alt="'.esc_attr($alt).'">'; 
              $output .= '<div class="els-lb-zoom"><a href="javascript:void(0);"></a></div></div></div>';
            }
            echo $output;
          }		      
	      endwhile; endif; 
	      wp_reset_postdata(); ?>
      </div>
    </div>
    <!-- Lookbook End -->

    <?php if ( $lb_style === 'els-lb-one' ) { ?>

      <script type="text/javascript">
        jQuery(document).ready(function($) {   
          $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-grid-wrap').imagesLoaded(function() {
            $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-grid-wrap').isotope({
              itemSelector: '.els-lb-grid-item',
              layoutMode: 'masonry',
            });
          });
          $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-gallery').lightGallery({
            showThumbByDefault: false,
            selector: "[data-rel='gallery']",
          }); 
        });
      </script>

    <?php } else if ( $lb_style === 'els-lb-two' ) { ?>

      <script type="text/javascript">
        jQuery(document).ready(function($) {
          $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-masonry-wrap').imagesLoaded(function() {
            $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-masonry-wrap').isotope({
              itemSelector: '.els-lb-masonry-item',
              masonry: {
                columnWidth: '.els-lb-masonry-sizer'
              }
            });
          });
          $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-gallery').lightGallery({
            showThumbByDefault: false,
            selector: "[data-rel='gallery']",
          }); 
        });
      </script>

    <?php } else if ( $lb_style === 'els-lb-three' ) { ?>

	    <script type="text/javascript">
	      jQuery(document).ready(function($) {       
	        var $owl = $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-slider-wrap');   
	        $owl.owlCarousel({
            stagePadding:  0,
	          items:         <?php echo esc_js($lb_slider_limit); ?>,
	          loop:          <?php echo esc_js($lb_slider_loop); ?>,
	          nav:           <?php echo esc_js($lb_slider_nav); ?>,
	          dots:          <?php echo esc_js($lb_slider_dots); ?>,
	          autoplay:      <?php echo esc_js($lb_slider_autoplay); ?>,           
	          navSpeed:      <?php echo esc_js($lb_slider_nav_speed); ?>, 
	          dotsSpeed:     <?php echo esc_js($lb_slider_dots_speed); ?>, 
	          autoplaySpeed: <?php echo esc_js($lb_slider_ap_speed); ?>, 
	          autoHeight:    false,
	          navText:       ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
	          responsive:    {
	                            0: {
	                                items: 1
	                            },
	                            480: {
	                                items: <?php echo esc_js($lb_slider_limit); ?>
	                            },
	                         },
	        }); 

          $owl.on('mousewheel', '.owl-stage', function (e) {
            if (e.deltaY>0) {
                $owl.trigger('prev.owl');
            } else {
                $owl.trigger('next.owl');
            }
            e.preventDefault();
          });

          $('.<?php echo esc_js($lb_styled_class); ?> .els-lb-gallery').lightGallery({
            showThumbByDefault: false,
            selector: "[data-rel='gallery']",
          });  

	      });        
	    </script>

	  <?php } ?>

    <?php
    // Return outbut buffer
    return ob_get_clean();

  }
}

add_shortcode( 'elsey_lb', 'elsey_lb_function' );