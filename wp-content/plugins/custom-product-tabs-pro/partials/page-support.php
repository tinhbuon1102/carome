<?php

	// Get our options array
	$settings = get_option( 'cptpro_settings' );

	// License
	$license = isset( $settings['licensing'] ) && isset( $settings['licensing']['license'] ) ? $settings['licensing']['license'] : '';
?>

<div class="wrap woo-ct-admin-page-wrap">	
	<h1>
		<span class="dashicons dashicons-exerpt-view"></span> 
		<?php _e( 'Custom Product Tabs Pro | Support', 'custom-product-tabs-pro' ); ?>
	</h1>

	<div class="cptpro-settings cptpro-settings-support-help-container">

		<!-- License -->
		<h2><?php _e( 'Support', 'custom-product-tabs-pro' ); ?></h2>

		<!-- No License Message -->
		<div class="yikes-custom-notice yikes-custom-notice-failure" <?php echo ! empty( $license ) ? 'style="display: none;"' : ''; ?>>
			<span class="yikes-custom-notice-content yikes-custom-notice-content-failure">
				<span class="dashicons dashicons-warning"></span> 
				<span class="yikes-custom-notice-message">
					<?php 
						echo sprintf( __( 'Making a support ticket requires a valid, active license. Please enter your license on the %1ssettings page%2s. If you have any issues, email us at support@yikesinc.com', 'custom-product-tabs-pro' ), '<a href="' . esc_url_raw( add_query_arg( array( 'page' => YIKES_Custom_Product_Tabs_Pro_Settings_Page ), admin_url( 'admin.php' ) ) ) . '" target="_blank">', '</a>' ); 
					?>	

					<?php _e( '', 'custom-product-tabs-pro' ) ?>
					
				</span>
				<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
			<span>
		</div>

		<!-- Support Success Message -->
		<div class="yikes-custom-notice yikes-custom-notice-success yikes-custom-notice-success" <?php echo isset( $_GET['success'] ) ? '' : 'style="display: none;"' ?>>
			<span class="yikes-custom-notice-content yikes-custom-notice-content-success">
				<span class="dashicons dashicons-yes"></span> 
				<span class="yikes-custom-notice-message"><?php _e( 'Your support request has been successfully sent.', 'custom-product-tabs-pro' ) ?></span>
				<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
			<span>
		</div>

		<!-- Support Failure Message -->
		<div class="yikes-custom-notice yikes-custom-notice-failure yikes-support-notice-failure" style="display: none;">
			<span class="yikes-custom-notice-content yikes-custom-notice-content-failure">
				<span class="dashicons dashicons-warning"></span> 
				<span class="yikes-custom-notice-message"><?php _e( 'A required field is missing.', 'custom-product-tabs-pro' ) ?></span>
				<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
			<span>
		</div>

		<p>
			<?php _e( 'If you have any problems with the form, send an email to support@yikesinc.com and a ticket will be created.', 'custom-product-tabs-pro'  ); ?>
		</p>
		
		<p>
			<?php 
				echo sprintf( __( 'Before submitting a support request, please visit our %1sknowledge base%2s where we have step-by-step guides and troubleshooting help.', 'custom-product-tabs-pro'  ), 
					'<a href="https://yikesplugins.com/support/knowledge-base/product/easy-custom-product-tabs-for-woocommerce/" target="_blank">', '</a>' ); 
			?>				
		</p>

		<!-- Support Form Fields -->
		<div class="cptpro-settings cptpro-settings-support-container <?php echo empty( $license ) ? 'cptpro-settings-no-license' : ''; ?>">

			<!-- Hidden License field -->
			<input type="hidden" name="cptpro-license" id="cptpro-license" value="<?php echo esc_attr( $license ); ?>"/>

			<!-- Name -->
			<div class="cptpro-settings-field field-name">
				<label for="cptpro-name" class="checkbox-label">
					<span class="checkbox-label-text"><?php _e( 'Name:  ', 'custom-product-tabs-pro' ) ?></span>
					<input type="input" name="cptpro-name" id="cptpro-name" class="cptpro-input-text" />
					<span class="dashicons dashicons-no cptpro-name-error cptpro-error-icons" style="display: none;"></span>
				</label>
			</div>

			<!-- Email -->
			<div class="cptpro-settings-field field-email">
				<label for="cptpro-email" class="checkbox-label">
					<span class="checkbox-label-text"><?php _e( 'Email: ', 'custom-product-tabs-pro' ) ?></span>
					<input type="input" name="cptpro-email" id="cptpro-email" class="cptpro-input-text" />
					<span class="dashicons dashicons-no cptpro-email-error cptpro-error-icons" style="display: none;"></span>
				</label>
			</div>

			<!-- Topic -->
			<div class="cptpro-settings-field field-topic">
				<label for="cptpro-topic" class="checkbox-label">
					<span class="checkbox-label-text"><?php _e( 'Topic: ', 'custom-product-tabs-pro' ) ?></span>
					<input type="input" name="cptpro-topic" id="cptpro-topic" class="cptpro-input-text" />
					<span class="dashicons dashicons-no cptpro-topic-error cptpro-error-icons" style="display: none;"></span>
				</label>
			</div>

			<!-- Issue -->
			<div class="cptpro-settings-field field-issue">
				<label for="cptpro-issue" class="checkbox-label">
					<span class="checkbox-label-text"><?php _e( 'Enter your issue below, please be as detailed as possible.', 'custom-product-tabs-pro' ) ?></span>
					<span class="dashicons dashicons-no cptpro-issue-error cptpro-error-icons" style="display: none;"></span>
					<?php wp_editor( '', 'cptpro_issue', array( 'textarea_name' => 'cptpro_issue', 'media_buttons' => false ) ); ?>
				</label>
			</div>

			<!-- Priority -->
			<div class="cptpro-settings-field field-priority">
				<span class="checkbox-label-text"><?php _e( 'Priority: ', 'custom-product-tabs-pro' ) ?></span>

				<label for="cptpro-priority" class="checkbox-label cptpro-priority-checkbox-label">
					<input type="radio" value="1" name="cptpro-priority" id="cptpro-priority-low" checked="checked" />
					<?php _e( 'Low', 'custom-product-tabs-pro' ); ?>
				</label>
				<label for="cptpro-priority" class="checkbox-label cptpro-priority-checkbox-label">
					<input type="radio" value="2" name="cptpro-priority" id="cptpro-priority-medium" />
					<?php _e( 'Medium', 'custom-product-tabs-pro' ); ?>
				</label>
				<label for="cptpro-priority" class="checkbox-label cptpro-priority-checkbox-label">
					<input type="radio" value="3" name="cptpro-priority" id="cptpro-priority-high" />
					<?php _e( 'High', 'custom-product-tabs-pro' ); ?>
				</label>
				<label for="cptpro-priority" class="checkbox-label cptpro-priority-checkbox-label">
					<input type="radio" value="4" name="cptpro-priority" id="cptpro-priority-urgent" />
					<?php _e( 'Urgent', 'custom-product-tabs-pro' ); ?>
				</label>
			</div>

			<div class="cptpro-license-save">
				<button type="button" class="button button-primary cptpro-button-primary" id="cptpro-support-request"><?php _e( 'Send Support Request', 'custom-product-tabs-pro' ) ?></button>
			</div>
		</div>

	</div>
</div>