<?php
/*
 * The sidebar only for WooCommerce pages.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

// Theme Option WC Sidebar Position
$woo_sidebar = cs_get_option('woo_sidebar_position');
$woo_widget  = cs_get_option('woo_widget');

if ($woo_sidebar === 'sidebar-left'){
  $col_layout_class = 'els-shop-left-sidebar els-left-col';
} elseif ($woo_sidebar == 'sidebar-hide') {
  $col_layout_class = '';
} else {
  $col_layout_class = 'els-shop-right-sidebar els-right-col';
}
?>

<!-- Sidebar Column Start -->
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 els-sidebar els-shop-sidebar <?php echo esc_attr($col_layout_class); ?>">
  <?php if ($woo_widget) {
  	if (is_active_sidebar($woo_widget)) {
    	dynamic_sidebar($woo_widget);
    }
  } else {
  	if (is_active_sidebar('sidebar-shop')) {
	    dynamic_sidebar('sidebar-shop');
	  }
  } ?>
</div>
<!-- Sidebar Column End -->