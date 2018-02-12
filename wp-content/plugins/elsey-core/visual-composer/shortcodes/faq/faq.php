<?php
/* ==========================================================
  FAQ
=========================================================== */
if ( !function_exists('elsey_faq_function')) {
  function elsey_faq_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'faq_limit'  => '',
      'faq_pagination'  => '',
      // Listing
      'faq_order'  => '',
      'faq_orderby'  => '',
      'faq_show'  => '',
      'class'  => '',
    ), $atts));

    // Turn output buffer on
    ob_start();

    //specific faq
    $faq_specific_arr=array();
    if($faq_show){
        $faq_specific_arr=explode(",",$faq_show);
    }

    $args = array(
      // other query params here,
      'post_type' => 'faq',
      'posts_per_page' => (int)$faq_limit,
      'post__in' => $faq_specific_arr,
      'orderby' => $faq_orderby,
      'order' => $faq_order
    );

    $elsey_post = new WP_Query( $args ); ?>
    <!-- Faq Start -->
    <div class="elsey-faqs">
      <?php
      if ($elsey_post->have_posts()) :
      while ($elsey_post->have_posts()) : $elsey_post->the_post();
        $post_type = get_post_meta( get_the_ID(), 'post_type_metabox', true );
      ?>
      <div class="faq-list data-scroll" data-anchor="p-<?php the_ID(); ?>">
        <div class="faq-type"><?php echo esc_attr(get_the_title()); ?></div> <!-- title -->
        <!-- Content -->
          <?php
          the_content();
          ?>
        <!-- Content -->
        </div><!-- #post-## -->

        <?php
      endwhile;
      endif;
      wp_reset_postdata(); ?>

    </div>
    <!-- Faq End -->

    <?php
    // Return outbut buffer
    return ob_get_clean();
  }
}
add_shortcode( 'elsey_faq', 'elsey_faq_function' );
