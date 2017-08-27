<?php
defined( 'ABSPATH' ) or exit;

global $wp;
global $wpdb;

require_once dirname( __FILE__ ) . '/../../../Repository/franchiseRepository.php';
require_once dirname( __FILE__ ) . '/../../../Repository/rentalRepository.php';
require_once dirname( __FILE__ ) . '/../../../Repository/reservationRepository.php';
require_once dirname( __FILE__ ) . '/../../../Repository/userRepository.php';

add_action( 'init', 'startSession', 1 );
add_action( 'wp_logout', 'endSession' );
add_action( 'wp_login', 'endSession' );

function endSession() {
    session_destroy ();
}

function startSession() {
    if ( ! session_id() ) session_start();
}

function getUser() {
    $userRepository = new UserRepository;
    $userId = get_current_user_id();
    if ( $userId < 1 ) return false;

    return $userRepository->findById( $userId );
}

function getUserFranchiseUri() {
    $reservationRepository = new ReservationRepository;
    $reservations = $reservationRepository->getReservationsByUserId( get_current_user_id() );
    $reservation = $reservations ? $reservations[ 0 ] : false; //modify if multiple reservations are allowed
    
    return ( $reservation && isset( $reservation[ 'franchiseUri' ] ) )
        ? $reservation[ 'franchiseUri' ] 
        : false ;
}

function getUserReservations() {
    $user = getUser();

    $reservationRepository = new ReservationRepository;

    $reservations = $user 
        ? $reservationRepository->getReservationsByUserId( $user[ 'userId' ] )
        : [ $reservationRepository->getNewReservation() ];

    return $reservations;
}

function validateZipcode( $zipcode )  {
    $safe_zipcode = intval( $zipcode );
    
    if ( ! $safe_zipcode ) {
        $safe_zipcode = '';
    }

    if ( strlen( $safe_zipcode ) > 5 ) {
        $safe_zipcode = substr( $safe_zipcode, 0, 5 );
    }

    return $safe_zipcode;
}

function recordUnservedZipcode() {
    $safe_zipcode = validateZipcode( $_POST[ 'zipcode' ] );
    
    $franchiseRepository = new FranchiseRepository();
    $franchiseRepository->recordUnservedZipcode( $safe_zipcode );
}

function getFranchiseRentalsAsOptionsList() {
    $rentalRepository = new RentalRepository;
    $reservationRepository = new ReservationRepository;
    $reservations = $reservationRepository->getReservationsByUserId( get_current_user_id() );
    $reservation = $reservations[ 0 ];

    $zipcode = $reservation[ 'zipCode' ];
    if ( strlen( $zipcode ) < 1 ) {
        $zipcode = $_GET ? $_GET['zipcode'] : '';
    }

    $safe_zipcode = validateZipcode( $zipcode );
    
    $rentalsList = $rentalRepository->listByZipcode( $safe_zipcode) ;

    foreach ( $rentalsList as list( $id, $rental ) ) {
        echo '<option value="'.$id.'">'.$rental.'</option>';
    }
}

function updateReservation() {
    $userId = get_current_user_id();
    
    if ( $userId > 0 ) {
        $reservationRepository = new ReservationRepository;
        $reservations = $reservationRepository->getReservationsByUserId( $userId );
        $reservation = $reservations[ 0 ]; //Modify this to pick the desired reservation if multiple are allowed
    
        $_SESSION[ 'reservation' ] = $reservation;
    }
    else {
        if( ! $_SESSION[ 'reservation' ] ) {
            $reservationRepository = new ReservationRepository;
            $_SESSION[ 'reservation' ] = $reservationRepository->getNewReservation();
        }
    }
}

function redirectHome() {
    if ( ! session_id() ) session_start();

    if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) {
        updateReservation();
        $reservation = $_SESSION[ 'reservation' ];

        if ( $reservation[ 'rentalId' ] > 0 ) {
            $uri = 'cart';
        }
        elseif ( $reservation[ 'franchiseId' ] > 0 ) {
            $uri = $reservation[ 'franchiseUri' ];
        }
        else {
            $uri = '';
        }
        $url = esc_url( home_url( '/' ) . $uri );
        wp_safe_redirect( $url );
        exit;
    }
}

add_action( 'init', 'redirectHome', 1 );

