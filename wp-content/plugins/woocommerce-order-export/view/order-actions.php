<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include_once $WC_Order_Export->path_plugin . '/classes/class-wc-table-order-actions.php';

$table = new WC_Table_Order_Actions( array( 'tab' => $tab, 'items' => $WC_Order_Export->settings_name_actions, 'plugin' => $WC_Order_Export ) );
?>
<!-- <div class="tabs-content"><a href="http://algolplus.com/plugins/downloads/woocommerce-order-export/" target=_blank><?php _e( 'Buy pro version', 'woocommerce-order-export' ) ?></a><?php _e( ' to get access to order actions', 'woocommerce-order-export' ) ?></div> -->
<div class="tabs-content">
	<?php
	$table->output();
	?>
</div>


<script>
	jQuery( document ).ready( function( $ ) {
		$('[data-action=add-order-action]').click(function () {
			document.location = '<?php echo admin_url( "admin.php?page=wc-order-export&tab={$tab}&wc_oe=add_action" ) ?>';
		});
		$('[data-action=edit-order-action]').click(function () {
			var id = $(this).attr('data-id');
			document.location = '<?php echo admin_url( "admin.php?page=wc-order-export&tab={$tab}&wc_oe=edit_action&action_id=" ) ?>' + id;
		});
		$('[data-action=clone-order-action]').click(function () {
			var id = $(this).attr('data-id');
			document.location = '<?php echo admin_url( "admin.php?page=wc-order-export&tab={$tab}&wc_oe=clone&action_id=" ) ?>' + id;
		});
		$('[data-action=delete-order-action]').click(function () {
			var id = $(this).attr('data-id');
			var f = confirm('Are you sure you want to DELETE this action?');
			if (f) {
				document.location = '<?php echo admin_url( "admin.php?page=wc-order-export&tab={$tab}&wc_oe=delete&action_id=" ) ?>' + id;
			}
		});
		$('[data-action=change-order-action-status]').change(function() {
			var id = $( this ).attr( 'data-id' );
			var checked = $( this ).is( ':checked' ) ? 1 : 0;
			document.location = '<?php echo admin_url( "admin.php?page=wc-order-export&tab={$tab}&wc_oe=change_status&action_id=" ) ?>' + id + '&status=' + checked;
		});
	} )
</script>