<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "39d8de0e1f09c5120dcf53c893acc1dd7f39cee6f4"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/my-vacay-valet-timeslot-restriction/my-vacay-valet-timeslot-restriction.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/my-vacay-valet-timeslot-restriction/my-vacay-valet-timeslot-restriction_2017-03-13-18.php") )  ) ){
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

			add_action('woocommerce_before_variations_form', array(&$this, 'mvv_tsr_datepicker_product_page') );

			add_filter('woocommerce_add_cart_item_data', array(&$this, 'mvv_tsr_add_date_cart'), 20, 2 );
			add_filter( 'woocommerce_get_item_data', array( &$this, 'mvv_tsr_get_item_data' ), 25, 2 );

			add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'mvv_tsr_order_item_meta' ), 10, 2 );

			add_filter( 'woocommerce_checkout_fields', array(&$this, 'mvv_tsr_checkout_fields'), 10, 1 );

			add_action( 'woocommerce_after_checkout_validation', array(&$this, 'mvv_tsr_date_validation'), 10, 1 );

			add_action( 'wp_ajax_mvv_available_timeslots_arrival', array(&$this, 'mvv_tsr_available_timeslots_arrival') );

			add_action( 'wp_ajax_nopriv_mvv_available_timeslots_arrival', array(&$this, 'mvv_tsr_available_timeslots_arrival') );

			add_action( 'wp_ajax_mvv_available_timeslots_departure', array(&$this, 'mvv_tsr_available_timeslots_departure') );

			add_action( 'wp_ajax_nopriv_mvv_available_timeslots_departure', array(&$this, 'mvv_tsr_available_timeslots_departure') );

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
			global $post;
			echo '<table class="variations" cellspacing="0"><tbody>';

			if (get_post_meta($post->ID, 'arrival_timeslot', true) === 'yes') {
				echo '<tr>
						<td class="label" style="width: 181px;"><label for"datepicker-1">Arrival Date</label></td> 
						<td><input type ="text" id ="datepicker-1" name="datepicker-1" class="datepicker-1" style="width: 173px;"></td>
						</tr>';
			}
			if (get_post_meta($post->ID, 'departure_timeslot', true) === 'yes') {
				echo '<tr>
						<td class="label" style="width: 181px;"><label for"datepicker-2">Departure Date</label></td> 
						<td><input type ="text" id ="datepicker-2" name="datepicker-2" class="datepicker-2" style="width: 173px;"></td>
						</tr>';
			}

			
			echo '</tbody></table>';

		}

		function mvv_tsr_add_date_cart($cart_item_meta, $product_id){

			$cart_arr = array();
			

			if ( isset( $_POST[ 'datepicker-1' ] ) ) {
                $cart_arr[ 'arrival_date' ] = $_POST[ 'datepicker-1' ];
            }
            if ( isset( $_POST[ 'datepicker-2' ] ) ) {
                $cart_arr[ 'departure_date' ] = $_POST[ 'datepicker-2' ];
            }
            if ( isset( $_POST[ 'attribute_arrival-time-slot' ] ) ) {
                $cart_arr[ 'arrival_timeslot' ] = $_POST[ 'attribute_arrival-time-slot' ];
            }
            if ( isset( $_POST[ 'attribute_departure-time-slot' ] ) ) {
                $cart_arr[ 'departure_timeslot' ] = $_POST[ 'attribute_departure-time-slot' ];
            }
            $cart_item_meta[ 'mvv_tsr' ][] = $cart_arr;

			return $cart_item_meta;

		}

		function mvv_tsr_get_item_data($item_data, $cart_data) {

			if ( isset( $cart_data['mvv_tsr'] ) ) {
				foreach( $cart_data['mvv_tsr'] as $data ) {
					

					if ( isset($data['arrival_date'] ) && $data['arrival_date'] != "" ) {
						$item_data[] = array(
							'name' => "Arrival Date",
							'display' => $data['arrival_date']
							);
					}
					if ( isset($data['departure_date'] ) && $data['departure_date'] != "" ) {
						$item_data[] = array(
							'name' => "Departure Date",
							'display' => $data['departure_date']
							);
					}
					if ( isset($data['arrival_timeslot'] ) && $data['arrival_timeslot'] != "" ) {
						$item_data[] = array(
							'name' => "Arrival/Service Timeslot",
							'display' => $data['arrival_timeslot']
							);
					}
					if ( isset($data['departure_timeslot'] ) && $data['departure_timeslot'] != "" ) {
						$item_data[] = array(
							'name' => "Departure Timeslot",
							'display' => $data['departure_timeslot']
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
				if ( $values['mvv_tsr'][0]['departure_date'] ) {

					$departure_date = $values['mvv_tsr'][0]['departure_date'];
					$fields['billing']['departure_date']['default'] = $departure_date;

				}
				if ( $values['mvv_tsr'][0]['arrival_timeslot'] ) {

					$arrival_timeslot = $values['mvv_tsr'][0]['arrival_timeslot'];
					$fields['billing']['service_timeslot']['default'] = $arrival_timeslot;

				}
				if ( $values['mvv_tsr'][0]['departure_timeslot'] ) {

					$departure_timeslot = $values['mvv_tsr'][0]['departure_timeslot'];
					$fields['billing']['departure_timeslot']['default'] = $departure_timeslot;

				}
				
			}

			return $fields;
		}

		function mvv_tsr_date_validation($posted) {

			$checkout_arrival_date = $posted['service_date'];
			$checkout_departure_date = $posted['departure_date'];
			global $woocommerce;
			foreach( $woocommerce->cart->get_cart() as $cart_item_key => $values ){
				$arrival_date = $values['mvv_tsr'][0]['arrival_date'];
				$departure_date = $values['mvv_tsr'][0]['departure_date'];
			}

			if ($arrival_date && $arrival_date !== $checkout_arrival_date) {
				wc_add_notice( __( "Different Arrival/Service date", 'woocommerce' ), 'error' );
			}
			if ($departure_date && $departure_date !== $checkout_departure_date) {
				wc_add_notice( __( "Different Departure date", 'woocommerce' ), 'error' );
			}
		}

		public function mvv_tsr_available_timeslots_arrival() {
				
			$posted = array();
			$excluded = array();

			parse_str( $_POST['form'], $posted);
			
			if ( empty( $posted['add-to-cart'] ) ) {
				return false;
			}


			$post_id = $posted['add-to-cart'];
			$arrival_query = $posted['datepicker-1'];
			global $wpdb;
			$orders = $wpdb->get_results( "
			SELECT ID FROM {$wpdb->posts} AS posts
			WHERE posts.post_type = 'shop_order'
			");	

			foreach($orders as $key => $value) {
			
				$order_id = $value->ID;
				$arrival_date = get_post_meta($order_id, 'service_date', true);

				if ($arrival_date && $arrival_date === $arrival_query){
					$excluded[] = get_post_meta($order_id, 'service_timeslot', true);
				}
			}

			die( json_encode($excluded));
		}

		public function mvv_tsr_available_timeslots_departure() {
				
			$posted = array();
			$excluded = array();

			parse_str( $_POST['form'], $posted);
			
			if ( empty( $posted['add-to-cart'] ) ) {
				return false;
			}


			$post_id = $posted['add-to-cart'];
			$departure_query = $posted['datepicker-2'];
			global $wpdb;
			$orders = $wpdb->get_results( "
			SELECT ID FROM {$wpdb->posts} AS posts
			WHERE posts.post_type = 'shop_order'
			");	

			foreach($orders as $key => $value) {
			
				$order_id = $value->ID;
				$departure_date = get_post_meta($order_id, 'departure_date', true);

				if ($departure_date && $departure_date === $departure_query){
					$excluded[] = get_post_meta($order_id, 'departure_timeslot', true);
				}
			}

			die( json_encode($excluded));
		}
	}

}

$mvv_tsr = new mvv_tsr();