function process_franchise() {
    $userId = get_current_user_id();
    $franchiseRepository = new FranchiseRepository;
    $reservationRepository = new ReservationRepository;
    $userRepository = new UserRepository;
    $uri = '';
    $user = getUser();

    $safe_zipcode = $_POST ? validateZipcode( $_POST[ 'zipcode' ] ) : '';

    if( $safe_zipcode ) {
        $franchise = $franchiseRepository->findByZipCode( $safe_zipcode );

        if ( $franchise ) {
            $uri = $franchise[ 'franchiseUri' ];
            if ( $uri ) {
                $reservationRepository->setUserFranchiseId( $franchise[ 'franchiseId' ], $userId );
                $reservationRepository->setUserZipCode( $safe_zipcode, $userId );
            }
        }
        else {
            $uri = "";
            $franchiseRepository->recordUnservedZipcode( $safe_zipcode );
        }
    }

    $home_url = home_url( '/' ) ;
    if ( $uri ) {
        $success = $user ? wp_safe_redirect( esc_url(  $home_url . $uri ) ) 
                         : wp_safe_redirect( esc_url( add_query_arg( 'zipcode', $safe_zipcode, $home_url . $uri ) ) );
        exit;
    }
    else {
        $success = wp_safe_redirect( esc_url( $home_url . 'sorry-we-currently-do-not-service-this-area') );
        exit;
    }
}
add_action( 'admin_post_nopriv_franchise', 'process_franchise' );
add_action( 'admin_post_franchise', 'process_franchise' );

function process_luray_rental() {
    $userId = get_current_user_id();
    if ( $userId > 0 ) {
        $safe_rentalId = intval( $_POST['rentalSelection'] );

        $reservationRepository = new ReservationRepository;
        $reservationRepository->setUserRentalId( $safe_rentalId, $userId );
    }

    $serviceUrl =  esc_url( home_url( '/' ) . 'luray/choose-your-service' );
    $success = wp_safe_redirect( $serviceUrl ) ;
    exit;
}
add_action( 'admin_post_nopriv_luray_rental', 'process_luray_rental' );
add_action( 'admin_post_luray_rental', 'process_luray_rental' );

/**
 * Queue parent style followed by child/customized style
 */
 add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_dequeue_style('vogue-style');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    wp_enqueue_style('child-styles', get_stylesheet_directory_uri() . '/child-styles.css', array('parent-style'));
}, 99);


/*2017 Theme Functions*/

/*Add Menus*/
function register_my_menus() {
  register_nav_menus(
    array(
      'front-page-menu' => __( 'Front Page Header' ),
      'front-page-footer-menu' => __( 'Front Page Footer' )
    )
  );
}
add_action( 'init', 'register_my_menus' );


/*Debug helper function*/
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

/*Dynamic Menu for loggedin vs non logged in users*/
function dynamic_menu() {

    if ( is_front_page() ) {
        echo wp_nav_menu(array( 'theme_location' => 'front-page-menu', 'menu_id' => 'front-page-header' ) ); 
        debug_to_console( "this is using front page header" );
    } elseif (is_account_page() && ! ( is_user_logged_in() ) ) {
            echo wp_nav_menu(array( 'theme_location' => 'front-page-menu', 'menu_id' => 'front-page-header' ) ); 
            debug_to_console( "this is the account page and you are not logged in" );
    } elseif (is_account_page() && ( is_user_logged_in() ) ) {
            echo wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) );
            debug_to_console( "this is the account page and you are actually logged in" );
    } else {
        echo wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) );
         debug_to_console( "this is any other page header" );
    }
}

add_action( 'emp_partial' , 'dynamic_menu' );





/***** Woocommerce custom functions *****/

/**
 * Add notice message in single product
 */
// add_filter('woocommerce_get_price_html', function ($price_html, $product) {
//     if (!$product->is_purchasable() && is_product()) {
//         $price_html .= '<p class="theme_error_messages">' . __('Please add a
// service package that includes grocery delivery in order to add this grocery item to your
// cart.', 'woocommerce') . '</p>';
//     }
//     return $price_html;
// }, 20, 2);

/**
 * @return array
 */
