<?php
/**
 * Single Post.
 */

// Single Post Type Meta
$elsey_post_type_metabox    = get_post_meta(get_the_ID(), 'post_type_metabox', true);
$elsey_gallery_display_type = !empty($elsey_post_type_metabox['gallery_display_type']) ? $elsey_post_type_metabox['gallery_display_type'] : 'img-slider';

// Single Theme Option
$elsey_single_featured_image = cs_get_option('single_featured_image');
$elsey_single_popup_option   = cs_get_option('single_popup_option');
$elsey_single_share_option   = cs_get_option('single_share_option');
$elsey_single_metas_hide     = (array) cs_get_option('single_metas_hide');

// Single Featured Image Option
$elsey_single_large_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '');
$elsey_single_large_image = $elsey_single_large_image[0];   ?>

<!-- Single Post Start -->
<div class="els-blog-single">
  <div id="post-<?php the_ID(); ?>">

    <div class="els-blog-intro">
      <h1 class="els-blog-heading"><?php echo esc_attr(get_the_title()); ?></h1>
      <?php if (!in_array('author', $elsey_single_metas_hide) || !in_array('category', $elsey_single_metas_hide)) { // Meta's Hide ?>
        <div class="els-blog-cat-author">
          <ul>
            <?php if (!in_array('category', $elsey_single_metas_hide)) { // Category Hide  ?>
              <li class="els-blog-cat"><label><?php esc_html_e('Category:&nbsp;', 'elsey'); ?></label><?php $categories = get_the_category(); if ($categories) { the_category('<span>&nbsp;&amp;&nbsp;</span>'); } ?></li><?php }
              if (!in_array('author', $elsey_single_metas_hide)) { // Author Hide
                if (!in_array('category', $elsey_single_metas_hide)) { ?><li><span>&nbsp;/&nbsp;</span></li><?php } ?><li class="els-by"><span>by</span></li><li><?php printf('<a href="%1$s" rel="author">%2$s</a>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()); ?></li>
            <?php } if ( !in_array( 'date', $elsey_single_metas_hide) ) {
              if( ( ( ( 'audio' == get_post_format() ) || ( 'video' == get_post_format() ) ) && !$elsey_single_large_image ) || !$elsey_single_featured_image ) {
                if (!in_array('author', $elsey_single_metas_hide) || !in_array('category', $elsey_single_metas_hide)) { ?>
                <li><span><span>&nbsp;/&nbsp;</span></span></li>
              <?php } ?>
              <li><?php echo '<div class="els-blog-single-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>'; ?></li>
            <?php } } ?>
          </ul>
        </div>
      <?php } // Blog Introduction End ?>
    </div>

    <div class="els-blog-publish">
      <?php
      // Blog Publish Start
      if($elsey_single_featured_image) {
        if (('gallery' == get_post_format()) && ($elsey_gallery_display_type == 'img-slider') && ! empty( $elsey_post_type_metabox['gallery_post_format'])) {

          if (!in_array( 'date', $elsey_single_metas_hide)) {
            echo '<div class="els-blog-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>';
          }  ?>

          <div class="els-blog-slider-box els-featured">
            <ul class="owl-carousel els-feature-img-carousel">
              <?php
              $ids = explode( ',', $elsey_post_type_metabox['gallery_post_format'] );
              foreach ( $ids as $id ) {
                $attachment = wp_get_attachment_image_src( $id, 'fullsize' );
                $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                $alt = $alt ? esc_attr($alt) : esc_attr(get_the_title());

                $post_img = $attachment[0];
                $post_img = $post_img ? $post_img : ELSEY_PLUGIN_ASTS . '/images/1170x705_sl.jpg';

                if ($elsey_single_popup_option) {
                  $link_open = '<a href='. esc_url($post_img).' class="els-img-popup">';
                  $link_close = '</a>';
                } else {
                  $link_open  = '';
                  $link_close = '';
                }
                echo '<li>'. $link_open .'<img src="'.esc_url($post_img).'" alt="'.$alt.'" />'. $link_close .'</li>';
              } ?>
            </ul>
          </div>

        <?php
        } elseif ( ('gallery' == get_post_format()) && ($elsey_gallery_display_type == 'img-gallery') && ! empty( $elsey_post_type_metabox['gallery_post_format'] ) ) {

          if (!in_array( 'date', $elsey_single_metas_hide)) {
            echo '<div class="els-blog-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>';
          } ?>

          <div class="els-gallery els-featured">
            <?php
            $ids = explode( ',', $elsey_post_type_metabox['gallery_post_format'] );
            $count_img = count($ids);
            $count = 0; $row_img = 1;
            $row_number = ceil($count_img/2);

            foreach ( $ids as $id ) {
              $attachment = wp_get_attachment_image_src( $id, 'full' );
              $alt = get_post_meta($id, '_wp_attachment_image_alt', true);

              $count++;

              if( ($count === 1) || (($count % 2) === 1) ) {

                echo '<ul class="row">';

                if( ($count_img === 1) || (($row_number % 2) === 1 && ($row_number === $row_img)) ) {
                  echo '<li class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                  echo '<a href='. esc_url($attachment[0]) .'><img src="'. esc_url($attachment[0]) .'" alt="'. esc_attr($alt) .'" /></a></li>';
                } else {
                  if( ($row_img === 1) || (($row_img % 2) === 1) ) {
                      echo '<li class="box col-lg-8 col-md-8 col-sm-8 col-xs-8">';
                      echo '<a href='. esc_url($attachment[0]) .'><img src="'. aq_resize( $attachment[0], '575', '320', true ) .'" alt="'. esc_attr($alt) .'" /></a></li>';
                    } else {
                      echo '<li class="box col-lg-5 col-md-5 col-sm-5 col-xs-5">';
                      echo '<a href='. esc_url($attachment[0]) .'><img src="'. aq_resize( $attachment[0], '380', '230', true ) .'" alt="'. esc_attr($alt) .'" /></a></li>';
                    }
                }

              } else {

                if( ($row_img === 1) || (($row_img % 2) === 1) ) {
                  echo '<li class="box col-lg-4 col-md-4 col-sm-4 col-xs-4">';
                  echo '<a href='. esc_url($attachment[0]) .'><img src="'. aq_resize( $attachment[0], '250', '320', true ) .'" alt="'. esc_attr($alt) .'" /></a></li>';
                } else {

                  echo '<li class="box col-lg-7 col-md-7 col-sm-7 col-xs-7">';
                  echo '<a href='. esc_url($attachment[0]) .'><img src="'. aq_resize( $attachment[0], '445', '230', true ) .'" alt="'. esc_attr($alt) .'" /></a></li>';
                }
              }

              if((($count % 2) === 0) || ($count === $count_img)) {
                echo '</ul>';
                $row_img++;
              }

            } ?>
          </div>

        <?php
        } elseif ( ('audio' == get_post_format()) && ! empty( $elsey_post_type_metabox['audio_post_format'] ) ) { ?>

          <div class="els-music els-featured">
            <?php echo $elsey_post_type_metabox['audio_post_format']; ?>
          </div>

        <?php
        } elseif ( ('video' == get_post_format()) && ! empty( $elsey_post_type_metabox['video_post_format'] ) ) { ?>

          <div class="els-video els-featured">
            <?php echo $elsey_post_type_metabox['video_post_format']; ?>
          </div>

        <?php
        } elseif ( $elsey_single_large_image ) {

          if (!in_array( 'date', $elsey_single_metas_hide)) {
            echo '<div class="els-blog-date"><span>'.get_the_time('d').'</span> '.get_the_time('M').'</div>';
          } ?>

          <div class="els-blog-feature-img els-featured">
            <?php
            if ($elsey_single_popup_option) {
              echo '<a href='. esc_url($elsey_single_large_image).' class="els-img-popup">';
            }?>
            <img src="<?php echo esc_url($elsey_single_large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
            <?php if ($elsey_single_popup_option) { echo '</a>'; } ?>
          </div>

        <?php
        } else { echo ''; }
      } // Blog Publish End ?>
    </div>

    <div class="els-blog-content">
      <?php
      the_content();
      if ( function_exists( 'elsey_wp_link_pages' ) ) {
        echo elsey_wp_link_pages();
      } ?>
    </div>

    <?php
    // Single More Start
    if (!in_array('tag', $elsey_single_metas_hide) && $elsey_single_share_option) {
      $single_tags_col_class  = 'col-lg-5 col-md-6 col-sm-6 col-xs-12';
      $single_share_col_class = 'col-lg-7 col-md-6 col-sm-6 col-xs-12';
    } else if (!in_array('tag', $elsey_single_metas_hide) || $elsey_single_share_option) {
      $single_tags_col_class  = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
      $single_share_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
    }
    if ( !in_array('tag', $elsey_single_metas_hide) || $elsey_single_share_option) { ?>

      <div class="els-blog-more row">
        <?php if(!in_array('tag', $elsey_single_metas_hide)) { ?>
          <div class="els-blog-tags <?php echo esc_attr($single_tags_col_class); ?>">
            <div class="els-tag-list">
              <?php echo the_tags('', '', ''); ?>
            </div>
          </div>
        <?php } if($elsey_single_share_option) { ?>
          <div class="els-blog-share <?php echo esc_attr($single_share_col_class); ?>">
            <?php if ( function_exists( 'elsey_wp_share_option' ) ) { echo elsey_wp_share_option(); } ?>
          </div>
        <?php } ?>
      </div>
    <?php } // Single More End ?>

  </div>
</div>
<!-- Single Post End -->