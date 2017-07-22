<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d11627af30c5"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-new-form.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/my-vacay-valet-timeslot-restriction/admin/valet-manager-new-form_2017-04-21-17.php") )  ) ){
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
		'<input type="submit" class="button-primary" name="save" value="%1$s" onclick="%2$s" style="float:right; margin:20px;"/>',
		esc_attr( 'Save' ),
		$onclick );

	echo $button;
}

$post_id = -1;

?>
<div class="wrap">
	
	<h1><?php echo 'Add New Inventory'; ?></h1>
	<script type="text/javascript">
		function onSubmit(form){
			console.log(form);
		}
	</script>

	<form enctype='application/json' method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'valet_manager', false ) ) ); ?>" id="valet_manager-new">
		
		<?php wp_nonce_field( 'valet_manager-new_' . $post_id ); ?>
		<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
		<input type="hidden" id="hiddenaction" name="action" value="save" />

		<label for="startdate">Start Date</label>
		<input type=text id="startdate" name="startdate" style="max-width: 90px;"/>

		<label for="enddate">End Date</label>
		<input type=text id="enddate" name="enddate" style="max-width: 90px;"/>
		<br><br>
		
		<style type="text/css">
			.valet-new-form input{
				max-width: 80px;
			}

		</style>

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
			<?php $timeslots = array(
				'9:00am - 9:30am',
				'9:30am - 10:00am',
				'10:00am - 10:30am',
				'10:30am - 11:00am',
				'11:00am - 11:30am',
				'3:00pm - 4:00pm',
				'3:30pm - 4:30pm',
				'4:00pm - 5:00pm',
				'4:30pm - 5:30pm',
				'5:00pm - 6:00pm',
				'5:30pm - 6:30pm',
				'6:00pm - 7:00pm',
				'6:30pm - 7:30pm',
				'7:00pm - 8:00pm',
				'7:30pm - 8:30pm',
				'8:00pm - 9:00pm'
				); 

				$weekdays = array(
					'monday',
					'tuesday',
					'wednesday',
					'thursday',
					'friday',
					'saturday',
					'sunday'
					);
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
						echo '>';
						echo '</td>';
					}

					echo '</tr>';
				}

				?>


			</tbody>
		</table>

		<?php mvv_tsr_save_button( $post_id ); ?>

	</form>

</div>
