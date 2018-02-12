<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

require_once 'definitions.php';

delete_metadata( 'post', null, WCWL_SLUG, null, true );
delete_metadata( 'user', null, WCWL_SLUG, null, true );
delete_metadata( 'post', null, WCWL_SLUG . '_count', null, true );
delete_metadata( 'post', null, '_' . WCWL_SLUG . '_count', null, true );
delete_metadata( 'post', null, WCWL_SLUG . '_has_dates', null, true );
delete_option( WCWL_SLUG );
delete_option( WCWL_SLUG . '_registration_needed' );
delete_option( WCWL_SLUG . '_archive_on' );