<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "0e19de3afa980d87356ce12451e6b88c3a124b4c3f"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/themes/vogue-child/footer.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/themes/vogue-child/footer_2017-02-15-22.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Vogue
 */
?>
		<div class="clearboth"></div>
	</div><!-- #content -->
	
	<?php if ( get_theme_mod( 'vogue-footer-layout', false ) == 'vogue-footer-layout-standard' ) : ?>
		
		<?php get_template_part( '/templates/footers/footer-standard' ); ?>
		
	<?php else : ?>
		
		<?php get_template_part( '/templates/footers/footer-social' ); ?>
		
	<?php endif; ?>
	
</div><!-- #page -->

<?php 

global $woocommerce;

$content = $woocommerce->cart->get_cart();

foreach($content as $key => $value) {
	if ( is_array($value['variation']) && !empty($value['variation']) && $value['variation']['attribute_arrival-day'] !== NULL) {
		$arrival_day = $value['variation']['attribute_arrival-day'];
		var_dump($arrival_day);
	}
}
?>
<?php wp_footer(); ?>



<script type="text/javascript" async="async" defer="defer" data-cfasync="false" src="https://mylivechat.com/chatinline.aspx?hccid=93234800"></script>

</body>
</html>
