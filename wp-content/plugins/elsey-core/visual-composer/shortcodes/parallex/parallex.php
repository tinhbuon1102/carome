<?php
/* ==========================================================
  Gmap
=========================================================== */
if ( !function_exists('elsey_parallex_function')) {
  function elsey_parallex_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'plx_scroll_speed' => '800',
      'plx_scroll_type'  => 'flp_scroll',
      'plx_side_nav'     => 'false',
      'plx_sections'     => '',
      'class'            => '',
    ), $atts));

    // Turn output buffer on
    ob_start();


    $e_uniqid     = uniqid();
    $els_plx_styled_class = 'els-plx-sections-'. $e_uniqid;

    if ($plx_scroll_type === 'flp_scroll') {
      $els_plx_styled_class .= ' els-plx-flp-scroll';
    } else {
      $els_plx_styled_class .= ' els-plx-layer-scroll';
    }

    // Group Field
    $plx_sections = (array) vc_param_group_parse_atts($plx_sections);
    $output = '';
    $output.= '<div class="els-plx-sections '.esc_attr($els_plx_styled_class.' '.$class).'">';
    $plx_js_count = 1;

    if ( count($plx_sections) > 0 ) { ?>
      <script type="text/javascript">
        jQuery(document).ready(function($) {
          $(window).load(function() {
          <?php foreach ( $plx_sections as $plx_section ) {
            $plx_speed = isset( $plx_section['plx_speed'] ) ? $plx_section['plx_speed'] : '0.1'; ?>
            $("#els-plxsec-<?php echo esc_js($plx_js_count); ?>").parallax("50%", <?php echo esc_js($plx_speed); ?>);
          <?php $plx_js_count++; } ?> 
        }); 
        }); 
      </script>
    <?php } 

    if ($plx_side_nav) {
      
      $plx_nav_count = 1;
      $output .= '<ul id="els-plx-nav">';

      foreach ( $plx_sections as $plx_section ) {
        $plx_active_color = isset( $plx_section['plx_active_color'] ) ? $plx_section['plx_active_color'] : '#222222'; 
        $output .=  '<li><a href="#els-plxsec-'.$plx_nav_count.'" data-color="'.$plx_active_color.'"></a></li>';
        $plx_nav_count++;
      }

      $output .= '</ul>';
    }    
  
    $plx_sections_count = 1;
    foreach ( $plx_sections as $plx_section ) {
      $plx_img = isset( $plx_section['plx_img'] ) ? wp_get_attachment_url( $plx_section['plx_img'] ) : '';
      $plx_content_align  = isset( $plx_section['plx_content_align'] ) ? $plx_section['plx_content_align'] : 'right-ns';
      $plx_min_height     = isset( $plx_section['plx_min_height'] ) ? elsey_check_px($plx_section['plx_min_height']) : '1020px';
      $plx_text_one       = isset( $plx_section['plx_text_one'] ) ? $plx_section['plx_text_one'] : '';
      $plx_text2_part_one = isset( $plx_section['plx_text2_part_one'] ) ? $plx_section['plx_text2_part_one'] : '';
      $plx_text2_part_two = isset( $plx_section['plx_text2_part_two'] ) ? $plx_section['plx_text2_part_two'] : '';
      $plx_text_three     = isset( $plx_section['plx_text_three'] ) ? $plx_section['plx_text_three'] : '';
      $plx_desc           = isset( $plx_section['plx_desc'] ) ? $plx_section['plx_desc'] : '';
      $plx_btn_text       = isset( $plx_section['plx_btn_text'] ) ? $plx_section['plx_btn_text'] : '';
      $plx_btn_link       = isset( $plx_section['plx_btn_link'] ) ? $plx_section['plx_btn_link'] : '#';

      $output .= '<div class="els-plxsec els-plxsec-content-'.esc_attr($plx_content_align).'" id="els-plxsec-'.$plx_sections_count.'" style="background: url('.$plx_img.') 50% 0 no-repeat fixed; min-height: '.$plx_min_height.'">';
      $output .= '<div class="els-plxsec-content">';
      $output .= !empty($plx_text_one) ? '<div class="els-plxsec-title-one">'.esc_attr($plx_text_one).'</div>' : '';
      $output .= !empty($plx_text2_part_one) ? '<div class="els-plxsec-title-two">'.esc_attr($plx_text2_part_one).'<br/>'.esc_attr($plx_text2_part_two).'</div>' : '';
      $output .= !empty($plx_text_three) ? '<div class="els-plxsec-title-three">'.esc_attr($plx_text_three).'</div>' : '';
      $output .= !empty($plx_desc) ? '<div class="els-plxsec-desc">'.esc_attr($plx_desc).'</div>' : '';
      $output .= !empty($plx_btn_text) ? '<div class="els-plxsec-btn"><a class="els-btn" href="'.esc_url($plx_btn_link).'">'. $plx_btn_text .'</a></div>' : '';
      $output .= '</div></div>';

      $plx_sections_count++;
    }

    $output .= '</div>'; 
    echo $output; ?>

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('.<?php echo esc_js($els_plx_styled_class); ?>').find('#els-plx-nav li:first').addClass('els-plxsec-active');
        $('#els-plx-nav').onePageNav({
          currentClass: 'els-plxsec-active',
          scrollSpeed: <?php echo esc_js($plx_scroll_speed); ?>,
        });
        $(window).scroll(function() {
          $("#els-plx-nav li").each(function(index) {
            var $this = $(this);
            if ($this.hasClass('els-plxsec-active')) {
              var $value = $this.find('a').data('color');
              $this.find('a').css('border-color', $value);
            } else {
              $this.find('a').removeAttr("style");
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
add_shortcode( 'elsey_parallex', 'elsey_parallex_function' );
