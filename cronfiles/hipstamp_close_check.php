<?php 
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$file = $wp_load."/cronfiles/newfile.txt" ;

// update_option("check_cron",time());
$tt = parse_str($argv[1],$output);

$page_no = $output['page'];
$w_content = 'Page no : '.$page_no.'';

write_txt_file($file,$w_content);
 
 
    if($page_no!=''){
        $i = $page_no;
        //save page number to option
        $option_name = 'page_no' ;

            
                
                    // 1. sku not match :---New entry
                    //$url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
                    $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/closed/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
                    $data       =  file_get_contents($url);
                    $characters =  json_decode($data);
                    $total_rec = count($characters->results);
                    $rec_inc = 0; 
                    foreach ($characters->results as $character) 
                    {

                            /***********************HIPSTAMP CHANGE OR UPDATE OR DELETE OR NEW********************************************************** */
                            // search with sku & Hipstamp ID . 
                            // 1. sku not match :---New entry
                            // 2. sku match but deleted:- delete entry
                            // 3. sku match not deleted:- update the entry
                            
                            $day   = date('Y-m-d', strtotime($character->updated_at));
                            $today = date('Y-m-d');
                            global $product;
                            // echo $character->name . '<br>';
                            $product = get_product_by_sku($character->id);
                            
                                if (is_null($product)) {
                                    
                                    $date = 'Not Found : '.$character->id .' '.$character->name;
                                }else {
                                    
                                    $date = 'Found : '.$character->id.' '.$character->name;   
                                    
                                }
                                $file = "/var/www/html/site01/closefile.txt" ;

                                    date_default_timezone_set('Australia/Melbourne');
                                    //$date = date('m/d/Y h:i:s a', time());
                                    

                                    file_put_contents($file, $date . "\n", FILE_APPEND);

                                    // $linecount = 0;
                                    // $handle = fopen($file, "r") ;
                                    // $allContent = array();
                                    //     while(!feof($handle)){
                                    //     $line = fgets($handle);
                                        
                                    //         if($line){
                                    //             $line = str_replace("\n", "", $line);
                                    //             $allContent[] .= $line;
                                    //         }
                                            
                                    //         $linecount++;
                                            
                                    //         echo $date.'<br>';
                                    //     }
                                    
                                    // $allContent[] = $date;
                                    
                                    // $handle = fopen($file, "w") ;
                                    // $allContent = implode("\n",$allContent);
                                    // fwrite($handle, $allContent );
                                    // fclose($handle); 

                        //$rec_inc++;
                    }
          
}




// function get_product_by_sku($sku)
// {
  
//     global $wpdb;
    
//     $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    
//     if ($product_id)
//         return new WC_Product($product_id);
    
//     return null;
// }




