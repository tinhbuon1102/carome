<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WOE_Export_Sftp extends WOE_Export {
	var $timeout = 15; //in seconds 

	public function run_export( $filename, $filepath ) {

		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).'/phpseclib');
		include('Net/SFTP.php');
		
		//use default port?
		if ( empty( $this->destination['sftp_port'] ) ) {
			$this->destination['sftp_port'] = 22;
		}
		
		//adjust path final /
		if ( substr( $this->destination['sftp_path'], -1 ) != '/' ) {
			$this->destination['sftp_path'] .= '/' ;
		}
		
		$sftp = new Net_SFTP( $this->destination['sftp_server'], $this->destination['sftp_port'], $this->timeout);
		if ( !$sftp->login( $this->destination['sftp_user'], $this->destination['sftp_pass']) ) {
			return sprintf( __( "Can't login to SFTP as user '%s' using password '%s'", 'woocommerce-order-export' ),
				$this->destination['sftp_user'], $this->destination['sftp_pass'] );
		}
		
		if ( !$sftp->put( $this->destination['sftp_path'].$filename, $filepath, NET_SFTP_LOCAL_FILE) ) {
			return sprintf( __( "Can't upload file '%s'. SFTP errors: %s", 'woocommerce-order-export' ), $filename, join("\n", $sftp->getSFTPErrors() ) );
		}
		
		return sprintf( __( "We have uploaded file '%s' to '%s'", 'woocommerce-order-export' ), $filename,
			$this->destination['sftp_server'] . $this->destination['sftp_path'] );
	}
}
