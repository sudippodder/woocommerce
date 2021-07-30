<?php
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$previous_day = date('Y-m-d',strtotime("-1 days"));
//$previous_day = '2019-09-05';
$url = 'https://www.hipstamp.com/api/stores/Antonios/listings/closed/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&start_time_from='.$previous_day.'&show=sold';
echo $url;
$data       =  file_get_contents($url);
$characters =  json_decode($data);
$itemsPerPage = 100;

$items = $characters->count;
$pages = ceil($items/$itemsPerPage);

//var_dump($characters);
if($characters->count>0){
    //var_dump($characters);
    foreach ($characters->results as $character) 
    {
        $wp_product_id = get_product_by_sku( $character->id );
        //echo $wp_product_id.'<br>';
        //var_dump($wp_product_id);
        $stock = get_post_meta( $wp_product_id, '_stock', true );
        //echo $stock.'<br>';
        if($stock!=0){
            echo 'Stock : '.$stock.'<br>';
            update_post_meta( $wp_product_id, '_stock', $character->quantity );
        }
        //echo $character->id.'<br>';
       //echo $character->quantity.'<br>';

    }
    // for ($i = 1; $i < 3; $i++) 
    // {

    //     // $cmd = shell_exec("php /var/www/html/site01/cronfiles/insert_previous_day.php 'page=".$i."'  >/dev/null 2>&1 &");
    //     //var_dump($cmd);
        
    // }
}


// function get_product_by_sku( $sku ) {

//     global $wpdb;

//     $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

//     if ( $product_id ){
//         return $product_id;
//         //return new WC_Product( $product_id );
//     }else{
//         return null;
//     }
// }

