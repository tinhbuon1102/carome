<?php
// Metabox Options
global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
$elsey_id   = ( ! is_tag() && ! is_archive() && ! is_search() && ! is_404() && ! is_singular('testimonial') ) ? $elsey_id : false;
$elsey_meta = get_post_meta( $elsey_id, 'page_type_metabox', true );

if ($elsey_meta) {
  $elsey_topbar_options = $elsey_meta['topbar_options'];
  if ($elsey_topbar_options === 'custom') {
    $elsey_topbar_config = 'custom';
  } else {
    $elsey_topbar_config = 'default';
  }
} else {
  $elsey_topbar_config = 'default';
}

if ($elsey_topbar_config === 'custom') {
  // Top Bar Content
  $elsey_topbar_left_content = $elsey_meta['topbar_left_content'];
  $elsey_topbar_my_account   = $elsey_meta['topbar_my_account'];
  $elsey_topbar_currency     = $elsey_meta['topbar_currency'];
  $elsey_topbar_background   = $elsey_meta['topbar_background'];
  $elsey_topbar_right_link   = $elsey_meta['topbar_right_link'];
  $elsey_topbar_link_title   = $elsey_meta['topbar_link_title'];
  $elsey_topbar_link_url     = $elsey_meta['topbar_link_url'];
  // Top Bar Class
  $elsey_topbar_background   = ($elsey_topbar_background) ? 'background-color: '. $elsey_topbar_background .';' : '';
} else {
  $elsey_need_topbar         = cs_get_option('need_topbar');
  // Top Bar Content
  $elsey_topbar_right_link   = cs_get_option('topbar_right_link');
  $elsey_topbar_link_title   = cs_get_option('topbar_link_title');
  $elsey_topbar_link_url     = cs_get_option('topbar_link_url');
  $elsey_topbar_left_content = cs_get_option('topbar_left_content');
  $elsey_topbar_my_account   = cs_get_option('topbar_my_account');
  $elsey_topbar_currency     = cs_get_option('topbar_currency');
  $elsey_topbar_background   = '';
} ?>

<?php if ( $elsey_topbar_left_content || $elsey_topbar_my_account || $elsey_topbar_currency ) { ?>

	<!-- Top Bar Start -->
	<div class="els-topbar" style="<?php echo esc_attr($elsey_topbar_background); ?>">
	  <div class="container">
	    <div class="row">

	      <div class="els-topbar-left col-lg-6 col-md-6 col-sm-5 col-xs-12">
	        <?php if ($elsey_topbar_left_content) { ?>
	          <div class="els-topbar-left-text">
	            <?php echo do_shortcode($elsey_topbar_left_content); ?>
	          </div>
	        <?php } ?>
	      </div>

	      <div class="els-topbar-right col-lg-6 col-md-6 col-sm-7 col-xs-12">
	        <?php if ($elsey_topbar_my_account || $elsey_topbar_currency) {  ?>
	          <ul>
	            <?php if ($elsey_topbar_my_account && class_exists('WooCommerce')) {
	              $elsey_myaccount_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
                if(!is_user_logged_in()) { ?>
	                <li>
	                  <a href="<?php echo esc_url($elsey_myaccount_url); ?>">
	                    <img src="<?php echo esc_url(ELSEY_IMAGES); ?>/login-icon.png" alt="login-icon" width="16" height="13"/>
	                    <?php esc_html_e('Login/Register', 'elsey'); ?>
	                  </a>
	                </li>
	            <?php } else { ?>
	              	<li><a href="<?php echo wp_logout_url( get_permalink() ); ?>" alt="log-out"><?php esc_html_e('Logout', 'elsey'); ?></a></li>
							<?php }
	            } if ($elsey_topbar_right_link && !empty($elsey_topbar_link_title) && !empty($elsey_topbar_link_url)) { ?>
	              <li class="els-topbar-right-link">
	                <a href="<?php echo esc_url($elsey_topbar_link_url); ?>"><?php echo esc_attr($elsey_topbar_link_title); ?></a>
	              </li>
	            <?php } if ($elsey_topbar_currency && class_exists('WooCommerce')) { ?>
	              <li class="els-currency-switcher">
	                <?php echo do_shortcode('[woocs]'); ?>
	              </li>
	            <?php } ?>
	          </ul>
	        <?php } ?>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- Top Bar End -->

<?php } ?>
