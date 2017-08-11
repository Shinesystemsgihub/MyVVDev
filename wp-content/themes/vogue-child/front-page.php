<?php
/**
 * The front page template file.
 *
 * Front Page Template
 * @package Vogue
 */
get_template_part( 'header_front_page' ); ?>
<hr style="height: 5px; font-size: 0; line-height: 0; background-color: #4AAF3E;"> 
<div class="front-page-hero" >
	<div class="front-page-content">
		<h1 ><span style="font-weight: thin;">Groceries And Services<br/>Delivered Straight to Your Vacation</span><br/><br/></h1>  
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="form-horizontal" >
				<fieldset style="border: none;">

					<!-- Text input-->
					<div class="form-group" >
						<label for="zipcode" style="line-height: 18px;">Please enter the zipcode where you will be staying.</label>
						<input id="zipcode" name="zipcode" type="text" placeholder="Vacation Zipcode" pattern="[0-9]{5}" title="Five digit zip code" size="5" class="form-control input-md" required >
					</div>

					<!-- Button -->
					<div class="form-button" >
						<input type="hidden" name="action" value="luray_franchise" />
						<input type="submit" id="zipcodeSubmit" name="zipcodeSubmit" class="btn btn-primary" value="GET STARTED" />
					</div>
	
				</fieldset>
			</form>
	</div>
</div><?php get_template_part( 'footer_front_page' );
