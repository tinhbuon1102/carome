<?php
/*
 * The template for displaying all single posts.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

$elsey_content_padding = ( $elsey_meta ) ? $elsey_meta['content_spacings'] : '';

if ( $elsey_content_padding && $elsey_content_padding !== 'els-padding-none' ) {
  $elsey_content_top_spacings = $elsey_meta['content_top_spacings'];
  $elsey_content_btm_spacings = $elsey_meta['content_btm_spacings'];
  if ($elsey_content_padding === 'els-padding-custom') {
    $elsey_content_top_spacings = $elsey_content_top_spacings ? 'padding-top:'. elsey_check_px($elsey_content_top_spacings) .' !important;' : '';
    $elsey_content_btm_spacings = $elsey_content_btm_spacings ? 'padding-bottom:'. elsey_check_px($elsey_content_btm_spacings) .' !important;' : '';
    $elsey_custom_padding = $elsey_content_top_spacings . $elsey_content_btm_spacings;
  } else {
    $elsey_custom_padding = '';
  }
} else {
  $elsey_custom_padding = '';
}

// Single Post Translation Text
$elsey_prev_post_text = ( cs_get_option('prev_post_text') ) ? cs_get_option('prev_post_text') : esc_html__( 'Previous Post', 'elsey' );
$elsey_next_post_text = ( cs_get_option('next_post_text') ) ? cs_get_option('next_post_text') : esc_html__( 'Next Post', 'elsey' );

// Single Post Theme Option
$elsey_single_page_layout      = cs_get_option('single_page_layout');
$elsey_single_comment_form     = cs_get_option('single_comment_form');
$elsey_single_sidebar_position = cs_get_option('single_sidebar_position');

// Single Post Layout Option
$elsey_post_page_layout_options = get_post_meta( get_the_ID(), 'post_page_layout_options', true );

if ($elsey_post_page_layout_options) {

  $elsey_post_page_layout      = $elsey_post_page_layout_options['post_page_layout'];
  $elsey_post_show_sidebar     = $elsey_post_page_layout_options['post_page_show_sidebar'];
  $elsey_post_sidebar_position = $elsey_post_page_layout_options['post_page_sidebar_position'];

  if ($elsey_post_page_layout === 'less-width') {
    $elsey_parent_class = 'els-less-width';
    $elsey_layout_class = 'container els-reduced';
  } elseif ($elsey_post_page_layout === 'full-width') {
    $elsey_parent_class = 'els-full-width';
    $elsey_layout_class = 'container';
  } else {
    if ($elsey_single_page_layout === 'full-width') {
      $elsey_parent_class = 'els-full-width';
      $elsey_layout_class = 'container';
    } else {
      $elsey_parent_class = 'els-less-width';
      $elsey_layout_class = 'container els-reduced';
    }
  }

  if ( ($elsey_post_page_layout === 'less-width') || ($elsey_post_page_layout === 'full-width') ) {

    if($elsey_post_show_sidebar) {
      if ($elsey_post_sidebar_position === 'sidebar-left') {
        $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-left-col';
        $elsey_sidebar_position = $elsey_post_sidebar_position;
      } else {
        $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-right-col';
        $elsey_sidebar_position = $elsey_post_sidebar_position;
      }
    } else {
      $elsey_column_class     = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
      $elsey_sidebar_position = 'sidebar-hide';
    }

  } else {

    if ($elsey_single_sidebar_position === 'sidebar-left') {
      $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-left-col';
      $elsey_sidebar_position = 'sidebar-left';
    } elseif ($elsey_single_sidebar_position === 'sidebar-hide') {
      $elsey_column_class     = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
      $elsey_sidebar_position = 'sidebar-hide';
    } else {
      $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-right-col';
      $elsey_sidebar_position = 'sidebar-right';
    }

  }

} else {

  if ($elsey_single_page_layout === 'full-width') {
    $elsey_parent_class = 'els-full-width';
    $elsey_layout_class = 'container';
  } else {
    $elsey_parent_class = 'els-less-width';
    $elsey_layout_class = 'container els-reduced';
  }

  if ($elsey_single_sidebar_position === 'sidebar-left') {
    $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-left-col';
    $elsey_sidebar_position = 'sidebar-left';
  } elseif ($elsey_single_sidebar_position === 'sidebar-hide') {
    $elsey_column_class     = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
    $elsey_sidebar_position = 'sidebar-hide';
  } else {
    $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-right-col';
    $elsey_sidebar_position = 'sidebar-right';
  }

}

get_header(); ?>

<!-- Container Start -->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class . ' ' . $elsey_content_padding); ?>" style="<?php echo esc_attr($elsey_custom_padding);?>">
  <div class="<?php echo esc_attr($elsey_layout_class); ?>">
    <div class="row">

      <?php if ($elsey_sidebar_position === 'sidebar-left') { get_sidebar(); } ?>

      <!-- Content Column Start -->
      <div class="<?php echo esc_attr($elsey_column_class); ?> els-content-col">
        <div class="row els-content-area">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <?php
            if ( have_posts() ) :
              while ( have_posts() ) : the_post();

                get_template_part( 'layouts/post/content', 'single' ); ?>

                <div class="els-blog-single-pagination">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 older">
                      <?php previous_post_link( '%link', '<i class="fa fa-angle-left" aria-hidden="true"></i> <span class="els-label">'.esc_attr($elsey_prev_post_text).'</span>' ); ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 newer">
                      <?php next_post_link( '%link', '<span class="els-label">'.esc_attr($elsey_next_post_text).'</span> <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?>
                    </div>
                  </div>
                </div>

                <?php
                if ( comments_open() || get_comments_number() ) :
                  comments_template();
                endif;

              endwhile;
            else :
              get_template_part( 'layouts/post/content', 'none' );
            endif;
            wp_reset_postdata();  // avoid errors further down the page
            ?>

          </div>
        </div>
      </div>
      <!-- Content Column End -->

      <?php if ($elsey_sidebar_position === 'sidebar-right') { get_sidebar(); } ?>

    </div>
  </div>
</div>
<!-- Container End -->

<?php get_footer();
