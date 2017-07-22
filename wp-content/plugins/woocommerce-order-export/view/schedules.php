<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once $WC_Order_Export->path_plugin . '/classes/class-wc-table-schedules.php';

$t_schedules = new WC_Table_Schedules();
?>
<!-- <div class="tabs-content"><a href="http://algolplus.com/plugins/downloads/woocommerce-order-export/" target=_blank><?php _e( 'Buy pro version', 'woocommerce-order-export' ) ?></a><?php _e( ' to get access to scheduled reports', 'woocommerce-order-export' ) ?></div> -->
<div class="tabs-content">
	<?php
	$t_schedules->output();
	?>
</div>


<script>
	jQuery( document ).ready( function( $ ) {
		$( '#add_schedule' ).click( function() {
			document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=schedules&wc_oe=add_schedule' ) ?>';
		} )

		$( '.btn-trash' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to DELETE this job?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=schedules&wc_oe=delete_schedule&schedule_id=' ) ?>' + id;
			}
		} )
		$( '.btn-export' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			document.location = '<?php echo admin_url( 'admin-ajax.php?action=order_exporter_run&method=run_one_job&schedule=' ) ?>' + id;
		} )
		$( '.btn-edit' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=schedules&wc_oe=edit_schedule&schedule_id=' ) ?>' + id;
		} )
		$( '.btn-clone' ).click( function() {
			var id = $( this ).attr( 'data-id' );
			var f = confirm( '<?php esc_attr_e( 'Are you sure you want to CLONE this job?', 'woocommerce-order-export' ) ?>' )
			if ( f ) {
				document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=schedules&wc_oe=edit_schedule&clone=yes&schedule_id=' ) ?>' + id;
			}	
		} )
		$( '[data-action=change-schedule-status]' ).change( function() {
			var id = $( this ).attr( 'data-id' );
			var checked = $( this ).is( ':checked' ) ? 1 : 0;
			document.location = '<?php echo admin_url( 'admin.php?page=wc-order-export&tab=schedules&wc_oe=change_status_schedule&schedule_id=' ) ?>' + id + '&status=' + checked;
		} )
	} )
</script>