function theme_get_cart_contents()
{
    $cart_contents = array();
    $cart = WC()->session->get('cart', null);
    if (is_null($cart) && ($saved_cart = get_user_meta(get_current_user_id(), '_woocommerce_persistent_cart', true))) {
        $cart = $saved_cart['cart'];
    } elseif (is_null($cart)) {
        $cart = array();
    }
    if (is_array($cart)) {
        //Willow Grove Farm Market (id 53): Unavailable to purchase for Monday & Tuesday Arrival/Service times
        $product_ids_by_53 = get_product_ids_by_category_id(53);
        //Main Street Bakery (id 54): unavailable to purchase for Sunday & Monday Arrival/Service times
        $product_ids_by_54 = get_product_ids_by_category_id(54);
        $product_ids_certain_days = array();

        foreach ($cart as $key => $values) {
            if(in_array($values['product_id'], $product_ids_by_53) && (date('N') == 1 || date('N') == 2)){
                /*var_dump($values['product_id']);*/
                $product_ids_certain_days[] = $values['product_id'];
            }
            if(in_array($values['product_id'], $product_ids_by_54) && (date('N') == 1 || date('N') == 7)){
                /*var_dump('1111');*/
                $product_ids_certain_days[] = $values['product_id'];
            }
            $_product = wc_get_product($values['variation_id'] ? $values['variation_id'] : $values['product_id']);
            if (!empty($_product) && $_product->exists() && $values['quantity'] > 0) {
                if ($_product->is_purchasable()) {
                    $session_data = array_merge($values, array('data' => $_product));
                    $cart_contents[$key] = apply_filters('woocommerce_get_cart_item_from_session', $session_data, $values, $key);
                }
            }
        }
        add_notice_message_product_ids_certain_days($product_ids_certain_days);
    }
    return $cart_contents;
}

// /**
//  * @param null $product_ids_certain_days
//  */
// function add_notice_message_product_ids_certain_days($product_ids_certain_days = null){
//     global $woocommerce;
//     $product_titles = array();
//     if($product_ids_certain_days){
//         foreach($product_ids_certain_days as $cart_product_id){
//             $product = get_product( $cart_product_id );
//             $product_titles[] = $product->post->post_title;
//         }
//     }
//     if(!empty($product_titles)){
//         wc_clear_notices();
//         $text = implode(",<br>",$product_titles).'!';
//         wc_add_notice( sprintf( __( "Please remove the following items from your cart, they are not available on the delivery date you have selected.<br> ".$text) ) ,'error' );
//     }

// }

// add_action('wp_loaded', function () {
//     if (!is_object(WC()->session)) {
//         return;
//     }
//     global $compare_cart_items;
//     foreach (theme_get_cart_contents() as $item) {
//         $compare_cart_items[] = $item['data']->id;
//     }
// }, 20);

// /**
//  * @param $category_id
//  * @return array
//  */
// function get_product_ids_by_category_id($category_id)
// {
//     global $wpdb;
//     $table_name = $wpdb->prefix;
//     $string = "
// SELECT   {$table_name}posts.ID FROM {$table_name}posts
// LEFT JOIN {$table_name}term_relationships
// ON ({$table_name}posts.ID = {$table_name}term_relationships.object_id)
// INNER JOIN {$table_name}postmeta
// ON ( {$table_name}posts.ID = {$table_name}postmeta.post_id ) WHERE 1=1
// AND ( {$table_name}term_relationships.term_taxonomy_id IN ({$category_id}))
// AND (
//   ( {$table_name}postmeta.meta_key = '_visibility' AND {$table_name}postmeta.meta_value IN ('catalog','visible') )
// )
// AND {$table_name}posts.post_type = 'product'
// AND (({$table_name}posts.post_status = 'publish')) GROUP BY {$table_name}posts.ID ORDER BY {$table_name}posts.post_date ASC";

//     $results = $wpdb->get_results($string, ARRAY_A);

//     $array_ids = array();
//     if (!empty($results)) {
//         foreach ($results as $value) {
//             $array_ids[] = $value['ID'];
//         }
//     }
//     return $array_ids;
// }

// add_filter('woocommerce_is_purchasable', function ($is_purchasable, $product) {
//     global $compare_cart_items;
//     global $product_cart_items;
//     // Service Category ID
//     $category_id = 14;
//     $items = is_array($compare_cart_items) ? $compare_cart_items : [];
//     //All services
//     $product_cart_items = get_product_ids_by_category_id($category_id);
//     $intersect = @array_intersect($items, $product_cart_items);
//     $product_unic_ids = explode(',', get_option('services_unique_ids'));
//     $intersect_unic = @array_intersect($items, $product_unic_ids);
//     if (!empty($items)) {
//         if (!empty($intersect)) {
//             if (@in_array($product->get_id(), $product_cart_items)) {
//                 $is_purchasable = in_array($product->get_id(), $items);
//             } else {
//                 if ($intersect_unic) {
//                     $is_purchasable = FALSE;
//                 }
//             }
//         } else {
//             if (in_array($product->get_id(), $product_unic_ids)) {
//                 $is_purchasable = FALSE;
//             }
//         }
//     } elseif (empty(WC()->cart->cart_contents)) {
//         $is_purchasable = in_array($product->get_id(), $product_cart_items);
//     }
//     return $is_purchasable;
// }, 20, 2);

