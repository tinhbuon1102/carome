<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the slider part of the quick view modal.
 *
 * This template can be overridden by copying it to yourtheme/woo-quick-view/parts/product-slider.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @link       http://xplodedthemes.com
 * @since      1.0.8
 *
 * @package    Woo_Quick_View
 * @subpackage Woo_Quick_View/public/templates/parts
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

global $product, $attachment_ids;

$lightbox_enabled = wooqv_option('modal_slider_lightbox_enabled', '0');

$attachment_ids = array();

if(!empty($variation_id)) {
		
	$variation = new WC_Product_Variation( $variation_id );

	$image_id = $variation->get_image_id();
	
	if(!empty($image_id)) {
		$attachment_ids[] = $image_id;
	}
		
	if(class_exists('WC_Additional_Variation_Images')) {
		$gallery_attachment_ids = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
		$gallery_attachment_ids = explode( ',', $gallery_attachment_ids );
		$attachment_ids = array_merge($attachment_ids, $gallery_attachment_ids);
	}	
	
}else{

	$image_id = $product->get_image_id();
	if(!empty($image_id)) {
		$attachment_ids[] = $image_id;
	}
	
	$gallery_attachment_ids = $product->get_gallery_image_ids();
	$attachment_ids = array_merge($attachment_ids, $gallery_attachment_ids);
}

$attachment_ids = array_filter($attachment_ids);
?>

<div class="wooqv-slider-wrapper" data-attachments="<?php echo count($attachment_ids);?>">

	<ul class="wooqv-slider">
		<?php
		if ( !empty($attachment_ids) ) {
	
			$light_box_rel = '';
				
			if($lightbox_enabled) {
				$light_box_rel = ' data-rel="prettyPhoto[product-gallery]"';
			}
			
			foreach ( $attachment_ids as $attachment_id ) {
				
				$props            = wc_get_product_attachment_props( $attachment_id, $product );
				$image            = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), 0, $props );
				$thumb_image 	  = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0);
			
				echo apply_filters(
					'woocommerce_single_product_image_html',
					sprintf(
						'<li data-thumb="%s" itemprop="image" title="%s"><a href="%s"%s>%s</a></li>',
						$thumb_image[0],
						esc_attr( $props['caption'] ),
						esc_url( $props['url'] ),
						$light_box_rel,
						$image
					),
					get_the_ID()
				);
	
			}
				
		}else{
			
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><img src="%s" alt="%s" /></li>', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), get_the_ID() );
		}
		?>
	</ul>
	
</div> 
