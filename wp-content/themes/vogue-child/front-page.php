<?php
/**
 * The front page template file.
 *
 * Front Page Template
 * @package Vogue
 */
get_template_part( 'header_front_page' ); ?>
<div style="margin: 50px 0px 100px;">

<?php get_template_part( '/templates/titlebar' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'templates/contents/content', 'page' ); ?>

<?php endwhile; // end of the loop. ?>

</div>

</div><?php get_template_part( 'footer_front_page' );
