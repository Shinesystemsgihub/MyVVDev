<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WOE_Export_Email extends WOE_Export {

	private static $from = '';
	private static $from_name = '';

	public function run_export( $filename, $filepath ) {
		//must rename tmp file
		$newfilepath = dirname( $filepath ) . "/" . $filename;
		//die($newfilepath);
		if ( !@copy( $filepath, $newfilepath ) ) {
			return __( "Can not rename temporary file", 'woocommerce-order-export' );
		}

		$to		 = $this->destination[ 'email_recipients' ];
		$subject = apply_filters("woe_export_email_subject", WC_Order_Export_Engine::make_filename($this->destination[ 'email_subject' ]) );
//		$message = sprintf( __( 'Order Export for %s', 'woocommerce-order-export' ),
//			date_i18n( wc_date_format(), current_time( 'timestamp' ) ) );
		
		@$message = WC_Order_Export_Engine::make_filename($this->destination[ 'email_body' ]);
		if( empty($message) )
			$message = __( "Please, review the attachment", 'woocommerce-order-export' );
			
		if ( $message != strip_tags($message) )
			$headers = "Content-Type: text/html\r\n";
		else
			$headers = "Content-Type: text/plain\r\n";

		self::$from      = $this->destination[ 'email_from' ];
		self::$from_name = $this->destination[ 'email_from_name' ];

		$headers .= "From: <" . self::$from . ">\r\n";
		add_action( 'phpmailer_init', array( $this, 'smtp_phpmailer_init' ) );

		$attachments = apply_filters("woe_export_email_attachments", array( $newfilepath ) );

		try {
			$result = wp_mail( $to, $subject, $message, $headers, $attachments );
		} catch (Exception $e) {
			//$e->getMessage();
			$result = false;
		}		
		
		//delete renamed copy 
		unlink($newfilepath);
		
		if ( !$result ) {
			global $ts_mail_errors;
			global $phpmailer;
			if ( !isset( $ts_mail_errors ) ) {
				$ts_mail_errors = array();
			}
			if ( isset( $phpmailer ) ) {
				$ts_mail_errors[] = $phpmailer->ErrorInfo;
			}
		}
		if ( empty( $ts_mail_errors ) ) {
			$return = sprintf( __( "We have sent file '%s' to '%s'", 'woocommerce-order-export' ), $filename, $to );
		} else {
			$return = implode( ';', $ts_mail_errors );
		}

		return $return;
	}

	public function smtp_phpmailer_init($phpmailer) {
		$phpmailer->From = self::$from;
		$phpmailer->FromName = self::$from_name;
	}

}
