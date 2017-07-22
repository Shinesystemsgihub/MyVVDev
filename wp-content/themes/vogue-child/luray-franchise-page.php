<?php
/**
 * Template Name: Luray Franchise Page
 *
 */
get_header(); ?>
<!--<?php //echo $to_echo; ?>-->

	<div id="primary" class="content-area content-area-full">
		<main id="main" class="site-main" role="main">

			<form id="luraycabinForm" class="cabin-form"  method="post" action="">

				<div class="form_description">
					<h2>Cabin Selection</h2>
					<p>This is the description</p>
				</div>	

				<ul >

					<li id="li_1" >
						<label class="description" for="cabinSelection">Select The Cabin You Will Be Staying At </label>
						<div>
							<select class="" id="cabinSelection" name="cabinSelection"> 
								<option value="" selected="selected"></option>
									<option value="1" >Cardinal Cottage – Pre-Arrival, 327 Cardinal Drive, Luray, VA 22835</option>
									<option value="2" >Garnerland – Pre-Arrival, 332 Whippoorwill Lane, Luray, VA 22835</option>
									<option value="3" >Helms Mountain Hideaway – Pre-Arrival, 896 Helms Road, Stanley, VA 22851</option>
									<option value="4" >Jewell Hollow Homestead – Pre-Arrival, 1918 Jewell Hollow Road, Luray, VA 22835</option>
									<option value="5" >Open Arms Hostel – Pre-Arrival, 1260 East Main Street, Luray, VA 22835</option>
									<option value="6" >Turtle Rock Cabin – 5 and 01 Turtle Rock Lane, Rileyville, VA 22650</option>
									<option value="7" >Three Sister's View Cabin - 3095 Lee Highway, U.S. 211, Luray, VA 22835</option>
									<option value="8" >Valley View Cabin - 3095 Lee Highway, U.S. 211, Luray, VA 22835</option>
									<option value="9" >Angler's Corner - 120 South Page Valley Road, Luray, VA 22835</option>
									<option value="10" >Angler's Nest - 121 South Page Valley Road, Luray, VA 22835</option>
									<option value="11" >Angler's Getaway - 136 South Page Valley Road, Luray, VA 22835</option>
									<option value="12" >Angler's Addiction - 137 South Page Valley Road, Luray, VA 22835</option>
									<option value="13" >Black Bear Lodge - 140 Pot Pie Lane, Stanley, VA 22851</option>
									<option value="14" >White Pine Lodge - 180 Pot Pie Lane, Stanley, VA 22851</option>
									<option value="15" >Barred Owl Lodge - 220 Pot Pie Lane, Stanley, VA 22851</option>
									<option value="16" >The Barn at Evermore - 170 Kibler Drive, Luray, VA 22835</option>

							</select>
						</div> 
					</li>

					<li class="buttons">
					    <input type="hidden" name="form_id" value="luraycabinForm" />
						<input id="saveCabin" class="button_text" type="submit" name="submit" value="On To Services" />
					</li>
				</ul>
			</form>	
									
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'templates/contents/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>