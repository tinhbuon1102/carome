<?php
/**
 * VictorTheme Custom Changes - Changed the structure to make the product large image slider and thumbnail slider and connect them
 */

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

// Custom Changes Start
$attachment_ids   = $product->get_gallery_image_ids();
$woo_single_modal = cs_get_option('woo_single_modal');
$image_gallery_class = ( $woo_single_modal ) ? 'modal-enabled els-gallery' : '';

$product_options = get_post_meta( $product->get_id(), 'product_options', true );
$product_sticky  = isset($product_options['product_sticky_info']) ? $product_options['product_sticky_info'] : false;
// Custom Changes End 
?>

<!-- Custom Changes Start -->
<?php if ( $product_sticky ) { ?>

	<div class="row"> 
		<div class="els-product-featured-image-col col-lg-12 col-md-12 col-sm-12 col-xs-12" id="els-product-featured-image-col">
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
				<figure class="woocommerce-product-gallery__wrapper">	
					<div class="<?php echo esc_attr($image_gallery_class); ?>" >

						<!-- Custom Changes Start -->
						<?php 
						if ( !$product->is_in_stock() ) {
							echo '<span class="els-product-sold">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>';
						} else if ( $product->is_on_sale() ) {
							echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey' ) . '</span>'; 
						} ?>

						<!-- Custom Changes End -->
						<?php
						$attributes = array(
							'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
							'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
							'data-src'                => $full_size_image[0],
							'data-large_image'        => $full_size_image[0],
							'data-large_image_width'  => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
						);

						if ( has_post_thumbnail() ) {
							$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
							$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
							$html .= '</a></div>';
						} else {
							$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
							$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'elsey' ) );
							$html .= '</div>';
						}

						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

						// Custom Changes Start
						if ( $attachment_ids && has_post_thumbnail() ) {
							foreach ( $attachment_ids as $attachment_id ) {
								$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
								$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
								
								$attributes      = array(
									'title'                   => get_post_field( 'post_title', $attachment_id ),
									'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

								$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
								$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
						 		$html .= '</a></div>';

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
							}
						} // Custom Changes End ?>

					</div>
				</figure>
			</div>
		</div>
	</div>

<?php } else { ?>

	<!-- Custom Changes Start -->
	<div class="row">

		<div class="els-product-thumbnails-col col-lg-3 col-md-3 col-sm-3 col-xs-2">
			<div id="els-product-thumbnails-slider">
		        <?php do_action( 'woocommerce_product_thumbnails' ); ?>
		    </div>
	    </div>	

		<div class="els-product-featured-image-col col-lg-9 col-md-9 col-sm-9 col-xs-10" id="els-product-featured-image-col">	
			<!-- Custom Changes End -->
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
				
				<figure class="woocommerce-product-gallery__wrapper">		
					<!-- Custom Changes Start -->
					<?php 
					if ( !$product->is_in_stock() ) {
						echo '<span class="els-product-sold">' . esc_html__( 'Out of Stock', 'elsey' ) . '</span>';
					} else if ( $product->is_on_sale() ) {
						echo '<span class="els-product-onsale">' . esc_html__( 'Sale!', 'elsey' ) . '</span>'; 
					} ?>

					<div id="els-product-featured-image-slider" class="<?php echo esc_attr($image_gallery_class); ?> slick-slider slick-arrows-small" >
						<!-- Custom Changes End -->
						<?php
						$attributes = array(
							'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
							'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
							'data-src'                => $full_size_image[0],
							'data-large_image'        => $full_size_image[0],
							'data-large_image_width'  => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
						);
						
						if ( has_post_thumbnail() ) {
							$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
							$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
							$html .= '</a></div>';
						} else {
							$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
							$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'elsey' ) );
							$html .= '</div>';
						}

						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

						// Custom Changes Start
						if ( $attachment_ids && has_post_thumbnail() ) {
							foreach ( $attachment_ids as $attachment_id ) {
								$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
								$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
								$attributes      = array(
									'title'                   => get_post_field( 'post_title', $attachment_id ),
									'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

								$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
								$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
						 		$html .= '</a></div>';

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
							}
						}
						// Custom Changes End
						?>
					</div>
				</figure>
			</div>
		</div>

	</div>

<?php } ?>

