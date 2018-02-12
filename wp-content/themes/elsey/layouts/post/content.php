<?php
/**
 * Template part for displaying posts.
 */
// Blog Theme Option
$blog_listing_style    = cs_get_option('blog_listing_style');
$blog_listing_columns  = cs_get_option('blog_listing_columns');
$blog_read_more_option = cs_get_option('blog_read_more_option');
$blog_share_option     = cs_get_option('blog_share_option');
$blog_excerpt_option   = cs_get_option('blog_excerpt_option');
$blog_popup_option     = cs_get_option('blog_popup_option');
$blog_metas_hide       = (array) cs_get_option('blog_metas_hide');
$blog_excerpt_length   = cs_get_option('blog_excerpt_length');
$blog_excerpt_length   = $blog_excerpt_length ? $blog_excerpt_length : '55';

// Blog Page Translation Text Option
$blog_read_more_option_text = cs_get_option('read_more_text') ? cs_get_option('read_more_text') : esc_html__( 'Continue Reading', 'elsey' );

// Blog Page Layout Option
$post_type_metabox = get_post_meta( get_the_ID(), 'post_type_metabox', true );
$post_featured_options = get_post_meta( get_the_ID(), 'post_featured_options', true );
$blog_large_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );

if($blog_listing_style === 'els-blog-masonry') {
  if (!empty($post_featured_options['masonry_featured_image'])) {
    $img_id = $post_featured_options['masonry_featured_image'];
    $attachment = wp_get_attachment_image_src( $img_id, 'full' );
    $blog_large_image = $attachment[0];
  } else {
    $blog_large_image = $blog_large_image[0];
  }
} else {
  $blog_large_image = $blog_large_image[0];
}

$blog_sticky_class = (is_sticky(get_the_ID())) ? 'sticky' : '';
$blog_sticky_pcls  = (is_sticky(get_the_ID())) ? 'els-blog-sticky' : '';

if($blog_listing_columns === 'els-blog-col-3') {
  if ($blog_read_more_option && $blog_share_option) {
    $blog_more_col_class  = 'col-lg-5 col-md-12 col-sm-12 col-xs-12';
    $blog_share_col_class = 'col-lg-7 col-md-12 col-sm-12 col-xs-12';
  } else if ($blog_read_more_option || $blog_share_option) {
    $blog_more_col_class  = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
    $blog_share_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
  } else {
    $blog_more_col_class  = 'col-lg-5 col-md-12 col-sm-12 col-xs-12';
    $blog_share_col_class = 'col-lg-7 col-md-12 col-sm-12 col-xs-12';
  }
} else {
  if ($blog_read_more_option && $blog_share_option) {
    $blog_more_col_class  = 'col-lg-5 col-md-5 col-sm-5 col-xs-12';
    $blog_share_col_class = 'col-lg-7 col-md-7 col-sm-7 col-xs-12';
  } else if ($blog_read_more_option || $blog_share_option) {
    $blog_more_col_class  = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
    $blog_share_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
  } else {
    $blog_more_col_class  = 'col-lg-5 col-md-5 col-sm-5 col-xs-12';
    $blog_share_col_class = 'col-lg-7 col-md-7 col-sm-7 col-xs-12';
  }
} ?>

