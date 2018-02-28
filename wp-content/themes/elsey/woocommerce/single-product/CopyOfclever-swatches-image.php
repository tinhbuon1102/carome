<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Use for display image swatch.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

$general_settings = get_option( 'zoo-cw-settings', true );
$is_gallery_enabled = isset( $general_settings[ 'product_gallery' ] ) ? intval( $general_settings[ 'product_gallery' ] ) : 1;
if ( isset( $variation_id ) ) {
	if ( $is_gallery_enabled ) {
		$gallery_images_id = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );
		$attachment_ids = array_filter( explode( ',', $gallery_images_id ) );
	} else {
		$attachment_ids = [];
	}
} else {
	global $post, $product; // just in case if your template file need this

	$default_active = [];
	$default_attributes = $product->get_default_attributes();
	$variation_id = 0;
	if ( count( $default_attributes ) && $is_gallery_enabled ) {
		foreach ( $default_attributes as $key => $value ) {
			$default_active[ 'attribute_' . $key ] = $value;
		}
		$data_store = WC_Data_Store::load( 'product' );
		$variation_id = $data_store->find_matching_product_variation( $product, $default_active );
	}

	if ( $variation_id == 0 ) {
		$attachment_ids = $product->get_gallery_image_ids();
	} else {
		$gallery_images_id = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );
		$attachment_ids = array_filter( explode( ',', $gallery_images_id ) );
	}
}

if ( isset( $_POST[ 'product_id' ] ) ) {
	$product_id = intval( $_POST[ 'product_id' ] );
} else $product_id = get_the_ID();

$product_swatch_data_array = get_post_meta( $product_id, 'zoo_cw_product_swatch_data', true );
if ( $product_swatch_data_array == '' ) {
	$is_gallery_enabled = 0;
}

if ( $product->is_type( 'variable' ) && $is_gallery_enabled != 0 ):
	$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $variation_id );
$full_size_image = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );
?>
<div class="els-product-image-col col-md-6 col-xs-12 images">
	<!-- Custom Changes Ends -->
	<div class="row">

		<!-- Custom Changes Ends -->
		<div class="els-product-thumbnails-col col-lg-3 col-md-3 col-sm-3 col-xs-2">
			<div id="els-product-thumbnails-slider">
				
						<?php
							$attributes = array(
								'title' => get_post_field( 'post_title', $post_thumbnail_id ),
								'data-caption' => get_post_field( 'post_excerpt', $post_thumbnail_id ),
								'data-src' => $full_size_image[ 0 ],
								'data-large_image' => $full_size_image[ 0 ],
								'data-large_image_width' => $full_size_image[ 1 ],
								'data-large_image_height' => $full_size_image[ 2 ],
							);

							if ( has_post_thumbnail( $variation_id ) ) {
								$html = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image">';
								//$html .= '<a href="' . esc_url( $full_size_image[ 0 ] ) . '">';
								$html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
								//$html .= '</a>';
								$html .= '</div>';
							} else {
								$html = '<div class="woocommerce-product-gallery__image--placeholder">';
								$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
								$html .= '</div>';
							}
							echo $html;
							foreach ( $attachment_ids as $key=> $attachment_id ) {
								
							$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
							$thumbnail = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
							$image_title = get_post_field( 'post_excerpt', $attachment_id );

							$attributes = array(
								'title' => $image_title,
								'data-src' => $full_size_image[ 0 ],
								'data-large_image' => $full_size_image[ 0 ],
								'data-large_image_width' => $full_size_image[ 1 ],
								'data-large_image_height' => $full_size_image[ 2 ],
							);

							$html = '<div data-thumb="' . esc_url( $full_size_image[ 0 ] ) . '" class="woocommerce-product-gallery__image ">';
								$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
							$html .='</div>';

							echo $html;
							 $i++;}
							?>
				</div>
		</div>


		<div class="els-product-featured-image-col col-lg-9 col-md-9 col-sm-9 col-xs-10" id="els-product-featured-image-col">
			<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 1; transition: opacity .25s ease-in-out;">
				<figure class="woocommerce-product-gallery__wrapper">
					<?php 
					if ( !$product->is_in_stock() ) {
						echo '<span class="els-product-sold">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>';
					} else if ( $product->is_on_sale() ) {
						echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey' ) . '</span>'; 
					} ?>
					<div id="els-product-featured-image-slider" class="<?php echo esc_attr($image_gallery_class); ?> slick-slider slick-arrows-small">
						<?php
						$attributes = array(
							'title' => get_post_field( 'post_title', $post_thumbnail_id ),
							'data-caption' => get_post_field( 'post_excerpt', $post_thumbnail_id ),
							'data-src' => $full_size_image[ 0 ],
							'data-large_image' => $full_size_image[ 0 ],
							'data-large_image_width' => $full_size_image[ 1 ],
							'data-large_image_height' => $full_size_image[ 2 ],
						);

						if ( has_post_thumbnail( $variation_id ) ) {
							$html = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image">';
							$html .= '<a href="' . esc_url( $full_size_image[ 0 ] ) . '">';
							$html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
							$html .= '</a>';
							$html .= '</div>';
						} else {
							$html = '<div class="woocommerce-product-gallery__image--placeholder">';
							$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
							$html .= '</div>';
						}
						echo $html;

						//thumb image
						foreach ( $attachment_ids as $attachment_id ) {
							$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
							$thumbnail = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
							$image_title = get_post_field( 'post_excerpt', $attachment_id );

							$attributes = array(
								'title' => $image_title,
								'data-src' => $full_size_image[ 0 ],
								'data-large_image' => $full_size_image[ 0 ],
								'data-large_image_width' => $full_size_image[ 1 ],
								'data-large_image_height' => $full_size_image[ 2 ],
							);

							$html = '<div data-thumb="' . esc_url( $thumbnail[ 0 ] ) . '" class="woocommerce-product-gallery__image custom_slider">';
							$html .= '<a href="' . esc_url( $full_size_image[ 0 ] ) . '">';
							$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
							$html .= '</a>';
							$html .= '</div>';

							echo $html;
						}
						?>
					</div>
				</figure>
			</div>
		</div>
	</div>
</div>
<?php
else :
	wc_get_template_part( 'single-product/product', 'image' );
endif;
?>