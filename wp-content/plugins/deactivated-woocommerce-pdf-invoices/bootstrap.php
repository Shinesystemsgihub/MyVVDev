<?php
/**
 * Plugin Name:       My Vacay Valet Pick-up list
 * Description:       Custom plugin for My Vacay Valet which creates Pick-up list for Woocommerce Orders.
 * Version:           1.0
 * Author:            Ashish Jena
 */

defined( 'ABSPATH' ) or exit;

/**
 * @deprecated instead use WPI_VERSION.
 */
define( 'MVVPS_VERSION', '2.8.1' );

define( 'WPI_VERSION', '2.8.1' );

/**
 * Load WooCommerce PDF Invoices plugin.
 */
function _mvvps_load_plugin() {
	/**
	 * @deprecated instead use `WPI_FILE`.
	 */
	define( 'MVVPS_FILE', __FILE__ );
	/**
	 * @deprecated instead use `WPI_DIR`.
	 */
	define( 'MVVPS_DIR', plugin_dir_path( __FILE__ ) );
	/**
	 * @deprecated instead use `plugin_basename( WPI_FILE )`.
	 */
	define( 'MVVPS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

	// Constants.
	define( 'WPI_FILE', __FILE__ );
	define( 'WPI_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

	require_once WPI_DIR . '/vendor/autoload.php';

	/**
	 * Main instance of BE_WooCommerce_PDF_Invoices.
	 *
	 * @since  2.5.0
	 * @return BE_WooCommerce_PDF_Invoices
	 */
	function MVVPS() {
		return BE_WooCommerce_PDF_Invoices::instance();
	}
	MVVPS();

	//_mvvps_on_plugin_update();
}
add_action( 'plugins_loaded', '_mvvps_load_plugin', 10 );

/**
 * On plugin update.
 *
 * @since 2.5.0
 */
function _mvvps_on_plugin_update() {
	$current_version = get_site_option( 'mvvps_version' );
	if ( WPI_VERSION !== $current_version ) {

		// temporary change max execution time to higher value to prevent internal server errors.
		$max_execution_time = (int) ini_get( 'max_execution_time' );
		if ( 0 !== $max_execution_time ) {
			set_time_limit( 360 );
		}

		// version 2.6.1- need to be updated with new email options and postmeta.
		/*if ( version_compare( $current_version, '2.6.1' ) <= 0 ) {
			update_email_type_options();
			update_postmeta();
		}
		*/

		// version 2.7.0- uploads folder changed to uploads/mvv-pick-up-slip.
		/*if ( version_compare( $current_version, '2.7.0' ) <= 0 ) {
			MVVPS()->setup_directories();

			// Move invoice from uploads/mvvps-invoices to uploads/mvv-pick-up-slip/attachments.
			move_pdf_invoices();

			// Rename uploads/mvvps-templates/invoices to uploads/mvvps-templates/invoice.
			$upload_dir = wp_upload_dir();
			rename( MVVPS_CUSTOM_TEMPLATES_INVOICES_DIR, $upload_dir['basedir'] . '/mvvps-templates/invoice' );
		}*/

		set_time_limit( $max_execution_time );

		update_site_option( 'mvvps_version', WPI_VERSION );
	}
}

/**
 * "Attach to Email" and "Attach to new order email" options changed to multi-checkbox, so update settings accordingly.
 *
 * @since 2.5.0
 */
/*
function update_email_type_options() {
	$general_options = get_option( 'mvvps_general_settings' );
	// check if we need to add and/or remove options.
	if ( isset( $general_options['mvvps_email_type'] ) ) {
		$email_type = $general_options['mvvps_email_type'];
		if ( ! empty( $email_type ) ) {
			// set new email type option.
			$general_options[ $email_type ] = 1;
		}
		// delete old option.
		unset( $general_options['mvvps_email_type'] );

		update_option( 'mvvps_general_settings', $general_options );
	}

	if ( isset( $general_options['mvvps_new_order'] ) ) {
		$email_type = $general_options['mvvps_new_order'];
		if ( $email_type ) {
			// set invoice attach to new order email option.
			$general_options['new_order'] = 1;
		}
		// delete old option.
		unset( $general_options['mvvps_new_order'] );

		update_option( 'mvvps_general_settings', $general_options );
	}
}
*/

/**
 *  Update postmeta in database.
 */
/*
function update_postmeta() {
	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'shop_order',
		'post_status' => array_keys( wc_get_order_statuses() ),
		'fields'      => 'ids',
	) );

	$template_options = get_option( 'mvvps_template_settings' );

	$date_format = $template_options['mvvps_date_format'];
	if ( empty( $date_format ) ) {
		$date_format = (string) get_option( 'date_format' );
	}

	foreach ( $posts as $post_id ) {
		// Create pdf path postmeta for all shop orders.
		create_pdf_path_postmeta( $post_id, $template_options );

		// Format date postmeta to mysql date.
		update_date_format_postmeta( $post_id, $date_format );
	}
}
*/

/**
 * Create full path postmeta for all orders that have a pdf invoice generated.
 *
 * @param int   $post_id Post ID or WC Order ID.
 * @param array $template_options User template options.
 *
 * @since 2.6.0
 */
function create_pdf_path_postmeta( $post_id, $template_options ) {
	$pdf_path = get_post_meta( $post_id, '_mvvps_invoice_pdf_path', true );
	if ( $pdf_path ) {
		return;
	}

	$formatted_invoice_number = get_post_meta( $post_id, '_mvvps_formatted_invoice_number', true );
	if ( ! $formatted_invoice_number ) {
		return;
	}

	// One folder for all invoices.
	$new_pdf_path = $formatted_invoice_number . '.pdf';
	if ( /*(bool) 1 */ true) {
		// Yearly sub-folders.
		$invoice_year = get_post_meta( $post_id, '_mvvps_invoice_year', true );
		if ( $invoice_year ) {
			$new_pdf_path = $invoice_year . '/' . $formatted_invoice_number . '.pdf';
		}
	} else {
		$new_pdf_path = $formatted_invoice_number . '.pdf';
	}

	if ( file_exists( MVVPS_INVOICES_DIR . $new_pdf_path ) ) {
		update_post_meta( $post_id, '_mvvps_invoice_pdf_path', $new_pdf_path );
	}
}

/**
 * Format date postmeta to mysql date.
 *
 * @param int    $post_id Post ID or WC Order ID.
 * @param string $date_format User option date format.
 *
 * @since 2.6.0
 */
function update_date_format_postmeta( $post_id, $date_format ) {
	$invoice_date = get_post_meta( $post_id, '_mvvps_invoice_date', true );
	if ( ! $invoice_date ) {
		return;
	}

	$date = DateTime::createFromFormat( $date_format, $invoice_date );
	if ( ! $date ) {
		return;
	}

	update_post_meta( $post_id, '_mvvps_invoice_date', $date->format( 'Y-m-d H:i:s' ) );
}

/**
 * Move all invoices to new uploads dir.
 */
function move_pdf_invoices() {
	$files = glob( MVVPS_INVOICES_DIR . '/*' );
	foreach ( $files as $file ) {

		if ( is_dir( $file ) ) {
			wp_mkdir_p( WPI_ATTACHMENTS_DIR . '/' . basename( $file ) );

			$files_year = glob( $file . '/*' );
			foreach ( $files_year as $file_year ) {
				if ( is_file( $file_year ) ) {
					$pdf_path = str_replace( MVVPS_INVOICES_DIR . '/', '', $file_year );
					copy( $file_year, WPI_ATTACHMENTS_DIR . '/' . $pdf_path );
				}
			}

			continue;
		}

		copy( $file, WPI_ATTACHMENTS_DIR . '/' . basename( $file ) );
	}
}

/**
 * Save install date, plugin version to db and set transient to show activation notice.
 *
 * @since 2.5.0
 */
function _mvvps_on_plugin_activation() {
	// save install datetime for plugin activation admin notice.
	update_site_option( 'mvvps_install_date', current_time( 'mysql' ) );

	// use transient to display activation admin notice.
	set_transient( 'mvvps-admin-notice-activation', true, 30 );

	update_site_option( 'mvvps_version', WPI_VERSION );
}
register_activation_hook( __FILE__, '_mvvps_on_plugin_activation' );
