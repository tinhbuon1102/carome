<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	), $customer_id );
} else {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
	), $customer_id );
}
$oldcol = 1;
$col    = 1;

$billing_postcode = get_user_meta( $customer_id, 'billing_postcode', true );
$billing_state = get_user_meta( $customer_id, 'billing_state', true );
$billing_city = get_user_meta( $customer_id, 'billing_city', true );
$billing_address1 = get_user_meta( $customer_id, 'billing_address_1', true );
$billing_address2 = get_user_meta( $customer_id, 'billing_address_2', true );
$billing_lastname = get_user_meta( $customer_id, 'billing_last_name', true );
$billing_firstname = get_user_meta( $customer_id, 'billing_first_name', true );
?>

<h3 class="address-list-title heading heading--xsmall">
	<?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); ?>
</h3>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="address-list default flex-justify-between">
<?php endif; ?>

<?php foreach ( $get_addresses as $name => $title ) : ?>

	<!--<div class="u-column<?php //echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> col-<?php echo ( ( $oldcol = $oldcol * -1 ) < 0 ) ? 1 : 2; ?> woocommerce-Address">-->
		<div class="input-option--box">
		<div class="input-option--box__contents">
		<div class="input-option--box__details">
			<h3 class="address-type-title heading heading--xsmall"><?php echo $title; ?></h3>
		<!--<?php //if (! $customer_address ) { ?>-->
		<!--<p class="p4"><?php //_e( '住所はまだ登録されていません。', 'elsey' ); ?></p>-->
		<!--<?php //} else { ?>-->
		<address><?php
			$address = wc_get_account_formatted_address( $name );
			echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
		?></address>
		<!--<?php //} ?>-->
		</div>
		<div class="input-option--box__actions">
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="cta cta--secondary address-edit"><!--<?php //if (! $customer_address ) { ?>--><?php //_e( '住所を登録', 'elsey' ); ?><!--<?php //} else { ?>--><?php _e( 'Edit', 'woocommerce' ); ?><!--<?php //} ?>--></a>
		</div>
		</div>
		</div>
	<!--</div>-->

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
<?php endif;
