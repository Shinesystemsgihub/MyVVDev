<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d10beb565c86"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/mvv-pick-up-slip/includes/templates/invoice/simple/minimal/header.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/mvv-pick-up-slip/includes/templates/invoice/simple/minimal/header_2017-05-16-22.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * PDF invoice header template that will be visible on every page.
 *
 * This template can be overridden by copying it to youruploadsfolder/mvv-pick-up-slip/templates/invoice/simple/yourtemplatename/header.php.
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

$templater       = MVVPS()->templater();
$order           = $templater->order;
$invoice         = $templater->invoice;
$payment_gateway = wc_get_payment_gateway_by_order( $order );
?>

<table cellpadding="0" cellspacing="0">
	<tr class="top">
		<td>
			<?php
			if ( $templater->get_logo_url() ) {
				printf( '<img src="var:company_logo" style="max-height:100px;"/>' );
			} else {
				printf( '<h2>%s</h2>', esc_html( $templater->get_option( 'mvvps_company_name' ) ) );
			}
			?>
		</td>

		<td>
			<?php
			printf( __( 'Invoice #: %s', 'mvv-pick-up-slip' ), $invoice->get_formatted_number() );
			printf( '<br />' );
			printf( __( 'Invoice Date: %s', 'mvv-pick-up-slip' ), $invoice->get_formatted_invoice_date() );
			printf( '<br />' );
			printf( __( 'Order Date: %s', 'mvv-pick-up-slip' ), $invoice->get_formatted_order_date() );
			printf( '<br />' );
			printf( __( 'Order Number: %s', 'mvv-pick-up-slip' ), $order->get_order_number() );

			if ( $payment_gateway ) {
				printf( '<br />' );
				printf( __( 'Payment Method: %s', 'mvv-pick-up-slip' ), $payment_gateway->get_title() );

				// Get PO Number from 'WooCommerce Purchase Order Gateway' plugin.
				if ( 'woocommerce_gateway_purchase_order' === $payment_gateway->get_method_title() ) {
					$po_number = $templater->get_meta( '_po_number' );
					if ( $po_number ) {
						printf( '<br />' );
						printf( __( 'Purchase Order Number: %s', 'mvv-pick-up-slip' ), $po_number );
					}
				}
			}

			// Get VAT Number from 'WooCommerce EU VAT Number' plugin.
			$vat_number = $templater->get_meta( '_vat_number' );
			if ( $vat_number ) {
				printf( '<br />' );
				printf( __( 'VAT Number: %s', 'mvv-pick-up-slip' ), $vat_number );
			}
			?>
		</td>
	</tr>
</table>
