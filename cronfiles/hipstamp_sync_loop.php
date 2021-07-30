<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$wp_load = '/var/www/html/site01';

require( $wp_load . '/wp-load.php' );
require_once "memory.php";
//$file = $wp_load."/cronfiles/id_list_new.txt" ;

//update_option("check_cron",time());
define('LIMIT',100);

//$page = $argv[1];
$total_record = 500;
$devide = 100;
$loop_count = $total_record/$devide;
for($i=0;$i<$loop_count;$i++){
    $page_start_value = $i*$devide ;
    $page_end_value = $devide*($i+1) ;
    echo $page_start_value .'--'.$page_end_value;
    $count = $i+1; 
    
}
$page_start_value = 0;
$page_end_value = 100;
$total = 500;
api_llop_function($page_start_value,$page_end_value,$total,1);

function api_llop_function($page_start_value,$page_end_value,$total,$ccn){


    
    $file = "/var/www/html/site01/cronfiles/id_list.txt" ;
    // $page_start_value = 0 ;
    // $page_end_value = 500 ;

   

    for ($page = $page_start_value; $page <= $page_end_value; $page++) 
        {

        //yield $page.'<br>';
            
            if($page!=''){
                $i = trim($page);
                $i = (int)$i;
                //save page number to option
                $option_name = 'page_no' ;
                            // 1. sku not match :---New entry
                            $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit=100&page=' . $i . '&sort=started_desc';
                            
                             $data       =  file_get_contents($url);
                            $characters =  json_decode($data);
                            $total_rec = count($characters->results);
                            $rec_inc = 0;

                            foreach ($characters->results as $kke=>&$character) 
                            {
                                 $character;
                                // $status = update_product_with_object_and_id($character,'',$i);
                               // var_dump($status);
                                //if($status == false){
                                    $fid = trim($character->id);
                                    $fid = (int)$fid;
                                   // $write_content = date("Y-m-d h:i:sa"). '--'.$fid.'--'.formatBytes(memory_get_peak_usage());
                                    $write_content = $fid;
                                    write_txt_file($file, $write_content );
                                //}
                                //print formatBytes(memory_get_peak_usage());
                                    
                                    unset($character);
                                    unset($status);
                                    unset($write_content);
                            } 
                            unset($data);
                            unset($characters);
                
            }


            if($page==$page_end_value){
                echo 'match';
                $next_page = $ccn+1;
                $page_end_value = LIMIT + $page_end_value;
                echo 'call+('.$page.'--'.$page_end_value.'--'.$total.'--'.$next_page.')';
                
                if($next_page < 6 ){ 
                   // $write_content = date("Y-m-d h:i:sa"). '--'.$next_page;
                   // write_txt_file($file, $write_content );
                    api_llop_function($page,$page_end_value,$total,$next_page);
                    
                }else{
                    die();
                }
                if($next_page == 5){ die();}
            }
            //unset
            
            
        }

        unset($page);
        unset($page_end_value);
        unset($next_page);

}
    //   $error = error_get_last();
    //   $write_error_content = serialize($error);
    //   $write_error_content.= memory_get_usage().'--'. $startMemory. '-bytes';
     // write_txt_file($file, $write_error_content );


     