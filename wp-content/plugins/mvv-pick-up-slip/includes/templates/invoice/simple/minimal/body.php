<?php
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
		<tr class="heading" bgcolor="#000000">
			<th>
				<?php _e( 'Product', 'mvv-pick-up-slip' ); ?>
			</th>
			<th>
				<?php _e( 'Description', 'mvv-pick-up-slip' ); ?>
			</th>
			<th>
				<?php _e( 'Brand', 'mvv-pick-up-slip' ); ?>
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
	$willowgrove = [];
	$mainstreet = [];
	$shoppersvalue = [];
	$shop = '';
	$test = [[]];

	foreach ( $line_items as $item_id => $item ) {
		
		$product_id = (int) $item['product_id'];
		$cats = get_the_terms($product_id, 'product_cat');
		$cats_array = [];


		$tags = get_the_terms($product_id, 'product_tag');
		$item_type = '';
		foreach ($tags as $tag) {
			if ($tag->name == "One"){
				$item_type = "One";
			}
			if ($tag->name == "Two"){
				$item_type = "Two";
			}
			if ($tag->name == "Three"){
				$item_type = "Three";
			}
			if ($tag->name == "Freezer"){
				$item_type = "Freezer";
			}
			if ($tag->name == "Perishable"){
				$item_type = "Perishable";
			}
			if ($tag->name == "Refrigerated"){
				$item_type = "Refrigerated";
			}
			if ($tag->name == "Shelf"){
				$item_type = "Shelf";
			}
			if ($tag->name == "Shelf Stable"){
				$item_type = "Shelf Stable";
			}
			if ($tag->name == "Stable"){
				$item_type = "Stable";
			}
		}

		foreach ($cats as $cat) {
			$cats_array[]  = $cat->name;
		}
		if ( in_array('Willow Grove Farm Market', $cats_array) ) {
			$shop = 'Willow Grove Farm Market';
		}
		if ( in_array('Main Street Bakery', $cats_array) ) {
			$shop ='Main Street Bakery';
		}
		if ( in_array('Shoppers Value', $cats_array) ) {
			$shop = 'Shoppers Value';
		}
		if (!in_array('Willow Grove Farm Market', $cats_array) && !in_array('Main Street Bakery', $cats_array) && !in_array('Shoppers Value', $cats_array)) {
			$shop = 'Shoppers Value';
		}
		foreach ($cats_array as $key => $value) {
			if ($value === "All Groceries" || $value == "Willow Grove Farm Market" || $value == "Main Street Bakery"){
				unset($cats_array[$key]);
			}
		}
		foreach ($cats_array as $key => $value) {
			$cat_name = $value ? ' ('. $value. ')' : '';
		}
		
		$post = get_post($product_id);
		$post_content = $post->post_content;
		$post_excerpt = $post->post_excerpt;

		$tr = '';
		$tr = '<tr class="item"><td width="40%">' . $item['name']. $cat_name .'</td>' . '<td>'. $post_content . '</td>'.'<td>'. $post_excerpt .'</td>'. '<td>' . $item['qty'] . '</td>' . '<td>' . $order->get_formatted_line_subtotal( $item ) . '</td>' . '</tr>';
			
				/*$templater->wc_display_item_meta( $item );
				$templater->wc_display_item_downloads( $item );*/

		switch ($shop) {
			case 'Willow Grove Farm Market':
				$willowgrove[$item_type] .= $tr;
				break;
			case 'Main Street Bakery':
				$mainstreet[$item_type] .= $tr;
				break;
			case 'Shoppers Value':
				$shoppersvalue[$item_type] .= $tr;
				break;
	
		}
	} 

	if ($willowgrove != []) {
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
		echo '<tr class="item"><td width="100%">' .'<h4>Willow Grove Farm Market</h4>' . '</td></tr>';
		foreach ($willowgrove as $key => $value) {
			echo '<tr class="item"><td width="100%">' .'<h5>(' . $key . ')</h5>' . '</td></tr>';
			echo $value;
		}
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
	}
	if ($mainstreet != []) {
		echo '<tr class="item"><td width="100%">' . '<h4>Main Street Bakery</h4>' . '</td></tr>';
		foreach ($mainstreet as $key => $value) {
			echo '<tr class="item"><td width="100%">' .'<h5>(' . $key . ')</h5>' . '</td></tr>';
			echo $value;
		}
		echo '	<tr class="spacer">
		<td></td>
	</tr>';
	}
	if ($shoppersvalue != []) {
		echo '<tr class="item"><td width="100%">' . '<h4>Shoppers Value</h4>' . '</td></tr>';
		foreach ($shoppersvalue as $key => $value) {
			echo '<tr class="item"><td width="100%">' .'<h5>(' . $key . ')</h5>' . '</td></tr>';
			echo $value;
		}
		
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
			<td></td>
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