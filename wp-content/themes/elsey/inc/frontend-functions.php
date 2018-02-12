<?php
/*
 * All Front-End Helper Functions
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/* Exclude category from blog */
if( ! function_exists( 'elsey_vt_excludeCat' ) ) {
  function elsey_vt_excludeCat($query) {
  	if ( $query->is_home ) {
  		$exclude_cat_ids = cs_get_option('blog_exclude_categories');
  		if($exclude_cat_ids) {
  			foreach( $exclude_cat_ids as $exclude_cat_id ) {
  				$exclude_from_blog[] = '-'. $exclude_cat_id;
  			}
  			$query->set('cat', implode(',', $exclude_from_blog));
  		}
  	}
  	return $query;
  }
  add_filter('pre_get_posts', 'elsey_vt_excludeCat');
}

/* Excerpt Length */
class ElseyExcerpt {

  // Default length (by WordPress)
  public static $length = 55;

  // Output: elsey_excerpt('short');
  public static $types = array(
    'short'   => 25,
    'regular' => 55,
    'long'    => 100
  );

  /**
   * Sets the length for the excerpt,
   * then it adds the WP filter
   * And automatically calls the_excerpt();
   *
   * @param string $new_length
   * @return void
   * @author Baylor Rae'
   */
  public static function length($new_length = 55) {
    ElseyExcerpt::$length = $new_length;
    add_filter('excerpt_length', 'ElseyExcerpt::new_length');
    ElseyExcerpt::output();
  }

  // Tells WP the new length
  public static function new_length() {
    if( isset(ElseyExcerpt::$types[ElseyExcerpt::$length]) )
      return ElseyExcerpt::$types[ElseyExcerpt::$length];
    else
      return ElseyExcerpt::$length;
  }

  // Echoes out the excerpt
  public static function output() {
    the_excerpt();
  }

}

// Custom Excerpt Length
if( ! function_exists( 'elsey_excerpt' ) ) {
  function elsey_excerpt($length = 55) {
    ElseyExcerpt::length($length);
  }
}

if ( ! function_exists( 'elsey_new_excerpt_more' ) ) {
  function elsey_new_excerpt_more( $more ) {
    return '[...]';
  }
  add_filter('excerpt_more', 'elsey_new_excerpt_more');
}

/* Tag Cloud Widget - Remove Inline Font Size */
if( ! function_exists( 'elsey_vt_tag_cloud' ) ) {
  function elsey_vt_tag_cloud($tag_string){
    return preg_replace("/style='font-size:.+pt;'/", '', $tag_string);
  }
  add_filter('wp_generate_tag_cloud', 'elsey_vt_tag_cloud', 10, 3);
}

/* Password Form */
if( ! function_exists( 'elsey_vt_password_form' ) ) {
  function elsey_vt_password_form( $output ) {
    $output = str_replace( 'type="submit"', 'type="submit" class=""', $output );
    return $output;
  }
  add_filter('the_password_form' , 'elsey_vt_password_form');
}

/* Maintenance Mode */
if( ! function_exists( 'elsey_vt_maintenance_mode' ) ) {
  function elsey_vt_maintenance_mode() {
    $maintenance_mode_page = cs_get_option( 'maintenance_mode_page' );
    $enable_maintenance_mode = cs_get_option( 'enable_maintenance_mode' );

    if ( isset($enable_maintenance_mode) && ! empty( $maintenance_mode_page ) && ! is_user_logged_in() ) {
      get_template_part('layouts/post/content', 'maintenance');
      exit;
    }
  }
  add_action( 'wp', 'elsey_vt_maintenance_mode', 1 );
}

