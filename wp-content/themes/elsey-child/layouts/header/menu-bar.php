<?php
// Metabox Options
global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_id   = ( ! is_tag() && ! is_archive() && ! is_search() && ! is_404() && ! is_singular('testimonial') ) ? $elsey_id : false;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_menubar_options = $elsey_meta['menubar_options'];
  if ($elsey_menubar_options === 'custom') {
    $elsey_menubar_config = 'custom';
  } else {
    $elsey_menubar_config = 'default';
  }
} else {
  $elsey_menubar_config = 'default';
}

if ($elsey_menubar_config === 'custom') {
  $elsey_menubar_menu        = $elsey_meta['menubar_choose_menu'];
  $elsey_menubar_position    = $elsey_meta['menubar_menu_position'];
  $elsey_menubar_search      = $elsey_meta['menubar_search'];
  $elsey_menubar_wishlist    = $elsey_meta['menubar_wishlist'];
  $elsey_menubar_cart        = $elsey_meta['menubar_cart'];
  $elsey_menubar_rightmenu   = $elsey_meta['menubar_rightmenu'];
  $elsey_menubar_transparent = $elsey_meta['menubar_transparent'];
  $elsey_menubar_trans_icon  = $elsey_meta['menubar_trans_icon_color'];
  $elsey_menubar_bg          = $elsey_meta['menubar_bg'];
} else {
  $elsey_menubar_menu        = '';
  $elsey_menubar_position    = cs_get_option('menubar_menu_position');
  $elsey_menubar_search      = cs_get_option('menubar_search');
  $elsey_menubar_wishlist    = cs_get_option('menubar_wishlist');
  $elsey_menubar_cart        = cs_get_option('menubar_cart');
  $elsey_menubar_rightmenu   = cs_get_option('menubar_rightmenu');
  $elsey_menubar_transparent = false;
  $elsey_menubar_trans_icon  = 'icon-default';
  $elsey_menubar_bg          = '';
}

if ($elsey_menubar_position === 'left') {
  $elsey_menubar_position_css = 'text-align: left;';
} else if ($elsey_menubar_position === 'right') {
  $elsey_menubar_position_css = 'text-align: right;';
} else {
  $elsey_menubar_position_css = 'text-align: center;';
}

$elsey_menubar_position_class = ($elsey_menubar_position) ? 'els-menu-position-'.$elsey_menubar_position : '';
$elsey_menubar_bg = !empty($elsey_menubar_bg) ? 'background-color: '.$elsey_menubar_bg.';' : '';

$elsey_icon_search_black = ELSEY_IMAGES.'/search-icon.png';
$elsey_icon_wishlist_black = ELSEY_IMAGES.'/wishlist-icon.png';
$elsey_icon_cart_black = ELSEY_IMAGES.'/cart-icon.png';
$elsey_icon_search_white = ELSEY_IMAGES.'/search-icon-white.png';
$elsey_icon_wishlist_white = ELSEY_IMAGES.'/wishlist-icon-white.png';
$elsey_icon_cart_white = ELSEY_IMAGES.'/cart-icon-white.png';

if ($elsey_menubar_trans_icon == 'icon-white') {
  $elsey_icon_class = 'els-icon-white';
} else {
  $elsey_icon_class = 'els-icon-default';
}

// Default Logo
$elsey_brand_logo_default = cs_get_option('brand_logo_default');
$elsey_brand_logo_retina  = cs_get_option('brand_logo_retina');
$elsey_brand_logo_width   = cs_get_option('brand_logo_width');
$elsey_brand_logo_top     = cs_get_option('brand_logo_top_space');
$elsey_brand_logo_bottom  = cs_get_option('brand_logo_bottom_space');

// Transparent Header Logo
$elsey_transparent_logo   = cs_get_option('transparent_logo_default');
$elsey_transparent_retina = cs_get_option('transparent_logo_retina');

