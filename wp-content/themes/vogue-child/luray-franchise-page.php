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
<div>
<select id="rentalSelection" class="" name="rentalSelection">
<option selected="selected" value="">Click to Choose Cabin</option>
<?php
    getFranchiseRentalsAsOptionsList()
?>
</select></div></li>
    <li class="buttons" style="list-style-type: none;">
    <input type="hidden" name="action" value="luray_rental" />
    <input type="submit" class="button_text" value="On To Services" />
</li></ul></form>
<p style="text-align: center;">Don't See your cabin listed here? <a href="/luray/cabin-suggestion/">CLICK HERE to make a suggestion</a></p>
&nbsp;
<p style="text-align: center;"><a href="/">Return to MyVacayValet.com</a></p>