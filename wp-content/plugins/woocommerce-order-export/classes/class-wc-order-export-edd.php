<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_EDD {

    private static $instance;
	/**
	 * WC_Order_Export_EDD constructor.
	 */
	private function __construct() {
		// EDD license actions
		add_action( 'admin_init', array( $this, 'edd_woe_plugin_updater' ), 0 );
		add_action( 'admin_init', array( $this, 'edd_woe_register_option' ) );
		add_action( 'admin_init', array( $this, 'edd_woe_activate_license' ) );
		add_action( 'admin_init', array( $this, 'edd_woe_deactivate_license' ) );
	}

	//***********  EDD LICENSE FUNCTIONS BEGIN  *************************************************************************************************************************************************************************************************************************************************
	function edd_woe_plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'edd_woe_license_key' ) );

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WOE_STORE_URL, 'woocommerce-order-export/woocommerce-order-export.php', array(
				'version' 	=> WOE_VERSION,   // current version number
				'license' 	=> $license_key,  // license key (used get_option above to retrieve from DB)
				'item_name' => WOE_ITEM_NAME, // name of this plugin
				'author' 	=> WOE_AUTHOR     // author of this plugin
			)
		);

	}

	function edd_woe_license_page() {
		$license 	= get_option( 'edd_woe_license_key' );
		$status 	= get_option( 'edd_woe_license_status' );
		$error 	    = get_option( 'edd_woe_license_error' );
		?>
		<div class="wrap">
		<h2><?php _e('Plugin License Options'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('edd_woe_license'); ?>

			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e('License Key'); ?>
					</th>
					<td>
						<input id="edd_woe_license_key" name="edd_woe_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
						<label class="description" for="edd_woe_license_key"><?php _e('Enter your license key'); ?></label>
					</td>
				</tr>
				<?php if( false !== $license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Activate License'); ?>
						</th>
						<td>
							<?php if( $status !== false && $status == 'valid' ) { ?>
								<span style="color:green;"><?php _e('active'); ?></span>
								<?php wp_nonce_field( 'edd_woe_nonce', 'edd_woe_nonce' ); ?>
								<input type="submit" class="button-secondary" name="edd_woe_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
							<?php } else {
								if ( ! empty( $error ) ) { ?>
									<span style="color:red;"><?php echo $error; ?></span>
								<?php }
								wp_nonce_field( 'edd_woe_nonce', 'edd_woe_nonce' ); ?>
								<input type="submit" class="button-secondary" name="edd_woe_license_activate" value="<?php _e('Activate License'); ?>"/>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
		<?php
	}

	function edd_woe_register_option() {
		// creates our settings in the options table
		register_setting('edd_woe_license', 'edd_woe_license_key', array($this, 'edd_sanitize_license') );
	}

	function edd_sanitize_license( $new ) {
		$old = get_option( 'edd_woe_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'edd_woe_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}



	/************************************
	 * this illustrates how to activate
	 * a license key
	 *************************************/

	function edd_woe_activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_woe_license_activate'] ) ) {

			// run a quick security check
			if( ! check_admin_referer( 'edd_woe_nonce', 'edd_woe_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( $_POST['edd_woe_license_key'] );
			update_option( 'edd_woe_license_key', $license );


			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( WOE_ITEM_NAME ), // the name of our product in EDD
				'url'       => WOE_MAIN_URL
			);

			// Call the custom API.
			$response = wp_remote_post( WOE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'edd_woe_license_status', $license_data->license );
			update_option( 'edd_woe_license_error', @$license_data->error );

		}
	}

	function edd_woe_force_deactivate_license() {
		$this->_edd_woe_deactivate_license();
	}

	function edd_woe_deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_woe_license_deactivate'] ) ) {

			// run a quick security check
			if( ! check_admin_referer( 'edd_woe_nonce', 'edd_woe_nonce' ) )
				return; // get out if we didn't click the Activate button

			$this->_edd_woe_deactivate_license();
		}
	}

	private function _edd_woe_deactivate_license() {
		// retrieve the license from the database
		$license = trim( get_option( 'edd_woe_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( WOE_ITEM_NAME ), // the name of our product in EDD
			'url'       => WOE_MAIN_URL
		);

		// Call the custom API.
		$response = wp_remote_post( WOE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'edd_woe_license_status' );
		delete_option( 'edd_woe_license_error' );
    }

	function edd_woe_check_license() {

		global $wp_version;

		$license = trim( get_option( 'edd_woe_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_name' => urlencode( WOE_ITEM_NAME ),
			'url'       => WOE_MAIN_URL
		);

		// Call the custom API.
		$response = wp_remote_post( WOE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->license == 'valid' ) {
			echo 'valid'; exit;
			// this license is still valid
		} else {
			echo 'invalid'; exit;
			// this license is no longer valid
		}
	}

	public static function getInstance() {
	    if ( empty( self::$instance ) ) {
		    self::$instance = new self();
        }

        return self::$instance;
    }
//***********  EDD LICENSE FUNCTIONS END  *************************************************************************************************************************************************************************************************************************************************
}
WC_Order_Export_EDD::getInstance();