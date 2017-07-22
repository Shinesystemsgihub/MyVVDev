<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d10beb565c86"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/mvv-pick-up-slip/includes/class-mvvps-template.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/mvv-pick-up-slip/includes/class-mvvps-template_2017-05-16-19.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * Templater class to populate templates.
 *
 * @author      Bas Elbers
 * @category    Class
 * @package     BE_WooCommerce_PDF_Invoices/Class
 * @version     0.0.1
 */

defined( 'ABSPATH' ) or exit;

/**
 * Class MVVPS_Template.
 */
class MVVPS_Template {

	/** Main instance.
	 *
	 * @var MVVPS_Template The single instance of the class.
	 */
	protected static $_instance = null;

	/**
	 * WooCommerce order.
	 *
	 * @var WC_Order.
	 */
	public $order;

	/**
	 * WooCommerce PDF Invoices invoice.
	 *
	 * @var MVVPS_Invoice.
	 */
	public $invoice;

	/**
	 * WooCommerce PDF Invoices packing slip.
	 *
	 * @var MVVPS_Packing_Slip.
	 */
	public $packing_slip;

	/**
	 * Template directories.
	 *
	 * @var array.
	 */
	private $directories = array();

	/**
	 * String placeholders.
	 *
	 * @var array.
	 */
	private static $placeholders = array( '[payment_method]', '[shipping_method]' );

	/**
	 * Main MVVPS_Template Instance.
	 *
	 * Ensures only one instance of MVVPS_Template is loaded or can be loaded.
	 *
	 * @since 2.7.0
	 * @static
	 * @return MVVPS_Template Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * MVVPS_Template constructor.
	 */
	private function __construct() {
		$upload_dir = wp_upload_dir();
		$this->directories = apply_filters( 'mvvps_template_directories', array(
			$upload_dir['basedir'] . '/mvvps-templates', // Old custom templates directory.
			WPI_TEMPLATES_DIR, // uploads/mvv-pick-up-slip/templates.
			WPI_DIR . '/includes/templates',
		) );
	}

	/**
	 * Get template files.
	 *
	 * @param string $type Document type.
	 *
	 * @return array
	 */
	public function get_template( $type ) {
		$template = array();

		// get template name from template options.
		$name = $this->get_option( 'mvvps_template_name' );
				var_dump('INSIDE TEMP CLASS');
		var_dump($name);

		// first check custom directory, second plugin directory.
		foreach ( $this->directories as $directory ) {
			$template_path = $directory . '/' . $type . '/' . $name;
			if ( ! file_exists( $template_path ) ) {
				continue;
			}

			$files = array_merge( glob( $template_path . '/*.php' ), glob( $template_path . '/*.css' ) );
			foreach ( $files as $full_path ) {
				$file = pathinfo( $full_path );
				$template[ $file['filename'] ] = $full_path;
			}

			break;
		}

		if ( count( $template ) === 0 ) {
		   
			MVVPS()->logger()->error( sprintf( 'PDF generation aborted. Template not found in %1$s:%2$s', __FILE__,  __LINE__ ) );
		}

		return $template;
	}

	/**
	 * Get absolute paths of all invoice/simple templates.
	 *
	 * @return array
	 */
	public function get_templates() {
		$templates = array();

		foreach ( $this->directories as $directory ) {
			$templates = array_merge( $templates, glob( $directory . '/invoice/simple/*', GLOB_ONLYDIR ) );
		}

		return $templates;
	}

	/**
	 * Get template options by key.
	 *
	 * @param string $name the option key.
	 *
	 * @return string
	 */
	public function get_option( $name ) {
		$template_options = get_option( 'mvvps_template_settings' );

		$order_id =  MVVPS_WC_Order_Compatibility::get_id( $this->order );
		$value = apply_filters( $name, $template_options[ $name ], $name, $order_id );

		if ( self::has_placeholder( $value ) ) {
			$value = $this->replace_placeholders( $value );
		}

		return $value;
	}

	/**
	 * Checks if string has placeholders.
	 *
	 * @param string $value Text value.
	 *
	 * @return bool
	 */
	private static function has_placeholder( $value ) {
		foreach ( self::$placeholders as $placeholder ) {
			if ( strpos( $value, $placeholder ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Replace template placeholder within string.
	 *
	 * @param string $value string to format.
	 *
	 * @return string
	 */
	private function replace_placeholders( $value ) {
		$payment_gateway = wc_get_payment_gateway_by_order( $this->order );

		$value = str_replace(
			self::$placeholders,
			array(
				( $payment_gateway ) ? $payment_gateway->get_title() : $value,
				$this->order->get_shipping_method(),
			),
			$value
		);

		return $value;
	}

	/**
	 * Check if order has only virtual products.
	 *
	 * @param array $items WooCommerce products.
	 *
	 * @return bool
	 * @since 2.5.3
	 */
	public function has_only_virtual_products( $items ) {
		foreach ( $items as $item ) {
			$product = $this->order->get_product_from_item( $item );
			if ( ! $product || ! $product->is_virtual() ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Order item meta port.
	 *
	 * @param object $item Order item meta.
	 */
	public function wc_display_item_meta( $item ) {
		// WooCommerce v3.
		if ( function_exists( 'wc_display_item_meta' ) ) {
			wc_display_item_meta( $item );
		} else {
			$this->order->display_item_meta( $item );
		}
	}

	/**
	 * Order item downloads meta.
	 *
	 * @param object $item Order item.
	 */
	public function wc_display_item_downloads( $item ) {
		if ( function_exists( 'wc_display_item_downloads' ) ) {
			// WooCommerce v3.
			wc_display_item_downloads( $item );
		} else {
			$this->order->display_item_downloads( $item );
		}
	}

	/**
	 * Get the company logo URL.
	 *
	 * @return string The actual url from the Media Library.
	 */
	public function get_logo_url() {
		return esc_url_raw( $this->get_option( 'mvvps_company_logo' ) );
	}

	/**
	 * Get custom post meta data.
	 *
	 * @param string $meta_key The post meta key.
	 *
	 * @return string
	 */
	public function get_meta( $meta_key ) {
		$order_id = MVVPS_WC_Order_Compatibility::get_id( $this->order );

		return (string) get_post_meta( $order_id, $meta_key, true );
	}

	/**
	 * Get template directories.
	 *
	 * @return array
	 */
	public function get_directories() {
		return $this->directories;
	}

	/**
	 * Get two letter ISO language code.
	 */
	public function get_two_letter_iso_code() {
		return substr( get_locale(), 0, 2 );
	}

	/**
	 * Set order.
	 *
	 * @param WC_Order $order WooCommerce Order object.
	 */
	public function set_order( $order ) {
		$this->order = $order;
	}

	/**
	 * Set invoice.
	 *
	 * @param MVVPS_Invoice $invoice WooCommerce PDF Invoices invoice object.
	 */
	public function set_invoice( $invoice ) {
		$this->invoice = $invoice;
	}

	/**
	 * Set invoice.
	 *
	 * @param MVVPS_Packing_Slip $packing_slip WooCommerce PDF Invoices invoice object.
	 */
	public function set_packing_slip( $packing_slip ) {
		$this->packing_slip = $packing_slip;
	}
}
