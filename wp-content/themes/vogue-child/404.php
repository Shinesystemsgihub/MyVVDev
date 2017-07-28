<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Vogue
 */

 ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

	<div class="site-container ">

<!--<?php //echo $to_echo; ?>-->

		<div id="primary" class="content-area content-area-full">
			<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				
				<div>
					<i class="fa fa-ban"></i>
				</div>
				
				<header class="page-header">
					<h1 class="page-title"><?php echo wp_kses_post( get_theme_mod( 'vogue-website-error-head', __( 'Oops! <span>404</span>', 'vogue' ) ) ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p>
                        <?php echo wp_kses_post( get_theme_mod( 'vogue-website-error-msg', __( 'It looks like that page does not exist. <br />Return home or try a search', 'vogue' ) ) ); ?>
					</p>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->


		<div class="clearboth"></div>
	
	</div><!-- #content -->
	
	
</div><!-- #page -->

<?php wp_footer(); ?>



<!-- <script type="text/javascript" async="async" defer="defer" data-cfasync="false" src="https://mylivechat.com/chatinline.aspx?hccid=93234800"></script>
 -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41157947-2', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
