<?php
/* ==========================================================
  Image Slider
=========================================================== */

if ( !function_exists('elsey_image_slider_function')) {  
    
  function elsey_image_slider_function( $atts, $content = NULL ) {
    
    extract(shortcode_atts(array(
      'title'          => '',
      'items_cnt'      => '',
      'images'         => '',
      'images_links'   => '',
      'loop'           => '',
      'nav'            => '',
      'dots'           => '',
      'gap'            => '',
      'bg_color'       => '',
      'nav_speed'      => '',
      'dots_speed'     => '',
      'autoplay'       => '',
      'autoplay_speed' => '',
      'class'          => '',
    ), $atts));   
    
    $class          = $class ? ' '. $class : '';
    $items_cnt      = $items_cnt ? $items_cnt : '6';
    $loop           = $loop ? 'true' : 'false';
    $nav            = $nav ? 'true' : 'false';
    $dots           = $dots ? 'true' : 'false';
    $autoplay       = $autoplay ? 'true' : 'false';
    $gap            = $gap ? $gap : '10';
    $nav_speed      = $nav_speed ? (int)$nav_speed : '500';
    $dots_speed     = $dots_speed ? (int)$dots_speed : '500';    
    $autoplay_speed = $autoplay_speed ? (int)$autoplay_speed : '500';
    $bg_color       = $bg_color ? $bg_color : '';
    
    // Shortcode Style CSS
    $e_uniqid  = uniqid();
    $styled_id = 'els-img-carousel-'. $e_uniqid;

    // Turn output buffer on
    ob_start();

    $images       = explode( ',', $images );
    $images_links = ( $images_links != '' ) ? explode( ',', $images_links ) : '';
    $index        = 0;
      
    $carousel = '<div class="els-img-carousel '.$class.'">';

    if( !empty($title) ) {
      $carousel.= '<h4 class="els-img-carousel-title">'.esc_attr($title).'</h4>';
    }

    $carousel.= '<ul class="owl-carousel" id="'.$styled_id.'">';  
    
    foreach( $images as $image_id ) {
      $large_image = wp_get_attachment_image_src( $image_id, 'fullsize', false, '' );
      $large_image = $large_image[0];
        
      $url_link        = (isset( $images_links[ $index ] ) && $images_links[ $index ] != '') ? esc_url( $images_links[ $index ] ) : '';    
      $code_link_start = ($url_link != '') ? '<a href="'.$url_link.'" >' : '<div class="">';
      $code_link_end   = ($url_link != '') ? '</a>' : '</div>';
      
      $carousel.= '<li>'.$code_link_start.'<img src="'.esc_url($large_image).'" alt="">'.$code_link_end.'</li>';  
      $index++;     
    }  

    $carousel.= '</ul></div>';  

    echo $carousel;
?>
      
    <script type="text/javascript">
      jQuery(document).ready(function($) {       
        var $owl = $('#<?php echo esc_js($styled_id); ?>');   
        $owl.imagesLoaded(function() {    
          $owl.owlCarousel({
            items:         <?php echo esc_js($items_cnt); ?>,
            margin:        <?php echo esc_js($gap); ?>,
            loop:          <?php echo esc_js($loop); ?>,
            nav:           <?php echo esc_js($nav); ?>,
            dots:          <?php echo esc_js($dots); ?>,
            autoplay:      <?php echo esc_js($autoplay); ?>,           
            navSpeed:      <?php echo esc_js($nav_speed); ?>, 
            dotsSpeed:     <?php echo esc_js($dots_speed); ?>, 
            autoplaySpeed: <?php echo esc_js($autoplay_speed); ?>, 
            autoHeight:    false,
            navText:       ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
            responsive:    {
                              0: {
                                  items: 3
                              },
                              400: {
                                  items: 4
                              },
                              600: {
                                  items: <?php echo esc_js($items_cnt); ?>
                              }
                           },
            onInitialized: setOwlStageColor,
            onResized:     setOwlStageColor,
            onTranslated:  setOwlStageColor
          }); 
        });     
        function setOwlStageColor(event) {  
          $('#<?php echo esc_js($styled_id); ?> .owl-stage').css('background', "<?php echo esc_js($bg_color); ?>");
        }        
      });        
    </script>
        
    <?php    
    // Return outbut buffer
    return ob_get_clean();
  }
}
add_shortcode( 'elsey_image_slider', 'elsey_image_slider_function' );