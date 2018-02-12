<?php

	// Get our options array
	$settings = get_option( 'cptpro_settings' );

	// Get our License
	$license = isset( $settings['licensing'] ) && isset( $settings['licensing']['license'] ) ? $settings['licensing']['license'] : '';

	// Unpack our settings
	$hide_tab_title         = isset( $settings['hide_tab_title'] ) && $settings['hide_tab_title'] === true ? 'checked="checked"' : ''; 
	$search_wordpress     = isset( $settings['search_wordpress'] ) && $settings['search_wordpress'] === true ? 'checked="checked"' : ''; 
	$search_woo = isset( $settings['search_woo'] ) && $settings['search_woo'] === true ? 'checked="checked"' : ''; 
?>

<div class="wrap woo-ct-admin-page-wrap">
	<h1>
		<span class="dashicons dashicons-exerpt-view"></span> 
		<?php _e( 'Custom Product Tabs Pro | Settings', 'custom-product-tabs-pro' ); ?>
	</h1>

	<!-- License -->
	<h2><?php _e( 'License', 'custom-product-tabs-pro' ); ?></h2>

	<!-- License Success Message -->
	<div class="yikes-custom-notice yikes-custom-notice-license-success" style="display: none;">
		<span class="yikes-custom-notice-content yikes-custom-notice-content-license-success">
			<span class="dashicons dashicons-yes"></span> 
			<span class="yikes-custom-notice-message"><?php _e( 'Your license has been successfully activated.', 'custom-product-tabs-pro' ) ?></span>
			<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
		<span>
	</div>

	<!-- License Failure Message -->
	<div class="yikes-custom-notice yikes-custom-notice-license-failure" style="display: none;">
		<span class="yikes-custom-notice-content yikes-custom-notice-content-license-failure">
			<span class="dashicons dashicons-no-alt"></span> 
			<span class="yikes-custom-notice-message"><?php _e( 'Your license is not valid. Please try again in a few minutes. If the issue persists, please email us at support@yikesinc.com.', 'custom-product-tabs-pro' ) ?></span>
			<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
		<span>
	</div>

	<!-- License Container -->
	<div class="cptpro-settings cptopro-settings-license-container">

		<div class="cptpro-settings-field field-hide-tab-title">
			<p class="cptpro-field-description">
				<?php _e( 'To receive updates and premium support, please enter your license key.', 'custom-product-tabs-pro' ); ?>		
			</p>
			<label for="cptpro-license" class="checkbox-label">
				<input type="input" value="<?php echo $license ?>" name="cptpro-license" id="cptpro-license" class="cptpro-input-text" />
				<!-- <span class="checkbox-label-text"><?php _e( 'Enter your license key.', 'custom-product-tabs-pro' ) ?></span> -->
				<span style="display: none;" class="dashicons dashicons-thumbs-up license-active" title="<?php _e( 'Your license is active', 'custom-product-tabs-pro' ) ?>"></span>
				<span class="dashicons dashicons-thumbs-down license-inactive" title="<?php _e( 'Your license is not yet active', 'custom-product-tabs-pro' ) ?>"></span>
				<span style="display: none;" class="license-spinner-gif"><img src="<?php echo admin_url( 'images/loading.gif' ) ?>" alt="License Details Loading"/></span>
			</label>

			<div class="cptpro-license-details cptpro-settings" style="display: none;">
				<div>
					<div class="cptpro-license-customer-label cptpro-license-field-label"><strong>Customer:</strong></div>
					<div class="cptpro-license-customer-value cptpro-license-field-value"></div>
				</div>
				<div>
					<div class="cptpro-license-limit-label cptpro-license-field-label">License Limit: </div>
					<div class="cptpro-license-limit-value cptpro-license-field-value"></div>
				</div>
				<!-- <span class="cptpro-license-site-count-label">Active Site Count: </span>
				<span class="cptpro-license-site-count-value"></span> -->
				<div>
					<div class="cptpro-license-expires-label cptpro-license-field-label">License Expires: </div>
					<div class="cptpro-license-expires-value cptpro-license-field-value"></div>
				</div>
			</div>
		</div>

		<div class="cptpro-license-save">
			<!-- Activate License -->
			<button type="button" class="button button-primary cptpro-button-primary" id="cptpro-license-activate">
				<?php _e( 'Activate License', 'custom-product-tabs-pro' ) ?>
			</button>

			<!-- Deactivate License -->
			<button style="display: none;" type="button" class="button button-primary cptpro-button-primary" id="cptpro-license-deactivate">
				<?php _e( 'Deactivate License', 'custom-product-tabs-pro' ) ?>		
			</button>
		</div>
	</div>


	<!-- Settings -->
	<h2><?php _e( 'Tab Settings', 'custom-product-tabs-pro' ); ?></h2>

	<!-- Settings Success Message -->
	<div class="yikes-custom-notice yikes-custom-notice-success" style="display: none;">
		<span class="yikes-custom-notice-content yikes-custom-notice-content-success">
			<span class="dashicons dashicons-yes"></span> 
			<span class="yikes-custom-notice-message"><?php _e( 'Your settings have been successfully saved.', 'custom-product-tabs-pro' ) ?></span>
			<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
		<span>
	</div>

	<!-- Settings Failure Message -->
	<div class="yikes-custom-notice yikes-custom-notice-failure" style="display: none;">
		<span class="yikes-custom-notice-content yikes-custom-notice-content-failure">
			<span class="dashicons dashicons-no-alt"></span> 
			<span class="yikes-custom-notice-message"><?php _e( 'Something went wrong...', 'custom-product-tabs-pro' ) ?></span>
			<span class="dashicons dashicons-dismiss yikes-custom-dismiss" title="<?php _e( 'Dismiss', 'custom-product-tabs-pro' ) ?>"></span><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'custom-product-tabs-pro' ) ?></span>
		<span>
	</div>

	<!-- Settings Container -->
	<div class="cptpro-settings cptpro-settings-settings-container">

		<span style="display: none;" class="settings-spinner-gif"><img src="<?php echo admin_url( 'images/loading.gif' ) ?>" alt="Settings Saving"/></span>

		<h3><?php _e( 'Tab Titles', 'custom-product-tabs-pro' ); ?></h3>

		<div class="cptpro-settings-field field-hide-tab-title">
			<label for="hide-tab-title" class="checkbox-label">
				<input type="checkbox" value="1" name="hide-tab-title" id="hide-tab-title" <?php echo $hide_tab_title ?> />
				<span class="checkbox-label-text"><?php _e( 'Remove tab title from tab content.', 'custom-product-tabs-pro' ) ?></span>
			</label>
			<p>
				<?php _e( 'Remove the tab title from being repeated in the tab content area.', 'custom-product-tabs-pro' ); ?>
			</p>
			<p>
				<img src="<?php echo YIKES_Custom_Product_Tabs_Pro_URI . 'images/hide-title.png' ?>" />
			</p>
		</div>

		<hr />

		<h3><?php _e( 'Search', 'custom-product-tabs-pro' ); ?></h3>

		<p>
			<?php _e( 'By default, custom tab content is not included in WordPress or WooCommerce search, adjust those settings below.', 'custom-product-tabs-pro' ); ?>
		</p>

		<div class="cptpro-settings-field field-search-wordpress">
			<label for="search-wordpress" class="checkbox-label">
				<input type="checkbox" value="1" name="search-wordpress" id="search-wordpress" <?php echo $search_wordpress ?> />
				<span class="checkbox-label-text"><?php _e( 'Include custom tab content in the WordPress search.', 'custom-product-tabs-pro' ) ?></span>
			</label>
		</div>

		<div class="cptpro-settings-field field-search-woo">
			<label for="search-woo" class="checkbox-label">
				<input type="checkbox" value="1" name="search-woo" id="search-woo" <?php echo $search_woo ?> />
				<span class="checkbox-label-text"><?php _e( 'Include custom tab content in the WooCommerce search widget.', 'custom-product-tabs-pro' ) ?></span>
			</label>
		</div>

		<div class="cptpro-settings-save">
			<button type="button" class="button button-primary cptpro-button-primary" id="cptpro-settings-save"><?php _e( 'Save Settings', 'custom-product-tabs-pro' ) ?></button>
		</div>
	</div>
</div>