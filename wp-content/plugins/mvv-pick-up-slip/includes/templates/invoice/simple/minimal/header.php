<?php
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
$line_items 	 = $order->get_items( 'line_item' );
$payment_gateway = wc_get_payment_gateway_by_order( $order );

$service_array = [];

foreach ($line_items as $item_id => $item) {
		$product_id = (int) $item['product_id'];
		$cats = get_the_terms($product_id, 'product_cat');
		$cats_array = [];
		foreach ($cats as $cat) {
			if ( $cat->name == "Services") {
				$service_array[] = $item['name'];
				unset($line_items[$item_id]);
			}
		}
	
}
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
			printf( __( '<h5>Invoice #: %s', 'mvv-pick-up-slip' ), $invoice->get_formatted_number() );
			//printf( '<br />' );
			printf( __( '<h5>Invoice Date: %s</h5>', 'mvv-pick-up-slip' ), $invoice->get_formatted_invoice_date() );
			//printf( '<br />' );
			printf( __( '<h5>Order Date: %s</h5>', 'mvv-pick-up-slip' ), $invoice->get_formatted_order_date() );
			//printf( '<br />' );
			printf( __( '<h5>Order Number: %s</h5>', 'mvv-pick-up-slip' ), $order->get_order_number() );
			foreach ($service_array as $key => $value) {
				//printf( '<br />' );
				printf('<h5>Service: %s</h5>', $value);
			}
			if ( $payment_gateway ) {
				//printf( '<br />' );
				printf( __( '<h5>Payment Method: %s</h5>', 'mvv-pick-up-slip' ), $payment_gateway->get_title() );

				// Get PO Number from 'WooCommerce Purchase Order Gateway' plugin.
				if ( 'woocommerce_gateway_purchase_order' === $payment_gateway->get_method_title() ) {
					$po_number = $templater->get_meta( '_po_number' );
					if ( $po_number ) {
						//printf( '<br />' );
						printf( __( '<h5>Purchase Order Number: %s</h5>', 'mvv-pick-up-slip' ), $po_number );
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
