<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Table_Order_Actions extends WP_List_Table {

	var $current_destination = '';

	public function __construct( $args = array() ) {

		parent::__construct( array(
			'singular' => __( 'action', 'woocommerce-order-export' ),
			'plural'   => __( 'actions', 'woocommerce-order-export' ),
			'ajax'     => true
		) );

		$this->_args = array_merge( $this->_args, $args );
	}

	/**
	 * Output the report
	 */
	public function output() {
		$this->prepare_items();
		?>

		<div class="wp-wrap">
			<?php
			$this->display();
			?>
		</div>
		<?php
	}

	public function display_tablenav( $which ) {
		if ( 'top' != $which ) {
			return;
		}
		?>
		<div style="margin-top: 10px;">
			<input type="button" class="button-secondary"
			       value="<?php _e( 'Add Action', 'woocommerce-order-export' ); ?>" data-action="add-order-action">
		</div><br>
		<?php
	}

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = array();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->items = get_option( $this->_args['items'], array() );

		foreach ( $this->items as $index => $item ) {
			$this->items[ $index ][ 'id' ] = $index;
		}
	}

	public function get_columns() {
		$columns                        = array();
		$columns['active']              = __( 'Active', 'woocommerce-order-export' );
		$columns['title']               = __( 'Title', 'woocommerce-order-export' );
		$columns['from_status']         = __( 'From status', 'woocommerce-order-export' );
		$columns['to_status']          	= __( 'To status', 'woocommerce-order-export' );
		$columns['format']          	= __( 'Format', 'woocommerce-order-export' );
		$columns['destination']         = __( 'Destination', 'woocommerce-order-export' );
		$columns['destination_details'] = __( 'Destination Details', 'woocommerce-order-export' );
		$columns['actions']             = __( 'Actions', 'woocommerce-order-export' );

		return $columns;
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'active':
				return "<input type='checkbox' data-action='change-order-action-status' data-id='{$item['id']}' " . ( ! isset( $item['active'] ) || $item['active'] ? 'checked' : '' ) . "/> " . $item[ 'id' ];
			case 'title':
				return '<a href="admin.php?page=wc-order-export&tab=order_actions&wc_oe=edit_action&action_id=' . $item[ 'id' ] . '">' . $item[ $column_name ] . '</a>';
			case 'from_status':
			case 'to_status':
				$data         = array();
				$all_statuses = wc_get_order_statuses();

				$statuses = isset( $item[ $column_name ] ) ? $item[ $column_name ] : array();
				if (  empty( $statuses ) ) {
					$data[] = __( 'Any', 'woocommerce-order-export' );
				} else {
					foreach ( $statuses as $status ) {
						$data[] = $all_statuses[ $status ];
					}
				}

				return implode( ', ', $data );
			case 'destination':
				$this->current_destination = isset( $item['destination']['type'] ) ? $item['destination']['type'] : '';

				return $this->current_destination;
			case 'destination_details':
				if ( $this->current_destination == 'http' ) {
					return esc_html( $item['destination']['http_post_url'] );
				}
				if ( $this->current_destination == 'email' ) {
					return __( 'Subject: ',
						'woocommerce-order-export' ) . esc_html( $item['destination']['email_subject'] ) . "<br>" . __( 'To: ',
						'woocommerce-order-export' ) . esc_html( $item['destination']['email_recipients'] );
				}
				if ( $this->current_destination == 'ftp' ) {
					return esc_html( $item['destination']['ftp_user'] ) . "@" . esc_html( $item['destination']['ftp_server'] ) . $item['destination']['ftp_path'];
				}
				if ( $this->current_destination == 'folder' ) {
					return esc_html( $item['destination']['path'] );
				}
				return '';
			case 'actions':
				return "<div class='button-secondary' title='" . __( 'Edit', 'woocommerce-order-export' ) . "'   data-id='{$item['id']}' data-action='edit-order-action'><span class='dashicons dashicons-edit'></span></div>&nbsp;" .
				       "<div class='button-secondary' title='" . __( 'Copy', 'woocommerce-order-export' ) . "'   data-id='{$item['id']}' data-action='clone-order-action'><span class='dashicons dashicons-admin-page'></span></div>&nbsp;" .
				       "<div class='button-secondary' title='" . __( 'Delete', 'woocommerce-order-export' ) . "' data-id='{$item['id']}' data-action='delete-order-action'><span class='dashicons dashicons-trash'></span></div>&nbsp;";
				break;
			default:
				return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
		}
	}

}
