<?php
/**
 * Product quantity inputs with plus/minus buttons
 *
 * Save this template to your theme as woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( $max_value && $min_value === $max_value ) {
	?>
	<input type="hidden" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	<?php
} else {
	?>
<?php
	
	$terms = get_the_terms( $post->ID, 'product_cat' );
	foreach ($terms as $term) {
		if ($term->slug == 'cosmetic')
		{
			$ivClass = 'hidden';
		} else {
			$ivClass = 'enable';
		}
		break;
	}
?>
<?php if( current_user_can('administrator') ) {  ?>
<?php } ?>
<?php if (is_product()) { ?><div class="pdp__attribute inventory <?php echo $ivClass ?>"><?php } ?>
    <?php if (is_product()) { ?><div class="pdp__attribute__label"><?php esc_html_e( 'Quantity', 'elsey' ); ?></div><?php } ?>
	<div class="qty-input quantity">
		<input class="button qty-input__ctrl minus" type="button" value="-">
		<input type="text" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" class="qty-input__ctrl input--white value" size="2" pattern="<?php echo esc_attr( $pattern ); ?>" inputmode="<?php echo esc_attr( $inputmode ); ?>" />
		<input class="button qty-input__ctrl plus" type="button" value="+">
	</div>
<?php if (is_product()) { ?></div><?php } ?>
	<?php
}