/**
 *  Remove cart items - displays notice telling user to add a grocery service
 */
// add_action('template_redirect', function () {
//     global $product_cart_items;
//     $cart_ids = array();
//     $items = is_array($product_cart_items) ? $product_cart_items : array();
//     foreach (WC()->cart->cart_contents as $prod_in_cart) {
//         // Get the Product ID
//         $cart_ids[] = $prod_in_cart['product_id'];
//     }
//     $intersect = @array_intersect($cart_ids, $items);
//     if (empty($intersect)) {
//         WC()->cart->empty_cart(true);
//         wc_clear_notices();
//         cart_unset_all_notice();
//     }
// });

// function cart_unset_all_notice()
// {
//     $notices = WC()->session->get('wc_notices', array());
//     unset($notices['success'], $notices['error']);
//     wc_add_notice('Please <a href="/index.php#services">CLICK HERE</a> to select a service so you may order groceries.', 'error');
// }







add_action('init', 'mvv_remove_woocommerce_template_loop_product_thumbnail', 10);

function mvv_remove_woocommerce_template_loop_product_thumbnail() {
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
}

add_action('init', 'mvv_add_woocommerce_template_loop_product_thumbnail', 10);

function mvv_add_woocommerce_template_loop_product_thumbnail() {
    add_action('woocommerce_before_shop_loop_item_title', 'mvv_woocommerce_template_loop_product_thumbnail', 10);
}

if ( ! function_exists( 'mvv_woocommerce_template_loop_product_thumbnail' ) ) {

    function mvv_woocommerce_template_loop_product_thumbnail() {
        
        global $post;
        global $product;

        $terms = get_the_terms( $post->ID, 'product_tag' );
        
        $term_array = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $term_array[] = $term->name;
            }
        }

        if( in_array('local', $term_array) ) {
            echo '<div class="local-flag mvv-thumbnail-wrap">';
        }
        else {
            echo '<div class="mvv-thumbnail-wrap">';
        }
        
        $thumbnail_html = woocommerce_get_product_thumbnail();

        if ( ! $product->is_in_stock() ) {

        $value = get_post_meta( $post->ID, 'mvv_outofstock_value', true );
        
        $outofstock_text = "OUT OF STOCK";
        $outofstock_text = apply_filters('mvv_outofstock_text', $outofstock_text);
        //if ( $value === 'grey_out'){
                $thumbnail_html = str_replace('class="', 'class="gray-out ', $thumbnail_html);
                $thumbnail_html = '<div class="outofstock-text"><span class="outofstock-inner">' . $outofstock_text . '</span></div>' . $thumbnail_html;
        //  }
        }

        echo $thumbnail_html;
        
        echo '</div>';
    }
}

add_filter('woocommerce_single_product_image_html','mvv_add_local_flag', 10, 2);

if ( ! function_exists( 'mvv_add_local_flag' ) ) {
    
    function mvv_add_local_flag($html, $postID) {

        $terms = get_the_terms( $postID, 'product_tag' );
        
        $term_array = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $term_array[] = $term->name;
            }
        }

        if( in_array('local', $term_array) ) {
            $html = str_replace( "class=\"woocommerce-main-image", "class=\"local-flag woocommerce-main-image", $html );
        }

        $product = wc_get_product( $postID );

        if ( ! $product->is_in_stock() ) {

        $value = get_post_meta( $postID, 'mvv_outofstock_value', true );
        $outofstock_text = "OUT OF STOCK";
        $outofstock_text = apply_filters('mvv_outofstock_text', $outofstock_text);
        
        if ( $value === 'grey_out' || true){
                $html = str_replace('attachment-shop_single', 'attachment-shop_single gray-out', $html);
                
            }
        $html = str_replace('</a>', '<div class="outofstock-text"><span class="outofstock-inner">'.$outofstock_text.'</span></div></a>', $html);
        }

        return $html;
    }
}

add_action( 'add_meta_boxes', 'mvv_add_outofstock_meta_box' );