<!-- Blog Start -->
<div class="els-recent-blog <?php echo esc_attr($blog_sticky_pcls); ?>">

  <div id="post-<?php the_ID(); ?>" <?php post_class('els-blog-post '.$blog_sticky_class); ?>>

    <!-- Blog Introduction Start -->
    <div class="els-blog-intro">

      <h3 class="els-blog-heading">
        <a href="<?php echo esc_url(get_permalink()); ?>">
          <?php echo esc_attr(get_the_title()); ?>
        </a>
      </h3>

      <?php if (!in_array('author', $blog_metas_hide) || !in_array('category', $blog_metas_hide) || !in_array('date', $blog_metas_hide)) { // Meta's Hide ?>
        <div class="els-blog-cat-author">
          <ul>
          <?php if (!in_array('category', $blog_metas_hide)) { // Category Hide  ?>
            <li class="els-blog-cat">
              <label><?php esc_html_e('Category: ', 'elsey'); ?></label>
              <?php
              $categories = get_the_category();
              if ($categories) {
                the_category('<span>&nbsp;&amp;&nbsp;</span>');
              } ?>
            </li>
          <?php } if (!in_array('author', $blog_metas_hide)) { // Author Hide
            if (!in_array('category', $blog_metas_hide)) { ?>
              <li><span>/</span></li>
            <?php } ?>
            <li><span><?php esc_html_e('by', 'elsey') ?></span></li>
            <li>
              <?php printf('<a href="%1$s" rel="author">%2$s</a>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()); ?>
            </li>
          <?php } if (!in_array( 'date', $blog_metas_hide)) {?>
            <li><span>/</span></li>
            <li><?php echo '<div class="els-blog-single-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>'; ?></li>
          <?php } ?>
          </ul>
        </div>
      <?php } ?>

    </div>
    <!-- Blog Introduction End -->

    <!-- Blog Publish Start -->
    <div class="els-blog-publish">

      <?php
      if (('gallery' == get_post_format()) && !empty($post_type_metabox['gallery_post_format'])) {

        $images = [];
        $ids    = explode( ',', $post_type_metabox['gallery_post_format'] );

        foreach ( $ids as $id ) {
          $attachment = wp_get_attachment_image_src( $id, 'full' );
          if ( isset($attachment[0]) ) {
            array_push($images, $attachment[0]);
          }
        }

        if ( count($images) > 0 ) {

          echo '<div class="els-blog-slider-box"><ul class="owl-carousel els-feature-img-carousel">';

          foreach ( $ids as $id ) {
            $attachment = wp_get_attachment_image_src($id, 'full');
            $alt        = get_post_meta($id, '_wp_attachment_image_alt', true);
            $alt        = $alt ? $alt : get_the_title();
            $g_img      = $attachment[0];

            if($blog_listing_style === 'els-blog-masonry') {
              $post_img = ($g_img) ? $g_img : '';
            } else {
              if($blog_listing_columns === 'els-blog-col-3') {
                if($g_img){
                  if(class_exists('Aq_Resize')) {
                    $featured_img = aq_resize( $g_img, '370', '235', true );
                    $post_img = ($featured_img) ? $featured_img : PLUGIN_ASTS . '/images/370x235.jpg';
                  } else {
                    $post_img = $g_img;
                  }
                } else {
                  $post_img = '';
                }
              } else if($blog_listing_columns === 'els-blog-col-2') {
                if($g_img){
                  if(class_exists('Aq_Resize')) {
                    $featured_img = aq_resize( $g_img, '570', '355', true );
                    $post_img = ($featured_img) ? $featured_img : PLUGIN_ASTS . '/images/570x355.jpg';
                  } else {
                    $post_img = $g_img;
                  }
                } else {
                  $post_img = '';
                }
              } else {
                if($g_img){
                  $post_img = $g_img;
                } else {
                  $post_img = '';
                }
              }
            }

            if ($blog_popup_option) {
              $popup_class = 'els-img-popup';
              $link_to = ($g_img) ? $g_img : get_the_permalink();
            } else {
              $popup_class = 'els-img-link';
              $link_to = get_the_permalink();
            }

            if ($post_img) {
              echo '<li><a href='.esc_url($link_to).' class="'.esc_attr($popup_class).'"><img src="'.esc_url($post_img).'" alt="'.esc_attr($alt).'" /></a></li>';
            } else {
              echo '';
            }
          }
          echo '</ul></div>';

        } else {
          echo '';
        }

      } elseif (('audio' == get_post_format()) && ! empty( $post_type_metabox['audio_post_format'])) {

        echo '<div class="els-music">'. $post_type_metabox['audio_post_format'] .'</div>';

      } elseif (('video' == get_post_format()) && ! empty( $post_type_metabox['video_post_format'])) {

        echo '<div class="els-video">'. $post_type_metabox['video_post_format'] .'</div>';

      } elseif ($blog_large_image) {

        if ($blog_listing_style === 'els-blog-masonry') {
          $post_img = ($blog_large_image) ? $blog_large_image : PLUGIN_ASTS . '/images/1170x705.jpg';
        } else {
          if ($blog_listing_columns === 'els-blog-col-3') {
            if (class_exists('Aq_Resize')) {
              $post_img = aq_resize( $blog_large_image, '370', '235', true );
              $post_img = ($post_img) ? $post_img : PLUGIN_ASTS . '/images/370x235.jpg';
            } else {
              $post_img = $blog_large_image;
            }
          } else if ($blog_listing_columns === 'els-blog-col-2') {
            if (class_exists('Aq_Resize')) {
              $post_img = aq_resize( $blog_large_image, '570', '355', true );
              $post_img = ($post_img) ? $post_img : PLUGIN_ASTS . '/images/570x355.jpg';
            } else {
              $post_img = $blog_large_image;
            }
          } else {
            $post_img = $blog_large_image;
          }
        }

        if ($blog_popup_option) {
          $popup_class = 'els-img-popup';
          $link_to = $blog_large_image;
        } else {
          $popup_class = 'els-img-link';
          $link_to = get_the_permalink();
        } ?>

        <div class="els-blog-feature-img">
          <a href="<?php echo esc_url($link_to); ?>" class="<?php echo esc_attr($popup_class); ?>"><img src="<?php echo esc_url($post_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/></a>
        </div>

      <?php } else { echo ''; } ?>

    </div>
    <!-- Blog Publish End -->

    <?php
    if($blog_excerpt_option) { ?>
    <!-- Blog Excerpt Start -->
      <div class="els-blog-excerpt">
        <?php
        if ( function_exists( 'elsey_excerpt' ) ) {
          elsey_excerpt($blog_excerpt_length);
        }
        if ( function_exists( 'elsey_wp_link_pages' ) ) {
          echo elsey_wp_link_pages();
        }
        ?>
      </div>
    <!-- Blog Excerpt End -->
    <?php
    }

    if ($blog_read_more_option || $blog_share_option) { ?>
    <!-- Blog More Start -->
      <div class="els-blog-more row">
        <?php if($blog_read_more_option) { ?>
          <div class="els-blog-readmore <?php echo esc_attr($blog_more_col_class); ?>">
            <a href="<?php echo esc_url(get_permalink()); ?>">
              <?php echo esc_attr($blog_read_more_option_text); ?>
            </a>
          </div>
        <?php } if($blog_share_option) { ?>
          <div class="els-blog-share <?php echo esc_attr($blog_share_col_class); ?>">
            <?php if ( function_exists( 'elsey_wp_share_option' ) ) { echo elsey_wp_share_option(); } ?>
          </div>
        <?php } ?>
      </div>
    <!-- Blog More End -->
    <?php } ?>

  </div>
</div>
<!-- Blog End -->