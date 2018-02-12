<?php
/*
 * The template for displaying archive pages.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

$elsey_content_padding = ($elsey_meta) ? $elsey_meta['content_spacings'] : '';
if ($elsey_content_padding && $elsey_content_padding !== 'els-padding-none') {
  $elsey_content_top_spacings = $elsey_meta['content_top_spacings'];
  $elsey_content_btm_spacings = $elsey_meta['content_btm_spacings'];
  if ($elsey_content_padding === 'els-padding-custom') {
		$elsey_content_top_spacings = ($elsey_content_top_spacings) ? 'padding-top:'. elsey_check_px($elsey_content_top_spacings) .' !important;' : '';
		$elsey_content_btm_spacings = ($elsey_content_btm_spacings) ? 'padding-bottom:'. elsey_check_px($elsey_content_btm_spacings) .' !important;' : '';
		$elsey_custom_padding = $elsey_content_top_spacings . $elsey_content_btm_spacings;
  } else {
		$elsey_custom_padding = '';
  }
} else {
  $elsey_custom_padding = '';
}

// Theme Options
$elsey_blog_page_layout      = cs_get_option('blog_page_layout');
$elsey_blog_listing_style    = cs_get_option('blog_listing_style');
$elsey_blog_listing_columns  = cs_get_option('blog_listing_columns');
$elsey_blog_sidebar_position = cs_get_option('blog_sidebar_position');

// Page Layout
if ($elsey_blog_page_layout === 'full-width') {
  $elsey_parent_class = 'els-full-width';
  $elsey_layout_class = 'container';
} else {
  $elsey_parent_class = 'els-less-width';
  $elsey_layout_class = 'container els-reduced';
}

// Sidebar Position
if ($elsey_blog_sidebar_position === 'sidebar-hide') {
  $elsey_column_class     = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
  $elsey_sidebar_position = 'sidebar-hide';
} elseif ($elsey_blog_sidebar_position === 'sidebar-left') {
  $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-left-col';
  $elsey_sidebar_position = 'sidebar-left';
} else {
  $elsey_column_class     = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-has-right-col';
  $elsey_sidebar_position = 'sidebar-right';
}

// Blog Style
if ($elsey_blog_listing_style === 'els-blog-masonry') {
  $elsey_blog_listing_style_class = 'els-blog-masonry';
  $elsey_blog_masonry_grid_class  = 'els-blog-masonry-wrap';
  $elsey_blog_masonry_item_class  = 'els-blog-masonry-item';
} else {
  $elsey_blog_listing_style_class = 'els-blog-standard';
  $elsey_blog_masonry_grid_class  = '';
  $elsey_blog_masonry_item_class  = '';
}

// Column Style
if($elsey_blog_listing_columns === 'els-blog-col-2') {
  $elsey_blog_grid_number  = 2;
  $elsey_blog_column_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
} else if($elsey_blog_listing_columns === 'els-blog-col-3') {
  $elsey_blog_grid_number  = 3;
  $elsey_blog_column_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
} else {
  $elsey_blog_grid_number  = 1;
  $elsey_blog_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
}

get_header(); ?>

<!-- Container Start -->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class.' '.$elsey_content_padding); ?>" style="<?php echo esc_attr($elsey_custom_padding);?>">
  <div class="<?php echo esc_attr($elsey_layout_class); ?>"> 
    <div class="row">

      <?php if ($elsey_sidebar_position === 'sidebar-left') { get_sidebar(); } ?>

      <!-- Content Col Start -->
      <div class="<?php echo esc_attr($elsey_column_class); ?> els-content-col">
        <div class="row els-content-area">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="els-blog-wrapper <?php echo esc_attr($elsey_blog_listing_style_class); ?>">
              <div class="els-blog-inner <?php echo esc_attr($elsey_blog_masonry_grid_class); ?>">

                <?php if ( have_posts() ) :
                  $count_all_post = $GLOBALS['wp_query']->post_count;
                  $count = 0;

               	  while ( have_posts() ) : the_post();
                    $count++;

                    if ($elsey_blog_listing_style === 'els-blog-masonry') {
                      if( $count === 1 ) {
                        echo '<div class="els-blog-masonry-gutter"></div>';
                        echo '<div class="'.esc_attr($elsey_blog_column_class).' els-blog-masonry-sizer"></div>';
                      }
                    } else {
                      if ( $elsey_blog_grid_number === 1) {
                        echo '<div class="row">';
                      } else {
                        if( $count === 1 ) {
                          echo '<div class="row">';
                        } else if(( $count % $elsey_blog_grid_number ) === 1 ) {
                          echo '<div class="row">';
                        }
                      }
                    }

                    echo '<div class="'.esc_attr($elsey_blog_column_class.' '.$elsey_blog_masonry_item_class).'">';
                      get_template_part( 'layouts/post/content' );
                    echo '</div>';

                    if ($elsey_blog_listing_style !== 'els-blog-masonry') {        
                      if ( $elsey_blog_grid_number === 1 ) {
                        echo '</div>';
                      } else {
                        if((($count % $elsey_blog_grid_number) === 0) || ($count === ($count_all_post))) {
                          echo '</div>';
                        }
                      }
                    }
               	  endwhile;
                else :
               	  get_template_part( 'layouts/post/content', 'none' );
                endif; ?>
                
              </div>

              <?php elsey_blog_paging_nav(); wp_reset_postdata(); ?>

            </div>
          </div>
        </div>
      </div>
      <!-- Content Col End -->

      <?php if ($elsey_sidebar_position === 'sidebar-right') { get_sidebar(); } ?>
    
    </div>
  </div>
</div>
<!-- Container End -->

<?php get_footer();
