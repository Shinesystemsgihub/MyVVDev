<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d10beb565c86"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/mvv-pick-up-slip/includes/templates/invoice/simple/minimal/body.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/mvv-pick-up-slip/includes/templates/invoice/simple/minimal/body_2017-05-16-22.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * PDF invoice template body.
 *
 * This template can be overridden by copying it to youruploadsfolder/mvv-pick-up-slip/templates/invoice/simple/yourtemplatename/body.php.
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

$templater                      = MVVPS()->templater();
$order                          = $templater->order;
$invoice                        = $templater->invoice;
$formatted_shipping_address     = $order->get_formatted_shipping_address();
$formatted_billing_address      = $order->get_formatted_billing_address();
$line_items                     = $order->get_items( 'line_item' );
$color                          = $templater->get_option( 'mvvps_color_theme' );
$terms                          = $templater->get_option( 'mvvps_terms' );


?>

<div class="title">
	<div>
		<h2>Pick-up Slip</h2>
	</div>
	<div class="watermark">
		<?php
		if ( $templater->get_option( 'mvvps_show_payment_status' ) && $order->is_paid() ) {
			printf( '<h2 class="rubber-stamp">%s</h2>', __( 'Paid', 'mvv-pick-up-slip' ) );
		}
		?>
	</div>
</div>

<table cellpadding="0" cellspacing="0">
	<tr class="information">
		<td width="50%">
			<?php echo nl2br( $templater->get_option( 'mvvps_company_address' ) ); ?>
		</td>

		<td>
			<?php
			if ( $templater->get_option( 'mvvps_show_ship_to' ) && ! empty( $formatted_shipping_address ) && $formatted_shipping_address !== $formatted_billing_address && ! $templater->has_only_virtual_products( $line_items ) ) {
				printf( '<strong>%s</strong><br />', __( 'Ship to:', 'mvv-pick-up-slip' ) );
				echo $formatted_shipping_address;
			}
			?>
		</td>

		<td>
			<?php echo $formatted_billing_address; ?>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr class="heading" bgcolor="<?php echo $color; ?>;">
			<th>
				<?php _e( 'Product', 'mvv-pick-up-slip' ); ?>
			</th>

			<th>
				<?php _e( 'Qty', 'mvv-pick-up-slip' ); ?>
			</th>

			<?php do_action( 'mvvps_line_item_headers_after_quantity', $invoice ); ?>

			<th>
				<?php _e( 'Price', 'mvv-pick-up-slip' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$willowgrove = '';
	$mainstreet = '';
	$shoppersvalue = '';
	$shop = '';

	foreach ( $line_items as $item_id => $item ) {
		
		$product_id = (int) $item['product_id'];
		$tags = get_the_terms($product_id, 'product_tag');
		$tags_array = [];
		foreach ($tags as $tag) {
			$tags_array[]  = $tag->name;
		}
		if ( in_array('Willow Grove', $tags_array) ) {
			$shop = 'Willow Grove';
		}
		if ( in_array('Main Street', $tags_array) ) {
			$shop ='Main Street';
		}
		if ( in_array('Shoppers Value', $tags_array) ) {
			$shop = 'Shoppers Value';
		}
		if (!in_array('Willow Grove', $tags_array) && !in_array('Main Street', $tags_array) && !in_array('Shoppers Value', $tags_array)) {
			$shop = 'Shoppers Value';
		}
		$tr = '';
		$tr = '<tr class="item"><td width="50%">' . $item['name'] . '</td>' . '<td>' . $item['qty'] . '</td>' . '<td>' . $order->get_formatted_line_subtotal( $item ) . '</td>' . '</tr>';
			
				/*$templater->wc_display_item_meta( $item );
				$templater->wc_display_item_downloads( $item );*/

		switch ($shop) {
			case 'Willow Grove':
				$willowgrove .= $tr;
				break;
			case 'Main Street':
				$mainstreet .= $tr;
				break;
			case 'Shoppers Value':
				$shoppersvalue .= $tr;
				break;
	
		}
	} 
	if ($willowgrove != '') {
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
		echo '<tr class="item"><td width="100%">' .'SHOP: Willow Grove' . '</td></tr>';
		echo $willowgrove;
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
	}
	if ($mainstreet != '') {
		echo '<tr class="item"><td width="100%">' . 'SHOP: Main Street' . '</td></tr>';
		echo  $mainstreet;
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
	}
	if ($shoppersvalue != '') {
		echo '<tr class="item"><td width="100%">' . 'SHOP: Shoppers Value' . '</td></tr>';
		echo $shoppersvalue;
		
	}
	?>

	<tr class="spacer">
		<td></td>
	</tr>

	<?php
	foreach ( $invoice->get_order_item_totals() as $key => $total ) {
		$class = str_replace( '_', '-', $key );
		?>

		<tr class="total">
			<td></td>
			<td class="border <?php echo $class; ?>" colspan="<?php echo $templater->invoice->colspan; ?>"><?php echo $total['label']; ?></td>
			<td class="border <?php echo $class; ?>"><?php echo $total['value']; ?></td>
		</tr>

	<?php } ?>
	</tbody>
</table>

<table class="notes" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php
			// Customer notes.
			if ( $templater->get_option( 'mvvps_show_customer_notes' ) ) {
				// Note added by customer.
				$customer_note = MVVPS_WC_Order_Compatibility::get_customer_note( $order );
				if ( $customer_note ) {
					printf( '<strong>' . __( 'Note from customer: %s', 'mvv-pick-up-slip' ) . '</strong><br />', nl2br( $customer_note ) );
				}

				// Notes added by administrator on 'Edit Order' page.
				foreach ( $order->get_customer_order_notes() as $custom_order_note ) {
					printf( '<strong>' . __( 'Note to customer: %s', 'mvv-pick-up-slip' ) . '</strong><br />', nl2br( $custom_order_note->comment_content ) );
				}
			}
			?>
		</td>
	</tr>

	<tr>
		<td>
			<?php
			// Zero Rated VAT message.
			if ( $templater->get_meta( '_vat_number_is_valid' ) && count( $order->get_tax_totals() ) === 0 ) {
				_e( 'Zero rated for VAT as customer has supplied EU VAT number', 'mvv-pick-up-slip' );
				printf( '<br />' );
			}
			?>
		</td>
	</tr>
</table>

<?php if ( $terms ) { ?>
	<div class="terms">
		<table>
			<tr>
				<td style="border: 1px solid #000;">
					<?php echo nl2br( $terms ); ?>
				</td>
			</tr>
		</table>
	</div>
<?php }