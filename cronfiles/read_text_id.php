<?php 

$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );


update_option("check_cron",time());
define('LIMIT',100);




$args = array(
	'post_type'              => array( 'product' ),
	'order'                  => 'ASC',
	'orderby'                => 'title',
	'meta_query'             => array(
		array(
			'key'     => 'custom_field_check_for_api',
			'value'   => 1,
			'compare' => '='
		)
	),
);

// The Query
$query = new WP_Query( $args );
var_dump($query);

// var_dump($query->post_count);
// die();



$handle = fopen("./id_list/id_list5.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        
        $sku_id = trim($line);
        //var_dump($sku_id);
        $get_product_id = get_product_by_sku($sku_id);
        
        if(empty($get_product_id)){
            echo 'blank_product_id.<br>';
        }else{
           $status = cfwc_save_custom_field( $get_product_id );
           var_dump($status);
          //$status_id_value = get_post_meta(get_the_ID(), 'custom_field_check_for_api');
          //var_dump($status_id_value);
           //die(); 
        }
       // 
      echo '-'; 
        var_dump($get_product_id);

    }

    fclose($handle);
} else {
    // error opening the file.
} 



