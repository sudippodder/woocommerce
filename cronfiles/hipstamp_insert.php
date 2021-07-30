<?php 
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );


update_option("check_cron",time());
define('LIMIT',100);


$tt = parse_str($argv[1],$output);
$page_no = $output['page'];


if($page_no!=''){
    $i = $page_no;
    //save page number to option
    $option_name = 'page_no' ;
    // The option already exists, so we just update it.
      
                // 1. sku not match :---New entry
                $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
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
                        $day   = 1;
                        $today = 1;
                        global $product;
                        //var_dump($character);
                        // echo $character->name . '<br>';
                       $status = update_product_with_object_and_id($character);
                        var_dump($status);
                        // $product = get_product_by_sku($character->id);

                        // if($product == ''){
                            
                        //     $product_details = get_post_type( $character->id );
                        //     if($product_details=='product'){
                        //         $product = $character->id;
                        //     }
                
                        // }
                        

                        
                    $rec_inc++;

                   
                }
                echo $total_rec.'=='.$rec_inc.'--';
                if($total_rec==$rec_inc){
                    update_option( $option_name, $i );
                }
                
            
            
                
            
        
    
}