// Logo Custom Style CSS
$elsey_logo_style  = '';
$elsey_logo_style .= ($elsey_brand_logo_top) ? 'padding-top:'.elsey_check_px($elsey_brand_logo_top).';' : '';
$elsey_logo_style .= ($elsey_brand_logo_bottom) ? 'padding-bottom:'.elsey_check_px($elsey_brand_logo_bottom).';' : '';
$elsey_logo_style .= !empty($elsey_brand_logo_width) ? 'max-width:'.elsey_check_px($elsey_brand_logo_width).';' : ''; ?>
<!--<div id="infotick" class="header__promo-banner">
<ul>
<li><a href="#">CAROME.モデルオーディション開始</a></li>
</ul>
</div>-->
<!--<div id="infotick" class="header__promo-banner">
<ul>
<li><a href="#modal"><span class="date">2018.08.10</span>夏季休暇に伴う商品発送に関しまして</a></li>
</ul>
</div>-->
<!-- Menubar Starts -->
<div class="els-menubar <?php echo esc_attr($elsey_menubar_position_class); ?>" style="<?php echo esc_attr($elsey_menubar_bg); ?>">
  <div class="o-row">
	  <div class="header__main">
		  
    <div class="header__primary">
		<div class="header__sett">
			<div class="header__mobile-trigger">
				<span class="header__mobile-trigger__bar"></span>
			</div>
			<a class="header-search" data-toggle="modal" data-target="#els-search-modal">
				<label for="search" class="Header-button--circled"><span class="inputGroup-placeholder">Search</span></label>
				<input type="text" id="search" data-id="inputFocus">
				<div class="inputGroup-submit"><span class="submit-icon"><img src="<?php echo esc_url($elsey_icon_search_black); ?>" alt="search_icon" width="18" height="18"/></span></div>
			</a>
		</div>

      <!--logo-->
        <a href="<?php echo esc_url(home_url( '/' )); ?>" class="header__logo__anchor">
			<div class="html-slot-container">
				<?php if ( $elsey_menubar_transparent ) {

            if ( isset( $elsey_transparent_logo ) ) {

              if ( isset( $elsey_transparent_retina ) ) {
                echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_transparent_retina ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="retina-logo transparent-logo">';
                echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_retina ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="retina-logo transparent-scroll-logo">';
              }

              echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_transparent_logo ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo transparent-logo">';
              echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo transparent-scroll-logo">';

            } elseif ( isset( $elsey_brand_logo_default ) ) {

              if ($elsey_brand_logo_retina) {
                echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_retina ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="retina-logo">';
              }

              echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo">';

            } else { echo '<div class="text-logo">'. esc_attr(get_bloginfo( 'name' )) . '</div>'; }

          } elseif ( isset($elsey_brand_logo_default) ) {

            if ($elsey_brand_logo_retina) {
              echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_retina ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="retina-logo">';
            }

            echo '<img src="'. esc_url( wp_get_attachment_url( $elsey_brand_logo_default ) ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" class="default-logo">';

          } else { echo '<div class="text-logo">'. esc_attr( get_bloginfo( 'name' ) ) . '</div>'; } ?>
			</div>
        </a>
		<!--/logo-->
		
		<div class="els-icon header__user">             
      	<ul>
			<li class="els-user-icon display--small-up">
				<?php $elsey_myaccount_url = get_permalink(get_option('woocommerce_myaccount_page_id')); ?>
				<a href="<?php echo esc_url($elsey_myaccount_url); ?>"><i class="cmn-icon cmn-single-03-2"></i></a>
			</li>
          <?php if ( $elsey_menubar_wishlist && class_exists('WooCommerce') ) {
          	if ( defined( 'YITH_WCWL' ) ) {
			  			$els_wishlist_count = YITH_WCWL()->count_products();
              $els_wishlist_url   = get_permalink(get_option('yith_wcwl_wishlist_page_id'));
              $els_wishlist_class = ($els_wishlist_count) ? 'els-wishlist-filled' : 'els-wishlist-empty'; ?>
							<li class="els-wishlist-icon display--small-up <?php echo esc_attr($els_wishlist_class); ?>">
							  <a href="<?php echo esc_url( home_url( '/my-account/favorite-list/' ) ); ?>">
							  	<i class="cmn-icon cmn-heart-2-3"></i>					    
							  </a>
							</li>
          <?php } } if ( $elsey_menubar_cart && class_exists('WooCommerce') ) {
            global $woocommerce; ?>
            <li id="els-shopping-cart-content">
              <a href="javascript:void(0);" id="els-cart-trigger">
              	<?php if ( $woocommerce->cart->get_cart_contents_count() == '0' ) { ?>
                  <span class="els-cart-count els-cart-zero"><?php echo esc_attr($woocommerce->cart->get_cart_contents_count()); ?></span>
                <?php } else { ?>
                  <span class="els-cart-count"><?php echo esc_attr($woocommerce->cart->get_cart_contents_count()); ?></span>
                <?php } ?>            

                <i class="cmn-icon cmn-bag-09-2"></i>	

              </a>
              <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
              </div>
            </li>
          <?php } if ( $elsey_menubar_rightmenu ) {
            if (is_active_sidebar('sidebar-right')) { ?>
            <!--<li id="els-right-menu" class="<?php echo esc_attr($elsey_icon_class); ?>">
              <span></span>
              <span></span>
              <span></span>
            </li>-->
          <?php } } ?>
      	</ul>

        
      </div>
		</div><!--.header__primary-->

      <div class="els-main-menu header__secondary" style="<?php echo esc_attr($elsey_menubar_position_css); ?>">
	<div class="header__secondary__content">
        <?php
        wp_nav_menu(
          array(
            'menu'           => 'primary',
            'theme_location' => 'primary',
            'menu_id'        => 'els-menu',
            'menu'           => $elsey_menubar_menu,
            'fallback_cb'    => 'Walker_Nav_Menu_Custom::fallback',
            'walker'         => new Walker_Nav_Menu_Custom(),
          )
        ); ?>
		  <div class="header__user display--small-only">
			  <a href="<?php echo esc_url($elsey_myaccount_url); ?>" class="header__user__item header__user__link"><?php if ( is_user_logged_in() ) { ?><?php _e( 'My Page', 'elsey' ); ?><?php } else { ?><?php _e( 'Sign in / Register', 'elsey' ); ?><?php } ?></a>
			  <?php if ( $elsey_menubar_wishlist && class_exists('WooCommerce') ) {
	if ( defined( 'YITH_WCWL' ) ) {
		$els_wishlist_count = YITH_WCWL()->count_products();
		$els_wishlist_class = ($els_wishlist_count) ? 'els-wishlist-filled' : 'els-wishlist-empty'; ?>
			  <a href="<?php echo esc_url( home_url( '/my-account/favorite-list/' ) ); ?>" class="header__user__item header__user__link <?php echo esc_attr($els_wishlist_class); ?>"><?php _e( 'Wishlist', 'elsey' ); ?></a>
			  <?php } } ?>
		  </div>
		</div><!--/header__secondary__content-->
		</div><!--/header__secondary-->
		 
		<div class="focus-overlay focus-overlay--header"></div>
	  </div>
  </div>
	
</div>
<!-- Menubar End -->