<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "39d8de0e1f09c5120dcf53c893acc1dd5fcb01a78f"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-new-form.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-new-form_2017-04-05-14.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


function mvv_tsr_save_button( $post_id ) {
	static $button = '';

	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'valet_manager-new_' . $post_id );

	$onclick = sprintf(
		"this.form._wpnonce.value = '%s';"
		. " this.form.action.value = 'save';"
		. " return true;",
		$nonce );

	$button = sprintf(
		'<input type="submit" class="button-primary" name="save" value="%1$s" onclick="%2$s" />',
		esc_attr( 'Save' ),
		$onclick );

	echo $button;
}

$post_id = -1;

?>
<div class="wrap">
	
	<h1><?php echo 'Add New Inventory'; ?></h1>


	<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'valet_manager', false ) ) ); ?>" id="valet_manager-new">
		
		<?php wp_nonce_field( 'valet_manager-new_' . $post_id ); ?>
		<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
		<input type="hidden" id="hiddenaction" name="action" value="save" />

		<label for="startdate">Start Date</label>
		<input type=text id="startdate" name="startdate"/>

		<label for="enddate">End Date</label>
		<input type=text id="enddate" name="enddate"/>

		<label for="monday">Monday</label>
		<input type=number id="monday" name="monday"/>

		<label for="tuesday">Tuesday</label>
		<input type=number id="tuesday" name="tuesday"/>

		<label for="wednesday">Wednesday</label>
		<input type=number id="wednesday" name="wednesday"/>

		<label for="thursday">Thursday</label>
		<input type=number id="thursday" name="thursday"/>

		<label for="friday">Friday</label>
		<input type=number id="friday" name="friday"/>

		<label for="saturday">Saturday</label>
		<input type=number id="saturday" name="saturday"/>

		<label for="sunday">Sunday</label>
		<input type=number id="sunday" name="sunday"/>
	

		<?php mvv_tsr_save_button( $post_id ); ?>

	</form>

</div>
