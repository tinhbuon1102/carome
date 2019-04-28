<?php
/**
 * Variation Gallery
 *
 * @author  Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="form-row form-row-full yith-wccl-variation-gallery-wrapper">
    <h4><?php _e( 'Variation Image Gallery', 'yith-woocommerce-color-label-variations' ) ?></h4>
    <div class="yith-wccl-variation-gallery-image-container">
        <ul class="yith-wccl-variation-gallery-images">
            <?php foreach( $gallery as $image_id ) : ?>
                <li class="image">
                    <input type="hidden" name="yith_wccl_variation_gallery[<?php echo $loop ?>][]" value="<?php echo $image_id ?>">
                    <a href="#" class="remove" title="<?php _ex( 'Remove image', 'label for remove single image from variation gallery', 'yith-woocommerce-color-label-variations' ) ?>"></a>
                    <img src="<?php echo esc_url( wp_get_attachment_thumb_url( $image_id ) ); ?>">
                </li>
            <?php endforeach; ?>
            <li class="image add">
                <a href="#" data-index="<?php echo $loop ?>" class="add-variation-gallery-image"></a>
            </li>
        </ul>
    </div>
</div>