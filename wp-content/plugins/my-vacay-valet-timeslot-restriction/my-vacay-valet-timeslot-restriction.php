<?php

/*
Plugin Name: My Vacay Valet Timeslot Restriction
Description: This plugin is exclusively built for My Vacay Valet.
Version: 1.0
Author: Ashish Jena
Author URI: https://twitter.com/ashishjena94
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( ! class_exists('mvv_tsr') ) {

	class mvv_tsr {

		public function __construct () {

			add_action('wp_enqueue_scripts', array(&$this, 'mvv_tsr_scripts') );

			add_action('woocommerce_before_variations_form', array(&$this, 'mvv_tsr_datepicker_product_page') );

			add_action('woocommerce_before_add_to_cart_button', array(&$this, 'mvv_tsr_end_fieldset_before_button') );

			add_action('woocommerce_after_add_to_cart_button', array(&$this, 'mvv_tsr_end_fieldset_after_button') );

			add_filter('woocommerce_add_cart_item_data', array(&$this, 'mvv_tsr_add_date_cart'), 20, 2 );

			add_filter( 'woocommerce_get_item_data', array( &$this, 'mvv_tsr_get_item_data' ), 25, 2 );

			add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'mvv_tsr_order_item_meta' ), 10, 2 );

			add_filter( 'woocommerce_checkout_fields', array(&$this, 'mvv_tsr_checkout_fields'), 10, 1 );

			add_action( 'woocommerce_after_checkout_validation', array(&$this, 'mvv_tsr_date_validation'), 10, 1 );

			add_action( 'wp_ajax_mvv_available_timeslots_arrival', array(&$this, 'mvv_tsr_available_timeslots_arrival') );

			add_action( 'wp_ajax_nopriv_mvv_available_timeslots_arrival', array(&$this, 'mvv_tsr_available_timeslots_arrival') );

			add_action( 'wp_ajax_mvv_available_timeslots_departure', array(&$this, 'mvv_tsr_available_timeslots_departure') );

			add_action( 'wp_ajax_nopriv_mvv_available_timeslots_departure', array(&$this, 'mvv_tsr_available_timeslots_departure') );

			add_action( 'admin_menu', array(&$this, 'mvv_tsr_valet_manager') );

			

			//register_deactivation_hook( __FILE__, array(&$this, 'mvv_tsr_table_uninstall') );

		}

		function mvv_tsr_scripts() {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css?ver=4.7.2');
			wp_enqueue_style('mvv_tsr_styles', plugin_dir_url( __FILE__ ) . 'css/mvv-tsr.css' );
			wp_enqueue_script('jquery_easing_plugin', plugin_dir_url(__FILE__) . 'js/easing.min.js', 'jquery');
			wp_enqueue_script('mvv_tsr_scripts', plugin_dir_url( __FILE__ ) . 'js/mvv-tsr.js', 'jquery_easing_plugin' );

			$mvv_tsr_params = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				);

			wp_localize_script('mvv_tsr_scripts', 'mvv_tsr_params', $mvv_tsr_params );
		}

		function mvv_tsr_datepicker_product_page() {
			global $post;		
        	
			$skip = get_post_meta($post->ID, 'skip_the_store', true) === "yes" ? true : false ;
			if ($skip) 
			{
			echo '<fieldset>';

			echo '<table class="variations" cellspacing="0"><tbody>';
			echo '<tr>
					<td class="label" style="width: 181px;"><label for="vacation-status">Status</label></td>
					<td class="value">
					<select id="vacation-status" name="vacation-status">
						<option class="attached enabled" value="">Choose an option</option>
						<option class="attached enabled" value="yet">Yet to check-in</option>
						<option class="attached enabled" value="already">Already checked-in</option>
					</select>
					</td>
			</tr>';

			echo '</tbody></table>';
		
			echo '<input type="button" name="next" class="next action-button" value="Next" />';			
			echo '</fieldset>';
			/*
			echo '<fieldset>';
		
			echo '<table class="variations" cellspacing="0"><tbody>';			
			echo '<tr>
					<td class="label" style="width: 181px;"><label for="pre-arrival">Pre-arrival Preference</label></td>
					<td class="value">
					<select id="pre-arrival" name="pre-arrival">
						<option class="attached enabled" value="">Choose an option</option>
						<option class="attached enabled" value="yes-pre-arrival">I want my order delivered before I arrive</option>
						<option class="attached enabled" value="no-pre-arrival">I want my order delivered when I arrive</option>
						<option class="attached enabled" value="NA-pre-arrival" style="display:none;">Not Applicable</option>
					</select>
					</td>
			</tr>';


			echo '</tbody></table>';
		
			echo '<input type="button" name="previous" class="previous action-button" value="Previous" />';
			echo '<input type="button" name="next" class="next action-button" value="Next" />';			
			echo '</fieldset>';
			*/
			echo '<fieldset>';
		}
			echo '<table class="variations" cellspacing="0"><tbody>';

			if (get_post_meta($post->ID, 'arrival_timeslot', true) === 'yes') {
				echo '<tr>
						<td class="label" style="width: 181px;"><label for="datepicker-1">Arrival Date</label></td> 
						<td><input type ="text" id ="datepicker-1" name="datepicker-1" class="datepicker-1" style="width: 173px;"></td>
						</tr>';
			}
			if (get_post_meta($post->ID, 'departure_timeslot', true) === 'yes') {
				echo '<tr>
						<td class="label" style="width: 181px;"><label for="datepicker-2">Departure Date</label></td> 
						<td><input type ="text" id ="datepicker-2" name="datepicker-2" class="datepicker-2" style="width: 173px;"></td>
						</tr>';
			}

			
			echo '</tbody></table>';
			if ($skip) {
			echo '<input type="button" name="previous" class="previous action-button" value="Previous" />';
			echo '<input type="button" name="next" class="next action-button" value="Next" />';
			echo '</fieldset>';
			echo '<fieldset>';
		}
		}

		function mvv_tsr_end_fieldset_before_button(){
			global $post;
			$skip = get_post_meta($post->ID, 'skip_the_store', true) === "yes" ? true : false ;
			if ($skip) {
				echo '<input type="button" name="previous" class="previous action-button" value="Previous" />';
			}
		}

		function mvv_tsr_end_fieldset_after_button(){
			global $post;
			$skip = get_post_meta($post->ID, 'skip_the_store', true) === "yes" ? true : false ;
			if ($skip) {
				echo '</fieldset>';
			}
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
            if ( isset( $_POST[ 'vacation-status' ] ) ) {
                $cart_arr[ 'vacation-status' ] = $_POST[ 'vacation-status' ];
            }
            /*
            if ( isset( $_POST[ 'pre-arrival' ] ) ) {
                $cart_arr[ 'pre-arrival' ] = $_POST[ 'pre-arrival' ];
            }
            */

            $cart_item_meta[ 'mvv_tsr' ][] = $cart_arr;

			return $cart_item_meta;

		}

		function mvv_tsr_get_item_data($item_data, $cart_data) {

			if ( isset( $cart_data['mvv_tsr'] ) ) {
				foreach( $cart_data['mvv_tsr'] as $data ) {
					
					if(isset($data['vacation-status'] ) && $data['vacation-status'] != ""){
						$item_data[] = array(
							'name' => "Vacation Status",
							'display' => $data['vacation-status'] === "yet" ? "Yet to check-In" : "Already checked-In"
							);
					}
					/*
					if(isset($data['pre-arrival'] ) && $data['pre-arrival'] != "") {
						$item_data[] =array(
							'name' => "Pre-arrival Preference",
							'display' => $data['pre-arrival'] === "yes-pre-arrival" ? ("Yes") : ($data['pre-arrival'] === "no-pre-arrival" ? "No" : "Not Applicable")
							);
					}
					*/

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
				if ( $values['mvv_tsr'][0]['vacation-status'] ) {

					$status = $values['mvv_tsr'][0]['vacation-status'] === "yet" ? "Yet to check-In" : "Already checked-In";
					$fields['billing']['vacation_status']['default'] = $status;

				}
				/*
				if ( $values['mvv_tsr'][0]['pre-arrival'] ) {

					$pre_arrival = $values['mvv_tsr'][0]['pre-arrival'] === "yes-pre-arrival" ? ("Yes") : ($values['mvv_tsr'][0]['pre-arrival'] === "no-pre-arrival" ? "No" : "Not Applicable");

					$fields['billing']['pre_arrival']['default'] = $pre_arrival;

				}
				*/
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

			$table_name = $wpdb->prefix."mvvtsr";
        	$sql = "SELECT * FROM " . $table_name;
        	$results = $wpdb->get_results( $sql, 'ARRAY_A' );

        	$arrival_date = date('Y-m-d', strtotime($arrival_query));

        	$date_day = date('N', strtotime($arrival_query));

        	$arrival_weekday = '';
        	if ($date_day === '1') $arrival_weekday = "monday";
        	if ($date_day === '2') $arrival_weekday = "tuesday";
        	if ($date_day === '3') $arrival_weekday = "wednesday";
        	if ($date_day === '4') $arrival_weekday = "thursday";
        	if ($date_day === '5') $arrival_weekday = "friday";
        	if ($date_day === '6') $arrival_weekday = "saturday";
        	if ($date_day === '7') $arrival_weekday = "sunday";

        	$maximum_valet = 0;
        	$default_maximum_valet = 0;

        	foreach($results as $result) {
        		if ( ( $result['startdate'] < $arrival_date && $arrival_date < $result['enddate'] ) || ( $result['startdate'] == $arrival_date || $result['enddate'] == $arrival_date ) ){

        			$maximum_valet = (int)$result[$arrival_weekday];

        		}
        		if( $result['startdate'] === '0' || $result['startdate'] === 0 ) {
        			$default_maximum_valet = (int)$result[$arrival_weekday];
        		}
        	}

        	if ($maximum_valet === 0) $maximum_valet = $default_maximum_valet;

			$orders = $wpdb->get_results( "
			SELECT ID FROM {$wpdb->posts} AS posts
			WHERE posts.post_type = 'shop_order'
			");	


			foreach($orders as $key => $value) {
			
				$order_id = $value->ID;
				$arrival_date = get_post_meta($order_id, 'service_date', true);
				$exclude = get_post_meta($order_id, 'exclude_from_timeslot_restriction', true);
				$exclude = ($exclude === "Yes" || $exclude === "yes") ? true : false;

				if ($arrival_date && $arrival_date === $arrival_query && !$exclude){
					$excluded[] = get_post_meta($order_id, 'service_timeslot', true);
				}
			}

			$excluded_count_array = array_count_values($excluded);
			$excluding =array();

			foreach ($excluded_count_array as $key => $value) {
				if ($value >= $maximum_valet)
					$excluding[] = $key;
			}

			die( json_encode($excluding));
			
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

			$table_name = $wpdb->prefix."mvvtsr";
        	$sql = "SELECT * FROM " . $table_name;
        	$results = $wpdb->get_results( $sql, 'ARRAY_A' );

        	$departure_date = date('Y-m-d', strtotime($departure_query));

        	$date_day = date('N', strtotime($departure_query));

        	$departure_weekday = '';
        	if ($date_day === '1') $departure_weekday = "monday";
        	if ($date_day === '2') $departure_weekday = "tuesday";
        	if ($date_day === '3') $departure_weekday = "wednesday";
        	if ($date_day === '4') $departure_weekday = "thursday";
        	if ($date_day === '5') $departure_weekday = "friday";
        	if ($date_day === '6') $departure_weekday = "saturday";
        	if ($date_day === '7') $departure_weekday = "sunday";

        	$maximum_valet = 0;
        	$default_maximum_valet = 0;

        	foreach($results as $result) {
        		if ( ( $result['startdate'] < $departure_date && $departure_date < $result['enddate'] ) || ( $result['startdate'] == $departure_date || $result['enddate'] == $departure_date ) ){

        			$maximum_valet = (int)$result[$departure_weekday];

        		}
        		if( $result['startdate'] === '0' || $result['startdate'] === 0 ) {
        			$default_maximum_valet = (int)$result[$departure_weekday];
        		}
        	}

        	if ($maximum_valet === 0) $maximum_valet = $default_maximum_valet;

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
			
			$excluded_count_array = array_count_values($excluded);
			$excluding =array();

			foreach ($excluded_count_array as $key => $value) {
				if ($value >= $maximum_valet)
					$excluding[] = $key;
			}

			die( json_encode($excluding));
		}

		function mvv_tsr_valet_manager(){
			$view = add_menu_page(
				'Valet Management',
				'Valet Manager',
				'manage_options',
				'valet_manager',
				array(&$this, 'mvv_tsr_valet_manager_cb')
				);

			/* $edit = add_submenu_page(
				'valet_manager',
				'Edit Inventory',
				'Inventories',
				'manage_options',
				'valet_manager',
				array(&$this, 'mvv_tsr_valet_manager_cb')
				);
			*/
			$addnew = add_submenu_page(
				'valet_manager',
				'Add New Inventory',
				'Add New',
				'manage_options',
				'valet_manager-new',
				array(&$this, 'mvv_tsr_valet_manager_new_cb')
				);

			add_action('load-'. $view, array(&$this,'mvv_tsr_load_valet_manager'));

		}

		function mvv_tsr_valet_manager_cb(){
			$action = isset($_REQUEST['action']) ? esc_attr($_REQUEST['action']) : '';
			$nonce = isset($_REQUEST['_wpnonce']) ? esc_attr($_REQUEST['_wpnonce']) : '';

			if ('edit' == $action){
	            if ( ! wp_verify_nonce( $nonce, 'edit' ) ) {
					wp_die( 'Nonce failed' );
				}
				require_once dirname( __FILE__ ) . '/admin/valet-manager-edit-form.php';
				return;
			}
			$list_inventory = new mvv_tsr_valet_management();
			$list_inventory->prepare_items();
			?>

			<div class="wrap">

				<h1><?php 
				echo esc_html('Valet Manager');

				if ( current_user_can('manage_options') ){
					echo ' <a href="' . esc_url( menu_page_url( 'valet_manager-new', false ) ) . '" class="add-new-h2">' . esc_html('Add New') . '</a>';
					}
				?></h1>

				<form id="valet-manager-form" method="get">
            
            		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            
            		<?php $list_inventory->display(); ?>
        		</form>

			</div>

			<?php
		}

		function mvv_tsr_valet_manager_new_cb() {
			require_once dirname( __FILE__ ) . '/admin/valet-manager-new-form.php';
		}

		function mvv_tsr_load_valet_manager(){
			global $plugin_page;
			$action = isset($_REQUEST['action']) ? esc_attr($_REQUEST['action']) : '';
			$nonce = isset($_REQUEST['_wpnonce']) ? esc_attr($_REQUEST['_wpnonce']) : '';
			$post_ID = isset($_REQUEST['post_ID']) ? esc_attr($_REQUEST['post_ID']) : -1;

			if ('edit' == $action) {
	            if (isset($_REQUEST['inventory']) && ! wp_verify_nonce( $nonce, 'edit' ) ) {
					wp_die( 'Nonce FFailed' );
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( '' );
				}
				$startdate = isset( $_POST['startdate'] ) ? $_POST['startdate'] : '';
				$enddate = isset( $_POST['enddate'] ) ? $_POST['enddate'] : '';
				$monday = isset( $_POST['monday'] ) ? $_POST['monday'] : '';
				$tuesday = isset( $_POST['tuesday'] ) ? $_POST['tuesday'] : '';
				$wednesday = isset( $_POST['wednesday'] ) ? $_POST['wednesday'] : '';
				$thursday = isset( $_POST['thursday'] ) ? $_POST['thursday'] : '';
				$friday = isset( $_POST['friday'] ) ? $_POST['friday'] : '';
				$saturday = isset( $_POST['saturday'] ) ? $_POST['saturday'] : '';
				$sunday = isset( $_POST['sunday'] ) ? $_POST['sunday'] : '';

				global $wpdb;				
				$table_name = $wpdb->prefix . "mvvtsr";

				$wpdb->update( $table_name, array( 
					'startdate' => $startdate, 'enddate' => $enddate, 'monday' => $monday,
					'tuesday' => $tuesday, 'wednesday' => $wednesday, 'thursday' => $thursday ,
					'friday' => $friday, 'saturday' => $saturday, 'sunday' => $sunday  ),
					array('ID' => $post_ID) );
				if (!isset($_REQUEST['inventory'])) {
				$arr_params = array( 'action', 'inventory', '_wpnonce', 'post');
		        wp_redirect( esc_url_raw(remove_query_arg($arr_params, menu_page_url( 'valet_manager', false ) ) ) );
				exit;					
				}

			}

			if ('save' == $action) {
	            if ( ! wp_verify_nonce( $nonce, 'valet_manager-new_'. $post_ID ) ) {

					wp_die( 'Nonce ffffailed' );

				}
				
				$id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '-1';

				check_admin_referer( 'valet_manager-new_' . $id );
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( '' );
				}
				$startdate = isset( $_POST['startdate'] ) ? $_POST['startdate'] : '';
				$enddate = isset( $_POST['enddate'] ) ? $_POST['enddate'] : '';
				$monday = isset( $_POST['monday'] ) ? $_POST['monday'] : '';
				$tuesday = isset( $_POST['tuesday'] ) ? $_POST['tuesday'] : '';
				$wednesday = isset( $_POST['wednesday'] ) ? $_POST['wednesday'] : '';
				$thursday = isset( $_POST['thursday'] ) ? $_POST['thursday'] : '';
				$friday = isset( $_POST['friday'] ) ? $_POST['friday'] : '';
				$saturday = isset( $_POST['saturday'] ) ? $_POST['saturday'] : '';
				$sunday = isset( $_POST['sunday'] ) ? $_POST['sunday'] : '';
				
				global $wpdb;				
				$table_name = $wpdb->prefix . "mvvtsr";
				$insert_id = $wpdb->insert_id;
				$insert_id++;

				$wpdb->insert( $table_name, array( 
					'startdate' => $startdate, 'enddate' => $enddate, 'monday' => $monday,
					'tuesday' => $tuesday, 'wednesday' => $wednesday, 'thursday' => $thursday ,
					'friday' => $friday, 'saturday' => $saturday, 'sunday' => $sunday  ) );

				$arr_params = array( 'action', 'inventory', '_wpnonce', 'post');
		        wp_redirect( esc_url_raw(remove_query_arg($arr_params, menu_page_url( 'valet_manager', false ) ) ) );
				exit;
			}
		}



	}

}



