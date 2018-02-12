<?php

	class YIKES_Custom_Product_Tabs_Pro_Support {

		/**
		* Constructah >:^)
		*/
		public function __construct() {

			// Add our support page content
			add_action( 'yikes-woo-support-page-pro', array( $this, 'render_support_page' ), 20 );

			// Enqueue scripts & styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10, 1 );

			// AJAX call to send support request
			add_action( 'wp_ajax_cptpro_send_support_request', array( $this, 'cptpro_send_support_request' ) );
		}

		/**
		* Enqueue our scripts and styes
		*
		* @param string | $page | The slug of the page we're currently on
		*/
		public function enqueue_scripts( $page ) {

			if ( $page === 'custom-product-tabs-pro_page_' . YIKES_Custom_Product_Tabs_Support_Page ) {

				wp_enqueue_style ( 'repeatable-custom-tabs-styles' , YIKES_Custom_Product_Tabs_URI . 'css/repeatable-custom-tabs.min.css', '', YIKES_Custom_Product_Tabs_Version, 'all' );
				wp_enqueue_script( 'icheck', YIKES_Custom_Product_Tabs_Pro_URI . 'js/icheck.min.js', array( 'jquery' ) );
				wp_enqueue_style ( 'icheck-flat-blue-styles', YIKES_Custom_Product_Tabs_Pro_URI . 'css/flat/blue.css' );
				wp_enqueue_style ( 'cptpro-settings-styles', YIKES_Custom_Product_Tabs_Pro_URI . 'css/settings.min.css' );
				wp_enqueue_script( 'cptpro-shared-scripts', YIKES_Custom_Product_Tabs_Pro_URI . 'js/shared.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'cptpro-settings-scripts', YIKES_Custom_Product_Tabs_Pro_URI . 'js/support.min.js', array( 'icheck' ) );
				wp_localize_script( 'cptpro-settings-scripts', 'support_data', array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'cptpro_send_support_request' ),
						'action'   => 'cptpro_send_support_request',
					)
				);
			}
		}

		/**
		* Ping our yikesplugins API to create a Fresh Desk support ticket [AJAX]
		*/
		public function cptpro_send_support_request() {

			// Verify the nonce
			if ( ! check_ajax_referer( 'cptpro_send_support_request', 'nonce', false ) ) {
			 	wp_send_json_error();
			}

			$name     = isset( $_POST['name'] ) ? $_POST['name'] : '';
			$email    = isset( $_POST['email'] ) ? $_POST['email'] : '';
			$topic    = isset( $_POST['topic'] ) ? $_POST['topic'] : '';
			$issue    = isset( $_POST['issue'] ) ? $_POST['issue'] : '';
			$priority = isset( $_POST['priority'] ) ? $_POST['priority'] : 1;
			$license  = isset( $_POST['license'] ) ? $_POST['license'] : '';

			$ticket_array = array(
				'action'           => 'yikes-support-request',
				'license_key'      => base64_encode( $license ),
				'plugin_name'      => 'Custom Product Tabs Pro',
				'edd_item_id'      => YIKES_Custom_Product_Tabs_Pro_License_Item_ID,
				'user_email'       => $email,
				'site_url'         => esc_url( home_url() ),
				'support_name'     => $name,
				'support_topic'    => $topic,
				'support_priority' => $priority,
				'support_content'  => $issue,
				'api_version'      => '2'
			);

			// Call the custom API.
			$response = wp_remote_post( 'https://yikesplugins.com', array(
				'timeout'   => 30,
				'sslverify' => false,
				'body'      => $ticket_array
			) );

			// Catch the error
			if( is_wp_error( $response ) ) {
				wp_send_json_error( $response->getMessage() );
			}

			// Retrieve our body
			$response_body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $response_body->success ) && $response_body->success === true ) {
				wp_send_json_success( array( 'redirect_url' => add_query_arg( array( 'success' => 'success', 'page' => YIKES_Custom_Product_Tabs_Support_Page ), admin_url( 'admin.php' ) ) ) );
			} else {
				wp_send_json_error( array( 'message' => $response_body->message ) );
			}
		}

		public function render_support_page() {

			// Get our options array
			$settings = get_option( 'cptpro_settings' );

			// License
			$license = isset( $settings['licensing'] ) && isset( $settings['licensing']['license'] ) ? $settings['licensing']['license'] : '';
		?>
			<!-- No License Message -->
			<div class="yikes-custom-notice yikes-custom-notice-failure" <?php echo ! empty( $license ) ? 'style="display: none;"' : ''; ?>>
				<span class="yikes-custom-notice-content yikes-custom-notice-content-failure">
					<span class="dashicons dashicons-warning"></span> 
					<span class="yikes-custom-notice-message">
						<?php 
							echo sprintf( __( 'Making a support ticket requires a valid, active license. Please enter your license on the %1ssettings page%2s. If you have any issues, email us at support@yikesinc.com', 'custom-product-tabs-pro' ), 
									'<a href="' . esc_url_raw( add_query_arg( array( 'page' => YIKES_Custom_Product_Tabs_Pro_Settings_Page ), admin_url( 'admin.php' ) ) ) . '">', '</a>' ); 
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
			<div class="yikes-custom-notice dashicons-warning yikes-support-notice-failure" style="display: none;">
				<span class="yikes-custom-notice-content yikes-custom-notice-content-failure">
					<span class="dashicons dashicons-warning"></span> 
					<span class="yikes-custom-notice-message"><?php _e( 'A required field is missing.', 'custom-product-tabs-pro' ) ?></span>
					<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
				<span>
			</div>

			<p>
				<?php _e( 'If you have any problems with the form, send an email to <a href="mailto:support@yikesinc.com">support@yikesinc.com</a> and a ticket will be created.', 'custom-product-tabs-pro'  ); ?>
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
		<?php
		}
		
	}

	new YIKES_Custom_Product_Tabs_Pro_Support();