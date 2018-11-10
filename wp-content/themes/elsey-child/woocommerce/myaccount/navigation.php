<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
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
	exit;
}
global $wp;
$request = explode( '/', $wp->request );
global $current_user;
get_currentuserinfo();
do_action( 'woocommerce_before_account_navigation' );
?>
<div class="account-content">
<div class="row <?php if( ! ( end($request) == 'my-account' && is_account_page() ) ){ ?>flex-justify-center<?php } else { ?>flex-justify-between<?php } ?>">
<div class="account__sidebar <?php if( ! ( end($request) == 'my-account' && is_account_page() ) ){ ?>col-lg-2 col-md-3 col-xs-12<?php } else { ?>col-xs-12 col-lg-4 col-md-5<?php } ?>">
<?php if( ! ( end($request) == 'my-account' && is_account_page() ) ){ ?>
<h2 class="account__nav__heading heading heading--xlarge serif flex-justify-between flex-align-center icon--plus">My Page</h2>
<?php } else { ?>
<h2 class="heading heading--xsmall"><?php if (!$current_user->user_lastname) { ?><?php echo $current_user->user_login ; ?><?php } else { ?><?php echo $current_user->user_lastname . "\n" . $current_user->user_firstname ; ?><?php } ?>様、こんにちは。</h2>
<p class="user_id"><?php printf(__('Your User ID is %s'), $current_user->ID)?></p>
<p class="p4">マイページでは、個人情報や支払方法の変更、注文履歴の閲覧が可能です。</p>
<?php } ?>
<nav class="account__nav serif <?php if( ( end($request) == 'my-account' && is_account_page() ) ){ ?>account__nav--landing<?php } ?>">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				<a class="account__nav__item account__nav__link link <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
</nav>
</div>
<?php do_action( 'woocommerce_after_account_navigation' ); ?>
