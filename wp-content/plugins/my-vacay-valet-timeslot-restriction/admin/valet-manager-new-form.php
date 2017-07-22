<?php

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


	<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'valet_manager', false ) ) ); ?>" id="valet_manager-new">
		
		<?php wp_nonce_field( 'valet_manager-new_' . $post_id ); ?>
		<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
		<input type="hidden" id="hiddenaction" name="action" value="save" />

		<label for="startdate">Start Date</label>
		<input type=text id="startdate" name="startdate" style="max-width: 90px;"/>

		<label for="enddate">End Date</label>
		<input type=text id="enddate" name="enddate" style="max-width: 90px;"/>

		<label for="monday">Monday</label>
		<input type=number id="monday" name="monday" style="max-width: 60px;"/>

		<label for="tuesday">Tuesday</label>
		<input type=number id="tuesday" name="tuesday" style="max-width: 60px;"/>

		<label for="wednesday">Wednesday</label>
		<input type=number id="wednesday" name="wednesday" style="max-width: 60px;"/>

		<label for="thursday">Thursday</label>
		<input type=number id="thursday" name="thursday" style="max-width: 60px;"/>

		<label for="friday">Friday</label>
		<input type=number id="friday" name="friday" style="max-width: 60px;"/>

		<label for="saturday">Saturday</label>
		<input type=number id="saturday" name="saturday" style="max-width: 60px;"/>

		<label for="sunday">Sunday</label>
		<input type=number id="sunday" name="sunday" style="max-width: 60px;"/>
	

		<?php mvv_tsr_save_button( $post_id ); ?>

	</form>

</div>
