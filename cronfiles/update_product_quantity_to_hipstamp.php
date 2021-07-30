<?php
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$file = $wp_load."/cronfiles/update_hipstamp.txt" ;

$tt = parse_str($argv[1],$output);

$product_id = $output['product_id'];
$quantity = $output['quantity'];



define('LIMIT',100);

if($product_id!='' && $quantity!=''){
    
    $product_sku = get_post_meta( $product_id, '_sku', true );
    
    $command = "curl -X PUT -d quantity=".$quantity." https://www.hipstamp.com/api/listings/".$product_sku."/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit";
    $cmd = shell_exec($command);
    $w_content = 'Product Deails : '.$quantity.' ('.$product_id.')('.$product_sku.')'.$command.'---'. $cmd->results->id;
    write_txt_file($file,$w_content);
    $i = $page_no;
}