/* Widget Layouts */
if ( ! function_exists( 'elsey_vt_footer_widgets' ) ) {
  function elsey_vt_footer_widgets() {
    $output = '';
    $footer_widget_layout = cs_get_option('footer_widget_layout');

    if( $footer_widget_layout ) {
       switch ( $footer_widget_layout ) {
        case 1: $widget = array('piece' => 1, 'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12'); break;
        case 2: $widget = array('piece' => 2, 'class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12'); break;
        case 3: $widget = array('piece' => 3, 'class' => 'col-lg-4 col-md-4 col-sm-6 col-xs-12'); break;
        case 4: $widget = array('piece' => 4, 'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12'); break;
        case 5: $widget = array('piece' => 3, 'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12', 'layout' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'queue' => 1); break;
        case 6: $widget = array('piece' => 3, 'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12', 'layout' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'queue' => 2); break;
        case 7: $widget = array('piece' => 3, 'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12', 'layout' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'queue' => 3); break;
        case 8: $widget = array('piece' => 4, 'class' => 'col-lg-2 col-md-3 col-sm-6 col-xs-12', 'layout' => 'col-lg-6 col-md-3 col-sm-6 col-xs-12', 'queue' => 1); break;
        case 9: $widget = array('piece' => 4, 'class' => 'col-lg-2 col-md-3 col-sm-6 col-xs-12', 'layout' => 'col-lg-6 col-md-3 col-sm-6 col-xs-12', 'queue' => 4); break;
        default : $widget = array('piece' => 4, 'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12'); break;
      }

      for( $i = 1; $i < $widget["piece"]+1; $i++ ) {
        $widget_class = ( isset( $widget["queue"] ) && $widget["queue"] == $i ) ? $widget["layout"] : $widget["class"];
        $output .= '<div class="'. $widget_class .'">';
        ob_start();
        if (is_active_sidebar('footer-'. $i)) {
          dynamic_sidebar( 'footer-'. $i );
        }
        $output .= ob_get_clean();
        $output .= '</div>';
      }
    }
    return $output;
  }
}

/* WP Link Pages */
if ( ! function_exists( 'elsey_wp_link_pages' ) ) {
  function elsey_wp_link_pages() {
    $defaults = array(
      'before'           => '<div class="wp-link-pages">' . esc_html__( 'Pages:', 'elsey' ),
      'after'            => '</div>',
      'link_before'      => '<span>',
      'link_after'       => '</span>',
      'next_or_number'   => 'number',
      'separator'        => ' ',
      'pagelink'         => '%',
      'echo'             => 1
    );
    wp_link_pages( $defaults );
  }
}

/* Custom Comment Area Modification */
if ( ! function_exists( 'elsey_comment_modification' ) ) {
  function elsey_comment_modification($comment, $args, $depth) {

    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
      $tag = 'div';
      $add_below = 'comment';
    } else {
      $tag = 'li';
      $add_below = 'div-comment';
    }

    $comment_class = empty( $args['has_children'] ) ? '' : 'parent';
    $reply_comment_text = cs_get_option('reply_comment_text') ? cs_get_option('reply_comment_text') : esc_html__( 'Reply', 'elsey' ); ?>

    <<?php echo esc_attr($tag); ?> <?php comment_class('item ' . $comment_class .' ' ); ?> id="comment-<?php comment_ID() ?>">

      <?php if ( 'div' != $args['style'] ) : ?>
      <div id="div-comment-<?php comment_ID() ?>" class="">
      <?php endif; ?>

        <div class="comment-theme">
            <div class="comment-image">
              <?php if ( $args['avatar_size'] != 0 ) {
                echo get_avatar( $comment, 80 );
              } ?>
            </div>
        </div><div class="comment-main-area">
          <div class="els-comments-meta">
            <h4><?php printf( '%s', get_comment_author() ); ?></h4>
            <span class="comments-date">
              <?php echo get_comment_date('F d, Y'); ?>
            </span>
          </div>
          <?php if ( $comment->comment_approved == '0' ) : ?>
          <em class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'elsey' ); ?></em>
          <?php endif; ?>
          <div class="comment-area">
            <?php comment_text(); ?>
          </div>
          <div class="comments-reply">
            <?php
            comment_reply_link( array_merge( $args, array(
            'reply_text' => '<span class="comment-reply-link">'. $reply_comment_text .'</span>',
            'before' => '',
            'class'  => '',
            'depth' => $depth,
            'max_depth' => $args['max_depth']
            ) ) );
            ?>
          </div>
        </div>

      <?php if ( 'div' != $args['style'] ) : ?>
      </div>
    <?php endif;
  }
}