class mvv_tsr_valet_management extends WP_List_Table{
	function __construct(){
		parent::__construct( array(
			'singular' => 'inventory',
			'plural' => 'inventories',
			'ajax' => false,
		) );
	}

	public static function delete_inventory( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}mvvtsr",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	function prepare_items(){
		global $wpdb;
		$table_name = $wpdb->prefix."mvvtsr";
        $sql = "SELECT * FROM " . $table_name;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        $data = $result;

		$columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $this->items = $data;

	}

	function get_bulk_actions() {
		
		return array();
		
	}

    function process_bulk_action() {
        
        if( 'delete'===$this->current_action() ) {
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            $action = esc_attr($_REQUEST['action'] );

            if ( ! wp_verify_nonce( $nonce, 'delete' ) ) {

				die( 'Nonce failed' );

			}
			else {
				self::delete_inventory( absint( $_GET['inventory'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
				$arr_params = array( 'action', 'inventory', '_wpnonce');
		        wp_redirect( esc_url_raw(remove_query_arg($arr_params)) );
				exit;
			}
        }

       
    }


	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item['ID'] );
	}

	function get_columns(){
		$columns = array(
			'cb'        => '<input type="checkbox" />',
            'startdate'     => 'Start Date',
            'enddate'     => 'End Date',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        );
        return $columns;
	}

	function get_sortable_columns(){
		return array();
	}

	function column_default($item, $column_name){
		return $item[$column_name];
	}

	function column_startdate($item){
		$delete_nonce = wp_create_nonce( 'delete' );
		$edit_nonce = wp_create_nonce( 'edit' );

		$actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&inventory=%s&_wpnonce=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID'],$edit_nonce),
            'delete'    => sprintf('<a href="?page=%s&action=%s&inventory=%s&_wpnonce=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID'], $delete_nonce),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['startdate'],
            /*$2%s*/ //$item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
	}

}

$mvv_tsr = new mvv_tsr();

register_activation_hook( __FILE__, 'mvv_tsr_table_install' );

		function mvv_tsr_table_install(){

			global $wpdb;

			$table_name = $wpdb->prefix.'mvvtsr';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = 
			"CREATE TABLE $table_name (
	ID int(11) NOT NULL AUTO_INCREMENT, 
	startdate VARCHAR(30) NOT NULL, 
	enddate VARCHAR(30) NULL, 
	monday int(11) NULL, 
	tuesday int(11) NULL, 
	wednesday int(11) NULL, 
	thursday int(11) NULL, 
	friday int(11) NULL, 
	saturday int(11) NULL, 
	sunday int(11) NULL, 
	PRIMARY KEY (ID)
);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}