<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d11627af30c5"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-edit-form.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-edit-form_2017-04-21-17.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php 

if (! defined('ABSPATH')) {
	die('-1');
}

function mvv_tsr_edit_button($post_id){
	static $button = '';
	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'valet_manager-edit_' . $post_id );

	$onclick = sprintf(
		"this.form._wpnonce.value = '%s';"
		. " this.form.action.value = 'edit';"
		. " return true;",
		$nonce );

	$button = sprintf(
		'<input type="submit" class="button-primary" name="save" value="%1$s" onclick="%2$s" style="float:right; margin:20px;"/>',
		esc_attr( 'Edit' ),
		$onclick );

	echo $button;

}
$post_id = isset($_REQUEST['inventory']) ? esc_attr($_REQUEST['inventory']) : -1;
?>

<div class="wrap">
	
	<h1><?php echo 'Add New Inventory'; ?></h1>



	<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'valet_manager', false ) ) ); ?>" id="valet_manager-new">
		
		<?php wp_nonce_field( 'valet_manager-edit_' . $post_id ); ?>

		<?php 
		$ID  = (int) $post_id;
		global $wpdb;
		$table_name = $wpdb->prefix.'mvv_tsr';
		$sql = "SELECT * FROM $table_name WHERE ID = $ID";
		$results = $wpdb->get_results($sql, 'ARRAY_A');

		$results[0]

		?>
		<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
		<input type="hidden" id="hiddenaction" name="action" value="edit" />

		<label for="startdate">Start Date<b>(YYYY-MM-DD)</b></label>
		<input type=text id="startdate" name="startdate" value="<?php echo $results[0]['startdate']; ?>" style="max-width: 90px;"/>

		<label for="enddate">End Date<b>(YYYY-MM-DD)</b></label>
		<input type=text id="enddate" name="enddate" value="<?php echo $results[0]['enddate']; ?>" style="max-width: 90px;"/>
				<br><br>
		
		<style type="text/css">
			.valet-new-form input{
				max-width: 80px;
			}

		</style>
		<?php 
		$results[0]['monday'] =  unserialize($results[0]['monday']);
		$results[0]['tuesday'] =  unserialize($results[0]['tuesday']);
		$results[0]['wednesday'] =  unserialize($results[0]['wednesday']);
		$results[0]['thursday'] =  unserialize($results[0]['thursday']);
		$results[0]['friday'] =  unserialize($results[0]['friday']);
		$results[0]['saturday'] =  unserialize($results[0]['saturday']);
		$results[0]['sunday'] =  unserialize($results[0]['sunday']);
		/*var_dump( $results[0]); */?>
		<table class="wp-list-table widefat fixed striped valet-new-form">
			<thead>
				<tr>
					<th id="timeslot">Timeslot</th>
					<th id="mon">Monday</th>
					<th id="tue">Tuesday</th>
					<th id="wed">Wednesday</th>
					<th id="thu">Thursday</th>
					<th id="fri">Friday</th>
					<th id="sat">Saturday</th>
					<th id="sun">Sunday</th>
				</tr>
			</thead>
			<tbody>
			<?php $timeslots = array('9:00am - 9:30am','9:30am - 10:00am','10:00am - 10:30am','10:30am - 11:00am','11:00am - 11:30am','3:00pm - 4:00pm','3:30pm - 4:30pm','4:00pm - 5:00pm','4:30pm - 5:30pm','5:00pm - 6:00pm',
				'5:30pm - 6:30pm','6:00pm - 7:00pm','6:30pm - 7:30pm','7:00pm - 8:00pm','7:30pm - 8:30pm',
				'8:00pm - 9:00pm'
				); 

				$weekdays = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
				?>

				<?php 
				foreach ($timeslots as $timeslot) {
					echo '<tr>';
					echo '<td>';
					echo $timeslot;
					echo '</td>';

					foreach ($weekdays as $weekday) {
						echo '<td>';
						echo '<input type="number"';
						echo 'name="' . $weekday . '[' . $timeslot . ']" ';
						echo 'value="';
						echo $results[0][$weekday][$timeslot];
						echo '"';
						echo '>';
						echo '</td>';
					}

					echo '</tr>';
				}

				?>

			</tbody>
		</table>
	
		<?php mvv_tsr_edit_button( $post_id ); ?>

	</form>

</div>