/* Title Area */
if ( ! function_exists( 'elsey_title_area' ) ) {
  function elsey_title_area() {
    global $post, $wp_query;
    // Get post meta in all type of WP pages
    $elsey_id   = ( isset( $post ) ) ? $post->ID : false;
    $elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
    if (class_exists('WooCommerce')) { $elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id; }
    $elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

    if ($elsey_meta) {
      $title_bar    = $elsey_meta['titlebar_options'];
      $custom_title = ($title_bar === 'custom') ? $elsey_meta['title_custom_text'] : '';
      if (class_exists('WooCommerce')) {
        if (is_shop()) {
          if ($custom_title) {
            $custom_title = $custom_title;
          } elseif (is_post_type_archive()) {
            post_type_archive_title();
          } else {
            $custom_title = '';
          }
        }
      }
    } else {
      $custom_title = '';
    }
    /**
     * For strings with necessary HTML, use the following:
     * Note that I'm only including the actual allowed HTML for this specific string.
     * More info: https://codex.wordpress.org/Function_Reference/wp_kses
     */
    $allowed_html_array = array(
        'a' => array(
          'href' => array(),
        ),
        'span' => array(
          'class' => array(),
        )
    );
    if ( class_exists('WooCommerce') && ( is_product_category() || is_product_tag() ) ) {
      single_cat_title();
    } else if ( class_exists('WooCommerce') && is_search('product') ) {
      /* translators: %s: post title */
      printf(esc_html__( '&nbsp;Search Results for %s', 'elsey' ), '<mark class="dark">' . get_search_query() . '</mark>');
    } else {
      if($custom_title) {
        echo esc_attr($custom_title);
      } elseif (is_home()) {
        bloginfo('description');
      } elseif (is_search()) {
        /* translators: %s: title */
        printf(esc_html__( 'Search Results for %s', 'elsey' ), '<mark class="dark">' . get_search_query() . '</mark>');
      } elseif(is_404()) {
        esc_html_e('404 Error', 'elsey');
      } elseif (is_category()) {
        single_cat_title();
      } elseif (is_tag()) {
        single_tag_title(esc_html__('Posts Tagged: ', 'elsey'));
      } elseif (is_archive()) {
        if ( is_day() ) {
          /* translators: %s: post day */
          printf( wp_kses( __( 'Archive for <span>%s</span>', 'elsey' ), $allowed_html_array ), get_the_date());
        } elseif ( is_month() ) {
          /* translators: %s: post month */
          printf( wp_kses( __( 'Archive for <span>%s</span>', 'elsey' ), $allowed_html_array ), get_the_date( 'F, Y' ));
        } elseif ( is_year() ) {
          /* translators: %s: post date */
          printf( wp_kses( __( 'Archive for <span>%s</span>', 'elsey' ), $allowed_html_array ), get_the_date( 'Y' ));
        } elseif ( is_author() ) {
          /* translators: %s: author name */
          printf( wp_kses( __( 'Posts by: <span>%s</span>', 'elsey' ), $allowed_html_array ), get_the_author_meta( 'display_name', $wp_query->post->post_author ));
        } elseif ( is_shop() ) {
          echo esc_attr($custom_title);
        } else {
          esc_html__( 'Archives', 'elsey' );
        }
      } else {
        the_title();
      }
    }
  }
}

