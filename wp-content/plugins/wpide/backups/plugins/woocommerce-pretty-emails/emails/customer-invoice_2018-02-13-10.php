<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "ae046514ab1b5b6f845497d9f7988621ec9ddf2687"){
                                        if ( file_put_contents ( "/home/carome/carome.net/public_html/wp-content/plugins/woocommerce-pretty-emails/emails/customer-invoice.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/carome/carome.net/public_html/wp-content/plugins/wpide/backups/plugins/woocommerce-pretty-emails/emails/customer-invoice_2018-02-13-10.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * Customer invoice email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php include( MBWPE_TPL_PATH.'/settings.php' ); ?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php if ( $order->has_status( 'pending' ) ) : ?>

	<?php if ( $intro = get_option( 'woocommerce_email_mbc_ci_intro' ) ) :

		if( function_exists('pll__') )
 				$intro = pll__($intro);
 			
	 	echo apply_filters( 'woocommerce_email_mbc_ci_intro_filter', wpautop( wp_kses_post( wptexturize(  str_replace('{{pay_url}}', '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', 'woocommerce' ) . '</a>', $intro  ) ) ) ) );

	else : ?>

		<p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'woocommerce' ), get_bloginfo( 'name' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', 'woocommerce' ) . '</a>' ); ?></p>

	<?php endif; ?>

<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2 <?php echo $orderref;?>><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>

<table cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%; border: 1px solid <?php echo $bordercolor;?>;" border="1" bordercolor="<?php echo $bordercolor;?>">
	<thead>
		<tr>
			<th scope="col"  width="50%" style="text-align:left; border: 1px solid <?php echo $bordercolor;?>;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" width="25%" style="text-align:center; border: 1px solid <?php echo $bordercolor;?>;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col"  width="25%" style="text-align:center; border: 1px solid <?php echo $bordercolor;?>;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php include( MBWPE_TPL_PATH.'/tbody.php' ); ?>
	</tbody>
	<?php include( MBWPE_TPL_PATH.'/tfoot.php' ); ?>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>

<?php include( MBWPE_TPL_PATH.'/treatments.php' ); ?>