<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

  <meta charset="<?php bloginfo('charset'); ?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

  <?php
  $all_element_color  = cs_get_customize_option( 'all_element_colors' ); ?>

  <meta name="msapplication-TileColor" content="<?php echo esc_attr($all_element_color); ?>"/>
  <meta name="theme-color" content="<?php echo esc_attr($all_element_color); ?>"/>

  <link rel="profile" href="http://gmpg.org/xfn/11"/>
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

  <?php
  // Maintenance Mode
  $maintenance_mode_bg  = cs_get_option( 'maintenance_mode_bg' );
  $page = cs_get_option('maintenance_mode_page');

  if ($maintenance_mode_bg) {
    $maintenance_css = ( ! empty( $maintenance_mode_bg['image'] ) ) ? 'background-image: url('. $maintenance_mode_bg['image'] .');' : '';
    $maintenance_css .= ( ! empty( $maintenance_mode_bg['repeat'] ) ) ? 'background-repeat: '. $maintenance_mode_bg['repeat'] .';' : '';
    $maintenance_css .= ( ! empty( $maintenance_mode_bg['position'] ) ) ? 'background-position: '. $maintenance_mode_bg['position'] .';' : '';
    $maintenance_css .= ( ! empty( $maintenance_mode_bg['attachment'] ) ) ? 'background-attachment: '. $maintenance_mode_bg['attachment'] .';' : '';
    $maintenance_css .= ( ! empty( $maintenance_mode_bg['size'] ) ) ? 'background-size: '. $maintenance_mode_bg['size'] .';' : '';
    $maintenance_css .= ( ! empty( $maintenance_mode_bg['color'] ) ) ? 'background-color: '. $maintenance_mode_bg['color'] .';' : '';
  }

  $maintenance_mode_css = ! empty($maintenance_css) ? $maintenance_css : '';

  wp_head(); ?>

</head>

<body <?php body_class(); ?> style="<?php echo esc_attr($maintenance_mode_css); ?>">

  <div class="els-container-wrap">
    <div class="container">
      <div class="row">
        <?php
        $page = get_post( cs_get_option('maintenance_mode_page') );
        WPBMap::addAllMappedShortcodes();
        echo ( is_object( $page ) ) ? do_shortcode( $page->post_content ) : '';
        ?>
      </div>
    </div>
  </div>

  <?php wp_footer(); ?>

</body>

</html>