/* Pagination Function Blog */
if ( ! function_exists( 'elsey_blog_paging_nav' ) ) {
  function elsey_blog_paging_nav() {

    global $wp_query;
    $blog_pagination_style = cs_get_option('blog_pagination_style');
    $lmore_post_text = cs_get_option('lmore_post_text');
    $older_post_text = cs_get_option('older_post_text');
    $newer_post_text = cs_get_option('newer_post_text');

    if($blog_pagination_style === 'pagination_btn') {
      $lmore_post_text = $lmore_post_text ? $lmore_post_text : esc_html__( 'Load More', 'elsey' ); ?>
      <div class="els-load-more-box els-blog-load-more-box">
        <div class="els-load-more-link els-blog-load-more-link">
          <?php next_posts_link( '&nbsp;', $wp_query->max_num_pages ); ?>
        </div>
        <div class="els-load-more-controls els-blog-load-more-controls els-btn-mode">
          <div class="line-scale-pulse-out">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
          <a href="javascript:void(0);" id="els-blog-load-more-btn" class="els-btn"><?php echo esc_attr($lmore_post_text); ?></a>
          <a href="javascript:void(0);" id="els-loaded" class="els-btn"><?php echo esc_html__( 'All Loaded', 'elsey' ); ?></a>
        </div>
      </div>
    <?php
    } elseif($blog_pagination_style === 'pagination_nextprv') {
      $older_post_text = ($older_post_text) ? $older_post_text : esc_html__( 'Older Posts', 'elsey' );
      $newer_post_text = ($newer_post_text) ? $newer_post_text : esc_html__( 'Newer Posts', 'elsey' );
    ?>
      <div class="els-prev-next-pagination els-blog-single-pagination">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 older">
            <?php next_posts_link( '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt=""> '. $older_post_text, $wp_query->max_num_pages ); ?>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 newer">
            <?php previous_posts_link( $newer_post_text . ' <img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">', $wp_query->max_num_pages ); ?>
          </div>
        </div>
      </div>
    <?php
    } else {
      if ( function_exists('wp_pagenavi')) {
        wp_pagenavi();
      } else {
        $older_post_text = $older_post_text ? $older_post_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt="">';
        $newer_post_text = $newer_post_text ? $newer_post_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">';
        $big = 999999999;
        if($wp_query->max_num_pages == '1' ) {} else {echo '';}
        echo '<div class="wp-pagenavi">';
        echo paginate_links( array(
          'base'       => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
          'format'     => '?paged=%#%',
          'total'      => $wp_query->max_num_pages,
          'show_all'   => false,
          'current'    => max( 1, get_query_var('paged') ),
          'prev_text'  => $older_post_text,
          'next_text'  => $newer_post_text,
          'mid_size'   => 1,
          'type'       => 'list'
        ) );
        echo '</div>';
        if($wp_query->max_num_pages == '1' ) {} else {echo '';}
      }
    }
  }
}

/* Pagination Function Shop */
if ( ! function_exists( 'elsey_shop_paging_nav' ) ) {
  function elsey_shop_paging_nav() {
    global $wp_query;
    $pagination_style = cs_get_option('woo_load_style');
    $lmore_shop_text  = cs_get_option('lmore_shop_text');
    $older_shop_text  = cs_get_option('older_shop_text');
    $newer_shop_text  = cs_get_option('newer_shop_text');

    if($pagination_style === 'page_btn') {
      $lmore_shop_text = $lmore_shop_text ? $lmore_shop_text : esc_html__( 'Load More', 'elsey' ); ?>
      <div class="els-load-more-box els-shop-load-more-box">
        <div class="els-load-more-link els-shop-load-more-link">
          <?php next_posts_link( '&nbsp;', $wp_query->max_num_pages ); ?>
        </div>
        <div class="els-load-more-controls els-shop-load-more-controls els-btn-mode">
          <div class="line-scale-pulse-out">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
          <a href="javascript:void(0);" id="els-shop-load-more-btn" class="els-btn"><?php echo esc_attr($lmore_shop_text); ?></a>
          <a href="javascript:void(0);" id="els-loaded" class="els-btn"><?php echo esc_html__( 'All Loaded', 'elsey' ); ?></a>
        </div>
      </div>
    <?php
    } elseif($pagination_style === 'prev_next') {
      $older_shop_text = $older_shop_text ? $older_shop_text : esc_html__( 'Older Products', 'elsey' );
      $newer_shop_text = $newer_shop_text ? $newer_shop_text : esc_html__( 'Newer Products', 'elsey' ); ?>
      <div class="els-prev-next-pagination els-shop-pagination">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 older">
            <?php next_posts_link( '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt=""> '. $older_shop_text, $wp_query->max_num_pages ); ?>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 newer">
            <?php previous_posts_link( $newer_shop_text . ' <img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">', $wp_query->max_num_pages ); ?>
          </div>
        </div>
      </div><?php
    } else {
      if ( function_exists('wp_pagenavi')) {
        wp_pagenavi( array( 'query' => $wp_query ) );
      } else {
        $older_shop_text = $older_shop_text ? $older_shop_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-left.png" alt="">';
        $newer_shop_text = $newer_shop_text ? $newer_shop_text : '<img src="'.ELSEY_IMAGES.'/nav-arrow-right.png" alt="">';
        $big = 999999999;
        echo '<div class="wp-pagenavi">';
        echo paginate_links( array(
          'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
          'format'    => '?paged=%#%',
          'total'     => $wp_query->max_num_pages,
          'show_all'  => false,
          'current'   => max( 1, get_query_var('paged') ),
          'prev_text' => $older_shop_text,
          'next_text' => $newer_shop_text,
          'mid_size'  => 1,
          'type'      => 'list'
        ) );
        echo '</div>';
      }
    }
  }
}

