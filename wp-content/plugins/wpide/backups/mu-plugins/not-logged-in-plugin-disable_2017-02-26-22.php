<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "0e19de3afa980d87356ce12451e6b88c14be72348d"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/mu-plugins/not-logged-in-plugin-disable.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/mu-plugins/not-logged-in-plugin-disable_2017-02-26-22.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/*
Plugin Name: Logged In Plugin Disabler
Plugin URI: http://www.your-url.com
Description:  Removes a plugin functionality when a user is logged in
Author: Your Name
Version: 1.0
Author URI: Your URL
*/
 
add_filter( 'option_active_plugins', 'disable_logged_in_plugin' );
 
function disable_logged_in_plugin( $plugins ) {
     
    // The 'option_active_plugins' hook occurs before any user information get generated,
    // so we need to require this file early to be able to check for logged in status
    require (ABSPATH . WPINC . '/pluggable.php');

    $current_user = wp_get_current_user();
    $first_name = $current_user->user_firstname;
    // If we are logged in, and NOT an admin...
    if ( !is_user_logged_in() || ( is_user_logged_in() && $first_name !== 'Kevin' ) ) {
 
        // Use the plugin folder and main file name here.
        //  Bloom is used here as an example
        $key = array_search( 'my-vacay-valet-timeslot-restriction/my-vacay-valet-timeslot-restriction.php' , $plugins );
 
        if ( false !== $key ) {
            // Remove the plugin reference, based on its key
            unset( $plugins[ $key ] );
        }
         
        // You can "deactive" other plugins here as well,
        //  using the code above as a template.
         
    }
    return $plugins;
}