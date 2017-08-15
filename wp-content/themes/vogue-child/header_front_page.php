<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Vogue
 */
global $woocommerce;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site <?php echo sanitize_html_class( get_theme_mod( 'vogue-slider-type' ) ); ?>">
	
	<?php if ( get_theme_mod( 'vogue-header-layout' ) == 'vogue-header-layout-three' ) : ?>
	
		<?php get_template_part( '/templates/header/header-layout-front-page' ); ?>
		
	<?php else : ?>
	
		<?php get_template_part( '/templates/header/header-layout-one' ); ?>
		
	<?php endif; ?>
	
	<hr style="height: 5px; font-size: 0; line-height: 0; background-color: #4AAF3E;"> 
	<div class="front-page-hero" >
		<div class="front-page-content">
			<h1 ><span style="font-weight: thin;">Groceries And Services<br/>Delivered Straight to Your Vacation</span><br/><br/></h1>  
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?> " method="post" class="form-horizontal" >
					<fieldset style="border: none;">

						<!-- Text input-->
						<div class="form-group" >
							<label for="zipcode" style="line-height: 18px;">Please enter the zipcode where you will be staying.</label>
							<input id="zipcode" name="zipcode" type="text" placeholder="Vacation Zipcode" pattern="[0-9]{5}" title="Five digit zip code" size="5" class="form-control input-md" required >
						</div>

						<!-- Button -->
						<div class="form-button" >
							<input type="hidden" name="action" value="franchise" />
							<input type="submit" id="zipcodeSubmit" class="btn btn-primary" value="GET STARTED" />
					</div>
					</fieldset>
				</form>
		</div>
	</div>

	<div class="site-container <?php echo ( ! is_active_sidebar( 'sidebar-1' ) ) ? sanitize_html_class( 'content-no-sidebar' ) : sanitize_html_class( 'content-has-sidebar' ); ?> <?php echo ( get_theme_mod( 'vogue-titlebar-centered' ) ) ? sanitize_html_class( 'title-bar-centered' ) : ''; ?>">
