<?php
/*
Template Name: Eyeliner
 */
global $wp;
$request = explode( '/', $wp->request );
// Metabox
global $post;
$elsey_id   = ( isset( $post ) ) ? $post->ID : false;
$elsey_id   = ( is_home() ) ? get_option( 'page_for_posts' ) : $elsey_id;
$elsey_id   = ( is_woocommerce_shop() ) ? wc_get_page_id( 'shop' ) : $elsey_id;
get_header(); 
get_template_part( 'template-parts/eyeliner/button', 'buy' );
?>
<!-- Container Start -->
<div class="eyeliner-container">

	<?php
    get_template_part( 'template-parts/eyeliner/section', 'mainv' );
	get_template_part( 'template-parts/eyeliner/section', 'intro' );
	get_template_part( 'template-parts/eyeliner/section', 'products' );
	get_template_part( 'template-parts/eyeliner/section', 'feature' );
	get_template_part( 'template-parts/eyeliner/section', 'msg' );
	get_template_part( 'template-parts/eyeliner/section', 'buy' );
	?>

</div>
<!-- Container End -->

<?php get_footer();
