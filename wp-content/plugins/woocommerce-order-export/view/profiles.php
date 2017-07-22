<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once $WC_Order_Export->path_plugin . '/classes/class-wc-table-profiles.php';

$t_p = new WC_Table_Profiles();
?>
<!-- <div class="tabs-content"><a href="http://algolplus.com/plugins/downloads/woocommerce-order-export/" target=_blank><?php _e( 'Buy pro version', 'woocommerce-order-export' ) ?></a><?php _e( ' to get access to profiles', 'woocommerce-order-export' ) ?></div> -->
<div class="tabs-content">
	<?php
	$t_p->output();
	?>
</div>


<script>
	jQuery( document ).ready( function( $ ) {
		$( '#add_profile' ).click( function() {
			document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=add_profile' ) ?>';
		} )

		$( '.btn-trash' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to DELETE this profile?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=delete_profile&profile_id=' ) ?>' + id;
			}
		} )
		$( '.btn-export' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			document.location = '<?php echo admin_url( 'admin-ajax.php?action=order_exporter_run&method=run_one_job&profile=' ) ?>' + id;
		} )
		$( '.btn-edit' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=edit_profile&profile_id=' ) ?>' + id;
		} )
		$( '.btn-clone' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to CLONE this profile?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=edit_profile&clone=yes&profile_id=' ) ?>' + id;
			}	
		} )
		$( '.btn-to-scheduled' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to COPY this profile and MOVE it to "Scheduled Exports"?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=copy_profile_to_scheduled&profile_id=' ) ?>' + id;
			}
		} )
		$( '.btn-to-actions' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to COPY this profile and MOVE it to "Order Change"?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=profiles&wc_oe=copy_profile_to_actions&profile_id=' ) ?>' + id;
			}
		} )
	} )
</script>