function mvv_add_outofstock_meta_box(){
    
    add_meta_box('mvv_outofstock', 'When the Product is Out of Stock', 'mvv_outofstock_callback', 'product', 'side', 'low');
    
}

function mvv_outofstock_callback( $post ){
        
        wp_nonce_field( 'mvv_outofstock_box', 'mvv_outofstock_nonce' );
        $value = get_post_meta( $post->ID, 'mvv_outofstock_value', true );
        
        ?>
        <label for="mvv_outofstock_field"><?php _e( "Choose option:", 'choose_option' ); ?></label>
        <br />  
        <input type="radio" name="outofstock_radio_buttons" value="hide_it" <?php checked( $value, 'hide_it' ); ?> >Hide it<br>
        <input type="radio" name="outofstock_radio_buttons" value="grey_out" <?php checked( $value, 'grey_out' ); ?> >Grey out<br>

        <?php
               
}

add_action( 'save_post', 'mvv_save_outofstock_meta_box' );


function mvv_save_outofstock_meta_box( $post_id ) {

        if ( !isset( $_POST['mvv_outofstock_nonce'] ) ) {
                return;
        }

        if ( !wp_verify_nonce( $_POST['mvv_outofstock_nonce'], 'mvv_outofstock_box' ) ) {
                return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }

        $new_meta_value = ( isset( $_POST['outofstock_radio_buttons'] ) ? sanitize_html_class( $_POST['outofstock_radio_buttons'] ) : '' );

        update_post_meta( $post_id, 'mvv_outofstock_value', $new_meta_value );

}

add_filter('woocommerce_product_is_in_stock', 'mvv_check_local_avail', 99999, 1);

function mvv_check_local_avail($status) {

    if (is_admin()) { return $status; }

    global $product;
    global $woocommerce;
    $content = $woocommerce->cart->get_cart();
    if ( is_object($product)) {
        
        $id = $product->get_id();
        
        foreach($content as $key => $value) {
    
            if ( is_array($value['mvv_tsr']) && !empty($value['mvv_tsr']) && $value['mvv_tsr'][0]['arrival_date'] !== NULL) {
                $date_day = date('N', strtotime($value['mvv_tsr'][0]['arrival_date']));
                $arrival_day = "";
                if ($date_day === '7') $arrival_day = "Sunday";
                if ($date_day === '1') $arrival_day = "Monday";
                if ($date_day === '2') $arrival_day = "Tuesday";
                
                $product_cat = get_the_terms($id, 'product_cat');

                foreach ($product_cat as $key => $value) {

                        $cat_name = $value->name;
    
                        if ($cat_name === "Main Street Bakery") {
                            if ($arrival_day === "Sunday" || $arrival_day === "Monday") {
                                return false;
                            }
                        }
        
                        if ($cat_name === "Willow Grove Farm Market") {
                            if ($arrival_day === "Tuesday" || $arrival_day === "Monday") {
                                return false;
                            }
                        }
                    }
            }
    }   
        }

    return $status;
}

add_filter('mvv_outofstock_text', 'mvv_outofstock_text', 10, 1);

function mvv_outofstock_text($text) {
        global $product;
    global $woocommerce;
    $content = $woocommerce->cart->get_cart();
    
    foreach($content as $key => $value) {
        if ( is_array($value['mvv_tsr']) && !empty($value['mvv_tsr']) && $value['mvv_tsr'][0]['arrival_date'] !== NULL) {

            $date_day = date('N', strtotime($value['mvv_tsr'][0]['arrival_date']));
                $arrival_day = "";
                if ($date_day === '7') $arrival_day = "Sunday";
                if ($date_day === '1') $arrival_day = "Monday";
                if ($date_day === '2') $arrival_day = "Tuesday";
                
            $product_cat = get_the_terms($product->get_id(), 'product_cat');
            foreach ($product_cat as $key => $value) {
                $cat_name = $value->name;
                if ($cat_name === "Main Street Bakery") {
                    if ($arrival_day === "Sunday" || $arrival_day === "Monday") {
                        return "UNAVAILABLE ON SUNDAY AND MONDAY";
                    }
                }
    
                if ($cat_name === "Willow Grove Farm Market") {
                    if ($arrival_day === "Tuesday" || $arrival_day === "Monday") {
                        return "UNAVAILABLE ON MONDAY AND TUESDAY";
                    }
                }
            }
        }
}
    return "OUT OF STOCK";
}
