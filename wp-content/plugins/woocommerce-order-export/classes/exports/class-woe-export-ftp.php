<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WOE_Export_Ftp extends WOE_Export {
	var $timeout = 15; //in seconds 

	public function run_export( $filename, $filepath ) {

		//use default port?
		if ( empty( $this->destination['ftp_port'] ) ) {
			$this->destination['ftp_port'] = 21;
		}
		
		//1	
		$conn_id = @ftp_connect( $this->destination['ftp_server'], $this->destination['ftp_port'], $this->timeout );
		if ( ! $conn_id ) {
			return sprintf( __( "Can not connect to %s using port %s", 'woocommerce-order-export' ),
				$this->destination['ftp_server'], $this->destination['ftp_port'] );
		}

		//2
		if ( ! @ftp_login( $conn_id, $this->destination['ftp_user'], $this->destination['ftp_pass'] ) ) {
			return sprintf( __( "Can not login to FTP as user '%s' using password '%s'", 'woocommerce-order-export' ),
				$this->destination['ftp_user'], $this->destination['ftp_pass'] );
		}

		//3?
		if ( @$this->destination['ftp_passive_mode'] AND ! @ftp_pasv( $conn_id, true ) ) {
			return __( "Can not switch to Passive Mode", 'woocommerce-order-export' );
		}

		if ( $this->destination['ftp_path'] ) {
			if ( substr( $this->destination['ftp_path'], 0, 1 ) != '/' ) {
				$this->destination['ftp_path'] = '/' . $this->destination['ftp_path'];
			}
			if ( ! @ftp_chdir( $conn_id, $this->destination['ftp_path'] ) ) {
				return sprintf( __( "Can not change FTP directory to '%s'", 'woocommerce-order-export' ),
					$this->destination['ftp_path'] );
			}
		}
		
		//4 support append 
 		if ( @$this->destination['ftp_append_existing'] ) {
			$ftp_files = ftp_nlist($conn_id, $this->destination['ftp_path']);
			//got existing file?
			if ( in_array($filename, $ftp_files ) ) {
				$existing_file = tempnam( sys_get_temp_dir(), "ftp");
				if ( ! ftp_get( $conn_id, $existing_file, $filename, FTP_BINARY ) ) {
					return sprintf( __( "Can not download file '%s'", 'woocommerce-order-export' ), $filename );
				}
				//!empty file, must call merger hook
				if( filesize($existing_file) ) {
					do_action('woe_ftp_append_'.WC_Order_Export_Engine::$current_job_settings['format'], $existing_file, $filepath);
				}
				unlink($existing_file);
			}
 		}

		if ( ! @ftp_put( $conn_id, $filename, $filepath, FTP_BINARY ) ) {
			return sprintf( __( "Can not upload file '%s'", 'woocommerce-order-export' ), $filename );
		}

		@ftp_close( $conn_id );

		return sprintf( __( "We have uploaded file '%s' to '%s'", 'woocommerce-order-export' ), $filename,
			$this->destination['ftp_server'] . $this->destination['ftp_path'] );
	}
}
