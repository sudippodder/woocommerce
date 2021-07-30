<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('WC_Deposits_Admin_List_Table_Orders')):


class WC_Deposits_Admin_List_Table_Orders {

    private $bulk_ids = array();
    public $partial_payments_list_table;
	/**
	 * Constructor.
	 */
	public function __construct( &$wc_deposits ) {
		add_filter( 'bulk_actions-edit-shop_order', array( $this, 'order_bulk_actions' ), 10, 1 );
		add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk_actions' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'bulk_admin_notices' ) );


		if(get_option('wc_deposits_order_list_table_show_has_deposit','no') === 'yes'){
            add_filter('manage_edit-shop_order_columns',array($this,'add_has_deposit_column'));
            add_action( 'manage_shop_order_posts_custom_column', array($this,'populate_has_deposit_column' ));
        }

        // Load correct list table classes for current screen.
        add_action( 'current_screen', array( $this, 'setup_screen' ) );
        add_action( 'check_ajax_referer', array( $this, 'setup_screen' ) );


	}
 
    public function setup_screen() {

        $screen_id = false;

        if ( function_exists( 'get_current_screen' ) ) {
            $screen    = get_current_screen();
            $screen_id = isset( $screen, $screen->id ) ? $screen->id : '';
        }

        if ( ! empty( $_REQUEST['screen'] ) ) { // WPCS: input var ok.
            $screen_id = wc_clean( wp_unslash( $_REQUEST['screen'] ) ); // WPCS: input var ok, sanitization ok.
        }

        if($screen_id === 'edit-wcdp_payment' && class_exists('WC_Deposits_Admin_List_Table_Partial_Payments')){
            include_once 'class-wc-deposits-admin-list-table-partial-payments.php';
            $this->partial_payments_list_table = new WC_Deposits_Admin_List_Table_Partial_Payments();
        }

        // Ensure the table handler is only loaded once. Prevents multiple loads if a plugin calls check_ajax_referer many times.
        remove_action( 'current_screen', array( $this, 'setup_screen' ) );
        remove_action( 'check_ajax_referer', array( $this, 'setup_screen' ) );
    }

	function add_has_deposit_column($columns){

        $new_columns = array();

        $screen = get_current_screen();
        if($screen && $screen->id === 'edit-shop_order' && isset($_GET['post_status']) && $_GET['post_status'] === 'trash'){
            return $columns;
        }
        foreach($columns as $key => $column){

            if($key === 'order_total'){
                $new_columns['wc_deposits_has_deposit'] = __('Has Deposit','woocommerce-deposits');
            }
            $new_columns[$key] = $column;



        }

        return $new_columns;

    }

    function populate_has_deposit_column($column){

        if ( 'wc_deposits_has_deposit' === $column ) {
            global $post;
            $order = wc_get_order($post->ID);
            if($order){
                $order_has_deposit = $order->get_meta( '_wc_deposits_order_has_deposit' , true );

                if($order_has_deposit === 'yes'){
                    echo '<span class="button wcdp_has_deposit">&#10004; Yes</span>';
                } else {
                    echo '<span class="button wcdp_no_deposit"> &#10006; No</span>';
                }
            }
        }

    }


	public function order_bulk_actions( $actions ) {
		$actions['mark_partially_paid'] = __( 'Mark partially paid', 'woocommerce-deposits' );
		return $actions;
	}

	function handle_bulk_actions( $redirect_to, $action, $ids ) {
 		if( $action == 'mark_partially_paid' ) {
			$changed = 0;
			
			foreach ( $ids as $id ) {
				$order = new WC_Order( $id );
				$order->update_status( 'partially-paid', __( 'Order status changed by bulk edit:', 'woocommerce-deposits' ) );
				$changed++;
			}

			$redirect_to = add_query_arg(
				array(
					'post_type'             => 'shop_order',
					'marked_partially_paid' => true,
					'changed'               => $changed,
				), $redirect_to
			);
		}

		return $redirect_to;
	}

	/**
	 * Show confirmation message that order status changed for number of orders.
	 */	 
	function bulk_admin_notices() {
		global $post_type, $pagenow;

		// Exit if not on shop order list page.
		if ( 'edit.php' !== $pagenow || 'shop_order' !== $post_type ) {
			return;
		}
		
		if ( isset( $_REQUEST['marked_partially_paid'] ) ) {
			$number = isset( $_REQUEST['changed'] ) ? absint( $_REQUEST['changed'] ) : 0;
			if ( 'edit.php' == $pagenow && 'shop_order' == $post_type ) {
				$message = sprintf( _n( 'Order status changed.', '%s order statuses changed.', $number ), number_format_i18n( $number ) );
				echo '<div class="updated"><p>' . $message . '</p></div>';
			}
		}
	}

}

endif;