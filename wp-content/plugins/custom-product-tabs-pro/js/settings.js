jQuery( document ).ready( function() {

	// Run DOM-manipulation functions on page-load
	cptpro_check_license();

	jQuery( '#cptpro-settings-save' ).click( cptpro_settings_save );

	jQuery( '#cptpro-license-activate' ).click( cptpro_activate_license );

	jQuery( '#cptpro-license-deactivate' ).click( cptpro_deactivate_license );
});


function cptpro_settings_save() {

	// Show the settings spinner gif
	jQuery( '.settings-spinner-gif' ).show();
	jQuery( '.yikes-custom-notice-success, .yikes-custom-notice-failure' ).fadeOut();

	// Hide Tab Title
	var hide_tab_title   = jQuery( '#hide-tab-title' ).prop( 'checked' );
	var search_wordpress = jQuery( '#search-wordpress' ).prop( 'checked' );
	var search_woo       = jQuery( '#search-woo' ).prop( 'checked' );

	var data = {
		action: settings_data.save_settings_action,
		hide_tab_title: hide_tab_title,
		search_wordpress: search_wordpress,
		search_woo: search_woo,
		nonce: settings_data.save_settings_nonce
	};

	jQuery.post( settings_data.ajax_url, data, function( response ) {
		if ( typeof response === 'object' && response.success === true ) {
			jQuery( '.settings-spinner-gif' ).fadeOut( 'fast', function() { jQuery( '.yikes-custom-notice-success' ).fadeIn(); });
		} else {
			jQuery( '.settings-spinner-gif' ).fadeOut( 'fast', function() { jQuery( '.yikes-custom-notice-failure' ).fadeIn(); });
		}
	});
}

function cptpro_check_license() {
	
	var license = jQuery( '#cptpro-license' ).val();

	if ( license.length === 0 ) {
		return;
	}

	// Show spinner gif
	cptpro_license_load_show_spinner_gif();

	var data = {
		license: license,
		action: settings_data.check_license_action,
		nonce: settings_data.check_license_nonce
	};

	jQuery.post( settings_data.ajax_url, data, function( response ) {
		console.log( response );

		if ( typeof response.success !== 'undefined' && response.success === true ) {

			 cptpro_handle_active_license( response.data );
		} else {

			cptpro_license_load_hide_spinner_gif_failure();

			if ( typeof response.data !== 'undefined' ) {
				alert( response.data );
			}
		}
	});

}

function cptpro_activate_license() {

	var license = jQuery( '#cptpro-license' ).val();

	if ( license.length === 0 ) {
		return;
	}

	// Show spinner gif
	cptpro_license_load_show_spinner_gif();

	var data = {
		license: license,
		action: settings_data.activate_license_action,
		nonce: settings_data.activate_license_nonce
	};

	jQuery.post( settings_data.ajax_url, data, function( response ) {
		console.log( response );

		if ( typeof response.success !== 'undefined' && response.success === true ) {

			jQuery( '.yikes-custom-notice-license-success' ).fadeIn();
			cptpro_handle_active_license( response.data );
		} else {
			jQuery( '.yikes-custom-notice-license-failure' ).fadeIn();

			cptpro_handle_deactivate_license();
		}
	});
}

function cptpro_handle_active_license( license_data ) {

	// Show the thumbs up
	jQuery( '.license-spinner-gif' ).fadeOut( 'slow', function() {
		jQuery( '.license-active' ).fadeIn();
	});

	// Show the "Deactivate" license button
	jQuery( '#cptpro-license-activate' ).fadeOut( 'slow', function() {
		jQuery( '#cptpro-license-deactivate' ).fadeIn();
	});

	// Add our customer license details data to the HTML
	var customer_name  = license_data.customer_name;
	var customer_email = license_data.customer_email;
	var expiration     = license_data.expires;
	var license_limit  = license_data.license_limit;

	jQuery( '.cptpro-license-customer-value' ).text( customer_name + ' / ' + customer_email );
	jQuery( '.cptpro-license-expires-value' ).text( expiration );
	jQuery( '.cptpro-license-limit-value' ).text( license_limit );

	// Show the customer license details section
	jQuery( '.cptpro-license-details' ).fadeIn();

}

function cptpro_deactivate_license() {

	var license = jQuery( '#cptpro-license' ).val();

	if ( license.length === 0 ) {
		return;
	}

	// Show spinner gif
	cptpro_license_load_show_spinner_gif();

	var data = {
		license: license,
		action: settings_data.deactivate_license_action,
		nonce: settings_data.deactivate_license_nonce
	};

	jQuery.post( settings_data.ajax_url, data, function( response ) {
		console.log( response );

		if ( typeof response.success !== 'undefined' && response.success === true ) {

			 cptpro_handle_deactivate_license();
		} else {

			cptpro_license_load_hide_spinner_gif_failure();

			if ( typeof response.data !== 'undefined' ) {
				alert( response.data );
			}
		}

	});
}

function cptpro_handle_deactivate_license() {

	// Hide the customer license details section
	jQuery( '.cptpro-license-details' ).fadeOut();

	// Show the thumbs down
	jQuery( '.license-spinner-gif' ).fadeOut( 'slow', function() {
		jQuery( '.license-inactive' ).fadeIn();
	});

	// Show the "Activate" license button
	jQuery( '#cptpro-license-deactivate' ).fadeOut( 'slow', function() {
		jQuery( '#cptpro-license-activate' ).fadeIn();
	});
}

function cptpro_license_load_show_spinner_gif() {
	jQuery( '.license-active, .license-inactive' ).hide();
	jQuery( '.license-spinner-gif' ).show();
}

function cptpro_license_load_hide_spinner_gif_failure() {
	jQuery( '.license-inactive' ).show();
	jQuery( '.license-spinner-gif' ).hide();
}