<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $woocommerce;

?>

<div id="wc_pre_orders_data" class="panel woocommerce_options_panel">
	<div class="options_group">
		<?php

		if ( $has_active_pre_orders = WC_Pre_Orders_Product::product_has_active_pre_orders( $post->ID ) ) {
			echo '<p><strong>' . sprintf( __( 'There are active pre-orders for this product. To change the release date, %suse the Actions menu%s.', 'wc-pre-orders' ), '<a href="' . admin_url( 'admin.php?page=wc_pre_orders&tab=actions&section=change-date' ) . '">', '</a>' ) . '</strong></p>';
			
			echo '<p><strong>' . sprintf( __( 'To change other settings, please %scomplete or cancel the active pre-orders%s first.', 'wc-pre-orders' ), '<a href="' . admin_url( 'admin.php?page=wc_pre_orders' ) . '">', '</a>' ) . '</strong></p>';
		}

		do_action( 'wc_pre_orders_product_options_start' );

		// Enable pre-orders.
		woocommerce_wp_checkbox(
			array(
				'id'          => '_wc_pre_orders_enabled',
				'label'       => __( 'Enable Pre-Orders', 'wc-pre-orders' ),
				'description' => __( 'Enable pre-orders for this product. For variable products, pre-orders are enabled for each variation.', 'wc-pre-orders' ),
			)
		);

		// Availability date/time.
		$availability_timestamp = WC_Pre_Orders_Product::get_localized_availability_datetime_timestamp( $post->ID );
		
		?><p class="form-field _wc_pre_orders_availability_datetime_field ">
			<label for="_wc_pre_orders_availability_datetime"><?php _e( 'Availability Date/Time', 'wc-pre-orders' ); ?></label>
			<input type="text" class="short" name="_wc_pre_orders_availability_datetime" id="_wc_pre_orders_availability_datetime" value="<?php echo esc_attr( ( 0 === $availability_timestamp ) ? '' : date( 'Y-m-d H:i', $availability_timestamp ) ); ?>" placeholder="YYYY-MM-DD HH:MM"  />
			<img class="help_tip" data-tip="<?php printf( __( 'Set the date %s time that this pre-order will be available. The product will behave as a normal product when this date/time is reached.', 'wc-pre-orders' ), '&amp;' ); ?>" src="<?php echo esc_url( $woocommerce->plugin_url() . '/assets/images/help.png' ); ?>" height="16" width="16" />
		</p>
		
		<p class="form-field _wc_pre_orders_availability_date_approx_field ">
			<?php 
			$availability_date_approx = WC_Pre_Orders_Product::get_localized_availability_date_approx( $post->ID, false );
			$avail_years = array();
			$avail_months = array();
			
			$avail_years[0] = __( 'Select Year', 'wc-pre-orders' );
			$avail_months[0] = __( 'Select Month', 'wc-pre-orders' );
			
			for($i = date('Y'); $i <= date('Y') + 4; $i ++)
			{
				$avail_years[$i] = $i;
			}
			
			for($i = 1; $i <= 12; $i ++)
			{
				$month = strlen($i) == 1 ? "0$i" : $i;
				$avail_months[$month] = $month;
			}
			
			woocommerce_wp_select( array(
				'id'          => '_wc_pre_orders_availability_date_year',
				'label'       => __( 'Availability Year', 'wc-pre-orders' ),
				'value' => $availability_date_approx[0],
				'options' => $avail_years
			) );
			woocommerce_wp_select( array(
				'id'          => '_wc_pre_orders_availability_date_month',
				'label'       => __( 'Availability Month', 'wc-pre-orders' ),
				'value' => $availability_date_approx[1],
				'options' => $avail_months
			) );
			woocommerce_wp_select( array(
				'id'          => '_wc_pre_orders_availability_date_range',
				'label'       => __( 'Availability Range', 'wc-pre-orders' ),
				'value' => $availability_date_approx[2],
				'options' => array(
					'' => __( 'Select Range', 'wc-pre-orders' ),
					'first' => __( 'First', 'wc-pre-orders' ),
					'middle' => __( 'Midlle', 'wc-pre-orders' ),
					'end' => __( 'End', 'wc-pre-orders' ),
				)
			) );
			?>
		</p>
		<?php

		// Pre-order fee
		woocommerce_wp_text_input(
			array(
				'id'          => '_wc_pre_orders_fee',
				'label'       => __( 'Pre-Order Fee', 'wc-pre-orders' ),
				'description' => __( 'Set a fee to be charged when a pre-order is placed. Leave blank to not charge a pre-order fee.', 'wc-pre-orders' ),
				'desc_tip'    => true,
			)
		);

		woocommerce_wp_select(
			array(
				'id'          => '_wc_pre_orders_when_to_charge',
				'label'       => __( 'When to Charge', 'wc-pre-orders' ),
				'description' => __( 'Select "Upon Release" to charge the entire pre-order amount (the product price + pre-order fee if applicable) when the pre-order becomes available. Select "Upfront" to charge the pre-order amount during the initial checkout.', 'wc-pre-orders' ),
				'desc_tip'    => true,
				'default'     => 'upon_release',
				'options'     => array(
// 					'upon_release' => __( 'Upon Release', 'wc-pre-orders' ),
					'upfront'      => __( 'Upfront', 'wc-pre-orders' ),
				),
			)
		);

		do_action( 'wc_pre_orders_product_options_end' );
	?>
	</div>
	<?php
		// Disable fields if the product has active pre-orders.
		if ( $has_active_pre_orders ) {
			ob_start();
			?>
				$( 'input[name^=_wc_pre_orders_], select#_wc_pre_orders_when_to_charge' ).attr( 'disabled', true );
				$( 'img.ui-datepicker-trigger' ).css( 'display', 'none' );
			<?php
			if ( function_exists( 'wc_enqueue_js' ) ) {
				wc_enqueue_js( ob_get_clean() );
			} else {
				$woocommerce->add_inline_js( ob_get_clean() );
			}
		}
	?>
</div>
