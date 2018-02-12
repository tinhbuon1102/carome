<?php
/*
 * The template for displaying 404 pages (not found).
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

// Theme Options
$elsey_error_page_heading = cs_get_option('error_page_heading');
$elsey_error_page_content = cs_get_option('error_page_content');
$elsey_error_page_bground = cs_get_option('error_page_bground');
$elsey_error_page_btntext = cs_get_option('error_page_btntext');
$elsey_error_page_heading = ( $elsey_error_page_heading ) ? $elsey_error_page_heading : esc_html__( 'Oops! Page Not Found!', 'elsey' );
$elsey_error_page_bground = ( $elsey_error_page_bground ) ? wp_get_attachment_url($elsey_error_page_bground) : ELSEY_IMAGES . '/404.png';
$elsey_error_page_btntext = ( $elsey_error_page_btntext ) ? $elsey_error_page_btntext : esc_html__( 'GO BACK TO HOME', 'elsey' );
$elsey_error_page_content = ( $elsey_error_page_content ) ? $elsey_error_page_content : esc_html__( 'It looks like nothing was found at this location. Click the link below to return home.', 'elsey' );

$elsey_parent_class = 'els-full-width';
$elsey_layout_class = 'container';

get_header(); ?>

<!-- Content 404 Start -->
<div class="els-container-wrap <?php echo esc_attr($elsey_parent_class); ?>">
  <div class="<?php echo esc_attr($elsey_layout_class); ?>">
    <div class="row">
			<div class="els-error-content">
				<img src="<?php echo esc_url($elsey_error_page_bground); ?>" alt="<?php esc_html_e('404 Error', 'elsey'); ?>">
				<h1><?php echo esc_attr($elsey_error_page_heading); ?></h1>
				<p><?php echo esc_attr($elsey_error_page_content); ?></p>
				<a href="<?php echo esc_url(home_url( '/' )); ?>" class="els-btn"><?php echo esc_attr($elsey_error_page_btntext); ?></a>
			</div>
    </div>
  </div>
</div>
<!-- Content 404 End -->

<?php get_footer();
