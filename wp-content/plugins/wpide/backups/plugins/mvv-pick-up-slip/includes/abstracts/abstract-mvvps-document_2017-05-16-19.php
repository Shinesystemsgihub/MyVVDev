<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c0565f1064c8c799650a11f8b77d66d10beb565c86"){
                                        if ( file_put_contents ( "/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/mvv-pick-up-slip/includes/abstracts/abstract-mvvps-document.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/shinesan/private_html/pilot.myvacayvalet.com/wp-content/plugins/wpide/backups/plugins/mvv-pick-up-slip/includes/abstracts/abstract-mvvps-document_2017-05-16-19.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * Document Class for different types of (invoice) documents.
 *
 * @author      Bas Elbers
 * @category    Abstract Class
 * @package     BE_WooCommerce_PDF_Invoices/Abstracts
 * @version     1.0.0
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'MVVPS_Abstract_Document' ) ) {
	/**
	 * Class MVVPS_Abstract_Document.
	 */
	abstract class MVVPS_Abstract_Document {

		/**
		 * Type of document like invoice, packing slip or credit note.
		 *
		 * @var string type of document.
		 */
		public $type;

		/**
		 * WooCommerce Order associated with invoice.
		 *
		 * @var WC_Order
		 */
		public $order;

		/**
		 * Output destination mode of mPDF.
		 *
		 * @var string
		 */
		protected $destination;

		/**
		 * Array containing all template files.
		 *
		 * @var array
		 */
		protected $template = array();

		/**
		 * Full path to document.
		 *
		 * @var string
		 */
		protected $full_path;

		/**
		 * Document filename.
		 *
		 * @var string.
		 */
		protected $filename;

		/**
		 * General options.
		 *
		 * @var array
		 */
		protected $general_options = array();

		/**
		 * Template options.
		 *
		 * @var array
		 */
		protected $template_options = array();

		/**
		 * MVVPS_Abstract_Document constructor.
		 */
		public function __construct() {
			$templater    = MVVPS()->templater();
			$templater->set_order( $this->order );
			$this->template         = $templater->get_template( $this->type );
						var_dump('abstract document COMING');
			
			$this->general_options  = get_option( 'mvvps_general_settings' ); // @todo remove.
			$this->template_options = get_option( 'mvvps_template_settings' ); // @todo remove and use 'templater()'.
		}

		/**
		 * Generate document.
		 *
		 * @param string $destination Destination mode for file.
		 */
		public function generate( $destination = 'F' ) {
			$order_id = MVVPS_WC_Order_Compatibility::get_id( $this->order );
            
			do_action( 'mvvps_before_invoice_content', $order_id );

			// Only use default font with version 2.6.2- because we defining font in template.
			$default_font    = ( version_compare( WPI_VERSION, '2.6.2' ) <= 0 ) ? 'opensans' : '';
			$is_new_template = strpos( strtolower( 'minimal' ), 'minimal' ) !== false;

			$mpdf_params = apply_filters( 'mvvps_mpdf_options', array(
				'mode'              => 'utf-8',
				'format'            => '',
				'default_font_size' => 0,
				'default_font'      => $default_font,
				'margin_left'       => ( $is_new_template ) ? 0 : 14,
				'margin_right'      => ( $is_new_template ) ? 0 : 14,
				'margin_top'        => ( $is_new_template ) ? 0 : 14,
				'margin_bottom'     => 0,
				'margin_header'     => ( $is_new_template ) ? 0 : 14,
				'margin_footer'     => ( $is_new_template ) ? 0 : 6,
				'orientation'       => 'P',
			) );
			/* @var mPDF $mpdf */
			$mpdf        = new mPDF(
				$mpdf_params['mode'],
				$mpdf_params['format'],
				$mpdf_params['default_font_size'],
				$mpdf_params['default_font'],
				$mpdf_params['margin_left'],
				$mpdf_params['margin_right'],
				$mpdf_params['margin_top'],
				$mpdf_params['margin_bottom'],
				$mpdf_params['margin_header'],
				$mpdf_params['margin_footer'],
				$mpdf_params['orientation']
			);

			// add company logo image as a variable.
			$wp_upload_dir = wp_upload_dir();
			$image_url     =  ''; /*$this->template_options['mvvps_company_logo'];header_image();*/
			if ( ! empty( $image_url ) ) {
				// use absolute path due to probability of (local)host misconfiguration.
				// problems with shared hosting when one ip is configured to multiple users/environments.
				$image_path         = str_replace( $wp_upload_dir['baseurl'], $wp_upload_dir['basedir'], $image_url );
				$mpdf->company_logo = file_get_contents( $image_path );
			}

			// Show legacy paid watermark.
			if ( strpos( 'minimal', 'micro' ) !== false && /*$this->template_options['mvvps_show_payment_status'] && */strpos( $this->type, 'invoice' ) !== false && $this->order->is_paid() ) {
				$mpdf->SetWatermarkText( __( 'Paid', 'mvv-pick-up-slip' ) );
				$mpdf->showWatermarkText  = true;
				$mpdf->watermarkTextAlpha = '0.2';
				$mpdf->watermarkImgBehind = false;
			}

			// debug.
			/*
			if ( (bool) $this->general_options['mvvps_mpdf_debug'] ) {
				$mpdf->debug           = true;
				$mpdf->showImageErrors = true;
			}*/

			// Layout.
			$mpdf->setAutoTopMargin    = 'stretch';
			$mpdf->setAutoBottomMargin = 'stretch';
			$mpdf->autoMarginPadding   = ( $is_new_template ) ? 20 : 10;

			// Font.
			$mpdf->autoScriptToLang    = true;
			$mpdf->autoLangToFont      = true;
			$mpdf->baseScript          = 1;
			$mpdf->autoVietnamese      = true;
			$mpdf->autoArabic          = true;
			$mpdf->useSubstitutions    = true;

			// Template.
			$html = $this->get_html();
			
			if ( count( $html ) === 0 ) {
			    
				MVVPS()->logger()->error( sprintf( 'PDF generation aborted. No HTML for PDF in %1$s:%2$s', __FILE__,  __LINE__ ) );
				return;
			}

			if ( ! empty( $html['header'] ) ) {
				$mpdf->SetHTMLHeader( $html['header'] );
			}

			if ( ! empty( $html['footer'] ) ) {
				$mpdf->SetHTMLFooter( $html['footer'] );
			}

			$mpdf = apply_filters( 'mvvps_mpdf', $mpdf, $this );

			$mpdf->WriteHTML( $html['style'] . $html['body'] );

			do_action( 'mvvps_after_invoice_content', $order_id );

			$mpdf = apply_filters( 'mvvps_mpdf_after_write', $mpdf, $this );

			if ( 'F' === $destination ) {
				$name = $this->full_path;
			} else {
				$name = $this->filename;
			}

			$mpdf->Output( $name, $destination );
		}

		/**
		 * Output HTML file to buffer.
		 *
		 * @param string $full_path to html file.
		 *
		 * @return string
		 */
		private function buffer( $full_path ) {
			ob_start();
			require $full_path;
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		/**
		 * Get PDF html.
		 *
		 * @return array
		 */
		private function get_html() {
			$html = array();

			foreach ( $this->template as $section => $full_path ) {
				if ( 'style' === $section ) {
					$html[ $section ] = '<style>' . $this->buffer( $full_path ) . '</style>';
					continue;
				}

				$html[ $section ] = $this->buffer( $full_path );
			}

			return $html;
		}

		/**
		 * View PDF file.
		 *
		 * @param string $full_path absolute path to PDF file.
		 */
		public static function view( $full_path ) {
			$general_options = get_option( 'mvvps_general_settings' );
			$type            =  'inline' ; /* 'attachment' */

			header( 'Content-type: application/pdf' );
			header( 'Content-Disposition: ' . $type . '; filename="' . basename( $full_path ) . '"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . filesize( $full_path ) );
			header( 'Accept-Ranges: bytes' );

			readfile( $full_path );
			exit;
		}

		/**
		 * Delete pdf document.
		 *
		 * @param string $full_path absolute path to pdf document.
		 */
		protected static function delete( $full_path ) {
			if ( file_exists( $full_path ) ) {
				wp_delete_file( $full_path );
			}
		}

		/**
		 * Full path to pdf invoice.
		 *
		 * @return string full path to pdf invoice.
		 */
		public function get_full_path() {
			return $this->full_path;
		}

		/**
		 * Date format from user option or get default WordPress date format.
		 *
		 * @return string
		 */
		public function get_date_format() {
			$date_format = "F j, Y"; /*$this->template_options['mvvps_date_format'];*/
			if ( ! empty( $date_format ) ) {
				return (string) $date_format;
			}

			return (string) get_option( 'date_format' );
		}

		/**
		 * Order date formatted with user option format and localized.
		 *
		 * @return string
		 */
		public function get_formatted_order_date() {
			// WC backwards compatibility.
			$order_date = MVVPS_WC_Order_Compatibility::get_date_created( $this->order );

			return date_i18n( $this->get_date_format(), strtotime( $order_date ) );
		}

		/**
		 * Check if pdf exists within uploads folder.
		 *
		 * @param string $full_path to pdf file.
		 *
		 * @return bool/string false when pdf does not exist else full path to pdf.
		 */
		public static function exists( $full_path ) {
			if ( ! file_exists( $full_path ) ) {
				return false;
			}

			return $full_path;
		}
	}
}