/* Search Modal */
if ( ! function_exists( 'elsey_search_modal' ) ) {
  function elsey_search_modal() {
    if ( class_exists( 'WooCommerce' ) ) { ?>
      <div class="modal fade bs-example-modal-lg" id="els-search-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="searchform woocommerce-product-search" >
              <input type="search" name="s" id="els-prs" placeholder="<?php esc_html_e('Search', 'elsey'); ?>" />
              <input type="hidden" name="post_type" value="product" />
            </form>
          </div>
        </div>
        <button type="button" data-dismiss="modal" class="els-search-close">×</button>
      </div>
    <?php
    }
  }
}

/* Right Slide Menu */
if ( ! function_exists( 'elsey_right_slide_menu' ) ) {
  function elsey_right_slide_menu() {
    if (is_active_sidebar('sidebar-right')) { ?>
      <div id="els-sidebar-menu" class="els-sidebar els-sidebar-menu">
        <a href="javascript:void(0)" id="els-sidebar-menu-close" class="els-sidebar-menu-close">×</a>
        <div class="els-aside">
          <?php dynamic_sidebar('sidebar-right'); ?>
        </div>
      </div>
    <?php
    }
  }
}

/* Preloader Function */
if ( ! function_exists( 'elsey_preloader_option' ) ) {
  function elsey_preloader_option() {
    $elsey_show_preloader = cs_get_option('show_preloader');
    if ($elsey_show_preloader) {
      $preloader_styles = cs_get_option('preloader_styles');
      if(isset($preloader_styles)) {
        $preloader_styles_list = array("ball-pulse"=>3,"ball-grid-pulse"=>9,"ball-clip-rotate"=>1,"ball-clip-rotate-pulse"=>2,"square-spin"=>1,
          "ball-clip-rotate-multiple"=>2,"ball-pulse-rise"=>5,"ball-rotate"=>1,"cube-transition"=>2,"ball-zig-zag"=>2,
          "ball-zig-zag-deflect"=>2,"ball-triangle-path"=>3,"ball-scale"=>1,"line-scale"=>5,"line-scale-party"=>4,
          "ball-scale-multiple"=>3,"ball-pulse-sync"=>3,"ball-beat"=>3,"line-scale-pulse-out"=>5,"line-scale-pulse-out-rapid"=>5,
          "ball-scale-ripple"=>1,"ball-scale-ripple-multiple"=>3,"ball-spin-fade-loader"=>8,"line-spin-fade-loader"=>8,"triangle-skew-spin"=>1,
          "pacman"=>5,"ball-grid-beat"=>9,"semi-circle-spin"=>1,"ball-scale-random"=>3);
        ?>
        <div class="els-preloader-mask">
          <div id="els-preloader-wrap">
            <div class="els-preloader-html <?php echo esc_attr($preloader_styles); ?>">
              <?php for ($x = 0; $x < $preloader_styles_list[$preloader_styles]; $x++) { echo '<div></div>'; } ?>
            </div>
          </div>
        </div>
        <?php
      }
    }
  }
}
