<?php
/**
 * VictorTheme Custom Changes - Added div and class and changed h3 to h5
 */

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
do_action( 'woocommerce_before_notices' );
wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'elsey' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout row flex-justify-between" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="order--checkout__col--form col-md-7 col-xs-12"> <!-- Added col class div -->

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set" id="customer_details">
				<legend class="order--checkout__form__section">
					<h2 class="heading heading--xlarge serif">
						<span class="order--checkout__title-break"><?php esc_html_e( 'お客様・お届け先情報', 'elsey' ); ?></h5></span>
					</h2>
					<p class="form__description p4"><?php esc_html_e( '以下の必要項目をご入力ください', 'elsey' ); ?></p>
				</legend>
				<fieldset class="order--checkout__form__section">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</fieldset>
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>

	</div>

	<div id="secondary" class="order--checkout__col--summary summary col-lg-4 col-md-5 col-xs-12"><!-- Added col class div -->
		<h2 class="order--checkout__summary__heading heading p2 serif flex-justify-between icon--plus" id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h2>
		
			<div class="order--checkout__actions--top flex-justify-between">
				<span class="heading heading--small"><?php esc_html_e( '商品数', 'elsey' ); ?> (<?php echo sprintf ( _n( '%d点', '%d点', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>)<span class="order--checkout__actions__total display--small-only">– 合計金額 <?php wc_cart_totals_order_total_html(); ?></span></span>
				<a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" class="cta cta--underlined section-header-note"><?php esc_html_e( 'Edit bag', 'elsey' ); ?></a>
			</div>
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
