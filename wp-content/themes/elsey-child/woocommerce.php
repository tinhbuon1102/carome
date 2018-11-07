<?php
/*
 * The template for displaying all pages.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_content_padding = $elsey_meta['content_spacings'];
} else {
  $elsey_content_padding = '';
}

if ($elsey_content_padding && $elsey_content_padding !== 'els-padding-none') {
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

// Layout Options
$elsey_woo_page_layout      = cs_get_option('woo_page_layout');
$elsey_woo_load_style       = cs_get_option('woo_load_style');
$elsey_woo_sidebar_position = cs_get_option('woo_sidebar_position');
$elsey_woo_product_columns  = cs_get_option('woo_product_columns');
$elsey_woo_product_columns  = $elsey_woo_product_columns ? $elsey_woo_product_columns : '4';

if (is_shop() || is_product_category() || is_product_tag()) {
	if (isCustomerInPrivateEvent()) {
	  $elsey_parent_class = 'els-less-width carome-event-shop';
	  $elsey_layout_class = 'container els-reduced';
  } else {
	  if ($elsey_woo_page_layout === 'full-width') {
    $elsey_parent_class = 'els-full-width';
    $elsey_layout_class = 'container';
  } else {
    $elsey_parent_class = 'els-less-width';
    $elsey_layout_class = 'container els-reduced';
  }
  }
  
  if (isCustomerInPrivateEvent()) { //for event archive
		$elsey_shop_col_class = 'els-shop-wrapper shop-archive-event woo-col-list';
	} else {
		$elsey_shop_col_class = 'els-shop-wrapper woo-col-'.esc_attr($elsey_woo_product_columns);
	}
  
	if (isCustomerInPrivateEvent()) {
		$elsey_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar col-event-shop';
	} else {
		if ($elsey_woo_sidebar_position === 'sidebar-hide') {
    $elsey_column_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
  } else {
    if ($elsey_woo_sidebar_position === 'sidebar-left') {
      $elsey_column_class = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-shop-has-sidebar els-has-left-col';
    } else {
      $elsey_column_class = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 els-has-sidebar els-shop-has-sidebar els-has-right-col';
    }
  }
		
	}
  

} else {
  $elsey_parent_class   = 'els-less-width';
  $elsey_layout_class   = 'container els-reduced max-width--large';
  $elsey_column_class   = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 els-no-sidebar';
  $elsey_shop_col_class = 'woo-content';
}

$a = $elsey_woo_sort_filter = cs_get_option('woo_sort_filter');
$b = $elsey_woo_result_cnt  = cs_get_option('woo_result_count');

if ($a && $b) {
  $elsey_woo_result_cnt_class  = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
  $elsey_woo_sort_filter_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
} else if ($a || $b) {
  $elsey_woo_result_cnt_class  = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
  $elsey_woo_sort_filter_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
} else {
  $elsey_woo_result_cnt_class  = '';
  $elsey_woo_sort_filter_class = '';
}

get_header(); ?>
<?php if ( !is_product() ){ ?>
<!-- Container Start-->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class . ' ' . $elsey_content_padding); ?>" style="<?php echo esc_attr($elsey_custom_padding);?>">
  <div class="<?php echo esc_attr($elsey_layout_class); ?>"> 
<?php } ?>
    <div class="els-shop-content row">

      <?php
      if (isCustomerInPrivateEvent()) {
		  
	  } else if (is_shop() || is_product_category() || is_product_tag()) {
        if ($elsey_woo_sidebar_position === 'sidebar-left') {
          get_sidebar('shop');
        }
      } ?>

      <!-- Content Col Start -->
      <div class="<?php echo esc_attr($elsey_column_class); ?> els-content-col">
        <div class="els-content-area">
          <?php if (is_shop() || is_product_category() || is_product_tag()) { echo '<div class="woocommerce">'; } ?>
            <div class="<?php echo esc_attr($elsey_shop_col_class); ?>">
            
              <?php if (is_shop() || is_product_category() || is_product_tag()) :  
                if ( $a || $b ) { ?>    
                  <div class="els-shop-filter row">
                    <?php if ( $b ) { ?>
                      <div class="els-result-count col-xs-6 <?php //echo esc_attr($elsey_woo_result_cnt_class); ?>">
                        <?php woocommerce_result_count(); ?>
                      </div>
                    <?php } if ( $a ) { ?>
                      <div class="els-order-filter col-xs-6 <?php //echo esc_attr($elsey_woo_sort_filter_class); ?>">
                        <?php woocommerce_catalog_ordering(); ?>
                      </div>
                    <?php } ?>
                  </div>
              <?php } endif; ?>

              <?php if (have_posts()) : woocommerce_content(); endif; ?>

            </div>
          <?php if (is_shop() || is_product_category() || is_product_tag()) { echo '</div>'; } ?>
        </div>
      </div>
      <!-- Content Col End -->

      <?php
      if (is_shop() || is_product_category() || is_product_tag()) {
        if (($elsey_woo_sidebar_position !== 'sidebar-left') && ($elsey_woo_sidebar_position !== 'sidebar-hide')) {
          get_sidebar('shop');
        }
      } ?>

    </div><!-- row End-->
<?php if ( !is_product() ){ ?>
  </div>
</div>
<!-- Container End-->
<?php } ?>

<?php get_footer();
