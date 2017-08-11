<?php
/**
 * Template Name: Luray Franchise Page
 *
 * Luray Franchise Page Template
* @package Vogue
 */
 get_template_part( 'header_franchise_page' ); ?>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" method="post" class="cabin-form" >
<div class="form_description">
<h2><img class="aligncenter size-full wp-image-24" src="/wp-content/uploads/2016/10/myvacayvalet-trans-logo-nosub280.png" alt="" width="280" height="146" /></h2>
<h2 style="text-align: center;">Congratulations!</h2>
<p style="text-align: center;">You are staying in an area we service!</p>
<p style="text-align: center;">Select The Cabin You Will Be Staying At</p>

</div>
<ul style="text-align: center;">
 	<li id="li_1" style="list-style-type: none;">
<div><select id="cabinSelection" class="" name="cabinSelection">
<option selected="selected" value="">Click to Choose Cabin</option>
<option value="1">Cardinal Cottage – Pre-Arrival, 327 Cardinal Drive, Luray, VA 22835</option>
<option value="2">Garnerland – Pre-Arrival, 332 Whippoorwill Lane, Luray, VA 22835</option>
<option value="3">Helms Mountain Hideaway – Pre-Arrival, 896 Helms Road, Stanley, VA 22851</option>
<option value="4">Jewell Hollow Homestead – Pre-Arrival, 1918 Jewell Hollow Road, Luray, VA 22835</option>
<option value="5">Open Arms Hostel – Pre-Arrival, 1260 East Main Street, Luray, VA 22835</option>
<option value="6">Turtle Rock Cabin – 5 and 01 Turtle Rock Lane, Rileyville, VA 22650</option>
<option value="7">Three Sister's View Cabin - 3095 Lee Highway, U.S. 211, Luray, VA 22835</option>
<option value="8">Valley View Cabin - 3095 Lee Highway, U.S. 211, Luray, VA 22835</option>
<option value="9">Angler's Corner - 120 South Page Valley Road, Luray, VA 22835</option>
<option value="10">Angler's Nest - 121 South Page Valley Road, Luray, VA 22835</option>
<option value="11">Angler's Getaway - 136 South Page Valley Road, Luray, VA 22835</option>
<option value="12">Angler's Addiction - 137 South Page Valley Road, Luray, VA 22835</option>
<option value="13">Black Bear Lodge - 140 Pot Pie Lane, Stanley, VA 22851</option>
<option value="14">White Pine Lodge - 180 Pot Pie Lane, Stanley, VA 22851</option>
<option value="15">Barred Owl Lodge - 220 Pot Pie Lane, Stanley, VA 22851</option>
<option value="16">The Barn at Evermore - 170 Kibler Drive, Luray, VA 22835</option>
</select></div></li>
    <li class="buttons" style="list-style-type: none;">
    <input type="hidden" name="action" value="luray_rental" />
    <input type="submit" class="button_text" value="On To Services" /></li>
</ul>
</form>
<p style="text-align: center;">Don't See your cabin listed here? <a href="/luray/cabin-suggestion/">CLICK HERE to make a suggestion</a></p>
&nbsp;
<p style="text-align: center;"><a href="/">Return to MyVacayValet.com</a></p>