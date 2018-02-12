<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

if ( ! defined( 'YITH_WCWL' ) ) {
    exit;
} // Exit if accessed directly

global $product, $main_product_id;
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" rel="nofollow" data-product-id="<?php echo $product_id ?>" data-product-type="<?php echo $product_type?>" class="button <?php echo $link_classes ?> <?php if ($main_product_id != $product_id) { ?>round_button fa fa-heart-o<?php } ?>" >
    <?php if (is_product()  && $main_product_id == $product_id) { ?>
	<?php echo $icon ?>
    <?php echo $label ?>
	<?php } ?>
</a>
<?php if (is_product()  && $main_product_id == $product_id) { ?>	
<img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" /><?php } ?>