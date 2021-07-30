<?php
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
global $woocommerce, $wpdb, $product;

   // include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');
    
    // WooCommerce Admin Report
   // $wc_report = new WC_Admin_Report();
    
    // Set date parameters for the current month
    $start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight');
    //$end_date = strtotime('+1month', $start_date) - 86400;
    $end_date = strtotime(date('Y-m-d'));
    
    
    $day_before =  strtotime( date('Y-m-d') . ' -1 day' );
    //echo date( 'Y-m-d',$day_before);
    //$wc_report->start_date = $start_date;
    //$wc_report->end_date = $end_date;
    
    // Avoid max join size error
    //$wpdb->query('SET SQL_BIG_SELECTS=1');
    
    // Get data for current month sold products
    // $sold_products = $wc_report->get_order_report_data(array(
    //     'data' => array(
    //         '_product_id' => array(
    //             'type' => 'order_item_meta',
    //             'order_item_type' => 'line_item',
    //             'function' => '',
    //             'name' => 'product_id'
    //         ),
    //         '_qty' => array(
    //             'type' => 'order_item_meta',
    //             'order_item_type' => 'line_item',
    //             'function' => 'SUM',
    //             'name' => 'quantity'
    //         ),
    //         '_line_subtotal' => array(
    //             'type' => 'order_item_meta',
    //             'order_item_type' => 'line_item',
    //             'function' => 'SUM',
    //             'name' => 'gross'
    //         ),
    //         '_line_total' => array(
    //             'type' => 'order_item_meta',
    //             'order_item_type' => 'line_item',
    //             'function' => 'SUM',
    //             'name' => 'gross_after_discount'
    //         )
    //     ),
    //     'query_type' => 'get_results',
    //     'group_by' => 'product_id',
    //     'where_meta' => '',
    //     'order_by' => 'quantity DESC',
    //     'order_types' => wc_get_order_types('order_count'),
    //     'filter_range' => TRUE,
    //     'order_status' => array('completed'),
    // ));
    
    // List Sales Items
    $args = array(
        'status'   => 'completed',
        
    );
    //'date_created' => '>' . ( time() - HOUR_IN_SECONDS ),
    //DAY_IN_SECONDS
    $orders = wc_get_orders( $args );
    $total_order = count($orders);
    echo '-------';
    if($total_order > 0){
        /**get order */
        foreach ($orders as $orders_details) { 
            //echo $orders_details->id.'<br>';
            $items = $orders_details->get_items();
            /**get order item */

           
            $to_time = current_time( 'timestamp', true );
$from_time = $orders_details->get_date_completed()->getTimestamp();
$min_diff_with_current = round(abs($to_time - $from_time) / 60,2);
            var_dump($min_diff_with_current);
            if($min_diff_with_current < 2){
                foreach ( $orders_details->get_items() as $item_id => $item_values ) {
                    $item_data = $item_values->get_data();
                    $product_id = $item_data['product_id'];
                    $stock = get_post_meta( $product_id, '_stock', true );
                        //echo $stock;
                        $cmd = shell_exec("php /var/www/html/site01/cronfiles/update_product_quantity_to_hipstamp.php 'product_id=".$product_id."&quantity=".$stock."'  >/dev/null 2>&1 &");
                }
            }
           
           // echo human_time_diff( $orders_details->get_date_created()->getTimestamp(), current_time( 'timestamp', true )).'<br>';
           
           // echo $completed_time.'<br>';
            
        }
    }
    echo '-------';
   // die();

