<?php
/**
 * PDF invoice footer template that will be visible on every page.
 *
 * This template can be overridden by copying it to youruploadsfolder/mvv-pick-up-slip/templates/invoice/simple/yourtemplatename/footer.php.
 *
 * HOWEVER, on occasion WooCommerce PDF Invoices will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  Bas Elbers
 * @package WooCommerce_PDF_Invoices/Templates
 * @version 0.0.1
 */

?>

<table class="footer" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php echo nl2br( MVVPS()->templater()->get_option( 'mvvps_left_footer_column' ) ); ?>
		</td>
		<td width="20%">
			<?php printf( __( '%1$s of %2$s', 'mvv-pick-up-slip' ), '{PAGENO}', '{nbpg}' ); ?>
		</td>
	</tr>
</table>
