<?php
/*
 * The sidebar containing the main widget area.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

// Theme Option Sidebar Position
global $post;
$elsey_id    = ( isset( $post ) ) ? $post->ID : false;
$elsey_id    = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id    = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;

$elsey_page_layout_options = get_post_meta( $elsey_id, 'page_layout_options', true );
$elsey_post_layout_options = get_post_meta( $elsey_id, 'post_page_layout_options', true);

$elsey_blog_sidebar_position = cs_get_option('blog_sidebar_position');
$elsey_blog_widget_selected  = cs_get_option('blog_sidebar_widget');
$elsey_post_sidebar_position = cs_get_option('single_sidebar_position');
$elsey_post_widget_selected  = cs_get_option('single_sidebar_widget');
$elsey_page_show_sidebar     = false;
$elsey_post_page_layout      = '';

if ($elsey_id && is_page(get_the_ID())) {

  if ($elsey_page_layout_options) {
    $elsey_page_show_sidebar     = $elsey_page_layout_options['page_show_sidebar'];
    $elsey_page_sidebar_position = $elsey_page_layout_options['page_sidebar_position'];

    if($elsey_page_show_sidebar == true) {
      if($elsey_page_sidebar_position === 'sidebar-right') {
        $elsey_col_layout_class = 'els-right-col';
      } else {
        $elsey_col_layout_class = 'els-left-col';
      }
    } else {
      $elsey_col_layout_class = '';
    }
  } else {
    $elsey_col_layout_class = '';
  }

} elseif ( !is_page(get_the_ID()) && !is_single(get_the_ID()) && ($elsey_blog_sidebar_position !== 'sidebar-hide') ) {

  if ($elsey_blog_sidebar_position === 'sidebar-hide') {
    $elsey_col_layout_class = '';
  } elseif ($elsey_blog_sidebar_position === 'sidebar-left') {
    $elsey_col_layout_class = 'els-left-col';
  } else {
    $elsey_col_layout_class = 'els-right-col';
  }

} elseif ($elsey_id && is_single()) {

  if ($elsey_post_layout_options) {
    $elsey_post_page_layout  = $elsey_post_layout_options['post_page_layout'];
    $elsey_post_show_sidebar = $elsey_post_layout_options['post_page_show_sidebar'];
    $elsey_post_sb_position  = $elsey_post_layout_options['post_page_sidebar_position'];
  } else {
    $elsey_post_page_layout  = '';
    $elsey_post_show_sidebar = '';
    $elsey_post_sb_position  = '';
  }

  if ( ($elsey_post_page_layout === 'less-width') || ($elsey_post_page_layout === 'full-width') ) {
    if ($elsey_post_show_sidebar) {
      if($elsey_post_sb_position === 'sidebar-left') {
        $elsey_col_layout_class = 'els-left-col';
      } else if($elsey_post_sb_position === 'sidebar-right') {
        $elsey_col_layout_class = 'els-right-col';
      }
    } else {
      $elsey_col_layout_class = '';
    }
  } else {
    if ($elsey_post_sidebar_position === 'sidebar-hide') {
      $elsey_col_layout_class = '';
    } elseif ($elsey_post_sidebar_position === 'sidebar-left') {
      $elsey_col_layout_class = 'els-left-col';
    } else {
      $elsey_col_layout_class = 'els-right-col';
    }
  }

} else {
  $elsey_col_layout_class = '';
}
?>

<!-- Sidebar Column Start -->
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 els-sidebar <?php echo esc_attr($elsey_col_layout_class); ?>">
  <?php
  if ( $elsey_id && is_page(get_the_ID()) && ($elsey_page_show_sidebar == true) && isset($elsey_page_layout_options['page_sidebar_widget']) ) {
    if (is_active_sidebar($elsey_page_layout_options['page_sidebar_widget'])) {
 	    dynamic_sidebar($elsey_page_layout_options['page_sidebar_widget']);
    } else {
    	if (is_active_sidebar('sidebar-main')) {
      	  dynamic_sidebar('sidebar-main');
    	}
    }
  } elseif ( !is_page() && !is_single() && ($elsey_blog_sidebar_position !== 'sidebar-hide') && isset($elsey_blog_widget_selected) ) {
 	  if (is_active_sidebar($elsey_blog_widget_selected)) {
      dynamic_sidebar($elsey_blog_widget_selected);
    }
  } elseif ( $elsey_id && is_single() && ($elsey_post_page_layout !== 'theme-default') && ($elsey_post_show_sidebar == true) && isset($elsey_post_layout_options['post_page_sidebar_widget']) ) {
 	  if (is_active_sidebar($elsey_post_layout_options['post_page_sidebar_widget'])) {
      dynamic_sidebar($elsey_post_layout_options['post_page_sidebar_widget']);
    }
  } elseif ( is_single() && ($elsey_post_page_layout === 'theme-default') && ($elsey_post_sidebar_position !== 'sidebar-hide') && isset($elsey_post_widget_selected) ) {
 	  if (is_active_sidebar($elsey_post_widget_selected)) {
      dynamic_sidebar($elsey_post_widget_selected);
    }
  } else {
 	  if (is_active_sidebar('sidebar-main')) {
      dynamic_sidebar('sidebar-main');
    }
  } ?>
</div>
<!-- Sidebar Column End -->