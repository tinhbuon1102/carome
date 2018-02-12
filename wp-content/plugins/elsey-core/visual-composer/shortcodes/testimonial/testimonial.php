<?php
/* ==========================================================
  Testimonial
=========================================================== */
if ( !function_exists('elsey_testi_function')) {
  
  function elsey_testi_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'testi_title'        => '',
      'testi_limit'        => '',
      'testi_image'        => '',
      'testi_name'         => '',
      'testi_profession'   => '',
      'testi_nav'          => '',
      'testi_dots'         => '',
      'testi_autoplay'     => '',
      'testi_order'        => '',
      'testi_orderby'      => '',
      'class'              => '',
    ), $atts));  

    $testi_nav      = ($testi_nav) ? 'true' : 'false';
    $testi_dots     = ($testi_dots) ? 'true' : 'false';
    $testi_autoplay = ($testi_autoplay) ? 'true' : 'false';

    // Turn output buffer on
    ob_start();
    
    $args = array(
      // Other query params here
      'post_type'      => 'testimonial',
      'posts_per_page' => (int)$testi_limit,
      'orderby'        => $testi_orderby,
      'order'          => $testi_order
    );

    $vcts_post = new WP_Query( $args );
    
    // Shortcode Style CSS
    $e_uniqid  = uniqid();
    $styled_id = 'els-testi-slider-'. $e_uniqid;   
?>

    <!-- Testimonial Start -->       
    <div class="els-testi-slider <?php echo esc_attr($class); ?>">  

      <?php if(isset($testi_title)) { ?>  
        <h2 class="els-testi-title">   
          <?php echo esc_attr($testi_title); ?>
        </h2>
      <?php } ?>

      <ul class="els-testi-box owl-carousel" id="<?php echo esc_attr($styled_id); ?>">          
      
        <?php
        if ($vcts_post->have_posts()) : while ($vcts_post->have_posts()) : $vcts_post->the_post();
               
          $large_image     = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' );
          $large_image     = $large_image[0];
          
          if(class_exists('Aq_Resize')) {
            $large_image = aq_resize( $large_image, '76', '76', true );
          } else {
            $large_image = $large_image;
          }
          
          $testi_options   = get_post_meta( get_the_ID(), 'testimonial_options', true );
          $testi_cname     = ($testi_options['testi_name']) ? $testi_options['testi_name'] : '';
          $testi_name_link = ($testi_options['testi_name_link']) ? $testi_options['testi_name_link'] : '';
          $testi_pro       = ($testi_options['testi_pro']) ? $testi_options['testi_pro'] : '';
          $testi_pro_link  = ($testi_options['testi_pro_link']) ? $testi_options['testi_pro_link'] : ''; ?>     
                 
          <li>   

            <?php 
            if($testi_image) {
              if($large_image) { ?>
              <div class="els-testi-img">
                <img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
              </div><?php 
              } 
            } ?>
               
            <div class="els-testi-comment"><?php the_content(get_the_ID()); ?></div>
          
            <?php if($testi_name && !empty($testi_cname)) { ?>  
              <div class="els-testi-name">
                <?php if(!empty($testi_name_link)) { ?>
                  <a href="<?php echo esc_url($testi_name_link) ?>">             
                    <?php } echo esc_attr($testi_cname); if(!empty($testi_name_link)) { ?>
                  </a>
                <?php } ?>
              </div>
            <?php } ?>
        
            <?php if($testi_profession && !empty($testi_pro)) { ?>  
              <div class="els-testi-pro">
                <?php if(!empty($testi_pro_link)) { ?>
                  <a href="<?php echo esc_url($testi_pro_link) ?>">             
                    <?php } echo esc_attr($testi_pro); if(!empty($testi_pro_link)) { ?>
                  </a>
                <?php } ?>
              </div>
            <?php } ?> 

          </li> 

        <?php               
        endwhile; endif;
        wp_reset_postdata();
        ?> 
        
      </ul>    
    </div>
    <!-- Testimonial End -->  
    
    <script type="text/javascript">
      jQuery(document).ready(function($) { 
        $('#<?php echo esc_js($styled_id); ?>').imagesLoaded(function() { 
          $('#<?php echo esc_js($styled_id); ?>').owlCarousel({
            items: 1,
            loop: true,
            nav: <?php echo esc_js($testi_nav); ?>,
            dots: <?php echo esc_js($testi_dots); ?>,
            autoplay: <?php echo esc_js($testi_autoplay); ?>,
            navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],        
            autoHeight: true,
            responsive: {
              0: {
                items: 1
              },
              600: {
                items: 1
              }
            }
          });
        });
      });        
    </script>
     
  <?php
    // Return outbut buffer
    return ob_get_clean();

  }
}
add_shortcode( 'elsey_testimonial', 'elsey_testi_function' );