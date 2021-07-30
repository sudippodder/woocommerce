<?php 
$projectF = 'site01';
//$projectF = 'woocommerce';
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
update_option("check_cron",time());
define('LIMIT',100);
$tt = parse_str($argv[1],$output);
$page_no = $output['page'];

$page_no = 1;


if($page_no!=''){
    $i = $page_no;
    $option_name = 'page_no' ;
    
    $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
    $data       =  file_get_contents($url);
    //echo $url;
    $characters =  json_decode($data);
    $total_rec = count($characters->results);
    $rec_inc = 0; 
   // var_dump($characters);die();
    foreach ($characters->results as $character) 
    {
        $sku = $character->id;
        $product_id = get_product_by_sku($sku);
        $write = $page_no;
        //var_dump($character);
        if(isset($product_id) && $product_id != '' && $product_id > 0){
            //update
            $write = $product_id;
        }else{
            //insert
            $write = '--inser -- '.$sku;
        }
        write_file($write);
    }
}


function write_file($add_content){
    $file = "/var/www/html/site01/test.txt" ;
    date_default_timezone_set('Australia/Melbourne');
    //$date = date('m/d/Y h:i:s a', time());
    $date = $add_content;
    $linecount = 0;
    $handle = fopen($file, "r") ;
    $allContent = array();
        while(!feof($handle)){
        $line = fgets($handle);
        if($line){
            $line = str_replace("\n", "", $line);
            $allContent[] .= $line;
        }
            $linecount++;
        }
    $allContent[] = $date;
    $handle = fopen($file, "w") ;
    $allContent = implode("\n",$allContent);
    fwrite($handle, $allContent );
    fclose($handle); 
}

