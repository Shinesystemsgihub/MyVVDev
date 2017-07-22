<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "0e19de3afa980d87356ce12451e6b88c453ceb6a1d"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/my-vacay-valet-timeslot-restriction/my-vacay-valet-timeslot-restriction.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/my-vacay-valet-timeslot-restriction/my-vacay-valet-timeslot-restriction_2017-02-27-14.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php

/*
Plugin Name: My Vacay Valet Timeslot Restriction
Description: This plugin is exclusively built for My Vacay Valet.
Version: 1.0
Author: Ashish Jena
Author URI: https://twitter.com/ashishjena94
*/


if ( ! class_exists('mvv_tsr') ) {

	class mvv_tsr {

		public function __construct () {

			add_action('wp_enqueue_scripts', array(&$this, 'mvv_tsr_scripts') );

			add_action('woocommerce_before_add_to_cart_button', array(&$this, 'mvv_tsr_datepicker_product_page') );

			add_filter('woocommerce_add_cart_item_data', array(&$this, 'mvv_tsr_add_date_cart'), 20, 2 );
			add_filter( 'woocommerce_get_item_data', array( &$this, 'mvv_tsr_get_item_data' ), 25, 2 );

			add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'mvv_tsr_order_item_meta' ), 10, 2 );

			add_filter( 'woocommerce_checkout_fields', array(&$this, 'mvv_tsr_checkout_fields'), 10, 1 );

			add_action( 'woocommerce_after_checkout_validation', array(&$this, 'mvv_tsr_date_validation'), 10, 1 );

			add_action( 'wp_ajax_mvv_available_timeslots', array(&$this, 'mvv_tsr_available_timeslots') );

			add_action( 'wp_ajax_nopriv_mvv_available_timeslots', array(&$this, 'mvv_tsr_available_timeslots') );			

		}

		function mvv_tsr_scripts() {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css?ver=4.7.2');

			wp_enqueue_script('mvv_tsr_scripts', plugin_dir_url( __FILE__ ) . 'js/mvv-tsr.js', 'jquery' );

			$mvv_tsr_params = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				);

			wp_localize_script('mvv_tsr_scripts', 'mvv_tsr_params', $mvv_tsr_params );
		}

		function mvv_tsr_datepicker_product_page() {

			echo '<p>Enter Date: <input type ="text" id ="datepicker-1" name="datepicker-1" class="datepicker-1"></p>';
			echo '<div class="test_div"></div>' ;

		}

		function mvv_tsr_add_date_cart($cart_item_meta, $product_id){


			$cart_arr = array();

			if ( isset( $_POST[ 'datepicker-1' ] ) ) {
                $cart_arr[ 'arrival_date' ] = $_POST[ 'datepicker-1' ];
            }
            $cart_item_meta[ 'mvv_tsr' ][] = $cart_arr;

			return $cart_item_meta;

		}

		function mvv_tsr_get_item_data($item_data, $cart_data) {

			if ( isset( $cart_data['mvv_tsr'] ) ) {
				foreach( $cart_data['mvv_tsr'] as $data ) {
					$name = "Arrival Date";

					if ( isset($data['arrival_date'] ) && $data['arrival_date'] != "" ) {
						$item_data[] = array(
							'name' => $name,
							'display' => $data['arrival_date']
							);
					}
				}
			}

			return $item_data;
		}

		function mvv_tsr_checkout_fields($fields) {
			global $woocommerce;
			foreach( $woocommerce->cart->get_cart() as $cart_item_key => $values ){
				if ( $values['mvv_tsr'][0]['arrival_date'] ) {

					$arrival_date = $values['mvv_tsr'][0]['arrival_date'];
					$fields['billing']['service_date']['default'] = $arrival_date;

				}
				
			}

			return $fields;
		}

		function mvv_tsr_date_validation($posted) {

			$checkout_arrival_date = $posted['service_date'];
			global $woocommerce;
			foreach( $woocommerce->cart->get_cart() as $cart_item_key => $values ){
				$arrival_date = $values['mvv_tsr'][0]['arrival_date'];
			}

			if ($arrival_date !== $checkout_arrival_date) {
				wc_add_notice( __( "Different date", 'woocommerce' ), 'error' );
			}
		}

		public function mvv_tsr_available_timeslots() {
			
			
			$posted = array();

			parse_str( $_POST['form'], $posted);

			
			if ( empty( $posted['add-to-cart'] ) ) {
				return false;
			}


			$post_id = $posted['add-to-cart'];
			$datepicker_1 = $posted['datepicker-1'];	

			global $wpdb;

			$orders = $wpdb->get_results( "
			SELECT ID FROM {$wpdb->posts} AS posts
			WHERE posts.post_type = 'shop_order'
			");	

			foreach($orders as $key => $value) {
			
				$order_id = $value->ID;
				$arrival_date = get_post_meta($order_id, 'service_date', true);
				$departure_date = get_post_meta($order_id, 'departure_date', true);
			}

			die('');
		}
	}

}

$mvv_tsr = new mvv_tsr();