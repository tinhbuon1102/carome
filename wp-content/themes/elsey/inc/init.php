<?php
/*
 * All Elsey Theme Related Functions Files are Linked here
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/* Theme All Basic Setup */
get_template_part( 'inc/theme', 'support' );
get_template_part( 'inc/backend', 'functions' );
get_template_part( 'inc/frontend', 'functions' );
get_template_part( 'inc/enqueue', 'files' );
get_template_part( 'inc/theme-options/theme-extend/custom', 'style' );
get_template_part( 'inc/theme-options/theme-extend/config' );

/* Menu Walker */;
get_template_part( 'inc/core/vt-mmenu/mega', 'menu-api' );

/* Install Plugins */
get_template_part( 'inc/plugins/notify/activation' );

/* Breadcrumbs */
get_template_part( 'inc/plugins/breadcrumb', 'trail' );

/* Aqua Resizer */
get_template_part( 'inc/plugins/aq_resizer' );

/* Sidebars */
get_template_part( 'inc/core/sidebars' );

/* WooCommerce Integration */
if (class_exists( 'WooCommerce' )){
  get_template_part( 'inc/plugins/woocommerce/woo', 'config' );
}